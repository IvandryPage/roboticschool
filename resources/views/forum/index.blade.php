<x-layouts::app :title="'Forum Diskusi'">

    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <flux:heading size="xl" class="font-bold">Forum Diskusi</flux:heading>
            <flux:text class="text-zinc-500 mt-1">Tanya, diskusi, dan berbagi dengan sesama siswa dan instruktur.</flux:text>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    {{-- Form Buat Topik Baru --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-800 flex items-center gap-2">
            <flux:icon name="pencil-square" class="size-4 text-zinc-400" />
            <flux:heading size="sm" class="font-semibold">Buat Diskusi Baru</flux:heading>
        </div>
        <form method="POST" action="{{ route('forum.store') }}" class="px-5 py-4 space-y-3">
            @csrf
            <div>
                <flux:label for="judul">Judul Diskusi <span class="text-red-500">*</span></flux:label>
                <flux:input
                    id="judul"
                    name="judul"
                    type="text"
                    value="{{ old('judul') }}"
                    placeholder="Tuliskan topik diskusimu..."
                    class="mt-1"
                />
                @error('judul')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <flux:label for="konten">Isi Diskusi <span class="text-red-500">*</span></flux:label>
                <flux:textarea
                    id="konten"
                    name="konten"
                    rows="3"
                    placeholder="Jelaskan pertanyaan atau diskusimu secara detail..."
                    class="mt-1"
                >{{ old('konten') }}</flux:textarea>
                @error('konten')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end">
                <flux:button type="submit" variant="primary" icon="paper-airplane" size="sm">
                    Kirim Diskusi
                </flux:button>
            </div>
        </form>
    </div>

    {{-- Daftar Topik --}}
    @if($topiks->isEmpty())
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-12 text-center shadow-sm">
            <flux:icon name="chat-bubble-left-right" class="size-12 text-zinc-200 dark:text-zinc-700 mx-auto mb-4" />
            <flux:heading size="sm" class="font-semibold text-zinc-500">Belum ada diskusi</flux:heading>
            <flux:text class="text-zinc-400 mt-1">Jadilah yang pertama memulai diskusi di kelasmu!</flux:text>
        </div>
    @else
        <div class="space-y-4">
            @foreach($topiks as $topik)
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
                    {{-- Header topik --}}
                    <div class="px-5 py-4">
                        <div class="flex items-start gap-3">
                            {{-- Avatar --}}
                            <div class="size-9 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-sm font-bold text-indigo-600 dark:text-indigo-300 shrink-0">
                                {{ strtoupper(substr($topik->pembuat->nama_lengkap ?? 'U', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="font-semibold text-sm text-zinc-800 dark:text-zinc-100">
                                        {{ $topik->pembuat->nama_lengkap ?? 'User' }}
                                    </span>
                                    @if($topik->pembuat?->role?->nama_role === 'Instruktur')
                                        <flux:badge color="teal" size="sm">Instruktur</flux:badge>
                                    @endif
                                    <span class="text-xs text-zinc-400">· {{ $topik->created_at->diffForHumans() }}</span>
                                </div>
                                <a href="{{ route('forum.show', $topik) }}" class="block">
                                    <div class="font-semibold text-zinc-800 dark:text-zinc-100 hover:text-teal-600 transition-colors">
                                        {{ $topik->judul ?? '(Tanpa Judul)' }}
                                    </div>
                                </a>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-2">
                                    {{ $topik->konten }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Footer: balasan count + form reply --}}
                    <div class="px-5 pb-4 border-t border-zinc-50 dark:border-zinc-800 pt-3">
                        <div class="flex items-center gap-2 mb-3">
                            <flux:icon name="chat-bubble-left" class="size-3.5 text-zinc-400" />
                            <flux:text class="text-xs text-zinc-400">
                                {{ $topik->komentar->count() }} balasan
                            </flux:text>
                        </div>

                        {{-- Komentar --}}
                        @foreach($topik->komentar->take(2) as $komentar)
                            <div class="flex items-start gap-3 mb-2 ml-4">
                                <div class="size-7 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-500 shrink-0">
                                    {{ strtoupper(substr($komentar->user->nama_lengkap ?? 'U', 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <span class="text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                                            {{ $komentar->user->nama_lengkap ?? 'User' }}
                                        </span>
                                        @if($komentar->user?->role?->nama_role === 'Instruktur')
                                            <flux:badge color="teal" size="sm">Instruktur</flux:badge>
                                        @endif
                                    </div>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 line-clamp-2">{{ $komentar->komentar }}</p>
                                </div>
                            </div>
                        @endforeach

                        @if($topik->komentar->count() > 2)
                            <a href="{{ route('forum.show', $topik) }}" class="ml-10 text-xs text-teal-500 hover:underline">
                                Lihat {{ $topik->komentar->count() - 2 }} balasan lainnya →
                            </a>
                        @endif

                        {{-- Form Reply --}}
                        <form method="POST" action="{{ route('forum.reply', $topik) }}" class="flex items-start gap-3 mt-3">
                            @csrf
                            <flux:textarea
                                name="komentar"
                                rows="1"
                                placeholder="Tulis balasan..."
                                class="flex-1 text-sm"
                            />
                            <flux:button type="submit" variant="ghost" size="sm" icon="paper-airplane" class="shrink-0 mt-0.5">
                                Balas
                            </flux:button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</x-layouts::app>
