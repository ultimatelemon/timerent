<?php

namespace App\Modules\Moneybird\Controllers;

use App\Http\Controllers\ApiController;
use App\Models\Modules\VenueModule;
use App\Models\Venue;
use App\Modules\Moneybird\Services\MoneybirdService;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class MoneybirdController extends ApiController
{
    private MoneybirdService $moneybirdService;

    public function __construct(Venue $venue)
    {
        $this->moneybirdService = new MoneybirdService($venue);
    }

    public function index(Venue $venue): JsonResponse
    {
        try {
            $invoices = $this->moneybirdService->getInvoices();
            return $this->success($invoices);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage());
        }
    }

    public function redirectToMoneybird()
    {
//        $scopes = ['sales_invoices', 'documents', 'settings', 'estimates', 'bank', 'time_entries', 'settings'];
//        $scopes = join('%20', $scopes);
        $query = http_build_query([
            'client_id' => 'a78d7229db418ecddad5737d4b3c4adc',
            'redirect_uri' => 'http://timerent-rewrite.test/api/moneybird/callback',
            'response_type' => 'code',
            'scope' => 'sales_invoices documents settings estimates bank time_entries settings'
        ]);

        $url = 'https://moneybird.com/oauth/authorize?' . $query;
//        return $url;
        return redirect($url);
//        return $this->success();
    }

    public function handleCallback(Request $request)
    {
        $code = $request->get('code');

        if (!$code) return $this->error('Authentication error');

        $client = new Client();
        $response = $client->post('https://moneybird.com/oauth/token', [
            'form_params' => [
                'client_id' => 'a78d7229db418ecddad5737d4b3c4adc',
                'client_secret' => '2b26bc0438b29d521c0c787254d88bfda061c38c5d69f387c5ee75fbe0207a36',
                'code' => $request->code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => 'http://timerent-rewrite.test/api/moneybird/callback',
            ]
        ]);

        if ($response->getStatusCode() != 200) return $this->error('');

        $data = json_decode($response->getBody()->getContents(), true);
        VenueModule::updateOrCreate(
            ['venue_id' => Cookie::get('module_venue_id')], // Search criteria
            [ // Values to insert or update
                'module_id' => '19e51598-18cf-4264-90b6-140b4e19993f',
                'settings' => json_encode([ // Encode the array as JSON for storage
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                    'expires_in' => now()->addHours((24 * 14)),
                ]),
            ]
        );

        $venue = Venue::findOrFail(Cookie::get('module_venue_id'));
        $moneybirdService = new MoneybirdService($venue);
        $administration_id = $moneybirdService->getAdministration();

        VenueModule::updateOrCreate(
            ['venue_id' => Cookie::get('module_venue_id')], // Search criteria
            [ // Values to insert or update
                'module_id' => '19e51598-18cf-4264-90b6-140b4e19993f',
                'settings' => json_encode([ // Encode the array as JSON for storage
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                    'expires_in' => now()->addHours((24 * 14)),
                    'administration_id' => $administration_id,
                ]),
            ]
        );


        return redirect('/store/' . $venue->id . '/home');
//        return $this->success('Moneybird connected');
    }
}