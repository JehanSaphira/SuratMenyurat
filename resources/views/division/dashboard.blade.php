@extends('layouts.app')

@section('content')
<div class="grid grid-2">
    <div class="card">
        <h3>Notifikasi</h3>
        <p>Surat menunggu review: <strong>{{ $pendingCount }}</strong></p>
    </div>
    <div class="card">
        <h3>Aksi Cepat</h3>
        <a class="btn" href="{{ route('division.outgoing.create') }}">Buat Surat</a>
    </div>
</div>

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
        <div>
            <div style="color:#64748b;">Grafik Surat</div>
            <div style="font-size:18px;font-weight:700;margin-top:6px;">Surat Masuk & Keluar ({{ $chartYear }})</div>
        </div>
        <div class="tag">Per bulan</div>
    </div>
    <div style="margin-top:14px;">
        <canvas id="divisionLettersChart" height="120"></canvas>
    </div>
</div>

<div class="card">
    <h3>Surat Keluar Terbaru</h3>
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Jenis</th>
                <th>Perihal</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($outgoing as $letter)
                <tr>
                    <td>{{ $letter->number }}</td>
                    <td>{{ $letter->type?->name }}</td>
                    <td>{{ $letter->subject }}</td>
                    <td>{{ $letter->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Surat Masuk Terbaru</h3>
    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Pengirim</th>
                <th>Perihal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incoming as $target)
                <tr>
                    <td>{{ $target->letter->number }}</td>
                    <td>{{ $target->letter->division?->name }}</td>
                    <td>{{ $target->letter->subject }}</td>
                    <td>{{ $target->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        const ctx = document.getElementById('divisionLettersChart');
        if (!ctx || !window.Chart) return;

        const labels = @json($chartLabels);
        const outgoing = @json($outgoingSeries);
        const incoming = @json($incomingSeries);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Surat Keluar',
                        data: outgoing,
                        backgroundColor: '#4f9ad7',
                        borderColor: '#2f6fb2',
                        borderWidth: 1,
                        borderRadius: 8,
                    },
                    {
                        label: 'Surat Masuk',
                        data: incoming,
                        backgroundColor: '#9cc8f0',
                        borderColor: '#60a5fa',
                        borderWidth: 1,
                        borderRadius: 8,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(100, 116, 139, 0.15)' },
                        ticks: { color: '#64748b' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b' }
                    }
                }
            }
        });
    })();
</script>
@endsection
