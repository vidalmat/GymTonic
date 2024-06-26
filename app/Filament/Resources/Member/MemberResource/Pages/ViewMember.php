<?php

namespace App\Filament\Resources\Member\MemberResource\Pages;

use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Member\MemberResource;
use Illuminate\Contracts\Support\Htmlable;

class ViewMember extends ViewRecord
{
    protected static string $resource = MemberResource::class;

    protected static ?string $title = 'Général';

//    protected function getHeaderActions(): array
//    {
//        return [
//             EditAction::make()
//        ];
//    }

    protected static ?string $breadcrumb = '';

    public function getTitle(): string | Htmlable
    {
        return "Membre " . $this->getRecord()?->code;
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::ScreenExtraLarge;
    }
}
