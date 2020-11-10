<?php if(!defined('BASEPATH')) exit('No script access allowed');

class Resign extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')) redirect('login');
		$this->load->database();
		$this->load->model('model_karyawan');
		$this->load->model('model_setup');
    $this->load->model('M_core', 'mcore');
    set_time_limit(900);
    ini_set('memory_limit', '-1');
  }

  function index()
  {
   $session_data                   = $this->session->userdata('logged_in');
   $data['username']               = $session_data['username'];
   $data['user']                   = $session_data['fullname'];
   $data['role_id']                = $session_data['role_id'];
   $branch_user                    = $session_data['branch_code'];
   $data['branch_user']            = $session_data['branch_code'];
   
   $data['periode_cutoff']         = $this->model_setup->get_cutoff();
   $data['get_karyawan_by_branch'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);
   $data['get_branch']             = $this->model_karyawan->get_branch();
   $data['container']              = 'karyawan/resign_karyawan';

   $this->load->view('core', $data); 		
 }

 function get_karyawan_by_branch()
 {
  $branch_user = $this->input->post('branch');
  $data = $this->model_karyawan->get_karyawan_by_branch($branch_user);

  echo json_encode($data);
}

public function update_resign()
{
  $nik        = $this->input->post('nik');
  $tgl_resign = $this->input->post('tgl_resign');
  $alasan     = $this->input->post('alasan');

  $table = 'app_karyawan_detail';
  $where = array('nik' => $nik);
  $data = array(
    'status' => '50',
    'resign' => $this->convert_date_db_format($tgl_resign),
  );

  $exec = $this->mcore->update($table, $where, $data);

  $table = 'app_resign';
  $data = array(
    'nik'        => $nik,
    'tgl_resign' => $this->convert_date_db_format($tgl_resign),
    'alasan'     => $alasan,
  );
  $exec = $this->mcore->store($table, $data);

  if($exec){
    $result = array(
      'code'        => '200',
      'description' => 'Update Karyawan Berhasil...'
    );
  }else{
    $result = array(
      'code'        => '400',
      'description' => 'Update karyawan gagal, silahkan coba kembali...',
    );
  }
  
  echo json_encode($result);
}

function action_resign()
{
 $session_data = $this->session->userdata('logged_in');
 $user         = $session_data['fullname'];
 
 $nik          = $this->input->post('nik_');
 $alasan       = $this->input->post('alasan');
 $resign       = $this->input->post('tgl');
 $resign       = str_replace('/', '', $resign);
 $resign       = substr($resign,4,4).'-'.substr($resign,0,2).'-'.substr($resign,2,2);

 $data = array('nik' => $nik,
  'tgl_resign' => $resign,
  'alasan_resign' => $alasan,
  'created_by' => $user,
  'created_date' => date('Y-m-d H:i:s'));

 $action_resign = $this->model_karyawan->action_resign($data);
 $action_update_status_resign = $this->model_karyawan->action_update_status_resign($nik);
 redirect('resign');
}

////////////////////////////////////////////////////////////////////////////////////////
  /// FUNCTION HELPER ///////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////
  
  private function convert_date_db_format($date)
  {
    $str_date = strtotime($date);
    $db_date  = date('Y-m-d', $str_date);
    return $db_date;
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  /// FUNCTION HELPER ///////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////
}