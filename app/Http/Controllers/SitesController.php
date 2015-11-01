<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp;

class SitesController extends BaseController {

	// @todo grab from the database
	private $sites_list = [
		"piratebay.org",
		"thepiratebay.org",
		"thepiratebay.se",
		"piratebay.se",
		"omeuip.com",
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
	];


	public function getSiteList( Request $req ) {
		return $this->sites_list;
	}


}