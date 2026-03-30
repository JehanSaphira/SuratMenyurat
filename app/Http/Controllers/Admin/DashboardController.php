<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Letter;
use App\Models\LetterType;
use App\Models\LetterTarget;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'divisionCount' => Division::count(),
            'letterTypeCount' => LetterType::count(),
            'userCount' => User::where('role', 'division')->count(),
            'letterCount' => Letter::count(),
        ]);
    }

    public function recap()
    {
        $day = request('day');
        $month = request('month');
        $year = request('year');

        $outgoingQuery = Letter::with(['type', 'division'])
            ->orderByDesc('created_at');

        if (!empty($day)) {
            $outgoingQuery->whereDay('created_at', $day);
        }
        if (!empty($month)) {
            $outgoingQuery->whereMonth('created_at', $month);
        }
        if (!empty($year)) {
            $outgoingQuery->whereYear('created_at', $year);
        }

        $incomingQuery = LetterTarget::with(['letter.type', 'letter.division'])
            ->orderByDesc('created_at');

        if (!empty($day) || !empty($month) || !empty($year)) {
            $incomingQuery->whereHas('letter', function ($q) use ($day, $month, $year) {
                if (!empty($day)) {
                    $q->whereDay('created_at', $day);
                }
                if (!empty($month)) {
                    $q->whereMonth('created_at', $month);
                }
                if (!empty($year)) {
                    $q->whereYear('created_at', $year);
                }
            });
        }

        return view('admin.recap', [
            'outgoing' => $outgoingQuery->get(),
            'incoming' => $incomingQuery->get(),
            'day' => $day,
            'month' => $month,
            'year' => $year,
        ]);
    }
}
