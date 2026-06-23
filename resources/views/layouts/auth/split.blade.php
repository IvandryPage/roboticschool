<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased">
        <div class="relative grid h-dvh min-h-screen items-stretch justify-center overflow-hidden bg-white px-0 py-0 sm:px-0 lg:grid-cols-2 lg:px-0">
            <div class="relative hidden h-full overflow-hidden bg-[#081B34] px-8 py-0 text-white lg:flex lg:flex-col lg:items-center lg:justify-center">
                <div class="absolute inset-0 bg-gradient-to-br from-[#0B1E3B] via-[#0D4F6D] to-[#0EA5C6]"></div>
                <div class="absolute inset-0 opacity-30" style="background-image: linear-gradient(rgba(255,255,255,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 40px 40px;"></div>

                <div class="absolute top-10 left-6 z-20">
                    <div class="inline-flex items-center gap-3 rounded-full border border-white/10 bg-white/10 px-4 py-2 text-sm font-semibold text-white backdrop-blur-md shadow-[0_20px_50px_rgba(0,0,0,0.16)]">
                        <img src="{{ asset('images/logo-robonesia.svg') }}" alt="RoboNesia" class="h-6 w-6" />
                        <span>RoboNesia Academy</span>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-center gap-6 relative z-10">
                    <div class="relative flex h-[320px] w-[320px] items-center justify-center rounded-[32px] bg-white/5 shadow-[0_20px_60px_rgba(0,0,0,0.3)] ring-1 ring-white/5">
                        <img src="{{ asset('images/logo-robonesia.svg') }}" alt="RoboNesia" class="h-32 w-32 opacity-90" />

                        <div class="absolute right-4 top-6 rounded-[20px] bg-white/8 px-4 py-2 text-center text-xs text-white backdrop-blur-sm shadow-[0_8px_20px_rgba(0,0,0,0.2)]">
                            <div class="text-lg font-semibold">500+</div>
                            <div class="text-[10px] uppercase tracking-[0.1em] text-slate-300">Siswa Aktif</div>
                        </div>

                        <div class="absolute left-4 bottom-6 rounded-[20px] bg-white/8 px-4 py-2 text-center text-xs text-white backdrop-blur-sm shadow-[0_8px_20px_rgba(0,0,0,0.2)]">
                            <div class="text-lg font-semibold">95%</div>
                            <div class="text-[10px] uppercase tracking-[0.1em] text-slate-300">Tingkat Lulus</div>
                        </div>
                    </div>

                    <div class="w-full px-4">
                        <p class="text-center text-base leading-8 text-white font-medium">"Bergabung dengan ribuan pelajar robotika Indonesia"</p>
                    </div>
                </div>

                <div class="absolute left-6 bottom-6 flex items-center gap-3 text-sm text-slate-200 z-20">
                    <div class="flex items-center gap-1">
                        <svg class="h-4 w-4 text-amber-300" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 .587l3.668 7.431L23.4 9.753l-5.7 5.556L18.834 24 12 20.201 5.166 24l1.134-8.691L.6 9.753l7.732-1.735L12 .587z"/></svg>
                        <svg class="h-4 w-4 text-amber-300" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 .587l3.668 7.431L23.4 9.753l-5.7 5.556L18.834 24 12 20.201 5.166 24l1.134-8.691L.6 9.753l7.732-1.735L12 .587z"/></svg>
                        <svg class="h-4 w-4 text-amber-300" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 .587l3.668 7.431L23.4 9.753l-5.7 5.556L18.834 24 12 20.201 5.166 24l1.134-8.691L.6 9.753l7.732-1.735L12 .587z"/></svg>
                        <svg class="h-4 w-4 text-amber-300" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 .587l3.668 7.431L23.4 9.753l-5.7 5.556L18.834 24 12 20.201 5.166 24l1.134-8.691L.6 9.753l7.732-1.735L12 .587z"/></svg>
                        <svg class="h-4 w-4 text-amber-300" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 .587l3.668 7.431L23.4 9.753l-5.7 5.556L18.834 24 12 20.201 5.166 24l1.134-8.691L.6 9.753l7.732-1.735L12 .587z"/></svg>
                    </div>
                    <span>4.9 rating dari alumni</span>
                </div>
            </div>

            <div class="w-full px-4 py-10 sm:px-6 lg:px-12">
                <div class="mx-auto flex w-full max-w-[450px] flex-col justify-center space-y-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-700 lg:hidden" wire:navigate>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Kembali ke Beranda
                    </a>

                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
