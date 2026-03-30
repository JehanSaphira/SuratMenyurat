<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\LetterType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $ops = Division::updateOrCreate(
            ['code' => 'OPS'],
            [
                'name' => 'Operasional',
                'number_format' => '{urut}/{kodeDivisi}/{bulan}/{tahun}',
            ]
        );

        $keu = Division::updateOrCreate(
            ['code' => 'KEU'],
            [
                'name' => 'Keuangan',
                'number_format' => '{urut}/{kodeDivisi}/{bulan}/{tahun}',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@local.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'ops@local.test'],
            [
                'name' => 'Akun OPS',
                'password' => Hash::make('password'),
                'role' => 'division',
                'division_id' => $ops->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'keu@local.test'],
            [
                'name' => 'Akun KEU',
                'password' => Hash::make('password'),
                'role' => 'division',
                'division_id' => $keu->id,
            ]
        );

        LetterType::updateOrCreate(
            ['code' => 'MEMO'],
            [
                'name' => 'Memo',
                'template_view' => 'letters.pdf.default',
                'extra_fields' => [],
            ]
        );

        LetterType::updateOrCreate(
            ['code' => 'UND'],
            [
                'name' => 'Undangan',
                'template_view' => 'letters.pdf.default',
                'extra_fields' => [
                    ['key' => 'tanggal', 'label' => 'Tanggal', 'type' => 'date', 'required' => true],
                    ['key' => 'waktu', 'label' => 'Waktu', 'type' => 'time', 'required' => true],
                    ['key' => 'tempat', 'label' => 'Tempat', 'type' => 'text', 'required' => true],
                    ['key' => 'agenda', 'label' => 'Agenda', 'type' => 'text', 'required' => false],
                ],
            ]
        );
    }
}
