<div>
    <div class="w-1/2 mx-auto">
        <form wire:submit="store" class="flex flex-col gap-6" enctype="multipart/form-data">
            @csrf
            @if($url !== null && !is_string($url))
                <div class="col-span-4 text-center mx-auto">
                    <img class="max-h-96 max-w-full" src="{{ $url->temporaryUrl() }}" alt="Image Preview">
                </div>
            @endif
            <flux:input type="file" wire:model="url" label="Image" accept="image/*"/>

            <flux:textarea
                label="Caption"
                placeholder="Write a caption..."
                wire:model="caption"
            />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full">{{ __('Submit') }}</flux:button>
            </div>
        </form>
    </div>
</div>
