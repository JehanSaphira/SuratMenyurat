@extends('layouts.app')

@section('content')
<div class="card" style="display:flex;justify-content:space-between;align-items:center;gap:16px;">
    <div>
        <div class="tag">Dashboard Admin</div>
        <h2 style="margin:8px 0 4px;">Ringkasan Sistem</h2>
        <div style="color:#64748b;">Kelola master data dan pantau aktivitas surat lintas divisi.</div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a class="btn" href="{{ route('admin.monitoring.index') }}">Monitoring Surat</a>
        <a class="btn secondary" href="{{ route('admin.divisions.index') }}">Master Divisi</a>
    </div>
</div>

<div class="grid grid-3">
    <div class="card">
        <div style="color:#64748b;">Total Divisi</div>
        <div style="font-size:30px;font-weight:700;margin-top:6px;">{{ $divisionCount }}</div>
        <div style="color:#94a3b8;margin-top:6px;">Unit aktif terdaftar</div>
    </div>
    <div class="card">
        <div style="color:#64748b;">Akun Divisi</div>
        <div style="font-size:30px;font-weight:700;margin-top:6px;">{{ $userCount }}</div>
        <div style="color:#94a3b8;margin-top:6px;">Akses pengguna divisi</div>
    </div>
    <div class="card">
        <div style="color:#64748b;">Jenis Surat</div>
        <div style="font-size:30px;font-weight:700;margin-top:6px;">{{ $letterTypeCount }}</div>
        <div style="color:#94a3b8;margin-top:6px;">Template & field tambahan</div>
    </div>
</div>

<div class="grid grid-2">
    <div class="card">
        <div style="color:#64748b;">Aktivitas Surat</div>
        <div style="font-size:28px;font-weight:700;margin-top:6px;">{{ $letterCount }}</div>
        <div style="color:#94a3b8;margin-top:6px;">Surat keluar & masuk tercatat</div>
    </div>
    <div class="card">
        <div style="color:#64748b;">Aksi Cepat</div>
        <div style="margin-top:10px;display:flex;gap:10px;flex-wrap:wrap;">
            <a class="btn secondary" href="{{ route('admin.letter-types.index') }}">Kelola Jenis Surat</a>
            <a class="btn secondary" href="{{ route('admin.accounts.index') }}">Kelola Akun Divisi</a>
        </div>
    </div>
</div>

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
        <div>
            <div style="color:#64748b;">Grafik Surat (Bar)</div>
            <div style="font-size:18px;font-weight:700;margin-top:6px;">Aktivitas per Bulan</div>
        </div>
        <div class="tag">Contoh data</div>
    </div>
    <div style="margin-top:14px;">
        <canvas id="adminLettersBar" height="120"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        const ctx = document.getElementById('adminLettersBar');
        if (!ctx || !window.Chart) return;

        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
        const data = {
            labels,
            datasets: [{
                label: 'Jumlah Surat',
                data: [12, 19, 9, 15, 22, 17],
                backgroundColor: '#4f9ad7',
                borderColor: '#2f6fb2',
                borderWidth: 1,
                borderRadius: 8,
            }]
        };

        const baseUrl = @json(route('admin.monitoring.index'));
        const year = new Date().getFullYear();

        new Chart(ctx, {
            type: 'bar',
            data,
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                onHover: (event, elements) => {
                    event.native.target.style.cursor = elements.length ? 'pointer' : 'default';
                },
                onClick: (event, elements) => {
                    if (!elements.length) return;
                    const index = elements[0].index;
                    const month = index + 1;
                    const pad = (n) => String(n).padStart(2, '0');
                    const dateFrom = `${year}-${pad(month)}-01`;
                    const lastDay = new Date(year, month, 0).getDate();
                    const dateTo = `${year}-${pad(month)}-${pad(lastDay)}`;
                    window.location.href = `${baseUrl}?date_from=${dateFrom}&date_to=${dateTo}`;
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
