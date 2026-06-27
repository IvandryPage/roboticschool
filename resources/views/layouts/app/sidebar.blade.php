<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('siswa.dashboard') }}" />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                @php $role = auth()->user()?->role?->nama_role; @endphp

                @if($role === 'Siswa')
                    <flux:sidebar.item icon="home"
                        :href="route('siswa.dashboard')"
                        :current="request()->routeIs('siswa.dashboard')">
                        Dashboard
                    </flux:sidebar.item>

                    <flux:sidebar.group heading="Akademik" class="grid mt-3">
                        <flux:sidebar.item icon="calendar-days"
                            :href="route('siswa.jadwal.index')"
                            :current="request()->routeIs('siswa.jadwal.*')">
                            Jadwal Live Session
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="book-open"
                            :href="route('siswa.materi.index')"
                            :current="request()->routeIs('siswa.materi.*')">
                            Modul Pembelajaran
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="clipboard-document-list"
                            :href="route('siswa.tugas.index')"
                            :current="request()->routeIs('siswa.tugas.*')">
                            Penugasan
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="chart-bar"
                            :href="route('siswa.progres.index')"
                            :current="request()->routeIs('siswa.progres.*')">
                            Progres Belajar
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="trophy"
                            :href="route('sertifikat.saya')"
                            :current="request()->routeIs('sertifikat.saya')">
                            Sertifikat
                        </flux:sidebar.item>
                    </flux:sidebar.group>

                    <flux:sidebar.group heading="Lainnya" class="grid mt-3">
                        <flux:sidebar.item icon="wrench"
                            :href="route('peminjaman.index')"
                            :current="request()->routeIs('peminjaman.index')">
                            Peminjaman Aset
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="chat-bubble-left-right"
                            :href="route('forum.index')"
                            :current="request()->routeIs('forum.*')">
                            Forum Diskusi
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="exclamation-triangle"
                            :href="route('keluhan.create')"
                            :current="request()->routeIs('keluhan.create')">
                            Kirim Keluhan
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="clock"
                            :href="route('keluhan.saya')"
                            :current="request()->routeIs('keluhan.saya')">
                            Riwayat Keluhan
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="user-circle"
                            :href="route('siswa.profil.show')"
                            :current="request()->routeIs('siswa.profil.*')">
                            Profil Saya
                        </flux:sidebar.item>
                    </flux:sidebar.group>

                @elseif($role === 'Admin Akademik')
                    <flux:sidebar.group heading="Platform" class="grid">
                        <flux:sidebar.item icon="home" href="/admin"
                            :current="request()->is('admin') || request()->is('admin/')">
                            Dashboard Admin
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="clipboard-document-check"
                            :href="route('admin.pendaftaran.index')"
                            :current="request()->routeIs('admin.pendaftaran.*')" wire:navigate>
                            Pendaftaran
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="users"
                            :href="route('admin.siswa.index')"
                            :current="request()->routeIs('admin.siswa.*')" wire:navigate>
                            Siswa Aktif
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="wrench"
                            :href="route('admin.aset.index')"
                            :current="request()->routeIs('admin.aset.*')">
                            Kelola Aset
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="clipboard"
                            :href="route('admin.peminjaman.index')"
                            :current="request()->routeIs('admin.peminjaman.index')">
                            Persetujuan Peminjaman
                        </flux:sidebar.item>
                    </flux:sidebar.group>

                @else
                    <flux:sidebar.group heading="Platform" class="grid">
                        <flux:sidebar.item icon="home"
                            :href="route('dashboard')"
                            :current="request()->routeIs('dashboard')">
                            Dashboard
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                @endif

            </flux:sidebar.nav>

            <flux:spacer />

            {{-- Desktop: user menu di dalam sidebar (unchanged) --}}
            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        {{-- Mobile header --}}
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            <flux:dropdown position="top" align="end">
                <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />
                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-sm">
                                <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                                <div class="grid flex-1 leading-tight">
                                    <span class="font-semibold truncate text-sm">{{ auth()->user()->name }}</span>
                                    <span class="text-xs text-zinc-400 truncate">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <flux:menu.radio.group>
                        @if(auth()->user()?->role?->nama_role === 'Siswa')
                            <flux:menu.item :href="route('siswa.profil.show')" icon="user">Profil Saya</flux:menu.item>
                        @else
                            <flux:menu.item :href="route('profile.edit')" icon="cog">Settings</flux:menu.item>
                        @endif
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer">
                            Log out
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{-- Content: tidak ada perubahan sama sekali dari versi yang benar --}}
        {{ $slot }}

        @persist('toast')
            <flux:toast.group><flux:toast /></flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>