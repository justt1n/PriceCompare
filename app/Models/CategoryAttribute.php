<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;


class CategoryAttribute extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'category_id',
        'attribute_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function attribute(){
        return $this->belongsTo(Attribute::class,'attribute_id','id');
    }
}
