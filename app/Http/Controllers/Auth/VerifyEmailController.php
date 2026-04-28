<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($this->verifiedRedirectFor($request));
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended($this->verifiedRedirectFor($request));
    }

    private function verifiedRedirectFor(EmailVerificationRequest $request): string
    {
        $baseRoute = $request->user()?->role === 'admin'
            ? route('admin.dashboard', absolute: false)
            : route('user.dashboard', absolute: false);

        return $baseRoute.'?verified=1';
    }
}
