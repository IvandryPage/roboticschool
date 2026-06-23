<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                    @if (auth()->user()->role && auth()->user()->role->nama_role === 'Admin Akademik')
                        <flux:sidebar.item icon="home" href="/admin" :current="request()->is('admin') || request()->is('admin/')">
                            {{ __('Dashboard Admin') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="clipboard-document-check" :href="route('admin.pendaftaran.index')" :current="request()->routeIs('admin.pendaftaran.*')" wire:navigate>
                            Pendaftaran
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="users" :href="route('admin.siswa.index')" :current="request()->routeIs('admin.siswa.*')" wire:navigate>
                            Siswa Aktif
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="wrench" :href="route('admin.aset.index')" :current="request()->routeIs('admin.aset.*')">
                            {{ __('Kelola Aset') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="clipboard" :href="route('admin.peminjaman.index')" :current="request()->routeIs('admin.peminjaman.index')">
                            {{ __('Persetujuan Peminjaman') }}
                        </flux:sidebar.item>
                    @elseif (auth()->user()->role && auth()->user()->role->nama_role === 'Siswa')
                        <flux:sidebar.item icon="home" :href="route('siswa.dashboard')" :current="request()->routeIs('siswa.dashboard')">
                            {{ __('Dashboard Siswa') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="user-circle" :href="route('siswa.profil.show')" :current="request()->routeIs('siswa.profil.*')" wire:navigate>
                            Profil Saya
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="trophy" :href="route('sertifikat.saya')" :current="request()->routeIs('sertifikat.saya')">
                            {{ __('Sertifikat Saya') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="clipboard" :href="route('peminjaman.index')" :current="request()->routeIs('peminjaman.index')">
                            {{ __('Peminjaman Aset') }}
                        </flux:sidebar.item>
                    @elseif (auth()->user()->role && auth()->user()->role->nama_role === 'Instruktur')
                        <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="layout-grid" href="/admin" :current="request()->is('admin*')">
                            {{ __('Filament Admin') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="clipboard" :href="route('peminjaman.index')" :current="request()->routeIs('peminjaman.index')">
                            {{ __('Peminjaman Aset') }}
                        </flux:sidebar.item>
                    @elseif (auth()->user()->role && in_array(auth()->user()->role->nama_role, ['Tim Publikasi', 'Direktur']))
                        <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="layout-grid" href="/admin" :current="request()->is('admin*')">
                            {{ __('Filament Admin') }}
                        </flux:sidebar.item>
                    @else
                        <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </flux:sidebar.item>
                    @endif
                </flux:sidebar.group>

                @if (auth()->user()->role && in_array(auth()->user()->role->nama_role, ['Siswa', 'Instruktur']))
                <flux:sidebar.group :heading="__('Diskusi')" class="grid mt-4">
                    <flux:sidebar.item icon="chat-bubble-left-right" :href="route('forum.index')" :current="request()->routeIs('forum.*')" wire:navigate>
                        {{ __('Forum Diskusi') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Helpdesk')" class="grid mt-4">
                    <flux:sidebar.item icon="ticket" :href="route('keluhan.create')" :current="request()->routeIs('keluhan.create')" wire:navigate>
                        {{ __('Kirim Keluhan') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="clock" :href="route('keluhan.saya')" :current="request()->routeIs('keluhan.saya')" wire:navigate>
                        {{ __('Riwayat Keluhan') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
                @endif
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="computer-desktop" href="https://robonesia.com" target="_blank">
                    {{ __('Website RoboNesia') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://robonesia.com/docs" target="_blank">
                    {{ __('Dokumentasi') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog">
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
