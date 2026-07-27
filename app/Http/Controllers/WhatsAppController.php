<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WhatsAppService;

class WhatsAppController extends Controller
{
    public function sendAsync(Request $request, WhatsAppService $service)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $waType = env('WA_TYPE', 'fonnte');

        if ($waType === 'chat') {
            $url = $service->getRedirectUrl($request->phone, $request->message);
            return response()->json([
                'success' => true,
                'type' => 'chat',
                'url' => $url
            ]);
        }

        // Fonnte
        $isSuccess = $service->send($request->phone, $request->message);
        return response()->json([
            'success' => $isSuccess,
            'type' => 'fonnte',
            'message' => $isSuccess ? 'Pesan berhasil dikirim via Fonnte.' : 'Gagal mengirim pesan via Fonnte. Cek konfigurasi dan koneksi perangkat.'
        ]);
    }
}
