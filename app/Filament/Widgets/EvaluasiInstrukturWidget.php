<?php

namespace App\Filament\Widgets;

use App\Models\EvaluasiInstruktur;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class EvaluasiInstrukturWidget extends BaseWidget
{
    protected static ?string $heading = 'Nilai Rata-rata Evaluasi Instruktur';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->whereHas('evaluasiInstruktur')
                    ->withCount('evaluasiInstruktur as jumlah_evaluasi')
                    ->withAvg('evaluasiInstruktur as skor_rata_rata', 'skor_rata_rata')
            )
            ->columns([
                TextColumn::make('nama_lengkap')
                    ->label('Instruktur')
                    ->searchable(),

                TextColumn::make('jumlah_evaluasi')
                    ->label('Jumlah Evaluasi')
                    ->alignCenter(),

                TextColumn::make('skor_rata_rata')
                    ->label('Skor Rata-rata')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) . ' / 5.00' : '-')
                    ->alignCenter(),
            ])
            ->paginated(false);
    }
}
