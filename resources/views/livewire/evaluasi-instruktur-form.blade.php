<div class="flex h-full w-full flex-1 flex-col gap-10 p-6">

    {{-- Hero Header --}}
    <div class="brand-gradient rounded-xl p-6 text-white shadow-[var(--shadow-brand-glow)]">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-cyan-300">Kuesioner Evaluasi</p>
                <h1 class="font-sans text-2xl font-bold leading-tight">Evaluasi Instruktur</h1>
                <p class="mt-1 text-sm text-slate-300">Berikan penilaian jujur untuk meningkatkan kualitas pengajaran.</p>
            </div>

            @if (! $sudahDiisi)
                <div class="shrink-0 text-right">
                    <p class="text-xs text-cyan-300">Progress</p>
                    <p class="mt-0.5 text-2xl font-bold {{ $totalTerjawab === $totalPertanyaan ? 'text-emerald-400' : 'text-white' }}">
                        {{ $totalTerjawab }}<span class="text-sm font-normal text-slate-300"> / {{ $totalPertanyaan }}</span>
                    </p>
                    <div class="mt-2 h-2 w-28 overflow-hidden rounded-full bg-white/20">
                        <div
                            class="h-full rounded-full transition-all duration-500 {{ $totalTerjawab === $totalPertanyaan ? 'bg-emerald-400' : 'progress-gradient' }}"
                            style="width: max({{ $totalPertanyaan > 0 ? ($totalTerjawab / $totalPertanyaan) * 100 : 0 }}%, {{ $totalTerjawab > 0 ? '8px' : '0px' }})"
                        ></div>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">pertanyaan dijawab</p>
                </div>
            @endif
        </div>

        <div class="mt-4 flex flex-wrap gap-6 border-t border-white/10 pt-4">
            <div>
                <p class="text-xs text-cyan-300">Kelas</p>
                <p class="mt-0.5 font-semibold">{{ $kelas->nama_kelas }}</p>
            </div>
            <div>
                <p class="text-xs text-cyan-300">Instruktur</p>
                <p class="mt-0.5 font-semibold">{{ $kelas->instruktur->nama_lengkap ?? $kelas->instruktur->name }}</p>
            </div>
        </div>
    </div>

    {{-- Sudah diisi --}}
    @if ($sudahDiisi)
        <div class="flex flex-col items-center justify-center gap-6 rounded-xl border border-slate-100 bg-white px-8 py-20 shadow-[var(--shadow-card)] dark:border-slate-700 dark:bg-slate-800">
            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-900/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-500" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="text-center">
                <h2 class="font-sans text-2xl font-bold text-slate-800 dark:text-white">Evaluasi Terkirim!</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Terima kasih! Penilaian Anda untuk <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $kelas->nama_kelas }}</span> telah berhasil disimpan.
                </p>
            </div>
            <a href="{{ route('dashboard') }}" wire:navigate
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-[var(--shadow-subtle)] transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600">
                ← Kembali ke Dashboard
            </a>
        </div>

    {{-- Form --}}
    @else
        <form wire:submit="submit" class="flex flex-col gap-6">

            {{-- Pertanyaan --}}
            @foreach ($pertanyaan as $key => $teks)
                <div class="rounded-xl border border-slate-100 bg-white p-6 shadow-[var(--shadow-subtle)] dark:border-slate-700 dark:bg-slate-800">
                    <div class="flex items-start gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-cyan-50 text-xs font-bold text-cyan-600 dark:bg-cyan-900/40 dark:text-cyan-400">
                            {{ $loop->iteration }}
                        </span>
                        <p class="text-sm font-medium leading-relaxed text-slate-800 dark:text-slate-200">
                            {{ $teks }}
                        </p>
                    </div>

                    <div class="mt-6 flex flex-row gap-3">
                        @foreach (range(1, 5) as $nilai)
                            @php $selected = (int) ($jawaban[$key] ?? 0) === $nilai; @endphp
                            <label class="flex-1 cursor-pointer">
                                <input
                                    type="radio"
                                    wire:model.live="jawaban.{{ $key }}"
                                    value="{{ $nilai }}"
                                    class="sr-only"
                                />
                                <div class="flex flex-col items-center gap-2 rounded-xl py-4 transition-all duration-150
                                    {{ $selected
                                        ? 'bg-cyan-500 shadow-[0_0_16px_rgba(6,182,212,0.35)]'
                                        : 'bg-slate-100 hover:bg-cyan-50 dark:bg-slate-700 dark:hover:bg-cyan-900/30' }}">
                                    <span class="text-lg font-bold leading-none
                                        {{ $selected ? 'text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                        {{ $nilai }}
                                    </span>
                                    <span class="text-center text-[10px] leading-tight
                                        {{ $selected ? 'text-cyan-100' : 'text-slate-400 dark:text-slate-500' }}">
                                        {{ ['', 'Buruk', 'Kurang', 'Cukup', 'Baik', 'Sangat Baik'][$nilai] }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error("jawaban.$key")
                        <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            {{-- Saran & Ulasan --}}
            <div class="rounded-xl border border-slate-100 bg-white p-6 shadow-[var(--shadow-subtle)] dark:border-slate-700 dark:bg-slate-800">
                <label for="saran" class="block text-sm font-semibold text-slate-800 dark:text-slate-200">
                    Saran & Ulasan
                    <span class="ml-1 font-normal text-slate-400">(opsional)</span>
                </label>
                <p class="mt-0.5 text-xs text-slate-400">Masukan Anda sangat berarti untuk pengembangan instruktur.</p>
                <textarea
                    id="saran"
                    wire:model="saranUlasan"
                    placeholder="Tuliskan saran atau ulasan Anda untuk instruktur..."
                    rows="4"
                    class="mt-3 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 dark:border-slate-600 dark:bg-slate-700/50 dark:text-slate-200 dark:placeholder-slate-500 dark:focus:bg-slate-700"
                ></textarea>
                @error('saranUlasan')
                    <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" wire:navigate
                   class="inline-flex items-center gap-1 text-sm text-slate-500 transition hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                    ← Batal
                </a>
                <button
                    type="submit"
                    class="btn-primary px-6 py-2.5 disabled:cursor-not-allowed disabled:opacity-60"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Kirim Evaluasi</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>

        </form>
    @endif

</div>
