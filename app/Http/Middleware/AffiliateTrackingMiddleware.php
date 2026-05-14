<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AffiliateTrackingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $referralCode = $request->query('ref') ?: $request->query('tracking_code');

        if ($referralCode) {
            $link = \App\Models\AffiliateLink::where('tracking_code', $referralCode)
                ->where('status', 'active')
                ->first();

            if ($link) {
                // Increment clicks count
                $link->increment('clicks_count');

                // Set cookie for 30 days
                $response = $next($request);
                return $response->withCookie(cookie('referral_code', $referralCode, 60 * 24 * 30));
            }
        }

        return $next($request);
    }
}
