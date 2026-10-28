<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantRequest;
use App\Models\Branch;
use App\Models\Course;
use App\Models\Participant;
use App\Models\Workshop;
use App\Services\CertificateService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class WorkshopRegistrationController extends Controller
{
    public function __construct(protected CertificateService $certificateService)
    {
    }

    public function show(Workshop $workshop)
    {
        if ($message = $workshop->registrationUnavailableMessage()) {
            $isEarlyVisit = $workshop->status === 'active' && $workshop->isBeforeStartDate();
            $status = $isEarlyVisit ? 200 : ($workshop->status === 'active' ? 503 : 404);

            return $this->unavailableResponse($workshop, $message, $status);
        }

        $isFull = $workshop->max_participants
            && $workshop->participants()->count() >= $workshop->max_participants;

        $courses = Course::query()
            ->where('status', true)
            ->with(['branches' => fn ($q) => $q->where('status', true)->orderBy('name')])
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('public.workshop.register', compact('workshop', 'isFull', 'courses'));
    }

    public function branches(Course $course)
    {
        abort_unless($course->status, 404);

        return response()->json(
            $course->branches()->where('status', true)->orderBy('name')->get(['id', 'name'])
        );
    }

    public function store(StoreParticipantRequest $request, Workshop $workshop)
    {
        if ($message = $workshop->registrationUnavailableMessage()) {
            $isEarlyVisit = $workshop->status === 'active' && $workshop->isBeforeStartDate();
            $status = $isEarlyVisit ? 200 : ($workshop->status === 'active' ? 503 : 404);

            return $this->unavailableResponse($workshop, $message, $status);
        }

        $validated = $request->validated();
        $feedback = collect(config('workshop_feedback.questions', []))
            ->pluck('key')
            ->mapWithKeys(fn ($key) => [$key => $validated['feedback_' . $key] ?? null])
            ->all();
        $feedbackProvided = collect($feedback)->contains(fn ($value) => filled($value));

        try {
            $participant = DB::transaction(function () use ($workshop, $validated, $feedback, $feedbackProvided) {
                $lockedWorkshop = Workshop::whereKey($workshop->id)->lockForUpdate()->firstOrFail();

                if ($message = $lockedWorkshop->registrationUnavailableMessage()) {
                    throw new \RuntimeException($message);
                }

                if ($lockedWorkshop->max_participants && $lockedWorkshop->participants()->count() >= $lockedWorkshop->max_participants) {
                    throw new \RuntimeException('This workshop has reached its maximum number of participants.');
                }

                $course = Course::whereKey($validated['course_id'])->where('status', true)->firstOrFail();
                $branch = Branch::whereKey($validated['branch_id'])
                    ->where('course_id', $course->id)
                    ->where('status', true)
                    ->firstOrFail();

                $participant = $lockedWorkshop->participants()->create([
                    'name' => mb_strtoupper(trim($validated['name']), 'UTF-8'),
                    'email' => strtolower(trim($validated['email'])),
                    'mobile' => preg_replace('/\D+/', '', $validated['mobile']),
                    'whatsapp_number' => preg_replace('/\D+/', '', $validated['whatsapp_number']),
                    'home_state' => $validated['home_state'],
                    'home_district' => $validated['home_district'],
                    'course_id' => $course->id,
                    'branch_id' => $branch->id,
                    'course' => $course->name,
                    'branch' => $branch->name,
                    'year' => $validated['year'],
                    'college_name' => $lockedWorkshop->college_name,
                    'enrollment_number' => $validated['enrollment_number'],
                    'enrollment_id' => $lockedWorkshop->nextEnrollmentId(),
                    'feedback_workshop_rating' => $feedback['workshop_rating'],
                    'feedback_previous_quaere_training' => $feedback['previous_quaere_training'],
                    'feedback_trainee_interest' => $feedback['trainee_interest'],
                    'feedback_program_interest' => $feedback['program_interest'],
                    'feedback_practical_oriented' => $feedback['practical_oriented'],
                    'feedback_industry_relevant' => $feedback['industry_relevant'],
                    'feedback_speaker' => $feedback['speaker'],
                    'feedback_speaker_rating' => $feedback['speaker_rating'],
                    'feedback_speaker_answered_questions' => $feedback['speaker_answered_questions'],
                    'feedback_comments' => filled($feedback['comments'] ?? null) ? trim($feedback['comments']) : null,
                    'feedback_submitted_at' => $feedbackProvided ? now() : null,
                ]);

                // Registration is only committed when its certificate can be
                // generated from the template assigned to this workshop.
                $this->certificateService->generateForRegistration($participant);

                return $participant;
            });
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()
                    ->withErrors(['registration' => 'You have already registered for this workshop with this email or mobile number.'])
                    ->withInput();
            }

            throw $e;
        } catch (\RuntimeException $e) {
            return back()->withErrors(['registration' => $e->getMessage()])->withInput();
        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors([
                'registration' => 'Registration could not be completed because the certificate could not be prepared. Please try again or contact the workshop organizer.',
            ])->withInput();
        }

        return redirect()->route('workshop.register.success', [$workshop, $participant]);
    }

    public function success(Workshop $workshop, Participant $participant)
    {
        abort_unless($participant->workshop_id === $workshop->id, 404);

        $participant->load('certificate');

        return view('public.workshop.success', compact('workshop', 'participant'));
    }

    protected function unavailableResponse(Workshop $workshop, ?string $availabilityMessage = null, int $status = 404)
    {
        return response()->view('public.workshop.unavailable', compact('workshop', 'availabilityMessage'), $status);
    }
}
