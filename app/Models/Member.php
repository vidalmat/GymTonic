<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Member extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'members';
    protected $connection = 'mysql';

    protected $guarded = [];

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'member_document', 'member_id', 'document_id');
    }

    public function hasDocument($documentType)
    {
        return $this->documents()->where('label', $documentType)->exists();
    }
}
