<x-layouts::app :title="'Status Pendaftaran Kelas'">

    <div class="mb-6">
        <flux:heading size="xl" class="font-bold">Status Pendaftaran Kelas</flux:heading>
        <flux:text class="mt-1 text-zinc-500">Pantau status verifikasi pembayaran kelas yang kamu daftar.</flux:text>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @php
        $enrollmentPending = $siswa->enrollmentKelas->where('status', 'Pending');
    @endphp

    @if($enrollmentPending->isEmpty())
        <div class="bg-white border border-zinc-200 rounded-xl p-12 text-center">
            <flux:icon name="check-circle" class="w-12 h-12 text-green-400 mx-auto mb-3" />
            <p class="text-zinc-500">Tidak ada pendaftaran kelas yang sedang menunggu verifikasi.</p>
            <a href="{{ route('siswa.daftar-kelas.index') }}" class="mt-4 inline-block">
                <flux:button variant="primary" size="sm">Daftar Kelas Baru</flux:button>
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($enrollmentPending as $enrollment)
            <div class="bg-white border border-zinc-200 rounded-xl p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-semibold text-zinc-800">{{ $enrollment->kelas?->nama_kelas ?? '-' }}</p>
                        <p class="text-sm text-zinc-500 mt-0.5">{{ $enrollment->kelas?->batch?->program?->nama_program ?? '-' }}</p>
                        <p class="text-xs text-zinc-400 mt-1">Didaftarkan: {{ $enrollment->created_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        Menunggu Verifikasi Admin
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</x-layouts::app>
