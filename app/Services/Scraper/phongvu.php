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

class phongvu implements IScrape
{
    private $_url = 'https://phongvu.vn/';
    private $_siteId = "5";
    private $_imageUrlPattern = '';
    private $_detailUrlPattern = 'https://discovery.tekoapis.com/api/v1/product?location=&terminalCode=phongvu&sku=';

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
            '/c/phone-dien-thoai' => 'https://discovery.tekoapis.com/api/v2/search-skus-v2',
            '/c/may-tinh-bang' => 'https://discovery.tekoapis.com/api/v2/search-skus-v2',
            '/c/dong-ho-thong-minh' => 'https://discovery.tekoapis.com/api/v2/search-skus-v2',
            '/c/laptop' => 'https://discovery.tekoapis.com/api/v2/search-skus-v2',
        ];

        if ($isUpdate) {
            foreach ($urlPatterns as $key => $urlPattern) {
                $this->updateByUrl($key, $urlPattern);
            }
            return;
        }


        if (!$isFetch) {
            $products = ScrapeHelper::loadArrayFromJsonFile('storage/phongvu.json');
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
        $products = [];
        $count = 0;
        $response = $client->request('POST', $url, [
            'json' => [
                'terminalId' => 4,
                'pageSize' => 2000,
                'slug' => $key
            ]
        ]);

        $body = $response->getBody()->getContents();
        $body = json_decode($body, true);
        $listProducts = $body['data']['products'];

        foreach ($listProducts as $productItem) {
            try {
                $product = [];
                $product['category_name'] = $this->getCategoryName($key);
                $product['category_id'] = $this->getCategoryId($product['category_name']);
                $product['name'] = $productItem['name'];
                $product['url'] = $this->_url . "/" . $productItem['canonical'];
                // echo $product['url'] . "\n";
                if ($product['url'] == $this->_url) {
                    continue;
                }
                $product['price'] = $productItem['latestPrice'];
                if ($product['price'] == 0) {
                    continue;
                }
                $product['site_id'] = $this->_siteId;

                if (!empty($productItem['imageUrl'])) {
                    $product['image'] = $productItem['imageUrl'];
                } else {
                    $product['image'] = "https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png";
                }

                $detailAPI = $this->_detailUrlPattern . $productItem['sku'];

                $product = $this->getDetail($detailAPI, $product); // get detail( attributes, images)

                $product['brand'] = $productItem['brandName'];

                $products[] = $product;

                // $this->saveToDatabase($product);
                $count++;
            } catch (\Exception $e) {
                Log::channel('scrapper')->error($e);
            }
        }

        ScrapeHelper::saveArrayToJsonFile($products, 'storage/phongvu.json');
        $count = 0;
        foreach ($products as $product) {
            try {
                if ($this->saveToDatabase($product)) {
                    $count++;
                }
            } catch (\Exception $e) {
                Log::channel('scrapper')->error($e);
            }
        }
        Log::channel('scrapper')->info("Saved " . $count . " " . $product['category_name'] . " from: " . $this->_url . "\n");
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
            $pro['updated_by'] = 'batch';
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
            case '/c/phone-dien-thoai':
                return 'Phone';
            case '/c/may-tinh-bang':
                return 'Tablet';
            case '/c/dong-ho-thong-minh':
                return 'Smart Watch';
            case '/c/laptop':
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
        foreach ($body['result']['product']['productDetail']['attributeGroups'] as $attribute) {
            $attributeArr[$attribute['name']] = $attribute['value'];
            if ($attribute['name'] == 'Tính năng nổi bật') {
                $tmp = explode('-', $attribute['value']);
                foreach ($tmp as $t) {
                    $tmp2 = explode(':', $t);
                    if (count($tmp2) == 2) {
                        $attributeArr[$tmp2[0]] = $tmp2[1];
                    }
                }
            }
        }

        $attributeArr['titleDescription'] = $body['result']['product']['productDetail']['shortDescription'];
        $attributeArr['description'] = $body['result']['product']['productDetail']['description'];

        $product['detailData']['attributes'] = $attributeArr;
        // Get images
        foreach ($body['result']['product']['productDetail']['images'] as $image) {
            $images[] = $image['url'];
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


    function updateByUrl($key, $url)
    {
        $client = new Client();
        $products = [];
        $count = 0;
        $response = $client->request('POST', $url, [
            'json' => [
                'terminalId' => 4,
                'pageSize' => 2000,
                'slug' => $key
            ]
        ]);

        $body = $response->getBody()->getContents();
        $body = json_decode($body, true);
        $listProducts = $body['data']['products'];

        foreach ($listProducts as $productItem) {
            try {
                $product = [];
                $product['category_name'] = $this->getCategoryName($key);
                $product['name'] = $productItem['name'];
                $product['url'] = $this->_url . "/" . $productItem['canonical'];
                if ($product['url'] == $this->_url) {
                    continue;
                }
                $product['price'] = $productItem['latestPrice'];
                if ($product['price'] == 0) {
                    continue;
                }
                $product['site_id'] = $this->_siteId;

                $products[] = $product;

                print("Updating " . count($products) . " " . $product['category_name'] . " from: " . $this->_url . "\n");
                $count++;
                Log::channel('scrapper')->info("updating: " . count($products) . " " . $product['category_name'] . " from: " . $this->_url . "\n");
            } catch (\Exception $e) {
                Log::channel('scrapper')->error($e);
            }
        }

        ScrapeHelper::saveArrayToJsonFile($products, 'storage/prices/phongvu-update.json');
        $count = 0;
        foreach ($products as $product) {
            try {
                $dbProduct = $this->_productRepository->getByName(ScrapeHelper::getPureName($product['name']));
                if ($dbProduct && $dbProduct->min_price > $product['price']) {
                    $this->_productRepository->updateMinPrice($dbProduct->id, $product['price'], $product['site_id']);
                    $count++;
                    print("Updated " . $count . " " . $product['category_name'] . " from: " . $this->_url . "\n");
                    Log::channel('scrapper')->info("Updated " . $count . " " . $product['category_name'] . "s\n");
                }
            } catch (\Exception $e) {
                Log::channel('scrapper')->error($e);
            }
        }
    }


}
