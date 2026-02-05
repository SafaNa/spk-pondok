<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Get a WhatsApp "Click to Chat" URL.
     *
     * @param string $targetPhone The recipient's phone number.
     * @param string $message The message content.
     * @return string|null The whatsapp redirection URL or null if phone is invalid.
     */
    public function getRedirectUrl(string $targetPhone, string $message): ?string
    {
        // Format phone number: Replace 08 with 628
        if (str_starts_with($targetPhone, '0')) {
            $targetPhone = '62' . substr($targetPhone, 1);
        }

        // Basic validation
        if (empty($targetPhone)) {
            return null;
        }

        // WhatsApp Click to Chat URL format
        // https://wa.me/62812345678?text=Hello%20World
        $encodedMessage = urlencode($message);

        return "https://wa.me/{$targetPhone}?text={$encodedMessage}";
    }
}
