<?php
namespace App\Interfaces;

interface IProductAttributeRepository
{
    public function save(array $productAttributeDetails);
    public function update(array $productAttributeDetails, $id);
    public function delete($id);
    public function getByID($id);
    public function getAll();
}

?>