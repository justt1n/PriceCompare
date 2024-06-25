<?php
namespace App\Repositories;

use App\Models\CategoryAttribute;
use App\Interfaces\IAttributeCategoryRepository;
use Illuminate\Support\Carbon;

class AttributeCategoryRepository implements IAttributeCategoryRepository
{
    private CategoryAttribute $categoryAttribute;
    public function __construct()
    {
        $this->categoryAttribute = new CategoryAttribute();
    }
    public function save(array $categoryAttribute)
    {
        $data = [
            "category_id" => $categoryAttribute["category_id"],
            "attribute_id" => $categoryAttribute["attribute_id"],
            "created_at" => Carbon::now(),
        ];

        return $this->categoryAttribute::Create($data);
    }
    public function update(array $categoryAttribute, $id)
    {
        $data = [
            "category_id" => $categoryAttribute["category_id"],
            "attribute_id" => $categoryAttribute["attribute_id"],
        ];

        return $this->categoryAttribute::where("id", $id)->update($data);
    }
    public function delete($id)
    {
        return $this->categoryAttribute::where("id", $id)->delete();
    }
    public function getByID($id)
    {
        return $this->categoryAttribute::where("id", $id)->first();
    }
    public function getAll()
    {
        return $this->categoryAttribute->all();
    }

    public function getByCategoryID($category_id)
    {
        return $this->categoryAttribute::where("category_id", $category_id)->get();
    }
    
    public function getByAttributeIdAndCategoryId($attribute_id, $category_id)
    {
        return $this->categoryAttribute::where("attribute_id", $attribute_id)->where("category_id", $category_id)->first();
    }
    public function getByAttributeId($attribute_id) {
        return $this->categoryAttribute::where("attribute_id", $attribute_id)->get();
    }
}

?>