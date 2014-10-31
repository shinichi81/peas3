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
class Email_manager extends CI_Model 
{
	public function getEmailsCountByUserId( $user_id = null )
	{		
		if(empty($user_id)) return 0;
		
		$this -> db -> select('id');
		$this -> db -> from('lists');
		$this -> db -> where('user_id', $user_id);
		$this -> db -> where('status', 1);

		$query = $this -> db -> get();
		if($query -> num_rows() > 0)
		{
			$list_id_arr = array();
			$listsCount = 0;
			foreach($query->result() as $list)
			{
				$list_id_arr[ $list->id ] = $list->id;
			}
			foreach($list_id_arr as $list_id)
			{
				$listsCount += count($this->_getEmailsList( $list_id ));
			}
			return $listsCount;
		}
		
		return 0;
	}
	
	public function getEmailsByList($list_id = null, $user_id = null) 
	{
		if(empty($list_id)) return false;
		if(empty($user_id)) return false;
		
		$this -> db -> select('id, name, user_id, status');
		$this -> db -> from('lists');
		$this -> db -> where('user_id', $user_id);
		$this -> db -> where('status', 1);

		$query = $this -> db -> get();
		if($query -> num_rows() > 0)
		{
			$list_id_arr = array();
			foreach($query->result() as $list)
			{
				$list_id_arr[ $list->id ] = $list->id;
			}
			
			if(in_array($list_id, $list_id_arr))
			{
				return $this->_getEmailsList($list_id); // get Real Emails List
			}
		}
		else
		{
			return false;
		}
	}
	
	public function _getEmailsList( $list_id = null )
	{
		if(empty($list_id)) return false;
		$this->db->select('*');
		$this->db->from('lists_emails');
		$this->db->join('emails', 'lists_emails.email_id = emails.id');
		$this->db->where('lists_emails.status', 1);		
		$this->db->where('list_id', $list_id);		
		$query = $this->db->get();
		if($query -> num_rows() > 0)
		{
			return $query->result_array();			
		}
		return false;
	}
	
	public function addEmail($email = '', $user_id = null, $list_id = null )
	{		
		if(empty($email)) return false;
		$this->load->helper('email');
		if (!valid_email($email)) return false;
		
		if(empty($user_id)) return false;
		if(empty($list_id)) return false;
		
		$data = array(
			'email' => $email,
			'last_activity' => time(),
			'status' => 1,
		);
		$insert_query = $this->db->insert_string('emails', $data);
		$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO', $insert_query);
		$this->db->query($insert_query);
		$last_email_id = $this->db->insert_id();
		
		if(!empty($last_email_id) && $last_email_id != 0)
		{
			$data = array(
				'list_id' => $list_id,
				'email_id' => $last_email_id,
				'status' => 1,
			);
			$this->db->insert('lists_emails', $data);
			return  $this->db->insert_id();
		}
		else
		{
			$this->db->select('id')->from('emails')->where('email',$email)->limit(1);
			$query = $this->db->get();
			if($query -> num_rows() > 0)
			{
				$row = $query->result();	
				$email_id = $row[0]->id;
				
				$data = array(
					'list_id' => $list_id,
					'email_id' => $email_id,
					'status' => 1,
				);
				
				$insert_query = $this->db->insert_string('lists_emails', $data);
				$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO', $insert_query);
				$this->db->query($insert_query);
				return  $this->db->insert_id();
			}
		}
		
		return false;
	}

	public function deleteEmail( $el_id = null )
	{
		if(empty($el_id)) return false;
		$data = array(
			'status' => 2,
		);
		$this->db->where('id', $el_id);
		$this->db->update('lists_emails', $data);
		return $this->db->affected_rows();
	}
	
};