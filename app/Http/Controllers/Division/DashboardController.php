<?php

namespace App\Http\Controllers\Division;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\LetterTarget;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $divisionId = request()->user()->division_id;
        $year = now()->year;

        $outgoingByMonth = Letter::where('division_id', $divisionId)
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $incomingByMonth = LetterTarget::join('letters', 'letter_targets.letter_id', '=', 'letters.id')
            ->where('letter_targets.division_id', $divisionId)
            ->whereYear('letters.created_at', $year)
            ->selectRaw('MONTH(letters.created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $chartLabels = [];
        $outgoingSeries = [];
        $incomingSeries = [];

        for ($m = 1; $m <= 12; $m++) {
            $chartLabels[] = now()->setMonth($m)->translatedFormat('M');
            $outgoingSeries[] = (int) ($outgoingByMonth[$m] ?? 0);
            $incomingSeries[] = (int) ($incomingByMonth[$m] ?? 0);
        }

        return view('division.dashboard', [
            'outgoing' => Letter::with('type')->where('division_id', $divisionId)->latest()->take(10)->get(),
            'incoming' => LetterTarget::with(['letter.type', 'letter.division'])
                ->where('division_id', $divisionId)
                ->latest()
                ->take(10)
                ->get(),
            'pendingCount' => LetterTarget::where('division_id', $divisionId)->where('status', 'pending')->count(),
            'chartLabels' => $chartLabels,
            'outgoingSeries' => $outgoingSeries,
            'incomingSeries' => $incomingSeries,
            'chartYear' => $year,
        ]);
    }

    public function recap()
    {
        $divisionId = request()->user()->division_id;
        $day = request('day');
        $month = request('month');
        $year = request('year');

        $outgoingQuery = Letter::with('type')
            ->where('division_id', $divisionId)
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
            ->where('division_id', $divisionId)
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

        return view('division.recap', [
            'outgoing' => $outgoingQuery->get(),
            'incoming' => $incomingQuery->get(),
            'day' => $day,
            'month' => $month,
            'year' => $year,
        ]);
    }
}
