<?php 
namespace App\Interfaces;

interface IProductSiteRepository
{
    public function save(array $productSites);
    
    public function update($id, array $productSites);

    public function delete($id);

    public function getById($id);

    public function getAll();
}

?>