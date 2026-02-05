<?php

namespace App\Filament\Resources\Kegiatans\Pages;

use App\Filament\Resources\Kegiatans\KegiatanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKegiatan extends EditRecord
{
    protected static string $resource = KegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
      protected function afterSave(): void
    {
        $state = $this->form->getState();

        if (array_key_exists('pegawai_ids', $state)) {
            $this->record
                ->pegawaiWajib()
                ->sync($state['pegawai_ids']);
        }
    }
}  