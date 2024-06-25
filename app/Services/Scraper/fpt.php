<?php

namespace App\Services\Scraper;

use App\Helpers\ScrapeHelper;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
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

class fpt implements IScrape
{
    private $_url = 'https://www.fptshop.com.vn';
    private $_siteId = "2";
    private $_imageUrlPattern = 'https://fptshop.com.vn/Uploads/Originals/';
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
            'mobile' => 'https://fptshop.com.vn/apiFPTShop/Product/GetProductList?brandAscii=&url=https%3A%2F%2Ffptshop.com.vn%2Fdien-thoai%3Fsort%3Dban-chay-nhat%26trang%3D',
            'laptop' => 'https://fptshop.com.vn/apiFPTShop/Product/GetProductList?brandAscii=&url=https%3A%2F%2Ffptshop.com.vn%2Fmay-tinh-xach-tay%3Fsort%3Dban-chay-nhat%26trang%3D',
            'tablet' => 'https://fptshop.com.vn/apiFPTShop/Product/GetProductList?brandAscii=&url=https%3A%2F%2Ffptshop.com.vn%2Fmay-tinh-bang%3Fsort%3Dban-chay-nhat%26trang%3D',
            'smartwatch' => 'https://fptshop.com.vn/apiFPTShop/Product/GetProductList?brandAscii=&url=https%3A%2F%2Ffptshop.com.vn%2Fsmartwatch%3Fsort%3Dban-chay-nhat%26trang%3D',
        ];

        if ($isUpdate) {
            foreach ($urlPatterns as $urlPattern) {
                $this->updateByUrl($urlPattern);
            }
            return;
        }

        if (!$isFetch) {
            $products = ScrapeHelper::loadArrayFromJsonFile('storage/fpt.json');
            foreach ($products as $product) {
                try {
                    $this->saveToDatabase($product);
                } catch (\Exception $e) {
                    Log::channel('scrapper')->error($e);
                }
            }
            return;
        }

        foreach ($urlPatterns as $urlPattern) {
            $this->scrapeByUrl($urlPattern);
        }
    }

    function scrapeByUrl($url)
    {
        $client = new Client();
        $total = -1;
        $totalCurrent = -1;
        $i = 1;
        $products = [];
        $count = 0;
        do {
            $response = $client->request('GET', $url . $i);
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);

            $total = $body['datas']['filterModel']['listDefault']['total'];
            $totalCurrent = $body['datas']['filterModel']['listDefault']['totalCurrent'];
            $listProducts = $body['datas']['filterModel']['listDefault']['list'];
            foreach ($listProducts as $productItem) {
                try {
                    $product = [];
                    $product['category_name'] = $this->getCategoryName($productItem['productType']['name']);
                    $product['category_id'] = $this->getCategoryId($product['category_name']);
                    $product['name'] = $productItem['name'];
                    $product['url'] = $this->_url . "/" . $productItem['productType']['nameAscii'] . "/" . $productItem['nameAscii'];
                    $product['price'] = $productItem['productVariant']['price'];
                    $product['site_id'] = $this->_siteId;
                    $product['image'] = $this->_imageUrlPattern . $productItem['urlPicture'];

                    $detailAPI = 'https://fptshop.com.vn/api-data/API_GiaDung/api/Product/AppliancesAPI/GetProductDetail?name=' . $productItem['nameAscii'] . "&url=" . urlencode($product['url']);
                    $product['brand'] = $productItem['brandName'];
                    $product['detailData'] = [];
                    try {
                        $product['detailData'] = $this->getDetailData($detailAPI);
                    } catch (\Exception $e) {
                        \Log::error('' . $e->getMessage());
                    }

                    // $product['attributes'] = $this->getAttributes($detailAPI, $product);
                    // $product = $this->getImagesAndAttributes($detailAPI, $product);
                    if (!empty($product['price'])) {
                        $products[] = $product;
                    }
                    print("Fetching " . $count . " " . $product['category_name'] . "\n");
                    $count++;
                } catch (Exception $e) {
                    Log::channel('scrapper')->error($e);
                }
            }
            $i++;
        } while (count($listProducts) != 0);
        $count = 0;
        ScrapeHelper::saveArrayToJsonFile($products, 'storage/fpt.json');
        foreach ($products as $product) {
            try {
                if($this->saveToDatabase($product)) {
                    $count++;
                }
            } catch (Exception $e) {
                Log::channel('scrapper')->error($e);

            }
        }
        Log::channel('scrapper')->info("Saved " . $count . " " . $product['category_name'] . "\n");
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
            if ($product['detailData'] != [])
                $this->addImages($product['detailData']['images'], $productSite->id);
            $this->saveToProductAttribute($product['detailData']['attributes'], $productSite->product_id, $catId);
        } catch (Exception $e) {
            Log::channel('scrapper')->error($e);
            return false;
        }
        return true;
    }

    public function saveToProductAttribute($productAttributes, $productId, $categoryId)
    {
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
    }


    public function saveToProductSite($product, $catId)
    {
        $productSite = [];
        $productSite['name'] = $product['name'];
        $productSite['url'] = $product['url'];
        $productSite['price'] = $product['price'];
        $productSite['product_id'] = $this->getProductId($product, $catId);
        $productSite['site_id'] = $this->_siteId;

        $productSite = $this->_productSiteRepository->save($productSite);
        return $productSite;
    }

    function saveToProduct($product, $catId)
    {
        $pro = [];
        $pro['name'] = ScrapeHelper::getPureName($product['name']);
        $pro['brand'] = $product['brand'];
        $pro['public'] = true;
        $pro['active'] = 1;
        $pro['image'] = $product['image'];
        $pro['min_price'] = $product['price'];
        $pro['min_price_site_id'] = intval($this->_siteId);
        $pro['count_site'] = 0;
        $pro['category_id'] = $catId;
        $pro['created_by'] = 'batch';
        return $this->_productRepository->save($pro);
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
            case 'Điện thoại':
                return 'Phone';
            case 'Máy tính bảng':
                return 'Tablet';
            case 'Smartwatch':
                return 'Smart Watch';
            case 'Máy tính xách tay':
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


    public function getDetailData($url)
    {
        do {
            $client = new Client();
            $response = $client->request('GET', $url);
            $body = $response->getBody()->getContents();
            $datas = json_decode($body, true);
        } while ($body == null);

        $images = [];
        $dataDetail = [];
        $attributeArr = [];
        $description = null;
        $titleDescription = null;
        $titleDescription = $datas['datas']['model']['product']['description'];
        $description = $datas['datas']['model']['product']['details'];
        foreach ($datas['datas']['model']['product']['productVariant']['listGallery'] as $image) {
            $images[] = $this->_imageUrlPattern . $image['url'];
        }
        $images = array_slice($images, 0, 4);
        foreach ($datas['datas']['model']['product']['productAttributes'] as $attribute) {
            $attributeArr[$attribute['attributeName']] = $attribute['specName'];
            $attributeArr['description'] = $description;
            $attributeArr['titleDescription'] = $titleDescription;
        }
        $dataDetail = [
            "images" => $images,
            "attributes" => $attributeArr
        ];
        return $dataDetail;
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

    function updateByUrl($url)
    {
        $client = new Client();
        $total = -1;
        $totalCurrent = -1;
        $i = 1;
        $products = [];
        do {
            $response = $client->request('GET', $url . $i);
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);

            $total = $body['datas']['filterModel']['listDefault']['total'];
            $totalCurrent = $body['datas']['filterModel']['listDefault']['totalCurrent'];
            $listProducts = $body['datas']['filterModel']['listDefault']['list'];
            foreach ($listProducts as $productItem) {
                try {

                    $product = [];
                    $product['name'] = $productItem['name'];
                    $product['price'] = $productItem['productVariant']['price'];
                    $product['site_id'] = $this->_siteId;
                    $products[] = $product;
                    print("Updating " . count($products) . "products\n");
                } catch (\Exception $e) {
                    Log::channel('scrapper')->error($e);
                }
            }
            $i++;
        } while (count($listProducts) != 0);
        $count = 0;
        ScrapeHelper::saveArrayToJsonFile($products, 'storage/prices/fpt-update.json');
        foreach ($products as $product) {
            try {
                $dbProduct = $this->_productSiteRepository->getByNameAndSiteId($product['name'], $this->_siteId);
                if ($dbProduct && $dbProduct->price != $product['price']) {
                    $this->_productSiteRepository->updatePrice($dbProduct->id, $product['price']);
                    Log::channel('scrapper')->info("Updated " . $count . " " . "products\n");
                    $count++;
                }
            } catch (Exception $e) {
                Log::channel('scrapper')->error($e);
            }
        }
    }

}