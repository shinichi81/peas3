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
class Feed_manager extends CI_Model 
{
	public function getFeeds( $user_id = null )
	{		
		if(empty($user_id)) return 0;
		
		$this -> db -> select('id,url,name');
		$this -> db -> from('feeds');
		$this -> db -> where('user_id', $user_id);
		$this -> db -> where('status', 1);

		$query = $this -> db -> get();
		if($query -> num_rows() > 0)
		{
			return $query->result_array();
		}		
		return false;
	}
	
	public function deleteFeed( $el_id = null, $user_id = null )
	{
		if(empty($el_id)) return false;
		if(empty($user_id)) return false;
		$data = array(
			'status' => 2,
		);
		$this->db->where('id', $el_id);
		$this->db->where('user_id', $user_id);
		$this->db->update('feeds', $data);
		return $this->db->affected_rows();
	}
	
	public function addFeed( $url = "", $user_id = null)
	{
		if(empty($url)) return false;
		//get feed name
		$this->load->library('rss');
		$feedName = $this->rss->getTitle($url);
		if(empty($feedName)) return false;
		// make insert array
		$data = array(
			'url' => $url,
			'ctime' => time(),
			'name' => $feedName,
			'status' => 1,
			'user_id' => $user_id,
		);
		// execute insert query
		$insert_query = $this->db->insert_string('feeds', $data);
		$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO', $insert_query);
		$this->db->query($insert_query);
		if( $this->db->affected_rows() ){
			return $this->db->insert_id();
		}
		return false;		
	}
	
	public function getFeedAndItems( $user_id = null )
	{
		if(empty($user_id)) return false;
		$feeds = $this->getFeeds( $user_id );
		if(empty($feeds)) return false;
		
		$this->load->library('rss');
		$items = array();
		foreach($feeds as $feed)
		{
			foreach( $this->rss->getItems( $feed['url'] ) as $spItem )
			{
				//set Feed data
				$data['feedURL'] = $feed['url'];
				$data['feedName'] = $feed['name'];
				
				//set Item data
				$data['itemId'] = $spItem->get_id();
				$data['itemURL'] = $spItem->get_link();
				$data['itemTitle'] = $spItem->get_title();
				$data['itemDescr'] = $spItem->get_description();
				$data['itemDate'] = $spItem->get_date();
				//$data['item'] = $spItem;
				
				$items[] = $data;
				unset( $data );
			}			
		}
		return $items;
	}
	
};