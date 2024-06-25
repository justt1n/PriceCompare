<?php

namespace App\Scraper;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Product;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Scraper\IScrape;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class tgdd implements IScrape
{
    public function scrape()
    {
        $urlPatterns = [
             'mobile' => 'https://www.thegioididong.com/Category/FilterProductBox?c=42&o=17&pi=',
            'laptop' => 'https://www.thegioididong.com/Category/FilterProductBox?c=44&o=17&pi=',
            'tablet' => 'https://www.thegioididong.com/Category/FilterProductBox?c=522&o=17&pi=',
            'smartwatch' => 'https://www.thegioididong.com/Category/FilterProductBox?c=7077&o=17&pi=',
        ];

        foreach ($urlPatterns as $urlPattern) {
            $this->scrapeByUrl($urlPattern);
        }
    }

    function scrapeByUrl($url)
    {
        $client = new Client();
        $total = -1;
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
            $crawler->filter('li.item')->each(function (Crawler $node) {
                try {
                    $node = $node->filter('a.main-contain');
                    $product = new Product();
                    $product->category_id = $this->getCategoryId($node->attr('data-cate'));
                    
                    $product->name = trim(str_replace($node->attr('data-cate'), '', $node->attr('data-name')));
                    
                    $product->brand = $node->attr('data-brand');
                    $product->public = 1;
                    $product->image = $node->filter('div.item-img img.lazyload')->attr('data-src');
                    if($product->image == null)
                        $product->image = 'none';

                    $product->count_site = $this->getCountSites('url');
                    $product->min_price = $node->attr('data-price');

                    $product->min_price_site_id = $this->getMinPriceSiteId('url');


                    $product->created_by = 1;
                    $product->updated_by = 1;
                    $product->deleted_by = 1;
                    DB::enableQueryLog(); // Enable query log

                    // Your existing code...
                    $product->save();

                    $log = DB::getQueryLog(); // Get query log

                    $lastQuery = end($log); // Get the last executed query

                } catch (\Exception $e) {
                    \Log::error($e->getMessage());
                }
            });
            $i++;
        } while ($total > 0);
    }

    function getCategoryId($name)
    {
        $category = Category::where('name', $name)->first();
        if ($category) {
            return $category->id;
        }
        $category = new Category();
        $category->name = $name;
        $category->created_by = 1;
        $category->updated_by = 1;
        $category->deleted_by = 1;
        $category->save();
        return $category->id;
    }

    function getCountSites($url)
    {
        return 0;
    }

    function getMinPriceSiteId($url)
    {
        return 0;
    }
}

