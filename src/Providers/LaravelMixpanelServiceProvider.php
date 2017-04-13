<?php namespace CanErdogan\LaravelMixpanel\Providers;

use CanErdogan\LaravelMixpanel\Events\MixpanelEvent;
use CanErdogan\LaravelMixpanel\LaravelMixpanel;
use CanErdogan\LaravelMixpanel\Listeners\LaravelMixpanelEventHandler;
use CanErdogan\LaravelMixpanel\Listeners\LaravelMixpanelUserObserver;
use CanErdogan\LaravelMixpanel\Console\Commands\Publish;
use CanErdogan\LaravelMixpanel\Listeners\MixpanelEventListener;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\ServiceProvider;

class LaravelMixpanelServiceProvider extends ServiceProvider
{

	protected $defer = FALSE;


	public function boot (Guard $guard)
	{

		include __DIR__ . '/../../routes/api.php';

		$this->loadViewsFrom( __DIR__ . '/../../resources/views', 'canerdogan-laravel-mixpanel' );
		$this->publishes( [
			                  __DIR__ . '/../../public' => public_path(),
		                  ], 'assets' );

		if(config( 'services.mixpanel.enable-default-tracking' )) {
			app( 'events' )->subscribe( new LaravelMixpanelEventHandler( $guard ) );
			app( 'events' )->listen( MixpanelEvent::class, MixpanelEventListener::class );

			$authModel = config( 'auth.providers.users.model' ) ?? config( 'auth.model' );
			$this->app->make( $authModel )->observe( new LaravelMixpanelUserObserver() );
		}
	}


	public function register ()
	{

		$this->mergeConfigFrom( __DIR__ . '/../../config/services.php', 'services' );
		$this->commands( Publish::class );
		$this->app->singleton( 'mixpanel', LaravelMixpanel::class );
	}


	/**
	 * @return array
	 */
	public function provides ()
	{

		return ['mixpanel'];
	}
}
