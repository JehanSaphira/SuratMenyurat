<?php

namespace App\Http\Controllers\Division;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\LetterTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IncomingLetterController extends Controller
{
    public function index(Request $request)
    {
        $divisionId = $request->user()->division_id;

        return view('division.incoming.index', [
            'targets' => LetterTarget::with(['letter.type', 'letter.division'])
                ->where('division_id', $divisionId)
                ->latest()
                ->paginate(15),
        ]);
    }

    public function show(LetterTarget $target)
    {
        $this->authorizeTarget($target);

        if ($target->status === 'pending') {
            $target->update(['status' => 'read']);
        }

        return view('division.incoming.show', [
            'target' => $target->load(['letter.type', 'letter.division']),
        ]);
    }

    public function download(LetterTarget $target)
    {
        $this->authorizeTarget($target);

        $path = $target->letter->regeneratePdf();

        return Storage::disk('public')->download($path);
    }

    public function approve(LetterTarget $target)
    {
        $this->authorizeTarget($target);

        $target->update([
            'status' => 'approved',
            'decided_at' => now(),
        ]);

        $this->updateGlobalStatus($target->letter);

        return back()->with('status', 'Surat disetujui.');
    }

    public function reject(LetterTarget $target)
    {
        $this->authorizeTarget($target);

        $target->update([
            'status' => 'rejected',
            'decided_at' => now(),
        ]);

        $this->updateGlobalStatus($target->letter);

        return back()->with('status', 'Surat ditolak.');
    }

    public function reply(LetterTarget $target)
    {
        $this->authorizeTarget($target);

        return redirect()->route('division.outgoing.create', [
            'parent_id' => $target->letter_id,
            'default_target_id' => $target->letter->division_id,
        ]);
    }

    private function authorizeTarget(LetterTarget $target): void
    {
        if ($target->division_id !== request()->user()->division_id) {
            abort(403);
        }
    }

    private function updateGlobalStatus(Letter $letter): void
    {
        $pending = $letter->targets()->whereIn('status', ['pending', 'read'])->count();
        if ($pending === 0) {
            $letter->update(['status' => 'completed']);
        }
    }
}
