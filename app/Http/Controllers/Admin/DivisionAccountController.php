<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DivisionAccountController extends Controller
{
    public function index()
    {
        return view('admin.accounts.index', [
            'accounts' => User::with('division')->where('role', 'division')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.accounts.create', [
            'divisions' => Division::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'division_id' => ['required', 'exists:divisions,id'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $data['role'] = 'division';
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.accounts.index')->with('status', 'Akun divisi berhasil dibuat.');
    }

    public function edit(User $account)
    {
        if ($account->role !== 'division') {
            abort(404);
        }

        return view('admin.accounts.edit', [
            'account' => $account,
            'divisions' => Division::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $account)
    {
        if ($account->role !== 'division') {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $account->id],
            'division_id' => ['required', 'exists:divisions,id'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $account->update($data);

        return redirect()->route('admin.accounts.index')->with('status', 'Akun divisi berhasil diperbarui.');
    }

    public function destroy(User $account)
    {
        if ($account->role !== 'division') {
            abort(404);
        }

        \App\Models\Letter::where('created_by', $account->id)->update(['created_by' => null]);
        \Illuminate\Support\Facades\DB::table('sessions')->where('user_id', $account->id)->delete();

        $account->delete();

        return redirect()->route('admin.accounts.index')->with('status', 'Akun divisi berhasil dihapus.');
    }
}
