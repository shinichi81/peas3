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
class VerifyLogin extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model','',TRUE);
		$this->load->library('country_code');		
	}	
	
	private function _getCC()
	{		
		$ip = $this->session->userdata('ip_address');
		return $this->country_code->getCCbyIP( $ip );
	}

	function index()
	{
		//This method will have the credentials validation
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');

		if($this->form_validation->run() == FALSE)
		{
			//Field validation failed.&nbsp; User redirected to login page
			$data['title'] = 'Login Failed';
			$this->load->template('login_view', $data);
		}
		else
		{
			//Go to private area
			redirect('home', 'refresh');
		}

	}

	function check_database($password)
	{
		//Field validation succeeded.&nbsp; Validate against database
		$username = $this->input->post('username');

		//query the database
		$result = $this->user_model->login($username, $password);

		if($result)
		{
			$sess_array = array();
			foreach($result as $row)
			{
				$sess_array = array(
					'id' => $row->id,
					'username' => $row->username,
					'email' => $row->email,
					'is_admin' => $row->is_admin,
					'country' => $this->_getCC(),
					'last_activity' => time(),
					'status' => $row->status,
				);
				$this->session->set_userdata('logged_in', $sess_array);
			}
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('check_database', 'Wrong username or password');
			return false;
		}
	}
};