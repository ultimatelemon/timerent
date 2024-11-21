<?php

namespace App\Modules\Moneybird\Services;

use _PHPStan_18cddd6e5\Nette\Neon\Exception;
use App\Models\Venue;
use GuzzleHttp\Client;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\DB;

class MoneybirdService
{
    private Client $apiClient;
    private string $access_token;
    private string $refresh_token;
    private string $api_url;
    private string $base_url = 'https://moneybird.com/api/v2';

    private $administration_id = '412217708370724680';

    private Venue $venue;

    public function __construct(Venue $venue)
    {
        if(!$venue) throw new InvalidArgumentException('Venue is required for this service');
        $this->setCredentials($venue);
        $this->venue = $venue;
        $this->apiClient = new Client();
        $this->api_url = 'https://moneybird.com/oauth/token';
    }

    protected function setCredentials(Venue $venue)
    {
        $settings = DB::table('venue_modules')
            ->where('venue_id', $venue->id)
            ->where('module_id', config('moneybird.module_id'))
            ->first();

        if (!$settings) return response()->json('Moneybird connection not found for this venue');

        $settings = json_decode($settings->settings, true);
        $this->access_token = $settings['access_token'];
        $this->refresh_token = $settings['refresh_token'];
    }

    public function getAdministration()
    {
        if(empty($this->access_token)) throw new Exception('Access token is not set for this service');
        $response = $this->apiClient->get($this->base_url . '/administrations.json', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->access_token,
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);

        return $data[0]['id'];
    }

    protected function refreshAccessToken()
    {
        $response = $this->apiClient->post($this->api_url, [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->refresh_token,
                'client_id' => env('MONEYBIRD_CLIENT_ID'),
                'client_secret' => env('MONEYBIRD_CLIENT_SECRET'),
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        $this->access_token = $data['access_token'];
        $this->refresh_token = $data['refresh_token'];

        $this->saveTokensToDatabase();
    }

    protected function saveTokensToDatabase()
    {
        DB::table('venue_modules')
            ->where('venue_id', $this->venue_id)
            ->where('module_id', config('moneybird.module_id'))
            ->update([
                'settings' => json_encode([
                    'access_token' => $this->access_token,
                    'refresh_token' => $this->refresh_token,
                ])
            ]);
    }

    public function getInvoices()
    {
        if ($this->isTokenExpired()) $this->refreshAccessToken();

        $response = $this->apiClient->get('https://moneybird.com/api/v2/412217708370724680/invoices.json', [
            'auth' => [$this->access_token, ''],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getProducts()
    {
        if ($this->isTokenExpired()) $this->refreshAccessToken();
        $response = $this->apiClient->get($this->base_url . '412217708370724680/sales_invoices.json', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->access_token,
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);

        return $data;
    }

    protected function isTokenExpired()
    {
        $expires_at = DB::table('venue_modules')
            ->where('venue_id', $this->venue->id)
            ->where('module_id', config('moneybird.module_id'))
            ->first()->updated_at;

        return now()->diffInDays($expires_at) > 365;
    }
}