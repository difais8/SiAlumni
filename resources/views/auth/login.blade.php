<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SiAlumni') }} - Login</title>
    <link rel="shortcut icon" href="{{ asset('images/iconLogoSMP_m.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="font-sans text-gray-900 antialiased">

    <div class="absolute top-5 left-5">
        <x-auth-session-status class="mb-4" :status="session('status')" />
    </div>

    <section class="bg-gray-100 min-h-screen flex box-border justify-center items-center">
        <div class="rounded-2xl flex max-w-3xl p-5 items-center shadow-lg bg-white">
            
            <div class="md:w-1/2 px-8">
                <h2 class="font-bold text-3xl text-[#002D74]">Login</h2>
                <p class="text-sm mt-4 text-[#002D74]">Silahkan isi form dibawah untuk login.</p>

                <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
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

                    <div class="relative">
                        <input class="p-2 rounded-xl border w-full" 
                               type="password" 
                               name="password" 
                               id="password" 
                               placeholder="Password"
                               required 
                               autocomplete="current-password" />
                        
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="gray" id="togglePassword"
                            class="bi bi-eye absolute top-1/2 right-3 -translate-y-1/2 cursor-pointer z-10"
                            viewBox="0 0 16 16">
                            <path
                                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z">
                            </path>
                            <path
                                d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z">
                            </path>
                        </svg>
                        {{-- (Saya sengaja tidak menyertakan ikon mata tertutup 
                             agar toggle-nya lebih sederhana) --}}
                        
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    
                    <button class="bg-[#002D74] text-white py-2 rounded-xl hover:scale-105 duration-300 hover:bg-[#206ab1] font-medium" type="submit">
                        Login
                    </button>
                </form>

                <div class="mt-10 text-sm border-b border-gray-500 py-5">
                    @if (Route::has('password.request'))
                        <a class="hover:text-blue-700" href="{{ route('password.request') }}">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <div class="mt-4 text-sm flex justify-between items-center">
                    <p class="mr-3 md:mr-0 ">Belum memiliki akun?</p>
                    <a href="{{ route('register') }}" class="register text-white bg-[#002D74] hover:border-gray-400 rounded-xl py-2 px-5 hover:scale-110 hover:bg-[#002c74] font-semibold duration-300">
                        Register
                    </a>
                </div>
            </div>

            <div class="md:block hidden w-1/2">
                <img class="rounded-2xl max-h-[1600px]" src="{{ asset('images/iconLogoSMP.png') }}" alt="login form image" style="background-color: #eef3ff;">
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            if(togglePassword) {
                togglePassword.addEventListener('click', function (e) {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    
                    // Ubah opasitas ikon saat di-klik
                    this.style.opacity = (type === 'password') ? 1 : 0.5;
                });
            }
        });
    </script>
    
</body>
</html>