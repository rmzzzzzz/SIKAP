<?php

namespace App\Filament\Resources\Pangkats\Pages;

use App\Filament\Resources\Pangkats\PangkatResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPangkat extends EditRecord
{
    protected static string $resource = PangkatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
