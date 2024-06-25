<?php 
namespace App\Interfaces;

interface ISiteRepository
{
    public function save(array $sites);
    
    public function update($id, array $sites);

    public function delete($id);

    public function getById($id);

    public function getAll();
}

?>