<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AcademicOptionController extends Controller
{
    public function index()
    {
        $courses = Course::withCount(['branches' => fn ($q) => $q->where('status', true)])
            ->orderBy('name')
            ->get();

        $branches = Branch::with('course')->orderBy('course_id')->orderBy('name')->get();

        return view('admin.academic.index', compact('courses', 'branches'));
    }

    public function storeCourse(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:courses,name'],
            'status' => ['sometimes', 'boolean'],
        ]);

        Course::create([
            'name' => trim($data['name']),
            'status' => $request->boolean('status', true),
        ]);

        return back()->with('status', 'Course added successfully.');
    }

    public function updateCourse(Request $request, Course $course)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('courses', 'name')->ignore($course->id)],
            'status' => ['sometimes', 'boolean'],
        ]);

        $course->update([
            'name' => trim($data['name']),
            'status' => $request->boolean('status'),
        ]);

        return back()->with('status', 'Course updated successfully.');
    }

    public function toggleCourse(Course $course)
    {
        $course->update(['status' => ! $course->status]);

        return back()->with('status', $course->status ? 'Course activated.' : 'Course deactivated.');
    }

    public function storeBranch(Request $request)
    {
        $data = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'name' => [
                'required', 'string', 'max:150',
                Rule::unique('branches', 'name')->where(fn ($q) => $q->where('course_id', $request->integer('course_id'))),
            ],
            'status' => ['sometimes', 'boolean'],
        ]);

        Branch::create([
            'course_id' => $data['course_id'],
            'name' => trim($data['name']),
            'status' => $request->boolean('status', true),
        ]);

        return back()->with('status', 'Branch added successfully.');
    }

    public function updateBranch(Request $request, Branch $branch)
    {
        $courseId = $request->integer('course_id');

        $data = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'name' => [
                'required', 'string', 'max:150',
                Rule::unique('branches', 'name')
                    ->where(fn ($q) => $q->where('course_id', $courseId))
                    ->ignore($branch->id),
            ],
            'status' => ['sometimes', 'boolean'],
        ]);

        $branch->update([
            'course_id' => $data['course_id'],
            'name' => trim($data['name']),
            'status' => $request->boolean('status'),
        ]);

        return back()->with('status', 'Branch updated successfully.');
    }

    public function toggleBranch(Branch $branch)
    {
        $branch->update(['status' => ! $branch->status]);

        return back()->with('status', $branch->status ? 'Branch activated.' : 'Branch deactivated.');
    }

    public function branches(Course $course)
    {
        return response()->json(
            $course->branches()
                ->where('status', true)
                ->orderBy('name')
                ->get(['id', 'name'])
        );
    }
}
