<?php

namespace App\Filament\Resources\Mail\MailResource\Pages;

use App\Filament\Resources\Mail\MailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMails extends ListRecords
{
    protected static string $resource = MailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
