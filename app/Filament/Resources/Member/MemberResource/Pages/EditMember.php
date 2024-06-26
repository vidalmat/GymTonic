<?php

namespace App\Filament\Resources\Member\MemberResource\Pages;

use App\Filament\Resources\Member\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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
        // Vérifiez si 'documents' existe dans l'état du formulaire
        if ($this->form->getState()['documents'] ?? false) {
            // Synchroniser les documents sélectionnés avec le membre
            $this->record->documents()->sync($this->form->getState()['documents']);
        }
    }
}
