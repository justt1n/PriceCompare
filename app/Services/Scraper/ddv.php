<?php

namespace App\Services\Scraper;

use App\Helpers\ScrapeHelper;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use App\Services\Scraper\IScrape;
use Illuminate\Support\Facades\Log;
use App\Repositories\SiteRepository;
use App\Repositories\ProductRepository;
use function PHPUnit\Framework\isEmpty;
use App\Repositories\CategoryRepository;
use App\Repositories\AttributeRepository;
use Symfony\Component\DomCrawler\Crawler;
use App\Repositories\ProductSiteRepository;

use App\Repositories\ProductImageRepository;
use App\Repositories\ProductAttributeRepository;
use App\Repositories\AttributeCategoryRepository;

class ddv implements IScrape
{
    private $_url = 'https://didongviet.vn';
    private $_siteId = "4";
    private $_imageUrlPattern = 'https://cdn-v2.didongviet.vn/';
    private $_detailUrlPattern = 'https://ecomws.didongviet.vn/fe/v1/products/';

    private $_categoryRepository;
    private $_siteRepository;
    private $_productRepository;
    private $_productSiteRepository;
    private $_attributeRepository;
    private $_productImageRepository;
    private $_attributeCategoryRepository;
    private $_productAttributeRepository;

    public function __construct()
    {
        $this->_categoryRepository = new CategoryRepository();
        $this->_siteRepository = new SiteRepository();
        $this->_productRepository = new ProductRepository();
        $this->_productSiteRepository = new ProductSiteRepository();
        $this->_attributeRepository = new AttributeRepository();
        $this->_productImageRepository = new ProductImageRepository();
        $this->_attributeCategoryRepository = new AttributeCategoryRepository();
        $this->_productAttributeRepository = new ProductAttributeRepository();
    }

    function scrape($isFetch = true, $isUpdate = false)
    {
        $urlPatterns = [
            'mobile' => 'https://ecomws.didongviet.vn/fe/v1/products?category_ids=3&page=',
            'laptopMacPro' => 'https://ecomws.didongviet.vn/fe/v1/products?category_ids=766&page=',
            'laptopMacAir' => 'https://ecomws.didongviet.vn/fe/v1/products?category_ids=23&page=',
            'tabletApple' => 'https://ecomws.didongviet.vn/fe/v1/products?category_ids=643&page=',
            'tabletSamsung' => 'https://ecomws.didongviet.vn/fe/v1/products?category_ids=339&page=',
            'smartwatch' => 'https://ecomws.didongviet.vn/fe/v1/products?category_ids=462&page=',
        ];
        if ($isUpdate) {
            foreach ($urlPatterns as $key => $urlPattern) {
                $this->updateByUrl($key, $urlPattern);
            }
            return;
        }
        if (!$isFetch) {
            $products = ScrapeHelper::loadArrayFromJsonFile('storage/ddv.json');
            foreach ($products as $product) {
                try {
                    $this->saveToDatabase($product);
                } catch (\Exception $e) {
                    \Log::error('' . $e->getMessage());
                }
            }
            return;
        }

        foreach ($urlPatterns as $key => $urlPattern) {
            $this->scrapeByUrl($key, $urlPattern);
        }
    }

