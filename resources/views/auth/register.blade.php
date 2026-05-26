<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduMonitor - Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen bg-[#dff3ff] text-slate-800 font-sans antialiased">

<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-4xl bg-white rounded-[40px] shadow-xl overflow-hidden grid md:grid-cols-2 min-h-[600px]">
        
        <div class="bg-sky-400 p-10 md:p-12 flex flex-col justify-between text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-10">
                    <i data-lucide="graduation-cap" class="h-8 w-8 text-white"></i>
                    <span class="text-2xl font-bold">EduMonitor</span>
                </div>

                <div class="space-y-6">
                    <h2 class="text-3xl font-extrabold leading-tight">Secure Academic Management</h2>
                    <p class="text-sky-55 text-sky-100 text-base">
                        Every user is verified by someone in authority above them in the hierarchy, ensuring only legitimate school personnel access the system.
                    </p>

                    <div class="bg-white/10 border border-white/20 rounded-2xl p-5 mt-6">
                        <div class="flex items-start gap-3">
                            <i data-lucide="shield" class="h-6 w-6 text-white flex-shrink-0 mt-0.5"></i>
                            <div>
                                <h3 class="font-semibold text-white mb-2">Verification Hierarchy</h3>
                                <ul class="text-sky-100 text-sm space-y-1 list-disc list-inside">
                                    <li>System Admin verifies WEOs</li>
                                    <li>WEO verifies Head Teachers</li>
                                    <li>Head Teacher verifies Teachers</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-sky-200 text-sm relative z-10 mt-8">
                &copy; {{ date('Y') }} EduMonitor
            </div>

            <div class="absolute -top-10 -left-10 w-40 h-40 border-[10px] border-sky-300 rounded-full opacity-40"></div>
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-sky-300 rounded-full opacity-30"></div>
        </div>

        <div class="p-10 md:p-12 flex flex-col justify-center bg-white">
            
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    @foreach(['Account', 'Profile', 'Review'] as $index => $label)
                        <div class="flex items-center">
                            <div id="step-circle-{{ $index + 1 }}" class="w-8 h-8 rounded-full flex items-center justify-center font-semibold transition-all duration-300">
                                <span id="step-number-{{ $index + 1 }}">{{ $index + 1 }}</span>
                            </div>
                            @if($index < 2)
                                <div id="step-line-{{ $index + 1 }}" class="w-16 h-1 mx-1 bg-gray-100 transition-all duration-300"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between text-xs text-gray-500 font-medium">
                    <span>Account</span>
                    <span>Profile</span>
                    <span>Review</span>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
                    <i data-lucide="alert-circle" class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5"></i>
                    <ul class="text-red-700 text-sm list-none p-0 m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="js-error-box" class="hidden mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
                <i data-lucide="alert-circle" class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5"></i>
                <p id="js-error-message" class="text-red-700 text-sm"></p>
            </div>

            <form action="{{ route('register') }}" method="POST" id="regForm">
                @csrf

                <div id="step-section-1" class="space-y-5">
                    <h2 class="text-2xl font-bold text-gray-800">Create account</h2>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all" placeholder="you@example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all" placeholder="Min 6 characters">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all" placeholder="Confirm password">
                    </div>
                    <button type="button" onclick="navigateStep(1, 2)"
                            class="w-full bg-sky-400 hover:bg-sky-500 text-white font-semibold py-3 rounded-full shadow-md transition-colors">
                        Continue
                    </button>
                </div>

                <div id="step-section-2" class="hidden space-y-5">
                    <h2 class="text-2xl font-bold text-gray-800">Your profile</h2>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Full Name *</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all" placeholder="Full name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all" placeholder="Phone number">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Select Your Role *</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                            @foreach([
                                'weo' => ['title' => 'Ward Education Officer', 'icon' => 'users'],
                                'head_teacher' => ['title' => 'Head Teacher', 'icon' => 'user-check'],
                                'academic_teacher' => ['title' => 'Academic Teacher', 'icon' => 'graduation-cap'],
                                'teacher' => ['title' => 'Teacher', 'icon' => 'graduation-cap']
                            ] as $key => $role)
                                <label id="label-{{ $key }}" class="role-card block p-3 border border-gray-100 rounded-xl cursor-pointer transition-all hover:bg-gray-50">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="role" value="{{ $key }}" onchange="handleRoleSelection('{{ $key }}')">
                                        <div class="flex items-center gap-2 font-medium text-gray-700 text-sm">
                                            <i data-lucide="{{ $role['icon'] }}" class="h-4 w-4 text-gray-400"></i>
                                            {{ $role['title'] }}
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div id="weo-block" class="hidden">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Your Ward *</label>
                        <input type="text" name="ward" id="ward" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all" placeholder="Enter ward">
                    </div>

                    <div id="school-block" class="hidden">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Select Your School *</label>
                        <select name="school_id" id="school_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-400 focus:bg-white outline-none transition-all">
                            <option value="">Choose school</option>
                            @if(isset($schools))
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" onclick="navigateStep(2, 1)" class="flex-1 border border-gray-200 hover:bg-gray-50 text-gray-600 font-medium py-3 rounded-full transition-colors">Back</button>
                        <button type="button" onclick="navigateStep(2, 3)" class="flex-1 bg-sky-400 hover:bg-sky-500 text-white font-semibold py-3 rounded-full shadow-md transition-colors">Continue</button>
                    </div>
                </div>

                <div id="step-section-3" class="hidden space-y-5">
                    <h2 class="text-2xl font-bold text-gray-800">Review Details</h2>

                    <div class="bg-gray-50 rounded-2xl p-5 space-y-3 text-sm border border-gray-100">
                        <div class="flex justify-between"><span class="text-gray-500">Email:</span> <span id="review-email" class="font-semibold text-gray-700">-</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Name:</span> <span id="review-name" class="font-semibold text-gray-700">-</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Phone:</span> <span id="review-phone" class="font-semibold text-gray-700">-</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Role:</span> <span id="review-role" class="font-semibold text-gray-700">-</span></div>
                        <div id="review-context-row" class="flex justify-between"><span id="review-context-label" class="text-gray-500">Assigned:</span> <span id="review-context-val" class="font-semibold text-gray-700">-</span></div>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" onclick="navigateStep(3, 2)" class="flex-1 border border-gray-200 hover:bg-gray-50 text-gray-600 font-medium py-3 rounded-full transition-colors">Back</button>
                        <button type="submit" class="flex-1 bg-sky-400 hover:bg-sky-500 text-white font-semibold py-3 rounded-full shadow-md transition-colors">Create Account</button>
                    </div>
                </div>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Already have an account? <a href="/login" class="text-sky-500 hover:underline font-semibold">Login</a>
            </p>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    function updateStepperUI(step) {
        for (let i = 1; i <= 3; i++) {
            const circle = document.getElementById(`step-circle-${i}`);
            const numSpan = document.getElementById(`step-number-${i}`);
            const line = document.getElementById(`step-line-${i}`);
            if (step > i) {
                circle.className = "w-8 h-8 rounded-full flex items-center justify-center font-semibold bg-sky-400 text-white shadow-sm";
                numSpan.innerHTML = '<i data-lucide="check" class="h-4 w-4"></i>';
            } else if (step === i) {
                circle.className = "w-8 h-8 rounded-full flex items-center justify-center font-semibold bg-sky-100 text-sky-600 border border-sky-300";
                numSpan.textContent = i;
            } else {
                circle.className = "w-8 h-8 rounded-full flex items-center justify-center font-semibold bg-gray-50 text-gray-400 border border-gray-200";
                numSpan.textContent = i;
            }
            if (line) line.className = `w-16 h-1 mx-1 ${step > i ? 'bg-sky-400' : 'bg-gray-100'}`;
        }
        lucide.createIcons();
    }
    function showError(msg) {
        const errBox = document.getElementById('js-error-box');
        const errMsg = document.getElementById('js-error-message');
        if(msg) { errMsg.textContent = msg; errBox.classList.remove('hidden'); } else { errBox.classList.add('hidden'); }
    }
    function validateStep(step) {
        showError('');
        if (step === 1) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            if (!email || !password || !confirm) return "Please fill in all fields";
            if (password.length < 6) return "Password must be at least 6 characters";
            if (password !== confirm) return "Passwords do not match";
        }
        if (step === 2) {
            const name = document.getElementById('full_name').value;
            const selectedRole = document.querySelector('input[name="role"]:checked');
            if (!name || !selectedRole) return "Please fill in all required fields";
            const role = selectedRole.value;
            if (role === 'weo' && !document.getElementById('ward').value) return "WEO must specify their ward";
            if (['head_teacher', 'teacher', 'academic_teacher'].includes(role) && !document.getElementById('school_id').value) return "Please select your school";
        }
        return null;
    }
    function handleRoleSelection(role) {
        document.querySelectorAll('.role-card').forEach(card => card.classList.remove('border-sky-300', 'bg-sky-50/50'));
        const targetLabel = document.getElementById(`label-${role}`);
        if (targetLabel) targetLabel.classList.add('border-sky-300', 'bg-sky-50/50');
        document.getElementById('weo-block').classList.toggle('hidden', role !== 'weo');
        document.getElementById('school-block').classList.toggle('hidden', role === 'weo');
    }
    function compileReviewPage() {
        document.getElementById('review-email').textContent = document.getElementById('email').value;
        document.getElementById('review-name').textContent = document.getElementById('full_name').value;
        document.getElementById('review-phone').textContent = document.getElementById('phone').value || 'None';
        const role = document.querySelector('input[name="role"]:checked').value;
        document.getElementById('review-role').textContent = role.replace('_', ' ').toUpperCase();
        if (role === 'weo') {
            document.getElementById('review-context-label').textContent = "Ward:";
            document.getElementById('review-context-val').textContent = document.getElementById('ward').value;
        } else {
            document.getElementById('review-context-label').textContent = "School:";
            const sel = document.getElementById('school_id');
            document.getElementById('review-context-val').textContent = sel.options[sel.selectedIndex].text;
        }
    }
    function navigateStep(from, to) {
        if (to > from && validateStep(from)) { showError(validateStep(from)); return; }
        if (to === 3) compileReviewPage();
        document.getElementById(`step-section-${from}`).classList.add('hidden');
        document.getElementById(`step-section-${to}`).classList.remove('hidden');
        updateStepperUI(to);
    }
    updateStepperUI(1);
</script>
</body>
</html>