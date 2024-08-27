<?php

namespace App\Actions;

use App\Models\Member;
use App\Models\Document;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintMembersAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'printMembers')
            ->label(false)
            ->icon('heroicon-o-printer')
            ->color('info')
            ->requiresConfirmation()
            ->modalAlignment('center')
            ->modalHeading('Imprimer')
            ->modalDescription('Voulez-vous imprimer la liste des membres en PDF ?')
            ->icon('heroicon-o-printer')
            ->extraAttributes([
                'title' => 'Imprimer la liste des membres',
            ])
            ->action(function () {

                $members = Member::with('documents')->get();

                $requiredDocuments = Document::all()->pluck('label')->toArray();

                $membersData = $members->map(function ($member) use ($requiredDocuments) {
                    $documentsPresent = $member->documents->pluck('label')->toArray();
                    $documentsCount = $member->documents->count();

                    $documentsMissing = [];
                    if ($documentsCount < 5) {
                        $documentsMissing = array_diff($requiredDocuments, $documentsPresent);
                    }

                    return [
                        'firstname' => $member->firstname,
                        'lastname' => $member->lastname,
                        'email' => $member->email,
                        'created_at' => $member->created_at->format('d/m/Y'),
                        'documents_count' => $documentsCount,
                        'documents_missing' => $documentsMissing
                    ];
                });

                $pdf = Pdf::loadView('exports.members', ['members' => $membersData])
                    ->setPaper('a3', 'portrait');

                return response()->streamDownload(
                    fn() => print($pdf->output()),
                    'liste_des_membres.pdf'
                );
            });
    }
}
