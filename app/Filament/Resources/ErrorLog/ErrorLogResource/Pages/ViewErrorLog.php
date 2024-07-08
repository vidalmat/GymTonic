<?php

namespace App\Filament\Resources\ErrorLog\ErrorLogResource\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ErrorLog\ErrorLogResource;

class ViewErrorLog extends ViewRecord
{
    protected static string $resource = ErrorLogResource::class;

    protected static ?string $title = 'Résultat(s)';

    protected static ?string $breadcrumb = '';
    
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
        ];
    }
}
