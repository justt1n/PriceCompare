<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronjobSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'site_id',
        'new',
        'update',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
