<x-layouts::app :title="'Status Pendaftaran Saya'">
    <div class="max-w-3xl mx-auto p-6">
        <flux:heading size="xl" class="font-bold mb-1">Status Pendaftaran Saya</flux:heading>
        <flux:text class="text-slate-500 mb-6">Riwayat dan status semua pendaftaran program kursus kamu.</flux:text>

        @if($pendaftarans->isEmpty())
            <div class="rounded-xl border border-slate-200 bg-white p-10 text-center shadow-sm">
                <flux:icon.document-text class="h-12 w-12 text-slate-300 mx-auto mb-3" />
                <flux:heading size="lg" class="text-slate-500">Belum ada pendaftaran</flux:heading>
                <flux:text class="text-slate-400 mt-1">Kamu belum pernah mendaftarkan diri ke program kursus.</flux:text>
                <div class="mt-5">
                    <flux:button href="{{ route('pendaftaran.create') }}" variant="primary">Daftar Sekarang</flux:button>
                </div>
            </div>
        @else
            <div class="flex flex-col gap-4">
                @foreach($pendaftarans as $p)
                @php
                    $badgeColor = match($p->status) {
                        'disetujui', 'lunas' => 'green',
                        'revisi' => 'yellow',
                        'ditolak' => 'red',
                        default => 'zinc',
                    };
                    $badgeLabel = match($p->status) {
                        'pending', 'Menunggu' => 'Menunggu Verifikasi',
                        'disetujui' => 'Disetujui',
                        'lunas' => 'Pembayaran Diterima',
                        'revisi' => 'Perlu Revisi',
                        'ditolak' => 'Ditolak',
                        'menunggu_verifikasi_pembayaran' => 'Menunggu Verifikasi Pembayaran',
                        default => ucfirst($p->status),
                    };
                @endphp
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <flux:heading size="sm" class="font-bold">{{ $p->program?->nama_program ?? 'Program' }}</flux:heading>
                            <flux:text class="text-slate-400 text-xs mt-0.5">
                                Didaftarkan {{ $p->created_at?->translatedFormat('d F Y') }}
                            </flux:text>
                        </div>
                        <flux:badge color="{{ $badgeColor }}" size="sm">{{ $badgeLabel }}</flux:badge>
                    </div>

                    <div class="mt-4 rounded-lg bg-slate-50 px-4 py-3">
                        <p class="text-xs text-slate-400 mb-1">Kode Pendaftaran</p>
                        <p class="font-mono font-bold text-slate-800 text-lg tracking-widest">
                            {{ $p->no_referensi ?? '-' }}
                        </p>
                    </div>

                    @if($p->catatan_admin)
                    <div class="mt-3 rounded-lg bg-yellow-50 border border-yellow-200 px-4 py-3">
                        <p class="text-xs font-semibold text-yellow-700 mb-1">Catatan Admin:</p>
                        <p class="text-sm text-yellow-800">{{ $p->catatan_admin }}</p>
                    </div>
                    @endif

                    <div class="mt-4 flex gap-2">
                        @if($p->status === 'revisi')
                            <flux:button href="{{ route('pendaftaran.revisi', $p->id) }}" variant="primary" size="sm">
                                Upload Revisi
                            </flux:button>
                        @endif
                        @if(in_array($p->status, ['disetujui']))
                            <flux:button href="{{ route('pembayaran.index', $p->id) }}" variant="primary" size="sm">
                                Bayar Sekarang
                            </flux:button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts::app>
