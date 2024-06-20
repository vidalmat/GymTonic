<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'members';
    protected $connection = 'mysql';

    protected $guarded = [];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

}
