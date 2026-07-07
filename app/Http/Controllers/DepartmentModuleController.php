<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
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

        $user = auth()->user()->load('school');
        $classes = SchoolClass::where('school_id', $user->school_id)
            ->withCount('learners')
            ->orderBy('name')
            ->orderBy('stream')
            ->get();
        $subjects = Subject::orderBy('name')->get();

        return view('modules.head_teacher', compact('user', 'classes', 'subjects'));
    }

    public function storeSubject(Request $request): RedirectResponse
    {
        $this->authorizeRole('headmaster');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:subjects,name'],
            'code' => ['nullable', 'string', 'max:30', 'unique:subjects,code'],
        ]);

        Subject::create($data);

        return redirect()
            ->route('module.headTeacher')
            ->with('status', 'Subject added successfully.');
    }

    public function storeClass(Request $request): RedirectResponse
    {
        $this->authorizeRole('headmaster');

        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'stream' => ['nullable', 'string', 'max:50'],
        ]);

        SchoolClass::firstOrCreate([
            'school_id' => $user->school_id,
            'name' => $data['name'],
            'stream' => $data['stream'] ?: 'main',
        ]);

        return redirect()
            ->route('module.headTeacher')
            ->with('status', 'Class added successfully.');
    }

    public function uploadSubjects(Request $request): RedirectResponse
    {
        $this->authorizeRole('headmaster');

        $request->validate([
            'subjects_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $imported = 0;

        foreach ($this->readCsvRows($request->file('subjects_file')->getRealPath()) as $row) {
            $name = trim($row['name'] ?? $row['subject'] ?? '');
            $code = trim($row['code'] ?? $row['subject_code'] ?? '');

            if ($name === '') {
                continue;
            }

            Subject::updateOrCreate(
                ['name' => $name],
                ['code' => $code !== '' ? $code : null]
            );

            $imported++;
        }

        return redirect()
            ->route('module.headTeacher')
            ->with('status', "{$imported} subject(s) uploaded successfully.");
    }

    public function uploadClasses(Request $request): RedirectResponse
    {
        $this->authorizeRole('headmaster');

        $request->validate([
            'classes_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $user = $request->user();
        $imported = 0;

        foreach ($this->readCsvRows($request->file('classes_file')->getRealPath()) as $row) {
            $name = trim($row['name'] ?? $row['class'] ?? $row['class_name'] ?? '');
            $stream = trim($row['stream'] ?? '');

            if ($name === '') {
                continue;
            }

            SchoolClass::firstOrCreate([
                'school_id' => $user->school_id,
                'name' => $name,
                'stream' => $stream !== '' ? $stream : 'main',
            ]);

            $imported++;
        }

        return redirect()
            ->route('module.headTeacher')
            ->with('status', "{$imported} class(es) uploaded successfully.");
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

    private function readCsvRows(string $path): array
    {
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return [];
        }

        $headers = null;
        $rows = [];

        while (($data = fgetcsv($handle)) !== false) {
            $data = array_map(fn ($value) => trim((string) $value), $data);

            if ($data === [] || implode('', $data) === '') {
                continue;
            }

            if ($headers === null) {
                $headers = array_map(
                    fn ($header) => str($header)->lower()->replace([' ', '-'], '_')->toString(),
                    $data
                );
                continue;
            }

            $rows[] = array_combine($headers, array_pad($data, count($headers), ''));
        }

        fclose($handle);

        return $rows;
    }
}
