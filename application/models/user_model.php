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
class User_model extends CI_Model 
{
	public function login($username, $password) 
	{
		$this -> db -> select('id, username, password, email, country, is_admin, status');
		$this -> db -> from('users');
		$this -> db -> where('username', $username);
		$this -> db -> where('password', sha1(md5($password)));
		$this -> db -> limit(1);

		$query = $this -> db -> get();

		if($query -> num_rows() == 1)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}
	
	public function add_user()
	{		
		$this->load->library('country_code');
		$data=array(
			'username' => $this->input->post('user_name'),
			'email' => $this->input->post('email_address'),
			'password' => sha1(md5($this->input->post('password'))),
			'country' => $this->_getCC(),
			'last_activity' => time(),
			'status' => 1,
		);
		$insert_query = $this->db->insert_string('users',$data);
		$insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO', $insert_query);
		$this->db->query($insert_query);
		return  $this->db->insert_id();
	}
	
	private function _getCC()
	{		
		$ip = $this->session->userdata('ip_address');
		return $this->country_code->getCCbyIP( $ip );
	}

};

