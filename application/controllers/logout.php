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
class Logout extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->_logout();
	}

	public function index()
	{
		$this->_logout();
		redirect('/login');
	}

	private function _logout()
	{	
		$this->session->unset_userdata('logged_in');
		if(!empty($_SESSION)) session_destroy();
		//redirect('/login');
	}
};
?>