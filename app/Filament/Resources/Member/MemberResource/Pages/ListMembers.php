<?php

namespace App\Filament\Resources\Member\MemberResource\Pages;

use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Actions\Excel\ExcelImportAction;
use App\Filament\Resources\Member\MemberResource;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExcelImportAction::make()
            ->processCollectionUsing(function (string $modelClass, \Illuminate\Support\Collection $collection) {
                return $collection;
            }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
