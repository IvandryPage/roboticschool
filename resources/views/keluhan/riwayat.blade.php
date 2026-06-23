<x-layouts::app :title="__('Riwayat Keluhan Saya')">
    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Keluhan Saya</h1>
                <p class="text-[15px] text-slate-500 mt-2">Daftar tiket keluhan yang pernah Anda buat dan status penyelesaiannya.</p>
            </div>
            <a href="{{ route('keluhan.create') }}" 
               class="inline-flex items-center justify-center bg-[#06B6D4] hover:bg-[#0891B2] text-white font-bold py-2.5 px-5 rounded-xl transition-all shadow-sm hover:shadow-md text-[14px]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Buat Keluhan Baru
            </a>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Subjek & Kategori</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Prioritas</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($tiketKeluhans as $tiket)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <!-- Tanggal -->
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-[14px] font-semibold text-slate-900">{{ $tiket->created_at->format('d M Y') }}</div>
                                    <div class="text-[13px] text-slate-500 mt-0.5">{{ $tiket->created_at->format('H:i') }} WIB</div>
                                </td>
                                
                                <!-- Subjek & Kategori -->
                                <td class="px-6 py-5">
                                    <div class="text-[15px] font-semibold text-slate-900 max-w-sm truncate" title="{{ $tiket->subjek }}">
                                        {{ $tiket->subjek }}
                                    </div>
                                    <div class="mt-1.5 flex items-center gap-2">
                                        <!-- Kategori Icon/Badge -->
                                        @if($tiket->kategori == 'Pembelajaran')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-cyan-50 text-cyan-700 border border-cyan-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                                {{ $tiket->kategori }}
                                            </span>
                                        @elseif($tiket->kategori == 'Error Sistem')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                {{ $tiket->kategori }}
                                            </span>
                                        @elseif($tiket->kategori == 'Pendaftaran & Pembayaran')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-orange-50 text-orange-700 border border-orange-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                                {{ $tiket->kategori }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                                {{ $tiket->kategori }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Prioritas -->
                                <td class="px-6 py-5 whitespace-nowrap">
                                    @if($tiket->prioritas == 'Tinggi')
                                        <div class="flex items-center gap-2 text-[14px] font-semibold text-[#EF4444]">
                                            <div class="w-2 h-2 rounded-full bg-[#EF4444]"></div>
                                            Tinggi
                                        </div>
                                    @elseif($tiket->prioritas == 'Sedang')
                                        <div class="flex items-center gap-2 text-[14px] font-semibold text-[#F97316]">
                                            <div class="w-2 h-2 rounded-full bg-[#F97316]"></div>
                                            Sedang
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2 text-[14px] font-semibold text-[#10B981]">
                                            <div class="w-2 h-2 rounded-full bg-[#10B981]"></div>
                                            Rendah
                                        </div>
                                    @endif
                                </td>
                                
                                <!-- Status -->
                                <td class="px-6 py-5 whitespace-nowrap">
                                    @if($tiket->status == 'Open')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#06B6D4]/10 text-[#06B6D4] border border-[#06B6D4]/20">
                                            Open
                                        </span>
                                    @elseif($tiket->status == 'In Progress')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#F97316]/10 text-[#F97316] border border-[#F97316]/20">
                                            In Progress
                                        </span>
                                    @elseif($tiket->status == 'Resolved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20">
                                            Resolved
                                        </span>
                                    @elseif($tiket->status == 'Closed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#0F172A]/10 text-[#0F172A] border border-[#0F172A]/20">
                                            Closed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $tiket->status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-slate-50 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-[15px] font-medium text-slate-900 mb-1">Belum ada riwayat keluhan</p>
                                    <p class="text-sm text-slate-500">Anda belum pernah membuat tiket keluhan sebelumnya.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
