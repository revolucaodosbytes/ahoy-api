<?php

namespace App\Http\Controllers;

use App\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp;

class BannerController extends BaseController {

	public static function getMessage() {
        
	        $message = [];
	        
	        $retrivedMessage = Banner::orderBy('id', 'DESC')->first();
	        
	        if($retrivedMessage)
	        {
	            $message["text"] = $retrivedMessage->text;
	            $message["url"] = $retrivedMessage->url;
	        }
	        
        	return $message;
	}
}
