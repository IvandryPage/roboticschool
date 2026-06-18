<?php

namespace App\Filament\Resources\MateriPembelajarans\Schemas;

use Filament\Schemas\Schema; // HARUS INI
use Filament\Forms\Components\TextInput;
// ... (hapus kalau ada "use Filament\Forms\Form;")

class MateriPembelajaranForm
{
    public static function configure(Schema $schema): Schema // HARUS SCHEMA
    {
        return $schema->components([
            // ... komponenmu
        ]);
    }
}