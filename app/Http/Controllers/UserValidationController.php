<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\View\View;

class UserValidationController extends Controller
{
    public function index(): View
    {
        $submissions = Submission::query()
            ->with('matchedOfficialContent')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('user.validation-results', [
            'submissions' => $submissions,
        ]);
    }
}
