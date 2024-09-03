<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Event;
use App\Models\Partner;
use App\Models\Speaker;
use App\Models\Supplier;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index()
    {
        $themes_most_sale = Theme::select('theme')
            ->withCount(['events' => function ($query) {
                $query->where('status', 3);
            }])
            ->having('events_count', '>', 0)
            ->orderByDesc('events_count')
            ->get();
        $speakers_most_sale = Speaker::select('id', 'name')
            ->whereHas('events', function ($query) {
                $query->where('status', 3);
            })
            ->withCount(['events' => function ($query) {
                $query->where('status', 3);
            }])
            ->orderByDesc('events_count')
            ->get();

        // dd($themes_most_sale);

        $total_value = Event::where('status', 3)->sum('total_price');
        return view('statistics/statistics', compact(['total_value', 'speakers_most_sale', 'themes_most_sale']));
    }

    public function calculateSum(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $totalSum = Event::whereBetween('created_at', [$startDate, $endDate])->sum('total_price');

        return response()->json(['totalSum' => $totalSum]);
    }
}
