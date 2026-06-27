<x-layouts::app :title="'Kirim Keluhan'">

    <div class="mb-6">
        <flux:heading size="xl" class="font-bold">Kirim Keluhan</flux:heading>
        <flux:text class="text-zinc-500 mt-1">Sampaikan kendala atau masukanmu, tim kami akan menindaklanjuti dalam 1×24 jam.</flux:text>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-zinc-100 dark:border-zinc-800 flex items-center gap-2">
                <flux:icon name="exclamation-triangle" class="size-4 text-zinc-400" />
                <flux:heading size="sm" class="font-semibold">Form Keluhan</flux:heading>
            </div>

            <form action="{{ route('keluhan.store') }}" method="POST" class="px-5 py-5 space-y-5">
                @csrf

                {{-- ── Jenis Keluhan ── --}}
                {{--
                    Alpine.js dipakai karena peer-checked: Tailwind tidak jalan
                    ketika class di-purge saat build (class tidak ada di HTML statis).
                    Dengan x-data, toggle class dilakukan di runtime → aman.
                --}}
                <div x-data="{ selected: '{{ old('kategori', '') }}' }">
                    <flux:label class="mb-2 block">Jenis Keluhan <span class="text-red-500">*</span></flux:label>

                    {{-- Hidden input untuk submit form --}}
                    <input type="hidden" name="kategori" :value="selected">

                    <div class="grid grid-cols-2 gap-3">

                        {{-- ── Pembelajaran (TEAL) ── --}}
                        <button type="button"
                                @click="selected = 'Pembelajaran'"
                                class="text-left rounded-xl border-2 p-4 transition-all focus:outline-none"
                                :class="selected === 'Pembelajaran'
                                    ? 'border-teal-500 bg-teal-50 dark:bg-teal-900/20 ring-1 ring-teal-400/30'
                                    : 'border-zinc-100 dark:border-zinc-700 hover:border-teal-200 dark:hover:border-teal-700 bg-white dark:bg-zinc-800'">
                            <div class="flex items-start gap-3">
                                <div class="size-9 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                                     :class="selected === 'Pembelajaran' ? 'bg-teal-100 dark:bg-teal-900/40' : 'bg-zinc-100 dark:bg-zinc-700'">
                                    <svg class="size-5 transition-colors"
                                         :class="selected === 'Pembelajaran' ? 'text-teal-600' : 'text-zinc-400'"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold"
                                         :class="selected === 'Pembelajaran' ? 'text-teal-700 dark:text-teal-300' : 'text-zinc-800 dark:text-zinc-100'">
                                        Pembelajaran
                                    </div>
                                    <div class="text-xs text-zinc-400 mt-0.5">Materi, kelas, atau konsep</div>
                                </div>
                            </div>
                            {{-- Checkmark --}}
                            <div class="mt-2 flex justify-end">
                                <div class="size-4 rounded-full border-2 transition-all flex items-center justify-center"
                                     :class="selected === 'Pembelajaran' ? 'border-teal-500 bg-teal-500' : 'border-zinc-200 dark:border-zinc-600'">
                                    <svg x-show="selected === 'Pembelajaran'" class="size-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </button>

                        {{-- ── Error Sistem (RED) ── --}}
                        <button type="button"
                                @click="selected = 'Error Sistem'"
                                class="text-left rounded-xl border-2 p-4 transition-all focus:outline-none"
                                :class="selected === 'Error Sistem'
                                    ? 'border-red-500 bg-red-50 dark:bg-red-900/20 ring-1 ring-red-400/30'
                                    : 'border-zinc-100 dark:border-zinc-700 hover:border-red-200 dark:hover:border-red-700 bg-white dark:bg-zinc-800'">
                            <div class="flex items-start gap-3">
                                <div class="size-9 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                                     :class="selected === 'Error Sistem' ? 'bg-red-100 dark:bg-red-900/40' : 'bg-zinc-100 dark:bg-zinc-700'">
                                    <svg class="size-5 transition-colors"
                                         :class="selected === 'Error Sistem' ? 'text-red-600' : 'text-zinc-400'"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold"
                                         :class="selected === 'Error Sistem' ? 'text-red-700 dark:text-red-300' : 'text-zinc-800 dark:text-zinc-100'">
                                        Error Sistem
                                    </div>
                                    <div class="text-xs text-zinc-400 mt-0.5">Bug, gangguan, halaman error</div>
                                </div>
                            </div>
                            <div class="mt-2 flex justify-end">
                                <div class="size-4 rounded-full border-2 transition-all flex items-center justify-center"
                                     :class="selected === 'Error Sistem' ? 'border-red-500 bg-red-500' : 'border-zinc-200 dark:border-zinc-600'">
                                    <svg x-show="selected === 'Error Sistem'" class="size-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </button>

                        {{-- ── Pendaftaran & Pembayaran (AMBER) ── --}}
                        <button type="button"
                                @click="selected = 'Pendaftaran & Pembayaran'"
                                class="text-left rounded-xl border-2 p-4 transition-all focus:outline-none"
                                :class="selected === 'Pendaftaran & Pembayaran'
                                    ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20 ring-1 ring-amber-400/30'
                                    : 'border-zinc-100 dark:border-zinc-700 hover:border-amber-200 dark:hover:border-amber-700 bg-white dark:bg-zinc-800'">
                            <div class="flex items-start gap-3">
                                <div class="size-9 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                                     :class="selected === 'Pendaftaran & Pembayaran' ? 'bg-amber-100 dark:bg-amber-900/40' : 'bg-zinc-100 dark:bg-zinc-700'">
                                    <svg class="size-5 transition-colors"
                                         :class="selected === 'Pendaftaran & Pembayaran' ? 'text-amber-600' : 'text-zinc-400'"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold"
                                         :class="selected === 'Pendaftaran & Pembayaran' ? 'text-amber-700 dark:text-amber-300' : 'text-zinc-800 dark:text-zinc-100'">
                                        Pendaftaran & Pembayaran
                                    </div>
                                    <div class="text-xs text-zinc-400 mt-0.5">Enroll, transaksi, invoice</div>
                                </div>
                            </div>
                            <div class="mt-2 flex justify-end">
                                <div class="size-4 rounded-full border-2 transition-all flex items-center justify-center"
                                     :class="selected === 'Pendaftaran & Pembayaran' ? 'border-amber-500 bg-amber-500' : 'border-zinc-200 dark:border-zinc-600'">
                                    <svg x-show="selected === 'Pendaftaran & Pembayaran'" class="size-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </button>

                        {{-- ── Hal Lainnya (GREEN) ── --}}
                        <button type="button"
                                @click="selected = 'Hal Lainnya'"
                                class="text-left rounded-xl border-2 p-4 transition-all focus:outline-none"
                                :class="selected === 'Hal Lainnya'
                                    ? 'border-green-500 bg-green-50 dark:bg-green-900/20 ring-1 ring-green-400/30'
                                    : 'border-zinc-100 dark:border-zinc-700 hover:border-green-200 dark:hover:border-green-700 bg-white dark:bg-zinc-800'">
                            <div class="flex items-start gap-3">
                                <div class="size-9 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                                     :class="selected === 'Hal Lainnya' ? 'bg-green-100 dark:bg-green-900/40' : 'bg-zinc-100 dark:bg-zinc-700'">
                                    <svg class="size-5 transition-colors"
                                         :class="selected === 'Hal Lainnya' ? 'text-green-600' : 'text-zinc-400'"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold"
                                         :class="selected === 'Hal Lainnya' ? 'text-green-700 dark:text-green-300' : 'text-zinc-800 dark:text-zinc-100'">
                                        Hal Lainnya
                                    </div>
                                    <div class="text-xs text-zinc-400 mt-0.5">Pertanyaan umum lainnya</div>
                                </div>
                            </div>
                            <div class="mt-2 flex justify-end">
                                <div class="size-4 rounded-full border-2 transition-all flex items-center justify-center"
                                     :class="selected === 'Hal Lainnya' ? 'border-green-500 bg-green-500' : 'border-zinc-200 dark:border-zinc-600'">
                                    <svg x-show="selected === 'Hal Lainnya'" class="size-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </button>

                    </div>

                    {{-- Validasi: tampil jika form disubmit tanpa pilih kategori --}}
                    @error('kategori')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror

                    {{-- Warning jika belum pilih dan coba submit --}}
                    <div x-show="selected === ''" class="hidden">
                        {{-- handled by required on hidden input via :value --}}
                    </div>
                </div>

                {{-- ── Subjek ── --}}
                <div>
                    <flux:label for="subjek">Subjek <span class="text-red-500">*</span></flux:label>
                    <flux:input
                        id="subjek"
                        name="subjek"
                        type="text"
                        value="{{ old('subjek') }}"
                        placeholder="Contoh: Tidak bisa memutar video materi"
                        class="mt-1 {{ $errors->has('subjek') ? 'border-red-400' : '' }}"
                    />
                    @error('subjek')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ── Detail Keluhan ── --}}
                <div>
                    <flux:label for="deskripsi">Detail Keluhan <span class="text-red-500">*</span></flux:label>
                    <flux:textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="5"
                        placeholder="Jelaskan kendala secara lengkap — termasuk langkah yang sudah dicoba..."
                        class="mt-1 {{ $errors->has('deskripsi') ? 'border-red-400' : '' }}"
                    />
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info --}}
                <div class="bg-zinc-50 dark:bg-zinc-800 border border-zinc-100 dark:border-zinc-700 rounded-lg px-4 py-3 flex items-start gap-2 text-xs text-zinc-500">
                    <flux:icon name="information-circle" class="size-4 text-zinc-400 shrink-0 mt-0.5" />
                    <span>Keluhanmu akan ditinjau oleh tim <strong class="text-zinc-700 dark:text-zinc-300">RoboNesia Academy</strong> dalam 1×24 jam.</span>
                </div>

                <div class="flex justify-end gap-3 pt-1">
                    <flux:button href="{{ route('keluhan.saya') }}" variant="ghost">
                        Riwayat Keluhan
                    </flux:button>
                    <flux:button type="submit" variant="primary" icon="paper-airplane">
                        Kirim Keluhan
                    </flux:button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Sukses --}}
    @if(session('success_modal'))
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-zinc-900/60 backdrop-blur-sm"></div>
            <div class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-zinc-200 dark:border-zinc-700 p-8 max-w-sm w-full text-center">
                <div class="mx-auto size-16 rounded-full bg-green-50 dark:bg-green-900/30 flex items-center justify-center mb-5">
                    <flux:icon name="check-circle" class="size-8 text-green-500" />
                </div>
                <flux:heading size="lg" class="font-bold mb-2">Keluhan Terkirim!</flux:heading>
                <flux:text class="text-zinc-500 mb-6">
                    Tim RoboNesia Academy akan meninjau dan menghubungimu dalam 1×24 jam.
                </flux:text>
                <flux:button href="{{ route('keluhan.saya') }}" variant="primary" class="w-full">
                    Lihat Riwayat Keluhan
                </flux:button>
            </div>
        </div>
    @endif

</x-layouts::app>
