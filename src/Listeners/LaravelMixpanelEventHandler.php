<?php namespace CanErdogan\LaravelMixpanel\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Events\Dispatcher;
use CanErdogan\LaravelMixpanel\Events\MixpanelEvent;

class LaravelMixpanelEventHandler
{

	protected $guard;


	public function __construct (Guard $guard)
	{

		$this->guard = $guard;
	}


	public function onUserLoginFailed ($event)
	{
		$user         = $event->user ?? $event;
		$trackingData = [
			['Session', ['Status' => 'Login Attempt Failed']],
		];
		event(new MixpanelEvent('Login Attempt', $user, $trackingData));
	}


	public function onUserLogin ($event)
	{

		$user         = $event->user ?? $event;
		$trackingData = [['Session', ['Status' => 'Logged In']]];
		event(new MixpanelEvent('User Login', $user, $trackingData));
	}


	public function onUserLogout ($event)
	{

		$user         = $event->user ?? $event;
		$trackingData = [['Session', ['Status' => 'Logged Out']]];
		event(new MixpanelEvent('User Logout', $user, $trackingData));
	}

	public function onUserRegister ($event)
	{

		$user         = $event->user ?? $event;
		app( 'mixpanel' )->alias( $user->getKey() );
		$trackingData = [['Session', ['Status' => 'Registered']]];
		event(new MixpanelEvent('User Registered', $user, $trackingData));
	}


	public function subscribe (Dispatcher $events)
	{

		$events->listen( Failed::class, self::class . '@onUserLoginFailed' );
		$events->listen( Login::class, self::class . '@onUserLogin' );
		$events->listen( Logout::class, self::class . '@onUserLogout' );
		$events->listen( Registered::class, self::class . '@onUserRegister' );
	}
}
