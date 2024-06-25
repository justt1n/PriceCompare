<?php
namespace App\Repositories;

use App\Models\Attribute;
use Illuminate\Support\Carbon;

class AttributeRepository
{
    private Attribute $attribute;
    public function __construct()
    {
        $this->attribute = new Attribute();
    }
    public function save(array $attribute)
    {
        $data = [
            "name" => $attribute["name"],
            "created_at" => Carbon::now(),
        ];
        return $this->attribute->create($data);
    }
    public function update(array $attribute, $id)
    {
        $data = [
            "name" => $attribute["name"],
        ];

        return $this->attribute::where("id", $id)->update($data);
    }
    public function delete($id)
    {
        return $this->attribute->where("id", $id)->delete();
    }
    public function getById($id)
    {
        return $this->attribute::where("id", $id)->first();
    }
    public function getAll()
    {
        return $this->attribute->all();
    }
    public function getByName($name)
    {
        return $this->attribute->where("name", $name)->first();
    }
}
?>