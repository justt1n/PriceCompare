<?php
namespace App\Repositories;

use App\Models\ProductAttribute;
use App\Interfaces\IProductAttributeRepository;
use Illuminate\Support\Carbon;

class ProductAttributeRepository implements IProductAttributeRepository
{
    private ProductAttribute $productAttribute;
    public function __construct()
    {
        $this->productAttribute = new ProductAttribute();
    }
    public function save(array $productAttributeDetails)
    {
        $data = [
            "product_id" => $productAttributeDetails["product_id"],
            "attribute_id" => $productAttributeDetails["attribute_id"],
            "value" => $productAttributeDetails["value"],
            "created_at" => Carbon::now(),
        ];
        return $this->productAttribute::Create($data);
    }
    public function update(array $productAttributeDetails, $id)
    {
        $data = [
            "product_id" => $productAttributeDetails["product_id"],
            "attribute_id" => $productAttributeDetails["attribute_id"],
            "value" => $productAttributeDetails["value"],
        ];

        return $this->productAttribute::where('id', $id)->update($data);
    }
    public function delete($id)
    {
        return $this->productAttribute::where('id', $id)->delete();
    }
    public function getById($id)
    {
        return $this->productAttribute::where('id', $id)->first();
    }

    public function getByProductId($id)
    {
        return $this->productAttribute::where('product_id', $id)->get();
    }

    public function getByAttributeIdAndProductId($attributeId, $productId)
    {
        return $this->productAttribute::where('attribute_id', $attributeId)->where('product_id', $productId)->first();
    }

    public function getAll()
    {
        return $this->productAttribute->all();
    }
}

?>