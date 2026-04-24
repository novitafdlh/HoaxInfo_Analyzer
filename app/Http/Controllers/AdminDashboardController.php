<?php

namespace App\Http\Controllers;

use App\Models\OfficialContent;
use App\Models\Submission;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $now = Carbon::now();
        $currentMonthStart = $now->copy()->startOfMonth();
        $previousMonthStart = $currentMonthStart->copy()->subMonth();
        $previousMonthEnd = $currentMonthStart->copy()->subSecond();

        $totalOfficialContent = OfficialContent::count();
        $totalSubmissions = Submission::count();
        $pendingValidation = Submission::where('final_status', 'menunggu_validasi')->count();
        $verifiedContent = Submission::where('final_status', 'terverifikasi')->count();
        $officialContentThisMonth = OfficialContent::where('created_at', '>=', $currentMonthStart)->count();
        $officialContentLastMonth = OfficialContent::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();
        $submissionsThisMonth = Submission::where('created_at', '>=', $currentMonthStart)->count();
        $submissionsLastMonth = Submission::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();

        $officialContentGrowth = $this->calculateGrowthRate($officialContentThisMonth, $officialContentLastMonth);
        $submissionGrowth = $this->calculateGrowthRate($submissionsThisMonth, $submissionsLastMonth);
        $verificationAccuracy = $totalSubmissions > 0
            ? round(($verifiedContent / $totalSubmissions) * 100, 1)
            : 0.0;

        return view('admin.dashboard', [
            'totalOfficialContent' => $totalOfficialContent,
            'totalSubmissions' => $totalSubmissions,
            'pendingValidation' => $pendingValidation,
            'verifiedContent' => $verifiedContent,
            'officialContentGrowth' => $officialContentGrowth,
            'submissionGrowth' => $submissionGrowth,
            'verificationAccuracy' => $verificationAccuracy,
        ]);
    }

    private function calculateGrowthRate(int $currentValue, int $previousValue): int
    {
        if ($previousValue === 0) {
            return $currentValue > 0 ? 100 : 0;
        }

        return (int) round((($currentValue - $previousValue) / $previousValue) * 100);
    }
}
