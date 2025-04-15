<?php

namespace App\Filament\Resources\CodesGroupResource\Pages;

use App\Filament\Resources\CodesGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCodesGroups extends ListRecords
{
    protected static string $resource = CodesGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
