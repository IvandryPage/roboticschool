<x-layouts::app :title="'Forum Diskusi'">

    <div class="max-w-3xl mx-auto">

        {{-- Header Sesi --}}
        <div class="bg-white rounded-xl p-6 shadow mb-4">

            <span class="text-xs font-semibold text-cyan-600 uppercase">
                Arduino Basic
            </span>

            <h1 class="text-2xl font-bold mt-1">
                Sesi 5 : PWM & Kontrol Servo
            </h1>

        </div>

        {{-- Placeholder Materi --}}
        <div class="bg-white rounded-xl shadow mb-4 h-64 flex items-center justify-center">

            <div class="text-center">
                <div class="text-lg font-semibold">
                    Area Materi / Video
                </div>

                <div class="text-gray-500 text-sm">
                    Menunggu integrasi fitur materi
                </div>
            </div>

        </div>

        {{-- Tab --}}
        <div class="bg-white rounded-xl shadow mb-6 px-6">

            <div class="flex gap-8">

                <button type="button" class="py-4 text-gray-500">
                    Materi
                </button>

                <button type="button" class="py-4 text-gray-500">
                    Tugas
                </button>

                <button type="button" class="py-4 border-b-2 border-cyan-500 text-cyan-500 font-semibold">
                    Diskusi
                </button>

            </div>

        </div>

        {{-- Form Diskusi --}}
        <form method="POST" action="{{ route('forum.store') }}" class="bg-white rounded-xl p-5 shadow mb-6">
            @csrf

            <textarea name="konten" class="w-full border rounded-lg p-3" rows="3"
                placeholder="Tulis pertanyaan atau diskusi..."></textarea>

            <div class="flex justify-end mt-3">
                <button type="submit" class="px-5 py-2 bg-cyan-500 text-white rounded-lg">
                    Kirim
                </button>
            </div>
        </form>

        {{-- List Diskusi --}}
        @foreach ($topiks as $topik)

            <div class="bg-white rounded-lg p-4 shadow mb-3">

                <div class="flex items-center gap-3 mb-3">

                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold">
                        {{ strtoupper(substr($topik->pembuat->nama_lengkap ?? 'U', 0, 1)) }}
                    </div>

                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-semibold">
                                {{ $topik->pembuat->nama_lengkap ?? 'User' }}
                            </h3>
                            @if ($topik->pembuat?->role?->nama_role === 'Instruktur')
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-cyan-100 text-cyan-700 uppercase tracking-wide">
                                    Instruktur
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">
                            {{ $topik->created_at->diffForHumans() }}
                        </p>
                    </div>

                </div>

                <h2 class="font-semibold mb-2">
                    {{ $topik->judul }}
                </h2>

                <p class="text-gray-700">
                    {{ $topik->konten }}
                </p>

                <div class="mt-3 text-sm text-cyan-600">
                    {{ $topik->komentar->count() }} balasan
                    @foreach ($topik->komentar as $komentar)

                        <div class="mt-2 ml-4 border-l-2 pl-3 text-sm">

                            <div class="flex items-center gap-2">
                                <span class="font-semibold">
                                    {{ $komentar->user->nama_lengkap ?? 'User' }}
                                </span>
                                @if ($komentar->user?->role?->nama_role === 'Instruktur')
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-cyan-100 text-cyan-700 uppercase tracking-wide">
                                        Instruktur
                                    </span>
                                @endif
                            </div>

                            <div class="text-gray-700 mt-0.5">
                                {{ $komentar->komentar }}
                            </div>

                        </div>

                    @endforeach
                </div>
                <form method="POST" action="{{ route('forum.reply', $topik) }}" class="mt-3">
                    @csrf

                    <textarea name="komentar" rows="2" class="w-full border rounded-lg p-2"
                        placeholder="Tulis balasan..."></textarea>

                    <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg">
                        Balas
                    </button>
                </form>
            </div>

        @endforeach

    </div>

</x-layouts::app>