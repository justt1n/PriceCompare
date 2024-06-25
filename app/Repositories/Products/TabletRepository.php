<?php
namespace App\Repositories\Products;

use Illuminate\Database\Eloquent\Model;

use App\Interfaces\IProductRepository;
class TabletRepository implements IProductRepository
{
    public function save(array $productDetails)
    {
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
    public function saveToProductSite(array $productDetails)
    {
    }
    public function saveToProductAttribute(array $productDetails)
    {
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