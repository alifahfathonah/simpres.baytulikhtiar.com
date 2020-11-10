<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LemburController extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if(!$this->session->userdata('logged_in')) redirect('login');
    $this->load->model('model_setup');
    $this->load->model('model_karyawan');
    $this->load->model('model_user');
    $this->load->model('M_core', 'mcore');
  }

  public function index()
  {
    $session_data           = $this->session->userdata('logged_in');
    $data['username']       = $session_data['username'];
    $data['user']           = $session_data['fullname'];
    $data['role_id']        = $session_data['role_id'];
    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $data['container']      = 'lembur/table';
    $data['get_branch']     = $this->mcore->get_branch_own();

    $this->load->view('core', $data);
  }

  public function data()
  {
    $id_cabang            = $this->input->get('id_cabang');
    $id_cabang            = ($id_cabang == "semua")? null : $id_cabang;
    $data['get_karyawan'] = $this->mcore->data_karyawan_lembur($id_cabang);

    return $this->load->view('lembur/detail', $data, FALSE);
  }

  public function create()
  {
    $session_data           = $this->session->userdata('logged_in');
    $data['username']       = $session_data['username'];
    $data['user']           = $session_data['fullname'];
    $data['role_id']        = $session_data['role_id'];
    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $data['get_branch']     = $this->mcore->get_branch_own();
    $data['get_karyawan']   = $this->mcore->get_list_karyawan();
    $data['container']      = 'lembur/create';

    $this->load->view('core', $data);
  }

  public function store()
  {
		$tgl_obj    = new DateTime();
		$nik        = $this->input->post('nik');
		$tgl        = $this->input->post('tgl');
		$jam_a      = $this->input->post('jam_a');
		$jam_b      = $this->input->post('jam_b');
		$jam_a      = $tgl.' '.$jam_a;
		$jam_b      = $tgl.' '.$jam_b;
		$keterangan = $this->input->post('keterangan');
		$approval   = $this->input->post('approval');
		$created_at = date('Y-m-d H:i:s');

    $data = array(
      'nik'        => $nik,
      'tgl'        => $tgl,
      'jam_a'      => $jam_a,
      'jam_b'      => $jam_b,
      'keterangan' => $keterangan,
      'approval'   => $approval,
      'created_at' => $created_at,
    );
    $table = 'app_lembur';

    $exec = $this->mcore->store($table, $data);

    if($exec === FALSE){
      $result = array(
        'code' => '500',
        'description' => 'Proses Pengajuan Lembur Gagal'
      );

    }else{

			$jam_ao   = $tgl_obj->createFromFormat('H:i:s', $this->input->post('jam_a'));
			$jam_bo   = $tgl_obj->createFromFormat('H:i:s', $this->input->post('jam_b'));
			$diff     = $jam_bo->diff($jam_ao);
			$interval = $diff->h;

    	$this->mcore->update('app_absensi_manual', ['nik' => $nik, 'tanggal' => $tgl], ['lembur' => $interval]);
      $result = array(
        'code' => '200',
        'description' => 'Proses Pengajuan Lembur Berhasil'
      );
    }

    echo json_encode($result);

  }

  public function destroy()
  {
    $id    = $this->input->post('id');

    $table = 'app_lembur';
    $where = array('id' => $id);
    $exec  = $this->mcore->destroy($table, $where);

    if($exec === FALSE){
      $result = array(
        'code' => '500',
        'description' => 'Proses Pembatalan Lembur Gagal'
      );

    }else{
      $result = array(
        'code' => '200',
        'description' => 'Proses Pembatalan Lembur Berhasil'
      );
    }

    echo json_encode($result);
  }

}

/* End of file LemburController.php */
/* Location: ./application/controllers/LemburController.php */