<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Training;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Training::published()
            ->with(['category'])
            ->withCount('enrollments');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('price')) {
            if ($request->price === 'free') {
                $query->where('is_free', true);
            } elseif ($request->price === 'paid') {
                $query->where('is_free', false);
            }
        }

        if ($request->filled('skp')) {
            if ($request->skp === 'yes') {
                $query->where('skp_value', '>', 0);
            } else {
                $query->where('skp_value', 0);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('schedule', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('schedule', '<=', $request->date_to);
        }

        $trainings  = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::all();

        $allSchedules = Training::published()
            ->whereNotNull('schedule')
            ->selectRaw('DATE(schedule) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        return view('catalog.index', compact('trainings', 'categories', 'allSchedules'));
    }

    public function show(string $slug)
    {
        $training = Training::published()
            ->with(['category'])
            ->withCount('enrollments')
            ->where('slug', $slug)
            ->firstOrFail();

        $enrolled = false;
        if (auth()->check()) {
            $enrolled = auth()->user()->isEnrolled($training);
        }

        $related = Training::published()
            ->with(['category'])
            ->withCount('enrollments')
            ->where('category_id', $training->category_id)
            ->where('id', '!=', $training->id)
            ->limit(4)
            ->get();

        return view('catalog.show', compact('training', 'enrolled', 'related'));
    }
}
