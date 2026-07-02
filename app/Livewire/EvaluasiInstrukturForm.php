<?php

namespace App\Livewire;

use App\Models\EnrollmentKelas;
use App\Models\EvaluasiInstruktur;
use App\Models\Kelas;
use Livewire\Component;

class EvaluasiInstrukturForm extends Component
{
    public Kelas $kelas;

    public bool $sudahDiisi = false;

    public array $jawaban = [];

    public string $saranUlasan = '';

    public function mount(Kelas $kelas): void
    {
        $siswa = auth()->user()->siswa;

        if (! $siswa) {
            abort(403);
        }

        if (strtolower($kelas->status) !== 'selesai') {
            abort(403);
        }

        $terdaftar = EnrollmentKelas::where('kelas_id', $kelas->id)
            ->where('siswa_id', $siswa->id)
            ->exists();

        if (! $terdaftar) {
            abort(403);
        }

        $this->kelas = $kelas->load('instruktur');

        $this->sudahDiisi = EvaluasiInstruktur::where('kelas_id', $kelas->id)
            ->where('siswa_id', $siswa->id)
            ->exists();

        foreach (array_keys($this->pertanyaan()) as $key) {
            $this->jawaban[$key] = null;
        }
    }

    public function pertanyaan(): array
    {
        return [
            'kejelasan_materi'      => 'Instruktur menjelaskan materi dengan jelas dan mudah dipahami',
            'responsif'             => 'Instruktur responsif dan sabar dalam menjawab pertanyaan siswa',
            'motivasi'              => 'Instruktur mampu memotivasi dan membangkitkan semangat belajar',
            'kedisiplinan'          => 'Instruktur disiplin dan tepat waktu dalam mengajar',
            'penilaian_keseluruhan' => 'Penilaian keseluruhan terhadap instruktur',
        ];
    }

    public function totalTerjawab(): int
    {
        return collect($this->jawaban)->filter(fn ($v) => ! is_null($v))->count();
    }

    protected function rules(): array
    {
        $rules = ['saranUlasan' => 'nullable|string|max:1000'];

        foreach (array_keys($this->pertanyaan()) as $key) {
            $rules["jawaban.$key"] = 'required|integer|min:1|max:5';
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'jawaban.*.required' => 'Semua pertanyaan wajib dijawab.',
            'jawaban.*.integer'  => 'Nilai harus berupa angka.',
            'jawaban.*.min'      => 'Nilai minimal adalah 1.',
            'jawaban.*.max'      => 'Nilai maksimal adalah 5.',
        ];
    }

    public function submit(): void
    {
        if ($this->sudahDiisi) {
            return;
        }

        $this->validate();

        $siswa = auth()->user()->siswa;

        $skorRataRata = collect($this->jawaban)
            ->map(fn ($v) => (int) $v)
            ->average();

        EvaluasiInstruktur::create([
            'kelas_id'          => $this->kelas->id,
            'siswa_id'          => $siswa->id,
            'instruktur_id'     => $this->kelas->instruktur_id,
            'jawaban_kuesioner' => json_encode($this->jawaban),
            'skor_rata_rata'    => round($skorRataRata, 2),
            'saran_ulasan'      => $this->saranUlasan ?: null,
        ]);

        $this->sudahDiisi = true;
    }

    public function render()
    {
        return view('livewire.evaluasi-instruktur-form', [
            'pertanyaan'      => $this->pertanyaan(),
            'totalPertanyaan' => count($this->pertanyaan()),
            'totalTerjawab'   => $this->totalTerjawab(),
        ])->layout('layouts.app', ['title' => 'Evaluasi Instruktur']);
    }
}
