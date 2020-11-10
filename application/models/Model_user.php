<?php if(!defined('BASEPATH')) exit('No script access allowed');

class Model_user extends CI_Model
{	
	function login($username, $password)
	{
		$query = $this->db->query("SELECT * FROM app_user WHERE username = '$username' AND password = '$password'");

		return $query->result();
			
	}

	function update_user($data, $user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update('app_user', $data);
	}

	function truncate_user()
	{
		$this->db->truncate('app_user');
	}
}