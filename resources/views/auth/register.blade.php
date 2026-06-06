<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Register</title>
    <link rel="shortcut icon" href="{{ asset('images/iconLogoSMP_m.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="font-sans text-gray-900 antialiased">

    <section class="bg-gray-100 min-h-screen flex box-border justify-center items-center">
        <div class="rounded-2xl flex max-w-3xl p-5 items-center shadow-lg bg-white">
            
            <div class="md:w-1/2 px-8">
                <h2 class="font-bold text-3xl text-[#002D74]">Register</h2>
                <p class="text-sm mt-4 text-[#002D74]">Silahakan isi form dibawah ini untuk membuat akun.</p>

                <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4">
                    @csrf

                    <div>
                        <input class="p-2 mt-8 rounded-xl border w-full" 
                               type="text" 
                               name="name" 
                               placeholder="Nama Lengkap" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <input class="p-2 mt-4 rounded-xl border w-full" 
                               type="text" 
                               name="username" 
                               placeholder="Username" 
                               value="{{ old('username') }}" 
                               required />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <div>
                        <input class="p-2 mt-4 rounded-xl border w-full" 
                               type="email" 
                               name="email" 
                               placeholder="Email" 
                               value="{{ old('email') }}" 
                               required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="relative">
                        <input class="p-2 mt-4 rounded-xl border w-full" 
                               type="password" 
                               name="password" 
                               id="password" 
                               placeholder="Password"
                               required 
                               autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="relative">
                        <input class="p-2 mt-4 rounded-xl border w-full" 
                               type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               placeholder="Confirm Password"
                               required 
                               autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button class="bg-[#002D74] text-white py-2 rounded-xl hover:scale-105 duration-300 hover:bg-[#206ab1] font-medium mt-4" type="submit">
                        Register
                    </button>
                </form>

                <div class="mt-6 text-sm flex justify-between items-center">
                    <p class="mr-3 md:mr-0 ">Sudah memiliki akun?</p>
                    <a href="{{ route('login') }}" class="register text-white bg-[#002D74] hover:border-gray-400 rounded-xl py-2 px-5 hover:scale-110 hover:bg-[#002c74] font-semibold duration-300">
                        Login
                    </a>
                </div>
            </div>

            <div class="md:block hidden w-1/2">
                <img class="rounded-2xl max-h-[1600px]" src="{{ asset('images/iconLogoSMP.png') }}" alt="register form image" style="background-color: #eef3ff;">
            </div>
        </div>
    </section>
    
</body>
</html>