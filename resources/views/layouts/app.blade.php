<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Surat Menyurat' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;500;600;700&display=swap');
        :root {
            --bg: #f3f7fb;
            --surface: #ffffff;
            --surface-2: #eaf2fb;
            --text: #1f2a37;
            --muted: #64748b;
            --primary: #4f9ad7;
            --primary-2: #2563eb;
            --accent: #9cc8f0;
            --border: #d7e3f2;
            --shadow: 0 12px 28px rgba(37, 99, 235, 0.12);
            --shadow-soft: 0 6px 14px rgba(37, 99, 235, 0.08);
        }

        * { box-sizing: border-box; }
        body {
            font-family: "Source Sans 3", "Segoe UI", Arial, sans-serif;
            background:
                radial-gradient(900px 300px at 10% -10%, #dbeafe 0%, transparent 62%),
                radial-gradient(900px 300px at 90% -10%, #e0f2fe 0%, transparent 62%),
                var(--bg);
            color: var(--text);
            margin: 0;
        }

        header {
            position: sticky;
            top: 0;
            z-index: 20;
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            color: #fff;
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand img {
            height: 34px;
            width: auto;
            display: block;
        }
        .brand-title {
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        header a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            margin-right: 14px;
            font-weight: 500;
        }
        header a:hover { color: #ffffff; }

        main { padding: 30px; max-width: 1200px; margin: 0 auto; }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
            box-shadow: var(--shadow);
            margin-bottom: 18px;
        }

        .grid { display: grid; gap: 18px; }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }

        .btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            color: #ffffff;
            padding: 10px 16px;
            border-radius: 10px;
            border: none;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            font-weight: 600;
            letter-spacing: 0.2px;
            box-shadow: var(--shadow-soft);
        }
        .btn.secondary { background: #1d4ed8; color: #ffffff; }
        .btn.danger { background: #dc2626; color: #ffffff; }

        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--surface-2);
            color: var(--text);
        }
        label { font-weight: 600; display: block; margin: 10px 0 6px; color: var(--muted); }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid var(--border); text-align: left; }
        th { color: var(--muted); font-weight: 600; }

        .status { font-size: 12px; padding: 4px 10px; border-radius: 999px; background: #f1f5f9; }
        .status.success { background: rgba(22,163,74,0.12); color: #166534; }
        .status.danger { background: rgba(220,38,38,0.12); color: #991b1b; }
        .status.info { background: rgba(14,165,233,0.12); color: #075985; }

        .flash {
            padding: 10px 14px;
            background: rgba(59,130,246,0.12);
            border: 1px solid rgba(59,130,246,0.24);
            margin-bottom: 12px;
            border-radius: 10px;
        }

        .tag {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(59,130,246,0.12);
            color: #1d4ed8;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <header>
        <div class="brand">
            <img src="{{ asset('images/pertamina.jpeg') }}" alt="Pertamina Per­ta Arun Gas">
            <div class="brand-title">E-Office Mail</div>
        </div>
        <div>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                    <a href="{{ route('admin.recap') }}">Rekap Surat</a>
                    <a href="{{ route('admin.divisions.index') }}">Divisi</a>
                    <a href="{{ route('admin.accounts.index') }}">Akun Divisi</a>
                    <a href="{{ route('admin.letter-types.index') }}">Jenis Surat</a>
                    <a href="{{ route('admin.monitoring.index') }}">Monitoring</a>
                @else
                    <a href="{{ route('division.dashboard') }}">Dashboard Divisi</a>
                    <a href="{{ route('division.outgoing.index') }}">Surat Keluar</a>
                    <a href="{{ route('division.incoming.index') }}">Surat Masuk</a>
                @endif
                <form action="{{ route('logout') }}" method="post" style="display:inline;">
                    @csrf
                    <button class="btn secondary" type="submit">Logout</button>
                </form>
            @endauth
        </div>
    </header>
    <main>
        @if(session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
