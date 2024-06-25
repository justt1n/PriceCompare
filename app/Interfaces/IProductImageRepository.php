<?php
    namespace App\Interfaces;
    interface IProductImageRepository
    {
        public function save($product_site_id, array $images);
        public function update($id, array $images);
        public function delete($id);
        public function getById($id);
        public function getAll();
    }

?>
