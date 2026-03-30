<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        return view('admin.divisions.index', [
            'divisions' => Division::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.divisions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:divisions,code'],
            'number_format' => ['required', 'string', 'max:255'],
        ]);

        Division::create($data);

        return redirect()->route('admin.divisions.index')->with('status', 'Divisi berhasil dibuat.');
    }

    public function edit(Division $division)
    {
        return view('admin.divisions.edit', compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:divisions,code,' . $division->id],
            'number_format' => ['required', 'string', 'max:255'],
        ]);

        $division->update($data);

        return redirect()->route('admin.divisions.index')->with('status', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        if ($division->users()->exists()) {
            return redirect()->route('admin.divisions.index')
                ->with('status', 'Divisi tidak bisa dihapus karena masih memiliki akun.');
        }

        if ($division->letters()->exists() || $division->targets()->exists()) {
            return redirect()->route('admin.divisions.index')
                ->with('status', 'Divisi tidak bisa dihapus karena sudah memiliki surat.');
        }

        $division->delete();

        return redirect()->route('admin.divisions.index')->with('status', 'Divisi berhasil dihapus.');
    }

}
