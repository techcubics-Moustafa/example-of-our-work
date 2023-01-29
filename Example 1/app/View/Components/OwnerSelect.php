<?php

namespace App\View\Components;

use Illuminate\View\Component;

class OwnerSelect extends Component
{
    public $owners;
    public $ownerId;

    public function __construct($owners, $ownerId)
    {
        $this->owners = $owners;
        $this->ownerId = $ownerId;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Support\Htmlable|string|\Closure|\Illuminate\Contracts\Foundation\Application
    {
        return view('components.owner-select');
    }
}
