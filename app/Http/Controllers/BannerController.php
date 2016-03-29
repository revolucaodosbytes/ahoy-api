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
        
        $retrivedMessage = Banner::where('active', '=', 1)->get();
        
        $message["text"] = $retrivedMessage[0]->text;
        $message["url"] = $retrivedMessage[0]->url;
        
		return $message;
	}
}
