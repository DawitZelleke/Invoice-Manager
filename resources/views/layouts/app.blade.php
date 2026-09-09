<!DOCTYPE html>
<html lang="en" data-theme="day">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f4f1fa">
    <title>@yield('title', 'Invoice Manager')</title>

    {{-- Set the theme before first paint so there's no white flash. --}}
    <script>
        try {
            const saved = localStorage.getItem('invoice-theme');
            const dark = saved ? saved === 'night'
                : matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.dataset.theme = dark ? 'night' : 'day';
        } catch (e) {}
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700;800&family=Public+Sans:wght@300..800&display=swap">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    <script defer src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
</head>
<body>
    <div class="rail" aria-hidden="true"></div>

    <div class="shell">
        <header class="masthead">
            <a class="mark" href="{{ route('invoices.index') }}">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect class="coin" x="3" y="2" width="18" height="20" rx="4" fill="var(--grape)"></rect>
                    <path d="M8 8.5h8M8 12h8M8 15.5h4.5" stroke="#fff" stroke-width="1.8" stroke-linecap="round"></path>
                </svg>
                Invoice Manager
            </a>

            <div class="masthead-tools">
                <button class="toggle" type="button" data-theme-toggle
                        aria-label="Switch between light and dark">
                    <svg class="moon-icon" width="17" height="17" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"></path>
                    </svg>
                    <svg class="sun-icon" width="17" height="17" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"></circle>
                        <path d="M12 2v2m0 16v2M2 12h2m16 0h2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M19.1 4.9l-1.4 1.4M6.3 17.7l-1.4 1.4"></path>
                    </svg>
                </button>

                @yield('action')
            </div>
        </header>

        @yield('content')
    </div>

    @if (session('flash'))
        <p class="toast" role="status"
           @if (str_contains(session('flash'), 'added')) data-celebrate @endif>
            <span class="check" aria-hidden="true">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m4 12.5 5.5 5.5L20 6"></path>
                </svg>
            </span>
            {{ session('flash') }}
        </p>
    @endif
</body>
</html>
