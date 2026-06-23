<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    protected static ?string $navigationLabel = 'Manajemen Akun';

    protected static ?string $modelLabel = 'Akun Pengguna';

    protected static ?string $pluralModelLabel = 'Akun Pengguna';

    protected static string|\UnitEnum|null $navigationGroup = 'Administrasi Sistem';

    /** Hanya Admin Akademik yang bisa mengakses manajemen akun */
    public static function canViewAny(): bool
    {
        return Auth::user()?->role?->nama_role === 'Admin Akademik';
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->role?->nama_role === 'Admin Akademik';
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->role?->nama_role === 'Admin Akademik';
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->role?->nama_role === 'Admin Akademik';
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit'   => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Manajemen Akun';
    }
}

