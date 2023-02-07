<?php

namespace App\Traits; 

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait ImageableTrait 
{
    public static function bootImageableTrait()
    {
        static::creating(function ($model) {
            self::updateImageUrl($model);
        });

        static::saving(function ($model) {
            self::updateImageUrl($model);
        });
    }

    public function updateImage(UploadedFile $image)
    {
        $imagePath = $image->store($this::getImageStoragePath());
        $imageUrl = Storage::url($imagePath);
        $this->image_url = $imageUrl;
    }

    private static function getImageStoragePath()
    {
        $modelName = class_basename(self::class);
        return strtolower((Str::plural($modelName)));
    }

    private static function storeBase64Image(string $base64): ?string
    {
        try {
            $image = Image::make($base64)->encode('jpg');
            $hash = md5($image->__toString());
            $imagePath = Str::kebab(class_basename(self::class)).'/'.$hash.'.jpeg';

            if (Storage::put($imagePath, $image)) {
                return $imagePath;
            }
        } catch (\Throwable $th) {
            Log::error('Failed to store base64 image. \n' . $th->getMessage());

            return null;
        }
    }

    private static function updateImageUrl($model)
    {
        if (request()->filled('base64_image')) {
            $imageUrl = self::storeBase64Image(request()->base64_image);
            $model->image_url = $imageUrl;
            return $model;
        }
    }
}