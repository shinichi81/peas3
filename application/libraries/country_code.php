<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
////////////////////////
//      pEAS v3.0     //
// by Kristaps Ledins //
// ------------------ //
// twitter.com/krysits//
// github.com/krysits //
// krysits@gmail.com  //
// ------------------ //
//(c)2014 krysits.COM //
////////////////////////
class country_code
{
	public $ip = '';
	public $country_code = 'XX';
	private $_ip_url = "http://api.hostip.info/get_json.php?ip=";
	
	public function __construct($ip = Null)
	{
		$this->getCCbyIP($ip);
	}
	
	public function getCCbyIP($ip = Null)
	{
		if(!empty($ip))
		{
			$this->ip = $ip;
			$this->country_code = $this->_geo_loc_code($ip);
		}
		return $this->country_code;
	}
	
	private function _geo_loc_code($ip){
		$url = $this->_ip_url . $ip;
		$data = file_get_contents($url);
		$json_data = json_decode($data);
		return $json_data->country_code;
	}
};