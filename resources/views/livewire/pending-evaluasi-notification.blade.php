<div>
    @if ($pendingKelas->isNotEmpty())
        <div class="flex flex-col gap-2">
            @foreach ($pendingKelas as $kelas)
                <div class="card-compact flex items-center justify-between gap-4 px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full"
                             style="background-color: var(--color-brand)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                Evaluasi instruktur belum diisi
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $kelas->nama_kelas }} &mdash; {{ $kelas->instruktur->nama_lengkap ?? $kelas->instruktur->name }}
                            </p>
                        </div>
                    </div>
                    <a
                        href="{{ route('evaluasi.instruktur', $kelas->id) }}"
                        wire:navigate
                        class="btn-primary shrink-0 px-4 py-1.5 text-xs"
                    >
                        Isi Sekarang →
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
