<?php

namespace App\Filament\Resources\Member\MemberResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\Member\MemberResource;

class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->form->getState()['documents'] ?? false) {
            $this->record->documents()->sync($this->form->getState()['documents']);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string | Htmlable
    {
        return "Modifier " . $this->getRecord()?->lastname . " " . $this->getRecord()?->firstname;
    }
}
