<?php
namespace App\Repositories\Products;

use App\Models\ProductImage;
use App\Models\ProductSite;
use App\Models\Product;
use App\Interfaces\IProductRepository;

class MobileRepository implements IProductRepository
{
    public function save(array $productDetails)
    {
        $tmpProduct = new Product();
        $tmpProduct->name = $productDetails['name'];
        $tmpProduct->brand = $productDetails['brand'];
        $tmpProduct->public = $productDetails['public'];
        $tmpProduct->image = $productDetails['image'];
        $tmpProduct->min_price = $productDetails['min_price'];
        $tmpProduct->count_site = $productDetails['count_site'];
        $tmpProduct->min_price_site_id = $productDetails['min_price_site_id'];
        $tmpProduct->category_id = $productDetails['category_id'];
        $tmpProduct->created_by = 'batch';
        $tmpProduct->updated_by = 'batch';
        $tmpProduct->deleted_by = null;
        Product::create($tmpProduct->toArray());
    }
    public function update(array $productDetails, $id)
    {
    }
    public function detele($id)
    {
    }
    public function compare()
    {
    }
    public function saveToProductSite($product)
    {
        $tmpProduct = new ProductSite();
        $tmpProduct->name = $product['name'];
        $tmpProduct->url = $product['url'];
        $tmpProduct->price = $product['price'];
        $tmpProduct->product_id = $product['product_id'];
        $tmpProduct->site_id = $product['site_id'];
        $this->instance = ProductSite::create($tmpProduct->toArray());
    }
    
    public function saveToProductAttribute($product)
    {
        return true;
    }
    public function saveToAttributeCategory($product)
    {
        return true;
    }
    public function saveToAttribute($product)
    {
        return true;
    }

    public function getById($id)
    {
    }
    public function getAll()
    {
    }
    public function getBySiteId($siteId)
    {
    }
    public function getByName($name)
    {
    }
    public function getBySiteIdAndProductId($siteId, $productId)
    {
    }
}