    function scrapeByUrl($key, $url)
    {
        $client = new Client();
        $total = -1;
        $totalCurrent = -1;
        $i = 0;
        $products = [];
        $count = 0;
        do {
            $response = $client->request('GET', $url . $i);
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);

            $total = $body['data']['paging']['total'];
            $totalCurrent = $body['data']['paging']['currentPage'];
            $listProducts = $body['data']['data'];

            foreach ($listProducts as $productItem) {

                try {
                    $product = [];
                    $product['category_name'] = $this->getCategoryName($productItem['categorySlug']);
                    $product['category_id'] = $this->getCategoryId($product['category_name']);

                    $product['name'] = $productItem['product'];
                    $product['url'] = $this->_url . "/" . $productItem['categorySlug'] . "/" . $productItem['redirect_url'];
                    if ($product['url'] == $this->_url . "/" . $productItem['categorySlug']) {
                        continue;
                    }

                    $product['price'] = $productItem['price'];
                    if ($product['price'] == 0) {
                        continue;
                    }
                    $product['site_id'] = $this->_siteId;

                    if (!empty($productItem['thumbnail'])) {
                        $product['image'] = $this->_imageUrlPattern . $productItem['thumbnail'];
                    } else {
                        $product['image'] = "https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png";
                    }

                    $detailAPI = $this->_detailUrlPattern . $productItem['slug'];

                    $product = $this->getDetail($detailAPI, $product);  // get detail( attributes, images)

                    $brand = $this->getBrandName($productItem['product']);

                    $product['brand'] = $brand;

                    $products[] = $product;

                    print("Fetching " . count($products) . " " . $key . " from: " . $this->_url . "\n");
                    $count++;
                } catch (\Exception $e) {
                    Log::channel('scrapper')->error($e);
                }
            }
            $i++;
        } while ($total > $count);

        // // Lưu dữ liệu vào tệp JSON
        // $trimCate = str_replace(' ', '', $key);
        // $nowInHCM = Carbon::now('Asia/Ho_Chi_Minh');
        // $stringDateTime = $nowInHCM->toIso8601String();
        // $filePath = storage_path('app/dataCraw/didongviet/' . $stringDateTime . '_' . $trimCate . '.json');
        // // Chuyển dữ liệu thành chuỗi JSON và lưu vào tệp
        // file_put_contents($filePath, json_encode($products, JSON_PRETTY_PRINT));

