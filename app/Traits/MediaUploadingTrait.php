<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\CompanyProperty\App\Models\CompanyProperty;

trait MediaUploadingTrait
{
    public function storeMedia($validatedData, CompanyProperty $property)
    {
        $property_media = $property->medias()->updateOrCreate([
            'name' => $validatedData['name'] ?? null,
            'description' => $validatedData['description'] ?? null,
            'type' => $validatedData['type'],
        ]);

        foreach ($validatedData['media'] as $key => $media) {
            if(is_file($media['file'])) {
                $media_path = null;
                $uniqueFilename = null;
                $name = $media['name'] ?? ($key + 1);
                
                if (isset($media['media_path_id']) && $media['media_path_id']) {
                    $media_path = $property_media->paths()->where('id', $media['media_path_id'])->first();

                    if ($media_path && $media_path->path) {
                        Storage::delete($media_path->path);
                    }
                }

                if ($validatedData['type'] === '360 Virtual Tour') {
                    $uniqueFilename = $name . '.jpg';
                } else {
                    $uniqueFilename = $this->generateUniqueFilename($media['file']);
                }

                $path = $media['file']->storeAs('company/' . $property->company_id . '/properties/' . $property->id . '/media/' .$property_media->id. '/' . $property_media->type, $uniqueFilename);
                
                if (!$media_path) {
                    $property_media->paths()->create([
                        "name" => $name,
                        "path" => $path
                    ]);
                } else {
                    $media_path->update([
                        "name" => $name,
                        "path" => $path
                    ]);
                }
            }
        }

        return $property_media->load('paths');
    }

    private function generateUniqueFilename($file)
    {
        // Generate a unique name for the file
        $uniqueFileName = Str::uuid()->toString(); // Using UUID
        // Or
        $uniqueFileName = time() . '_' . Str::random(10); // Using timestamp and random string

        // Get the file extension
        $fileExtension = $file->getClientOriginalExtension();

        // Combine the unique name and the original extension to create the final filename
        return $uniqueFileName . '.' . $fileExtension;
    }
}
