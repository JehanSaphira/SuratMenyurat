<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Letter extends Model
{
    protected $fillable = [
        'division_id',
        'letter_type_id',
        'parent_id',
        'created_by',
        'number',
        'subject',
        'body',
        'cc',
        'data',
        'attachments',
        'pdf_path',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'data' => 'array',
        'attachments' => 'array',
        'sent_at' => 'datetime',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(LetterType::class, 'letter_type_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Letter::class, 'parent_id');
    }

    public function targets(): HasMany
    {
        return $this->hasMany(LetterTarget::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function regeneratePdf(): string
    {
        $this->loadMissing(['type', 'division', 'targets.division']);

        $view = $this->type?->template_view ?: 'letters.pdf.default';
        $html = view($view, ['letter' => $this])->render();

        $filename = 'letters/' . $this->id . '.pdf';

        if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            Storage::disk('public')->put($filename, $pdf->output());
            $this->update(['pdf_path' => $filename]);
            return $filename;
        }

        $fallback = 'letters/' . $this->id . '.html';
        Storage::disk('public')->put($fallback, $html);
        $this->update(['pdf_path' => $fallback]);
        return $fallback;
    }
}
