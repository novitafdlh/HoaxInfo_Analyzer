<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminUserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $verification = trim((string) $request->query('verification', ''));

        $users = User::query()
            ->where('role', 'user')
            ->withCount('submissions')
            ->withCount([
                'submissions as verified_submissions_count' => fn ($query) => $query->where('final_status', 'terverifikasi'),
                'submissions as pending_submissions_count' => fn ($query) => $query->where('final_status', 'menunggu_validasi'),
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                });
            })
            ->when($verification === 'verified', fn ($query) => $query->whereNotNull('email_verified_at'))
            ->when($verification === 'unverified', fn ($query) => $query->whereNull('email_verified_at'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $userSummary = [
            'total' => User::where('role', 'user')->count(),
            'verified' => User::where('role', 'user')->whereNotNull('email_verified_at')->count(),
            'unverified' => User::where('role', 'user')->whereNull('email_verified_at')->count(),
            'with_submissions' => User::where('role', 'user')->whereHas('submissions')->count(),
        ];

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'verification' => $verification,
            'userSummary' => $userSummary,
        ]);
    }

    public function show(User $user): View
    {
        $managedUser = $this->managedUser($user);

        $managedUser->loadCount([
            'submissions',
            'submissions as verified_submissions_count' => fn ($query) => $query->where('final_status', 'terverifikasi'),
            'submissions as pending_submissions_count' => fn ($query) => $query->where('final_status', 'menunggu_validasi'),
        ]);

        $recentSubmissions = Submission::query()
            ->where('user_id', $managedUser->id)
            ->latest()
            ->take(6)
            ->get();

        return view('admin.users.show', [
            'managedUser' => $managedUser,
            'recentSubmissions' => $recentSubmissions,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $managedUser = $this->managedUser($user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$managedUser->id],
            'email_verification_status' => ['required', 'in:verified,unverified'],
        ]);

        $managedUser->update([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'email_verified_at' => $validated['email_verification_status'] === 'verified' ? now() : null,
            'role' => 'user',
        ]);

        return back()->with('success', 'Data akun user berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $managedUser = $this->managedUser($user);

        DB::transaction(function () use ($managedUser) {
            Submission::query()
                ->where('user_id', $managedUser->id)
                ->update([
                    'user_id' => null,
                    'guest_session_id' => null,
                ]);

            if (DB::getSchemaBuilder()->hasTable('sessions')) {
                DB::table('sessions')->where('user_id', $managedUser->id)->delete();
            }

            $managedUser->notifications()->delete();
            $managedUser->delete();
        });

        return redirect()->route('admin.users.index')->with('success', 'Akun user berhasil dihapus.');
    }

    private function managedUser(User $user): User
    {
        abort_unless($user->role === 'user', 404);

        return $user;
    }
}
