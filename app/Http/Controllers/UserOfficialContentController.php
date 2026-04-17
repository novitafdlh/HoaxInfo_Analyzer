<?php

namespace App\Http\Controllers;

use App\Models\OfficialContent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserOfficialContentController extends Controller
{
    public function index(Request $request): View
    {
        $categories = OfficialContent::query()
            ->select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $selectedCategory = trim((string) $request->query('category', ''));
        $search = trim((string) $request->query('search', ''));

        $officialContents = OfficialContent::query()
            ->when($selectedCategory !== '', function ($query) use ($selectedCategory) {
                $query->where('category', $selectedCategory);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('title', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%');
                });
            })
            ->latest()
            ->get();

        return view('user.official-contents.index', [
            'officialContents' => $officialContents,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'search' => $search,
        ]);
    }

    public function show(OfficialContent $officialContent): View
    {
        return view('user.official-contents.show', [
            'officialContent' => $officialContent,
        ]);
    }
}
