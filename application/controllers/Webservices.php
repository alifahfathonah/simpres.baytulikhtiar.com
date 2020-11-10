<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Webservices extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('model_karyawan');
		$this->load->model('model_setup');
		//$this->load->model('model_profile');
	}
	
	function get_fp_from_client()
	{
		$datas = json_decode(file_get_contents('php://input'), true);
		$post_nik = $datas['post_nik'];
		$get_fp = $datas['get_fp'];
		$key_nik = array_keys($post_nik);

		for($n = 0; $n < count($get_fp); $n++)
		{
			$action_get_from_client = $this->model_karyawan->action_get_from_client($get_fp[$n]);
		}

		$nik = '';
		for($i = 0; $i <= end($key_nik); $i++)
		{
			if(array_key_exists($i, $post_nik))
			{
				if($i != '0')
				{
					$post_nik_ = "'".$post_nik[$i]."'";
					$nik = $post_nik_.','.$nik;
				}else
				{
					$post_nik_ = "'".$post_nik[$i]."'";
					$nik = $post_nik_;
				}
			}else
			{
				continue;
			}
		}
		$action_get_nik_by_fp = $this->model_karyawan->action_get_nik_by_fp($nik);

		$data = array('nik' => $action_get_nik_by_fp);
		print($data);

		echo json_encode($data);
	}
	
	function post_absen_by_client()
	{
		$data = array('nik' => $this->input->post('pin'),
						'waktu' => $this->input->post('waktu'),
						'tanggal' => $this->input->post('tanggal'));

		$post_absen_by_client = $this->model_karyawan->post_absen_by_client($data);
	}

	function get_detail()
	{
		$branch_code = $this->input->post('branch_code');

		$get_cuttoff = $this->model_setup->get_cutoff();
		$get_libur = $this->model_karyawan->get_libur();
		$get_kantor = $this->model_karyawan->get_branch();
		$get_jabatan = $this->model_karyawan->get_position();
		$get_karyawan_by_kantor = $this->model_karyawan->get_karyawan_by_branch($branch_code);
		$get_admin = $this->model_setup->get_user();

		$data = array('get_cuttoff' => $get_cuttoff,
						'get_libur' => $get_libur,
						'get_kantor' => $get_kantor,
						'get_jabatan' => $get_jabatan,
						'get_karyawan_by_kantor' => $get_karyawan_by_kantor,
						'get_admin' => $get_admin);
		
		echo json_encode($data);
	}

	function get_user()
	{
		$branch_user = $this->input->post('branch_user');

		$get_karyawan_by_branch = $this->model_karyawan->get_karyawan_by_branch($branch_user);
		$get_user = $this->model_profile->get_id_user();

		$data = array('get_user' => $get_user,
						'get_karyawan_by_branch' => $get_karyawan_by_branch);
		
		echo json_encode($data);
	}
}