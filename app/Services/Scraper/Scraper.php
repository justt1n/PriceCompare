<?php
namespace App\Services\Scraper;

use App\Services\Scraper\IScrape;

class Scraper
{
    public static function scrape(IScrape $scrape, $isFetch = true, $isUpdate = false)
    {
        $scrape->scrape($isFetch, $isUpdate);
    }
}
?>