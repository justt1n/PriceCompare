<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductDetail extends Component
{
    /**
     * Create a new component instance.
     */

    public $data;
    public $dataImages;
    public $maxPriceProductSite;
    public $productSite;
    public function __construct($data,$dataImages,$maxPriceProductSite,$productSite)
    {
        $this->data = $data;
        $this->dataImages = $dataImages;
        $this->maxPriceProductSite = $maxPriceProductSite;
        $this->productSite = $productSite; 
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product-detail')->with('data',$this->data)->with('dataImages',$this->dataImages)->with('maxPriceProductSite', $this->maxPriceProductSite)->with('productSite',$this->productSite);
    }
}
