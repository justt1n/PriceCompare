<?php

namespace App\Services\Scraper;

use App\Helpers\ScrapeHelper;
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
use Illuminate\Support\Facades\Log;

class tgdd implements IScrape
{
    private $_url = 'https://www.thegioididong.com';
    private $_siteId = "1";

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
            'mobile' => 'https://www.thegioididong.com/Category/FilterProductBox?c=42&m=2,1971,5332,2235,2236,17201,1,4832,19,80&o=17&pi=',
            'laptop' => 'https://www.thegioididong.com/Category/FilterProductBox?c=44&m=122,29176,128,36246,119,1470,120,37208,118,133,203,32075&o=17&pi=',
            'tablet' => 'https://www.thegioididong.com/Category/FilterProductBox?c=522&m=1028,5203,1101,2246,29147,37840,35263,1226&o=17&pi=',
            'smartwatch' => 'https://www.thegioididong.com/Category/FilterProductBox?c=7077&m=17189,17188,19817,20486,18728,19144,20482,36914,19546,17197,18653,17190,36984&o=17&pi=',
        ];

        if ($isUpdate) {
            foreach ($urlPatterns as $urlPattern) {
                $this->updateByUrl($urlPattern);
            }
            return;
        }

        if (!$isFetch) {
            $TotalProducts = ScrapeHelper::loadArrayFromJsonFile('storage/tgdd.json');
            foreach ($TotalProducts as $product) {
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
        $client = new Client([
            'verify' => false,
        ]);
        $total = -1;
        $products = [];
        $i = 0;
        $products = [];
        do {
            $response = $client->request('POST', $url . $i, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                ],
                'form_params' => [
                    'IsParentCate' => 'False',
                    'IsShowCompare' => 'True',
                    'prevent' => 'true',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);

            $total = $body['total'];
            $body = $body['listproducts'];
            $crawler = new Crawler($body);
            $crawler->filter('li.item')->each(function (Crawler $node) use (&$products) {
                try {
                    $product = array();
                    $atributes = [];
                    $node->filter('div.utility p')->each(function (Crawler $node) use (&$atributes) {
                        $atributes[] = $node->text();
                    });
                    $node = $node->filter('a.main-contain');
                    $node->filter('div.item-compare.gray-bg span')->each(function (Crawler $node) use (&$atributes) {
                        $atributes[] = $node->text();
                    });
                    $product['id'] = $node->attr('data-id');
                    $product['category-name'] = $this->getCategoryName($node->attr('data-cate'));
                    $product['url'] = $this->_url . $node->attr('href');
                    $product['name'] = trim($node->filter('h3')->first()->text());
                    $product['brand'] = $node->attr('data-brand');
                    $product['price'] = $node->attr('data-price');
                    $product['base-price'] = $this->convertPriceToInt($node->filter('p.price-old.black')->first()->text());
                    $urlToGetImages = 'https://www.thegioididong.com/Product/GetGalleryItemInPopup?productId=' . $product['id'] . '&isAppliance=false&galleryType=2&colorId=0';
                    $product['image'] = $node->filter('div.item-img img')->attr('data-src');
                    $product['detailData']['images'] = $this->getImages($urlToGetImages);
                    // $product = $this->addAttribute($product['category-name'], $product, $atributes);
                    $product['detailData'] = $this->getDetail($product['url']);
                    $products[] = $product;


                    print("fetched: " . count($products) . " " . $product['category-name'] . "s\n");
                    Log::channel('scrapper')->info("fetched: " . count($products) . " " . $product['category-name'] . "s");
                } catch (\Exception $e) {
                    Log::channel('scrapper')->error($e);
                }

                // $product['category_id'] = $this->getCategoryId($product['category_name']);

            });
            $i++;
        } while ($total > 0);
        // $products = $this->getTheNakedNameOfProduct($products);
        ScrapeHelper::saveArrayToJsonFile($products, 'storage/tgdd.json');
        $count = 0;
        foreach ($products as $product) {
            try {
                if($this->saveToDatabase($product)) {
                    $count++;
                }
            } catch (\Exception $e) {
                Log::channel('scrapper')->error($e);
            }
        }
        print("Saved: " . $count . " products from " . $url . " \n");
    }

    function getCategoryName($nameFromWeb)
    {
        switch ($nameFromWeb) {
            case 'Điện thoại':
                return 'Phone';
            case 'Máy tính bảng':
                return 'Tablet';
            case 'Đồng hồ thông minh':
                return 'Smart Watch';
            case 'Laptop':
                return 'Laptop';
            default:
                return 'Other';
        }
    }
    function convertPriceToInt($price)
    {
        $price = str_replace(['.', '₫'], '', $price); // remove dots and currency symbol
        return $price; // convert to integer
    }

    public function addAttribute($category, $product, $atributes)
    {
        switch ($category) {
            case 'Phone':
                return $this->addMobileAttribute($product, $atributes);
            case 'Tablet':
                return $this->addTabletAttribute($product, $atributes);
            case 'Laptop':
                return $this->addLaptopAttribute($product, $atributes);
            case 'Smart Watch':
                return $this->addSmartWatchAttribute($product, $atributes);
            default:
                return -1;
        }
    }

    public function addMobileAttribute($product, $atributes)
    {
        $product['detailData']['attributes']['Chip'] = $atributes[0];
        $product['detailData']['attributes']['Ram'] = $atributes[1];
        $product['detailData']['attributes']['Rom'] = $atributes[2];
        $product['detailData']['attributes']['Front-camera'] = $atributes[4];
        $product['detailData']['attributes']['Rear-camera'] = $atributes[3];
        $product['detailData']['attributes']['Pin'] = $atributes[5];
        $product['detailData']['attributes']['Screen-size'] = $atributes[6];
        $product['detailData']['attributes']['Screen-res'] = $atributes[7];
        return $product;
    }

    public function addTabletAttribute($product, $atributes)
    {
        $product['detailData']['attributes']['Chip'] = $atributes[0];
        $product['detailData']['attributes']['Ram'] = $atributes[1];
        $product['detailData']['attributes']['Rom'] = $atributes[2];
        $product['detailData']['attributes']['Pin'] = array_pop($atributes);
        return $product;
    }

    public function addLaptopAttribute($product, $atributes)
    {
        $product['detailData']['attributes']['Display'] = $atributes[0];
        $product['detailData']['attributes']['Chip'] = $atributes[1];
        $product['detailData']['attributes']['Vga'] = $atributes[2];
        $product['detailData']['attributes']['Pin'] = $atributes[3];
        $product['detailData']['attributes']['Weight'] = $atributes[4];
        return $product;
    }

    public function addSmartWatchAttribute($product, $atributes)
    {
        $product['detailData']['attributes']['Display'] = $atributes[0];
        $product['detailData']['attributes']['Technologies'] = $atributes[1];
        $product['detailData']['attributes']['Notification-Type'] = $atributes[2];
        return $product;
    }


    public function saveToDatabase($product)
    {
        try {
            $catId = $this->getCategoryId($product['category-name']);
            $product['category_id'] = $catId;
            $productSite = $this->_productSiteRepository->getByNameAndSiteId($product['name'], $this->_siteId);
            if ($productSite == null) {
                $productSite = $this->saveToProductSite($product, $catId);
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

    public function getImages($url)
    {
        $client = new Client([
            'verify' => false,
        ]);
        $response = $client->request('GET', $url);
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $crawler = new Crawler($body);
        $images = [];
        $crawler->filter('div.content-t__list img')->each(function (Crawler $node) use (&$images) {
            try {
                if (!is_null($node->attr('src')))
                    $images[] = $node->attr('src');
            } catch (\Exception $e) {
                Log::channel('scrapper')->error($e);
            }
        });
        $images = array_slice($images, 0, 4);
        return $images;
    }

    public function getDetail($url)
    {
        $client = new Client([
            'verify' => false,
        ]);
        $response = $client->request('GET', $url);
        $body = $response->getBody()->getContents();
        $crawler = new Crawler($body);
        // $images = [];
        // $crawler->filter('div.show-tab.active[data-gallery-id]=color-images-gallery')->each(function (Crawler $node) use (&$images) {
        //     try {
        //         if (!is_null($node->attr('src')))
        //             $images[] = $node->attr('src');
        //     } catch (\Exception $e) {
        //         //throw $th;
        //         Log::channel('scrapper')->error($e);
        //     }
        // });
        // $images = array_slice($images, 0, 4);

        $atributes = [];
        $crawler->filter('ul.parameter__list li')->each(function (Crawler $node) use (&$atributes) {
            try {
                $key = $node->filter('p.lileft')->first()->text();
                $value = $node->filter('div.liright span')->first()->text();
                $atributes[$key] = $value;
            } catch (\Exception $e) {
                Log::channel('scrapper')->error($e);
            }
        });
        $details = [
            'attributes' => $atributes
        ];
        return $details;
    }

    public function saveToProductSite($product, $catId)
    {
        $productSite = [];
        $productSite['name'] = $product['name'];
        $productSite['url'] = $product['url'];
        $productSite['price'] = $product['price'];
        $productSite['product_id'] = $this->getProductId($product, $catId);
        $productSite['site_id'] = $this->_siteId;
        $this->updateProductMinPrice($productSite);
        $productSite = $this->_productSiteRepository->save($productSite);
        return $productSite;
    }

    public function updateProductMinPrice($productSite)
    {
        $product = $this->_productRepository->getByName(ScrapeHelper::getPureName($productSite['name']));
        if ($product->min_price > $productSite['price'] || $product->min_price == null) {
            $this->_productRepository->updateMinPrice($productSite['product_id'], $productSite['price'], $productSite['site_id']);
        }
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

    function addImages($productImages, $productSiteId)
    {
        foreach ($productImages as $productImage) {
            $this->_productImageRepository->save($productSiteId, $productImage);
        }
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

    public function getCategoryId($name)
    {
        $category = $this->_categoryRepository->getByName($name);
        if ($category) {
            return $category->id;
        }
        $category = [];
        $category['name'] = $name;
        $category['created_by'] = 1;
        $category['updated_by'] = 1;
        $new_cate = $this->_categoryRepository->save($category);
        return $new_cate->id;
    }

    // public function getCountSites($productId)
    // {
    //     $product = Product::find($productId);
    //     return $product ? $product->count_site : null;
    // }

    // public function getMinPriceSiteId($productId)
    // {
    //     $product = Product::find($productId);
    //     return $product ? $product->count_site : null;
    // }

    // public function getSiteUrl($url)
    // {
    //     $tmp = explode('/', $url);
    //     return $tmp[2];
    // }


    function preProcessName($name)
    {
        $name = mb_strtolower($name);
        $removeItems = ['laptop', 'máy tính bảng', 'điện thoại', 'đồng hồ thông minh ', 'đồng hồ', 'win 10', 'win 11', 'xanh', 'cam', 'xám', 'vàng', 'đen'];
        $name = str_replace($removeItems, '', $name);
        $name = explode('-', $name);
        $name = $name[0];
        $name = explode("(", $name);
        $name = $name[0];
        $name = explode(",", $name);
        $name = $name[0];
        return $name;
    }
    function updateByUrl($url)
    {
        $client = new Client([
            'verify' => false,
        ]);
        $total = -1;
        $products = [];
        $i = 0;
        $products = [];
        do {
            $response = $client->request('POST', $url . $i, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                ],
                'form_params' => [
                    'IsParentCate' => 'False',
                    'IsShowCompare' => 'True',
                    'prevent' => 'true',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);

            $total = $body['total'];
            $body = $body['listproducts'];
            $crawler = new Crawler($body);
            $crawler->filter('li.item')->each(function (Crawler $node) use (&$products) {
                try {
                    $product = array();
                    $atributes = [];
                    $node->filter('div.utility p')->each(function (Crawler $node) use (&$atributes) {
                        $atributes[] = $node->text();
                    });
                    $node = $node->filter('a.main-contain');
                    $node->filter('div.item-compare.gray-bg span')->each(function (Crawler $node) use (&$atributes) {
                        $atributes[] = $node->text();
                    });
                    $product['category-name'] = $this->getCategoryName($node->attr('data-cate'));
                    $product['name'] = trim($node->filter('h3')->first()->text());
                    $product['price'] = $node->attr('data-price');
                    $product['site_id'] = $this->_siteId;
                    $products[] = $product;


                    print("updating: " . count($products) . " " . $product['category-name'] . "s\n");
                    Log::channel('scrapper')->info("updateing: " . count($products) . " " . $product['category-name'] . "s");
                } catch (\Exception $e) {
                    Log::channel('scrapper')->error($e);
                }

                // $product['category_id'] = $this->getCategoryId($product['category_name']);

            });
            $i++;
        } while ($total > 0);
        // $products = $this->getTheNakedNameOfProduct($products);
        ScrapeHelper::saveArrayToJsonFile($products, 'storage/prices/tgdd-update.json');
        $count = 0;
        foreach ($products as $product) {
            try {
                $dbProduct = $this->_productSiteRepository->getByNameAndSiteId($product['name'], $this->_siteId);
                if ($dbProduct && $dbProduct->price != $product['price']) {
                    $this->_productSiteRepository->updatePrice($dbProduct->id, $product['price']);
                    print("Updated " . $count . " " . $product['category_name'] . " from: " . $this->_url . "\n");
                    Log::channel('scrapper')->info("Updated " . $count . " " . $product['category_name'] . "s\n");
                    $count++;
                }
            } catch (\Exception $e) {
                Log::channel('scrapper')->error($e);
            }
        }
        print("Updated: " . $count . " products from " . $url . " \n");
    }

}

