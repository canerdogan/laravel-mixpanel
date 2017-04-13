<?php namespace CanErdogan\LaravelMixpanel\Listeners;

use CanErdogan\LaravelMixpanel\Events\MixpanelEvent;

class LaravelMixpanelUserObserver
{

	public function saving ($user)
	{

		$trackingData = [
			['User', ['Status' => 'Updated']],
		];
		event(new MixpanelEvent('User Updated', $user, $trackingData));
	}


	public function deleting ($user)
	{

		$trackingData = [
			['User', ['Status' => 'Deactivated']],
		];
		event(new MixpanelEvent('User Deleted', $user, $trackingData));
	}


	public function restored ($user)
	{

		$trackingData = [
			['User', ['Status' => 'Reactivated']],
		];
		event(new MixpanelEvent('User Restored', $user, $trackingData));
	}
}
