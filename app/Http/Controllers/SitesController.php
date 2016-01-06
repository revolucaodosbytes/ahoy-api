<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp;

class SitesController extends BaseController {

	// @todo grab from the database
	public static $sites_list = [
		//Piratebay links
		"piratebay.org",
		"thepiratebay.org",
		"thepiratebay.se",
		"piratebay.se",
		"1337x.to",
		"btrev.net",
		"clubedodownload.info",
		"dayt.se",
		"extratorrent.cc",
		"forum-maximus.net",
		"gigatuga.com",
		"lusoshare.com",
		"megafilmeshd.net",
		"moov7.net",
		"osmetralhas.pt",
		"osreformados.com",
		"piratatugafilmes.com",
		"poptuga.com",
		"ptxtuga.com",
		"revistas-jornais.blogspot.com",
		"revistas-jornais.blogspot.pt",
		"seriestvix.tv",
		"thewatchseries.to",
		"toppt.net",
		"torrentreactor.com",
		"tuga.io",
		"tugaanimado.net",
		"tugaflix.com",
		"tugaoxe.com",
		"watchseries.lt",
		"baixartv.com",
		"cinefilmesonline.net",
		"elitedosfilmes.com",
		"filmesonline2.com",
		"filmesonlinegratis.net",
		"lusofilmesonline.com",
		"monova.org",
		"primewire.ag",
		"rpds-download.org",
		"scnsrc.me",
		"seriesvideobb.com",
		"sharetuga.com",
		"torrenthound.com",
		"tuga-filmes.info",
		"warez-box.net",
		"watchseries.li",
		"filmesonlineportugueses.wordpress.com",
		"isohunt.to",
		"kat.cr",
		"piratebay.to",
		"rarbg.to",
		"ratotv.net",
		"thepiratebay.la",
		"yts.to",
		"toppt.tv",
		"uber.com",
		"tudodownloadpt-pt2.blogspot.pt",
		"amofilmes.net",
		"avxhome.se",
		"bitsnoop.com",
		"dramatize.com",
		"ilovefilmesonline.com",
		"megafilmeshd.tv",
		"megafilmesonline.net",
		"projectfreetv.so",
		"rapidmoviez.com",
		"sanet.me",
		"sceper.ws",
		"series-cravings.me",
		"topdezfilmes.org",
		"toptorent.org",
		"watch-series-tv.to",
		"armagedomfilmes.biz",
		"baixartorrent.net",
		"clubedodownload.info",
		"cucirco.eu",
		"ddlvalley.rocks",
		"filmesdetv.com",
		"megafilmesonlinehd.com",
		"onlinemovies-pro.com",
		"rlslog.net",
		"seedpeer.eu",
		"supercineonline.com",
		"telona.org",
		"torlock.com",
		"torrentfunk.com",
		"torrents.net",
		"tubeplus.is",
		"tuga-filmes.com",
		"yourbittorrent.com",
		"mp3skull.online",
		"gigatuga.io",
		"megapirata.net",
		"tuga.su",
		"avxsearch.se",
		"tugashare.net",
		"tugashare.in",
		"solarmovie.ac",
		"solarmovie.ph",
		"tugafree.com",
		"stream2watch.co",
		"desportogratis.com",
		"myfreetv.me",
		"moseon.com",
		"warezmovie.net",
		"rojadirecta.me",
		"torrentz.eu",
		"piratatuga.xyz",
		"mytvfree.me",
		"nowwatchtvlive.me",
		"putlocker.is",
		"putlocker.is",
		"index-of-stream.com",
		"geektv.me",
		"nowwatchtvlive.me",
		"livetv.sx",
	];


	public function getSiteList( Request $req ) {

		// Include the IP checking site if not running in production
		if ( App::environment( "local" ) ) {
			self::$sites_list[] = "omeuip.com";
		}


		return self::$sites_list;
	}


}
