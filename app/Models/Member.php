<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Member extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'members';
    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lastname',
        'firstname',
        'email',
    ];

    protected $guarded = [];

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'member_document', 'member_id', 'document_id');
    }

    public function hasDocument($documentType)
    {
        return $this->documents()->where('label', $documentType)->exists();
    }

    public function emails(): BelongsToMany
    {
        return $this->belongsToMany(MailUser::class, 'mail_recipient', 'mail_id', 'member_id');
    }

    public function hasAllRequiredDocuments(): bool
    {
        $requiredDocumentCount = 5;
        return $this->documents()->count() >= $requiredDocumentCount;
    }
}
