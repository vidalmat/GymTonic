<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ErrorLog extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'error_logs';
    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'code',
        'stack_trace',
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
