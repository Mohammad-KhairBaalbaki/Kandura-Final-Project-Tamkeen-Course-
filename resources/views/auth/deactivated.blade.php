<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Account Deactivated') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-indigo-50 flex items-center justify-center p-6">
    <div class="w-full max-w-lg bg-white shadow-xl rounded-2xl p-8 border border-gray-100 text-center">
        <div class="mx-auto w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
            <svg class="w-7 h-7 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('Account Deactivated') }}</h1>
        <p class="text-sm text-gray-600 mb-6">
            {{ __('Your account has been deactivated by an administrator. Please contact support if you think this is a mistake.') }}
        </p>

        <div class="space-y-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full px-4 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition">
                    {{ __('Logout') }}
                </button>
            </form>
            <a href="{{ route('dashboard') }}"
                class="inline-block w-full px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition">
                {{ __('Back to Dashboard') }}
            </a>
        </div>
    </div>
</body>

</html>
