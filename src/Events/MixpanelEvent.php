<?php namespace CanErdogan\LaravelMixpanel\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class MixpanelEvent
{

	use SerializesModels;

	public $eventName;
	public $arguments;


	public function __construct (string $eventName, ...$arguments) {

		$this->eventName    = $eventName;
		$this->arguments    = $arguments;
	}
}
