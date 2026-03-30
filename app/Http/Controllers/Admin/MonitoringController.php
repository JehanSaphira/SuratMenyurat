<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Letter;
use App\Models\LetterType;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $query = Letter::with(['division', 'type', 'targets.division'])->orderByDesc('created_at');

        if ($request->filled('division_id')) {
            $query->where('division_id', $request->input('division_id'));
        }

        if ($request->filled('target_division_id')) {
            $query->whereHas('targets', function ($q) use ($request) {
                $q->where('division_id', $request->input('target_division_id'));
            });
        }

        if ($request->filled('letter_type_id')) {
            $query->where('letter_type_id', $request->input('letter_type_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        return view('admin.monitoring.index', [
            'letters' => $query->paginate(15)->withQueryString(),
            'divisions' => Division::orderBy('name')->get(),
            'types' => LetterType::orderBy('name')->get(),
        ]);
    }
}
