<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MailUser extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'mails';
    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'send_to',
        'subject',
        'message',
        'sent',
    ];

    protected $casts = [];

    protected $guarded = [];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'mail_recipient', 'mail_id', 'member_id');
    }

    public function getSendToAttribute($value)
{
    return $value;
}
}
