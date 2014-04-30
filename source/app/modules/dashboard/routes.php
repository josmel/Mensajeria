<?php
Route::group(array('before' => 'auth'), function(){
    Route::get('/dashboard',  array('as' => 'gethome', 'uses' => 'App\Modules\Dashboard\Controllers\DashboardController@getHome'));
    Route::get('/',  array('as' => 'index', 'uses' => 'App\Modules\Dashboard\Controllers\DashboardController@sendOne'));
    Route::get('/send/one',  array('as' => 'sendone', 'uses' => 'App\Modules\Dashboard\Controllers\DashboardController@sendOne'));
    Route::post('/send/save/one',  array('as' => 'saveone', 'uses' => 'App\Modules\Dashboard\Controllers\DashboardController@saveOne'));
    Route::get('/send/oneagend',  array('as' => 'sendoneagend', 'uses' => 'App\Modules\Dashboard\Controllers\DashboardController@sendOneAgend'));
    Route::get('/send/group',  array('as' => 'sendgroup', 'uses' => 'App\Modules\Dashboard\Controllers\DashboardController@sendGroup'));
    Route::get('/send/groupagend',  array('as' => 'sendgroupagend', 'uses' => 'App\Modules\Dashboard\Controllers\DashboardController@sendGroupAgend'));
    Route::post('/send/save/group',  array('as' => 'savegroup', 'uses' => 'App\Modules\Dashboard\Controllers\DashboardController@saveGroup'));
    Route::get('/send/file',  array('as' => 'sendfile', 'uses' => 'App\Modules\Dashboard\Controllers\DashboardController@sendFile'));
    Route::post('/send/save/file',  array('as' => 'savefile', 'uses' => 'App\Modules\Dashboard\Controllers\DashboardController@saveFile'));

    Route::get('/supply/user',  array('as' => 'suplyuser', 'uses' => 'App\Modules\Dashboard\Controllers\SuplyController@showUser'));
    Route::get('/supply/operation/user',  array('as' => 'suplyuseroperation', 'uses' => 'App\Modules\Dashboard\Controllers\SuplyController@showUserOperation'));
    Route::get('/supply/operation/user/{id}',  array('as' => 'suplyuserupdateoperation', 'uses' => 'App\Modules\Dashboard\Controllers\SuplyController@editUserOperation'));
    Route::post('/supply/save/user',  array('as' => 'supplysaveuser', 'uses' => 'App\Modules\Dashboard\Controllers\SuplyController@saveSupplyUser'));
    Route::get('/supply/user/list',  array('as' => 'suplyuserlist', 'uses' => 'App\Modules\Dashboard\Controllers\SuplyController@listSupplyUser'));
    Route::get('/supply/group',  array('as' => 'suplygroup', 'uses' => 'App\Modules\Dashboard\Controllers\SuplyController@showGroup'));
    Route::get('/supply/operation/group',  array('as' => 'suplygroupoperation', 'uses' => 'App\Modules\Dashboard\Controllers\SuplyController@showGroupOperation'));
    Route::get('/supply/operation/group/{id}',  array('as' => 'suplygroupupdateoperation', 'uses' => 'App\Modules\Dashboard\Controllers\SuplyController@editGroupOperation'));
    Route::post('/supply/save/group',  array('as' => 'supplysavegroup', 'uses' => 'App\Modules\Dashboard\Controllers\SuplyController@saveSupplyGroup'));
    Route::get('/supply/group/list',  array('as' => 'suplygrouplist', 'uses' => 'App\Modules\Dashboard\Controllers\SuplyController@listSupplyGroup'));
    Route::get('/supply/delete',  array('as' => 'supplydelete', 'uses' => 'App\Modules\Dashboard\Controllers\SuplyController@deleteSupply'));
    
    Route::get('/perfil',  array('as' => 'editperfil', 'uses' => 'App\Modules\Dashboard\Controllers\UserController@editPerfil'));
    Route::post('/perfil/{id}',  array('as' => 'updateperfil','before' => 'csrf', 'uses' => 'App\Modules\Dashboard\Controllers\UserController@updatePerfil'));
    Route::get('/password',  array('as' => 'editpassword', 'uses' => 'App\Modules\Dashboard\Controllers\UserController@editPassword'));
    Route::post('/password/update',  array('as' => 'updatepassword', 'uses' => 'App\Modules\Dashboard\Controllers\UserController@updatePassword'));
    Route::get('/pruebasms',  array('uses' => 'App\Modules\Dashboard\Controllers\UserController@pruebaSms'));

    Route::get('/report/detail',  array('as' => 'reportdetail', 'uses' => 'App\Modules\Dashboard\Controllers\ReportController@showDetail'));
    Route::get('/report/detail/list',  array('as' => 'reportdetaillist', 'uses' => 'App\Modules\Dashboard\Controllers\ReportController@listDetail'));
    Route::get('/report/consolidated',  array('as' => 'reportconsolidated', 'uses' => 'App\Modules\Dashboard\Controllers\ReportController@showConsolidated'));
    Route::get('/report/consolidated/list',  array('as' => 'reportconsolidatedlist', 'uses' => 'App\Modules\Dashboard\Controllers\ReportController@listConsolidated'));
    Route::get('/report/consolidated/detail/{id}',  array('as' => 'reportconsolidateddetail', 'uses' => 'App\Modules\Dashboard\Controllers\ReportController@showConsolidatedDetail'));
    Route::get('/report/consolidated/detailexcel/{id}',  array('as' => 'reportconsolidateddetailexcel', 'uses' => 'App\Modules\Dashboard\Controllers\ReportController@showConsolidatedDetailExcel'));
    Route::get('/report/consolidated/detaillist',  array('as' => 'reportconsolidateddetaillist', 'uses' => 'App\Modules\Dashboard\Controllers\ReportController@listConsolidatedDetail'));
    
    Route::post('/ajax/phonecustomer',  array('as' => 'uniquephonecustomer', 'uses' => 'App\Modules\Dashboard\Controllers\AjaxController@uniquePhoneCustomer'));

});

// Custom 404 page
App::missing(function($exception)
{
    return Response::view('dashboard::error.404', array(), 404);
});
