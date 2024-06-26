<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberDocument extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'member_document';
    protected $connection = 'mysql';

    protected $guarded = [
        // 'id' => 'string',
        // 'document_id' => 'string',
        // 'member_id' => 'string',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
