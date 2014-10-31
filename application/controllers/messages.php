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
class Messages extends CI_Controller {

	private $_userId = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('loginmodel');	
		$this->load->model('list_manager');
	}

	public function index()
	{
		if($this->session->userdata('logged_in') != "")
		{	//var_dump($_REQUEST);exit();
			$session_data = $this->session->userdata('logged_in');
			$data['username'] = $session_data['username'];
			$this->_userId = $session_data['id'];
			$data['title'] = 'Messages';
			
			// Load user's Lists			
			$user_lists = $this->list_manager->getUserLists($this->_userId);
			
			if(empty($user_lists))
			{
				$data['lists'] = array();		
				$data['title'] = 'No Email Lists To Send To';
				redirect('/lists');
				exit();
			}
			else
			{
				$lists_count = count($user_lists);
				$data['lists'] = $user_lists;
			}
			
			//$this->_welcome();
			
			$this->load->model('menu_list');
			$data['menu'] = $this->menu_list->get_top_menu();
			$this->load->template('messages', $data);
		}
		else
		{
			 //If no session, redirect to login page
			 redirect('/login');
		}
	}
	
	public function sendMessage()
	{
		if($this->session->userdata('logged_in') != "")
		{
			$session_data = $this->session->userdata('logged_in');
			$data['username'] = $session_data['username'];
			$this->_userId = $session_data['id'];

			$subject = $this->input->post('subject');
			$msg = $this->input->post('msg');
			$lists_to = $this->input->post('to_list');
			
			$this->load->model('msg_manager');
			$data['sent'] = $this->msg_manager->sendMsg($subject, $msg, $lists_to);
			
			$data['title'] = 'Message Sent';
			
			$this->load->model('menu_list');
			$data['menu'] = $this->menu_list->get_top_menu();
			$this->load->template('message_sent', $data);
		}
		else
		{
			 //If no session, redirect to login page
			 redirect('/login');
		}
	}
	
};
?>