<?php
namespace App\Interfaces;

interface ICategoryRepository
{
    public function save(array $categoryDetails);
    public function update(array $categoryDetails, $id);
    public function delete($id);
    public function getByID($id);
    public function getByName($name);
    public function getAll();
}

?>