        // // Đọc dữ liệu từ tệp JSON
        // // $readData = json_decode(file_get_contents($filePath), true);
        ScrapeHelper::saveArrayToJsonFile($products, 'storage/ddv.json');
        $count = 0;
        foreach ($products as $product) {
            try {
                if ($this->saveToDatabase($product)){
                    $count++;
                }
                print("Saving " . $count . " " . $key . " from" . $this->_url . "\n");
            } catch (\Exception $e) {
                Log::channel('scrapper')->error($e);
            }
        }
        Log::channel('scrapper')->info("Saved " . $count . " " . $key . " from: " . $this->_url . "\n");
    }


    public function saveToDatabase($product)
    {
        try {
            $catId = $this->getCategoryId($product['category_name']);
            $product['category_id'] = $catId;
            $productSite = $this->_productSiteRepository->getByNameAndSiteId($product['name'], $this->_siteId);

            if ($productSite == null) {
                $productSite = $this->saveToProductSite($product, $catId);
                $this->updateProductMinPrice($productSite);
                $this->_productRepository->updateCountSite($productSite->product_id);
            }
            $this->addImages($product['detailData']['images'], $productSite->id);
            $this->saveToProductAttribute($product['detailData']['attributes'], $productSite->product_id, $catId);
        } catch (\Exception $e) {
            Log::channel('scrapper')->error($e);
            return false;
        }
        return true;
    }

    public function saveToProductAttribute($productAttributes, $productId, $categoryId)
    {
        try {
            foreach ($productAttributes as $key => $value) {
                $attribute = $this->_attributeRepository->getByName($key);
                if (!$attribute) {
                    $attribute = [];
                    $attribute['name'] = $key;
                    $attribute = $this->_attributeRepository->save($attribute);
                }
                $attributeCategory = $this->_attributeCategoryRepository->getByAttributeIdAndCategoryId($attribute->id, $categoryId);
                if (!$attributeCategory) {
                    $attributeCategory = [];
                    $attributeCategory['attribute_id'] = $attribute->id;
                    $attributeCategory['category_id'] = $categoryId;
                    $attributeCategory = $this->_attributeCategoryRepository->save($attributeCategory);
                }
                $newProductAttribute = [];
                $newProductAttribute['product_id'] = $productId;
                $newProductAttribute['attribute_id'] = $attribute->id;
                $newProductAttribute['value'] = $value;
                $this->_productAttributeRepository->save($newProductAttribute);
            }
        } catch (\Exception $e) {
            Log::channel('scrapper')->error($e);
        }
    }


    public function saveToProductSite($product, $catId)
    {
        try {
            $productSite = [];
            $productSite['name'] = $product['name'];
            $productSite['url'] = $product['url'];
            $productSite['price'] = $product['price'];
            $productSite['product_id'] = $this->getProductId($product, $catId);
            $productSite['site_id'] = $this->_siteId;

            $productSite = $this->_productSiteRepository->save($productSite);
            return $productSite;
        } catch (\Exception $e) {
            Log::channel('scrapper')->error($e);
        }
    }

    function saveToProduct($product, $catId)
    {
        try {
            $pro = [];
            $pro['name'] = ScrapeHelper::getPureName($product['name']);
            $pro['brand'] = $product['brand'];
            $pro['public'] = true;
            $pro['active'] = 1;
            $pro['image'] = $product['image'];
            $pro['min_price'] = $product['price'];
            $pro['min_price_site_id'] = intval($this->_siteId);
            $pro['category_id'] = $catId;
            $pro['created_by'] = 'batch';
            $pro['count_site'] = 0;
            return $this->_productRepository->save($pro);
        } catch (\Exception $e) {
            Log::channel('scrapper')->error($e);
        }
    }

    function addImages($productImages, $productSiteId)
    {
        foreach ($productImages as $productImage) {
            $this->_productImageRepository->save($productSiteId, $productImage);
        }
    }

    function getCategoryName($nameFromWeb)
    {
        switch ($nameFromWeb) {
            case 'dien-thoai':
                return 'Phone';
            case 'may-tinh-bang':
                return 'Tablet';
            case 'dong-ho-thong-minh':
                return 'Smart Watch';
            case 'laptop':
                return 'Laptop';
            case 'apple-macbook-imac':
                return 'Laptop';
            default:
                return 'Other';
        }
    }

    public function getCategoryId($name)
    {
        $category = $this->_categoryRepository->getByName($name);
        if ($category) {
            return $category->id;
        }
        $category = [];
        $category['name'] = $name;
        $category['created_by'] = 'batch';
        $category['updated_by'] = 'batch';
        $new_cate = $this->_categoryRepository->save($category);
        return $new_cate->id;
    }

    public function getDetail($url, $product)
    {
        $client = new Client();
        $response = $client->request('GET', $url);
        $body = $response->getBody()->getContents();
        $body = json_decode($body, true);
        $attributeArr = [];
        $images = [];

        // Get attributes
        foreach ($body['data']['productFeatures'] as $attribute) {
            foreach ($attribute['catalog_feature_details'] as $key => $detail) {
                $attributeArr[$detail['detail_name']] = $detail['value'];
            }
        }
        $attributeArr['titleDescription'] = $body['data']['short_description'];
        $attributeArr['description'] = $body['data']['promo_text'];

        $product['detailData']['attributes'] = $attributeArr;
        // Get images
        foreach ($body['data']['images'] as $image) {
            $images[] = $this->_imageUrlPattern . $image;
        }
        for ($i = count($images); $i < 5; $i++) {
            $images[] = "https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png";
        }
        $images = array_slice($images, 0, 4);

        $product['detailData']['images'] = $images;

        return $product;
    }

    function getProductId($product, $catId)
    {
        $p = $this->_productRepository->getByName(ScrapeHelper::getPureName($product['name']));
        if ($p) {
            return $p->id;
        }
        $newProduct = $this->saveToProduct($product, $catId);
        return $newProduct->id;
    }

    function convertPriceToInt($price)
    {
        $price = str_replace(['.', '₫'], '', $price); // remove dots and currency symbol
        return $price; // convert to integer
    }




    public function updateProductMinPrice($productSite)
    {
        $product = $this->_productRepository->getByName(ScrapeHelper::getPureName($productSite['name']));
        if ($product->min_price > $productSite['price'] || $product->min_price == null) {
            $this->_productRepository->updateMinPrice($productSite['product_id'], $productSite['price'], $productSite['site_id']);
        }
    }



    public function extractBrandName($productName)
    {
        $prefixes = [
            'smartwatch', 'smartband', 'smart watch', 'smart band',
            'đồng hồ thông minh', 'vòng đeo tay thông minh', 'đồng hồ thể thao thông minh',
        ];

        $productNameLower = mb_strtolower($productName, 'UTF-8');

        $productNameLower = preg_replace('/\s+/', ' ', $productNameLower);

        $words = explode(' ', $productNameLower);

        foreach ($words as $key => $word) {
            foreach ($prefixes as $prefix) {
                $prefix = mb_strtolower($prefix, 'UTF-8');
                $prefixLength = strlen($prefix);

                if (mb_substr($word, 0, $prefixLength) === $prefix) {
                    $nextWordIndex = $key + 1;

                    if (isset($words[$nextWordIndex])) {
                        return ucwords($words[$nextWordIndex]);
                    }
                }
            }
        }

        return ucwords($words[0]);
    }

    function getBrandName($productName)
    {
        $brandName = '';
        $productName = strtolower($productName);
        $brandList = array('apple', 'honor', 'vertu', 'garmin', 'redmi', 'amazfit', 'myalo', 'garmin', 'fitbit',
            'samsung', 'xiaomi', 'huawei', 'oppo', 'vivo', 'nokia', 'sony', 'lg', 'htc', 'motorola', 'lenovo', 'asus',
            'oneplus', 'realme', 'google', 'blackberry', 'philips', 'panasonic', 'toshiba', 'fujitsu', 'acer', 'dell',
            'hp', 'ibm', 'compaq', 'gateway', 'emachines', 'sonicwall', 'netgear', 'cisco', 'linksys', 'd-link',
            'tp-link', 'zyxel', 'belkin', '3com', 'intel', 'amd', 'nvidia', 'msi', 'asrock', 'gigabyte', 'evga', 'corsair',
            'kingston', 'crucial', 'sandisk', 'seagate', 'western digital', 'toshiba', 'hitachi', 'samsung', 'lg', 'sony',
            'philips', 'panasonic', 'bose', 'jbl', 'sonos', 'beats', 'sennheiser', 'plantronics', 'logitech', 'microsoft',
            'razer', 'steelseries', 'corsair', 'coolermaster', 'thermaltake', 'nzxt', 'fractal design', 'lian li', 'antec',
            'silverstone', 'bitfenix', 'cooler master', 'rosewill', 'in win', 'seasonic', 'evga', 'corsair', 'thermaltake',
            'be quiet!', 'xfx', 'super flower', 'enermax', 'fractal design', 'silverstone', 'corsair', 'nzxt', 'cooler master',
            'bitfenix', 'phanteks', 'lian li', 'antec', 'evga', 'seasonic', 'thermaltake', 'be quiet!', 'xfx', 'super flower',
            'enermax', 'fractal design', 'silverstone', 'corsair', 'nzxt', 'cooler master', 'bitfenix', 'phanteks', 'lian li',
            'antec', 'evga', 'seasonic', 'thermaltake', 'be quiet!', 'xfx', 'super flower', 'enermax', 'fractal design',
            'silverstone', 'corsair', 'nzxt', 'cooler master', 'bitfenix', 'phanteks', 'lian li', 'antec', 'evga', 'seasonic',
            'thermaltake', 'be quiet!', 'xfx', 'super flower', 'enermax', 'fractal design', 'silverstone', 'corsair', 'nzxt',
            'cooler master', 'bitfenix', 'phanteks', 'lian li', 'antec', 'evga', 'seasonic', 'thermaltake', 'be quiet!', 'xfx',
            'super flower', 'enermax', 'fractal design', 'silverstone', 'corsair', 'nzxt', 'cooler master', 'bitfenix', 'phanteks',
            'lian li', 'antec', 'evga', 'seasonic', 'thermaltake', 'be quiet!', 'xfx', 'super flower', 'enermax', 'fractal design',
            'silverstone', 'corsair', 'nzxt', 'cooler master', 'bitfenix', 'phanteks', 'lian li', 'antec', 'evga', 'seasonic',
            'thermaltake', 'be quiet!', 'xfx', 'super flower', 'enermax', 'fractal design', 'silverstone', 'corsair', 'nzxt',
            'cooler master', 'bitfenix', 'phanteks', 'lian li', 'antec', 'evga', 'seasonic', 'thermaltake', 'be quiet!', 'xfx',
            'super flower', 'enermax', 'fractal design', 'silverstone', 'corsair', 'nzxt', 'cooler master', 'bitfenix', 'phanteks',
            'lian li', 'antec', 'evga', 'seasonic', 'thermaltake', 'be quiet!', 'xfx', 'super flower', 'enermax', 'fractal design',
            'silverstone', 'corsair', 'nzxt', 'cooler master', 'bitfenix', 'phanteks', 'lian li', 'antec', 'evga', 'seasonic',
            'thermaltake', 'be quiet!', 'xfx', 'super flower', 'enermax');

        foreach ($brandList as $brand) {
            // tra ve vi tri chuoi con $brand xuat hien trong $productName
            if (strpos($productName, $brand) !== false) {
                $brandName = $brand;
                break;
            }
        }

        $words = explode(' ', $productName);

        if (($words[0]) == 'iphone' || ($words[0]) == 'ipad' || ($words[0]) == 'ipod' || ($words[0]) == 'macbook') {
            $brandName = 'apple';
        } elseif (($words[0]) == 'metavertu' || ($words[0]) == 'ivertu') {
            $brandName = 'vertu';
        }

        return $brandName;
    }

    function updateByUrl($key, $url)
    {
        $client = new Client();
        $total = -1;
        $totalCurrent = -1;
        $i = 0;
        $products = [];
        $count = 0;
        do {
            $response = $client->request('GET', $url . $i);
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);

            $total = $body['data']['paging']['total'];
            $totalCurrent = $body['data']['paging']['currentPage'];
            $listProducts = $body['data']['data'];

            foreach ($listProducts as $productItem) {

                try {
                    $product = [];

                    $product['name'] = $productItem['product'];
                    $product['price'] = $productItem['price'];
                    if ($product['price'] == 0) {
                        continue;
                    }
                    $product['site_id'] = $this->_siteId;
                    $products[] = $product;

                    print("Updating " . count($products) . " " . $key . " from: " . $this->_url . "\n");
                    $count++;
                } catch (\Exception $e) {
                    Log::channel('scrapper')->error($e);
                }
            }
            $i++;
        } while ($total > $count);

        ScrapeHelper::saveArrayToJsonFile($products, 'storage/prices/ddv-update.json');
        $count = 0;
        foreach ($products as $product) {
            try {
                $dbProduct = $this->_productSiteRepository->getByNameAndSiteId($product['name'], $this->_siteId);
                if ($dbProduct && $dbProduct->price != $product['price']) {
                    $this->_productSiteRepository->updatePrice($dbProduct->id, $product['price']);
                    print("Updated " . $count . " " . $key . " from" . $this->_url . "\n");
                    $count++;
                }
            } catch (\Exception $e) {
                Log::channel('scrapper')->error($e);
            }
        }
        Log::channel('scrapper')->info("Updated " . $count . " " . $key . " from: " . $this->_url . "s\n");
    }

}
