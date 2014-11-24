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
class Feeds extends CI_Controller {

	private $_userId = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('loginmodel');
		$session_data = $this->session->userdata('logged_in');
		$this->_userId = $session_data['id'];		
		$this->load->model('feed_manager');
	}

	public function index()
	{		
		$data['title'] = 'Feeds';
		
		// get Feeds List
		$data['feeds'] = $this->feed_manager->getFeeds($this->_userId);
		
		$this->load->model('menu_list');
		$data['menu'] = $this->menu_list->get_top_menu();
		$this->load->template('feeds', $data);
	}
	
	public function addFeed()
	{
		$feedURL = $_REQUEST['feedURL'];
		if(empty($feedURL))
		{
			redirect('/feeds');
		}		
		$this->feed_manager->addFeed($feedURL, $this->_userId);
		$this->index();
	}	
	
	public function deleteFeed( $fid = null )
	{
		if(empty($fid))
		{
			redirect('/feeds');
		}		
		$this->feed_manager->deleteFeed($fid, $this->_userId);
		$this->index();
	}	
};
?>