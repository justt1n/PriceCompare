<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BodyProductCompare extends Component
{
    /**
     * Create a new component instance.
     */
    public $productCompare;
    public function __construct($productCompare)
    {
        $this->productCompare = $productCompare;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.body-product-compare')->with('productCompare',$this->productCompare);
    }
}
