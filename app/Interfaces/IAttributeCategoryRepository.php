<?php
namespace App\Interfaces;

interface IAttributeCategoryRepository
{
    public function save(array $attributeCategoryDetails);
    public function update(array $attributeCategoryDetails, $id);
    public function delete($id);
    public function getByID($id);
    public function getAll();
}

?>