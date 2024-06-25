<?php

namespace App\Models;

use App\Models\ProductSite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
    use HasFactory;
    use SoftDeletes;//dòng này để tự động thêm điều kiện delete_at = null vào câu query nhés

    protected $fillable = [
        'id',
        'url',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function product_site()
    {
        return $this->hasMany(ProductSite::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sites', 'site_id', 'product_id');
    }
}
