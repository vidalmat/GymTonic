<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'lessons';
    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'label',
        'duration',
        'start',
        'end',
    ];


    protected $casts = [
        'dates' => 'array',
    ];

    protected $guarded = [];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
