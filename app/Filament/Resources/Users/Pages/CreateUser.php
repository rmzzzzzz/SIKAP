<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
{
    if (!empty($data['pegawai_id'])) {
        $pegawai = \App\Models\Pegawai::find($data['pegawai_id']);

        $data['name']  = $pegawai->nama;
    }

    return $data;
}

}
