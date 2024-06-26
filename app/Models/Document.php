<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Document extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'documents';
    protected $connection = 'mysql';

    protected $casts = [
        'member_charter' => 'boolean',
        'registration_form' => 'boolean',
        'cover_letter' => 'boolean',
        'partner_document' => 'boolean',
        'medical_certificat' => 'boolean',
    ];

    protected $guarded = [];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_document', 'document_id', 'member_id');
    }
}
