<?php

namespace App\Filament\Resources\Network\NetworkResource\Pages;

use App\Filament\Resources\Network\NetworkResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNetwork extends CreateRecord
{
    protected static string $resource = NetworkResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
