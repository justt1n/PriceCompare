<?php
namespace App\Repositories;

use App\Models\ProductSite;
use App\Interfaces\IProductSiteRepository;

class ProductSiteRepository implements IProductSiteRepository
{

    private ProductSite $productSite;

    /**
     * Create a new controller instance.
     *
     * @param $productSite
     *
     * @return void
     */
    public function __construct()
    {
        $this->productSite = new ProductSite;
    }

    public function save(array $productSites)
    {
        $dataCreate = [
            'name' => $productSites['name'],
            'url' => $productSites['url'],
            'price' => $productSites['price'],
            'product_id' => $productSites['product_id'],
            'site_id' => $productSites['site_id'],
        ];
        return $this->productSite::create($dataCreate);

    }

    public function update($id, array $productSites)
    {
        $dataUpdate = [
            'name' => $productSites['name'],
            'url' => $productSites['url'],
            'price' => $productSites['price'],
            'product_id' => $productSites['product_id'],
            'site_id' => $productSites['site_id'],
        ];
        return $this->productSite::where('id', $id)->update($dataUpdate);
    }

    public function delete($id)
    {
        return $this->productSite->where('id', $id)->delete();
    }

    public function getById($id)
    {
        return $this->productSite->where('id', $id)->first();
    }

    public function getAll()
    {
        return $this->productSite->all();
    }

    public function getByName($name)
    {
        return $this->productSite->where('name', $name)->first();
    }

    public function getByNameAndSiteId($name, $siteId)
    {
        return $this->productSite->where('name', $name)->where('site_id', $siteId)->first();
    }

    public function updatePrice($id, $price)
    {
        return $this->productSite->where('id', $id)->update(['price' => $price]);
    }

}
?>