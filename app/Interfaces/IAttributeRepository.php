<?php
namespace App\Interfaces;

interface IAttributeRepository
{
    public function save(array $attributeDetails);
    public function update(array $attributeDetails, $id);
    public function delete($id);
    public function getById($id);
    public function getAll();
    public function getByName($name);
}
?>