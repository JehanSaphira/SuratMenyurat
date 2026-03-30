@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Detail Surat Masuk</h2>
    <p><strong>Nomor:</strong> {{ $target->letter->number }}</p>
    <p><strong>Jenis:</strong> {{ $target->letter->type?->name }}</p>
    <p><strong>Pengirim:</strong> {{ $target->letter->division?->name }}</p>
    <p><strong>Perihal:</strong> {{ $target->letter->subject }}</p>
    <p><strong>Status:</strong> {{ $target->status }}</p>

    <a class="btn" href="{{ route('division.incoming.download', $target) }}">Unduh PDF</a>

    <div style="margin-top:12px;" id="incoming-action-buttons">
        @if(in_array($target->status, ['pending', 'read'], true))
            <form method="post" action="{{ route('division.incoming.approve', $target) }}" style="display:inline;" class="action-form" data-action="approve">
                @csrf
                <button class="btn" type="submit">Approve</button>
            </form>
            <form method="post" action="{{ route('division.incoming.reject', $target) }}" style="display:inline;" class="action-form" data-action="reject">
                @csrf
                <button class="btn danger" type="submit">Reject</button>
            </form>
        @endif
        <form method="post" action="{{ route('division.incoming.reply', $target) }}" style="display:inline;">
            @csrf
            <button class="btn secondary" type="submit">Balas</button>
        </form>
        <span id="incoming-action-hint" style="margin-left:8px;color:#64748b;"></span>
    </div>
</div>
<script>
    (function () {
        const container = document.getElementById('incoming-action-buttons');
        if (!container) return;

        const forms = Array.from(container.querySelectorAll('.action-form'));
        const hint = document.getElementById('incoming-action-hint');

        forms.forEach(form => {
            form.addEventListener('submit', () => {
                if (form.dataset.submitting === '1') return;
                form.dataset.submitting = '1';

                const btn = form.querySelector('button');
                if (btn) {
                    btn.disabled = true;
                    btn.style.opacity = '0.6';
                    btn.textContent = 'Sudah diklik...';
                }
                if (hint) {
                    hint.textContent = 'Sedang memproses tindakan...';
                }
            });
        });
    })();
</script>
@endsection
