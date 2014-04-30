<?php namespace App\Modules\Service;

class ServiceServiceProvider extends \Illuminate\Support\ServiceProvider {

	public function register()
	{
		\Log::debug("ServiceServiceProvider registered");
	}

}
