<?php namespace CanErdogan\LaravelMixpanel\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Events\Dispatcher;
use CanErdogan\LaravelMixpanel\Events\MixpanelEvent;
use Illuminate\Support\Facades\Session;

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
		event(new MixpanelEvent('Login Attempt', $user));
	}


	public function onUserLogin ($event)
	{

		$user         = $event->user ?? $event;
		event(new MixpanelEvent('User Login', $user));
	}


	public function onUserLogout ($event)
	{

		$user         = $event->user ?? $event;
		event(new MixpanelEvent('User Logout', $user));
	}

	public function onUserRegister ($event)
	{

		$user         = $event->user ?? $event;
		app( 'mixpanel' )->createAlias( Session::getId(), $user->getKey() );
		event(new MixpanelEvent('User Registered', $user));
	}


	public function subscribe (Dispatcher $events)
	{

		$events->listen( Failed::class, self::class . '@onUserLoginFailed' );
		$events->listen( Login::class, self::class . '@onUserLogin' );
		$events->listen( Logout::class, self::class . '@onUserLogout' );
		$events->listen( Registered::class, self::class . '@onUserRegister' );
	}
}
