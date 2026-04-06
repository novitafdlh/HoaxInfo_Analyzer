<?php

namespace App\Http\Controllers;

use App\Models\OfficialContent;
use App\Models\Submission;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalOfficialContent = OfficialContent::count();
        $totalSubmissions = Submission::count();
        $pendingValidation = Submission::where('final_status', 'menunggu_validasi')->count();
        $verifiedContent = Submission::where('final_status', 'terverifikasi')->count();

        return view('admin.dashboard', [
            'totalOfficialContent' => $totalOfficialContent,
            'totalSubmissions' => $totalSubmissions,
            'pendingValidation' => $pendingValidation,
            'verifiedContent' => $verifiedContent,
        ]);
    }
}
