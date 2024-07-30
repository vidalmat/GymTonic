<?php

namespace App\Actions\Excel\Concerns;

use Closure;

trait HasFormActionHooks
{
    protected ?Closure $beforeImportClosure = null;

    protected ?Closure $afterImportClosure = null;

    protected array $additionalData = [];

    public function additionalData(array $data): static
    {
        $this->additionalData = $data;

        return $this;
    }

    public function beforeImport(Closure $closure): static
    {
        $this->beforeImportClosure = $closure;

        return $this;
    }

    public function afterImport(Closure $closure): static
    {
        $this->afterImportClosure = $closure;

        return $this;
    }
}
