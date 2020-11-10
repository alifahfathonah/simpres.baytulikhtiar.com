<?php if(!defined('BASEPATH')) exit('No script access allowed');

class Model_setup extends CI_Model
{
	function update_cutoff($cutoff)
	{
		$this->db->update('app_cutoff', $cutoff);
	}

	function get_cutoff()
	{
		$query = $this->db->query('SELECT * FROM app_cutoff');
		return $query->result();		
	}

	function get_count_periode()
	{
		$query = $this->db->query("SELECT count(*) num FROM app_cutoff");

		$row = $query->row_array();
		return $row['num'];
	}

	function action_insert_cutoff($data)
	{
		$this->db->insert('app_cutoff', $data);
	}

	function action_update_cutoff($data)
	{
		$this->db->update('app_cutoff', $data);
	}

	function get_user()
	{
		$query = $this->db->query("SELECT a.user_id, a.username, a.fullname, a.role_id, b.description FROM app_user AS a
									JOIN app_parameter AS b ON a.branch_code::int = b.parameter_id AND parameter_group = 'cabang'");
		return $query->result();
	}

	function get_branch()
	{
		$query = $this->db->query("SELECT DISTINCT * FROM app_parameter WHERE parameter_group = 'cabang' ORDER BY parameter_id");
		return $query->result();
	}

	function get_branch_by_id($branch_id)
	{
		$query = $this->db->query("SELECT  DISTINCT * FROM app_parameter WHERE parameter_group = 'cabang' AND parameter_id = '$branch_id'");
		return $query->result();
	}

	function update_branch($branch_id, $branch_name)
	{
		$query = $this->db->query("UPDATE app_parameter SET description = '$branch_name' WHERE parameter_id = '$branch_id' AND parameter_group = 'cabang'");
		return $query;
	}

	function get_position()
	{
		$query = $this->db->query("SELECT DISTINCT * FROM app_parameter WHERE parameter_group = 'jabatan' ORDER BY parameter_id");
		return $query->result();
	}

	function get_position_by_id($position_id)
	{
		$query = $this->db->query("SELECT  DISTINCT * FROM app_parameter WHERE parameter_group = 'jabatan' AND parameter_id = '$position_id'");
		return $query->result();
	}

	function update_position($position_id, $position)
	{
		$query = $this->db->query("UPDATE app_parameter SET description = '$position' WHERE parameter_id = '$position_id' AND parameter_group = 'jabatan'");
		return $query;		
	}

	function action_setup_hari_libur($data)
	{
		$this->db->insert('app_hari_libur', $data);
	}

	function get_hari_libur()
	{
		$query = $this->db->query("SELECT * FROM app_hari_libur");
		return $query->result();
	}

	function get_libur_by_id($id)
	{
		$query = $this->db->query("SELECT * FROM app_hari_libur WHERE id = '$id'");
		return $query->result();
	}

	function action_update_setup_hari_libur($data, $id)
	{
		$this->db->where('id', $id);
		$this->db->update('app_hari_libur', $data);
	}

	function get_kategori_parameter()
	{
		$query = $this->db->query("SELECT DISTINCT parameter_group FROM app_parameter ORDER BY parameter_group");
		return $query->result();
	}

	function jqgrid_count_parameter_by_parameter_group($parameter_group)
	{
		$query = $this->db->query("SELECT count(*) num FROM app_parameter WHERE parameter_group = '$parameter_group'");

		$row = $query->row_array();
		return $row['num'];
	}

	function jqgrid_list_parameter_by_parameter_group($parameter_group)
	{
		$query = $this->db->query("SELECT * FROM app_parameter WHERE parameter_group = '$parameter_group' ORDER BY parameter_group, parameter_id");

		return $query->result_array();
	}

	function get_parameter_absent()
	{
		$query = $this->db->query("SELECT * FROM app_parameter WHERE parameter_group = 'absent'");
		return $query->result();
	}

	function get_id_parameter($parameter_group)
	{
		$query = $this->db->query("SELECT max(parameter_id) max FROM app_parameter WHERE parameter_group = '$parameter_group'");
		return $query->result_array();
	}

	function action_add_parameter($data)
	{
		$this->db->insert('app_parameter', $data);
	}

	function action_add_user($data)
	{
		$this->db->insert('app_user', $data);
	}

	function get_user_by_id($user_id)
	{
		$query = $this->db->query("SELECT a.nik, a.username, a.fullname, a.role_id, b.description AS cabang, d.description AS jabatan FROM app_user AS a
									JOIN app_parameter AS b ON a.branch_code = b.parameter_id AND b.parameter_group = 'cabang'
									JOIN app_karyawan_detail AS c ON a.nik = c.nik
									JOIN app_parameter AS d ON c.thru_position = d.parameter_id AND d.parameter_group = 'jabatan'
									WHERE a.user_id = '$user_id'");
		return $query->result();
	}

	function action_update_user($nik, $username, $password, $role_id)
	{
		$query = $this->db->query("UPDATE app_user SET username = '$username', password = '$password', role_id = '$role_id' WHERE nik = '$nik'");
		return $query;
	}
}