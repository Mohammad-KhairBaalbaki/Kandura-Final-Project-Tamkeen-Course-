<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Kandoura</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
    </style>
</head>

<body class="gradient-bg min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo/Brand Section -->
        <div class="text-center mb-8">
            <div class="inline-block p-4 bg-white rounded-full shadow-lg mb-4">
                <svg class="w-16 h-16 text-purple-600" viewBox="0 0 100 140" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <!-- Head -->
                    <circle cx="50" cy="15" r="8" />

                    <!-- Neck and shoulders -->
                    <path d="M45 23 L45 28 L42 30 L38 32" />
                    <path d="M55 23 L55 28 L58 30 L62 32" />

                    <!-- Main kandoura body - A-line shape -->
                    <path d="M38 32 L35 45 L30 70 L28 100 L25 130 L75 130 L72 100 L70 70 L65 45 L62 32 Z"
                        fill="currentColor" />

                    <!-- Center front line (decorative) -->
                    <line x1="50" y1="32" x2="50" y2="130" stroke="currentColor"
                        stroke-width="0.5" opacity="0.3" />

                    <!-- Sleeves - wide and flowing -->
                    <path d="M38 32 L30 35 L20 45 L15 60 L18 65 L25 60 L30 50 L35 40" opacity="0.8" />
                    <path d="M62 32 L70 35 L80 45 L85 60 L82 65 L75 60 L70 50 L65 40" opacity="0.8" />

                    <!-- Embroidery detail on neckline -->
                    <circle cx="50" cy="32" r="2" opacity="0.4" />
                    <circle cx="46" cy="34" r="1" opacity="0.4" />
                    <circle cx="54" cy="34" r="1" opacity="0.4" />

                    <!-- Side decorative elements -->
                    <path d="M32 80 Q35 85 32 90" stroke="currentColor" stroke-width="0.5" fill="none"
                        opacity="0.3" />
                    <path d="M68 80 Q65 85 68 90" stroke="currentColor" stroke-width="0.5" fill="none"
                        opacity="0.3" />

                    <!-- Bottom hem detail -->
                    <line x1="25" y1="130" x2="75" y2="130" stroke="currentColor"
                        stroke-width="1.5" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Kandoura</h1>
            <p class="text-purple-100">Sign in to manage your store</p>
        </div>

        <!-- Login Card -->
        <div class="glass-effect rounded-2xl p-8 shadow-2xl">

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="font-semibold">Oops! Something went wrong.</span>
                    <ul class="mt-2 ml-6 list-disc text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Field -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-envelope mr-2 text-purple-600"></i>Email Address
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                        placeholder="admin@example.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-2 text-purple-600"></i>Password
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required autocomplete="current-password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transform transition hover:scale-[1.02] active:scale-[0.98]">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </button>
            </form>



        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-white text-sm">
            <p>&copy; {{ date('Y') }} Kandoura Store. All rights reserved.</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto-hide success messages after 5 seconds
        setTimeout(() => {
            const successAlert = document.querySelector('.bg-green-50');
            if (successAlert) {
                successAlert.style.transition = 'opacity 0.5s';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }
        }, 5000);
    </script>

</body>

</html>
