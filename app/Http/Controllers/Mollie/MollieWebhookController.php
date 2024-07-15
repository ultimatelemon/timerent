<?php

namespace App\Http\Controllers\Mollie;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Support\Facades\Request;

class MollieWebhookController extends ApiController
{
    public function updatePayment(Request $request)
    {
        if(!$request->has('order_id')) return abort(404);

        $reservation = Reservation::find($request->input('reservation_id'));

    }
}
