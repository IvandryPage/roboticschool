<x-layouts::app :title="'Evaluasi Instruktur'">

    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <flux:heading size="xl" class="font-bold">Evaluasi Instruktur</flux:heading>
            <flux:text class="mt-1 text-zinc-500">
                Berikan penilaian jujurmu untuk meningkatkan kualitas pengajaran.
            </flux:text>
        </div>
        <a href="{{ route('siswa.dashboard') }}">
            <flux:button variant="ghost" icon="arrow-left" size="sm">Kembali</flux:button>
        </a>
    </div>

    <livewire:evaluasi-instruktur-form :kelas="$kelas" />

</x-layouts::app>
