<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PsychReview') · Psychology Exam Reviewer</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] },
                    colors: {
                        brand: {
                            50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',
                            400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca',
                            800:'#3730a3',900:'#312e81'
                        }
                    },
                    keyframes: {
                        'fade-up': { '0%': { opacity: 0, transform: 'translateY(16px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
                        'fade-in': { '0%': { opacity: 0 }, '100%': { opacity: 1 } },
                        'pop': { '0%': { transform: 'scale(.9)', opacity: 0 }, '100%': { transform: 'scale(1)', opacity: 1 } },
                        'float': { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
                        'shimmer': { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
                    },
                    animation: {
                        'fade-up': 'fade-up .6s ease-out both',
                        'fade-in': 'fade-in .8s ease-out both',
                        'pop': 'pop .4s ease-out both',
                        'float': 'float 6s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { background:
            radial-gradient(1200px 600px at 100% -10%, #eef2ff 0%, transparent 50%),
            radial-gradient(1000px 500px at -10% 10%, #f5f3ff 0%, transparent 45%),
            #f8fafc; }
        .hero-gradient { background: linear-gradient(120deg,#4f46e5,#7c3aed,#6366f1,#8b5cf6); background-size: 300% 300%; animation: gradientMove 12s ease infinite; }
        @keyframes gradientMove { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
        .lift { transition: transform .35s cubic-bezier(.2,.8,.2,1), box-shadow .35s ease; }
        .lift:hover { transform: translateY(-6px); box-shadow: 0 22px 40px -18px rgba(79,70,229,.45); }
        .stagger > * { opacity: 0; animation: fadeUp .6s ease-out forwards; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }
        .stagger > *:nth-child(1){animation-delay:.05s}.stagger > *:nth-child(2){animation-delay:.1s}
        .stagger > *:nth-child(3){animation-delay:.15s}.stagger > *:nth-child(4){animation-delay:.2s}
        .stagger > *:nth-child(5){animation-delay:.25s}.stagger > *:nth-child(6){animation-delay:.3s}
        .stagger > *:nth-child(7){animation-delay:.35s}.stagger > *:nth-child(8){animation-delay:.4s}
        .nav-link{position:relative}
        .nav-link::after{content:'';position:absolute;left:0;bottom:-6px;height:2px;width:0;background:#4f46e5;transition:width .3s ease;border-radius:2px}
        .nav-link:hover::after,.nav-link.active::after{width:100%}
        /* Flashcard 3D flip */
        .flip{perspective:1200px}
        .flip-inner{position:relative;width:100%;height:100%;transition:transform .7s cubic-bezier(.2,.8,.2,1);transform-style:preserve-3d}
        .flip.is-flipped .flip-inner{transform:rotateY(180deg)}
        .flip-face{position:absolute;inset:0;backface-visibility:hidden;-webkit-backface-visibility:hidden;display:flex;flex-direction:column;border-radius:1.25rem}
        .flip-back{transform:rotateY(180deg)}
        .btn-press{transition:transform .12s ease, box-shadow .2s ease}
        .btn-press:active{transform:scale(.96)}
        [x-cloak]{display:none!important}
        .prose-ai h2{font-size:1.15rem;font-weight:700;margin:.4rem 0}
        .prose-ai ul{list-style:disc;margin-left:1.1rem;margin-bottom:.6rem}
        .prose-ai p{margin-bottom:.6rem}
        .prose-ai strong{color:#4338ca}
        /* Skeleton loading */
        .skeleton{position:relative;overflow:hidden;background:#e2e8f0;border-radius:.75rem}
        .skeleton::after{content:'';position:absolute;inset:0;transform:translateX(-100%);
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.65),transparent);
            animation:skeleton-shimmer 1.4s infinite}
        @keyframes skeleton-shimmer{100%{transform:translateX(100%)}}
        /* Top navigation progress bar */
        #nav-progress{position:fixed;top:0;left:0;height:3px;width:0;z-index:70;opacity:0;
            background:linear-gradient(90deg,#4f46e5,#8b5cf6,#6366f1);
            box-shadow:0 0 10px rgba(99,102,241,.7);transition:width .25s ease,opacity .25s ease}
        #nav-progress.active{opacity:1}
        /* Full-page navigation skeleton */
        #page-skeleton{position:fixed;inset:0;z-index:65;background:#f8fafc;display:none;overflow:hidden}
        #page-skeleton.show{display:block;animation:fade-in .2s ease-out both}
    </style>
    @stack('head')
</head>
<body class="min-h-screen font-sans text-slate-800 antialiased">

    <!-- Navigation loading indicators -->
    <div id="nav-progress"></div>
    <div id="page-skeleton" aria-hidden="true">
        <div class="h-14 border-b border-slate-200 bg-white"></div>
        <div class="mx-auto max-w-6xl px-4 py-8">
            <div class="skeleton h-9 w-2/3 max-w-sm"></div>
            <div class="skeleton mt-3 h-4 w-1/2 max-w-xs"></div>
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-3xl border border-slate-100 bg-white p-6">
                    <div class="skeleton h-12 w-12 rounded-2xl"></div>
                    <div class="skeleton mt-5 h-5 w-3/4"></div>
                    <div class="skeleton mt-3 h-3 w-1/2"></div>
                </div>
                <div class="rounded-3xl border border-slate-100 bg-white p-6">
                    <div class="skeleton h-12 w-12 rounded-2xl"></div>
                    <div class="skeleton mt-5 h-5 w-3/4"></div>
                    <div class="skeleton mt-3 h-3 w-1/2"></div>
                </div>
                <div class="rounded-3xl border border-slate-100 bg-white p-6">
                    <div class="skeleton h-12 w-12 rounded-2xl"></div>
                    <div class="skeleton mt-5 h-5 w-3/4"></div>
                    <div class="skeleton mt-3 h-3 w-1/2"></div>
                </div>
                <div class="rounded-3xl border border-slate-100 bg-white p-6">
                    <div class="skeleton h-12 w-12 rounded-2xl"></div>
                    <div class="skeleton mt-5 h-5 w-3/4"></div>
                    <div class="skeleton mt-3 h-3 w-1/2"></div>
                </div>
            </div>
            <div class="mt-10 space-y-4">
                <div class="skeleton h-20 w-full rounded-2xl"></div>
                <div class="skeleton h-20 w-full rounded-2xl"></div>
                <div class="skeleton h-20 w-full rounded-2xl"></div>
            </div>
        </div>
    </div>

    <header class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/80 backdrop-blur-lg">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-brand-600 to-violet-600 text-white shadow-lg shadow-brand-500/30 transition group-hover:rotate-6">
                    <i data-lucide="brain-circuit" class="h-6 w-6"></i>
                </span>
                <span class="text-lg font-extrabold tracking-tight text-slate-900">Psych<span class="text-brand-600">Review</span></span>
            </a>

            <div class="flex items-center gap-4">
                @auth
                <nav class="hidden items-center gap-7 text-sm font-semibold text-slate-600 md:flex">
                    @php $r = Route::currentRouteName(); @endphp
                    <a href="{{ route('home') }}" class="nav-link inline-flex items-center gap-1.5 {{ $r==='home'?'active text-brand-700':'' }}"><i data-lucide="home" class="h-4 w-4"></i> Home</a>
                    <a href="{{ route('topics.index') }}" class="nav-link inline-flex items-center gap-1.5 {{ str_starts_with($r,'topics')?'active text-brand-700':'' }}"><i data-lucide="book-open" class="h-4 w-4"></i> Topics</a>
                    <a href="{{ route('flashcards.index') }}" class="nav-link inline-flex items-center gap-1.5 {{ $r==='flashcards.index'?'active text-brand-700':'' }}"><i data-lucide="layers" class="h-4 w-4"></i> Flashcards</a>
                    <a href="{{ route('quiz.index') }}" class="nav-link inline-flex items-center gap-1.5 {{ str_starts_with($r,'quiz')?'active text-brand-700':'' }}"><i data-lucide="clipboard-check" class="h-4 w-4"></i> Quiz</a>
                    <a href="{{ route('library.index') }}" class="nav-link inline-flex items-center gap-1.5 {{ $r==='library.index'?'active text-brand-700':'' }}"><i data-lucide="library-big" class="h-4 w-4"></i> Library</a>
                    <a href="{{ route('progress.index') }}" class="nav-link inline-flex items-center gap-1.5 {{ $r==='progress.index'?'active text-brand-700':'' }}"><i data-lucide="bar-chart-3" class="h-4 w-4"></i> Progress</a>
                    <a href="{{ route('ai.index') }}" class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-brand-600 to-violet-600 px-4 py-2 text-white shadow-md shadow-brand-500/30 transition hover:shadow-lg hover:shadow-brand-500/40 btn-press">
                        <i data-lucide="sparkles" class="h-4 w-4"></i> AI Tutor
                    </a>
                </nav>
                @endauth

                <div class="flex items-center gap-2 text-sm font-semibold">
                    @auth
                        @if (Auth::user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" title="Admin panel" class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1.5 text-amber-700 transition hover:bg-amber-200">
                                <i data-lucide="shield-check" class="h-4 w-4"></i><span class="hidden sm:inline">Admin</span>
                            </a>
                        @endif
                        <a href="{{ route('intro') }}" title="App guide" class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-slate-500 transition hover:bg-slate-100 hover:text-brand-600">
                            <i data-lucide="help-circle" class="h-4 w-4"></i><span class="hidden sm:inline">Guide</span>
                        </a>
                        <span class="hidden max-w-[8rem] truncate whitespace-nowrap text-slate-600 sm:inline">Hi, {{ \Illuminate\Support\Str::before(Auth::user()->name, ' ') }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-full border border-slate-300 px-3.5 py-1.5 text-slate-600 transition hover:bg-slate-100 btn-press">
                                <i data-lucide="log-out" class="h-4 w-4"></i> Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full px-3.5 py-1.5 text-slate-600 transition hover:bg-slate-100">
                            <i data-lucide="log-in" class="h-4 w-4"></i> Log in
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full bg-slate-900 px-3.5 py-1.5 text-white transition hover:bg-slate-700 btn-press">
                            Sign up
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="{{ auth()->check() ? 'pb-20 md:pb-0' : '' }}">
        @if (session('status'))
            <div class="mx-auto max-w-6xl px-4 pt-4">
                <div class="flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    <i data-lucide="check-circle-2" class="h-4 w-4"></i>
                    {{ session('status') }}
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    <!-- Facebook-style sticky bottom nav (mobile only) -->
    @auth
    <nav class="fixed inset-x-0 bottom-0 z-50 border-t border-slate-200 bg-white/95 backdrop-blur-lg shadow-[0_-4px_20px_-8px_rgba(0,0,0,0.15)] md:hidden">
        @php $r = Route::currentRouteName(); @endphp
        <div class="mx-auto grid max-w-lg grid-cols-7">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 py-2.5 text-[10px] font-semibold transition {{ $r==='home'?'text-brand-600':'text-slate-500' }}">
                <i data-lucide="home" class="h-5 w-5"></i> Home
            </a>
            <a href="{{ route('topics.index') }}" class="flex flex-col items-center gap-0.5 py-2.5 text-[10px] font-semibold transition {{ str_starts_with($r,'topics')?'text-brand-600':'text-slate-500' }}">
                <i data-lucide="book-open" class="h-5 w-5"></i> Topics
            </a>
            <a href="{{ route('flashcards.index') }}" class="flex flex-col items-center gap-0.5 py-2.5 text-[10px] font-semibold transition {{ $r==='flashcards.index'?'text-brand-600':'text-slate-500' }}">
                <i data-lucide="layers" class="h-5 w-5"></i> Cards
            </a>
            <a href="{{ route('quiz.index') }}" class="flex flex-col items-center gap-0.5 py-2.5 text-[10px] font-semibold transition {{ str_starts_with($r,'quiz')?'text-brand-600':'text-slate-500' }}">
                <i data-lucide="clipboard-check" class="h-5 w-5"></i> Quiz
            </a>
            <a href="{{ route('library.index') }}" class="flex flex-col items-center gap-0.5 py-2.5 text-[10px] font-semibold transition {{ $r==='library.index'?'text-brand-600':'text-slate-500' }}">
                <i data-lucide="library-big" class="h-5 w-5"></i> Library
            </a>
            <a href="{{ route('progress.index') }}" class="flex flex-col items-center gap-0.5 py-2.5 text-[10px] font-semibold transition {{ $r==='progress.index'?'text-brand-600':'text-slate-500' }}">
                <i data-lucide="bar-chart-3" class="h-5 w-5"></i> Progress
            </a>
            <a href="{{ route('ai.index') }}" class="flex flex-col items-center gap-0.5 py-2.5 text-[10px] font-semibold transition {{ $r==='ai.index'?'text-brand-600':'text-slate-500' }}">
                <i data-lucide="sparkles" class="h-5 w-5"></i> AI
            </a>
        </div>
    </nav>
    @endauth

    <footer class="mt-16 border-t border-slate-200 bg-white/70 pb-20 md:pb-0">
        <div class="mx-auto max-w-6xl px-4 py-10">
            <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
                <div class="flex items-center gap-2.5">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-br from-brand-600 to-violet-600 text-white">
                        <i data-lucide="brain-circuit" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <p class="font-bold text-slate-900">PsychReview</p>
                        <p class="text-xs text-slate-500">Master Psychology, one concept at a time.</p>
                    </div>
                </div>
                <nav class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm font-medium text-slate-500">
                    <a href="{{ route('home') }}" class="hover:text-brand-600">Home</a>
                    <a href="{{ route('topics.index') }}" class="hover:text-brand-600">Topics</a>
                    <a href="{{ route('flashcards.index') }}" class="hover:text-brand-600">Flashcards</a>
                    <a href="{{ route('quiz.index') }}" class="hover:text-brand-600">Quiz</a>
                    <a href="{{ route('progress.index') }}" class="hover:text-brand-600">Progress</a>
                    <a href="{{ route('ai.index') }}" class="hover:text-brand-600">AI Tutor</a>
                </nav>
            </div>
            <p class="mt-6 text-center text-xs text-slate-400">© {{ date('Y') }} PsychReview · Built with Laravel {{ Illuminate\Foundation\Application::VERSION }}</p>
        </div>
    </footer>

    <script>
        function renderIcons(){ if (window.lucide) lucide.createIcons(); }
        document.addEventListener('DOMContentLoaded', renderIcons);
        document.addEventListener('alpine:initialized', renderIcons);

        // Navigation loading: top progress bar + skeleton screen for slow page loads.
        (function () {
            const bar = document.getElementById('nav-progress');
            const skel = document.getElementById('page-skeleton');
            let trickle, skelTimer, running = false;

            function start() {
                if (running) return;
                running = true;
                bar.classList.add('active');
                bar.style.width = '8%';
                requestAnimationFrame(() => { bar.style.width = '40%'; });
                let w = 40;
                trickle = setInterval(() => {
                    w = Math.min(w + Math.random() * 8, 90);
                    bar.style.width = w + '%';
                }, 400);
                // Only reveal the skeleton if the page is genuinely slow (e.g. cold start).
                skelTimer = setTimeout(() => skel.classList.add('show'), 350);
            }

            function done() {
                clearInterval(trickle);
                clearTimeout(skelTimer);
                running = false;
                bar.style.width = '100%';
                setTimeout(() => { bar.classList.remove('active'); bar.style.width = '0'; }, 250);
                skel.classList.remove('show');
            }

            document.addEventListener('click', function (e) {
                const a = e.target.closest('a');
                if (!a) return;
                if (a.target === '_blank' || a.hasAttribute('download') || a.hasAttribute('data-no-skeleton')) return;
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;
                const href = a.getAttribute('href');
                if (!href || href.startsWith('#') || /^(mailto:|tel:|javascript:)/i.test(href)) return;
                if (a.origin !== location.origin) return;
                if (a.pathname === location.pathname && a.hash) return;
                start();
            });

            document.addEventListener('submit', function (e) {
                const f = e.target;
                if (e.defaultPrevented || f.hasAttribute('data-no-skeleton')) return;
                start();
            });

            // Reset when the page is shown (including back/forward cache restores).
            window.addEventListener('pageshow', done);
        })();

        // Lightweight, safe Markdown-lite -> HTML (escapes input first)
        function mdToHtml(md){
            const esc = s => String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
            const inline = s => s
                .replace(/\*\*(.+?)\*\*/g,'<strong>$1</strong>')
                .replace(/`(.+?)`/g,'<code>$1</code>')
                .replace(/_(.+?)_/g,'<em>$1</em>');
            const lines = esc(md).split('\n');
            let html = '', inList = false;
            const closeList = () => { if(inList){ html += '</ul>'; inList = false; } };
            for (const raw of lines){
                const line = raw.trim();
                if (line === '') { closeList(); continue; }
                if (line.startsWith('## ')) { closeList(); html += '<h2>'+inline(line.slice(3))+'</h2>'; continue; }
                if (line.startsWith('- '))  { if(!inList){ html += '<ul>'; inList = true; } html += '<li>'+inline(line.slice(2))+'</li>'; continue; }
                closeList();
                html += '<p>'+inline(line)+'</p>';
            }
            closeList();
            return html;
        }
    </script>
    @stack('scripts')
</body>
</html>
