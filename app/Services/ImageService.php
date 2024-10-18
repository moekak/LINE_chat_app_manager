<?php

namespace App\Services;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageService
{
      public function saveBase64Image($base64Image)
      {
            // // ファイル形式を取得
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                  // ファイル形式を取得（例：jpeg, png）

                  $imageType = strtolower($type[1]); // jpeg, pngなど


                  // フォーマットが適切か確認
                  if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'gif'])) {
                        return ['error' => 'Invalid image type'];
                  }
                  // Base64のデータ部分を取り出す

                  // Base64をデコードしてバイナリデータに変換
                  $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);  // プレフィックスを削除


                  // 画像を保存するパス（例えば、storage/app/public/imagesに保存）
                  $imageName = uniqid() . '.' . $imageType;
                  $path = 'images/' . $imageName;

                  // S3に画像を保存
                  Storage::disk('s3')->put($path, base64_decode($base64Image));

                  return $imageName;

                  
            }else{
                  return ['error' => 'Invalid image data'];
            }
      }
}
