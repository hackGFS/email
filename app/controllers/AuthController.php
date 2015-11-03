<?php

class AuthController extends BaseController {

	public function login()
	{
		if(!is_null(Session::get('error')))
		{
			$error = Session::get('error');
			return View::make('auth/login')
				->with('error', $error);	
		}

		return View::make('auth/login');
	}

	public function handleLogin()
	{
		$input = Input::all();

		try {
			$user = Sentry::authenticate($input);	

			return Redirect::action("HomeController@main");
		} catch (Exception $e) {
			return Redirect::route('user.login.view')
				->with('error', $e->getMessage());
		}


	}

	public function logout()
	{
		Sentry::logout();

		return Redirect::route("user.login.view");
	}

	public function register()
	{
		if(!is_null(Session::get('error')))
		{
			$error = Session::get('error');
			return View::make('auth/register')
				->with('error', $error);	
		}

		return View::make('auth/register');
	}

	public function handleRegister()
	{
		$input = Input::all();
		//dd($input);
		$email = $input['email'];
		$input['activated'] = 1;

		try {
			if (strpos($email, '@germantownfriends.org') == false) {
			    throw new Exception("'$email' is not a valide GFS email. Please enter a valid GFS email.");
			}

			$user = Sentry::register($input);
			
		} catch (Exception $e) {
			return Redirect::route('user.register.view')
				->with('error', $e->getMessage());
		}

		return Redirect::action('user.login.view')
			->with('email', $email);
	}

}
