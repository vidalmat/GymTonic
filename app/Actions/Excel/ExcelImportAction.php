<?php

namespace App\Actions\Excel;

use Closure;
use App\Models\Member;
use App\Models\Document;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\FileUpload;
use App\Actions\Excel\Concerns\HasUploadForm;
use App\Actions\Excel\Concerns\HasFormActionHooks;
use App\Actions\Excel\Concerns\CanCustomiseActionSetup;
use App\Actions\Excel\Concerns\HasCustomCollectionMethod;

class ExcelImportAction extends Action
{
    use HasUploadForm,
        HasFormActionHooks,
        HasCustomCollectionMethod,
        CanCustomiseActionSetup;

    protected string $importClass = DefaultImport::class;

    protected array $importClassAttributes = [];

    public function use(string $class = null, ...$attributes): static
    {
        $this->importClass = $class ?: DefaultImport::class;
        $this->importClassAttributes = $attributes;

        return $this;
    }

    public static function getDefaultName(): ?string
    {
        return 'import';
    }

    public function action(Closure | string | null $action): static
    {
        if ($action !== 'importData') {
            throw new \Exception('Vous ne pouvez pas remplacer l\'action de ce plugin');
        }

        $this->action = $this->importData();

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-arrow-down-tray')
            ->label('Import Excel')
            ->color('warning')
            ->form(fn () => $this->getDefaultForm())
            ->modalIcon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->modalWidth('md')
            ->modalAlignment('center')
            ->modalHeading(fn ($livewire) => __('Import Excel'))
            ->modalDescription(__('Importer des données dans la base de données à partir d\'un fichier Excel'))
            ->modalFooterActionsAlignment('right')
            ->closeModalByClickingAway(false)
            ->action('importData');
    }

    private function importData(): Closure
    {
        return function (array $data, $livewire): bool {
            if (is_callable($this->beforeImportClosure)) {
                call_user_func($this->beforeImportClosure, $data, $livewire, $this);
            }
            $importObject = new $this->importClass(
                method_exists($livewire, 'getModel') ? $livewire->getModel() : null,
                $this->importClassAttributes,
                $this->additionalData
            );

            if(method_exists($importObject, 'setAdditionalData')) {
                $importObject->setAdditionalData($this->additionalData);
            }

            if(method_exists($importObject, 'setCollectionMethod')) {
                $importObject->setCollectionMethod($this->collectionMethod);
            }

            Excel::import($importObject, $data['upload']);

            if (is_callable($this->afterImportClosure)) {
                call_user_func($this->afterImportClosure, $data, $livewire);
            }
            return true;
        };
    }

    public function processCollectionUsing(Closure $closure): static
{
    $this->collectionMethod = function (string $modelClass, \Illuminate\Support\Collection $collection) use ($closure) {
        $processedCollection = $closure($modelClass, $collection);

        foreach ($processedCollection as $row) {

            $member = Member::updateOrCreate(
                [
                    'email' => $row['email'],
                    'lastname' => $row['lastname'],
                    'firstname' => $row['firstname'],
                ]
            );

            $documentIds = [];

            if (!empty($row['certificat_medical'])) {
                $documentIds[] = Document::firstOrCreate(['label' => 'Certificat médical'])->id;
            }
            if (!empty($row['documents_partenaires'])) {
                $documentIds[] = Document::firstOrCreate(['label' => 'Documents partenaires'])->id;
            }
            if (!empty($row['fiche_dinscription'])) {
                $documentIds[] = Document::firstOrCreate(['label' => 'Fiche d\'inscription'])->id;
            }
            if (!empty($row['lettre_daccompagnement'])) {
                $documentIds[] = Document::firstOrCreate(['label' => 'Lettre d\'accompagnement'])->id;
            }
            if (!empty($row['charte_de_ladherent'])) {
                $documentIds[] = Document::firstOrCreate(['label' => 'Charte de l\'adhérent'])->id;
            }

            $member->documents()->sync($documentIds);

            // $member->documents()->syncWithoutDetaching($documentIds);
        }

        return $processedCollection;
    };

    return $this;
}
}
