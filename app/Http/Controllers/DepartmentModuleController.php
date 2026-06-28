<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DepartmentModuleController extends Controller
{
    public function academicTeacher(): View
    {
        $this->authorizeRole('academic_teacher');

        return view('modules.academic_teacher', ['user' => auth()->user()]);
    }

    public function schoolCompilation(): View
    {
        $this->authorizeRole('academic_teacher');

        return view('modules.school_compilation', ['user' => auth()->user()]);
    }

    public function missingMarks(): View
    {
        $this->authorizeRole('academic_teacher');

        return view('modules.missing_marks', ['user' => auth()->user()]);
    }

    public function officialTemplate(): View
    {
        $this->authorizeRole('academic_teacher');

        return view('modules.official_template', ['user' => auth()->user()]);
    }

    public function headTeacher(): View
    {
        $this->authorizeRole('headmaster');

        return view('modules.head_teacher', ['user' => auth()->user()]);
    }

    public function verifySchoolResults(): View
    {
        $this->authorizeRole('headmaster');

        return view('modules.verify_school_results', ['user' => auth()->user()]);
    }

    public function approvedReports(): View
    {
        $this->authorizeRole('headmaster');

        return view('modules.approved_reports', ['user' => auth()->user()]);
    }

    public function weo(): View
    {
        $this->authorizeRole('weo');

        return view('modules.weo', ['user' => auth()->user()]);
    }

    public function wardSubmissions(): View
    {
        $this->authorizeRole('weo');

        return view('modules.ward_submissions', ['user' => auth()->user()]);
    }

    public function wardCompilation(): View
    {
        $this->authorizeRole('weo');

        return view('modules.ward_compilation', ['user' => auth()->user()]);
    }

    public function wardTemplateExport(): View
    {
        $this->authorizeRole('weo');

        return view('modules.ward_template_export', ['user' => auth()->user()]);
    }

    public function admin(): View
    {
        $this->authorizeRole('admin');

        return view('modules.admin', ['user' => auth()->user()]);
    }

    public function manageSchools(): View
    {
        $this->authorizeRole('admin');

        return view('modules.manage_schools', ['user' => auth()->user()]);
    }

    public function manageTemplates(): View
    {
        $this->authorizeRole('admin');

        $templates = collect(Storage::disk('local')->files('templates'))
            ->map(fn (string $path) => [
                'path' => $path,
                'name' => basename($path),
                'size' => Storage::disk('local')->size($path),
                'updated_at' => Storage::disk('local')->lastModified($path),
            ])
            ->sortBy('name')
            ->values();

        return view('modules.manage_templates', [
            'user' => auth()->user(),
            'templates' => $templates,
        ]);
    }

    public function uploadTemplate(Request $request): RedirectResponse
    {
        $this->authorizeRole('admin');

        $request->validate([
            'template' => ['required', 'file', 'mimes:pdf,xlsx,xls,docx', 'max:10240'],
        ]);

        $file = $request->file('template');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileName = str($originalName)->slug()->append('-'.now()->format('YmdHis').'.'.$extension)->toString();

        $file->storeAs('templates', $fileName, 'local');

        return redirect()
            ->route('admin.templates')
            ->with('status', 'Official template uploaded successfully.');
    }

    public function downloadTemplate(string $fileName)
    {
        $this->authorizeRole('admin');

        $safeName = basename($fileName);
        $path = 'templates/'.$safeName;

        abort_unless(Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path);
    }

    private function authorizeRole(string $role): void
    {
        /** @var User $user */
        $user = auth()->user();

        abort_unless($user->role === $role && $user->role_status === 'approved', 403);
    }
}
