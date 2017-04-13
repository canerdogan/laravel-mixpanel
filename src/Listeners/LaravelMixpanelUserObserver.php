<?php namespace CanErdogan\LaravelMixpanel\Listeners;

use CanErdogan\LaravelMixpanel\Events\MixpanelEvent;

class LaravelMixpanelUserObserver
{

	public function saving ($user)
	{
		event(new MixpanelEvent('User Updated', $user));
	}


	public function deleting ($user)
	{
		event(new MixpanelEvent('User Deleted', $user));
	}


	public function restored ($user)
	{
		event(new MixpanelEvent('User Restored', $user));
	}
}
