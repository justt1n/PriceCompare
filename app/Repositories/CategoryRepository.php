<?php
namespace App\Repositories;

use App\Models\Category;
use App\Interfaces\ICategoryRepository;
use Illuminate\Support\Carbon;

class CategoryRepository implements ICategoryRepository
{
    private Category $category;
    public function __construct()
    {
        $this->category = new Category();
    }

    public function save(array $category)
    {
        // dd($category);
        $data = [
            "name" => $category["name"],
            "created_by" => $category["created_by"],
            "updated_by" => $category["updated_by"],
        ];
        return $this->category::Create($data);
    }
    public function update(array $category, $id)
    {
        $data = [
            "id" => $category["id"],
            "name" => $category["value"],
        ];
        return $this->category::where("id", $id)->update($data);
    }
    public function delete($id)
    {
        return $this->category::where('id', $id)->delete();
    }
    public function getByID($id)
    {
        return $this->category::where('id', $id)->first();
    }
    public function getByName($name)
    {
        return $this->category::where('name', $name)->first();
    }
    public function getAll()
    {
        return $this->category->all();
    }

    public function countAllCategories()
    {
        return $this->category->count();
    }
}

?>
