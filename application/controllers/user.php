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
class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('loginmodel');
		$this->load->model('user_model');
	}
	
	public function index()
	{
		if($this->session->userdata('logged_in') != "")
		{
			$this->_welcome();
		}
		else{
			$data['title']= 'New User';
			$this->load->template('registration_view', $data);
		}
	}
	
	private function _welcome()
	{
		$sess_array = $this->session->userdata('logged_in');
		$data['username'] = $sess_array['username'];
		
		$data['title'] = 'Welcome';
			
		$this->load->model('menu_list');
		$data['menu'] = $this->menu_list->get_top_menu();
		
		$this->load->template('welcome_view', $data);
	}
	
	public function registration()
	{
		$this->load->library('form_validation');
		// field name, error message, validation rules
		$this->form_validation->set_rules('user_name', 'User Name', 'trim|required|min_length[3]|xss_clean');
		$this->form_validation->set_rules('email_address', 'Your Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
		$this->form_validation->set_rules('con_password', 'Password Confirmation', 'trim|required|matches[password]');

		if($this->form_validation->run() == FALSE)
		{
			$this->index();
		}
		else
		{
			$this->user_model->add_user();
			redirect('/login');
		}
	}
	
};