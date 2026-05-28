<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BarAfterTopBar extends Component
{
    /**
     * Create a new component instance.
     */
    public $title;
    public $button;
    public $count;
    public $target;
    public $currentmonth;
    public function __construct($title,$button,$count,$target,$currentmonth)
    {
        $this->title = $title;
        $this->button = $button;
        $this->count = $count;
        $this->target = $target;
        $this->currentmonth = $currentmonth;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.bar-after-top-bar');
    }
}
