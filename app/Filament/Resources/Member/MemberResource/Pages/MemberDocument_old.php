<?php

namespace App\Filament\Resources\Member\MemberResource\Pages;

use App\Models\Member;
use App\Models\Document;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Actions\AssociateAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\DissociateAction;
use App\Filament\Resources\Member\MemberResource;
use Filament\Tables\Actions\DissociateBulkAction;
use Filament\Resources\Pages\ManageRelatedRecords;

class MemberDocument_old extends ManageRelatedRecords
{
    protected static string $resource = MemberResource::class;

    // protected static ?string $model = Document::class;

    protected static ?string $title = 'Documents';

    protected static string $relationship = 'documents';

    protected static ?string $breadcrumb = '';

    // public function getTitle(): string | Htmlable
    // {
    //     $recordTitle = $this->getRecordTitle();

    //     $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

    //     return "Documents du membre {$recordTitle}";
    // }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                CheckboxList::make('technologies')
                    ->options([
                        'member_charter' => 'Charte de l\'adhérent',
                        'registration_form' => 'Fiche d\'inscription',
                        'cover_letter' => 'Lettre d\'accompagnement',
                        'partner_document' => 'Documents partenaires',
                        'medical_certificat' => 'Certificat médical',
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('document_member.medical_certificat')
            ->columns([
                TextColumn::make('document_member.medical_certificat'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
                Action::make('edit')
                ->url(fn (Document $record): string => route('member_document.edit', $record))
                ->openUrlInNewTab()
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::ScreenExtraLarge;
    }

    public static function canViewForRecord(Member $record): bool
    {
        return $record->is_active ?? false;
    }
}



// public function form(Form $form): Form
//     {
//         return $form
//             ->schema([
//                 Checkbox::make('label'),
//             ]);
//     }

//     public function table(Table $table): Table
//     {
//         return $table
//             ->striped()
//             // ->recordTitleAttribute('member_document.label')
//             ->columns([
//                 CheckboxColumn::make('member_charter')
//                 ->label(new HtmlString('<span class="text-gray-400">Nom</span>'))
//                 ->searchable()
//                     ->sortable(),
//                 // Add more columns as needed
//             ])
//             ->filters([])
//             ->headerActions([EditAction::make()
//             ])->emptyStateActions([])
//             ->actions([
//                 EditAction::make()
//             ->form([
//                 TextInput::make('medical_certificat')
//                     ->required()
//                     ->maxLength(255),
//                 // ...
//             ]),
//             ])

//             ->bulkActions([
                // BulkAction::make('send')
                //     ->label('Envoyer sur Konect')
                //     ->icon('heroicon-m-document-plus')
                //     // ->button()
                //     ->requiresConfirmation()
                //     ->deselectRecordsAfterCompletion()
                //     ->action(function ($records) {
                //         foreach ($records as $record) {
                //             $url = env('URL_KONECT');
                //             $document = $record->file_id;
                //             $response = Http::post($url . '/mes-voyages/' . $document . '/document');
                //             // $response = Http::post($url. '/mes-voyages/'. $document. '/document', [
                //             //     'file_id' => $record->file_id,
                //             //     'contact_id' => $record->contact_id,
                //             //     'konect_user_id' => $record->konect_user_id,
                //             // ]);

                //             if ($response->failed()) {
                //                 Notification::make()
                //                 ->title('Erreur lors de l\'envoi du document')
                //                 ->danger()
                //                 ->send();
                //             }
                //         }

                //         Notification::make()
                //             ->title('Le document '. '"' . $record->label . '"' . ' a été envoyé avec succès')
                //             ->success()
                //             ->send();
                //     }),
//             ]);
//     }
