<?php

namespace App\Models;

use App\Models\ProductSite;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'brand',
        'public',
        'image',
        'min_price',
        'min_price_site_id',
        'count_site',
        'category_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function product_site()
    {
        return $this->hasMany(ProductSite::class);
    }

    public function product_attribute()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
