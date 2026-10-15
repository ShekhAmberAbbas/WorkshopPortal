<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCertificateTemplateRequest;
use App\Http\Requests\UpdateCertificateTemplateRequest;
use App\Models\CertificateTemplate;
use App\Services\CertificateService;
use Illuminate\Support\Facades\Storage;

class CertificateTemplateController extends Controller
{
    public function __construct(protected CertificateService $certificateService)
    {
    }

    public function index()
    {
        $templates = CertificateTemplate::withCount(['certificates', 'workshops'])->latest()->get();

        return view('admin.certificate-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.certificate-templates.create');
    }

    public function store(StoreCertificateTemplateRequest $request)
    {
        // Store on the PRIVATE disk — template PDFs are source material for
        // generation and are never served to the public directly.
        $path = $request->file('file')->store('certificate-templates', 'local');

        CertificateTemplate::create([
            'name' => $request->validated('name'),
            'file_path' => $path,
            'configuration' => $request->input('config', []),
            'status' => $request->boolean('status', true),
        ]);

        return redirect()
            ->route('admin.certificate-templates.index')
            ->with('status', 'Certificate template uploaded.');
    }

    public function edit(CertificateTemplate $certificateTemplate)
    {
        return view('admin.certificate-templates.edit', ['template' => $certificateTemplate]);
    }

    public function update(UpdateCertificateTemplateRequest $request, CertificateTemplate $certificateTemplate)
    {
        $data = [
            'name' => $request->validated('name'),
            'configuration' => $request->input('config', []),
            'status' => $request->boolean('status', true),
        ];

        if ($request->hasFile('file')) {
            Storage::disk('local')->delete($certificateTemplate->file_path);
            $data['file_path'] = $request->file('file')->store('certificate-templates', 'local');
        }

        $certificateTemplate->update($data);

        return redirect()
            ->route('admin.certificate-templates.index')
            ->with('status', 'Certificate template updated.');
    }

    public function destroy(CertificateTemplate $certificateTemplate)
    {
        if ($certificateTemplate->certificates()->exists()) {
            return back()->with('status', 'Cannot delete a template that already has certificates generated from it — deactivate it instead.');
        }

        Storage::disk('local')->delete($certificateTemplate->file_path);
        $certificateTemplate->delete();

        return redirect()
            ->route('admin.certificate-templates.index')
            ->with('status', 'Certificate template deleted.');
    }

    public function preview(CertificateTemplate $certificateTemplate)
    {
        $pdf = $this->certificateService->previewPdf($certificateTemplate);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="preview.pdf"',
        ]);
    }
}
