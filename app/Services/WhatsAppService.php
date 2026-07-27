<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $url;
    private ?string $token;

    public function __construct()
    {
        $this->url   = config('services.fonnte.url', 'https://api.fonnte.com/send');
        $this->token = config('services.fonnte.token');
    }

    /**
     * Normalize phone to Indonesian format (628xxx).
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Send a WhatsApp message via Fonnte API.
     * Returns true on success, false on failure.
     */
    public function send(string $phone, string $message): bool
    {
        if (empty($this->token)) {
            Log::warning('WhatsAppService: WA_API_KEY belum diisi di .env');
            return false;
        }

        $phone = $this->normalizePhone($phone);

        if (strlen($phone) < 10) {
            Log::warning("WhatsAppService: Nomor tidak valid — {$phone}");
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->asForm()->post($this->url, [
                'target'      => $phone,
                'message'     => $message,
                'countryCode' => '62',
            ]);

            $body = $response->json();

            if ($response->successful() && ($body['status'] ?? false) === true) {
                return true;
            }

            Log::warning('WhatsAppService: Fonnte response gagal', [
                'status' => $response->status(),
                'body'   => $body,
            ]);
            return false;
        } catch (\Throwable $e) {
            Log::error('WhatsAppService: Exception saat kirim WA', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get a WhatsApp "Click to Chat" URL.
     */
    public function getRedirectUrl(string $phone, string $message): ?string
    {
        $phone = $this->normalizePhone($phone);
        if (strlen($phone) < 10) return null;
        
        $encodedMessage = urlencode($message);
        return "https://wa.me/{$phone}?text={$encodedMessage}";
    }

    /**
     * Process WhatsApp notification based on WA_TYPE (.env).
     * Returns the redirect URL if WA_TYPE is 'chat', otherwise null.
     */
    public function process(string $phone, string $message): ?string
    {
        $waType = env('WA_TYPE', 'fonnte');

        if ($waType === 'chat') {
            return $this->getRedirectUrl($phone, $message);
        }

        $this->send($phone, $message);
        return null;
    }
}
