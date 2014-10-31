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
class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();		
		$this->load->model('loginmodel');
	}

	public function index()
	{
		if($this->session->userdata('logged_in') != "")
		{			
			$session_data = $this->session->userdata('logged_in');
			$data['username'] = $session_data['username'];
			$userId = $session_data['id'];
			$data['fromEmail'] = $session_data['email'];
			
			$data['title'] = 'Home';
			
			$this->load->model('list_manager');
			$data['listsCount'] = $this->list_manager->getUsersListsCount($userId);
			
			$data['emailsCount'] = 0;
			$this->load->model('email_manager');
			$data['emailsCount'] = $this->email_manager->getEmailsCountByUserId( $userId );
			
			$this->load->model('menu_list');
			$data['menu'] = $this->menu_list->get_top_menu();
			$this->load->template('home', $data);
		}
		else
		{
			 //If no session, redirect to login page
			 redirect('/login', 'refresh');
		}
	}

};
?>