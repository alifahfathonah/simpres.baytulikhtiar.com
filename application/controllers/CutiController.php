<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CutiController extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if(!$this->session->userdata('logged_in')) redirect('login');
    $this->load->model('M_core', 'mcore');
    $this->load->model('model_setup');
    $this->load->model('model_karyawan');
  }

  public function index()
  {
    $session_data           = $this->session->userdata('logged_in');
    $data['username']       = $session_data['username'];
    $data['user']           = $session_data['fullname'];
    $data['role_id']        = $session_data['role_id'];
    $data['branch_user']    = $session_data['branch_code'];
    $data['nama_cabang']    = $this->mcore->translate_nama_cabang($data['branch_user']);
    
    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $data['get_position']   = $this->model_karyawan->get_position();
    $data['get_branch']     = $this->mcore->get_branch_own();

    $data['get_periode_from_absensi_manual'] = $this->model_karyawan->get_periode_from_absensi_manual();

    $data['container'] = 'cuti/table';
    $this->load->view('core', $data);
  }

  public function create()
  {
    $session_data            = $this->session->userdata('logged_in');
    $data['username']        = $session_data['username'];
    $data['user']            = $session_data['fullname'];
    $data['role_id']         = $session_data['role_id'];
    $data['branch_user']     = $session_data['branch_code'];
    $data['nama_cabang']     = $this->mcore->translate_nama_cabang($data['branch_user']);
    
    $data['periode_cutoff']  = $this->model_setup->get_cutoff();
    $data['get_position']    = $this->model_karyawan->get_position();
    $data['get_branch']      = $this->model_karyawan->get_branch();
    $data['arr_cuti_khusus'] = $this->mcore->get_cuti_khusus();

    if($data['role_id']  == '0'){
      $data['get_karyawan'] = $this->mcore->get_list_karyawan();
    }else{
      $data['get_karyawan'] = $this->mcore->get_karyawan_by_branch($data['branch_user']);
    }

    foreach ($data['periode_cutoff'] as $key) {
      $data['from_date'] = date('d-m-Y', strtotime($key->from_date));
      $data['thru_date'] = date('d-m-Y', strtotime($key->thru_date));
    }
    

    $data['container'] = 'cuti/create';
    $this->load->view('core', $data);
  }

  public function terima($nik, $tgl_cuti, $tgl_cuti2, $keterangan, $hari, $group, $kc)
  {
    //$id_alfa    = $this->input->post('alfa_id');
    $nik        = $this->input->post('nik');
    $tgl_cuti   = $this->input->post('tgl_cuti');
    $keterangan = $this->input->post('keterangan');
    $hari       = '1';
    $group      = $this->input->post('group');
    $kc         = $this->input->post('kc'); // KC == KATEGORI CUTI

    $user_id    = $this->session->userdata('logged_in')['user_id'];
    $approve_by = $this->mcore->get_nik_by_username($user_id);
    $username   = $this->session->userdata('logged_in')['username'];
    if($username == 'admin'){
      $approve_by = '518.2005.0009'; // A/n tati set as default for Master Admin
    }

    $arr_ci = $this->mcore->get_cuti_remain($nik);
    $hak_cuti = $arr_ci->row('hak_cuti');
    $hak_ijin = $arr_ci->row('hak_ijin');

    $total_xxx = $hak_cuti + $hak_ijin;

    if($hak_cuti == 0 && $hak_ijin == 0){
      $result = array(
        'code'        => '400',
        'description' => 'Hak Ijin & Cuti Telah Habis'
      );
      echo json_encode($result);
      exit();
    }

    if($total_xxx <= $hari){
      $result = array(
        'code'        => '400',
        'description' => 'Hak Ijin & Cuti Tidak Mencukupi'
      );
      echo json_encode($result);
      exit();
    }

    // END PENGURANGAN CUTI / IJIN
    if($group == 'ijin'){
      ////////////////////////////////////// JIKA PENGAJUAN ADALAH IJIN
      /*
      SKEMA PENGURANGAN IJIN
      - jika hak ijin <= hari permintaan -> proses
      - jika nilai x (hak ijin - hari) > 0 ,maka nilai x digunakan untuk mengurangi cuti
       */
      
      if($hari <= $hak_ijin){
        $exec = $this->mcore->pengurangan_ijin($nik, $hari);
      }else{
        $nilai_x = $hak_ijin - $hari;
        $nilai_x = str_replace('-', '', $nilai_x);
        $exec    = $this->mcore->pengurangan_ijin($nik, $hak_ijin); // set to zero
        $exec    = $this->mcore->pengurangan_cuti($nik, $nilai_x); // pengurangan ijin menggunakan nilai cuti
        
      }

    }elseif($group == 'cuti'){
      ////////////////////////////////////// JIKA PENGAJUAN ADALAH CUTI      
      $exec = $this->mcore->pengurangan_cuti($nik, $hari); // pengurangan nilai cuti
    }

    // END PENGURANGAN CUTI / IJIN
    

    // UPDATE TABLE ALFA
    /*$table = 'app_alfa';
    $where = array('alfa_id' => $id_alfa);
    $data  = array('aprove_by' => $approve_by);
    $exec  = $this->mcore->update($table, $where, $data);*/
    // END UPDATE TABLE ALFA
    
    // UPDATE TABLE ABSENSI MANUAL
    $table = 'app_absensi_manual';
    $where = array('nik' => $nik, 'tanggal' => $tgl_cuti);
    $data  = array(
      'masuk'      => '',
      'keluar'     => '',
      'keterangan' => $keterangan
    );
    $exec  = $this->mcore->update($table, $where, $data);
    // END UPDATE TABLE ABSENSI MANUAL


    if($exec === FALSE){
      $result = array(
        'code' => '500',
        'description' => 'Proses Terima Cuti/Ijin Gagal'
      );

    }else{
      $result = array(
        'code' => '200',
        'description' => 'Proses Terima Cuti/Ijin Berhasil'
      );
    }

    echo json_encode($result);
  }

  public function tolak()
  {
    $id_alfa    = $this->input->post('alfa_id');
    $nik        = $this->input->post('nik');
    $tgl_cuti   = $this->input->post('tgl_cuti');
    $tgl_cuti2  = $this->input->post('tgl_cuti2');
    $kc         = $this->input->post('kc');
    $hari       = $this->input->post('hari');
    
    //$hari       = $this->mcore->hitung_hari_pengajuan_cuti($nik, $tgl_cuti, $tgl_cuti2)->row('hari');
    $hari_libur = $this->mcore->jumlah_hari_libur($tgl_cuti, $tgl_cuti2)->row('total');
    $total_hari = $hari + $hari_libur;

    // UPDATE TABLE ABSENSI MANUAL
    $tlk = '0';
		$c   = '0';
		$ck  = '0';
		$dnl = '0';
		$sd  = '0';
		$i   = '0';
		$ltg = '1';
		$h   = '0';

    for ($i=0; $i < $total_hari; $i++) { 
      $tgl_cuti_temp = date('Y-m-d', strtotime($tgl_cuti.'+ '.$i.' day'));
      $cek_tgl_libur = $this->mcore->cek_tgl_libur($tgl_cuti_temp);
      $table         = 'app_absensi_manual';

      if($cek_tgl_libur == 0){

        $where = array('nik' => $nik, 'tanggal' => $tgl_cuti_temp);
        $data  = array(
					'masuk'      => '',
					'keluar'     => '',
					'keterangan' => 'Tidak Hadir',
					'tlk'        => $tlk,
					'c'          => $c,
					'ck'         => $ck,
					'dnl'        => $dnl,
					'sd'         => $sd,
					'i'          => $i,
					'ltg'        => $ltg,
					'h'          => $h,
        );
        $exec  = $this->mcore->update($table, $where, $data);
      }
    }
    // END UPDATE TABLE ABSENSI MANUAL
    
    // PENGEMBALIAN NILAI CUTI / IJIN
    if($kc == "ijin" || $kc == "cuti"){
      $exec = $this->mcore->return_cuti_ijin($nik, $kc, $hari);
    }
    // END PENGEMBALIAN NILAI CUTI / IJIN

    $table = 'app_alfa';
    $where = array('alfa_id' => $id_alfa);
    $exec  = $this->mcore->destroy($table, $where);

    if($exec === FALSE){
      $result = array(
        'code' => '500',
        'description' => 'Proses Pembatalan Cuti/Ijin Gagal'
      );

    }else{
      $result = array(
        'code' => '200',
        'description' => 'Proses Pembatalan Cuti/Ijin Berhasil'
      );
    }

    echo json_encode($result);
  }

  public function get_karyawan_cabang()
  {
    $id_cabang = $this->input->get('id_cabang');
    $arr = $this->mcore->get_karyawan_by_branch($id_cabang);

    $karyawan = array();
    foreach ($arr->result() as $key) {
      $karyawan[] = array(
        'nik'      => $key->nik,
        'fullname' => $key->fullname
      );
    }

    $return = array(
      'code' => '200',
      'description' => 'Get Data Karyawan Success',
      'data' => $karyawan
    );

    echo json_encode($return);
  }

  public function get_cuti_remain()
  {
    $nik = $this->input->get('nik');
    $arr = $this->mcore->get_cuti_remain($nik);

    foreach ($arr->result() as $key) {
      $hak_cuti = $key->hak_cuti;
      $hak_ijin = $key->hak_ijin;
    }

    $return = array(
      'code'        => '200',
      'description' => 'Get Data Cuti Karyawan Success',
      'hak_cuti'    => $key->hak_cuti,
      'hak_ijin'    => $key->hak_ijin
    );

    echo json_encode($return);
  }

  public function store()
  {
    $id_cabang  = $this->input->post('id_cabang');
    $nik        = $this->input->post('nik');
    $sisa_cuti  = $this->input->post('sisa_cuti');
    $sisa_ijin  = $this->input->post('sisa_ijin');
    $tipe       = $this->input->post('tipe');
    $khusus     = $this->input->post('khusus');
    $tgl_cuti   = date('Y-m-d', strtotime($this->input->post('tgl_cuti')));
    $tgl_cuti2  = date('Y-m-d', strtotime($this->input->post('tgl_cuti2')));
    $keterangan = nl2br($this->input->post('keterangan'));
    $user_id    = $this->session->userdata('logged_in')['user_id'];
    
    //$hari       = $this->mcore->hitung_hari_pengajuan_cuti($nik, $tgl_cuti, $tgl_cuti2)->row('hari');
    $hari_alday   = $this->hitung_hari_allday($tgl_cuti, $tgl_cuti2);
    $hari_workday = $this->hitung_hari_weekend($tgl_cuti, $tgl_cuti2);
    $hari_libur   = $this->mcore->jumlah_hari_libur($tgl_cuti, $tgl_cuti2)->row('total');
    $total_hari   = $hari_workday - $hari_libur;

    if($tipe == "cuti_khusus"){
      $kategori_cuti = $khusus;
    }else{
      $kategori_cuti = '1';
    }

    $created_by = $this->mcore->get_nik_by_username($user_id);

    $username   = $this->session->userdata('logged_in')['username'];
    if($username == 'admin'){
      $created_by = '518.2005.0009'; // A/n tati set as default for Master Admin
    }

    // LANJUT PROSES TERIMA CUTI
      $user_id    = $this->session->userdata('logged_in')['user_id'];
      $approve_by = $this->mcore->get_nik_by_username($user_id);
      $username   = $this->session->userdata('logged_in')['username'];

      if($username == 'admin'){
        $approve_by = '518.2005.0009'; // A/n tati set as default for Master Admin, please never use it at live server
      }

      $arr_ci   = $this->mcore->get_cuti_remain($nik);
      $hak_cuti = $arr_ci->row('hak_cuti');
      $hak_ijin = $arr_ci->row('hak_ijin');

      $total_xxx = $hak_cuti + $hak_ijin;

      if($tipe == 'ijin' || $tipe == 'cuti'){
        if($hak_cuti == 0 && $hak_ijin == 0){
          $result = array(
            'code'        => '400',
            'description' => 'Hak Ijin & Cuti Telah Habis'
          );
          echo json_encode($result);
          exit();
        }


        if((int)$total_hari > (int)$total_xxx){
          $result = array(
            'code'        => '400',
            'description' => 'Hak '.$tipe.' tidak mencukupi '.$total_hari." >= ".$total_xxx
          );
          echo json_encode($result);
          exit();
        }  
      }
      

      // END PENGURANGAN CUTI / IJIN
      if($tipe == 'ijin'){
        if($total_hari <= $hak_ijin){
          // PENGURANGAN HAK IJIN
          $exec = $this->mcore->pengurangan_ijin($nik, $hari_workday);
          // END PENGURANGAN HAK IJIN

          // INSERT KE TABEL APP ALFA
          $data = array(
            'nik'           => $nik,
            'tgl_cuti'      => $tgl_cuti,
            'kategori_cuti' => $kategori_cuti,
            'keterangan'    => $keterangan,
            'aprove_by'     => $created_by,
            'created_by'    => $created_by,
            'created_date'  => date('Y-m-d H:i:s'),
            'group'         => $tipe,
            'hari'          => $total_hari,
            'tgl_cuti2'     => $tgl_cuti2,
          );
          $exec = $this->mcore->store('app_alfa', $data);
          // END INSERT KE TABEL APP ALFA
        }else{
          $nilai_x = $total_hari - $hak_ijin; // nilai yang digunakan untuk mengurangi hak cuti
          $exec    = $this->mcore->pengurangan_ijin($nik, $hak_ijin); // set to zero

          // PERHITUNGAN TANGGAL AKHIR TIDAK BENTROK DENGAN TGL LIBUR
          $no = $hak_ijin - 1;
          $no_libur = 0;

          while($no_libur <= 1){
            $tgl_akhir = date('Y-m-d', strtotime($tgl_cuti.'+ '.$no.' day'));
            $cek_tgl_libur = $this->mcore->cek_tgl_libur($tgl_akhir);

            if($cek_tgl_libur == 0){
              $no_libur = 2;
            }else{
              $no_libur = 0;
            }

            $no++;

          }
          // END PERHITUNGAN TANGGAL AKHIR TIDAK BENTROK DENGAN TGL LIBUR

          $data = array(
            'nik'           => $nik,
            'tgl_cuti'      => $tgl_cuti,
            'tgl_cuti2'     => $tgl_akhir, // Tanggal 
            'kategori_cuti' => $kategori_cuti,
            'keterangan'    => $keterangan,
            'aprove_by'     => $created_by,
            'created_by'    => $created_by,
            'created_date'  => date('Y-m-d H:i:s'),
            'group'         => 'ijin',
            'hari'          => $hak_ijin, // Nilai hari sebanyak nilai hak ijin tersisa
          );
          $exec = $this->mcore->store('app_alfa', $data);

          // PERHITUNGAN TANGGAL AWAL TIDAK BENTROK DENGAN TGL LIBUR
          $no = $nilai_x;
          $no_libur = 0;

          while($no_libur <= 1){
            $tgl_awal = date('Y-m-d', strtotime($tgl_akhir.'+ '.$no.' day'));
            $cek_tgl_libur = $this->mcore->cek_tgl_libur($tgl_awal);

            if($cek_tgl_libur == 0){
              $no_libur = 2;
            }else{
              $no_libur = 0;
            }

            $no++;

          }
          // END PERHITUNGAN TANGGAL AWAL TIDAK BENTROK DENGAN TGL LIBUR

          $data2 = array(
            'nik'           => $nik,
            'tgl_cuti'      => $tgl_awal,
            'tgl_cuti2'     => $tgl_cuti2, // Tanggal 
            'kategori_cuti' => $kategori_cuti,
            'keterangan'    => $keterangan,
            'aprove_by'     => $created_by,
            'created_by'    => $created_by,
            'created_date'  => date('Y-m-d H:i:s'),
            'group'         => 'cuti',
            'hari'          => $nilai_x, // Nilai hari sebanyak nilai hak ijin tersisa
          );
          $exec2 = $this->mcore->store('app_alfa', $data2);
          
          $exec    = $this->mcore->pengurangan_cuti($nik, $nilai_x); // pengurangan ijin menggunakan nilai cuti 
        }

      }elseif($tipe == 'cuti'){
        $exec = $this->mcore->pengurangan_cuti($nik, $total_hari);

        // INSERT KE TABEL APP ALFA
        $data = array(
          'nik'           => $nik,
          'tgl_cuti'      => $tgl_cuti,
          'tgl_cuti2'     => $tgl_cuti2,
          'kategori_cuti' => $kategori_cuti,
          'keterangan'    => $keterangan,
          'aprove_by'     => $created_by,
          'created_by'    => $created_by,
          'created_date'  => date('Y-m-d H:i:s'),
          'group'         => $tipe,
          'hari'          => $total_hari,
        );
        $exec = $this->mcore->store('app_alfa', $data);
        // END INSERT KE TABEL APP ALFA
      }else{
        // INSERT KE TABEL APP ALFA
        $data = array(
          'nik'           => $nik,
          'tgl_cuti'      => $tgl_cuti,
          'kategori_cuti' => $kategori_cuti,
          'keterangan'    => $keterangan,
          'aprove_by'     => $created_by,
          'created_by'    => $created_by,
          'created_date'  => date('Y-m-d H:i:s'),
          'group'         => $tipe,
          'hari'          => $total_hari,
          'tgl_cuti2'     => $tgl_cuti2,
        );
        $exec = $this->mcore->store('app_alfa', $data);
        // END INSERT KE TABEL APP ALFA
      }
      // END PENGURANGAN CUTI / IJIN
      
      
      // UPDATE TABLE ABSENSI MANUAL
      for ($i=0; $i < $hari_alday; $i++) {
        $tgl_cuti_temp = date('Y-m-d', strtotime($tgl_cuti.'+ '.$i.' day'));
        $cek_tgl_libur = $this->mcore->cek_tgl_libur($tgl_cuti_temp);
        $table = 'app_absensi_manual';

        if($cek_tgl_libur == 0 && date('l', strtotime($tgl_cuti_temp)) != 'Saturday' && date('l', strtotime($tgl_cuti_temp)) != 'Sunday'){

        	$cek_tipe = $this->mcore->get_where('app_alfa', [
        		'nik' => $nik,
        		'tanggal' => $tgl_cuti_temp,
        	]);

          $where = array('nik' => $nik, 'tanggal' => $tgl_cuti_temp);
          $data  = array(
            'masuk'      => '',
            'keluar'     => '',
            'keterangan' => $keterangan,
          );
          $exec  = $this->mcore->update($table, $where, $data);
        }
      }
      // END UPDATE TABLE ABSENSI MANUAL
    // END LANJUT PROSES TERIMA CUTI

    if($exec === true){
      $return = array(
        'code' => '200',
        'description' => 'Proses pengajuan cuti berhasil...'
      );
    }else{
      $return = array(
        'code' => '400',
        'description' => 'Proses pengajuan cuti gagal...'
      );
    }

    echo json_encode($return);
  }

  public function list_regis_cuti()
  {
    $id_cabang = $this->input->get('id_cabang');

    if($id_cabang == 'semua'){
      $data['nama_cabang'] = "Semua Cabang";
    }else{
      $table = 'app_parameter';
      $where = [
        'parameter_group' => 'cabang',
        'parameter_id'    => $id_cabang
      ];
      $arr_parameter = $this->mcore->get_where($table, $where);

      foreach ($arr_parameter->result() as $key) {
        $data['nama_cabang'] = $key->description;
      }
    }

    $id_cabang            = ($id_cabang == "semua")? null : $id_cabang;
    $status               = 'semua';
    $periode              = $this->input->get('periode');
    $awal                 = trim(substr($periode, 0, 10));
    $akhir                = trim(substr($periode, 13, 10));
    $data['awal']         = $awal;
    $data['akhir']        = $akhir;
    $data['get_karyawan'] = $this->mcore->data_karyawan_alpha($id_cabang, $status, $awal, $akhir);
    //$nama_cabang          = ($id_cabang == "semua")? "Semua Cabang" : strtoupper($status);
    return $this->load->view('cuti/detail', $data, FALSE);
  }

  public function hitung_hari_allday($tgl_cuti, $tgl_cuti2)
  {
    // HITUNG TOTAL HARI DARI TANGGAL A KE TANGGAL B
    $hari = 0;
    while (strtotime($tgl_cuti) <= strtotime($tgl_cuti2)) {
      $tgl_cuti = date("Y-m-d", strtotime("+1 day", strtotime($tgl_cuti)));
      $hari++;
    }
    return $hari;
    // END HITUNG TOTAL HARI DARI TANGGAL A KE TANGGAL B
  }

  public function hitung_hari_weekend($tgl_cuti, $tgl_cuti2)
  {
    // HITUNG HARI MINGGU DARI TANGGAL A KE TANGGAL B 
    $hari = 0;
    while (strtotime($tgl_cuti) <= strtotime($tgl_cuti2)) {
      if(date('l', strtotime($tgl_cuti)) != 'Saturday' && date('l', strtotime($tgl_cuti)) != 'Sunday')
      {
        $hari++;
      }
      $tgl_cuti = date("Y-m-d", strtotime("+1 day", strtotime($tgl_cuti)));
    }
    return $hari;
    // END HITUNG HARI MINGGU DARI TANGGAL A KE TANGGAL B 
  }

  public function store2()
  {
  	$this->db->trans_begin();
		$id_cabang  = $this->input->post('id_cabang', TRUE);
		$nik        = $this->input->post('nik', TRUE);
		$sisa_cuti  = $this->input->post('sisa_cuti', TRUE);
		$sisa_ijin  = $this->input->post('sisa_ijin', TRUE);
		$tipe       = $this->input->post('tipe', TRUE);
		$khusus     = $this->input->post('khusus', TRUE);
		$tgl_cuti   = $this->input->post('tgl_cuti', TRUE);
		$tgl_cuti2  = $this->input->post('tgl_cuti2', TRUE);
		$keterangan = trim($this->input->post('keterangan', TRUE));
		$tgl_obj    = new DateTime();
		$tgl_a      = $tgl_obj->createFromFormat('d-m-Y', $tgl_cuti);
		$tgl_b_temp = $tgl_obj->createFromFormat('d-m-Y', $tgl_cuti2);
		$tgl_b      = $tgl_obj->createFromFormat('d-m-Y', $tgl_cuti2);

		$tgl_b->modify('+1 days');

		$interval     = $tgl_b->diff($tgl_a);
		$days         = $interval->days;
		$period       = new DatePeriod($tgl_a, new DateInterval('P1D'), $tgl_b);
		$arr_holidays = $this->mcore->get_arr_tgl_libur();
		$holidays     = array();

		foreach ($arr_holidays->result() as $arr_holiday) {
			$holidays[] = $arr_holiday->tanggal;
		}

		$aprove_by = $this->mcore->get_where('app_user', 
			['user_id' => $this->session->userdata('logged_in')['user_id']]
		)->row()->nik;

		if($aprove_by == NULL || $aprove_by == ''){
			$aprove_by = '518.2005.0009';
		}

		$arr_tanggal = array();

		foreach ($period as $dt) {
			$curr = $dt->format('D');

			if($curr == 'Sat' || $curr == 'Sun'){
				$days--;
			}elseif(in_array($dt->format('Y-m-d'), $holidays)){
				$days--;
			}else{
				array_push($arr_tanggal, $dt->format('Y-m-d'));
			}
		}

		$karyawan_details = $this->mcore->get_where('app_karyawan_detail', ['nik' => $nik]);
		$hak_ijin         = $karyawan_details->row()->hak_ijin;
		$hak_cuti         = $karyawan_details->row()->hak_cuti;
		$tlk              = '0';
		$c                = '0';
		$ck               = '0';
		$dnl              = '0';
		$sd               = '0';
		$i                = '0';
		$ltg              = '0';
		$h                = '0';

		if($tipe == 'ijin' || $tipe == 'cuti'){
			if($hak_ijin <= 0 && $hak_cuti <= 0){
				$this->db->trans_rollback();
				echo json_encode([
					'code'        => '400',
					'description' => 'Hak Cuti & Hak Ijin Tidak Mencukupi'
				]);
				exit;		
			}else{
				if($tipe == 'ijin'){
					if($hak_ijin <= 0){
						$remain_cuti = $hak_cuti - $days;

						if($remain_cuti < 0){
							echo json_encode([
								'code'        => '400',
								'description' => 'Pemotongan Ijin dari Hak Cuti Tidak Mencukupi'
							]);
							exit;
						}else{
							# TIPE BERUBAH JADI CUTI
							$tipe = 'cuti';
							$c    = '1';
							foreach ($arr_tanggal as $key) {
								//echo $key."<br>";
								$dataa = [
									'tlk'        => $tlk,
									'c'          => $c,
									'ck'         => $ck,
									'dnl'        => $dnl,
									'sd'         => $sd,
									'i'          => $i,
									'ltg'        => $ltg,
									'h'          => $h,
									'keterangan' => 'C - '.$keterangan
								];

								$wheree = [
									'nik'     => $nik,
									'tanggal' => $key,
								];

								$exec_am = $this->mcore->update('app_absensi_manual', $wheree, $dataa);
							}

							$exec_pengurangan_cuti = $this->mcore->pengurangan_cuti($nik, $days);

							$data = [
								'nik'           => $nik,
								'tgl_cuti'      => $tgl_a->format('Y-m-d'),
								'kategori_cuti' => '1',
								'keterangan'    => $keterangan,
								'aprove_by'     => $aprove_by,
								'created_by'    => $aprove_by,
								'created_date'  => $tgl_obj->modify('now')->format('Y-m-d'),
								'group'         => $tipe,
								'tgl_cuti2'     => $tgl_b_temp->format('Y-m-d'),
								'hari'          => $days,
							];

							$exec = $this->mcore->store('app_alfa', $data);
						}
					}else{
						# JIKA HAK IJIN LEBIH DARI 0
						$remain_ijin = $hak_ijin - $days;

						if($remain_ijin >= 0){
							# TIPE TETAP IJIN
							$tipe = 'ijin';
							$i    = '1';
							foreach ($arr_tanggal as $key) {
								//echo $key."<br>";
								$dataa = [
									'tlk'        => $tlk,
									'c'          => $c,
									'ck'         => $ck,
									'dnl'        => $dnl,
									'sd'         => $sd,
									'i'          => $i,
									'ltg'        => $ltg,
									'h'          => $h,
									'keterangan' => 'I - '.$keterangan
								];

								$wheree = [
									'nik'     => $nik,
									'tanggal' => $key,
								];

								$exec_am = $this->mcore->update('app_absensi_manual', $wheree, $dataa);
							}

							$exec_pengurangan_ijin = $this->mcore->pengurangan_ijin($nik, $days);

							$data = [
								'nik'           => $nik,
								'tgl_cuti'      => $tgl_a->format('Y-m-d'),
								'kategori_cuti' => '1',
								'keterangan'    => $keterangan,
								'aprove_by'     => $aprove_by,
								'created_by'    => $aprove_by,
								'created_date'  => $tgl_obj->modify('now')->format('Y-m-d'),
								'group'         => $tipe,
								'tgl_cuti2'     => $tgl_b_temp->format('Y-m-d'),
								'hari'          => $days,
							];

							$exec = $this->mcore->store('app_alfa', $data);
						}else{
							# JIKA HAK IJIN SESUDAH DI POTONG MENJADI KURANG DARI NOL
							# HARI IJIN 3
							# HAK IJIN = 2
							# HAK CUTI = 1
							# HAK IJIN PASTI DIKURANGI DAN DI BUAT NOL (HABIS TERPOTONG)
							$remain_ijin_temp = $hak_ijin - $days; # SISA IJIN 2 - 3 => -1
							$remain_ijin      = $days - $hak_ijin; # SISA IJIN 3 - 2 => 1
							$remain_cuti      = $hak_cuti - $remain_ijin; # SISA CUTI 1 - 1 = 0

							if($remain_cuti < 0){
								echo json_encode([
									'code'        => '400',
									'description' => 'Pemotongan Ijin dari Hak Cuti Tidak Mencukupi'
								]);
								exit;
							}else{
								# PENGURANGAN IJIN DULU
								$noxxx = 0;
								for($i = 0; $i < $hak_ijin; $i++){
									//$arr_tanggal[$i]
									$dataa = [
										'tlk'        => $tlk,
										'c'          => $c,
										'ck'         => $ck,
										'dnl'        => $dnl,
										'sd'         => $sd,
										'i'          => '1',
										'ltg'        => $ltg,
										'h'          => $h,
										'keterangan' => 'I - '.$keterangan
									];

									$wheree = [
										'nik'     => $nik,
										'tanggal' => $arr_tanggal[$i],
									];

									$exec_am = $this->mcore->update('app_absensi_manual', $wheree, $dataa);

									$tgl_xxx = $arr_tanggal[$i];
									$noxxx++;
								}

								$exec_pengurangan_ijin = $this->mcore->pengurangan_ijin($nik, $hak_ijin);

								$data = [
									'nik'           => $nik,
									'tgl_cuti'      => $tgl_a->format('Y-m-d'),
									'kategori_cuti' => '1',
									'keterangan'    => $keterangan,
									'aprove_by'     => $aprove_by,
									'created_by'    => $aprove_by,
									'created_date'  => $tgl_obj->modify('now')->format('Y-m-d'),
									'group'         => 'ijin',
									'tgl_cuti2'     => $tgl_xxx,
									'hari'          => $hak_ijin,
								];

								$exec = $this->mcore->store('app_alfa', $data);
								# END PENGURANGAN IJIN DULU
								
								# PENGURANGAN CUTI KEMUDIAN
								$tgl_xxx = $arr_tanggal[$i];
								for($i = $noxxx; $i <= $remain_ijin; $i++){
									//$arr_tanggal[$i]
									$dataa = [
										'tlk'        => $tlk,
										'c'          => '1',
										'ck'         => $ck,
										'dnl'        => $dnl,
										'sd'         => $sd,
										'i'          => '0',
										'ltg'        => $ltg,
										'h'          => $h,
										'keterangan' => 'C - '.$keterangan
									];

									$wheree = [
										'nik'     => $nik,
										'tanggal' => $arr_tanggal[$i],
									];

									$exec_am = $this->mcore->update('app_absensi_manual', $wheree, $dataa);

									$tgl_yyy = $arr_tanggal[$i];
								}

								$exec_pengurangan_cuti = $this->mcore->pengurangan_cuti($nik, $remain_ijin);

								$data = [
									'nik'           => $nik,
									'tgl_cuti'      => $tgl_xxx,
									'kategori_cuti' => '1',
									'keterangan'    => $keterangan,
									'aprove_by'     => $aprove_by,
									'created_by'    => $aprove_by,
									'created_date'  => $tgl_obj->modify('now')->format('Y-m-d'),
									'group'         => 'cuti',
									'tgl_cuti2'     => $tgl_yyy,
									'hari'          => $remain_ijin,
								];

								$exec = $this->mcore->store('app_alfa', $data);
								# END PENGURANGAN CUTI KEMUDIAN
							}
						}
					}
				}elseif($tipe == 'cuti'){
					if($hak_cuti <= 0){
						echo json_encode([
							'code'        => '400',
							'description' => 'Hak Cuti Tidak Mencukupi'
						]);
						exit;
					}else{
						$remain_cuti = $hak_cuti - $days;

						if($remain_cuti < 0){
							echo json_encode([
								'code'        => '400',
								'description' => 'Hak Cuti Tidak Mencukupi'
							]);
							exit;
						}else{
							$tipe = 'cuti';
							$c    = '1';
							foreach ($arr_tanggal as $key) {
								//echo $key."<br>";
								$dataa = [
									'tlk'        => $tlk,
									'c'          => $c,
									'ck'         => $ck,
									'dnl'        => $dnl,
									'sd'         => $sd,
									'i'          => $i,
									'ltg'        => $ltg,
									'h'          => $h,
									'keterangan' => 'C - '.$keterangan
								];

								$wheree = [
									'nik'     => $nik,
									'tanggal' => $key,
								];

								$exec_am = $this->mcore->update('app_absensi_manual', $wheree, $dataa);
							}

							$exec_pengurangan_cuti = $this->mcore->pengurangan_cuti($nik, $days);

							$data = [
								'nik'           => $nik,
								'tgl_cuti'      => $tgl_a->format('Y-m-d'),
								'kategori_cuti' => '1',
								'keterangan'    => $keterangan,
								'aprove_by'     => $aprove_by,
								'created_by'    => $aprove_by,
								'created_date'  => $tgl_obj->modify('now')->format('Y-m-d'),
								'group'         => $tipe,
								'tgl_cuti2'     => $tgl_b_temp->format('Y-m-d'),
								'hari'          => $days,
							];

							$exec = $this->mcore->store('app_alfa', $data);
						}
					}
				}
			}
		}elseif($tipe == 'sakit'){
			$sd    = '1';
			foreach ($arr_tanggal as $key) {
				//echo $key."<br>";
				$dataa = [
					'tlk'        => $tlk,
					'c'          => $c,
					'ck'         => $ck,
					'dnl'        => $dnl,
					'sd'         => $sd,
					'i'          => $i,
					'ltg'        => $ltg,
					'h'          => $h,
					'keterangan' => 'SD - '.$keterangan
				];

				$wheree = [
					'nik'     => $nik,
					'tanggal' => $key,
				];

				$exec_am = $this->mcore->update('app_absensi_manual', $wheree, $dataa);
			}

			$data = [
				'nik'           => $nik,
				'tgl_cuti'      => $tgl_a->format('Y-m-d'),
				'kategori_cuti' => '1',
				'keterangan'    => $keterangan,
				'aprove_by'     => $aprove_by,
				'created_by'    => $aprove_by,
				'created_date'  => $tgl_obj->modify('now')->format('Y-m-d'),
				'group'         => $tipe,
				'tgl_cuti2'     => $tgl_b_temp->format('Y-m-d'),
				'hari'          => $days,
			];

			$exec = $this->mcore->store('app_alfa', $data);
		}elseif($tipe == 'cuti_khusus'){
			$ck    = '1';
			foreach ($arr_tanggal as $key) {
				//echo $key."<br>";
				$dataa = [
					'tlk'        => $tlk,
					'c'          => $c,
					'ck'         => $ck,
					'dnl'        => $dnl,
					'sd'         => $sd,
					'i'          => $i,
					'ltg'        => $ltg,
					'h'          => $h,
					'keterangan' => 'CK - '.$keterangan
				];

				$wheree = [
					'nik'     => $nik,
					'tanggal' => $key,
				];

				$exec_am = $this->mcore->update('app_absensi_manual', $wheree, $dataa);
			}

			$data = [
				'nik'           => $nik,
				'tgl_cuti'      => $tgl_a->format('Y-m-d'),
				'kategori_cuti' => $khusus,
				'keterangan'    => $keterangan,
				'aprove_by'     => $aprove_by,
				'created_by'    => $aprove_by,
				'created_date'  => $tgl_obj->modify('now')->format('Y-m-d'),
				'group'         => $tipe,
				'tgl_cuti2'     => $tgl_b_temp->format('Y-m-d'),
				'hari'          => $days,
			];

			$exec = $this->mcore->store('app_alfa', $data);
		}elseif($tipe == 'tlk'){
			$tlk    = '1';
			foreach ($arr_tanggal as $key) {
				//echo $key."<br>";
				$dataa = [
					'tlk'        => $tlk,
					'c'          => $c,
					'ck'         => $ck,
					'dnl'        => $dnl,
					'sd'         => $sd,
					'i'          => $i,
					'ltg'        => $ltg,
					'h'          => $h,
					'keterangan' => 'TLK - '.$keterangan
				];

				$wheree = [
					'nik'     => $nik,
					'tanggal' => $key,
				];

				$exec_am = $this->mcore->update('app_absensi_manual', $wheree, $dataa);
			}

			$data = [
				'nik'           => $nik,
				'tgl_cuti'      => $tgl_a->format('Y-m-d'),
				'kategori_cuti' => '1',
				'keterangan'    => $keterangan,
				'aprove_by'     => $aprove_by,
				'created_by'    => $aprove_by,
				'created_date'  => $tgl_obj->modify('now')->format('Y-m-d'),
				'group'         => $tipe,
				'tgl_cuti2'     => $tgl_b_temp->format('Y-m-d'),
				'hari'          => $days,
			];

			$exec = $this->mcore->store('app_alfa', $data);
		}elseif($tipe == 'dnl'){
			$dnl    = '1';
			foreach ($arr_tanggal as $key) {
				//echo $key."<br>";
				$dataa = [
					'tlk'        => $tlk,
					'c'          => $c,
					'ck'         => $ck,
					'dnl'        => $dnl,
					'sd'         => $sd,
					'i'          => $i,
					'ltg'        => $ltg,
					'h'          => $h,
					'keterangan' => 'DNL - '.$keterangan
				];

				$wheree = [
					'nik'     => $nik,
					'tanggal' => $key,
				];

				$exec_am = $this->mcore->update('app_absensi_manual', $wheree, $dataa);
			}

			$data = [
				'nik'           => $nik,
				'tgl_cuti'      => $tgl_a->format('Y-m-d'),
				'kategori_cuti' => '1',
				'keterangan'    => $keterangan,
				'aprove_by'     => $aprove_by,
				'created_by'    => $aprove_by,
				'created_date'  => $tgl_obj->modify('now')->format('Y-m-d'),
				'group'         => $tipe,
				'tgl_cuti2'     => $tgl_b_temp->format('Y-m-d'),
				'hari'          => $days,
			];

			$exec = $this->mcore->store('app_alfa', $data);
		}

		if($exec){
			$this->db->trans_commit();

			echo json_encode([
				'code'        => '200',
				'description' => 'Proses Pengajuan Ketidakhadiran Berhasil'
			]);
		}else{
			echo json_encode([
				'code'        => '500',
				'description' => 'Internal Server Database Error'
			]);
		}

  }

  public function tolak2()
  {
		$tgl_obj    = new DateTime();
		$alfa_id    = $this->input->post('alfa_id', TRUE);
		$nik        = $this->input->post('nik', TRUE);
		$tgl_cuti   = $this->input->post('tgl_cuti', TRUE);
		$tgl_cuti2  = $this->input->post('tgl_cuti2', TRUE);
		$kc         = $this->input->post('kc', TRUE);
		$hari       = $this->input->post('hari', TRUE);
		$tgl_a      = $tgl_obj->createFromFormat('Y-m-d', $tgl_cuti);
		$tgl_b_temp = $tgl_obj->createFromFormat('Y-m-d', $tgl_cuti2);
		$tgl_b      = $tgl_obj->createFromFormat('Y-m-d', $tgl_cuti2);

		$tgl_b->modify('+1 days');

		$interval     = $tgl_b->diff($tgl_a);
		$days         = $interval->days;
		$period       = new DatePeriod($tgl_a, new DateInterval('P1D'), $tgl_b);
		$arr_holidays = $this->mcore->get_arr_tgl_libur();
		$holidays     = array();

		foreach ($arr_holidays->result() as $arr_holiday) {
			$holidays[] = $arr_holiday->tanggal;
		}

		foreach ($period as $dt) {
			$curr = $dt->format('D');

			if($curr == 'Sat' || $curr == 'Sun'){
				$days--;
			}elseif(in_array($dt->format('Y-m-d'), $holidays)){
				$days--;
			}else{
				$tlk = '0';
				$c   = '0';
				$ck  = '0';
				$dnl = '0';
				$sd  = '0';
				$i   = '0';
				$ltg = '1';
				$h   = '0';

				$dataa = [
					'masuk'      => '',
					'keluar'     => '',
					'tlk'        => $tlk,
					'c'          => $c,
					'ck'         => $ck,
					'dnl'        => $dnl,
					'sd'         => $sd,
					'i'          => $i,
					'ltg'        => $ltg,
					'h'          => $h,
					'keterangan' => 'Tidak Hadir'
				];

				$wheree = [
					'nik'     => $nik,
					'tanggal' => $dt->format('Y-m-d'),
				];

				$exec_am = $this->mcore->update('app_absensi_manual', $wheree, $dataa);
			}
		}

		$exec = TRUE;
		$karyawan_details = $this->mcore->get_where('app_karyawan_detail', ['nik' => $nik]);
		$nilai_ijin = $days + $karyawan_details->row()->hak_ijin;
		$nilai_cuti = $days + $karyawan_details->row()->hak_cuti;
		if($kc == 'ijin'){
			$data  = ['hak_ijin' => $nilai_ijin];
			$where = ['nik' => $nik];
			$exec  = $this->mcore->update('app_karyawan_detail', $where, $data);
		}elseif($kc == 'cuti'){
			$data  = ['hak_cuti' => $nilai_cuti];
			$where = ['nik' => $nik];
			$exec  = $this->mcore->update('app_karyawan_detail', $where, $data);
		}

		if($exec){
			$this->mcore->destroy('app_alfa', ['alfa_id' => $alfa_id]);
			echo json_encode([
				'code'        => '200',
				'description' => 'Proses Pembatalan Ketidakhadiran Berhasil'
			]);
		}else{
			echo json_encode([
				'code'        => '500',
				'description' => 'Internal Server Database Error'
			]);
		}
  }

}

/* End of file CutiController.php */
/* Location: ./application/controllers/CutiController.php */