<?php
namespace App\Helpers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Services\Scraper\IScrape;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\SiteRepository;
use App\Repositories\ProductSiteRepository;
use App\Repositories\ProductImageRepository;
use App\Repositories\AttributeRepository;
use App\Repositories\AttributeCategoryRepository;
use App\Repositories\ProductAttributeRepository;


class ScrapeHelper
{
    public static function preProcessName($name)
    {
        $name = mb_strtolower($name);
        $removeItems = ['dương','tự nhiên','dark','clous','rococo','himalaya','crocodilec', 'paris', 'gradient', 'da cá sấu', 'gold', 'cinon', 'raspberry', 'moon', 'silver', 'steel', 'stainless', 'jade', 'pure', 'lizard', 'dawning', 'monogram', 'canvas', 'leather', 'basic', 'grey', 'navy', 'alligator', 'gothic', 'gentleman', 'baroque', 'calf', 'brown', 'stitching', 'lake', 'retro', 'lục', 'tím', 'kem', 'chip', '+', 'lte', 'thụy sĩ', 'và', 'kim loại', 'dây da', 'máy cơ tự động', 'máy tính xách tay', 'chính hãng', 'nam', 'nữ', 'laptop', 'máy tính bảng', 'điện thoại', 'đồng hồ thông minh ', 'đồng hồ', 'win 10', 'win 11', 'xanh', 'cam', 'xám', 'vàng', 'đen', 'full box', 'di động' , 'trẻ em' , 'màn hình' , 'cảm ứng' , 'nhập' , 'khẩu' , 'chắc chắn'];
        $name = str_replace($removeItems, '', $name);
        $name = preg_replace("/\b\d+-core\b/i", '', $name);
        $name = preg_replace("/\b(nhập|giảm)\s+\S+\b/i", "", $name);
        $delimiters = ['-', '(', ',', '|'];
        foreach ($delimiters as $delimiter) {
            $name = explode($delimiter, $name)[0];
        }

        return $name;
    }
    public static function getPureName($s)
    {
        $s = ScrapeHelper::preProcessName($s);
        $s = strtolower(trim($s));
        $s = preg_replace("/\b(\d+gb\/\d+(gb|tb))\b|\(\d+gb\/\d+(gb|tb)\)/i", "", $s);
        $s = preg_replace("/\b\d+g\/\d+(gb|tb)\b/i", "", $s);
        $s = preg_replace("/\b\d+g\/\d+(g|tb)\b/i", "", $s);
        $s = preg_replace("/[\/-]/", '', $s);
        $s = preg_replace("/[\(\)\[\]\|\&]/", '', $s);


        $removeItems = ['i3', 'i5', 'i7', 'i9', 'r3', 'r5', 'r7', 'r9', 'n4020', 'n5100', 'n6000', 'n4120', 'gtx', 'rtx', 'quadro'];


        $removeItemsPattern = implode('|', $removeItems);

        $s = preg_replace("/\b($removeItemsPattern)\b(\s+\S+)?/i", '', $s);


        $anotherNames = ['gpu', 'oled', 'gen', 'm1 pro', 'm1 max', 'm2 pro', 'm2 max', 'm3 pro', 'm3 max', 'm3', 'm2', 'm1', 'cellular', 'wifi', 'mới trần', 'apple', 'likenew', 'chính hãng', 'vna', 'red', 'orange', 'yellow', 'green', 'blue', 'indigo', 'violet', 'black', 'white', 'gray', 'titan', 'đen', 'space', 'purple'];

        $anotherNamesPattern = implode('|', $anotherNames);

        $s = preg_replace("/\b($anotherNamesPattern)\b/i", '', $s);

        $s = preg_replace("/\b\d+-cpu\b/i", '', $s);
        $s = preg_replace("/\bchip m\d+\b/i", '', $s);
        $s = preg_replace("/\bvn\/a\b/i", '', $s);
        // if (strpos($s, 'iphone') === false && strpos($s, 'ipad') === false && strpos($s, 'macbook') === false) {
        //     $s = preg_replace("/\b\d+\s*(gb|tb)\b/i", '', $s);
        // }
        
        $s = preg_replace("/\b\d+\s*(gb|tb)\b/i", '', $s);
        $s = preg_replace("/\b\d+g\b/i", '', $s);
        $s = preg_replace("/\b(\d+)(st|nd|rd|th)\b/i", '$1', $s);


        if (strpos($s, "macbook" === true)) {
            $s = trim($s);
            $s = explode(" ", $s);
            $s = array_filter($s);
            $s = implode(" ", $s);
            $s = preg_replace("/\b\d+(cpu|gpu)\b/i", "", $s);
            $name = str_replace(" ", "-", $s);
        } else {
            $s = preg_replace("/\b(20(1[8-9]|2[0-9]|30))\b/", '', $s);
            $s = preg_replace("/\b\d+(\.\d+)?\s*(inch|-inch)\b/i", '', $s);
            $s = trim($s);
            $s = explode(" ", $s);
            $s = array_filter($s);
            $s = implode(" ", $s);
            $name = str_replace(" ", "-", $s);
        }

        $name = ucwords(str_replace('-',' ',$name));
        $name = str_replace('Iphone','iPhone',$name);
        $name = str_replace('Ipad','iPad',$name);
        return $name;
    }

