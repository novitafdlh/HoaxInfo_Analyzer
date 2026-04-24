<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $guestSessionId = $request->session()->getId();

        $request->session()->regenerate();
        Submission::claimGuestSubmissionsToUser($guestSessionId, (int) $request->user()->id);

        if (! $request->user()?->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return redirect()->intended($this->dashboardRouteFor($request));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function dashboardRouteFor(Request $request): string
    {
        return $request->user()?->role === 'admin'
            ? route('admin.dashboard', absolute: false)
            : route('user.dashboard', absolute: false);
    }
}
