<?php

namespace App\Filament\Resources\Member\MemberResource\Pages;

use Filament\Actions\EditAction;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\Member\MemberResource;

class ViewMember extends ViewRecord
{
    protected static string $resource = MemberResource::class;

    protected static ?string $title = 'Général';

   protected function getHeaderActions(): array
   {
       return [
            EditAction::make()
       ];
   }

    protected static ?string $breadcrumb = '';

    public function getTitle(): string | Htmlable
    {
        return "Membre " . $this->getRecord()?->lastname . " " . $this->getRecord()?->firstname;
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::ScreenExtraLarge;
    }
}
