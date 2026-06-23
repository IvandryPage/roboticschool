<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:pending-evaluasi-notification />
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
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts::app>
