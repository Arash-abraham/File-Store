<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /** POST /analytics/pageview — records one storefront page view. Admin pages are not counted. */
    public function pageView(Request $request)
    {
        $data = $request->validate(['path' => ['required', 'string', 'max:255']]);
        $path = '/'.ltrim($data['path'], '/');

        if (str_starts_with($path, '/admin')) {
            return response()->noContent();
        }

        DB::table('page_views')->insert([
            'visitor_hash' => $this->visitorHash($request),
            'path' => mb_substr($path, 0, 255),
            'created_at' => now(),
        ]);

        return response()->noContent();
    }

    /** Anonymous visitor id: changes every day and is not reversible to an IP. */
    private function visitorHash(Request $request): string
    {
        return hash('sha256', implode('|', [$request->ip(), (string) $request->userAgent(), now()->toDateString(), config('app.key')]));
    }
}
