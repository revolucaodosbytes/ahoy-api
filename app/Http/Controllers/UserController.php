<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use GrahamCampbell\Throttle\Facades\Throttle;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller {

	function __construct() {

		$this->middleware('jwt.auth');
	}

	public function getCurrentUser( Request $request ) {

		$user = JWTAuth::parseToken()->toUser();

		// the token is valid and we have found the user via the sub claim
		return response()->json($user);

	}



}
