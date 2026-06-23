<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

            <x-app-logo href="{{ route('dashboard') }}" />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navbar.item>
                <flux:navbar.item icon="ticket" :href="route('keluhan.create')" :current="request()->routeIs('keluhan.create')" wire:navigate>
                    {{ __('Kirim Keluhan') }}
                </flux:navbar.item>
                <flux:navbar.item icon="clock" :href="route('keluhan.saya')" :current="request()->routeIs('keluhan.saya')" wire:navigate>
                    {{ __('Riwayat Keluhan') }}
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                <flux:tooltip :content="__('Search')" position="bottom">
                    <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#" :label="__('Search')" />
                </flux:tooltip>
                <flux:tooltip :content="__('Website RoboNesia')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="computer-desktop"
                        href="https://robonesia.com"
                        target="_blank"
                        :label="__('Website')"
                    />
                </flux:tooltip>
                <flux:tooltip :content="__('Dokumentasi')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="book-open-text"
                        href="https://robonesia.com/docs"
                        target="_blank"
                        :label="__('Dokumentasi')"
                    />
                </flux:tooltip>
            </flux:navbar>

            <x-desktop-user-menu />
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" />
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')">
                    @if (auth()->user()->role && auth()->user()->role->nama_role === 'Admin Akademik')
                        <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="layout-grid" href="/admin" :current="request()->is('admin*')">
                            {{ __('Filament Admin') }}
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
                    @elseif (auth()->user()->role && in_array(auth()->user()->role->nama_role, ['Instruktur', 'Tim Publikasi', 'Direktur']))
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
                        <flux:sidebar.item icon="clipboard" :href="route('peminjaman.index')" :current="request()->routeIs('peminjaman.index')">
                            {{ __('Peminjaman Aset') }}
                        </flux:sidebar.item>
                    @endif
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Helpdesk')" class="mt-4">
                    <flux:sidebar.item icon="ticket" :href="route('keluhan.create')" :current="request()->routeIs('keluhan.create')" wire:navigate>
                        {{ __('Kirim Keluhan') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="clock" :href="route('keluhan.saya')" :current="request()->routeIs('keluhan.saya')" wire:navigate>
                        {{ __('Riwayat Keluhan') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
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
        </flux:sidebar>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
