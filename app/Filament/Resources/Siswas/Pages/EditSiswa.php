<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSiswa extends EditRecord
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * Strip out user-owned fields before Eloquent tries to mass-assign
     * them onto the `siswa` table (they don't exist there).
     * We'll save them manually in afterSave().
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Remove fields that belong to the `users` table, not `siswa`
        unset($data['nama_lengkap'], $data['email'], $data['no_hp'], $data['status_aktif']);

        return $data;
    }

    /**
     * Persist user-owned fields to the related User model after the
     * Siswa record itself has been saved.
     */
    protected function afterSave(): void
    {
        $user = $this->record->user;

        if (! $user) {
            return;
        }

        $rawFormData = $this->form->getRawState();

        $userUpdates = array_filter([
            'nama_lengkap' => $rawFormData['nama_lengkap'] ?? null,
            'email'        => $rawFormData['email'] ?? null,
            'no_hp'        => $rawFormData['no_hp'] ?? null,
            'status_aktif' => isset($rawFormData['status_aktif'])
                ? (bool) $rawFormData['status_aktif']
                : null,
        ], fn ($value) => $value !== null);

        if (! empty($userUpdates)) {
            $user->update($userUpdates);
        }
    }
}
