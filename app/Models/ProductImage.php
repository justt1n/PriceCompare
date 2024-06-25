<?php

namespace App\Models;

use App\Models\ProductSite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use HasFactory;
    use SoftDeletes;//dòng này để tự động thêm điều kiện delete_at = null vào câu query nhé

    protected $table = 'product_images';

    protected $fillable = [
        'id',
        'url',
        'product_site_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function product_site()
    {
        return $this->belongsTo(ProductSite::class, 'product_site_id', 'id');
    }
}
