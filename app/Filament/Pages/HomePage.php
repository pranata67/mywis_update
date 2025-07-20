<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class HomePage extends Page
{
    protected static string $view = 'filament.pages.home-page';
    protected static string $layout = 'components.layouts.guest'; // Use custom layout
    
    protected static bool $shouldRegisterNavigation = false;
    
    // Remove admin panel features
    protected static bool $isDiscovered = false;

    public function getLayout(): string
    {
        return static::$layout;
    }
}