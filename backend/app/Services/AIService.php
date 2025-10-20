<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
abstract class AIService
{
    protected $aiBaseUrl = 'http://ai-services:8000';
    protected function postToAI($service, array $data, $cacheKey = null, $ttl = 3600)
    {
        if ($cacheKey && Cache::has($cacheKey)) {
            return ['success' => true, 'data' => Cache::get($cacheKey), 'cached' => true];
        }
        try {
            $response = Http::timeout(10)->post($this->aiBaseUrl . "/$service/predict", ['data' => $data]);
            if ($response->successful()) {
                $result = $response->json()['result'];
                if ($cacheKey) {
                    Cache::put($cacheKey, $result, $ttl);
                }
                return ['success' => true, 'data' => $result, 'cached' => false];
            }
            return ['success' => false, 'error' => $response->json()['detail'] ?? 'AI Service Error'];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => 'Connection Failed: ' . $e->getMessage()];
        }
    }
}
