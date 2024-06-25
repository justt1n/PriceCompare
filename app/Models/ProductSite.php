<?php

namespace App\Models;

use App\Models\Site;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSite extends Model
{
    use HasFactory;
    use SoftDeletes;//dòng này để tự động thêm điều kiện delete_at = null vào câu query nhés

    protected $table = 'product_sites';

    protected $fillable = [
        'id',
        'name',
        'url',
        'price',
        'product_id',
        'site_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function product_image()
    {
        return $this->hasMany(ProductImage::class,'product_site_id');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
