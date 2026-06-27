<x-layouts::app :title="$topik->judul ?? 'Detail Diskusi'">

    {{-- Breadcrumb --}}
    <div class="mb-5 flex items-center gap-2 text-sm text-zinc-400">
        <a href="{{ route('forum.index') }}" class="hover:text-zinc-600 dark:hover:text-zinc-300 transition flex items-center gap-1">
            <flux:icon name="arrow-left" class="size-3.5" />
            Forum Diskusi
        </a>
        <span>/</span>
        <span class="text-zinc-600 dark:text-zinc-300 truncate max-w-xs">{{ $topik->judul ?? 'Detail' }}</span>
    </div>

    {{-- Topik Utama --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden mb-4">
        <div class="px-5 py-4">
            <div class="flex items-start gap-3">
                <div class="size-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-sm font-bold text-indigo-600 dark:text-indigo-300 shrink-0">
                    {{ strtoupper(substr($topik->pembuat->nama_lengkap ?? 'U', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap mb-2">
                        <span class="font-semibold text-sm text-zinc-800 dark:text-zinc-100">
                            {{ $topik->pembuat->nama_lengkap ?? 'User' }}
                        </span>
                        @if($topik->pembuat?->role?->nama_role === 'Instruktur')
                            <flux:badge color="teal" size="sm">Instruktur</flux:badge>
                        @endif
                        <span class="text-xs text-zinc-400">· {{ $topik->created_at->diffForHumans() }}</span>
                    </div>
                    <flux:heading size="lg" class="font-bold mb-3">{{ $topik->judul ?? '(Tanpa Judul)' }}</flux:heading>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed whitespace-pre-line">
                        {{ $topik->konten }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Komentar --}}
    @if($topik->komentar->isNotEmpty())
        <div class="mb-4 space-y-3">
            <div class="flex items-center gap-2 px-1">
                <flux:icon name="chat-bubble-left-right" class="size-4 text-zinc-400" />
                <flux:text class="text-sm font-semibold text-zinc-500">
                    {{ $topik->komentar->count() }} Balasan
                </flux:text>
            </div>
            @foreach($topik->komentar as $komentar)
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm px-5 py-4">
                    <div class="flex items-start gap-3">
                        <div class="size-8 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-500 shrink-0">
                            {{ strtoupper(substr($komentar->user->nama_lengkap ?? 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-sm text-zinc-800 dark:text-zinc-100">
                                    {{ $komentar->user->nama_lengkap ?? 'User' }}
                                </span>
                                @if($komentar->user?->role?->nama_role === 'Instruktur')
                                    <flux:badge color="teal" size="sm">Instruktur</flux:badge>
                                @endif
                                <span class="text-xs text-zinc-400">· {{ $komentar->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed whitespace-pre-line">
                                {{ $komentar->komentar }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-xl px-5 py-6 text-center mb-4">
            <flux:text class="text-zinc-400 text-sm">Belum ada balasan. Jadilah yang pertama!</flux:text>
        </div>
    @endif

    {{-- Form Balas --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-800 flex items-center gap-2">
            <flux:icon name="pencil-square" class="size-4 text-zinc-400" />
            <flux:heading size="sm" class="font-semibold">Tulis Balasan</flux:heading>
        </div>
        <form method="POST" action="{{ route('forum.reply', $topik) }}" class="px-5 py-4 space-y-3">
            @csrf
            <div>
                <flux:label for="komentar">Balasan <span class="text-red-500">*</span></flux:label>
                <flux:textarea
                    id="komentar"
                    name="komentar"
                    rows="3"
                    placeholder="Tulis balasanmu di sini..."
                    class="mt-1"
                />
                @error('komentar')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end">
                <flux:button type="submit" variant="primary" icon="paper-airplane" size="sm">
                    Kirim Balasan
                </flux:button>
            </div>
        </form>
    </div>

</x-layouts::app>
