<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use GrahamCampbell\Throttle\Facades\Throttle;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;

		$this->middleware('jwt.auth', [ 'only' => [ 'renewToken',  ]]);

	}

	public function register( Request $request ) {

		$data = Input::get();

		// Rate limit to 3 requests per 60 minutes
		$throttler = Throttle::get($request, 3, 60);
		if( ! $throttler->attempt() ) {
			$response = Response( ['error'=>'too many requests']);
			$response->setStatusCode('420', "Enhance Your Calm");

			return $response;
		}

		try {
			$user = User::create(
				[
					'username' => $data['username'],
					'password' => Hash::make($data['password']),
					'email'    => $data['email'],
					'name'     => $data['name'],
				]
			);

			if( isset( $data['gcm_token'] ) ) {
				$user->setGCMToken( $data['gcm_token'] );
				$user->save();
			}

		} catch( \Exception $e ) {
			return new Response( ['error' => true, "message" => $e->getMessage()], Response::HTTP_CONFLICT);
		}

		$token = JWTAuth::fromUser($user);

		return ['error'=>false, 'token' => $token ];
	}


	public function login( Request $request ) {
		$data = $request->only('username', 'password');

		$gcm_token = $request->get('gcm_token');

		// Rate limit to 1 requests per minute
		$throttler = Throttle::get($request, 5, 1);
		if( ! $throttler->check() ) {
			$response = Response( ['error'=>'too many wrong requests']);
			$response->setStatusCode('420', "Enhance Your Calm");

			return $response;
		}

		try {
			if ( ! $token = JWTAuth::attempt( $data ) ) {
				// Invalid authentication, hit the throttler
				$throttler->hit();
				return response()->json(['error' => true, 'message' => 'invalid_credentials'], 401);
			}

			// We have a google token, so let's set it
			if ( null != $gcm_token ) {
				$user = JWTAuth::toUser($token);
				$user->setGCMToken( $gcm_token );
				$user->save();
			}

		} catch ( JWTException $e ) {
			$throttler->hit();
			return response()->json(['error' => true, 'message' => 'couldnt_create_token'], 401);
		}

		return response()->json( compact('token') );
	}

	public function renewToken( Request $request ) {

		// Simply refresh the new token
		try {
			$gcm_token = $request->get('gcm_token');

			$new_token = JWTAuth::parseToken()->refresh();

			// We have a google token, so let's set it
			if ( null != $gcm_token ) {
				$user = JWTAuth::toUser($new_token);
				$user->setGCMToken( $gcm_token );
				$user->save();
			}

		} catch (TokenExpiredException $e) {
			return new Response ( [ 'error' => 'token_expired'], $e->getStatusCode() );
		} catch (JWTException $e) {
			return new Response (  [ 'error' => 'token_invalid'], $e->getStatusCode() );
		}

		return new Response( ['token' => $new_token], Response::HTTP_OK );
	}


}
