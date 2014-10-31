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
class Menu_list extends CI_Model 
{	
	public function get_top_menu()
	{		
		$data=array(
			'/home' => 	'Home',
			'/lists' => 'Lists',
			'/emails' =>'Emails',
			'/crawl' =>'Crawl',
			'/messages' => 'Messages',
			
			'/logout' => 'Logout[x]',
		);
		return $data;
	}

};

