<?php namespace CanErdogan\LaravelMixpanel\Listeners;

use CanErdogan\LaravelMixpanel\Events\MixpanelEvent as Event;
use Carbon\Carbon;

class MixpanelEventListener
{

	public function handle (Event $event)
	{

		$user        = $event->user;
		$eventName   = $event->eventName;
		$profileData = $this->getProfileData( $user );
		$profileData = array_merge( $profileData, $event->profileData );

		app( 'mixpanel' )->identify( $user->getKey() );
		app( 'mixpanel' )->people->set( $user->getKey(), $profileData, request()->ip() );

		if($event->charge !== 0) {
			app( 'mixpanel' )->people->trackCharge( $user->id, $event->charge );
		}

		array_map( function($data) use ($eventName) {

			app( 'mixpanel' )->track( $eventName, $data );
		}, $event->trackingData );
	}


	private function getProfileData ($user): array
	{
		$authModel = config( 'auth.providers.users.model' ) ?? config( 'auth.model' );

		$data = [];
		if($user instanceof $authModel) {
			$firstName = $user->first_name;
			$lastName  = $user->last_name;

			if($user->name) {
				$nameParts = explode( ' ', $user->name );
				array_filter( $nameParts );
				$lastName = array_pop( $nameParts );
				$firstName = implode( ' ', $nameParts );
			}

			$data = [
				'$first_name' => $firstName,
				'$last_name'  => $lastName,
				'$name'       => $user->name,
				'$email'      => $user->email,
				'$created'    => ($user->created_at
					? ($user->created_at instanceof Carbon ? $user->created_at->format( 'Y-m-d\Th:i:s' )
						: Carbon::parse( $user->created_at )->format( 'Y-m-d\Th:i:s' ))
					: NULL),
				'$domain'     => (request()->header( 'referer' )
					? parse_url( request()->header( 'referer' ) )['host']
					: NULL)
			];
			array_filter( $data );
		}

		return $data;
	}
}
