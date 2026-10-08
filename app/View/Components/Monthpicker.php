<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Monthpicker extends Component
{
    public $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function render()
    {
        return view('components.monthpicker');
    }
}