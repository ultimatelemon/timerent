<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends ApiController
{
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

    public function updateSettings(Venue $venue, Request $request)
    {
        foreach ($request->settings as $setting) {
            $s = Setting::findOrFail($setting['id']);
            $s->value = $setting['value'];
            $s->save();
        }

        return $this->success();
    }
}
