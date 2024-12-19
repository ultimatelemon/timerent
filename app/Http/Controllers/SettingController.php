<?php

namespace App\Http\Controllers;

use App\Http\Requests\Venue\Settings\StoreFinanceSettings;
use App\Http\Requests\Venue\Settings\StoreGeneralSettings;
use App\Http\Requests\Venue\Settings\StoreReservationSettings;
use App\Models\Setting;
use App\Models\Venue;
use App\Notifications\Traits\EmailNotifiable;
use App\Notifications\UpdatedPaymentSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SettingController extends ApiController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('hasPermissions:VIEW_SETTINGS', only: ['getGeneralSettings', 'getFinanceSettings', 'getReservationSettings']),
            new Middleware('hasPermissions:MANAGE_SETTINGS', only: ['updateGeneralSettings', 'updateFinanceSettings', 'updateReservationSettings']),
        ];
    }

    public function getGeneralSettings(Venue $venue): JsonResponse
    {
        $settings = $venue->only(['name', 'address', 'postal_code', 'city', 'email', 'phone_number', 'phone_number_support', 'coc_number', 'tax_number']);
        return $this->success($settings);
    }

    public function updateGeneralSettings(Venue $venue, StoreGeneralSettings $request): JsonResponse
    {
        $venue->update($request->validated());
        return $this->success();
    }

    public function getFinanceSettings(Venue $venue): JsonResponse
    {
        $settings = $venue->only(['payment_service_provider', 'stripe_connect_id', 'stripe_connect_onboarded']);
        return $this->success($settings);
    }

    public function updateFinanceSettings(Venue $venue, StoreFinanceSettings $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        $venue->update([
            'payment_service_provider' => $validatedRequest['payment_service_provider'],
            'payment_api_key' => Crypt::encrypt($validatedRequest['payment_api_key']),
        ]);

        $user = $venue->user_venues()->where('owner', true)->first()->user->email;
        $emailNotifiable = new EmailNotifiable($user);
        $emailNotifiable->notify(new UpdatedPaymentSettings($venue));


        return $this->success();
    }

    public function getReservationSettings(Venue $venue): JsonResponse
    {
        $settings = $venue->only(['reservation_prefix', 'cancellation_hours']);
        return $this->success($settings);
    }

    public function updateReservationSettings(Venue $venue, StoreReservationSettings $request): JsonResponse
    {
        ray($request->all());
        $venue->update($request->validated());
        return $this->success();
    }
}
