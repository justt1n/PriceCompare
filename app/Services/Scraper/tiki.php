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
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Product;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;

class tiki implements IScrape
{
    private $_url = 'https://tiki.vn';
    private $_siteId = "3";
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
            'mobile' => $this->_url . '/api/personalish/v1/blocks/listings?limit=40&category=1795&page=',
            'tablet' => $this->_url . '/api/personalish/v1/blocks/listings?limit=40&category=1794&page=',
            'laptop' => $this->_url . '/api/personalish/v1/blocks/listings?limit=40&category=8095&page=',
            'smartwatch' => $this->_url . '/api/personalish/v1/blocks/listings?limit=40&category=1778&page=',
        ];
        if ($isUpdate) {
            foreach ($urlPatterns as $key => $urlPattern) {
                $this->updateByUrl($key, $urlPattern);
            }
            return;
        }

        if (!$isFetch) {
            $products = ScrapeHelper::loadArrayFromJsonFile('storage/tiki.json');
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
        $client = new Client();
        $total = -1;
        $totalCurrent = -1;
        $i = 0;
        $products = [];
        do {
            $response = $client->request('GET', $url . $i);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);

            $total = $body['paging']['total'];
            $totalCurrent = $body['paging']['to'];
            $listProducts = $body['data'];
            foreach ($listProducts as $productItem) {
                $product = [];
                $detailAPI = 'https://tiki.vn/api/v2/products/' . $productItem['id'] . "?platform=web&spid=248762053&version=3";
                $product['category_name'] = $this->getCategoryName($key);
                $product['category_id'] = $this->getCategoryId($product['category_name']);
                $product['name'] = $productItem['name'];
                $product['url'] = $this->_url . "/" . $productItem['url_path'];
                $product['price'] = $productItem['price'];
                $product['site_id'] = $this->_siteId;
                $product['image'] = $productItem['thumbnail_url'];

                try {
                    $product['detailData'] = $this->getDetailData($detailAPI);
                } catch (\Exception $e) {
                    Log::channel('scrapper')->error($e);
                }
                $product['brand'] = $productItem['brand_name'];
                try {
                    if ($product['detailData']['isAuthentic']['value'] == "Có") {
                        $products[] = $product;
                        print("fetch: " . count($products) . " " . $product['category_name'] . "s\n");
                    }
                } catch (\Throwable $th) {
                    Log::channel('scrapper')->error($e);
                }

            }

            $i++;
        } while ($total > $totalCurrent);
        ScrapeHelper::saveArrayToJsonFile($products, 'storage/tiki.json');
        $j = 1;
        foreach ($products as $product) {
            try {
                if ($this->saveToDatabase($product)) {
                    $j++;
                }
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
        $pro['name'] = ScrapeHelper::getPureName($this->preProcessName($product['name']));
        $pro['brand'] = $product['brand'];
        $pro['public'] = true;
        $pro['active'] = 1;
        $pro['image'] = $product['image'];
        $pro['min_price'] = $product['price'];
        $pro['count_site'] = 0;
        $pro['min_price_site_id'] = intval($this->_siteId);
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

        $images = [];
        $isAuthentic = [];
        $dataDetail = [];
        $attributeArr = [];
        $description = null;
        $titleDescription = null;
        $titleDescription = $datas['short_description'];
        $description = $datas['description'];
        foreach ($datas['images'] as $image) {
            $images[] = $image['base_url'];
        }
        $images = array_slice($images, 0, 4);
        foreach ($datas['specifications'][0]['attributes'] as $attribute) {
            if ($attribute['code'] == "is_authentic") {
                $isAuthentic = $attribute;
            }
            $attributeArr[$attribute['name']] = $attribute['value'];
            $attributeArr['description'] = $description;
            $attributeArr['titleDescription'] = $titleDescription;
        }
        $dataDetail = [
            "images" => $images,
            "isAuthentic" => $isAuthentic,
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
        if ($productSite['price'] == null)
            $productSite['price'] = 0;
        $product = $this->_productRepository->getByName(ScrapeHelper::getPureName($productSite['name']));
        if ($product == null)
            return;
        if ($product->min_price > $productSite['price'] || $product->min_price == null) {
            $this->_productRepository->updateMinPrice($productSite['product_id'], $productSite['price'], $productSite['site_id']);
        }
    }





    function preProcessName($name)
    {
        $name = mb_strtolower($name);
        $removeItems = ['chính hãng', 'nam', 'nữ', 'laptop', 'máy tính bảng', 'điện thoại', 'đồng hồ thông minh ', 'đồng hồ', 'win 10', 'win 11', 'xanh', 'cam', 'xám', 'vàng', 'đen', 'full box'];
        $name = str_replace($removeItems, '', $name);
        $name = explode('-', $name);
        $name = $name[0];
        $name = explode("(", $name);
        $name = $name[0];
        $name = explode(",", $name);
        $name = $name[0];
        return $name;
    }

    function updateByUrl($key, $url)
    {
        $client = new Client();
        $total = -1;
        $totalCurrent = -1;
        $i = 0;
        $products = [];
        do {
            $response = $client->request('GET', $url . $i);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);

            $total = $body['paging']['total'];
            $totalCurrent = $body['paging']['to'];
            $listProducts = $body['data'];
            foreach ($listProducts as $productItem) {
                $detailAPI = 'https://tiki.vn/api/v2/products/' . $productItem['id'] . "?platform=web&spid=248762053&version=3";
                $product = [];
                $product['category_name'] = $this->getCategoryName($key);
                $product['name'] = $productItem['name'];
                $product['price'] = $productItem['price'];
                $product['site_id'] = $this->_siteId;
                // try {
                //     $product['detailData'] = $this->getDetailData($detailAPI);
                // } catch (\Exception $e) {
                //     Log::channel('scrapper')->error('' . $e->getMessage());
                // }
                try {
                    //     if ($product['detailData']['isAuthentic']['value'] == "Có") {
                    $products[] = $product;
                    print("Updating " . count($products) . " " . $product['category_name'] . "s\n");
                    Log::channel('scrapper')->info("Updating " . count($products) . " " . $product['category_name'] . "s\n");
                } catch (\Exception $th) {
                    Log::channel('scrapper')->error('' . $th->getMessage());
                }

            }

            $i++;
        } while ($total > $totalCurrent);
        ScrapeHelper::saveArrayToJsonFile($products, 'storage/tiki.json');
        $j = 1;
        foreach ($products as $product) {
            try {
                $dbProduct = $this->_productSiteRepository->getByNameAndSiteId($product['name'], $this->_siteId);
                if ($dbProduct && $dbProduct->price != $product['price']) {
                    $this->_productSiteRepository->update($dbProduct->id, $product);
                    print("Updated " . count($products) . " " . $product['category_name'] . "s \n");
                    $j++;
                    
                }
            } catch (\Exception $e) {
                Log::channel('scrapper')->error($e);
            }
        }
        Log::channel('scrapper')->info('scrapper')->info("Updated " . $j . " " . $product['category_name'] . "s\n");
    }
}