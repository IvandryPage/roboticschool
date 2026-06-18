@extends('layouts.app') @section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Daftar Materi Pembelajaran</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($materi as $item)
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-blue-600 mb-2">{{ $item->judul }}</h2>
                    
                    <p class="text-gray-600 text-sm mb-4">
                        {{ $item->keterangan ?? 'Tidak ada keterangan singkat.' }}
                    </p>
                </div>

                <div class="mt-4">
                    @if($item->file_path)
                        <a href="{{ asset('storage/' . $item->file_path) }}" download class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium text-sm px-4 py-2 rounded transition-colors w-full text-center">
                            📁 Unduh Materi
                        </a>
                    @elseif($item->tautan_link)
                        <a href="{{ $item->tautan_link }}" target="_blank" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2 rounded transition-colors w-full text-center">
                            🌐 Buka Tautan
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection