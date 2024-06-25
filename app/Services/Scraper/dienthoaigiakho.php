<?php

namespace App\Services\Scraper;

use App\Helpers\ScrapeHelper;
use App\Repositories\AttributeCategoryRepository;
use App\Repositories\AttributeRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductAttributeRepository;
use App\Repositories\ProductImageRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductSiteRepository;
use App\Repositories\SiteRepository;
use App\Services\Scraper\IScrape;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Product;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class dienthoaigiakho implements IScrape
{
    private $_url = 'https://dienthoaigiakho.vn';
    private $_siteId = "6";
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
            'mobile' => 'https://api.dienthoaigiakho.vn/api/dien-thoai?&limit=20&offset=',
            'tablet' => 'https://api.dienthoaigiakho.vn/api/may-tinh-bang?&limit=20&offset=',
            'laptop' => 'https://api.dienthoaigiakho.vn/api/laptop?&limit=20&offset=',
            'smartwatch' => 'https://api.dienthoaigiakho.vn/api/dong-ho?&limit=20&offset=',
        ];
        if ($isUpdate) {
            foreach ($urlPatterns as $key => $urlPattern) {
                $this->updateByUrl($key, $urlPattern);
            }
            return;
        }
        if (!$isFetch) {
            $products = ScrapeHelper::loadArrayFromJsonFile('storage/dtgk.json');
            foreach ($products as $product) {
                try {
                    $this->saveToDatabase($product);
                } catch (\Exception $e) {
                    Log::channel('scrapper')->error($e);
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
        $client = new Client([
        ]);
        $products = [];
        $i = 1;
        do {

            $offset = 20 * $i;
            $response = $client->request('GET', $url . $offset);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);
            $total = $body['count'];
            $listProducts = $body['rows'];
            foreach ($listProducts as $productItem) {
                $product = [];
                $product["id"] = $productItem["id"];
                $product['category_name'] = $this->getCategoryName($key);
                $product['category_id'] = $this->getCategoryId($product['category_name']);
                $product['name'] = $productItem['name'];
                $product['url'] = $this->_url . "/" . $productItem['uri'];
                $product['price'] = $productItem['promoPrice'];
                $product['site_id'] = $this->_siteId;
                $product['image'] = $productItem['productPhoto'];

                $detailAPI = 'https://api-specs.dienthoaigiakho.vn/products/' . $productItem['id'];
                $product['detailData'] = [];
                try {
                    $product['detailData'] = $this->getDetailData($detailAPI);
                    $product['detailData']['images'] = $this->getImage($productItem);
                } catch (\Exception $e) {
                    Log::channel('scrapper')->error($e);
                }
                $product['brand'] = $productItem['brand'];
                try {
                    if (stripos($productItem['name'], "Cũ") === false) {
                        $products[] = $product;
                        print("fetch: " . count($products) . " " . $product['category_name'] . "s\n");
                    }
                } catch (\Exception $e) {
                    Log::channel('scrapper')->error($e);
                }

            }
            $i++;

        } while ($total > $offset);
        ScrapeHelper::saveArrayToJsonFile($products, 'storage/dtgk.json');
        $j = 1;
        foreach ($products as $product) {
            try {
                if ($this->saveToDatabase($product)) {
                    $j++;
                };
            } catch (\Exception $e) {
                Log::channel('scrapper')->error($e);
            }
        }
        Log::channel('scrapper')->info("Saved " . $j . " into db." . "\n");
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
        if (!empty($product['detailData'])) {
            $this->addImages($product['detailData']['images'], $productSite->id);
            $this->saveToProductAttribute($product['detailData']['attributes'], $productSite->product_id, $catId);
        }
        } catch (\Exception $e) {
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
        $pro['category_id'] = $catId;
        $pro['created_by'] = 'batch';
        $pro['count_site'] = 0;
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
            case 'mobile':
                return 'Phone';
            case 'tablet':
                return 'Tablet';
            case 'smartwatch':
                return 'Smart Watch';
            case 'laptop':
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

        $dataDetail = [];
        $attributeArr = [];
        foreach ($datas['data']['specs'] as $attribute) {
            $attributeArr[$attribute['name']] = $attribute['specValues'][0]['name'];
        }
        $dataDetail = [
            "attributes" => $attributeArr
        ];
        return $dataDetail;
    }

    public function getImage($productItem)
    {
        $images = [];
        try {
            foreach ($productItem['pOptions'][0]['images'] as $image) {
                $images[] = $image;
            }
            $images = array_slice($images, 0, 4);


        } catch (\Exception $e) {
            Log::channel('scrapper')->error($e);
        }
        return $images;
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

    function updateByUrl($key, $url)
    {
        $client = new Client();
        $products = [];
        $i = 1;
        do {

            $offset = 20 * $i;
            $response = $client->request('GET', $url . $offset);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);
            $total = $body['count'];
            $listProducts = $body['rows'];
            foreach ($listProducts as $productItem) {
                $product = [];
                $product['name'] = $productItem['name'];
                $product['price'] = $productItem['promoPrice'];
                $product['site_id'] = $this->_siteId;

                try {
                    if (stripos($productItem['name'], "Cũ") === false) {
                        $products[] = $product;
                        print("updating " . count($products) . "products\n");
                    }
                } catch (\Exception $e) {
                    Log::channel('scrapper')->error($e);
                }

            }
            $i++;

        } while ($total > $offset);
        ScrapeHelper::saveArrayToJsonFile($products, 'storage/prices/dtgk-update.json');
        $j = 1;
        foreach ($products as $product) {
            try {
                $dbProduct = $this->_productSiteRepository->getByNameAndSiteId($product['name'], $this->_siteId);
                if ($dbProduct && $dbProduct->price != $product['price']) {
                    $this->_productSiteRepository->updatePrice($dbProduct->id, $product['price']);
                    $j++;
                }
            } catch (\Exception $e) {
                Log::channel('scrapper')->error($e);
            }
        }
        Log::channel('scrapper')->info("Updated " . $j . " into db." . "\n");
    }

}