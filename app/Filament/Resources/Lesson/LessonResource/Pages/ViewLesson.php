<?php

namespace App\Filament\Resources\Lesson\LessonResource\Pages;

use Filament\Actions\EditAction;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\Lesson\LessonResource;

class ViewLesson extends ViewRecord
{
    protected static string $resource = LessonResource::class;

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
        return "Cours " . $this->getRecord()?->type;
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::ScreenExtraLarge;
    }
}
