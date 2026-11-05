<?php
namespace App\Http\Controllers;
use App\Models\Deal;
class VisitController extends Controller {
    public function go(Deal $deal) {
        abort_unless($deal->isPublic(),404);
        $deal->increment('clicks');
        // URL has been validated to http(s). Do not fetch it server-side.
        return redirect()->away($deal->deal_url);
    }
}
