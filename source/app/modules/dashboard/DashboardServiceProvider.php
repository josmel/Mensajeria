<?php namespace App\Modules\Dashboard;

class DashboardServiceProvider extends \Illuminate\Support\ServiceProvider {

	public function register()
	{
		\Log::debug("DashboardServiceProvider registered");
	}

}
