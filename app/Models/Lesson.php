<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'lessons';
    protected $connection = 'mysql';

    protected $casts = [];

    protected $guarded = [];
}
