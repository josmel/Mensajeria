<?php
Route::group(array('before' => 'auth'), function(){

    //Route::post('/ajax/phonecustomer',  array('as' => 'uniquephonecustomer', 'uses' => 'App\Modules\Dashboard\Controllers\AjaxController@uniquePhoneCustomer'));

});

Route::match(array('GET', 'POST'), '/api/send/sms',  array('as' => 'apisendsms', 'uses' => 'App\Modules\Service\Controllers\ApiController@sms'));
Route::match(array('GET', 'POST'), '/api/auth',  array('as' => 'apiauth', 'uses' => 'App\Modules\Service\Controllers\ApiController@auth'));
Route::match(array('GET', 'POST'), '/api/logout',  array('as' => 'apilogout', 'uses' => 'App\Modules\Service\Controllers\ApiController@logout'));
Route::match(array('GET', 'POST'), '/api/campaign',  array('as' => 'apicampaign', 'uses' => 'App\Modules\Service\Controllers\ApiController@campaign'));

// Custom 404 page
App::missing(function($exception)
{
    return Response::view('dashboard::error.404', array(), 404);
});
