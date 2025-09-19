<?php

namespace App\Http\Controllers;

use App\Models\Article;
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

    public function day(string $date, Request $request)
    {
        try {
            $day = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        } catch (\Exception $e) {
            abort(404);
        }

        $start = $day->copy()->startOfDay();
        $end = $day->copy()->endOfDay();

        $perPage = (int) $request->input('per_page', 12);
        $perPage = $perPage > 0 && $perPage <= 50 ? $perPage : 12;

        $articles = Article::whereBetween('created_at', [$start, $end])
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('calendar.day', [
            'title' => 'Cikkek: ' . $day->format('Y.m.d'),
            'day' => $day,
            'articles' => $articles,
        ]);
    }
}
