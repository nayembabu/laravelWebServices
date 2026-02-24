<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class BkashService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('bkash.base_url'), '/');
    }

    public function getToken(): string
    {
        // token cache (সাধারণত কিছু সময় valid থাকে; আমরা নিরাপদভাবে 50 মিনিট cache করছি)
        return Cache::remember('bkash_id_token', now()->addMinutes(50), function () {
            $response = Http::withHeaders([
                'username' => config('bkash.username'),
                'password' => config('bkash.password'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . '/v1.2.0-beta/tokenized/checkout/token/grant', [
                'app_key' => config('bkash.app_key'),
                'app_secret' => config('bkash.app_secret'),
            ]);

            $data = $response->json();

            if (!$response->successful() || empty($data['id_token'])) {
                throw new RuntimeException('bKash token grant failed: ' . json_encode($data));
            }

            return $data['id_token'];
        });
    }

    public function createPayment(float $amount, string $invoice): array
    {
        $token = $this->getToken();

        $response = Http::withToken($token)
            ->withHeaders([
                'X-APP-Key' => config('bkash.app_key'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post($this->baseUrl . '/v1.2.0-beta/tokenized/checkout/create', [
                'mode' => '0011',
                'payerReference' => ' ', // optional
                'callbackURL' => config('bkash.callback_url'),
                'amount' => number_format($amount, 2, '.', ''),
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => $invoice,
            ]);

        return $response->json();
    }

    public function executePayment(string $paymentId): array
    {
        $token = $this->getToken();

        $response = Http::withToken($token)
            ->withHeaders([
                'X-APP-Key' => config('bkash.app_key'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post($this->baseUrl . '/v1.2.0-beta/tokenized/checkout/execute', [
                'paymentID' => $paymentId
            ]);

        return $response->json();
    }
}
