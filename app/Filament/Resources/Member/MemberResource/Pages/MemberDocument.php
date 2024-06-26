<?php

namespace App\Filament\Resources\Member\MemberResource\Pages;

use App\Models\Member;
use App\Models\Document;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Actions\AssociateAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\DissociateAction;
use App\Filament\Resources\Member\MemberResource;
use Filament\Tables\Actions\DissociateBulkAction;
use Filament\Resources\Pages\ManageRelatedRecords;

class MemberDocument extends ManageRelatedRecords
{
    protected static string $resource = MemberResource::class;

    // protected static ?string $model = Document::class;

    protected static ?string $title = 'Documents';

    protected static string $relationship = 'documents';

    protected static ?string $breadcrumb = '';

    public function form(Form $form): Form
    {

        return $form
            ->schema([
            CheckboxList::make('documents')
                ->relationship('members')
            // ->options([
            //     'member_charter' => 'Charte de l\'adhérent',
            //     'registration_form' => 'Fiche d\'inscription',
            //     'cover_letter' => 'Lettre d\'accompagnement',
            //     'partner_document' => 'Documents partenaires',
            //     'medical_certificat' => 'Certificat médical',
            // ])
            ->options(function (Document $document) {
                // dd(Arr::pluck(Document::query()->get(), 'label', 'id'));
                return Arr::pluck(Document::query()->get(), 'label', 'id');
            })]);
    }

    public function table(Table $table): Table
    {        return $table
            ->columns([
                TextColumn::make('documents.id')
                    ->label(new HtmlString('<span class="text-gray-400">Libellé</span>'))
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                // DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canViewForRecord(Member $record): bool
    {
        return $record->is_active ?? false;
    }
}





    // $options = Document::all()->mapWithKeys(function ($item) {
    //         // dd($item);
    //         return [$item->id => $item->label];
    //     });

    //     // dd($options);

    //     return $form
    //         ->schema([

    //         // Hidden::make('id')
    //         //     ->default(fn () => Str::uuid()->toString())
    //         //     ->required(),


    //         // TextInput::make('member_document.id')
    //         //     ->default(Str::uuid()->toString()) // Génère un UUID par défaut
    //         //     // ->required()
    //         //     // ->disabled() // Empêche la modification manuelle
    //         //     ->columnSpanFull(),

    //             // CheckboxList::make('documents')
    //             //     ->relationship('members')
    //             //     ->options([
    //             //         'member_charter' => 'Charte de l\'adhérent',
    //             //         'registration_form' => 'Fiche d\'inscription',
    //             //         'cover_letter' => 'Lettre d\'accompagnement',
    //             //         'partner_document' => 'Documents partenaires',
    //             //         'medical_certificat' => 'Certificat médical',
    //             // ])
    //             // ->pivotData([
    //             //     'id' => fn () => Str::uuid()->toString(),
    //             //     'document_id' => fn () => Str::uuid()->toString(),
    //             //     'member_id' => fn () => Str::uuid()->toString(),
    //             // ])

    //             CheckboxList::make('documents')
    //                 ->label('Documents')
    //                 ->columnSpanFull()
    //                 ->relationship('documents')
    //                 ->options($options)
    //                 ->pivotData(function ($item, $state) {
    //                     return [
    //                         'document_id' => $item->document_id,
    //                         'member_id' => $item->member_id,
    //                     ];
    //                 })
    //                 ->columns(3),
    //         ]);
