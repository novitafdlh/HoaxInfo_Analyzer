<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Notifications\SubmissionStatusUpdatedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminSubmissionController extends Controller
{
    public function index(): View
    {
        $submissions = Submission::with('matchedOfficialContent')->latest()->paginate(10);

        return view('admin.submissions.index', [
            'submissions' => $submissions,
        ]);
    }

    public function show(Submission $submission): View
    {
        $submission->load('matchedOfficialContent');

        return view('admin.submissions.show', [
            'submission' => $submission,
        ]);
    }

    public function updateFinalStatus(Request $request, Submission $submission): RedirectResponse
    {
        $validated = $request->validate([
            'final_status' => ['required', 'in:terverifikasi,perlu_tindak_lanjut,menunggu_validasi'],
        ]);

        $wasChanged = $submission->final_status !== $validated['final_status'];

        $submission->update([
            'final_status' => $validated['final_status'],
        ]);

        if ($wasChanged && $submission->user && Schema::hasTable('notifications')) {
            $submission->user->notify(new SubmissionStatusUpdatedNotification($submission->fresh('matchedOfficialContent')));
        }

        return back()->with('status', 'Status final submission berhasil diperbarui.');
    }
}
