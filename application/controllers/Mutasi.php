<?php if (!defined('BASEPATH')) exit('No script access allowed');

class Mutasi extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata('logged_in')) redirect('login');
    $this->load->model('model_setup');
    $this->load->model('model_karyawan');
    $this->load->model('M_core', 'mcore');
  }

  function mutasi_jabatan()
  {
    $session_data     = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['user']     = $session_data['fullname'];
    $data['role_id']  = $session_data['role_id'];
    $branch_user      = $session_data['branch_code'];

    if ($data['role_id'] == '0') {
      $data['get_karyawan'] = $this->mcore->get_list_karyawan()->result();
    } else {
      $data['get_karyawan'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);
    }

    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $data['get_position']   = $this->model_karyawan->get_position();
    $data['container']      = 'karyawan/mutasi_jabatan';

    $this->load->view('core', $data);
  }

  function get_jabatan_by_nik()
  {
    $nik  = $this->input->post('nik');
    //$data = $this->model_karyawan->get_karyawan_by_nik_($nik);
    $data = $this->mcore->get_karyawan_by_nik($nik)->result();

    echo json_encode($data);
  }

  function action_mutasi_jabatan()
  {
    $session_data = $this->session->userdata('logged_in');
    $user = $session_data['fullname'];

    $nik = $this->input->post('nik');
    $from_position = $this->input->post('from_position');
    $jabatan = $this->input->post('thru_position');

    $tgl_mutasi       = $this->input->post('tgl_mutasi');
    $tgl_mutasi       = str_replace('/', '', $tgl_mutasi);
    $tgl_mutasi       = substr($tgl_mutasi, 4, 4) . '-' . substr($tgl_mutasi, 0, 2) . '-' . substr($tgl_mutasi, 2, 2);

    $data = array(
      'nik' => $nik,
      'from_position' => $from_position,
      'thru_position' => $jabatan,
      'tgl_mutasi' => $tgl_mutasi,
      'created_by' => $user,
      'created_date' => date('Y-m-d H:i:s')
    );

    $this->db->trans_begin();
    $this->model_karyawan->action_update_jabatan($jabatan, $nik);
    $this->model_karyawan->action_insert_mutasi_jabatan($data);
    if ($this->db->trans_status() === true) {
      $this->db->trans_commit();
      redirect('mutasi/mutasi_jabatan');
    } else {
      $this->db->trans_rollback();
      redirect('mutasi/mutasi_jabatan');
    }
  }

  function mutasi_cabang()
  {
    $session_data     = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['user']     = $session_data['fullname'];
    $data['role_id']  = $session_data['role_id'];
    $branch_user      = $session_data['branch_code'];

    if ($data['role_id'] == '0') {
      $data['get_karyawan'] = $this->mcore->get_list_karyawan()->result();
    } else {
      $data['get_karyawan'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);
    }

    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $data['get_branch']     = $this->model_karyawan->get_branch();
    $data['container']      = 'karyawan/mutasi_cabang';

    $this->load->view('core', $data);
  }

  function get_cabang_by_nik()
  {
    $nik = $this->input->post('nik');

    $data['get_karyawan_by_nik'] = $this->model_karyawan->get_karyawan_by_nik_($nik);

    $session_data = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['user'] = $session_data['fullname'];
    $data['role_id'] = $session_data['role_id'];

    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $data['get_branch'] = $this->model_karyawan->get_branch();
    $data['get_position'] = $this->model_karyawan->get_position();
    $data['container'] = 'karyawan/mutasi_cabang_detail';

    $this->load->view('core', $data);
  }

  function action_mutasi_cabang()
  {
    $session_data = $this->session->userdata('logged_in');
    $user = $session_data['fullname'];

    $nik = $this->input->post('nik');
    $from_branch = $this->input->post('from_branch');
    $cabang = $this->input->post('thru_branch');

    $tgl_mutasi       = $this->input->post('tgl_mutasi');
    $tgl_mutasi       = str_replace('/', '', $tgl_mutasi);
    $tgl_mutasi       = substr($tgl_mutasi, 4, 4) . '-' . substr($tgl_mutasi, 0, 2) . '-' . substr($tgl_mutasi, 2, 2);

    $data = array(
      'nik' => $nik,
      'from_branch' => $from_branch,
      'thru_branch' => $cabang,
      'tgl_mutasi' => $tgl_mutasi,
      'created_by' => $user,
      'created_date' => date('Y-m-d H:i:s')
    );

    $this->db->trans_begin();
    $this->model_karyawan->action_update_cabang($cabang, $nik);
    $this->model_karyawan->action_insert_mutasi_cabang($data);
    if ($this->db->trans_status() === true) {
      $this->db->trans_commit();
      redirect('mutasi/mutasi_cabang');
    } else {
      $this->db->trans_rollback();
      redirect('mutasi/mutasi_cabang');
    }
  }

  function get_karyawan_by_nik()
  {
    $nik = $this->input->post('nik');
    $data = $this->model_karyawan->get_karyawan_by_nik_($nik);

    echo json_encode($data);
  }

  function mutasi_status()
  {
    $session_data     = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['user']     = $session_data['fullname'];
    $data['role_id']  = $session_data['role_id'];
    $branch_user      = $session_data['branch_code'];

    if ($data['role_id'] == '0') {
      $data['get_karyawan'] = $this->mcore->get_list_karyawan();
    } else {
      $data['get_karyawan'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);
    }

    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $data['get_status']     = $this->model_karyawan->get_status_mutasi();
    $data['get_position']   = $this->model_karyawan->get_position();
    $data['cabangs']        = $this->mcore->get_where('app_parameter', ['parameter_group' => 'cabang']);
    $data['statuss']        = $this->model_karyawan->get_param_status();
    $data['container']      = 'karyawan/mutasi_status';

    $this->load->view('core', $data);
  }

  public function action_update_status_karyawan()
  {
    $session_data = $this->session->userdata('logged_in');
    $user         = $session_data['user_id'];
    $tgl_obj_from = new DateTime();
    $tgl_obj_to   = new DateTime();

    // echo '<pre>' . print_r(
    //   $this->input->post(),
    //   1
    // ) . '</pre>';
    // exit;

    $nik            = $this->input->post('nikx');
    $prev_id_status = $this->input->post('prev_id_status');
    $new_id_status  = $this->input->post('new_statusx');
    $from_period    = $this->input->post('periode_fromx');
    $thru_period    = $this->input->post('periode_tox');
    $hak_cuti       = $this->input->post('hak_cutix');
    $hak_ijin       = $this->input->post('hak_ijinx');

    if ($new_id_status == '30') {
      $thru_period = NULL;
    } else {
      $thru_period_obj = new DateTime();
      $thru_period = $thru_period_obj->createFromFormat('d-m-Y', $thru_period)->format('Y-m-d');
    }

    $from_period_obj = new DateTime();
    $from_period = $from_period_obj->createFromFormat('d-m-Y', $from_period)->format('Y-m-d');

    $datas = array(
      'nik'          => $nik,
      'from_status'  => $prev_id_status,
      'thru_status'  => $new_id_status,
      'from_date'    => $from_period,
      'thru_date'    => $thru_period,
      'created_by'   => $user,
      'created_date' => date('Y-m-d H:i:s')
    );

    $data = array(
      'status'   => $new_id_status,
      'hak_cuti' => $hak_cuti,
      'hak_ijin' => $hak_ijin
    );

    $this->db->trans_begin();
    $this->model_karyawan->action_update_status_karyawan($data, $nik); // UPDATE KARYAWAN DETAIL
    $this->model_karyawan->action_insert_mutasi_status($datas); // INSERT APP_MUTASI
    if ($this->db->trans_status() === true) {
      $this->db->trans_commit();
      redirect('mutasi/mutasi_status');
    } else {
      $this->db->trans_rollback();
      redirect('mutasi/mutasi_status');
    }
  }

  public function get_periode_status_by_nik()
  {
    $nik  = $this->input->post('nik');
    $data = $this->model_karyawan->get_periode_status_by_nik($nik);

    echo json_encode($data);
  }

  public function get_periode_cabang_by_nik()
  {
    $nik  = $this->input->post('nik');
    $data = $this->mcore->get_periode_cabang_by_nik($nik);

    echo json_encode($data);
  }

  public function get_karyawan_detail()
  {
    $nik = $this->input->get('nik');

    $data = $this->mcore->get_karyawan_by_nik($nik)->result();

    echo json_encode($data);
  }

  public function store_jabatan()
  {
    // echo '<pre>' . print_r($this->input->post(), 1) . '</pre>';
    // exit;
    $nik           = $this->input->post('nik');
    $from_position = $this->input->post('from_position');
    $thru_position = $this->input->post('thru_position');

    $tgl_mutasi_obj = new DateTime();
    $tgl_mutasi     = $this->input->post('tgl_mutasi');
    $tgl_mutasi     = $tgl_mutasi_obj->createFromFormat('d-m-Y', $tgl_mutasi)->format('Y-m-d');

    $from_date_obj = new DateTime();
    $from_date     = $this->input->post('from_date');
    $from_date     = $from_date_obj->createFromFormat('d-m-Y', $from_date)->format('Y-m-d');

    $thru_date_obj = new DateTime();
    $thru_date     = $this->input->post('thru_date');
    $thru_date     = $thru_date_obj->createFromFormat('d-m-Y', $thru_date)->format('Y-m-d');

    $created_by    = $this->session->userdata('logged_in')['user_id'];
    $created_date  = date('Y-m-d H:i:s');

    $data = array(
      'nik'           => $nik,
      'from_position' => $from_position,
      'thru_position' => $thru_position,
      'from_date'     => $from_date,
      'thru_date'     => $thru_date,
      'created_by'    => $created_by,
      'created_date'  => $created_date,
      'tgl_mutasi'    => $tgl_mutasi,
    );
    $exec = $this->mcore->store('app_mutasi_jabatan', $data);

    $data2 = array(
      'from_position'       => $from_position,
      'thru_position'       => $thru_position,
      'tgl_change_position' => $tgl_mutasi
    );
    $where = array('nik' => $nik);
    $exec2 = $this->mcore->update('app_karyawan_detail', $where, $data2);

    if ($exec === true && $exec2 === true) {
      $return = array(
        'code'        => '200',
        'description' => 'Proses Mutasi Berhasil'
      );
    } else {
      $return = array(
        'code'        => '400',
        'description' => 'Proses Mutasi Gagal'
      );
    }

    echo json_encode($return);
  }

  public function action_update_cabang_karyawan()
  {
    // echo '<pre>' . print_r($this->input->post(), 1) . '</pre>';
    // exit;
    $session_data = $this->session->userdata('logged_in');
    $user         = $session_data['user_id'];

    $nik          = $this->input->post('nik');
    $from_cabang  = $this->input->post('from_cabang');
    $thru_cabang  = $this->input->post('cabang');

    $from_periode_obj = new DateTime();
    $from_periode     = $this->input->post('from_periode');
    $from_periode     = $from_periode_obj->createFromFormat('d-m-Y', $from_periode)->format('Y-m-d');

    $thru_periode_obj = new DateTime();
    $thru_periode     = $this->input->post('thru_periode');
    $thru_periode     = $thru_periode_obj->createFromFormat('d-m-Y', $thru_periode)->format('Y-m-d');


    $this->db->trans_begin();

    $table = 'app_karyawan_detail';
    $where = array('nik' => $nik);
    $data = array(
      'from_branch'       => $from_cabang,
      'thru_branch'       => $thru_cabang,
      'tgl_change_branch' => $from_periode
    );
    $this->mcore->update($table, $where, $data); // UPDATE KARYAWAN DETAIL

    $datas = array(
      'nik'          => $nik,
      'from_branch'  => $from_cabang,
      'thru_branch'  => $thru_cabang,
      'from_date'    => $from_periode,
      'thru_date'    => $thru_periode,
      'created_by'   => $user,
      'created_date' => date('Y-m-d H:i:s'),
      'tgl_mutasi'   => $from_periode
    );
    $table = 'app_mutasi_cabang';
    $this->mcore->store($table, $datas); // INSERT APP_MUTASI

    if ($this->db->trans_status() === true) {
      $this->db->trans_commit();
      redirect('mutasi/mutasi_cabang');
    } else {
      $this->db->trans_rollback();
      redirect('mutasi/mutasi_cabang');
    }
  }

  public function get_list_karyawan($id_cabang)
  {
    $data = $this->model_karyawan->get_karyawan_by_cabang($id_cabang)->result();
    echo json_encode(compact('data'));
    exit;
  }

  public function get_info_karyawan()
  {
    $nik = $this->input->post('nik', TRUE);

    $karyawans = $this->model_karyawan->get_karyawan_info_by_nik($nik)->result();

    echo json_encode(compact('karyawans'));
    exit;
  }
}
