<?php

namespace App\Services\Scraper;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Repositories\Product;
use App\Services\Scraper\IScrape;
use App\Repositories\CategoryRepository;
use App\Repositories\SiteRepository;
use App\Repositories\Products\MobileRepository;

class tgdd_old implements IScrape
{
    private $_url = 'https://www.thegioididong.com';
    private $_siteId;
    public function scrape()
    {
        // $siteId = $this->getSiteId($this->url);
        $urlPatterns = [
            'mobile' => 'https://www.thegioididong.com/Category/FilterProductBox?c=42&m=2,1971,5332,2235,2236,17201,1,4832,19,80&o=17&pi=',
            'laptop' => 'https://www.thegioididong.com/Category/FilterProductBox?c=44&o=17&pi=',
            'tablet' => 'https://www.thegioididong.com/Category/FilterProductBox?c=522&o=17&pi=',
            'smartwatch' => 'https://www.thegioididong.com/Category/FilterProductBox?c=7077&o=17&pi=',
        ];
        foreach ($urlPatterns as $urlPattern) {
            $this->getProductURLs($urlPattern);
        }
    }

    function getProductURLs($url)
    {
        $client = new Client([
            'verify' => false,
        ]);
        $total = -1;
        $products = [];
        $i = 0;
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
                    $node = $node->filter('a.main-contain');
                    $product = array();
                    $product['category_name'] = $node->attr('data-cate');
                    $product['url'] = $this->_url . $node->attr('href');
                    $product['name'] = $node->attr('data-name');
                    $productColors = $this->getAdditionUrl($product['url']);
                    if (count($productColors) == 0) {
                        $products[] = $product;
                    }
                    foreach ($productColors as $productColor) {
                        $productColorTmp = array();
                        $productColorTmp['category_name'] = $product['category_name'];
                        $productColorTmp['url'] = $productColor['url'];
                        $productColorTmp['name'] = $product['name'] . ' ' .$productColor['name'];
                        $products[] = $productColorTmp;
                    }
                } catch (\Exception $e) {
                    //throw $th;
                }
                // $product['category_id'] = $this->getCategoryId($product['category_name']);
            });
            $i++;
        } while ($total > 0);
    }
    function getAdditionUrl($url)
    {
        $urls = [];
        $client = new Client([
            'verify' => false,
        ]);
        $respone = $client->request('GET', $url);
        $crawler = new Crawler($respone->getBody()->getContents());
        $crawler->filter('div.color.group a')->each(function (Crawler $node) use (&$urls) {
            try {
                $tmp = [];
                $tmp['url'] = $this->_url . $node->attr('href');
                $tmp['name'] = $node->text();
                $urls[] = $tmp;
            } catch (\Exception $e) {
                //throw $th;
            }
        });
        return $urls;
    }


    // function AddProductByCatName($name, $product)
    // {
    //     $categoryRepository = new CategoryRepository();
    //     $nameList = $categoryRepository->getAll()->pluck('name')->toArray();
    //     switch ($name) {
    //         case $nameList[0]:
    //             // Mobile
    //             $mobile = new MobileRepository();
    //             $mobile->name = $product['name'];
    //             $product['site_id'] = $this->siteId;
    //             break;
    //         case $nameList[1]:
    //             // Laptop
    //             break;
    //         case $nameList[2]:
    //             // Tablet
    //             break;
    //         case $nameList[3]:
    //             // Smartwatch
    //             break;
    //         case $nameList[4]:
    //             // EReader
    //             break;
    //         default:
    //             # code...
    //             break;
    //     }
    // }

    // public static function getCategoryId($name)
    // {
    //     $category = Category::where('name', $name)->first();
    //     if ($category) {
    //         return $category->id;
    //     }
    //     $category = new Category();
    //     $category->name = $name;
    //     $category->created_by = 1;
    //     $category->updated_by = 1;
    //     $category->deleted_by = 1;
    //     $category->save();
    //     return $category->id;
    // }

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

    // public function getSiteId($url)
    // {
    //     $site = Site::where('url', self::getSiteUrl($url))->first();
    //     if ($site) {
    //         return $site->id;
    //     }
    //     $site = new Site();
    //     $site->name = self::getSiteUrl($url);
    //     $site->created_by = 'batch';
    //     $site->updated_by = 'batch';
    //     $site->deleted_by = null;
    //     $site->save();
    //     return $site->id;
    // }










}

