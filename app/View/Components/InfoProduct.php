<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InfoProduct extends Component
{
    /**
     * Create a new component instance.
     */
    public $dataAttribute;
    public function __construct($dataAttribute)
    {
        //
        $this->dataAttribute = $dataAttribute; 
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.info-product')->with('dataAttribute',$this->dataAttribute);
    }
}
