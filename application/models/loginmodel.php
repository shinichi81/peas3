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
class Loginmodel extends CI_Model 
{	
	public function __construct()
	{
		parent::__construct();
		
		if($this->session->userdata('logged_in') == "")
		{
			redirect('/login');
			exit();
		}
	}

};