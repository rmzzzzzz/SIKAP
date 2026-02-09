<?php

namespace App\Filament\Resources\Pegawais\Pages;

use App\Filament\Resources\Pegawais\PegawaiResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreatePegawai extends CreateRecord
{
    protected static string $resource = PegawaiResource::class;
    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label('Simpan')
                ->submit('create'),

            Actions\Action::make('cancel')
            ->color('secondary')
                ->label('Batal')
                ->url($this->getResource()::getUrl()),
        ];
    }
}
