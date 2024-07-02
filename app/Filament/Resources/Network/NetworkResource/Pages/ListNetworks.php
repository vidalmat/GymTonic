<?php

namespace App\Filament\Resources\Network\NetworkResource\Pages;

use App\Filament\Resources\Network\NetworkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNetworks extends ListRecords
{
    protected static string $resource = NetworkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
