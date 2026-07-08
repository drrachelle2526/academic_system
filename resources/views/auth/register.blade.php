<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduMonitor - Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#dff3ff] text-slate-800 font-sans antialiased">

<div class="min-h-screen flex items-center justify-center p-4 md:p-6">
    <div class="w-full max-w-4xl bg-white rounded-[40px] shadow-xl overflow-hidden grid md:grid-cols-2 min-h-[600px]">
        
        <div class="p-10 md:p-12 flex flex-col justify-between bg-sky-50/60 border-r border-sky-100/80 relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-10">
                    <i data-lucide="graduation-cap" class="h-8 w-8 text-sky-500"></i>
                    <span class="text-2xl font-bold tracking-wide text-sky-950">EduMonitor</span>
                </div>

                <div class="space-y-6">
                    <h2 class="text-3xl font-extrabold leading-tight text-sky-950">System Verification Access</h2>
                    <p class="text-slate-600 text-base leading-relaxed">
                        To maintain public integrity and protect data security, your identity requires validation within the administrative verification framework.
                    </p>

                    <div class="bg-white border border-sky-100 rounded-2xl p-5 mt-6 shadow-sm">
                        <div class="flex items-start gap-3">
                            <i data-lucide="shield" class="h-6 w-6 text-sky-500 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <h3 class="font-bold text-sky-950 mb-2">Access Control Chains</h3>
                                <ul class="text-slate-600 text-sm space-y-2 list-disc list-inside">
                                    <li>System Administrators manage WEOs</li>
                                    <li>WEOs authenticate Head Teachers</li>
                                    <li>Heads activate institutional staff accounts</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-slate-400 text-xs font-medium relative z-10 mt-8">
                &copy; {{ date('Y') }} EduMonitor &bull; United Republic of Tanzania
            </div>

            <div class="absolute -top-10 -left-10 w-40 h-40 border-[10px] border-sky-200/40 rounded-full"></div>
        </div>

        <div class="p-10 md:p-12 flex flex-col justify-center bg-white">
            
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    @foreach(['Account', 'Profile', 'Review'] as $index => $label)
                        <div class="flex items-center">
                            <div id="step-circle-{{ $index + 1 }}" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300">
                                <span id="step-number-{{ $index + 1 }}">{{ $index + 1 }}</span>
                            </div>
                            @if($index < 2)
                                <div id="step-line-{{ $index + 1 }}" class="w-16 h-0.5 mx-1 bg-slate-100 transition-all duration-300"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between text-xs text-slate-400 font-semibold uppercase tracking-wider">
                    <span>Account</span>
                    <span>Profile</span>
                    <span>Review</span>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-100 rounded-2xl p-4 flex items-start gap-3">
                    <i data-lucide="alert-circle" class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5"></i>
                    <ul class="text-red-700 text-sm list-none p-0 m-0 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

           <div id="js-error-box" class="mb-6 bg-red-50 border border-red-100 rounded-2xl p-4 hidden items-start gap-3">
                <i data-lucide="alert-circle" class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5"></i>
                <p id="js-error-message" class="text-red-700 text-sm font-medium"></p>
            </div>

            <form action="{{ route('register') }}" method="POST" id="regForm">
                @csrf

                <div id="step-section-1" class="space-y-5">
                    <h2 class="text-2xl font-extrabold text-sky-950">System Configuration</h2>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Official Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all text-slate-800" placeholder="name@domain.com">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Secure Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all text-slate-800" placeholder="Minimum 6 characters">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Confirm System Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all text-slate-800" placeholder="Re-enter password">
                    </div>
                    <button type="button" onclick="navigateStep(1, 2)"
                            class="w-full bg-sky-500 hover:bg-sky-600 text-white font-bold py-3 rounded-full shadow transition-colors">
                        Continue to Profile Setup
                    </button>
                </div>

                <div id="step-section-2" class="hidden space-y-5">
                    <h2 class="text-2xl font-extrabold text-sky-950">Personnel Identity</h2>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Full Name (As shown on ID) *</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all text-slate-800" placeholder="Enter full name">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Active Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all text-slate-800" placeholder="e.g., 0712345678">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Ward *</label>
                        <select name="ward" id="ward" onchange="handleWardSelection()"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-sky-400 focus:bg-white transition-all text-slate-800">
                            <option value="">-- Choose Your Ward --</option>
                            @foreach($wards as $ward)
                                <option value="{{ $ward }}">{{ ucwords($ward) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="school-select-container" class="mt-5 hidden">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Primary School *</label>
                        <select name="school_id" id="school_id" onchange="handleSchoolSelection()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-sky-400 focus:bg-white transition-all text-slate-800">
                            <option value="">-- Choose Your School --</option>
                        </select>
                        <p class="text-xs text-slate-400 mt-2">Choose the ward first, then select a school from that ward.</p>
                    </div>

                    <div id="teaching-assignment-container" class="hidden grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Subject You Teach *</label>
                            <select name="teaching_subject_id" id="teaching_subject_id"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-sky-400 focus:bg-white transition-all text-slate-800">
                                <option value="">-- Choose Subject --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected(old('teaching_subject_id') == $subject->id)>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Classes You Teach *</label>
                            <div id="teaching_class_options" class="min-h-[52px] max-h-44 overflow-y-auto rounded-xl border border-slate-200 bg-slate-50 p-3 space-y-2"></div>
                            <p class="text-xs text-slate-400 mt-2">Select every class you teach for the chosen subject.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" onclick="navigateStep(2, 1)" class="flex-1 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold py-3 rounded-full transition-colors">Back</button>
                        <button type="button" onclick="navigateStep(2, 3)" class="flex-1 bg-sky-500 hover:bg-sky-600 text-white font-bold py-3 rounded-full shadow transition-colors">Review Summary</button>
                    </div>
                </div>

                <div id="step-section-3" class="hidden space-y-5">
                    <h2 class="text-2xl font-extrabold text-sky-950">Declaration Review</h2>

                    <div class="bg-slate-50 rounded-2xl p-5 space-y-3 text-sm border border-slate-200/60">
                        <div class="flex justify-between"><span class="text-slate-500 font-medium">Email Address:</span> <span id="review-email" class="font-bold text-slate-800">-</span></div>
                        <div class="flex justify-between"><span class="text-slate-500 font-medium">Full Name:</span> <span id="review-name" class="font-bold text-slate-800">-</span></div>
                        <div class="flex justify-between"><span class="text-slate-500 font-medium">Phone Terminal:</span> <span id="review-phone" class="font-bold text-slate-800">-</span></div>
                        <div class="flex justify-between"><span class="text-slate-500 font-medium">Assigned Role:</span> <span id="review-role" class="font-bold text-slate-800">Assigned after approval</span></div>
                        <div class="flex justify-between"><span class="text-slate-500 font-medium">Ward / School:</span> <span id="review-context-val" class="font-bold text-slate-800">-</span></div>
                        <div class="flex justify-between"><span class="text-slate-500 font-medium">Teaching Assignment:</span> <span id="review-assignment-val" class="font-bold text-slate-800">-</span></div>
                    </div>

                    <p class="text-xs text-slate-400 leading-normal">
                        By submitting this configuration, you affirm that the registration information details match valid public service personnel records.
                    </p>

                    <div class="flex gap-4">
                        <button type="button" onclick="navigateStep(3, 2)" class="flex-1 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold py-3 rounded-full transition-colors">Back</button>
                        <button type="submit" class="flex-1 bg-sky-500 hover:bg-sky-600 text-white font-bold py-3 rounded-full shadow transition-colors">Submit Enrollment</button>
                    </div>
                </div>
            </form>

            <p class="text-center text-sm text-slate-500 mt-6">
                Already registered? <a href="/login" class="text-sky-500 hover:underline font-bold">Sign in here</a>
            </p>

            <div class="text-center mt-8 pt-4 border-t border-slate-100">
                <a href="/" class="inline-flex items-center gap-1.5 text-sm font-bold text-slate-500 hover:text-sky-500 transition-colors group focus:outline-none focus:underline">
                    <i data-lucide="arrow-left" class="h-4 w-4 transition-transform group-hover:-translate-x-1"></i>
                    Back to Homepage
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    let currentStep = 1;

    function updateStepperUI(step) {
        for (let i = 1; i <= 3; i++) {
            const circle = document.getElementById(`step-circle-${i}`);
            const numSpan = document.getElementById(`step-number-${i}`);
            const line = document.getElementById(`step-line-${i}`);
            if (circle && numSpan) {
                if (step > i) {
                    circle.className = "w-8 h-8 rounded-full flex items-center justify-center font-bold bg-sky-500 text-white shadow-sm";
                    numSpan.innerHTML = '<i data-lucide="check" class="h-4 w-4"></i>';
                } else if (step === i) {
                    circle.className = "w-8 h-8 rounded-full flex items-center justify-center font-bold bg-sky-100 text-sky-600 border border-sky-300";
                    numSpan.textContent = i;
                } else {
                    circle.className = "w-8 h-8 rounded-full flex items-center justify-center font-bold bg-slate-50 text-slate-400 border border-slate-200";
                    numSpan.textContent = i;
                }
            }
            if (line) {
                line.className = `w-16 h-0.5 mx-1 ${step > i ? 'bg-sky-500' : 'bg-slate-100'}`;
            }
        }
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function showError(msg) {
        const errBox = document.getElementById('js-error-box');
        const errMsg = document.getElementById('js-error-message');
        if (errBox && errMsg) {
            if (msg) { 
                errMsg.textContent = msg; 
                errBox.classList.remove('hidden'); 
                errBox.classList.add('flex');
            } else { 
                errBox.classList.add('hidden'); 
                errBox.classList.remove('flex');
            }
        }
    }

    function validateStep(step) {
        showError('');
        if (step === 1) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            if (!email || !password || !confirm) return "Please fill in all security fields";
            if (password.length < 6) return "Password configuration must be at least 6 characters";
            if (password !== confirm) return "Passwords do not match";
        }
        if (step === 2) {
            const name = document.getElementById('full_name').value;
            const ward = document.getElementById('ward').value;
            const school = document.getElementById('school_id').value;
            if (!name || !ward || !school) return "Please provide all mandatory personnel identification data";
        }
        return null;
    }

    const schools = {{ Illuminate\Support\Js::from($schools->map(fn($school) => ['id' => $school->id, 'name' => $school->name, 'ward' => $school->ward])) }};
    const schoolClasses = {{ Illuminate\Support\Js::from($schoolClasses->map(fn($class) => ['id' => $class->id, 'name' => $class->name, 'stream' => $class->stream, 'school_id' => $class->school_id])) }};

    function handleWardSelection() {
        const ward = document.getElementById('ward').value;
        const schoolSelect = document.getElementById('school_id');
        const container = document.getElementById('school-select-container');

        if (!ward) {
            schoolSelect.innerHTML = '<option value="">-- Choose Your School --</option>';
            container.classList.add('hidden');
            handleSchoolSelection();
            return;
        }

        const filteredSchools = schools.filter(school => school.ward === ward);
        let options = '<option value="">-- Choose Your School --</option>';

        filteredSchools.forEach(school => {
            options += `<option value="${school.id}">${school.name}</option>`;
        });

        schoolSelect.innerHTML = options;
        container.classList.remove('hidden');
        handleSchoolSelection();
    }

    function handleSchoolSelection() {
        const schoolId = Number(document.getElementById('school_id').value);
        const classOptions = document.getElementById('teaching_class_options');
        const container = document.getElementById('teaching-assignment-container');

        if (!schoolId) {
            classOptions.innerHTML = '';
            container.classList.add('hidden');
            return;
        }

        const filteredClasses = schoolClasses.filter(schoolClass => schoolClass.school_id === schoolId);
        let options = '';

        filteredClasses.forEach(schoolClass => {
            const stream = schoolClass.stream && schoolClass.stream !== 'main' ? ` ${schoolClass.stream}` : '';
            options += `
                <label class="flex items-center gap-3 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-slate-700 border border-slate-100">
                    <input type="checkbox" name="teaching_class_ids[]" value="${schoolClass.id}" class="rounded border-slate-300 text-sky-500 focus:ring-sky-400">
                    <span>${schoolClass.name}${stream}</span>
                </label>
            `;
        });

        classOptions.innerHTML = options || '<p class="text-sm font-medium text-slate-500">No classes registered for this school yet.</p>';
        container.classList.remove('hidden');
    }

    function compileReviewPage() {
        document.getElementById('review-email').textContent = document.getElementById('email').value;
        document.getElementById('review-name').textContent = document.getElementById('full_name').value;
        document.getElementById('review-phone').textContent = document.getElementById('phone').value || 'None Declared';
        document.getElementById('review-role').textContent = 'Assigned after approval';

        const ward = document.getElementById('ward').value;
        const schoolSelect = document.getElementById('school_id');
        const schoolLabel = schoolSelect.options[schoolSelect.selectedIndex]?.text || '-';
        document.getElementById('review-context-val').textContent = `${ward} / ${schoolLabel}`;

        const subjectSelect = document.getElementById('teaching_subject_id');
        const subjectLabel = subjectSelect.options[subjectSelect.selectedIndex]?.text || '-';
        const classLabels = Array.from(document.querySelectorAll('input[name="teaching_class_ids[]"]:checked'))
            .map(input => input.closest('label')?.querySelector('span')?.textContent)
            .filter(Boolean);
        document.getElementById('review-assignment-val').textContent = classLabels.length > 0 ? `${subjectLabel} / ${classLabels.join(', ')}` : 'Assigned after approval';
    }

    function navigateStep(from, to) {
        const error = validateStep(from);
        if (to > from && error) { 
            showError(error); 
            return; 
        }
        if (to === 3) compileReviewPage();
        
        const currentSection = document.getElementById(`step-section-${from}`);
        const nextSection = document.getElementById(`step-section-${to}`);
        
        if (currentSection && nextSection) {
            currentSection.classList.add('hidden');
            nextSection.classList.remove('hidden');
            updateStepperUI(to);
            currentStep = to;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateStepperUI(1);
    });
</script>
</body>
</html>
