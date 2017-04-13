<?php

use CanErdogan\LaravelMixpanel\Http\Controllers\StripeWebhooksController;

Route::post('canerdogan/laravel-mixpanel/stripe', StripeWebhooksController::class .'@postTransaction');
