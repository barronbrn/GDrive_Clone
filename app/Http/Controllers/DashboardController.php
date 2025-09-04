<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // The main 'index' method is now handled by FileController.
    // This controller will handle the other dashboard-related views.

    public function recent(Request $request)
    {
        $recentItems = collect();
        if (Auth::check()) {
            $query = File::where('created_by', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(50);

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where('name', 'like', '%'.$search.'%');
            }

            $recentItems = $query->get();
        }

        return view('recent', ['recentItems' => $recentItems]);
    }

    public function trash(Request $request)
    {
        $trashedItems = collect();
        if (Auth::check()) {
            $query = File::where('created_by', Auth::id())
                ->onlyTrashed()
                ->orderBy('deleted_at', 'desc');

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where('name', 'like', '%'.$search.'%');
            }

            $trashedItems = $query->get();
        }

        return view('trash', ['trashedItems' => $trashedItems]);
    }
}
