<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CategoryAttribute;


class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function cateAttribute(){
        return $this->hasMany(CategoryAttribute::class,'category_id','id');
    }
}
