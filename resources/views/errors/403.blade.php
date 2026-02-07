{{-- resources/views/errors/403.blade.php --}}
@php
    $isRtl = app()->getLocale() === 'ar';
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>403 | Forbidden</title>

    <style>
        :root {
            color-scheme: light;
        }

        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Apple Color Emoji", "Segoe UI Emoji";
            background: #f6f7fb;
            color: #111827;
        }

        .wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 720px;
            background: #fff;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 16px 40px rgba(17, 24, 39, .10);
            border: 1px solid #eef2f7;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #9a3412;
            font-size: 13px;
            font-weight: 600;
        }

        h1 {
            margin: 14px 0 8px;
            font-size: 26px;
            letter-spacing: -.02em;
        }

        p {
            margin: 0 0 10px;
            line-height: 1.6;
            color: #374151;
        }

        .muted {
            color: #6b7280;
            font-size: 14px;
            margin-top: 14px;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .btn {
            display: inline-block;
            padding: 10px 14px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
        }

        .btn-primary {
            background: #111827;
            color: #fff;
        }

        .btn-outline {
            border: 1px solid #d1d5db;
            color: #111827;
            background: #fff;
        }

        .code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
            font-size: 13px;
            background: #f9fafb;
            border: 1px solid #eef2f7;
            padding: 10px 12px;
            border-radius: 12px;
            margin-top: 12px;
            color: #374151;
        }

        .row {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
        }

        html[dir="rtl"] .row {
            flex-direction: row-reverse;
        }

        html[dir="rtl"] .actions {
            justify-content: flex-start;
        }

        .left {
            flex: 1 1 420px;
        }

        .right {
            flex: 0 0 220px;
        }

        .ill {
            width: 100%;
            border-radius: 16px;
            background: radial-gradient(80% 80% at 30% 20%, rgba(17, 24, 39, .08), transparent), #f3f4f6;
            border: 1px solid #eef2f7;
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: #111827;
        }

        .ill span {
            font-size: 34px;
            letter-spacing: -.04em;
            opacity: .85;
        }
    </style>
    @include('partials.rtl')
</head>

<body dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <div class="wrap">
        <div class="card">
            <div class="row">
                <div class="left">
                    <div class="badge">403 • Access denied</div>

                    <h1>Forbidden</h1>
                    <p>You don’t have permission to access this page.</p>



                    @if (!empty($customMessage) && $customMessage !== 'Forbidden')
                        <div class="code">{{ $customMessage }}</div>
                    @endif

                    <p class="muted">
                        If you believe this is a mistake, please contact your administrator or support.
                    </p>

                    <div class="actions">
                        <a class="btn btn-primary" href="{{ url()->previous() }}">Go back</a>
                        <a class="btn btn-outline" href="{{ route('dashboard') }}">Home</a>

                        @auth
                            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                                @csrf
                                <button class="btn btn-outline" type="submit" style="cursor:pointer;">Logout</button>
                            </form>
                        @endauth
                    </div>
                </div>

                <div class="right">
                    <div class="ill"><span>🚫</span></div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
