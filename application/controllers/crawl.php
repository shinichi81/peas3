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
class Crawl extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('loginmodel');
		$this->load->model('list_manager');
		$this->load->model('email_manager');
		$this->load->model('web_crawler');
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
	
	public function start($list_id = null)
	{
		$url = $this->input->post('url');
		$this->web_crawler->startCrawl( $url );
		$this->_welcome( $list_id , $this->web_crawler->_messages);
	}
	
	private function _welcome($list_id = null , $msgs = null)
	{
		// Load user's Lists
		$sess_array = $this->session->userdata('logged_in');
		$user_id = $sess_array['id'];
		
		$user_lists = $this->list_manager->getUserLists($user_id);
		
		if(empty($user_lists))
		{
			redirect('/lists');
			exit();
		}
		else
		{
			$lists_count = count($user_lists);
			$data['lists'] = $user_lists;		
			$data['title']= 'Crawl Web To This List';
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
			//$data['emails'] = $this->email_manager->getEmailsByList( $list_id, $user_id );
		}
		
		$data['msg'] = $msgs;
		
		$this->load->model('menu_list');
		$data['menu'] = $this->menu_list->get_top_menu();		
		$this->load->template('crawl_view', $data);
	}
	
};