<x-layouts::app :title="'Daftar Kelas Baru'">

    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <flux:heading size="xl" class="font-bold">Daftar Kelas Baru</flux:heading>
            <flux:text class="mt-1 text-zinc-500">
                Pilih program dan kelas yang ingin kamu ikuti. Data dirimu sudah tersimpan — tinggal pilih dan bayar.
            </flux:text>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($programs->isEmpty())
        <div class="bg-white border border-zinc-200 rounded-xl p-12 text-center">
            <flux:icon name="academic-cap" class="w-12 h-12 text-zinc-300 mx-auto mb-3" />
            <p class="text-zinc-500">Tidak ada program tersedia saat ini, atau kamu sudah terdaftar di semua kelas yang aktif.</p>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($programs as $program)
                @foreach($program->batches as $batch)
                    @foreach($batch->kelas as $kelas)
                    <div class="bg-white border border-zinc-200 rounded-xl p-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-semibold bg-cyan-100 text-cyan-700 px-2 py-0.5 rounded-full">
                                        {{ $program->nama_program }}
                                    </span>
                                    <span class="text-xs text-zinc-400">{{ $batch->nama_batch ?? 'Batch ' . now()->year }}</span>
                                </div>
                                <h3 class="font-bold text-zinc-800 text-lg">{{ $kelas->nama_kelas }}</h3>
                                <div class="mt-2 flex flex-wrap gap-4 text-sm text-zinc-500">
                                    <span>Instruktur: <strong class="text-zinc-700">{{ $kelas->instruktur?->nama_lengkap ?? '-' }}</strong></span>
                                    <span>Kapasitas: <strong class="text-zinc-700">{{ $kelas->enrollments_count ?? $kelas->enrollmentKelas->count() }}/{{ $kelas->kapasitas }}</strong></span>
                                    <span>Biaya: <strong class="text-cyan-600">Rp{{ number_format($program->biaya, 0, ',', '.') }}</strong></span>
                                </div>
                            </div>

                            {{-- Form Daftar --}}
                            <div x-data="{ open: false }" class="shrink-0">
                                <flux:button
                                    x-show="!open"
                                    @click="open = true"
                                    variant="primary"
                                    size="sm">
                                    Daftar Kelas Ini
                                </flux:button>

                                <form
                                    x-show="open"
                                    x-transition
                                    method="POST"
                                    action="{{ route('siswa.daftar-kelas.store') }}"
                                    enctype="multipart/form-data"
                                    class="mt-3 p-4 bg-zinc-50 border border-zinc-200 rounded-xl space-y-3 min-w-72">
                                    @csrf
                                    <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

                                    <div>
                                        <label class="block text-xs font-semibold text-zinc-600 mb-1">
                                            Upload Bukti Pembayaran
                                        </label>
                                        <input
                                            type="file"
                                            name="bukti_pembayaran"
                                            accept=".jpg,.jpeg,.png,.pdf"
                                            class="block w-full text-sm text-zinc-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100"
                                            required>
                                        <p class="text-xs text-zinc-400 mt-1">JPG/PNG/PDF, maks 5MB</p>
                                    </div>

                                    <p class="text-xs text-zinc-500">
                                        Transfer ke rekening: <strong>BCA 1234567890 a.n. RoboNesia Academy</strong>.
                                        Upload bukti setelah transfer, Admin akan verifikasi dalam 1×24 jam.
                                    </p>

                                    <div class="flex gap-2">
                                        <flux:button type="submit" variant="primary" size="sm" class="flex-1">
                                            Kirim Pendaftaran
                                        </flux:button>
                                        <flux:button type="button" @click="open = false" variant="ghost" size="sm">
                                            Batal
                                        </flux:button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endforeach
            @endforeach
        </div>
    @endif

</x-layouts::app>
