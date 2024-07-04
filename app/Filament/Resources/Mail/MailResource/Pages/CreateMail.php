<?php

namespace App\Filament\Resources\Mail\MailResource\Pages;

use App\Filament\Resources\Mail\MailResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMail extends CreateRecord
{
    protected static string $resource = MailResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
