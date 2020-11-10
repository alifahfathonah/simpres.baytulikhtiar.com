<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cutoff extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if($this->session->userdata('logged_in') == NULL) redirect('login');
    $this->load->model('model_setup');
    $this->load->model('model_karyawan');
    $this->load->model('M_core', 'mcore');
    $this->load->model('M_userless','mkl');
  }

  public function index()
  {
    $session_data           = $this->session->userdata('logged_in');
    $data['username']       = $session_data['username'];
    $data['user']           = $session_data['fullname'];
    $data['role_id']        = $session_data['role_id'];
    
    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $data['container']      = 'setup/setup_cutoff';

    foreach ($data['periode_cutoff'] as $key) {
      $data['from_date'] = date('d-m-Y', strtotime($key->from_date));
      $data['thru_date'] = date('d-m-Y', strtotime($key->thru_date.' + 1 day'));
    }

    $this->load->view('core', $data);
    
  }

  /*
  proses ganti cutoff
  params{
    from_date, thru_date
  }
  */
  public function store()
  {
    # tangkap variable dari form
		$from_date      = new DateTime($this->_convertdate($this->input->get('from_date')));
		$thru_date      = new DateTime($this->_convertdate($this->input->get('thru_date')));
		$idle_thru_date = new DateTime($this->_convertdate($this->input->get('thru_date')));
    # end tangkap variable dari form
    # 
    # prepare array batch
    $array_batch = $this->_prepare_array($from_date, $thru_date);
    # end prepare array batch
    # 
    # proses insert batch
    // $exec = $this->model_karyawan->insert_batch_absensi_manual($array_batch);
    $exec = $this->mcore->insert_batch('app_absensi_manual', $array_batch);
    if($exec === TRUE){
      $this->mcore->update('app_cutoff', NULL, [
        'from_date' => $from_date->format('Y-m-d'),
        'thru_date' => $idle_thru_date->format('Y-m-d')
      ]);
      $ret = [
        'code' => 200,
        'description' => 'Proses Ganti Cutoff Berhasil'
      ];
    }else{
      $ret = [
        'code' => 500,
        'description' => 'Proses Ganti Cutoff Gagal, silahkan hubungi Team IT BAIK'
      ];
    }
    # end insert batch
    echo json_encode($ret);
  }

  private function _convertdate($tanggal)
  {
    $tanggal = date('Y-m-d', strtotime($tanggal));
    return $tanggal;
  }

  private function _prepare_array($from_date, $thru_date)
  {
		$xfrom_date   = $from_date->format('Y-m-d');
		$xthru_date   = $thru_date->format('Y-m-d');
		$created_by   = $this->session->userdata('logged_in')['fullname'];
		$created_date = date('Y-m-d H:i:s');
		$days         = $thru_date->diff($from_date)->format("%a");
		$period       = new DatePeriod($from_date, new DateInterval('P1D'), $thru_date->modify('+1 day'));
    # arr hari libur
    $arr_liburs = $this->mcore->get_arr_tgl_libur();
    foreach ($arr_liburs->result() as $key) {
      $holidays[] = $key->tanggal;
    }
    # end arr hari libur
    # 
    # preparation array batch
    # 
    $karyawans = $this->mcore->get_where_single_join('app_karyawan_detail', ['app_karyawan_detail.status !=' => '50'], 'app_karyawan', 'app_karyawan_detail.nik = app_karyawan.nik', 'left');

    foreach ($karyawans->result() as $karyawan) {
      foreach ($period as $dt) {
        $hari    = $dt->format('D');
        $tanggal = $dt->format('Y-m-d');

        if($hari == 'Sat' || $hari == 'Sun'){
          //$days--;
        }elseif(in_array($tanggal, $holidays) && ($hari != 'Sat' || $hari != 'Sun')){
          $keterangan = $this->mcore->get_where('app_hari_libur', ['tanggal' => $tanggal])->row()->description;

          // $object[] = "
          // '$karyawan->nik',
          // '',
          // '',
          // $tanggal,
          // $keterangan,
          // $xfrom_date,
          // $created_by,
          // $created_date,
          // '1',
          // '0'
          // ";
          
          $object[] = [
            'nik'               => $karyawan->nik,
            'masuk'             => '',
            'keluar'            => '',
            'tanggal'           => $tanggal,
            'keterangan'        => $keterangan,
            'periode_from_date' => $xfrom_date,
            'periode_thru_date' => $xthru_date,
            'created_by'        => $created_by,
            'created_date'      => $created_date,
            'l'                 => '1',
						'ltg'               => '0',
          ];
        }else{
        	// $object[] = "
         //  '$karyawan->nik',
         //  '',
         //  '',
         //  $tanggal,
         //  'Tidak Hadir',
         //  $xfrom_date,
         //  $created_by,
         //  $created_date,
         //  '1',
         //  '0'
         //  ";

          $object[] = [
						'nik'               => $karyawan->nik,
						'masuk'             => '',
						'keluar'            => '',
						'tanggal'           => $tanggal,
						'keterangan'        => 'Tidak Hadir',
						'periode_from_date' => $xfrom_date,
						'periode_thru_date' => $xthru_date,
						'created_by'        => $created_by,
						'created_date'      => $created_date,
						'l'                 => '0',
						'ltg'               => '1'
          ];
        }


      }

    }

    return $object;
  }

}

/* End of file Cutoff.php */
/* Location: ./application/controllers/Cutoff.php */