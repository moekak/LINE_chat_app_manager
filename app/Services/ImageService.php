<?php

namespace App\Services;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageService
{
      public function saveImage($imageData){
            try {
                  $fileName = uniqid() . '.' . $imageData->getClientOriginalExtension();
                  $path = 'images/' . $fileName;
                  
                  // S3に画像を保存
                  Storage::disk('s3')->put($path, file_get_contents($imageData->getRealPath()));
                  
                  return $fileName;
      
            } catch (\Exception $e) {
                  Log::error('Image save error: ' . $e->getMessage());
                  return ['error' => 'Failed to save image'];
            }
      }
}
