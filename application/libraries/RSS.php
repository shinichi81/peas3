<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once APPPATH . '/third_party/SimplePie/autoloader.php';
 
define('SIMPLEPIE_CACHE_PATH', APPPATH . '/third_party/SimplePie/cache');
 
class RSS extends SimplePie
{ 
    public $cache_location = SIMPLEPIE_CACHE_PATH;
 
    public function __construct() { 
        parent::__construct();
    }
	
	public function getTitle( $url )
	{
		$this->set_feed_url( $url );
		$this->init();
		$this->handle_content_type(); 
		return $this->get_title();
	}
	
	public function getItems( $url )
	{
		$this->set_feed_url( $url );
		$this->init();
		$this->handle_content_type(); 
		return $this->get_items();
	}
	
};