<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        @if(auth()->user()?->role?->nama_role === 'Instruktur')
            @livewire('instructor-dashboard')
        @elseif(auth()->user()?->role?->nama_role === 'Siswa')
            @livewire('student-dashboard')
        @else
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h2 class="text-xl font-bold">Selamat Datang di Dashboard!</h2>
                <p class="text-gray-600 mt-2">Anda login sebagai {{ auth()->user()?->role?->nama_role ?? 'User' }}.</p>
            </div>
        @endif
    </div>
</x-layouts::app>
