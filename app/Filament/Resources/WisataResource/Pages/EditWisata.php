<?php

namespace App\Filament\Resources\WisataResource\Pages;

use App\Filament\Resources\WisataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Livewire\Livewire;

class EditWisata extends EditRecord
{
    protected static string $resource = WisataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // protected function afterSave(): void
    // {
    //     $this->js('window.location.reload()'); // Auto-refresh halaman setelah menyimpan data
    // }
}
