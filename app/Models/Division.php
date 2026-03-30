<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    protected $fillable = [
        'name',
        'code',
        'number_format',
        'active',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class);
    }

    public function targets(): HasMany
    {
        return $this->hasMany(LetterTarget::class);
    }

    public function generateNumber(LetterType $letterType): string
    {
        $now = Carbon::now();
        $month = (int) $now->format('n');
        $year = (int) $now->format('Y');

        $sequenceValue = $this->letters()->count() + 1;
        $sequence = str_pad((string) $sequenceValue, 3, '0', STR_PAD_LEFT);
        $romanMonth = $this->romanMonth($month);

        $number = str_replace([
            '{urut}',
            '{kodeDivisi}',
            '{bulan}',
            '{tahun}',
            '{kodeJenis}',
        ], [
            $sequence,
            $this->code,
            $romanMonth,
            (string) $year,
            $letterType->code,
        ], $this->number_format);

        return $number;
    }

    private function romanMonth(int $month): string
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];

        return $map[$month] ?? (string) $month;
    }
}
