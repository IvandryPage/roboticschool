<x-layouts::app :title="'Penugasan'">

    <div class="mb-6">
        <flux:heading size="xl" class="font-bold">Penugasan</flux:heading>
        <flux:text class="text-zinc-500 mt-1">Daftar tugas, submit jawaban, dan lihat nilai dari instruktur.</flux:text>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-4">{{ session('success') }}</flux:callout>
    @endif
    @if(session('error'))
        <flux:callout variant="danger" icon="x-circle" class="mb-4">{{ session('error') }}</flux:callout>
    @endif

    @if($tugas->isEmpty())
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-12 text-center shadow-sm">
            <flux:icon name="clipboard-document-list" class="size-10 text-zinc-300 mx-auto mb-3" />
            <flux:text class="text-zinc-400">Belum ada tugas untuk kelasmu saat ini.</flux:text>
        </div>
    @else
        <div class="space-y-4">
            @foreach($tugas as $t)
                @php
                    $pengumpulan = $t->pengumpulanTugas->first();
                    $sudahKumpul = $pengumpulan !== null;
                    $sudahDinilai = $pengumpulan?->status_penilaian === 'Dinilai';
                    $lewatDeadline = $t->batas_waktu && now()->greaterThan($t->batas_waktu);
                    $bisaKumpul = !$sudahKumpul && !$lewatDeadline;
                @endphp

                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <div class="font-semibold text-zinc-800 dark:text-zinc-100">{{ $t->judul_tugas }}</div>
                                <div class="text-sm text-zinc-500 mt-0.5">
                                    {{ $t->sesi?->kelas?->nama_kelas ?? '-' }} •
                                    Sesi: {{ $t->sesi?->judul_sesi ?? '-' }}
                                </div>
                                @if($t->deskripsi)
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-2">{{ $t->deskripsi }}</p>
                                @endif
                                <div class="flex flex-wrap gap-3 mt-3 text-xs text-zinc-400">
                                    @if($t->batas_waktu)
                                        <span class="flex items-center gap-1 {{ $lewatDeadline ? 'text-red-400' : '' }}">
                                            <flux:icon name="clock" class="size-3" />
                                            Deadline: {{ \Carbon\Carbon::parse($t->batas_waktu)->translatedFormat('d M Y, H:i') }}
                                        </span>
                                    @endif
                                    @if($t->nilai_maksimum)
                                        <span class="flex items-center gap-1">
                                            <flux:icon name="star" class="size-3" />
                                            Maks: {{ $t->nilai_maksimum }}
                                        </span>
                                    @endif
                                    @if($t->file_soal)
                                        <a href="{{ asset('storage/' . $t->file_soal) }}" download
                                           class="flex items-center gap-1 text-cyan-600 hover:underline">
                                            <flux:icon name="document-arrow-down" class="size-3" />
                                            Unduh Soal
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- Badge Status --}}
                            <div class="shrink-0">
                                @if($sudahDinilai)
                                    <flux:badge color="green">Dinilai: {{ $pengumpulan->nilai }}/{{ $t->nilai_maksimum }}</flux:badge>
                                @elseif($sudahKumpul)
                                    <flux:badge color="yellow">Menunggu Penilaian</flux:badge>
                                @elseif($lewatDeadline)
                                    <flux:badge color="red">Melewati Deadline</flux:badge>
                                @else
                                    <flux:badge color="gray">Belum Dikumpulkan</flux:badge>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Nilai & Feedback --}}
                    @if($sudahDinilai && $pengumpulan->umpan_balik)
                        <div class="px-5 py-3 bg-green-50 dark:bg-green-900/20 border-t border-green-100 dark:border-green-800">
                            <flux:text class="text-xs font-semibold text-green-700 dark:text-green-400 mb-1">Umpan Balik Instruktur</flux:text>
                            <p class="text-sm text-green-700 dark:text-green-300">{{ $pengumpulan->umpan_balik }}</p>
                        </div>
                    @endif

                    {{-- Form Submit --}}
                    @if($bisaKumpul)
                        <div class="px-5 py-4 border-t border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/40">
                            <flux:text class="text-xs font-semibold text-zinc-500 mb-3">Kumpulkan Jawaban</flux:text>
                            <form method="POST"
                                  action="{{ route('siswa.tugas.kumpul', $t) }}"
                                  enctype="multipart/form-data"
                                  class="space-y-3">
                                @csrf
                                <div>
                                    <flux:label>File Jawaban (PDF, Word, Gambar, ZIP — maks 10MB)</flux:label>
                                    <input type="file" name="file_jawaban"
                                           class="mt-1 block w-full text-sm text-zinc-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 dark:file:bg-cyan-900/30 dark:file:text-cyan-400"
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip" />
                                </div>
                                <div>
                                    <flux:label>Catatan (opsional)</flux:label>
                                    <flux:textarea name="catatan_siswa" rows="2"
                                                   placeholder="Tambahkan catatan untuk instruktur..." />
                                </div>
                                <flux:button type="submit" variant="primary" size="sm" icon="paper-airplane">
                                    Kumpulkan
                                </flux:button>
                            </form>
                        </div>
                    @elseif($sudahKumpul)
                        <div class="px-5 py-3 border-t border-zinc-100 dark:border-zinc-800 text-xs text-zinc-400">
                            Dikumpulkan pada {{ \Carbon\Carbon::parse($pengumpulan->waktu_kumpul)->translatedFormat('d M Y, H:i') }}
                            @if($pengumpulan->catatan_siswa)
                                · "{{ $pengumpulan->catatan_siswa }}"
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

</x-layouts::app>
