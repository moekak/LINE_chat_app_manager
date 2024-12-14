<?php

namespace App\Services;

use App\Models\RedirectToken;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RedirectTokenService{
      public function generateToken(): string{
            $token = Str::random(64);
            
            RedirectToken::create([
                  'token' => $token,
                  'expires_at' => Carbon::now()->addMinutes(30), // 30分の有効期限
                  'used' => false
            ]);
            
            return $token;
      }

      public function validateToken(string $token): bool
      {
            $tokenRecord = RedirectToken::where('token', $token)
                  ->where('used', false)
                  ->where('expires_at', '>', Carbon::now())
                  ->first();
      
            if (!$tokenRecord) {
                  return false;
            }
      
            // トークンを使用済みにマーク
            $tokenRecord->update(['used' => true]);
      
            return true;
      }
}