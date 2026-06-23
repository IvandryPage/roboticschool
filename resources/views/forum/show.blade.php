<x-layouts::app :title="$topik->judul">

    <div class="max-w-4xl mx-auto space-y-4">

        <a href="{{ route('forum.index') }}" class="text-blue-600">
            ← Kembali ke Forum
        </a>

        <div class="bg-white rounded-xl p-6 shadow">

            <h1 class="text-2xl font-bold">
                {{ $topik->judul }}
            </h1>

            <p class="text-sm text-gray-500 mt-2">
                Oleh {{ $topik->pembuat->nama_lengkap }}
            </p>

            <div class="mt-4">
                {{ $topik->konten }}
            </div>

        </div>

        <div class="bg-white rounded-xl p-6 shadow">

            <div class="flex gap-3">

                <div class="w-10 h-10 rounded-full bg-cyan-500 text-white flex items-center justify-center font-bold">
                    {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 1)) }}
                </div>

                <div class="flex-1">

                    <textarea class="w-full border rounded-lg p-3" rows="3"
                        placeholder="Tulis pertanyaan atau diskusi..."></textarea>

                    <div class="flex justify-end mt-3">
                        <button class="bg-cyan-500 text-white px-5 py-2 rounded-lg" disabled>
                            Kirim
                        </button>
                    </div>

                </div>

            </div>

        </div>

        @foreach($topik->komentar as $komentar)

            <div class="bg-white rounded-xl p-5 shadow">

                <div class="flex gap-3">

                    <div class="w-10 h-10 rounded-full bg-indigo-500 text-white flex items-center justify-center font-bold">
                        {{ strtoupper(substr($komentar->user->nama_lengkap ?? 'U', 0, 1)) }}
                    </div>

                    <div class="flex-1">

                        <div class="font-semibold">
                            {{ $komentar->user->nama_lengkap }}
                        </div>

                        <div class="text-sm text-gray-500">
                            Balasan Diskusi
                        </div>

                        <div class="mt-2">
                            {{ $komentar->komentar }}
                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</x-layouts::app>