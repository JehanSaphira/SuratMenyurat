<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterType;
use Illuminate\Http\Request;

class LetterTypeController extends Controller
{
    public function index()
    {
        return view('admin.letter-types.index', [
            'types' => LetterType::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.letter-types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:letter_types,code'],
            'template_view' => ['required', 'string', 'max:255'],
            'extra_fields' => ['nullable', 'string'],
        ]);

        $data['extra_fields'] = $this->parseExtraFields($data['extra_fields'] ?? null);

        LetterType::create($data);

        return redirect()->route('admin.letter-types.index')->with('status', 'Jenis surat berhasil dibuat.');
    }

    public function edit(LetterType $letterType)
    {
        return view('admin.letter-types.edit', [
            'type' => $letterType,
            'extraFieldsJson' => $letterType->extra_fields ? json_encode($letterType->extra_fields, JSON_PRETTY_PRINT) : '',
        ]);
    }

    public function update(Request $request, LetterType $letterType)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:letter_types,code,' . $letterType->id],
            'template_view' => ['required', 'string', 'max:255'],
            'extra_fields' => ['nullable', 'string'],
        ]);

        $data['extra_fields'] = $this->parseExtraFields($data['extra_fields'] ?? null);

        $letterType->update($data);

        return redirect()->route('admin.letter-types.index')->with('status', 'Jenis surat berhasil diperbarui.');
    }

    public function destroy(LetterType $letterType)
    {
        if ($letterType->letters()->exists()) {
            return redirect()
                ->route('admin.letter-types.index')
                ->with('status', 'Jenis surat tidak bisa dihapus karena sudah digunakan.');
        }

        $letterType->delete();

        return redirect()->route('admin.letter-types.index')->with('status', 'Jenis surat berhasil dihapus.');
    }

    private function parseExtraFields(?string $raw): ?array
    {
        if (!$raw) {
            return null;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return null;
        }

        return $decoded;
    }
}
