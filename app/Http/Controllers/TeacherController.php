<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Learner;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherController extends Controller
{
    /**
     * Show the teacher module dashboard.
     */
    public function show()
    {
        $user = auth()->user()->load(['school', 'teachingSubject', 'teachingClasses']);
        $subjects = Subject::orderBy('name')->get();
        $classes = SchoolClass::where('school_id', $user->school_id)
            ->orderBy('name')
            ->orderBy('stream')
            ->get();

        return view('modules.teacher', compact('user', 'subjects', 'classes'));
    }

    public function updateAssignment(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless(in_array($user->role, ['academic_teacher', 'teacher'], true), 403);
        abort_if($user->school_id === null, 422, 'Your account is not linked to a school yet. Ask the Head Teacher to link your account to the school first.');

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
                ->where('school_id', $user->school_id)
                ->count();

            abort_if($validClassCount !== $teachingClassIds->count(), 422, 'Choose classes from your school only.');
        }

        $user->update([
            'teaching_subject_id' => $data['teaching_subject_id'] ?? null,
            'teaching_class_id' => $teachingClassIds->first(),
        ]);

        $user->teachingClasses()->sync($teachingClassIds);

        return back()->with('status', 'Your teaching subject and classes have been saved.');
    }

    public function classLists()
    {
        $user = auth()->user()->load('teachingSubject');
        $classLists = $user->teachingClasses()
            ->withCount('learners')
            ->orderBy('name')
            ->orderBy('stream')
            ->get();

        return view('modules.classlists', compact('user', 'classLists'));
    }

    public function recordMarks(Request $request): View
    {
        $user = auth()->user()->load(['teachingSubject', 'teachingClasses']);
        $selectedClass = $this->selectedTeachingClass($request);
        $learners = $selectedClass
            ? $selectedClass->learners()->orderBy('name')->get()
            : collect();
        $exam = $this->currentExam();
        $marks = $exam && $user->teaching_subject_id
            ? Mark::where('exam_id', $exam->id)
                ->where('subject_id', $user->teaching_subject_id)
                ->whereIn('learner_id', $learners->pluck('id'))
                ->get()
                ->keyBy('learner_id')
            : collect();

        return view('modules.record_marks', compact('user', 'selectedClass', 'learners', 'exam', 'marks'));
    }

    public function storeMarks(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_if($user->teaching_subject_id === null, 422, 'Choose your teaching subject first.');

        $data = $request->validate([
            'school_class_id' => ['required', 'integer'],
            'scores' => ['required', 'array'],
            'scores.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $class = $this->assignedClassOrFail((int) $data['school_class_id']);
        $exam = $this->currentExam();
        abort_if($exam === null, 422, 'No exam has been created for this school yet. Ask the Head Teacher to set up the current exam first.');

        $learnerIds = $class->learners()->pluck('id');

        foreach ($data['scores'] as $learnerId => $score) {
            if (! $learnerIds->contains((int) $learnerId) || $score === null || $score === '') {
                continue;
            }

            Mark::updateOrCreate(
                [
                    'exam_id' => $exam->id,
                    'learner_id' => (int) $learnerId,
                    'subject_id' => $user->teaching_subject_id,
                ],
                [
                    'teacher_id' => $user->id,
                    'score' => $score,
                    'grade' => $this->gradeForScore((float) $score),
                    'submitted_at' => now(),
                ]
            );
        }

        return redirect()
            ->route('teacher.recordMarks', ['school_class_id' => $class->id])
            ->with('status', 'Marks saved successfully.');
    }

    public function attendance(Request $request): View
    {
        $user = auth()->user()->load(['teachingSubject', 'teachingClasses']);
        $selectedClass = $this->selectedTeachingClass($request);
        $date = $request->input('date', now()->toDateString());
        $learners = $selectedClass
            ? $selectedClass->learners()->orderBy('name')->get()
            : collect();
        $attendances = $selectedClass
            ? Attendance::where('attendance_date', $date)
                ->whereIn('learner_id', $learners->pluck('id'))
                ->get()
                ->keyBy('learner_id')
            : collect();

        return view('modules.attendance', compact('user', 'selectedClass', 'date', 'learners', 'attendances'));
    }

    public function storeAttendance(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'school_class_id' => ['required', 'integer'],
            'attendance_date' => ['required', 'date'],
            'statuses' => ['required', 'array'],
            'statuses.*' => ['required', 'string', 'in:present,absent,late,excused'],
        ]);

        $class = $this->assignedClassOrFail((int) $data['school_class_id']);
        $learnerIds = $class->learners()->pluck('id');

        foreach ($data['statuses'] as $learnerId => $status) {
            if (! $learnerIds->contains((int) $learnerId)) {
                continue;
            }

            Attendance::updateOrCreate(
                [
                    'learner_id' => (int) $learnerId,
                    'attendance_date' => $data['attendance_date'],
                ],
                [
                    'school_class_id' => $class->id,
                    'teacher_id' => $user->id,
                    'status' => $status,
                ]
            );
        }

        return redirect()
            ->route('teacher.attendance', ['school_class_id' => $class->id, 'date' => $data['attendance_date']])
            ->with('status', 'Attendance saved successfully.');
    }

    public function reports(Request $request): View
    {
        $data = $this->teacherReportData($request);

        return view('modules.reports', $data);
    }

    public function downloadReports(Request $request)
    {
        $data = $this->teacherReportData($request);

        return response()->streamDownload(function () use ($data) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['position', 'admission_number', 'learner', 'class', 'subject', 'score', 'grade']);

            foreach ($data['learnerResults'] as $item) {
                fputcsv($output, [
                    $item['position'],
                    $item['learner']->admission_number,
                    $item['learner']->name,
                    $item['class_name'],
                    $data['user']->teachingSubject?->name,
                    $item['mark']?->score,
                    $item['mark']?->grade,
                ]);
            }

            fclose($output);
        }, 'teacher-subject-results.csv');
    }

    private function selectedTeachingClass(Request $request): ?SchoolClass
    {
        $classId = $request->integer('school_class_id') ?: auth()->user()->teachingClasses()->value('school_classes.id');

        return $classId ? $this->assignedClassOrFail((int) $classId) : null;
    }

    private function assignedClassOrFail(int $classId): SchoolClass
    {
        return auth()->user()->teachingClasses()
            ->where('school_classes.id', $classId)
            ->with('learners')
            ->firstOrFail();
    }

    private function currentExam(): ?Exam
    {
        $user = auth()->user();

        return Exam::where('school_id', $user->school_id)
            ->latest('year')
            ->latest('id')
            ->first();
    }

    private function gradeForScore(float $score): string
    {
        return match (true) {
            $score >= 81 => 'A',
            $score >= 61 => 'B',
            $score >= 41 => 'C',
            $score >= 21 => 'D',
            default => 'E',
        };
    }

    private function teacherReportData(Request $request): array
    {
        $user = auth()->user()->load(['teachingSubject', 'teachingClasses']);
        $exam = $this->currentExam();
        $selectedClass = $request->filled('school_class_id')
            ? $user->teachingClasses->firstWhere('id', (int) $request->school_class_id)
            : null;
        $search = trim((string) $request->input('search', ''));
        $classes = $selectedClass ? collect([$selectedClass]) : $user->teachingClasses;
        $learners = Learner::whereIn('school_class_id', $classes->pluck('id'))
            ->where('is_active', true)
            ->with('schoolClass')
            ->when($search !== '', fn ($query) => $query->where(function ($inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")
                    ->orWhere('admission_number', 'like', "%{$search}%");
            }))
            ->orderBy('name')
            ->get();
        $marks = $exam && $user->teaching_subject_id
            ? Mark::where('exam_id', $exam->id)
                ->where('subject_id', $user->teaching_subject_id)
                ->whereIn('learner_id', $learners->pluck('id'))
                ->get()
                ->keyBy('learner_id')
            : collect();
        $learnerResults = $learners->map(function (Learner $learner) use ($marks) {
            $mark = $marks->get($learner->id);

            return [
                'position' => null,
                'learner' => $learner,
                'class_name' => ($learner->schoolClass?->name ?? 'No class').($learner->schoolClass && $learner->schoolClass->stream !== 'main' ? ' '.$learner->schoolClass->stream : ''),
                'mark' => $mark,
                'score' => $mark?->score,
            ];
        })
            ->sortByDesc(fn (array $item) => $item['score'] ?? -1)
            ->values()
            ->map(function (array $item, int $index) {
                $item['position'] = $index + 1;

                return $item;
            });
        $submitted = $learnerResults->filter(fn (array $item) => $item['score'] !== null)->count();
        $expected = $learnerResults->count();
        $missing = max($expected - $submitted, 0);
        $average = $learnerResults->pluck('score')->filter(fn ($score) => $score !== null)->avg();
        $classReports = $classes
            ->sortBy('name')
            ->map(function (SchoolClass $class) use ($exam, $user) {
                $classLearners = $class->learners()->where('is_active', true)->get();
                $classMarks = $exam && $user->teaching_subject_id
                    ? Mark::where('exam_id', $exam->id)
                        ->where('subject_id', $user->teaching_subject_id)
                        ->whereIn('learner_id', $classLearners->pluck('id'))
                        ->whereNotNull('score')
                        ->get()
                    : collect();

                return [
                    'class' => $class,
                    'learners_count' => $classLearners->count(),
                    'submitted' => $classMarks->count(),
                    'missing' => max($classLearners->count() - $classMarks->count(), 0),
                    'average' => $classMarks->isNotEmpty() ? round($classMarks->avg('score'), 1) : null,
                    'highest' => $classMarks->isNotEmpty() ? $classMarks->max('score') : null,
                    'lowest' => $classMarks->isNotEmpty() ? $classMarks->min('score') : null,
                ];
            });

        return compact('user', 'exam', 'selectedClass', 'search', 'classReports', 'learnerResults', 'submitted', 'expected', 'missing', 'average');
    }
}
