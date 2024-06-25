<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterBody extends Component
{
    /**
     * Create a new component instance.
     */
    public $data;
    public $count;
    public function __construct($data, $count)
    {
        $this->data = $data;
        $this->count = $count;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.filter-body')->with('data', $this->data)->with('count', $this->count);
    }
}
