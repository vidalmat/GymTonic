<?php

namespace App\Filament\Resources\Member\MemberResource\Pages;

use App\Filament\Resources\Member\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $this->record->documents()->sync($this->form->getState()['documents']);
    }
}
