<?php
namespace App\Interfaces;

interface IUserRepository
{
    public function save(array $userDetails);
    public function update(array $userDetails, $id);
    public function delete($id);
    public function getById($id);
    public function getAll();
    public function getByEmail($email);
}
?>