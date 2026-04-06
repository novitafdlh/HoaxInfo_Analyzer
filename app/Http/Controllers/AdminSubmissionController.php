<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSubmissionController extends Controller
{
    public function index(): View
    {
        $submissions = Submission::latest()->paginate(10);

        return view('admin.submissions.index', [
            'submissions' => $submissions,
        ]);
    }

    public function show(Submission $submission): View
    {
        return view('admin.submissions.show', [
            'submission' => $submission,
        ]);
    }

    public function updateFinalStatus(Request $request, Submission $submission): RedirectResponse
    {
        $validated = $request->validate([
            'final_status' => ['required', 'in:terverifikasi,tidak_valid'],
        ]);

        $submission->update([
            'final_status' => $validated['final_status'],
        ]);

        return back()->with('status', 'Status final submission berhasil diperbarui.');
    }
}
