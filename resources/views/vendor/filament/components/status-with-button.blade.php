<x-filament::button
    type="button"
    color="warning"
    wire:click="konfirmasi({{ $record->id }})"
    class="filament-tables-button-action"
>
    Konfirmasi
</x-filament::button>