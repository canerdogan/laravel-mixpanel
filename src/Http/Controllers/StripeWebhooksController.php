<?php namespace CanErdogan\LaravelMixpanel\Http\Controllers;

use CanErdogan\LaravelMixpanel\Http\Requests\RecordStripeEvent;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class StripeWebhooksController extends Controller
{
    public function postTransaction(RecordStripeEvent $request) : Response
    {
        $request->process();

        return response('', 204);
    }
}
