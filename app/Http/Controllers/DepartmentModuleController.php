<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Exam;
use App\Models\Learner;
use App\Models\Mark;
use App\Models\ResultSubmission;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DepartmentModuleController extends Controller
{
    public function academicTeacher(): View
    {
        $this->authorizeRole('academic_teacher');

        return view('modules.academic_teacher', $this->academicSummary());
    }

    public function schoolCompilation(): View
    {
        $this->authorizeRole('academic_teacher');

        return view('modules.school_compilation', $this->academicSummary());
    }

    public function missingMarks(): View
    {
        $this->authorizeRole('academic_teacher');

        return view('modules.missing_marks', $this->academicSummary());
    }

    public function learnerRegister(): View
    {
        $this->authorizeRole('academic_teacher');

        $data = $this->academicSummary();
        $learnerSearch = trim((string) request('learner_search', ''));

        if ($learnerSearch !== '') {
            $data['learners'] = $data['learners']->filter(fn (Learner $learner) => str($learner->name)->lower()->contains(str($learnerSearch)->lower())
                || str($learner->admission_number)->lower()->contains(str($learnerSearch)->lower())
                || str($learner->gender)->lower()->contains(str($learnerSearch)->lower())
                || str($learner->schoolClass?->name)->lower()->contains(str($learnerSearch)->lower())
                || str($learner->schoolClass?->stream)->lower()->contains(str($learnerSearch)->lower())
            )->values();
        }

        $data['learnerSearch'] = $learnerSearch;

        return view('modules.learner_register', $data);
    }

    public function teacherSubmissions(): View
    {
        $this->authorizeRole('academic_teacher');

        return view('modules.teacher_submissions', $this->academicSummary());
    }

    public function learnerPerformance(): View
    {
        $this->authorizeRole('academic_teacher');

        $data = $this->academicSummary();
        $learnerSearch = trim((string) request('learner_search', ''));
        $classId = request('school_class_id');

        if ($learnerSearch !== '' || $classId) {
            $data['learnerPerformance'] = $data['learnerPerformance']->filter(function (array $item) use ($learnerSearch, $classId) {
                /** @var Learner $learner */
                $learner = $item['learner'];
                $matchesClass = ! $classId || $learner->school_class_id === (int) $classId;
                $matchesSearch = $learnerSearch === ''
                    || str($learner->name)->lower()->contains(str($learnerSearch)->lower())
                    || str($learner->admission_number)->lower()->contains(str($learnerSearch)->lower())
                    || str($learner->schoolClass?->name)->lower()->contains(str($learnerSearch)->lower())
                    || str($learner->schoolClass?->stream)->lower()->contains(str($learnerSearch)->lower());

                return $matchesClass && $matchesSearch;
            })->values();
        } else {
            $data['learnerPerformance'] = $data['learnerPerformance']->take(50);
        }

        $data['learnerSearch'] = $learnerSearch;
        $data['selectedClassId'] = $classId ? (int) $classId : null;
        $data['limitedLearnerPerformance'] = $learnerSearch === '' && ! $classId;

        return view('modules.learner_performance', $data);
    }

    public function resultsRanking(Request $request): View
    {
        $this->authorizeAcademicResultsAccess();

        return view('modules.results_ranking', $this->resultsRankingData($request));
    }

    public function downloadResultsRanking(Request $request)
    {
        $this->authorizeAcademicResultsAccess();

        $data = $this->resultsRankingData($request);
        $fileName = str($data['selectedClass']?->name ?? 'school-results')
            ->append('-ranking.csv')
            ->slug()
            ->toString();

        return response()->streamDownload(function () use ($data) {
            $output = fopen('php://output', 'w');
            fputcsv($output, array_merge(
                ['position', 'admission_number', 'learner', 'class', 'total', 'average', 'submitted', 'missing'],
                $data['subjects']->pluck('name')->all()
            ));

            foreach ($data['rankedLearners'] as $item) {
                fputcsv($output, array_merge(
                    [
                        $item['position'],
                        $item['learner']->admission_number,
                        $item['learner']->name,
                        $item['class_name'],
                        $item['total'],
                        $item['average'],
                        $item['submitted'],
                        $item['missing'],
                    ],
                    $item['subject_marks']->map(fn ($mark) => $mark?->score)->all()
                ));
            }

            fclose($output);
        }, $fileName);
    }

    public function resultsComparison(Request $request): View
    {
        $this->authorizeRole('academic_teacher');

        return view('modules.results_comparison', $this->resultsComparisonData($request));
    }

    public function officialTemplate(): View
    {
        $this->authorizeRole('academic_teacher');

        return view('modules.official_template', $this->academicSummary());
    }

    public function submitSchoolCompilation(): RedirectResponse
    {
        $this->authorizeRole('academic_teacher');

        $data = $this->academicSummary();

        abort_if($data['exam'] === null, 422, 'No exam is available for submission.');
        abort_if($data['missingMarksCount'] > 0, 422, 'Complete missing marks before submitting to the Head Teacher.');

        ResultSubmission::updateOrCreate(
            [
                'school_id' => $data['user']->school_id,
                'exam_id' => $data['exam']->id,
                'level' => 'school',
            ],
            [
                'status' => 'submitted',
                'submitted_by' => $data['user']->id,
                'submitted_at' => now(),
            ]
        );

        return redirect()
            ->route('academicTeacher.schoolCompilation')
            ->with('status', 'School compilation submitted to the Head Teacher for verification.');
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
        $exam = Exam::where('school_id', $user->school_id)
            ->latest('year')
            ->latest('id')
            ->first();
        $teacherSearch = trim((string) request('teacher_search', ''));
        $learners = Learner::with('schoolClass')
            ->where('school_id', $user->school_id)
            ->orderBy('name')
            ->get();
        $pendingUsers = User::with(['school', 'teachingSubject', 'teachingClasses'])
            ->whereIn('role', ['academic_teacher', 'teacher'])
            ->where('role_status', 'pending')
            ->where('school_id', $user->school_id)
            ->orderBy('name')
            ->get();
        $teachers = User::with(['teachingSubject', 'teachingClasses.learners'])
            ->whereIn('role', ['academic_teacher', 'teacher'])
            ->where('role_status', 'approved')
            ->where('school_id', $user->school_id)
            ->when($teacherSearch !== '', fn ($query) => $query->where(function ($inner) use ($teacherSearch) {
                $inner->where('name', 'like', "%{$teacherSearch}%")
                    ->orWhere('role', 'like', "%{$teacherSearch}%")
                    ->orWhereHas('teachingSubject', fn ($subject) => $subject->where('name', 'like', "%{$teacherSearch}%"))
                    ->orWhereHas('teachingClasses', fn ($class) => $class
                        ->where('name', 'like', "%{$teacherSearch}%")
                        ->orWhere('stream', 'like', "%{$teacherSearch}%"));
            }))
            ->orderBy('name')
            ->get()
            ->map(function (User $teacher) {
                $teacher->assigned_learners_count = $teacher->teachingClasses
                    ->flatMap(fn (SchoolClass $class) => $class->learners)
                    ->unique('id')
                    ->count();

                return $teacher;
            });
        $unlinkedTeachers = User::whereIn('role', ['academic_teacher', 'teacher'])
            ->where('role_status', 'approved')
            ->whereNull('school_id')
            ->orderBy('name')
            ->get();

        return view('modules.head_teacher', compact('user', 'classes', 'subjects', 'exam', 'learners', 'pendingUsers', 'teachers', 'unlinkedTeachers', 'teacherSearch'));
    }

    public function storeExam(Request $request): RedirectResponse
    {
        $this->authorizeRole('academic_teacher');

        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'term' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
        ]);

        Exam::updateOrCreate(
            [
                'school_id' => $user->school_id,
                'name' => $data['name'],
                'term' => $data['term'],
                'year' => $data['year'],
            ],
            [
                'starts_on' => $data['starts_on'] ?? null,
                'ends_on' => $data['ends_on'] ?? null,
            ]
        );

        return redirect()
            ->route('module.academicTeacher')
            ->with('status', 'Exam setup saved successfully.');
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

    public function storeLearner(Request $request): RedirectResponse
    {
        $this->authorizeRole('headmaster');

        $user = $request->user();
        $data = $request->validate([
            'school_class_id' => [
                'required',
                Rule::exists('school_classes', 'id')->where('school_id', $user->school_id),
            ],
            'admission_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('learners', 'admission_number')->where('school_id', $user->school_id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date'],
        ]);

        Learner::create([
            'school_id' => $user->school_id,
            'school_class_id' => $data['school_class_id'],
            'admission_number' => $data['admission_number'] ?? null,
            'name' => $data['name'],
            'gender' => $data['gender'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'is_active' => true,
        ]);

        return redirect()
            ->route('module.headTeacher')
            ->with('status', 'Learner added successfully.');
    }

    public function uploadLearners(Request $request): RedirectResponse
    {
        $this->authorizeRole('headmaster');

        $request->validate([
            'learners_file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
        ]);

        $user = $request->user();
        $imported = 0;
        $skipped = 0;

        foreach ($this->readCsvRows($request->file('learners_file')->getRealPath()) as $row) {
            $name = trim($row['name'] ?? $row['learner_name'] ?? $row['full_name'] ?? '');
            $grade = $this->normalizeGrade($row['grade'] ?? $row['class'] ?? $row['class_name'] ?? '');
            $stream = trim($row['stream'] ?? $row['section'] ?? '') ?: 'main';

            if ($name === '' || $grade === '') {
                $skipped++;
                continue;
            }

            $class = SchoolClass::firstOrCreate([
                'school_id' => $user->school_id,
                'name' => $grade,
                'stream' => $stream,
            ]);

            $admissionNumber = trim($row['admission_number'] ?? $row['admission_no'] ?? $row['adm_no'] ?? '');
            $learnerData = [
                'school_id' => $user->school_id,
                'school_class_id' => $class->id,
                'admission_number' => $admissionNumber !== '' ? $admissionNumber : null,
                'name' => $name,
                'gender' => trim($row['gender'] ?? '') ?: null,
                'date_of_birth' => trim($row['date_of_birth'] ?? $row['dob'] ?? '') ?: null,
                'is_active' => true,
            ];

            if ($admissionNumber !== '') {
                Learner::updateOrCreate(
                    [
                        'school_id' => $user->school_id,
                        'admission_number' => $admissionNumber,
                    ],
                    $learnerData
                );
            } else {
                Learner::create($learnerData);
            }

            $imported++;
        }

        return redirect()
            ->route('module.headTeacher')
            ->with('status', "{$imported} learner(s) uploaded successfully. {$skipped} row(s) skipped.");
    }

    public function updateTeacherAssignment(Request $request, User $teacher): RedirectResponse
    {
        $this->authorizeRole('headmaster');

        $headTeacher = $request->user();

        abort_unless(
            $teacher->school_id === $headTeacher->school_id
            && $teacher->role_status === 'approved'
            && in_array($teacher->role, ['academic_teacher', 'teacher'], true),
            403
        );

        $data = $request->validate([
            'teaching_subject_id' => ['nullable', 'exists:subjects,id'],
            'teaching_class_ids' => ['nullable', 'array'],
            'teaching_class_ids.*' => ['integer', 'exists:school_classes,id'],
        ]);

        $teachingClassIds = collect($data['teaching_class_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($teachingClassIds->isNotEmpty()) {
            $validClassCount = SchoolClass::whereIn('id', $teachingClassIds)
                ->where('school_id', $headTeacher->school_id)
                ->count();

            abort_if($validClassCount !== $teachingClassIds->count(), 422, 'Choose classes from your school only.');
        }

        $teacher->update([
            'teaching_subject_id' => $data['teaching_subject_id'] ?? null,
            'teaching_class_id' => $teachingClassIds->first(),
        ]);

        $teacher->teachingClasses()->sync($teachingClassIds);

        return redirect()
            ->route('module.headTeacher')
            ->with('status', "{$teacher->name}'s teaching assignment has been updated.");
    }

    public function attachTeacherToSchool(Request $request, User $teacher): RedirectResponse
    {
        $this->authorizeRole('headmaster');

        $headTeacher = $request->user();

        abort_unless(
            $teacher->school_id === null
            && $teacher->role_status === 'approved'
            && in_array($teacher->role, ['academic_teacher', 'teacher'], true),
            403
        );

        $teacher->update([
            'school_id' => $headTeacher->school_id,
        ]);

        return redirect()
            ->route('module.headTeacher')
            ->with('status', "{$teacher->name} has been linked to {$headTeacher->school->name}.");
    }

    public function verifySchoolResults(): View
    {
        $this->authorizeRole('headmaster');

        $user = auth()->user()->load('school');
        $submissions = ResultSubmission::with(['exam', 'school'])
            ->where('school_id', $user->school_id)
            ->where('level', 'school')
            ->orderByDesc('submitted_at')
            ->get();

        return view('modules.verify_school_results', compact('user', 'submissions'));
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

    private function authorizeAcademicResultsAccess(): void
    {
        /** @var User $user */
        $user = auth()->user();

        abort_unless(
            in_array($user->role, ['academic_teacher', 'headmaster'], true)
            && $user->role_status === 'approved',
            403
        );
    }

    private function resultsRankingData(Request $request): array
    {
        /** @var User $user */
        $user = auth()->user()->load('school');
        $exam = Exam::where('school_id', $user->school_id)
            ->latest('year')
            ->latest('id')
            ->first();
        $classes = SchoolClass::where('school_id', $user->school_id)
            ->withCount('learners')
            ->orderBy('name')
            ->orderBy('stream')
            ->get();
        $subjects = Subject::orderBy('name')->get();
        $selectedClass = $request->filled('school_class_id')
            ? $classes->firstWhere('id', (int) $request->school_class_id)
            : $classes->first();
        $search = trim((string) $request->input('search', ''));
        $learners = Learner::where('school_id', $user->school_id)
            ->where('is_active', true)
            ->with('schoolClass')
            ->when($selectedClass, fn ($query) => $query->where('school_class_id', $selectedClass->id))
            ->when($search !== '', fn ($query) => $query->where(function ($inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")
                    ->orWhere('admission_number', 'like', "%{$search}%");
            }))
            ->orderBy('name')
            ->get();
        $marks = $exam
            ? Mark::where('exam_id', $exam->id)
                ->whereIn('learner_id', $learners->pluck('id'))
                ->get()
                ->groupBy('learner_id')
            : collect();

        $rankedLearners = $learners->map(function (Learner $learner) use ($subjects, $marks) {
            $learnerMarks = $marks->get($learner->id, collect())->whereNotNull('score');
            $subjectMarks = $subjects->map(fn (Subject $subject) => $learnerMarks->firstWhere('subject_id', $subject->id));

            return [
                'position' => null,
                'learner' => $learner,
                'class_name' => ($learner->schoolClass?->name ?? 'No class').($learner->schoolClass && $learner->schoolClass->stream !== 'main' ? ' '.$learner->schoolClass->stream : ''),
                'subject_marks' => $subjectMarks,
                'submitted' => $learnerMarks->count(),
                'missing' => max($subjects->count() - $learnerMarks->count(), 0),
                'total' => round($learnerMarks->sum('score'), 2),
                'average' => $learnerMarks->isNotEmpty() ? round($learnerMarks->avg('score'), 1) : null,
            ];
        })
            ->sortByDesc(fn (array $item) => $item['total'])
            ->values()
            ->map(function (array $item, int $index) {
                $item['position'] = $index + 1;

                return $item;
            });

        return compact('user', 'exam', 'classes', 'subjects', 'selectedClass', 'search', 'rankedLearners');
    }

    private function resultsComparisonData(Request $request): array
    {
        /** @var User $user */
        $user = auth()->user()->load('school');
        $exams = Exam::where('school_id', $user->school_id)
            ->orderByDesc('year')
            ->orderByDesc('id')
            ->get();
        $currentExam = $request->filled('current_exam_id')
            ? $exams->firstWhere('id', (int) $request->current_exam_id)
            : $exams->first();
        $previousExam = $request->filled('previous_exam_id')
            ? $exams->firstWhere('id', (int) $request->previous_exam_id)
            : $exams->skip(1)->first();
        $classes = SchoolClass::where('school_id', $user->school_id)
            ->with('learners')
            ->orderBy('name')
            ->orderBy('stream')
            ->get();
        $subjects = Subject::orderBy('name')->get();
        $learners = Learner::where('school_id', $user->school_id)
            ->where('is_active', true)
            ->with('schoolClass')
            ->orderBy('name')
            ->get();
        $currentMarks = $currentExam
            ? Mark::where('exam_id', $currentExam->id)->whereIn('learner_id', $learners->pluck('id'))->whereNotNull('score')->get()
            : collect();
        $previousMarks = $previousExam
            ? Mark::where('exam_id', $previousExam->id)->whereIn('learner_id', $learners->pluck('id'))->whereNotNull('score')->get()
            : collect();
        $schoolComparison = [
            'current' => $currentMarks->isNotEmpty() ? round($currentMarks->avg('score'), 1) : null,
            'previous' => $previousMarks->isNotEmpty() ? round($previousMarks->avg('score'), 1) : null,
        ];
        $schoolComparison['change'] = $this->comparisonChange($schoolComparison['current'], $schoolComparison['previous']);
        $classComparisons = $classes->map(function (SchoolClass $class) use ($currentMarks, $previousMarks) {
            $learnerIds = $class->learners->pluck('id');
            $current = $currentMarks->whereIn('learner_id', $learnerIds);
            $previous = $previousMarks->whereIn('learner_id', $learnerIds);
            $currentAverage = $current->isNotEmpty() ? round($current->avg('score'), 1) : null;
            $previousAverage = $previous->isNotEmpty() ? round($previous->avg('score'), 1) : null;

            return [
                'name' => $class->name.($class->stream !== 'main' ? ' '.$class->stream : ''),
                'current' => $currentAverage,
                'previous' => $previousAverage,
                'change' => $this->comparisonChange($currentAverage, $previousAverage),
            ];
        });
        $subjectComparisons = $subjects->map(function (Subject $subject) use ($currentMarks, $previousMarks) {
            $current = $currentMarks->where('subject_id', $subject->id);
            $previous = $previousMarks->where('subject_id', $subject->id);
            $currentAverage = $current->isNotEmpty() ? round($current->avg('score'), 1) : null;
            $previousAverage = $previous->isNotEmpty() ? round($previous->avg('score'), 1) : null;

            return [
                'name' => $subject->name,
                'current' => $currentAverage,
                'previous' => $previousAverage,
                'change' => $this->comparisonChange($currentAverage, $previousAverage),
            ];
        });
        $learnerComparisons = $learners->map(function (Learner $learner) use ($currentMarks, $previousMarks) {
            $current = $currentMarks->where('learner_id', $learner->id);
            $previous = $previousMarks->where('learner_id', $learner->id);
            $currentAverage = $current->isNotEmpty() ? round($current->avg('score'), 1) : null;
            $previousAverage = $previous->isNotEmpty() ? round($previous->avg('score'), 1) : null;

            return [
                'learner' => $learner,
                'class_name' => ($learner->schoolClass?->name ?? 'No class').($learner->schoolClass && $learner->schoolClass->stream !== 'main' ? ' '.$learner->schoolClass->stream : ''),
                'current' => $currentAverage,
                'previous' => $previousAverage,
                'change' => $this->comparisonChange($currentAverage, $previousAverage),
            ];
        })->sortBy(fn (array $item) => $item['change'] ?? 0)->values();

        return compact(
            'user',
            'exams',
            'currentExam',
            'previousExam',
            'schoolComparison',
            'classComparisons',
            'subjectComparisons',
            'learnerComparisons'
        );
    }

    private function comparisonChange(?float $current, ?float $previous): ?float
    {
        return $current !== null && $previous !== null
            ? round($current - $previous, 1)
            : null;
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

    private function normalizeGrade(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        if (preg_match('/([1-7])/', $value, $matches) === 1) {
            return 'Grade '.$matches[1];
        }

        return str($value)->title()->toString();
    }

    private function academicSummary(): array
    {
        $user = auth()->user()->load('school');
        $exam = Exam::where('school_id', $user->school_id)
            ->latest('year')
            ->latest('id')
            ->first();
        $classes = SchoolClass::where('school_id', $user->school_id)
            ->withCount('learners')
            ->orderBy('name')
            ->orderBy('stream')
            ->get();
        $subjects = Subject::orderBy('name')->get();
        $learners = Learner::where('school_id', $user->school_id)
            ->where('is_active', true)
            ->with('schoolClass')
            ->orderBy('name')
            ->get();
        $marks = Mark::with(['learner.schoolClass', 'subject', 'teacher'])
            ->when($exam, fn ($query) => $query->where('exam_id', $exam->id))
            ->whereHas('learner', fn ($query) => $query->where('school_id', $user->school_id))
            ->get();
        $submittedMarks = $marks->filter(fn (Mark $mark) => $mark->score !== null);
        $expectedMarksCount = $learners->count() * $subjects->count();
        $missingMarksCount = max($expectedMarksCount - $submittedMarks->count(), 0);
        $completionPercent = $expectedMarksCount > 0
            ? round(($submittedMarks->count() / $expectedMarksCount) * 100)
            : 0;
        $classSummaries = $classes->map(function (SchoolClass $class) use ($subjects, $marks) {
            $classMarks = $marks->filter(fn (Mark $mark) => $mark->learner?->school_class_id === $class->id && $mark->score !== null);
            $expected = $class->learners_count * $subjects->count();

            return [
                'name' => $class->name.($class->stream !== 'main' ? ' '.$class->stream : ''),
                'learners' => $class->learners_count,
                'expected' => $expected,
                'submitted' => $classMarks->count(),
                'missing' => max($expected - $classMarks->count(), 0),
                'average' => $classMarks->isNotEmpty() ? round($classMarks->avg('score'), 1) : null,
            ];
        });
        $subjectSummaries = $subjects->map(function (Subject $subject) use ($learners, $marks) {
            $subjectMarks = $marks->filter(fn (Mark $mark) => $mark->subject_id === $subject->id && $mark->score !== null);
            $expected = $learners->count();

            return [
                'name' => $subject->name,
                'code' => $subject->code,
                'expected' => $expected,
                'submitted' => $subjectMarks->count(),
                'missing' => max($expected - $subjectMarks->count(), 0),
                'average' => $subjectMarks->isNotEmpty() ? round($subjectMarks->avg('score'), 1) : null,
            ];
        });
        $teachers = User::with(['teachingSubject', 'teachingClasses.learners'])
            ->whereIn('role', ['academic_teacher', 'teacher'])
            ->where('role_status', 'approved')
            ->where('school_id', $user->school_id)
            ->orderBy('name')
            ->get();
        $teacherSubmissions = $teachers->map(function (User $teacher) use ($marks) {
            $learnerIds = $teacher->teachingClasses
                ->flatMap(fn (SchoolClass $class) => $class->learners)
                ->unique('id')
                ->pluck('id');
            $expected = $teacher->teaching_subject_id ? $learnerIds->count() : 0;
            $submitted = $teacher->teaching_subject_id
                ? $marks->filter(fn (Mark $mark) => (
                    $mark->subject_id === $teacher->teaching_subject_id
                    && $learnerIds->contains($mark->learner_id)
                    && $mark->score !== null
                ))->count()
                : 0;

            return [
                'teacher' => $teacher,
                'subject' => $teacher->teachingSubject,
                'classes' => $teacher->teachingClasses,
                'expected' => $expected,
                'submitted' => $submitted,
                'missing' => max($expected - $submitted, 0),
                'completion' => $expected > 0 ? round(($submitted / $expected) * 100) : 0,
            ];
        });
        $learnerPerformance = $learners->map(function (Learner $learner) use ($subjects, $marks) {
            $learnerMarks = $marks->filter(fn (Mark $mark) => $mark->learner_id === $learner->id && $mark->score !== null);

            return [
                'learner' => $learner,
                'expected' => $subjects->count(),
                'submitted' => $learnerMarks->count(),
                'missing' => max($subjects->count() - $learnerMarks->count(), 0),
                'total' => $learnerMarks->sum('score'),
                'average' => $learnerMarks->isNotEmpty() ? round($learnerMarks->avg('score'), 1) : null,
                'marks' => $subjects->map(fn (Subject $subject) => [
                    'subject' => $subject,
                    'mark' => $learnerMarks->firstWhere('subject_id', $subject->id),
                ]),
            ];
        })->sortByDesc(fn (array $item) => $item['average'] ?? -1)->values();
        $missingSamples = $learners
            ->flatMap(fn (Learner $learner) => $subjects->map(fn (Subject $subject) => [
                'learner' => $learner,
                'subject' => $subject,
            ]))
            ->filter(fn (array $item) => ! $marks->contains(fn (Mark $mark) => (
                $mark->learner_id === $item['learner']->id
                && $mark->subject_id === $item['subject']->id
                && $mark->score !== null
            )))
            ->take(30)
            ->values();
        $submission = $exam
            ? ResultSubmission::where('school_id', $user->school_id)
                ->where('exam_id', $exam->id)
                ->where('level', 'school')
                ->first()
            : null;

        return compact(
            'user',
            'exam',
            'classes',
            'subjects',
            'learners',
            'marks',
            'submittedMarks',
            'expectedMarksCount',
            'missingMarksCount',
            'completionPercent',
            'classSummaries',
            'subjectSummaries',
            'teacherSubmissions',
            'learnerPerformance',
            'missingSamples',
            'submission'
        );
    }
}
