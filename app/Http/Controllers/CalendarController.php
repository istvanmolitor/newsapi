<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->query('year', now()->year);
        $month = (int) $request->query('month', now()->month);

        // Normalize year/month
        $date = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $year = (int) $date->year;
        $month = (int) $date->month;

        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Determine the first day to show (start from Monday)
        $startOfCalendar = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        $days = [];
        $cursor = $startOfCalendar->copy();
        while ($cursor->lte($endOfCalendar)) {
            $days[] = [
                'date' => $cursor->copy(),
                'isCurrentMonth' => $cursor->month === $month,
                'isToday' => $cursor->isToday(),
            ];
            $cursor->addDay();
        }

        $prev = $date->copy()->subMonth();
        $next = $date->copy()->addMonth();

        return view('calendar.index', [
            'title' => 'Naptár',
            'year' => $year,
            'month' => $month,
            'monthName' => $date->translatedFormat('F'),
            'days' => $days,
            'prevYear' => $prev->year,
            'prevMonth' => $prev->month,
            'nextYear' => $next->year,
            'nextMonth' => $next->month,
        ]);
    }
}
