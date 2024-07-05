<?php

namespace App\Filament\Resources\ErrorLog\ErrorLogResource\Pages;

use App\Filament\Resources\ErrorLog\ErrorLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListErrorLogs extends ListRecords
{
    protected static string $resource = ErrorLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
