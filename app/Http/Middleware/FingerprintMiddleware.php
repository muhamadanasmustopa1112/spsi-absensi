<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FingerprintMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Dapatkan informasi perangkat dan request header
        $fingerprint = $this->generateFingerprint($request);

        // Simpan atau bandingkan fingerprint di session
        $storedFingerprint = session('fingerprint');

        if (!$storedFingerprint) {
            session(['fingerprint' => $fingerprint]);
        } elseif ($storedFingerprint !== $fingerprint) {
            return response('Fingerprint mismatch', 403);
        }

        echo "Fingerprint" . $fingerprint;
        return $next($request);
    }

    /**
     * Generate a unique fingerprint for the mobile device.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    protected function generateFingerprint(Request $request)
    {
        // Informasi User-Agent (yang bisa mengidentifikasi perangkat)
        $userAgent = $request->header('User-Agent');
        
        // IP Address pengguna
        $ipAddress = $request->ip();
        
        // Dapatkan informasi tambahan dari header (contohnya Accept-Language)
        $acceptLanguage = $request->header('Accept-Language');
        
        // Dapatkan resolusi layar (jika dikirim dari frontend via request header atau cookie)
        $screenResolution = $request->cookie('screen_resolution', 'unknown');

        // Buat hash dari data yang diperoleh
        return hash('sha256', $userAgent . $ipAddress . $acceptLanguage . $screenResolution);
    }
}
