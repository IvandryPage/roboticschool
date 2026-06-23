<x-layouts::app :title="__('Kirim Keluhan')">

    <div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 font-sans">
        
        <!-- Container Utama (max-width: 1200px) -->
        <div class="max-w-6xl mx-auto">
            
            <!-- Main Card Wrapper -->
            <div class="bg-white rounded-3xl shadow-lg border border-slate-100 p-8 md:p-12">
                
                <!-- Heading -->
                <div class="mb-12">
                    <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-3">
                        Kirim Keluhan
                    </h1>
                    <p class="text-lg text-slate-500">
                        Sampaikan kendala atau masukanmu, tim kami akan menindaklanjuti.
                    </p>
                </div>

                <form action="{{ route('keluhan.store') }}" method="POST">
                    @csrf

                    <!-- Jenis Keluhan -->
                    <div class="mb-12">
                        <label class="block text-lg font-bold text-slate-900 mb-6">
                            Pilih Jenis Keluhan <span class="text-red-500">*</span>
                        </label>

                        <!-- Grid 4 Kolom -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            
                            <!-- Card: Pembelajaran -->
                            <label class="relative cursor-pointer group h-56">
                                <input type="radio" name="kategori" value="Pembelajaran" class="peer sr-only" required {{ old('kategori') == 'Pembelajaran' ? 'checked' : '' }}>
                                <div class="h-full rounded-2xl border-2 border-slate-100 bg-white p-8 transition duration-300 hover:-translate-y-1 hover:shadow-lg peer-checked:border-cyan-500 peer-checked:bg-cyan-50 peer-checked:shadow-md peer-checked:ring-2 peer-checked:ring-cyan-200 flex flex-col items-center justify-center text-center relative overflow-hidden">
                                    
                                    <!-- Checkmark -->
                                    <div class="absolute top-4 right-4 hidden peer-checked:block">
                                        <div class="bg-cyan-500 rounded-full p-1 text-white shadow-sm">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Icon -->
                                    <div class="mb-4 text-cyan-500 transition transform group-hover:scale-110">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>

                                    <h3 class="font-bold text-slate-900 text-base mb-2">Pembelajaran</h3>
                                    <p class="text-sm text-slate-500">Materi, kelas, atau pemahaman konsep</p>
                                </div>
                            </label>

                            <!-- Card: Error Sistem -->
                            <label class="relative cursor-pointer group h-56">
                                <input type="radio" name="kategori" value="Error Sistem" class="peer sr-only" {{ old('kategori') == 'Error Sistem' ? 'checked' : '' }}>
                                <div class="h-full rounded-2xl border-2 border-slate-100 bg-white p-8 transition duration-300 hover:-translate-y-1 hover:shadow-lg peer-checked:border-cyan-500 peer-checked:bg-cyan-50 peer-checked:shadow-md peer-checked:ring-2 peer-checked:ring-cyan-200 flex flex-col items-center justify-center text-center relative overflow-hidden">
                                    
                                    <div class="absolute top-4 right-4 hidden peer-checked:block">
                                        <div class="bg-cyan-500 rounded-full p-1 text-white shadow-sm">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Warna Pink untuk Error -->
                                    <div class="mb-4 text-pink-500 transition transform group-hover:scale-110">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>

                                    <h3 class="font-bold text-slate-900 text-base mb-2">Error Sistem / Platform</h3>
                                    <p class="text-sm text-slate-500">Bug, gangguan, atau halaman tidak berfungsi</p>
                                </div>
                            </label>

                            <!-- Card: Pendaftaran & Pembayaran -->
                            <label class="relative cursor-pointer group h-56">
                                <input type="radio" name="kategori" value="Pendaftaran & Pembayaran" class="peer sr-only" {{ old('kategori') == 'Pendaftaran & Pembayaran' ? 'checked' : '' }}>
                                <div class="h-full rounded-2xl border-2 border-slate-100 bg-white p-8 transition duration-300 hover:-translate-y-1 hover:shadow-lg peer-checked:border-cyan-500 peer-checked:bg-cyan-50 peer-checked:shadow-md peer-checked:ring-2 peer-checked:ring-cyan-200 flex flex-col items-center justify-center text-center relative overflow-hidden">
                                    
                                    <div class="absolute top-4 right-4 hidden peer-checked:block">
                                        <div class="bg-cyan-500 rounded-full p-1 text-white shadow-sm">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Warna Amber/Orange untuk Pembayaran -->
                                    <div class="mb-4 text-amber-500 transition transform group-hover:scale-110">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>

                                    <h3 class="font-bold text-slate-900 text-base mb-2">Pendaftaran & Pembayaran</h3>
                                    <p class="text-sm text-slate-500">Kendala enroll, transaksi, atau invoice</p>
                                </div>
                            </label>

                            <!-- Card: Hal Lainnya -->
                            <label class="relative cursor-pointer group h-56">
                                <input type="radio" name="kategori" value="Hal Lainnya" class="peer sr-only" {{ old('kategori') == 'Hal Lainnya' ? 'checked' : '' }}>
                                <div class="h-full rounded-2xl border-2 border-slate-100 bg-white p-8 transition duration-300 hover:-translate-y-1 hover:shadow-lg peer-checked:border-cyan-500 peer-checked:bg-cyan-50 peer-checked:shadow-md peer-checked:ring-2 peer-checked:ring-cyan-200 flex flex-col items-center justify-center text-center relative overflow-hidden">
                                    
                                    <div class="absolute top-4 right-4 hidden peer-checked:block">
                                        <div class="bg-cyan-500 rounded-full p-1 text-white shadow-sm">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Warna Emerald untuk Lainnya -->
                                    <div class="mb-4 text-emerald-500 transition transform group-hover:scale-110">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </div>

                                    <h3 class="font-bold text-slate-900 text-base mb-2">Hal Lainnya</h3>
                                    <p class="text-sm text-slate-500">Pertanyaan umum atau masukan lainnya</p>
                                </div>
                            </label>
                        </div>

                        @error('kategori')
                            <p class="text-red-500 text-sm mt-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Subjek -->
                    <div class="mb-8">
                        <label class="block text-lg font-bold text-slate-900 mb-3">
                            Subjek (Wajib) <span class="text-red-500">*</span>
                        </label>
                        
                        <input type="text" name="subjek" value="{{ old('subjek') }}" required
                               class="w-full rounded-2xl border-2 border-slate-100 bg-slate-50 px-6 py-4 text-slate-900 text-lg placeholder-slate-400 transition focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-cyan-100 hover:border-slate-300"
                               placeholder="Contoh: Tidak bisa memutar video materi">
                        
                        @error('subjek')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Detail Keluhan -->
                    <div class="mb-12">
                        <label class="block text-lg font-bold text-slate-900 mb-3">
                            Detail Keluhan <span class="text-red-500">*</span>
                        </label>
                        
                        <textarea name="deskripsi" rows="8" required
                                  class="w-full rounded-2xl border-2 border-slate-100 bg-slate-50 px-6 py-4 text-slate-900 text-lg placeholder-slate-400 transition focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-cyan-100 hover:border-slate-300 resize-none"
                                  placeholder="Jelaskan kendala secara lengkap di sini..."></textarea>

                        @error('deskripsi')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Section -->
                    <div>
                        <!-- Tombol Kirim -->
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white font-bold h-16 rounded-2xl transition duration-300 shadow-xl shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:-translate-y-1 flex items-center justify-center gap-3 text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform -rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim Keluhan
                        </button>
                        
                        <!-- Info Box Bawah -->
                        <div class="mt-8 flex items-center gap-4 bg-slate-50 rounded-2xl p-6 border border-slate-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-base text-slate-600">
                                Keluhanmu akan ditinjau oleh tim <span class="font-bold text-slate-900">RoboNesia Academy</span> dalam 1x24 jam.
                            </p>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Modal Success -->
    @if(session('success_modal'))
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        
        <!-- Backdrop Blur -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity"></div>
        
        <!-- Modal Card -->
        <div class="relative bg-white rounded-3xl shadow-2xl p-10 max-w-lg w-full text-center border border-slate-100">
            
            <!-- Icon Success Circle -->
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-emerald-50 mb-8">
                <svg class="h-12 w-12 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h2 class="text-3xl font-extrabold text-slate-900 mb-4 tracking-tight">Keluhan Terkirim!</h2>
            
            <p class="text-slate-500 mb-10 text-lg leading-relaxed">
                Terima kasih atas laporanmu. Tim RoboNesia Academy akan meninjau dan menghubungimu melalui email dalam 1×24 jam.
            </p>
            
            <a href="{{ route('keluhan.saya') }}" 
               class="block w-full bg-cyan-500 hover:bg-cyan-600 text-white font-bold h-14 rounded-2xl flex items-center justify-center transition duration-300 shadow-lg shadow-cyan-500/30 text-lg">
                Selesai
            </a>
        </div>
    </div>
    @endif

</x-layouts::app>