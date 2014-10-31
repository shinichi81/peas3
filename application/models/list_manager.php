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
class List_manager extends CI_Model 
{
	public function getUserLists($user_id) 
	{
		$this -> db -> select('id, name, user_id, status');
		$this -> db -> from('lists');
		$this -> db -> where('user_id', $user_id);
		$this -> db -> where('status', 1);
		//$this -> db -> limit(1);

		$query = $this -> db -> get();

		if($query -> num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}
	
	public function getUsersListsCount( $user_id = null )
	{
		$result = $this->getUserLists($user_id);
		if(!empty($result))
		{
			return count($result);
		}
		return 0;
	}
	
	public function addList($name = '', $user_id = null)
	{		
		if(empty($user_id)) return false;
		$data=array(
			'name' => $name,
			'user_id' => $user_id,
			'date_created' => time(),
			'status' => 1,
		);
		$this->db->insert('lists', $data);
	}

	public function deleteList($list_id = null, $user_id = null)
	{
		if(empty($list_id)) return false;
		if(empty($user_id)) return false;
		$data = array(
			'status' => 2,
		);
		$this->db->where('id', $list_id);
		$this->db->where('user_id', $user_id);
		$this->db->update('lists', $data);
		return $this->db->affected_rows();
	}
	
};