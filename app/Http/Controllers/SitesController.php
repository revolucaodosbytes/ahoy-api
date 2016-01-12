<?php

namespace App\Http\Controllers;

use App\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp;

class SitesController extends BaseController {

	// @todo grab from the database
	private static $sites_list = [
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
		"rlsbb.com",
		"elitetorrent.net",
	];

	public static function getAllSites() {

		$db_sites = Site::all();

		foreach( $db_sites as $site ) {
			self::$sites_list[] = $site->url;
		}

		return self::$sites_list;

	}


	public function getSiteList( Request $req ) {

		// Include the IP checking site if not running in production
		if ( App::environment( "local" ) ) {
			self::$sites_list[] = "omeuip.com";
		}


		return self::getAllSites();
	}

	public function getHostsList( Request $req ) {

		$hosts = [];

		foreach( self::getAllSites() as $site ) {

			$host = Cache::remember('host-' . $site, 6 * 60 + rand(1,30), function() use ($site) {
				return gethostbyname( $site );
			} );

			// If the gethostbyname fails
			if ( $host == $site || $host == '127.0.0.1' )
				continue;

			$hosts[] = $host;
		}

		return $hosts;

	}

	/**
	 * Tests if a given site is present in the site list
	 *
	 * @param $site the hostname to test
	 *
	 * @return bool true if the site is in the list
	 */
	public static function siteExists( $site ) {

		foreach( self::getAllSites() as $site_in_list ) {
			if ( strpos( $site, $site_in_list ) !== false ) {
				return true;
			}
		}

		return false;
	}

}
