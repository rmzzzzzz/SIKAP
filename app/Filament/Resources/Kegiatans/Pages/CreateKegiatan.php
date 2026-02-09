<?php

namespace App\Filament\Resources\Kegiatans\Pages;

use App\Filament\Resources\Kegiatans\KegiatanResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreateKegiatan extends CreateRecord
{
    protected static string $resource = KegiatanResource::class;


public function canCreateAnother(): bool
{
    return false;
}
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
    protected function afterCreate(): void
    {
        $state = $this->form->getState();

        if (! empty($state['pegawai_ids'])) {
            $this->record
                ->pegawaiWajib()
                ->sync($state['pegawai_ids']);
        }
    }
}
