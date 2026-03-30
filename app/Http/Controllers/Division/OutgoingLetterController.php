<?php

namespace App\Http\Controllers\Division;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Letter;
use App\Models\LetterTarget;
use App\Models\LetterType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OutgoingLetterController extends Controller
{
    public function index()
    {
        $divisionId = request()->user()->division_id;

        return view('division.outgoing.index', [
            'letters' => Letter::with('type')
                ->where('division_id', $divisionId)
                ->latest()
                ->paginate(15),
        ]);
    }

    public function create(Request $request)
    {
        $types = LetterType::orderBy('name')->get()->map(function (LetterType $type) {
            $type->effective_fields = $this->resolveExtraFields($type);
            return $type;
        });
        $divisions = Division::where('id', '!=', $request->user()->division_id)
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $parent = null;
        if ($request->filled('parent_id')) {
            $parent = Letter::with('division')->find($request->input('parent_id'));
        }

        return view('division.outgoing.create', [
            'types' => $types,
            'divisions' => $divisions,
            'parent' => $parent,
            'defaultTargetId' => $request->input('default_target_id'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'number' => ['nullable', 'string', 'max:255', 'unique:letters,number'],
            'letter_type_id' => ['required', 'exists:letter_types,id'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'targets' => ['required', 'array', 'min:1'],
            'targets.*' => ['exists:divisions,id'],
            'attachment' => ['nullable', 'file', 'max:2048'],
            'parent_id' => ['nullable', 'exists:letters,id'],
        ]);

        $letterType = LetterType::findOrFail($data['letter_type_id']);
        $division = $request->user()->division;

        $extraData = $this->extractExtraFields($request, $letterType);

        $attachments = [];
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $attachments[] = $path;
        }

        $manualNumber = $data['number'] ?? null;
        $number = $manualNumber ?: $division->generateNumber($letterType);

        $letter = Letter::create([
            'division_id' => $division->id,
            'letter_type_id' => $letterType->id,
            'parent_id' => $data['parent_id'] ?? null,
            'created_by' => $request->user()->id,
            'number' => $number,
            'subject' => $data['subject'],
            'body' => $data['body'],
            'cc' => null,
            'data' => $extraData,
            'attachments' => $attachments,
            'status' => 'sent',
            'sent_at' => Carbon::now(),
        ]);

        if (!empty($data['parent_id'])) {
            $parentTarget = LetterTarget::where('letter_id', $data['parent_id'])
                ->where('division_id', $division->id)
                ->first();
            if ($parentTarget) {
                $parentTarget->update([
                    'status' => 'replied',
                    'decided_at' => Carbon::now(),
                ]);

                $pending = $parentTarget->letter->targets()->whereIn('status', ['pending', 'read'])->count();
                if ($pending === 0) {
                    $parentTarget->letter->update(['status' => 'completed']);
                }
            }
        }

        foreach (array_unique($data['targets']) as $targetId) {
            LetterTarget::create([
                'letter_id' => $letter->id,
                'division_id' => $targetId,
                'status' => 'pending',
            ]);
        }

        $letter->regeneratePdf();

        return redirect()->route('division.outgoing.index')->with('status', 'Surat berhasil dikirim.');
    }

    public function show(Letter $letter)
    {
        $this->authorizeLetter($letter);

        return view('division.outgoing.show', [
            'letter' => $letter->load(['type', 'targets.division']),
        ]);
    }

    public function download(Letter $letter)
    {
        $this->authorizeLetter($letter);

        $path = $letter->regeneratePdf();

        return Storage::disk('public')->download($path);
    }

    public function destroy(Letter $letter)
    {
        $this->authorizeLetter($letter);

        Letter::where('parent_id', $letter->id)->update(['parent_id' => null]);

        $paths = [];
        if (!empty($letter->pdf_path)) {
            $paths[] = $letter->pdf_path;
        }
        if (!empty($letter->attachments)) {
            $paths = array_merge($paths, $letter->attachments);
        }

        if (!empty($paths)) {
            Storage::disk('public')->delete($paths);
        }

        $letter->targets()->delete();
        $letter->delete();

        return redirect()->route('division.outgoing.index')->with('status', 'Surat berhasil dihapus.');
    }

    private function authorizeLetter(Letter $letter): void
    {
        if ($letter->division_id !== request()->user()->division_id) {
            abort(403);
        }
    }

    private function extractExtraFields(Request $request, LetterType $letterType): array
    {
        $extra = [];
        $fields = $this->resolveExtraFields($letterType);

        foreach ($fields as $field) {
            $key = $field['key'] ?? null;
            if (!$key) {
                continue;
            }
            $value = $request->input('extra.' . $key);
            if (!empty($field['required']) && ($value === null || $value === '')) {
                abort(422, 'Field tambahan wajib diisi: ' . $key);
            }
            $extra[$key] = $value;
        }

        return $extra;
    }

    private function resolveExtraFields(LetterType $letterType): array
    {
        $fields = $letterType->extra_fields ?? [];
        if (!empty($fields)) {
            return $fields;
        }

        if (($letterType->template_view ?? null) === 'letters.pdf.default') {
            return [
                ['key' => 'tanggal', 'label' => 'Hari/Tanggal', 'type' => 'date', 'required' => false],
                ['key' => 'waktu', 'label' => 'Waktu', 'type' => 'text', 'required' => false],
                ['key' => 'tempat', 'label' => 'Tempat', 'type' => 'text', 'required' => false],
                ['key' => 'agenda', 'label' => 'Agenda', 'type' => 'textarea', 'required' => false],
            ];
        }

        return [];
    }

 
}
