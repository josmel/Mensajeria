<?php

namespace App\Modules\Auth\Controllers;

use Auth, Input, Redirect, URL, View;

class AuthController extends \BaseController {

	/**
	 * Display login screen
	 * @return View
	 */
	public function getLogin()
	{    
		return View::make('auth::login');
	}

	/**
	 * Attempt to login
	 * @return Redirect
	 */
	public function postLogin()
	{
		$credentials = array(
			'username' => Input::get('username'),
			'password' => Input::get('password'),
		);

		if (Auth::attempt($credentials))
		{
		    return Redirect::intended(URL::route('index'));
		}

		return Redirect::route('login')->with('msj', 'Login failed!')->withInput(Input::all());
	}

	/**
	 * Log a user out
	 * @return Redirect
	 */
	public function getLogout()
	{
                Auth::logout();
		return Redirect::route('login');
	}

}
