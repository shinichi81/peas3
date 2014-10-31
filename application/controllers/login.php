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
class Login extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// perform Logout before Login
		require_once(dirname(__FILE__)."/logout.php");
		new Logout();
		// show Login view
		$data['title'] = 'pEAS Login';
		$this->load->template('login_view', $data);
	}

};