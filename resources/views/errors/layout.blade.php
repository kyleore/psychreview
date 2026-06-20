<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Something went wrong') · PsychReview</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{
            min-height:100vh;display:flex;align-items:center;justify-content:center;
            font-family:'Inter',ui-sans-serif,system-ui,sans-serif;color:#0f172a;padding:24px;
            background:
                radial-gradient(1200px 600px at 100% -10%, #eef2ff 0%, transparent 50%),
                radial-gradient(1000px 500px at -10% 10%, #f5f3ff 0%, transparent 45%),
                #f8fafc;
        }
        .card{
            width:100%;max-width:30rem;background:#fff;border:1px solid #eef2f7;border-radius:1.5rem;
            box-shadow:0 30px 60px -25px rgba(79,70,229,.35);padding:40px 32px;text-align:center;
            animation:pop .4s cubic-bezier(.2,.8,.2,1) both;
        }
        @keyframes pop{from{opacity:0;transform:translateY(14px) scale(.97)}to{opacity:1;transform:none}}
        .badge{
            width:72px;height:72px;margin:0 auto 22px;display:grid;place-items:center;border-radius:1.25rem;
            color:#fff;background:linear-gradient(135deg,#4f46e5,#7c3aed);box-shadow:0 14px 30px -10px rgba(99,102,241,.6);
        }
        .badge svg{width:36px;height:36px}
        .code{font-size:13px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:#6366f1}
        h1{font-size:1.6rem;font-weight:800;margin:6px 0 10px;letter-spacing:-.02em}
        p{color:#64748b;font-size:.95rem;line-height:1.6}
        .spinner{
            width:42px;height:42px;margin:22px auto 6px;border-radius:50%;
            border:4px solid #e0e7ff;border-top-color:#6366f1;animation:spin .8s linear infinite;
        }
        @keyframes spin{to{transform:rotate(360deg)}}
        .count{font-size:.85rem;color:#94a3b8;margin-top:8px}
        .count b{color:#4f46e5}
        .actions{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-top:26px}
        .btn{
            display:inline-flex;align-items:center;gap:8px;border-radius:.75rem;padding:11px 20px;
            font-size:.9rem;font-weight:700;text-decoration:none;cursor:pointer;border:0;transition:transform .12s ease,box-shadow .2s ease,background .2s ease;
        }
        .btn:active{transform:scale(.96)}
        .btn-primary{color:#fff;background:linear-gradient(90deg,#4f46e5,#7c3aed);box-shadow:0 10px 24px -10px rgba(99,102,241,.7)}
        .btn-primary:hover{box-shadow:0 14px 28px -10px rgba(99,102,241,.8)}
        .btn-ghost{color:#475569;background:#fff;border:1px solid #e2e8f0}
        .btn-ghost:hover{background:#f8fafc}
        .brand{margin-top:26px;font-size:12px;color:#94a3b8;display:flex;align-items:center;justify-content:center;gap:8px}
        .brand-dot{width:22px;height:22px;border-radius:7px;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;display:grid;place-items:center;font-weight:800;font-size:11px}
    </style>
</head>
<body>
    <div class="card">
        <div class="badge">@yield('icon')</div>
        <div class="code">@yield('code', 'Error')</div>
        <h1>@yield('heading', 'Something went wrong')</h1>
        <p>@yield('message', 'An unexpected error occurred. Please try again.')</p>

        @yield('extra')

        <div class="actions">
            @yield('actions')
            <a href="{{ url('/') }}" class="btn btn-ghost">Go to homepage</a>
        </div>

        <div class="brand"><span class="brand-dot">P</span> PsychReview</div>
    </div>
    @yield('scripts')
</body>
</html>