    public static function loadArrayFromJsonFile($filename)
    {
        if (!file_exists($filename)) {
            return false;
        }

        $handle = fopen($filename, 'r');
        if (!$handle) {
            return false;
        }

        $bufferSize = 4096; // Set the buffer size to read the file
        $array = [];
        while (!feof($handle)) {
            $buffer = fread($handle, $bufferSize);
            $array[] = $buffer;
        }

        fclose($handle);

        $json = implode('', $array);
        $decodedArray = json_decode($json, true);
        return $decodedArray;
    }
    public static function saveArrayToJsonFile($array, $filename)
    {
        $existingData = [];
        if (file_exists($filename)) {
            $existingData = json_decode(file_get_contents($filename), true);
            if (!is_array($existingData)) {
                $existingData = [];
            }
        }

        $mergedData = array_merge($existingData, $array);
        $json = json_encode($mergedData);

        if (file_put_contents($filename, $json) === false) {
            return false;
        }
        return true;
    }

    public static function mapAttribute($attribute){
        $attributesToCompare = [];
        foreach ($attribute as $key => $value) {
            $key = strtolower($key);
            $map = [
                //man hinh
                'screen-size' => 'display',
                'display' => 'display',
                'màn hình' => 'display',
                'màn hình rộng' => 'display',
                'kích thước màn hình' => 'display',
                'kích thước màn hình (inch)' => 'display',
                'công nghệ màn hình' => 'display-tech',
                //do phan giai
                'độ phân giải màn hình' => 'res',
                'độ phân giải' => 'res',
                'screen-res' => 'res',
                //cpu
                'cpu' => 'cpu',
                'chip set' => 'cpu',
                'công nghệ CPU' => 'cpu',
                'chip' => 'cpu',
                'chip xử lý (cpu)' => 'cpu',
                //gpu
                'model' => 'gpu',
                'vga' => 'gpu',
                'chip đồ họa (gpu)' => 'gpu',
                'chip đồ họa' => 'gpu',
                //rom
                'rom' => 'rom',
                'dung lượng' => 'rom',
                'bộ nhớ trong' => 'rom',
                'ổ cứng' => 'rom',
                'lưu trữ' => 'rom',
                'dung lượng ssd' => 'rom',
                //ram
                'dung lượng ram' => 'ram',
                'ram' => 'ram',
                //can nang
                'weight' => 'weight',
                'trong lượng' => 'weight',
                'kích thước, khối lượng' => 'weight',
                //he dieu hanh
                'hệ điều hành' => 'os',
                //pin
                'pin' => 'pin',
                'dung lượng pin' => 'pin',
                'dung lượng pin (mah)' => 'pin',
                'công suất pin' => 'pin',
                //sw attributeé
                'chất liệu dây' => 'size',
                'kích thước' => 'size',
                //connection
                'kết nối' => 'connect',
                'kết nối không dây' => 'connect',
            ];

            if (array_key_exists($key, $map)) {
                $attributesToCompare[$map[$key]] = $value;
                unset($attribute[$key]);
            }
        }
        return $attributesToCompare;
    }

}




// {
//     public static $categoryRepository = new CategoryRepository();
//     public static $siteRepository = new SiteRepository();
//     public static $productRepository = new ProductRepository();
//     public static $productSiteRepository = new ProductSiteRepository();
//     public static $attributeRepository = new AttributeRepository();
//     public static $productImageRepository = new ProductImageRepository();
//     public static $attributeCategoryRepository = new AttributeCategoryRepository();
//     public static $productAttributeRepository = new ProductAttributeRepository();

//     public static function saveToProductSite($siteId, $product, $catId)
//     {
//         $productSite = [];
//         $productSite['name'] = $product['name'];
//         $productSite['url'] = $product['url'];
//         $productSite['price'] = $product['price'];
//         $productSite['product_id'] = $this->getProductId($product, $catId);
//         $productSite['site_id'] = $siteId;
//         $this->updateProductMinPrice($productSite);
//         $productSite = ScrapeHelper->productSiteRepository->save($productSite);
//         return $productSite;
//     }

//     public function updateProductMinPrice($productSite)
//     {
//         $product = $this->_productRepository->getById($productSite['product_id']);
//         if ($product->min_price > $productSite['price'] || $product->min_price == null) {
//             $this->_productRepository->updateMinPrice($productSite['product_id'], $productSite['price'], $productSite['site_id']);
//         }
//     }

//     function getProductId($product, $catId)
//     {
//         $p = $this->_productRepository->getByName($product['name']);
//         if ($p) {
//             return $p->id;
//         }
//         $newProduct = $this->saveToProduct($product, $catId);
//         return $newProduct->id;
//     }

//     function addImages($productImages, $productSiteId)
//     {
//         foreach ($productImages as $productImage) {
//             $this->_productImageRepository->save($productSiteId, $productImage);
//         }
//     }

//     function saveToProduct($product, $catId)
//     {
//         $pro = [];
//         $pro['name'] = $product['name'];
//         $pro['brand'] = $product['brand'];
//         $pro['public'] = true;
//         $pro['active'] = 1;
//         $pro['image'] = $product['image'];
//         $pro['min_price'] = $product['price'];
//         $pro['min_price_site_id'] = intval($this->_siteId);
//         $pro['category_id'] = $catId;
//         $pro['created_by'] = 'batch';
//         return $this->_productRepository->save($pro);
//     }

//     public function getCategoryId($name)
//     {
//         $category = $this->_categoryRepository->getByName($name);
//         if ($category) {
//             return $category->id;
//         }
//         $category = [];
//         $category['name'] = $name;
//         $category['created_by'] = 1;
//         $category['updated_by'] = 1;
//         $new_cate = $this->_categoryRepository->save($category);
//         return $new_cate->id;
//     }
// }
?>
