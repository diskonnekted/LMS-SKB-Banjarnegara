<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public $maxWidth;
    public $logoTheme;

    /**
     * Create a new component instance.
     */
    public function __construct($maxWidth = 'max-w-md', $logoTheme = 'dark')
    {
        $this->maxWidth = $maxWidth;
        $this->logoTheme = $logoTheme;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
