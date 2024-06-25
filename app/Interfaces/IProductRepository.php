<?php
    namespace App\Interfaces;
    interface IProductRepository
    {
        public function save(array $productDetails);
        public function update($id, array $productDetails);
        public function delete($id);        
        public function compare();
        public function getById($id);
        public function getAll();
        public function getBySiteId($siteId);
        public function getByName($name);
        public function getBySiteIdAndProductId($siteId, $productId);
    }

?>
