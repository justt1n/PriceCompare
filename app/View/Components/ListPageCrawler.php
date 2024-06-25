<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ListPageCrawler extends Component
{
    /**
     * Create a new component instance.
     */
    public $dataProductSite;
    public $numberSite;
    public $dataProduct;
    public $totalFilterProductSite;
    public function __construct($dataProductSite,$numberSite,$dataProduct,$totalFilterProductSite)
    {
        $this->dataProductSite = $dataProductSite;
        $this->numberSite = $numberSite;
        $this->dataProduct = $dataProduct;
        $this->totalFilterProductSite = $totalFilterProductSite;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.list-page-crawler')->with('dataProductSite',$this->dataProductSite)->with('numberSite',$this->numberSite)->with('dataProduct',$this->dataProduct)->with('totalFilterProductSite',$this->totalFilterProductSite);
    }
}
