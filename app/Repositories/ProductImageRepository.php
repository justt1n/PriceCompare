<?php
namespace App\Repositories;

use App\Models\ProductImage;
use App\Interfaces\IProductImageRepository;

class ProductImageRepository implements IProductImageRepository
{

    private ProductImage $productImage;

    /**
     * Create a new controller instance.
     *
     * @param $productImage
     *
     * @return void
     */
    public function __construct()
    {
        $this->productImage = new ProductImage;
    }

    public function save($product_site_id, $image)
    {
        $dataCreate = [
            'url' => $image,
            'product_site_id' => $product_site_id,
        ];
        return $this->productImage::create($dataCreate);
    }

    public function update($id, array $images)
    {
        $dataUpdate = [
            'url' => $images['url'],
            'product_site_id' => $id,
        ];
        return $this->productImage::where('id', $id)->update($dataUpdate);
    }

    public function delete($id)
    {
        return $this->productImage->where('id', $id)->delete();
    }

    public function getById($id)
    {
        return $this->productImage->where('id', $id)->first();
    }

    public function getAll()
    {
        return $this->productImage->all();
    }
}
?>