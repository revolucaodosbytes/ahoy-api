<?php

namespace App\Libs;
error_reporting(E_ALL ^ E_NOTICE);
/**
 * PHPProxyChecker class.
 * @author Stanislav Afanasiev <stas.progger[at]gmail.com>
 * @created 15.12.2010
 * @version 1.0
 */
class PHPProxyChecker {

	/**
	 * Contain result of query
	 * @var $result
	 * @param void
	 */
	private static $result;


	/**
	 * Check the proxy to support different methods of inquiry. Determining the type of proxy
	 * @static
	 * @param string $ip
	 * @param string $type
	 * @param method $method
	 * @param bool $ssl
	 * @return void
	 */
	private static function sendQuery($ip,$type = '',$method='GET',$ssl=false) {

		$handle = curl_init(HTTP_GATE);
		if($ssl) {
			$handle = curl_init(HTTPS_GATE);
		}

		curl_setopt($handle, CURLOPT_PROXY,trim($ip));

		if($type=='socks') {
			curl_setopt($handle, CURLOPT_PROXYTYPE,CURLPROXY_SOCKS5);
		}  else {
			curl_setopt($handle, CURLOPT_PROXYTYPE,CURLPROXY_HTTP);
		}

		curl_setopt($handle, CURLOPT_TIMEOUT, CHECK_TIMEOUT);

		curl_setopt($handle, CURLOPT_USERAGENT,"Mozilla/4.0");
		curl_setopt($handle, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($handle, CURLOPT_COOKIE, 'checkproxy=1;');
		curl_setopt($handle, CURLOPT_REFERER, 'http://yandex.ru/');

		if($method == 'POST') {
			curl_setopt($handle, CURLOPT_POST, 1);
			curl_setopt($handle, CURLOPT_POSTFIELDS,'&checkpost=1');
		}

		if($ssl) {
			curl_setopt($handle, CURLOPT_SSL_VERIFYPEER,0);
		}

		$startTime = microtime(1);
		$resultQuery = curl_exec($handle);
		$endTime = microtime(1);

		$resultQuery .= round($endTime-$startTime,3);

		$info = curl_getinfo($handle);
		curl_close($handle);

		unset($handle);
		if($info['http_code']==200) {
			return $resultQuery;
		}   else {
			return false;
		}

	}

	/**
	 * @static
	 * @param string $typeName
	 * @param string $typeCode
	 * @param array $arServer
	 * @param string $ip
	 * @param array $arTmp
	 * @param bool $ssl
	 * @return void
	 */
	private static function checkParam($typeName,$typeCode,$arServer,$ip,$arTmp,$ssl=false) {

		self::$result['TYPE_NAME'] = $typeName;
		self::$result['TYPE_CODE'] = $typeCode;
		self::$result['QUERY_TIME'] = $arTmp[3];
		self::$result['SUPPORT_GET'] = 'Y';

		if($arServer['HTTP_COOKIE'] == 'checkproxy=1;') {
			self::$result['SUPPORT_COOKIE'] = 'Y';
		}   else{
			self::$result['SUPPORT_COOKIE'] = 'N';
		}

		if($arServer['HTTP_REFERER'] == 'http://yandex.ru/') {
			self::$result['SUPPORT_REFERER'] = 'Y';
		}   else {
			self::$result['SUPPORT_REFERER'] = 'N';
		}

		// send POST query
		$sendPost = self::sendQuery($ip,'','POST');

		self::$result['SUPPORT_POST'] = 'N';
		if($sendPost) {

			$tmp = explode('<br>',$sendPost);
			$arPost = unserialize($tmp[2]);

			if($arPost['checkpost']==1) {
				self::$result['SUPPORT_POST'] = 'Y';
			}

		}

		// check HTTPS connect
		$sslConnect = self::sendQuery($ip,'','GET',true);

		if($sslConnect) {
			self::$result['SUPPORT_SSL'] = 'Y';
		} else {
			self::$result['SUPPORT_SSL'] = 'N';
		}

		unset($sendPost,$tmp,$arPost,$sslConnect);
	}

	/**
	 * @static
	 * @param string $ip
	 * @return array $result
	 */
	public static function checkProxy($ip) {

		$explodeIp = explode(':',$ip);

		$ProxyIp = $explodeIp[0];
		$ProxyPort = $explodeIp[1];


		// start check
		$checkOnHttp = self::sendQuery($ip);
		self::$result = array();

		if(!$checkOnHttp) {

			$checkOnSocks = self::sendQuery($ip,'socks');

			if($checkOnSocks) {
				self::$result['TYPE'] = 'SOCKS4/5';

				$arTmp = explode('<br>',$checkOnSocks);
				$arServer = unserialize($arTmp[0]);
			}

		} else {
			self::$result['TYPE'] = 'HTTP';

			$arTmp = explode('<br>',$checkOnHttp);
			$arServer = unserialize($arTmp[0]);
		}

		if(!empty($arServer)) {

			// check transparnt proxy
			if(($arServer['SERVER_ADDR'] == $arServer['HTTP_X_FORWARDED_FOR']) || strstr($arServer['HTTP_X_FORWARDED_FOR'],$arServer['SERVER_ADDR']))  {

				self::checkParam('Transparent proxy',0,$arServer,$ip,$arTmp);

			}

			// check on anonymous proxy
			if(empty( self::$result['TYPE_NAME']) && !empty($arServer['HTTP_VIA'])) {

				self::checkParam('Anonymous proxy',1,$arServer,$ip,$arTmp);

			}

			// check on high anonymous proxy
			if(empty( self::$result['TYPE_NAME']) && empty($arServer['HTTP_VIA'])) {

				self::checkParam('High anonymous / Elite proxy',2,$arServer,$ip,$arTmp);

			}

			// other
			if(!isset(self::$result['TYPE_NAME'])) {

				self::checkParam('Undefined',3,$arServer,$ip,$arTmp);

			}

			self::$result['PROXY_IP'] = $ProxyIp;
			self::$result['PROXY_PORT'] = $ProxyPort;

		}  else {
			self::$result['NOT_WORKING'] = 'Y';
		}
		unset($arServer,$arTmp,$explodeIp,$ProxyIp,$ProxyPort,$checkOnHttp,$checkOnSocks);
		return self::$result;

	}
}
?>