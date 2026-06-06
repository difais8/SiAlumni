<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Forgot Password</title>
    <link rel="shortcut icon" href="{{ asset('images/iconLogoSMP_m.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="font-sans text-gray-900 antialiased">

    <section class="bg-gray-100 min-h-screen flex box-border justify-center items-center">
        <div class="bg-white rounded-2xl flex max-w-3xl p-5 items-center shadow-lg">
            
            <div class="px-8">
                <h2 class="font-bold text-3xl text-[#002D74]">Forgot Password</h2>
                <p class="text-sm mt-4 text-[#002D74]">
                    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                </p>

                <div class="mt-4">
                    <x-auth-session-status class="mb-4 font-medium text-sm text-green-600" :status="session('status')" />
                </div>

                <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-4">
                    @csrf

                    <div>
                        <input class="p-2 mt-8 rounded-xl border w-full" 
                               type="email" 
                               name="email" 
                               placeholder="Email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <button class="bg-[#002D74] text-white py-2 rounded-xl hover:scale-105 duration-300 hover:bg-[#206ab1] font-medium" type="submit">
                        Email Password Reset Link
                    </button>
                </form>

                <div class="mt-10 text-sm flex justify-end items-center">
                    <a href="{{ route('login') }}" class="register text-white bg-[#002D74] hover:border-gray-400 rounded-xl py-2 px-5 hover:scale-110 hover:bg-[#002c74] font-semibold duration-300">
                        Back to Login
                    </a>
                </div>
            </div>
        </div>
    </section>
    
</body>
</html>