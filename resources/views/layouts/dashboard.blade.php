<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Kandoura Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] {
            display: none !important;
        }

        .sidebar-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body[dir="rtl"] .rotate-180 {
            transform: rotate(0deg);
        }

        body[dir="ltr"] .rotate-180 {
            transform: rotate(180deg);
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="bg-white shadow-xl transition-all duration-300 ease-in-out hidden lg:block overflow-y-auto">
            <div class="h-full flex flex-col">

                <!-- Logo Section -->
                <div class="p-4 border-b flex items-center justify-between">
                    <div class="flex items-center space-x-3" x-show="sidebarOpen" x-cloak>
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 100 140" fill="currentColor">
                                <circle cx="50" cy="15" r="8" />
                                <path d="M45 23 L45 28 L42 30 L38 32" />
                                <path d="M55 23 L55 28 L58 30 L62 32" />
                                <path d="M38 32 L35 45 L30 70 L28 100 L25 130 L75 130 L72 100 L70 70 L65 45 L62 32 Z" />
                                <path d="M38 32 L30 35 L20 45 L15 60 L18 65 L25 60 L30 50 L35 40" opacity="0.8" />
                                <path d="M62 32 L70 35 L80 45 L85 60 L82 65 L75 60 L70 50 L65 40" opacity="0.8" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">{{ __('Kandoura Store') }}</h2>
                            <p class="text-xs text-gray-500">{{ __('Admin Panel') }}</p>
                        </div>
                    </div>
                    <div x-show="!sidebarOpen"
                        class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 100 140" fill="currentColor">
                            <circle cx="50" cy="15" r="8" />
                            <path d="M38 32 L35 45 L30 70 L28 100 L25 130 L75 130 L72 100 L70 70 L65 45 L62 32 Z" />
                        </svg>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 p-4 space-y-2">

                    <!-- Dashboard/Home -->
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-home text-lg w-5"></i>
                        <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('Dashboard') }}</span>
                    </a>

                    <!-- Users Management -->
                    <a href="{{ route('users.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->routeIs('users*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-users text-lg w-5"></i>
                        <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('Users') }}</span>
                    </a>
                    {{-- Admins Management --}}
                    @if (Auth::user()->roles()->first()->name == 'super-admin')
                        <div x-data="{ open: {{ request()->is('admins*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-purple-50 transition text-gray-700">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-users text-lg w-5"></i>
                                    <span x-show="sidebarOpen" x-cloak
                                        class="font-medium">{{ __('admins.users') }}</span>
                                </div>
                                <i x-show="sidebarOpen" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"
                                    class="fas text-sm"></i>
                            </button>
                            <div x-show="open && sidebarOpen" x-cloak class="ml-8 mt-2 space-y-1">

                                <a href="{{ route('admins.index') }}"
                                    class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-600 hover:text-purple-600 rounded-lg hover:bg-purple-50">
                                    <i class="fas fa-list w-4"></i>
                                    <span>{{ __('All Admins') }}</span>
                                </a>

                                <a href="{{ route('admins.create') }}"
                                    class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-600 hover:text-purple-600 rounded-lg hover:bg-purple-50">
                                    <i class="fas fa-plus w-4"></i>
                                    <span>{{ __('Add Admin') }}</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Designs -->
                    <a href="{{ route('designs.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('designs*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-palette text-lg w-5"></i>
                        <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('designs.designs') }}</span>
                    </a>
                    <!-- Design Options -->
                    {{-- {{ route('packages.index') }} --}}
                    <a href="{{ route('design_options.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('design_options*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-sliders-h text-lg w-5"></i> <span x-show="sidebarOpen" x-cloak
                            class="font-medium">{{ __('design_options.design_options') }}</span>
                    </a>

                    <!-- Orders -->
                    {{-- {{ route('orders.index') }} --}}
                    <a href="{{ route('orders.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('orders*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-shopping-cart text-lg w-5"></i>
                        <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('Orders') }}</span>
                    </a>

                    <!-- Coupons -->
                    {{-- {{ route('bottles.index') }} --}}
                    <a href="{{ route('coupons.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('coupons*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-ticket-alt text-lg w-5"></i> <span x-show="sidebarOpen" x-cloak
                            class="font-medium">{{ __('coupons.coupons') }}</span>
                    </a>




                    <!-- Locations -->
                    <div x-data="{ open: {{ request()->is('countries*') || request()->is('cities*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-purple-50 transition text-gray-700">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-map-marked-alt text-lg w-5"></i>
                                <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('Locations') }}</span>
                            </div>
                            <i x-show="sidebarOpen" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"
                                class="fas text-sm"></i>
                        </button>
                        <div x-show="open && sidebarOpen" x-cloak class="ml-8 mt-2 space-y-1">
                            {{-- {{ route('countries.index') }} --}}
                            <a href=""
                                class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-600 hover:text-purple-600 rounded-lg hover:bg-purple-50">
                                <i class="fas fa-flag w-4"></i>
                                <span>{{ __('Countries') }}</span>
                            </a>
                            {{-- {{ route('cities.index') }} --}}
                            <a href=""
                                class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-600 hover:text-purple-600 rounded-lg hover:bg-purple-50">
                                <i class="fas fa-city w-4"></i>
                                <span>{{ __('Cities') }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Wallets -->
                    {{-- {{ route('reviews.index') }} --}}
                    <a href="{{ route('wallets.charge') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('wallets*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-wallet text-lg w-5"></i>
                        <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('wallets.wallets') }}</span>
                    </a>

                    <!-- Roles -->
                    {{-- {{ route('reviews.index') }} --}}
                    @if (Auth::user()->roles()->first()->name == 'super-admin')
                        <a href="{{ route('roles.index') }}"
                            class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('roles*') ? 'sidebar-active' : 'text-gray-700' }}">
                            <i class="fas fa-person text-lg w-5"></i>
                            <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('Roles') }}</span>
                        </a>
                    @endif
                    <!-- Payments -->
                    <a href="{{ route('payments.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('payments*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-credit-card text-lg w-5"></i>
                        <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('payments.payments') }}</span>
                    </a>



                </nav>

                <!-- Toggle Sidebar Button -->
                <div class="p-4 border-t">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="w-full flex items-center justify-center px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        <i :class="sidebarOpen ? 'fa-angle-left' : 'fa-angle-right'" class="fas text-lg"></i>
                    </button>
                </div>

            </div>
        </aside>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

        <!-- Mobile Sidebar -->
        <aside x-show="mobileMenuOpen" x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" x-cloak
            class="fixed inset-y-0 left-0 w-64 bg-white shadow-xl z-50 lg:hidden overflow-y-auto">
            <!-- Same navigation as desktop sidebar -->
            <div class="h-full flex flex-col">
                <div class="p-4 border-b flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 100 140" fill="currentColor">
                                <circle cx="50" cy="15" r="8" />
                                <path d="M38 32 L35 45 L30 70 L28 100 L25 130 L75 130 L72 100 L70 70 L65 45 L62 32 Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">{{ __('Kandoura Store') }}</h2>
                            <p class="text-xs text-gray-500">{{ __('Admin Panel') }}</p>
                        </div>
                    </div>
                    <button @click="mobileMenuOpen = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <nav class="flex-1 p-4 space-y-2">
                    <!-- Copy same navigation items from desktop sidebar -->
                </nav>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Navbar -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-4">

                    <!-- Left: Mobile Menu Button -->
                    <button @click="mobileMenuOpen = true" class="lg:hidden text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Left: Page Title (Desktop) -->
                    <div class="hidden lg:block">
                        <h1 class="text-2xl font-bold text-gray-800">@yield('page-title', __('Dashboard'))</h1>
                        <p class="text-sm text-gray-500">{{ now()->format('l, F d, Y') }}</p>
                    </div>

                    <!-- Right: Actions -->
                    <div class="flex items-center space-x-4">

                        <!-- Language Switcher -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                                <i class="fas fa-globe text-gray-600"></i>
                                <span
                                    class="text-sm font-medium text-gray-700">{{ strtoupper(app()->getLocale()) }}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border py-2">
                                <a href="{{ route('language.switch', 'en') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                                    <img src="https://flagcdn.com/w20/gb.png" class="w-5 h-4 mr-2" alt="English">
                                    English
                                </a>
                                <a href="{{ route('language.switch', 'ar') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                                    <img src="https://flagcdn.com/w20/sa.png" class="w-5 h-4 mr-2" alt="العربية">
                                    العربية
                                </a>
                            </div>
                        </div>

                        <!-- Notifications -->
                        @php
                            $latestNotifications = auth()->user()
                                ?->notifications()
                                ->latest()
                                ->take(5)
                                ->get() ?? collect();
                            $unreadCount = auth()->user()
                                ?->unreadNotifications()
                                ->count() ?? 0;
                        @endphp
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-bell text-xl"></i>
                                @if ($unreadCount > 0)
                                    <span
                                        class="absolute -top-1 -right-1 min-w-5 h-5 px-1 bg-red-500 text-white text-[10px] leading-5 text-center rounded-full">
                                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                    </span>
                                @endif
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg border">
                                <div class="p-4 border-b flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-800">{{ __('notifications.notifications') }}</h3>
                                    <a href="{{ route('notifications.index') }}"
                                        class="text-xs text-purple-600 hover:text-purple-700 font-medium">
                                        {{ __('notifications.view_all') }}
                                    </a>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    @forelse ($latestNotifications as $notification)
                                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}"
                                            class="border-b last:border-b-0">
                                            @csrf
                                            <button type="submit"
                                                class="w-full text-left px-4 py-3 hover:bg-gray-50">
                                                <p
                                                    class="text-sm {{ $notification->read_at ? 'text-gray-800' : 'font-semibold text-gray-900' }}">
                                                    {{ $notification->data['title'] ?? __('notifications.notification') }}
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $notification->data['body'] ?? '' }}
                                                </p>
                                                <p class="text-[11px] text-gray-400 mt-1">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </button>
                                        </form>
                                    @empty
                                        <div class="px-4 py-6 text-center text-sm text-gray-500">
                                            {{ __('notifications.no_notifications') }}
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                                <div
                                    class="w-8 h-8 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ __('Administrator') }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border py-2">
                                {{-- {{ route('profile.edit') }} --}}
                                <a href="{{ route('profile.show') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                                    <i class="fas fa-user w-5"></i>
                                    <span>{{ __('Profile') }}</span>
                                </a>
                                {{-- {{ route('settings.index') }} --}}
                                <a href=""
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                                    <i class="fas fa-cog w-5"></i>
                                    <span>{{ __('Settings') }}</span>
                                </a>
                                <hr class="my-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt w-5"></i>
                                        <span>{{ __('Logout') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-6">
                @yield('content')
            </main>

        </div>

    </div>

    @stack('scripts')

    <script type="module">
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js";
        import {
            getMessaging,
            getToken,
            onMessage
        } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging.js";

        // TODO: paste firebaseConfig here
        const firebaseConfig = {
            apiKey: "AIzaSyCg9F6j7wmEZPVuC3Q85Od_velGH9V6ExA",
            authDomain: "kandoura-f72d5.firebaseapp.com",
            projectId: "kandoura-f72d5",
            storageBucket: "kandoura-f72d5.firebasestorage.app",
            messagingSenderId: "353127064443",
            appId: "1:353127064443:web:2b4d49dc5f1e9e3e1813de",
            measurementId: "G-S2TLZ46HL1"
        };

        const VAPID_KEY = "BIZVjSD9X55PkQHg-3YkyLmhT0MdcPxmRnmg9u5Z_P4gYW-14HnNTqO-00SJn8rhv_Q4Y2cF-jthCDvmYz29QJo";

        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        async function registerFcm() {
            try {
                if (!("serviceWorker" in navigator)) {
                    console.warn("No serviceWorker support");
                    return;
                }

                // 1) Register SW
                const swReg = await navigator.serviceWorker.register("/firebase-messaging-sw.js");
                console.log("SW registered:", swReg);

                // 2) Ask permission
                const permission = await Notification.requestPermission();
                console.log("Notification permission:", permission);
                if (permission !== "granted") return;

                // 3) Get token
                const token = await getToken(messaging, {
                    vapidKey: VAPID_KEY,
                    serviceWorkerRegistration: swReg,
                });

                console.log("FCM token:", token);
                if (!token) return;

                // 4) Send token to Laravel
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                if (!csrf) {
                    console.error("Missing <meta name='csrf-token' ...>");
                    return;
                }

                const res = await fetch("/fcm/token", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrf,
                    },
                    credentials: "same-origin",
                    body: JSON.stringify({
                        token,
                        platform: "web"
                    }),
                });

                const text = await res.text();
                console.log("Save token response:", res.status, text);

            } catch (e) {
                console.error("FCM register error:", e);
            }
        }

        // Foreground messages (when dashboard tab is open)
        onMessage(messaging, (payload) => {
            console.log("FCM foreground:", payload);

            // Optional: show a browser notification even in foreground
            const title = payload?.notification?.title;
            const body = payload?.notification?.body;
            if (title) new Notification(title, {
                body: body ?? ""
            });

        });

        registerFcm();
    </script>

</body>

</html>
