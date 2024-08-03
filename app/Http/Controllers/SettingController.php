<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Crypt;

class SettingController extends ApiController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('hasPermissions:VIEW_SETTINGS', only: ['getSettingByCategory']),
            new Middleware('hasPermissions:MANAGE_SETTINGS', only: ['updateSettings', 'getPaymentSettings', 'updatePaymentSettings']),
        ];
    }

    /**
     * Get settings of the venue by category
     *
     * @param Venue $venue
     * @param Request $request
     * @return JsonResponse
     */
    public function getSettingByCategory(Venue $venue, Request $request): JsonResponse
    {
        $settings = $venue->settings()->where('category', $request->category)->get();
        return $this->success($settings);
    }

    /**
     * Update the venue's settings
     *
     * @param Venue $venue
     * @param Request $request
     * @return JsonResponse
     */
    public function updateSettings(Venue $venue, Request $request): JsonResponse
    {
        foreach ($request->settings as $setting) {
            $s = Setting::findOrFail($setting['id']);
            $s->value = $setting['value'];
            $s->save();
        }

        return $this->success();
    }

    /**
     * Get the venue's payment settings
     *
     * @param Venue $venue
     * @return JsonResponse
     */
    public function getPaymentSettings(Venue $venue): JsonResponse
    {
        $settings = $venue->settings()->where('category', 'finance')->get();
        return $this->success(
          [
              'venue' => $venue,
              'settings' => [
                  'payment_provider' => $settings->where('key', 'payment_provider')->first()->value,
              ]
          ]
        );
    }

    /**
     * Update the venue's payment settings
     *
     * @param Venue $venue
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePaymentSettings(Venue $venue, Request $request): JsonResponse
    {
        $setting_provider = $venue->settings->where('key', 'payment_provider')->first();
        $setting_api_key = $venue->settings->where('key', 'payment_api_key')->first();

        $setting_provider->value = $request->payment_provider;
        $setting_provider->save();

        $setting_api_key->value = Crypt::encrypt($request->payment_api_key);
        $setting_api_key->save();

        return $this->success();
    }
}
