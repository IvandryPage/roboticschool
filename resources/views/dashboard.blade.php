<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        @if(auth()->user()?->role?->nama_role === 'Instruktur')
            @livewire('instructor-dashboard')
        @elseif(auth()->user()?->role?->nama_role === 'Siswa')
            @livewire('student-dashboard')
        @else
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h2 class="text-xl font-bold">Selamat Datang di Dashboard!</h2>
                <p class="text-gray-600 mt-2">Anda login sebagai {{ auth()->user()?->role?->nama_role ?? 'User' }}.</p>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="flex flex-col justify-between p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-sm">
                <div>
                    <div class="flex items-center gap-2 text-neutral-500 dark:text-neutral-400">
                        <flux:icon.clipboard class="size-5" />
                        <span class="text-xs font-semibold uppercase tracking-wider">Aset & Inventaris</span>
                    </div>
                    <h3 class="mt-3 text-lg font-bold text-neutral-900 dark:text-white">Peminjaman Kit Robotik</h3>
                    <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                        @php
                            $activeCount = \App\Models\PeminjamanItemAset::where('user_id', auth()->id())
                                ->whereIn('status', ['Diajukan', 'Dipinjam'])
                                ->count();
                        @endphp
                        Kamu memiliki <strong>{{ $activeCount }}</strong> peminjaman aktif saat ini.
                    </p>
                </div>
                <div class="mt-6">
                    <flux:button href="{{ route('peminjaman.index') }}" variant="primary" class="w-full justify-center">
                        Ajukan Peminjaman
                    </flux:button>
                </div>
            </div>
        @endif
    </div>
</x-layouts::app>
