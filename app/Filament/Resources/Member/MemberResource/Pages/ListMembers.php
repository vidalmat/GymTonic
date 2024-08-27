<?php

namespace App\Filament\Resources\Member\MemberResource\Pages;

use Filament\Actions;
use Filament\Actions\CreateAction;
use App\Actions\PrintMembersAction;
use App\Actions\Excel\ExcelImportAction;
use Filament\Resources\Pages\ListRecords;
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
            PrintMembersAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
