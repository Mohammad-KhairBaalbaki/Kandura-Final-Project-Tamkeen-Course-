<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" >

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
                            <h2 class="text-lg font-bold text-gray-800">Kandoura Store</h2>
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
                    <div x-data="{ open: {{ request()->is('users*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-purple-50 transition text-gray-700">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-users text-lg w-5"></i>
                                <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('Users') }}</span>
                            </div>
                            <i x-show="sidebarOpen" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"
                                class="fas text-sm"></i>
                        </button>
                        <div x-show="open && sidebarOpen" x-cloak class="ml-8 mt-2 space-y-1">
                            <a href="{{ route('users.index') }}"
                                class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-600 hover:text-purple-600 rounded-lg hover:bg-purple-50">
                                <i class="fas fa-list w-4"></i>
                                <span>{{ __('All Users') }}</span>
                            </a>
                            <a href="{{ route('users.create') }}"
                                class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-600 hover:text-purple-600 rounded-lg hover:bg-purple-50">
                                <i class="fas fa-plus w-4"></i>
                                <span>{{ __('Add User') }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Designs -->
                    <div x-data="{ open: {{ request()->is('designs*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-purple-50 transition text-gray-700">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-palette text-lg w-5"></i>
                                <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('Designs') }}</span>
                            </div>
                            <i x-show="sidebarOpen" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"
                                class="fas text-sm"></i>
                        </button>
                        <div x-show="open && sidebarOpen" x-cloak class="ml-8 mt-2 space-y-1">
                            {{-- {{ route('designs.index') }} --}}
                            <a href=""
                                class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-600 hover:text-purple-600 rounded-lg hover:bg-purple-50">
                                <i class="fas fa-list w-4"></i>
                                <span>{{ __('All Designs') }}</span>
                            </a>

                        </div>
                    </div>

                    <!-- Design Options -->
                    {{-- {{ route('packages.index') }} --}}
                    <a href=""
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('design_options*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-sliders-h text-lg w-5"></i> <span x-show="sidebarOpen" x-cloak
                            class="font-medium">{{ __('Design Options') }}</span>
                    </a>

                    <!-- Orders -->
                    {{-- {{ route('orders.index') }} --}}
                    <a href=""
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('orders*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-shopping-cart text-lg w-5"></i>
                        <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('Orders') }}</span>
                    </a>

                    <!-- Coupons -->
                    {{-- {{ route('bottles.index') }} --}}
                    <a href=""
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('coupons*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-ticket-alt text-lg w-5"></i> <span x-show="sidebarOpen" x-cloak
                            class="font-medium">{{ __('Coupons') }}</span>
                    </a>



                    <!-- Reviews -->
                    <a href="{{ route('reviews.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('reviews*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-star text-lg w-5"></i>
                        <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('Reviews') }}</span>
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
                            <a href="{{ route('countries.index') }}"
                                class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-600 hover:text-purple-600 rounded-lg hover:bg-purple-50">
                                <i class="fas fa-flag w-4"></i>
                                <span>{{ __('Countries') }}</span>
                            </a>
                            <a href="{{ route('cities.index') }}"
                                class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-600 hover:text-purple-600 rounded-lg hover:bg-purple-50">
                                <i class="fas fa-city w-4"></i>
                                <span>{{ __('Cities') }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Wallets -->
                    {{-- {{ route('reviews.index') }} --}}
                    <a href=""
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('wallets*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-wallet text-lg w-5"></i>
                        <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('Wallets') }}</span>
                    </a>

                    <!-- Roles -->
                    {{-- {{ route('reviews.index') }} --}}
                    <a href=""
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('roles*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-person text-lg w-5"></i>
                        <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('Roles') }}</span>
                    </a>

                    <!-- Reports -->
                    {{-- {{ route('reviews.index') }} --}}
                    <a href=""
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('reports*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-chart-bar text-lg w-5"></i>
                        <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('Reports') }}</span>
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('settings.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-50 transition {{ request()->is('settings*') ? 'sidebar-active' : 'text-gray-700' }}">
                        <i class="fas fa-cog text-lg w-5"></i>
                        <span x-show="sidebarOpen" x-cloak class="font-medium">{{ __('Settings') }}</span>
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
                            <h2 class="text-lg font-bold text-gray-800">Perfume Store</h2>
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
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border">
                                <div class="p-4 border-b">
                                    <h3 class="font-semibold text-gray-800">{{ __('Notifications') }}</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b">
                                        <p class="text-sm text-gray-800">{{ __('New order received') }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ __('2 minutes ago') }}</p>
                                    </a>
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                        <p class="text-sm text-gray-800">{{ __('New user registered') }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ __('1 hour ago') }}</p>
                                    </a>
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
                                    <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->first_name }}
                                        {{ auth()->user()->last_name }}</p>
                                    <p class="text-xs text-gray-500">{{ __('Administrator') }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border py-2">
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                                    <i class="fas fa-user w-5"></i>
                                    <span>{{ __('Profile') }}</span>
                                </a>
                                <a href="{{ route('settings.index') }}"
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
</body>

</html>
