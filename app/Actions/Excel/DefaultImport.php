<?php

namespace App\Actions\Excel;

use Closure;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DefaultImport implements ToCollection, WithHeadingRow
{
    protected array $additionalData = [];

    protected ?Closure $collectionMethod = null;

    public function __construct(
        public string $model,
        public array $attributes = []
    ) {
    }

    public function setAdditionalData(array $additionalData): void
    {
        $this->additionalData = $additionalData;
    }

    public function setCollectionMethod(Closure $closure): void
    {
        $this->collectionMethod = $closure;
    }

    public function collection(Collection $collection)
    {
        if(is_callable($this->collectionMethod)) {
            $collection = call_user_func($this->collectionMethod, $this->model, $collection);
        }else{
            foreach ($collection as $row) {
                $data = $row->toArray();
                if(filled($this->additionalData)) {
                    $data = array_merge($data, $this->additionalData);
                }
                $this->model::create($data);
            }
        }

        return $collection;
    }
}
