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
class Lists extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('loginmodel');
		$this->load->model('list_manager');
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
	
	private function _welcome()
	{
		// Load user's Lists
		$sess_array = $this->session->userdata('logged_in');
		$user_id = $sess_array['id'];
		
		$user_lists = $this->list_manager->getUserLists($user_id);
		
		if(empty($user_lists))
		{
			$data['lists'] = array();		
			$data['title']= 'Lists';		
		}
		else
		{
			$lists_count = count($user_lists);
			$data['lists'] = $user_lists;		
			$data['title']= 'Lists (' . $lists_count . ')';
		}
			
		$this->load->model('menu_list');
		$data['menu'] = $this->menu_list->get_top_menu();
		
		$this->load->template('lists_view', $data);
	}
	
	public function add_list()
	{
		$this->load->library('form_validation');
		// field name, error message, validation rules
		$this->form_validation->set_rules('name', 'New List Name', 'trim|required|min_length[3]|xss_clean');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->index();
		}
		else
		{
			$sess_array = $this->session->userdata('logged_in');
			$user_id = $sess_array['id'];
			
			$this->list_manager->addList( $this->input->post('name'), $user_id );
			$this->_welcome();
		}
	}
	
	public function deleteList($lid = null)
	{
		if(empty($lid))
		{
			$this->_welcome();
		}
		else
		{
			$sess_array = $this->session->userdata('logged_in');
			$user_id = $sess_array['id'];
			
			$this->list_manager->deleteList( $lid , $user_id );
			$this->_welcome();
		}
	}
	
};