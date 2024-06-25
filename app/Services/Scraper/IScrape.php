<?php
namespace App\Services\Scraper;
interface IScrape
{
    public function scrape($isFetch, $isUpdate);
}

?>