<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(Auth::user()->role === 'academic_teacher' && Auth::user()->role_status === 'pending')
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Account Verification Pending</p>
                    <p class="text-sm">You are currently logged in with standard teacher permissions. Your request for Academic Teacher access is waiting for Headmaster verification.</p>
                </div>
            @endif

              
            @if(Auth::user()->role === 'headmaster' && Auth::user()->role_status === 'pending')
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Account Verification Pending</p>
                    <p class="text-sm">Your request for Head Teacher access is waiting for WEO verification.</p>
                </div>
            @endif

            @if(Auth::user()->role === 'weo' && Auth::user()->role_status === 'pending')
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Account Verification Pending</p>
                    <p class="text-sm">Your request for WEO access is waiting for System Administrator verification.</p>
                </div>
            @endif

            @if(session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Success</p>
                    <p class="text-sm">{{ session('status') }}</p>
                </div>
            @endif

            @if(isset($pendingUsers) && $pendingUsers->isNotEmpty())
                <div class="bg-white border border-gray-200 shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800">Pending Role Verifications</h3>
                        <div class="mt-4 divide-y divide-gray-100">
                            @foreach($pendingUsers as $pendingUser)
                                <div class="py-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $pendingUser->name }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ ucwords(str_replace('_', ' ', $pendingUser->role)) }}
                                            @if($pendingUser->school)
                                                - {{ $pendingUser->school->name }} - {{ $pendingUser->school->ward }}
                                            @elseif($pendingUser->ward)
                                                - {{ $pendingUser->ward }}
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-500">{{ $pendingUser->email }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('role-verifications.approve', $pendingUser) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Approve
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if(Auth::user()->role === 'academic_teacher' && Auth::user()->role_status === 'approved')
                <div class="bg-blue-50 border border-blue-200 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-blue-900">
                        <h3 class="text-lg font-bold text-blue-800 flex items-center gap-2">
                            Academic Management Module
                        </h3>
                        <p class="text-sm text-blue-700 mt-1">This module is unlocked. You can now manage school schedules, subjects, and compile ward reports.</p>
                        </div>
                </div>
            @endif


            @if(Auth::user()->role === 'headmaster' && Auth::user()->role_status === 'approved')
                <div class="bg-blue-50 border border-blue-200 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-blue-900">
                        <h3 class="text-lg font-bold text-blue-800 flex items-center gap-2">
                            Head Teacher Management Module
                        </h3>
                        <p class="text-sm text-blue-700 mt-1">This module is unlocked. You can now manage school schedules, subjects, and compile ward reports.</p>
                        </div>
                </div>
            @endif

            @if(Auth::user()->role === 'weo' && Auth::user()->role_status === 'approved')
                <div class="bg-blue-50 border border-blue-200 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-blue-900">
                        <h3 class="text-lg font-bold text-blue-800 flex items-center gap-2">
                            WEO Verification Module
                        </h3>
                        <p class="text-sm text-blue-700 mt-1">You can verify pending Head Teacher accounts for schools in your ward.</p>
                    </div>
                </div>
            @endif

            @if(Auth::user()->role === 'admin' && Auth::user()->role_status === 'approved')
                <div class="bg-blue-50 border border-blue-200 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-blue-900">
                        <h3 class="text-lg font-bold text-blue-800 flex items-center gap-2">
                            System Administrator Verification Module
                        </h3>
                        <p class="text-sm text-blue-700 mt-1">You can verify pending WEO accounts.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
