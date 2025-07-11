<?php

namespace App\Traits;

use File;

trait FileProcess
{
    protected function storeFile($file, string $path, string|null $file_name = null): string | null
    {
        $image_path = null;
        if($file !== null) {
            if(empty($file_name))
                $image_path = $file?->store($path, ['disk' => 'public']);
            else
                $image_path = $file?->storeAs($path, $file_name . '.' . $file->guessExtension(), ['disk' => 'public']);
        }

        return $image_path;
    }

    protected function updateFile($file, string $path, mixed $model, string $column, string|null $file_name = null): string | null
    {
        $image_path = null;
        if($file !== null || !empty($model->getAttribute($column))) {
            if($file !== null){
                $this->deleteFile($model, $column);
                $image_path = $this->storeFile($file, $path, $file_name);
            } else {
                $image_path = $model->getAttribute($column);
            }
        }

        return $image_path;
    }

    protected function deleteFile(mixed $model, string $column): void
    {
        File::delete(storage_path('app/public/') . $model->getAttribute($column));
        $model->setAttribute($column, null);
        $model->save();
    }
}
