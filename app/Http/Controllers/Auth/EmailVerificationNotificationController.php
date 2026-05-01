<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($this->dashboardRouteFor($request));
        }

        if (User::usesFormalEmailVerification()) {
            return back()->with('status', 'verification-link-sent');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    private function dashboardRouteFor(Request $request): string
    {
        return $request->user()?->role === 'admin'
            ? route('admin.dashboard', absolute: false)
            : route('user.dashboard', absolute: false);
    }
}
