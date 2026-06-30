<x-layouts::auth.split :title="'Login RoboNesia'">

    <div class="w-full max-w-[450px] mx-auto px-4 py-10 sm:px-0">

        <div class="mb-10 -ml-4 pl-4">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-700" wire:navigate>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>

        <div class="space-y-2 text-center">
            <h1 class="text-4xl font-bold tracking-tight text-slate-950">Selamat Datang! <span class="inline-block">👋</span></h1>
            <p class="text-sm text-slate-500">Masuk ke akun RoboNesia Academy kamu</p>
        </div>

        <div class="mt-10">
            @if (Route::has('auth.google.redirect'))
            <a href="{{ route('auth.google.redirect') }}" class="btn-google">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22 12.24c0-.82-.07-1.61-.2-2.38H12v4.52h5.43c-.23 1.24-.92 2.28-1.97 2.99v2.48h3.18c1.86-1.71 2.93-4.26 2.93-7.61z" fill="#4285F4"/>
                    <path d="M12 23c2.43 0 4.47-.8 5.96-2.16l-3.18-2.48c-.88.6-2.02.96-3.78.96-2.9 0-5.36-1.96-6.24-4.59H2.47v2.88C3.95 20.98 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.76 14.73a7.2 7.2 0 010-4.46V7.39H2.47a11 11 0 000 9.22l3.29-1.88z" fill="#FBBC05"/>
                    <path d="M12 4.5c1.66 0 3.15.57 4.33 1.7l-3.24-3.24C16.46 1.18 14.43 0 12 0 7.7 0 3.95 2.02 2.47 5.39l3.29 1.88A7.96 7.96 0 0112 4.5z" fill="#EA4335"/>
                </svg>
                Lanjutkan dengan Google
            </a>
            @endif

            <div class="my-8 flex items-center gap-3">
                <div class="flex-1 h-px bg-slate-200"></div>
                <div class="text-[11px] uppercase tracking-[0.35em] font-semibold text-slate-400 divider-text whitespace-nowrap">atau masuk dengan email</div>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                Email atau password yang Anda masukkan salah.
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Alamat Email</label>
                <div class="relative form-input-pill">
                    <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 6.75C3 5.784 3.784 5 4.75 5h14.5c.966 0 1.75.784 1.75 1.75v10.5c0 .966-.784 1.75-1.75 1.75H4.75A1.75 1.75 0 013 17.25V6.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="m3.75 6.5 8.25 5.25L20.25 6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="email@contoh.com"
                        class="w-full bg-transparent pl-11 text-sm text-slate-900 outline-none placeholder:text-slate-400"
                        required
                    />
                </div>
            </div>

            <div>
                <div class="mb-2 flex items-center justify-between">
                    <label class="text-sm font-semibold text-slate-700">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-semibold text-cyan-500 hover:text-cyan-600">Lupa Password?</a>
                    @endif
                </div>
                <div class="relative form-input-pill" x-data="{ showPassword: false }">
                    <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 11V8a5 5 0 0 0-10 0v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M9 16h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        type="password"
                        name="password"
                        placeholder="Masukkan Password"
                        class="w-full bg-transparent pl-11 pr-10 text-sm text-slate-900 outline-none placeholder:text-slate-400"
                        required
                    />
                    <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-slate-600">
                        <svg x-show="!showPassword" class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <svg x-show="showPassword" style="display: none;" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                            <path d="M1 12s4-8 11-8"/>
                            <path d="M4.73 7.73a11.13 11.13 0 0 0-3.73 4.27s4 8 11 8c1.78 0 3.38-.5 4.77-1.38"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="form-cta">
                Masuk ke Akun →
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-slate-500">
            Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-cyan-600 hover:text-cyan-700">Daftar Sekarang</a>
        </div>

        <div class="mt-12 text-center text-xs text-slate-400">
            © 2025 RoboNesia Academy
        </div>
    </div>

</x-layouts::auth.split>
