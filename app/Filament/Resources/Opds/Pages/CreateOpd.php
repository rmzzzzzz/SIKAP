<?php

namespace App\Filament\Resources\Opds\Pages;

use App\Filament\Resources\Opds\OpdResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;
class CreateOpd extends CreateRecord
{
    protected static string $resource = OpdResource::class;
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
