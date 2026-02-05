<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['pegawai_id'])) {
            $pegawai = \App\Models\Pegawai::find($data['pegawai_id']);

            $data['name']   = $pegawai->nama;
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus User'),
        ];
    }
}
