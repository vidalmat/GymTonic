<?php

namespace App\Filament\Resources\Member\MemberResource\Pages;

use Filament\Actions;
use Filament\Actions\CreateAction;
use App\Actions\PrintMembersAction;
use Illuminate\Support\Facades\Auth;
use App\Actions\Excel\ExcelImportAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Member\MemberResource;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [
            CreateAction::make(),
            PrintMembersAction::make()
        ];

        if (Auth::user()->isSuperAdmin()) {
            $actions[] = ExcelImportAction::make()
                ->processCollectionUsing(function (string $modelClass, \Illuminate\Support\Collection $collection) {
                    return $collection;
                });
        }

        return $actions;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
