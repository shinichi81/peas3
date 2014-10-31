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
class Emails extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('loginmodel');
		$this->load->model('list_manager');
		$this->load->model('email_manager');
	}
	
	public function index()
	{
		if($this->session->userdata('logged_in') != "")
		{
			$this->_welcome();
		}
		else{
			redirect('/login');
		}
	}
	
	public function showList($list_id = null)
	{
		if($this->session->userdata('logged_in') != "")
		{
			$this->_welcome( $list_id );
		}
		else{
			redirect('/login');
		}
	}
	
	private function _welcome($list_id = null)
	{
		// Load user's Lists
		$sess_array = $this->session->userdata('logged_in');
		$user_id = $sess_array['id'];
		
		$user_lists = $this->list_manager->getUserLists($user_id);
		
		if(empty($user_lists))
		{
			$data['lists'] = array();		
			$data['title']= 'Emails';
			redirect('/lists');
			exit();
		}
		else
		{
			$lists_count = count($user_lists);
			$data['lists'] = $user_lists;		
			$data['title']= 'Emails in ' . $lists_count . ' Lists';
		}
		// get select onchange list_id value
		$post_list_id = $this->input->post('list_id');
		if(!empty($post_list_id))
		{
			$list_id = $post_list_id;
		}
		if(!empty($list_id)){
			$data['list_id'] = $list_id;
			// show email list
			$data['emails'] = $this->email_manager->getEmailsByList( $list_id, $user_id );
		}
		
		$this->load->model('menu_list');
		$data['menu'] = $this->menu_list->get_top_menu();
		
		$this->load->template('emails_view', $data);
	}
	
	public function addEmail()
	{
		$this->load->library('form_validation');
		// field name, error message, validation rules
		$this->form_validation->set_rules('email', 'New Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('list_id', 'List ID', 'trim|required');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->index();
		}
		else
		{
			$sess_array = $this->session->userdata('logged_in');
			$user_id = $sess_array['id'];
			
			$this->email_manager->addEmail( $this->input->post('email'), $user_id , $this->input->post('list_id'));
			
			$this->_welcome();
		}
	}
	
	public function uploadFile()
	{
		$sess_array = $this->session->userdata('logged_in');
		$user_id = $sess_array['id'];
		
		$tmpFileName = md5(time().rand(1000,9999)) . ".txt";
		
		$config['upload_path'] = APPPATH . 'uploads/';
		$config['allowed_types'] = 'txt';
		$config['file_name'] = $tmpFileName;
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('userfile'))
		{
			$error = array('error' => $this->upload->display_errors());
			//var_dump($error);exit();
			$this->load->template('upload_form', $error);
		}
		else
		{
			$uploadData = $this->upload->data();
			$ems = $this->_getEmailsFromTextFile( $uploadData['full_path'] );
			if(!empty($ems))
			{
				foreach($ems as $email)
				{
					$this->email_manager->addEmail( $email , $user_id , $this->input->post('list_id'));	
				}
			}
			$this->_welcome();
		}
	}
	
	public function deleteEmail($lid = null)
	{
		if(empty($lid))
		{
			$this->_welcome();
		}
		else
		{			
			$this->email_manager->deleteEmail( $lid );
			$this->_welcome();
		}
	}
	
	private function _getEmailsFromTextFile($file = '')
	{
		if(empty($file)) return false;
		$this->load->helper('email');
		$arr = array();
		// file open - read- close
		$fileDataLines = file( $file );
		if(!empty($fileDataLines))
		{
			foreach($fileDataLines as $line)
			{
				$email = trim($line);
				
				if (valid_email($email))
				{
					$arr[ $email ] = $email;
				}
			}
		}
		return $arr;
	}
	
};