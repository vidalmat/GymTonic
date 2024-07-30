<?php

namespace App\Actions\Excel\Concerns;

use Closure;

trait HasCustomCollectionMethod
{
    protected ?Closure $collectionMethod = null;

    public function processCollectionUsing(Closure $closure): static
    {
        $this->collectionMethod = $closure;
        return $this;
    }
}
