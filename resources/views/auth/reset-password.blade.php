<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - {{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('images/iconLogoSMP_m.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">

    <section class="bg-gray-100 min-h-screen flex box-border justify-center items-center">
        <div class="bg-white rounded-2xl flex max-w-6xl p-5 items-center shadow-lg">
            
            <div class="px-8">
                <h2 class="font-bold text-3xl text-[#002D74] text-center">Reset Password</h2>
                <p class="text-sm mt-4 text-[#002D74]">
                    Silakan buat password baru untuk akun Anda.
                </p>

                <form method="POST" action="{{ route('password.store') }}" class="flex flex-col gap-4 mt-6">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <input class="p-2 rounded-xl border w-full" 
                               type="email" 
                               name="email" 
                               placeholder="Email" 
                               value="{{ old('email', $request->email) }}" 
                               required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="relative">
                        <input class="p-2 rounded-xl border w-full" 
                               type="password" 
                               name="password" 
                               id="password"
                               placeholder="Password Baru" 
                               required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="relative">
                        <input class="p-2 rounded-xl border w-full" 
                               type="password" 
                               name="password_confirmation" 
                               id="password_confirmation"
                               placeholder="Konfirmasi Password Baru" 
                               required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button class="bg-[#002D74] text-white py-2 rounded-xl hover:scale-105 duration-300 hover:bg-[#206ab1] font-medium mt-4 mb-4" type="submit">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </section>

</body>
</html>