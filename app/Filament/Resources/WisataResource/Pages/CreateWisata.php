<?php

namespace App\Filament\Resources\WisataResource\Pages;

use App\Filament\Resources\WisataResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWisata extends CreateRecord
{
    protected static string $resource = WisataResource::class;

    // protected function afterSave(): void
    // {
    //     $this->dispatch('refresh'); //untuk autorefresh setelah kita simpan
    // }
}
