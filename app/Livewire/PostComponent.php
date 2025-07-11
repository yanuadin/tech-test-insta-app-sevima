<?php

namespace App\Livewire;

use App\Models\Post;
use App\Traits\FileProcess;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostComponent extends Component
{
    use WithFileUploads, FileProcess;

    public $type = "image";
    public $url = "";
    public $caption = "";

    public function render()
    {
        return view('livewire.post-component');
    }

    public function rules(): array
    {
        return [
            'url' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
            'caption' => ['required', 'string'],
        ];
    }

    public function store(): void
    {
        $validated = $this->validate($this->rules(), [
            'url.required' => "The image field is required.",
            'url.max' => "The image must be less than 3 MB.",
        ]);
        $validated['url'] = $this->storeFile($validated['url'], 'post');

        Post::query()->create([
            'user_id' => auth()->user()->id,
            'type' => $this->type,
            'url' => $validated['url'],
            'caption' => $validated['caption'],
        ]);

        $this->redirectRoute('home');
    }

}
