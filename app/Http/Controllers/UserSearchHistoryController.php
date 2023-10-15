<?php

namespace App\Http\Controllers;

use App\Models\Keywords;
use App\Models\User;
use App\Models\UserSearchHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;



class UserSearchHistoryController extends Controller
{
    public function show()
    {
        $users = User::select('id', 'name')->get();
        $keywords = Keywords::select('id', 'keyword')->get();
        return view('search_history', compact('users', 'keywords'));
    }

    public function filterSearchHistory(Request $request): JsonResponse
    {
        $keywords = $request->input('keywords', []);
        $userIds = $request->input('users', []);
        $yesterday = $request->has('yesterday');
        $lastWeek = $request->has('last-week');
        $lastMonth = $request->has('last-month');
        $startDate = $request->input('start-date');
        $endDate = $request->input('end-date');

        // Empty check
        if (empty($keywords)) {
            return response()->json(['error' => 'Please select at least one keyword'], 400);
        }
        if (empty($userIds)) {
            return response()->json(['error' => 'Please select at least one user'], 400);
        }

        // Query the search history data based on filters
        $query = UserSearchHistory::query();

        // Apply keyword filter
        if (!empty($keywords)) {
            $query->whereIn('search_keyword', $keywords);
        }

        // Apply user filter
        if (!empty($userIds)) {
            $query->whereIn('user_id', $userIds);
        }

        // Apply yesterday filter
        if ($yesterday) {
            $query->whereDate('searched_at', now()->subDay());
        }

        // Apply last week filter
        if ($lastWeek) {
            $query->whereDate('searched_at', '>=', now()->subWeek());
        }

        // Apply last month filter
        if ($lastMonth) {
            $query->whereDate('searched_at', '>=', now()->subMonth());
        }

        // Apply date range filter
        if ($startDate && $endDate) {
            $query->whereBetween('searched_at', [$startDate, $endDate]);
        }
        $filterResult = $query->with('user')->get();

        // Keyword Count
        $keywordCounts = countKeywords($filterResult, $keywords);

        // Return final response
        return response()->json(['data' => $filterResult, 'keywords' => $keywordCounts], 200);
    }
}
