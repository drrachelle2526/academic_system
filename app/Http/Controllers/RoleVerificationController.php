<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleVerificationController extends Controller
{
    public function approve(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role_status === 'pending', 404);
        abort_unless($this->canApprove($request->user(), $user), 403);

        $user->update(['role_status' => 'approved']);

        return redirect()
            ->route('dashboard')
            ->with('status', "{$user->name}'s account has been approved.");
    }

    private function canApprove(User $approver, User $candidate): bool
    {
        return match ($candidate->role) {
            'academic_teacher' => $approver->role === 'headmaster'
                && $approver->role_status === 'approved'
                && $approver->school_id === $candidate->school_id,
            'headmaster' => $approver->role === 'weo'
                && $approver->role_status === 'approved'
                && str($candidate->school?->ward)->trim()->lower()->toString() === str($approver->ward)->trim()->lower()->toString(),
            'weo' => $approver->role === 'admin'
                && $approver->role_status === 'approved',
            default => false,
        };
    }
}
