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

        $data = ['role_status' => 'approved'];

        if ($request->user()->role === 'weo') {
            $request->validate([
                'assigned_role' => ['required', 'string', 'in:headmaster'],
            ]);

            $data['role'] = 'headmaster';
        }

        if ($request->user()->role === 'headmaster') {
            $request->validate([
                'assigned_role' => ['required', 'string', 'in:teacher,academic_teacher'],
            ]);

            $data['role'] = $request->assigned_role;
            $data['school_id'] = $request->user()->school_id;
        }

        $user->update($data);

        return redirect()
            ->route('dashboard')
            ->with('status', "{$user->name}'s account has been approved.");
    }

    private function canApprove(User $approver, User $candidate): bool
    {
        return match ($candidate->role) {
            'academic_teacher', 'teacher' => (
                $approver->role === 'headmaster'
                && $approver->role_status === 'approved'
                && (
                    $approver->school_id === $candidate->school_id
                    || (
                        $candidate->school_id === null
                        && str($candidate->ward)->trim()->lower()->toString() === str($approver->school?->ward)->trim()->lower()->toString()
                    )
                )
            ) || (
                $approver->role === 'weo'
                && $approver->role_status === 'approved'
                && str($candidate->school?->ward)->trim()->lower()->toString() === str($approver->ward)->trim()->lower()->toString()
            ),
            'headmaster' => $approver->role === 'weo'
                && $approver->role_status === 'approved'
                && str($candidate->school?->ward)->trim()->lower()->toString() === str($approver->ward)->trim()->lower()->toString(),
            'weo' => $approver->role === 'admin'
                && $approver->role_status === 'approved',
            default => false,
        };
    }
}
