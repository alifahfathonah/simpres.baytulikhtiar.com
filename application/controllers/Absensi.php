<?php if(!defined('BASEPATH')) exit('No scripts access allowed');

class Absensi extends CI_Controller
{
	public function __construct()
	{
    parent::__construct();
    if(!$this->session->userdata('logged_in')) redirect('login');
    $this->load->model('model_setup');
    $this->load->model('model_karyawan');
    $this->load->model('M_core', 'mcore');
    ini_set('max_execution_time', 3000); 
    ini_set('memory_limit','2048M');
  }  

  public function index()
  {
    $session_data     = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['user']     = $session_data['fullname'];
    $data['role_id']  = $session_data['role_id'];

    //if($branch_user == '1' || $data['username'] == "admin")
    if($data['role_id'] == '0')
    {
      //$data['get_karyawan'] = $this->model_karyawan->get_karyawan();
      $data['get_karyawan'] = $this->mcore->get_all_data_e_resign('app_karyawan');
    }
    else
    {
      $data['get_karyawan'] = $this->mcore->get_karyawan_by_branch_e_res($session_data['branch_code']);
    }

    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $data['get_branch']     = $this->model_karyawan->get_branch();
    $data['container']      = 'absensi/absen_karyawan';

    $this->load->view('core', $data);			
  }

  function get_absen_by_nik()
  {

    $session_data     = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['user']     = $session_data['fullname'];
    $data['role_id']  = $session_data['role_id'];
    $branch_user      = $session_data['branch_code'];

    if($this->uri->segment(3)){
    	$nik = $this->uri->segment(3);
    }else{
    	$nik = $this->input->post('nik');
    }

    $data['nik']      = $nik;

    if($data['role_id'] == '0' || $data['username'] == "admin")
    {
      $data['get_karyawan'] = $this->mcore->get_all_data('app_karyawan');
    }else{
      $data['get_karyawan'] = $this->mcore->get_karyawan_by_branch($branch_user);
    }

    $data['periode_cutoff'] = $this->model_setup->get_cutoff();

    foreach ($data['periode_cutoff'] as $key) {
      $from_date = $key->from_date;
      $thru_date = $key->thru_date;
    }

    $data['get_karyawan_by_branch'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);
    $data['get_branch']             = $this->model_karyawan->get_branch();
    $data['get_karyawan_by_nik']    = $this->mcore->get_karyawan_by_nik($nik);
    $data['get_presensi_by_nik']    = $this->model_karyawan->get_presensi_by_nik_date($nik, $from_date, $thru_date);
    //$data['get_libur']              = $this->model_karyawan->get_libur_array();
    $data['get_libur']              = $this->mcore->get_all_data('app_hari_libur');
    $data['get_count_absen_by_nik'] = $this->model_karyawan->get_count_absen_by_nik($nik);
    $data['get_absent_by_nik']      = $this->model_karyawan->get_absent_by_nik($nik);
    $data['get_lembur']             = $this->mcore->data_karyawan_lembur_by_nik($nik, $from_date, $thru_date);
    $data['container']              = 'absensi/absen_perkaryawan';

    $this->load->view('core', $data);

  }


  function load()
  {
    $session_data     = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['user']     = $session_data['fullname'];
    $data['role_id']  = $session_data['role_id'];
    $branch_user      = $session_data['branch_code'];
    $nik              = $this->uri->segment(3);
    $periode_cutoff   = $this->model_setup->get_cutoff();

    foreach($periode_cutoff as $values)
    {
      $from_date = $values->from_date;
      $thru_date = $values->thru_date;
    }

    $get_data_absen = $this->model_karyawan->get_data_absen($nik);
    $get_absensi_fp = $this->model_karyawan->get_absensi_fp($nik);

    $n = 0;
    foreach($get_absensi_fp as $values)
    {
      $min = $values->min;
      $max = $values->max;

      if($min >= '01:00:00' && $min <= '12:00:00'){ $masuk = $min; }else{ $masuk = '12:00:00'; }
      if($max >= '12:00:01' && $max <= '23:59:59'){ $keluar = $max; }else{ $keluar = '12:00:00'; }
      $tanggal = $values->tanggal;
      $update_absensi_manual_by_nik = $this->model_karyawan->update_absensi_manual_by_nik($nik, $masuk, $keluar, $tanggal);
    }


    $data['periode_cutoff']         = $this->model_setup->get_cutoff();
    $data['get_karyawan']           = $this->model_karyawan->get_karyawan();
    $data['get_karyawan_by_branch'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);
    $data['get_branch']             = $this->model_karyawan->get_branch();
    $data['get_karyawan_by_nik']    = $this->model_karyawan->get_karyawan_by_nik($nik);
    $data['get_presensi_by_nik']    = $this->model_karyawan->get_presensi_by_nik($nik);
    $data['get_count_absen_by_nik'] = $this->model_karyawan->get_count_absen_by_nik($nik);
    $data['get_libur']              = $this->model_karyawan->get_libur();
    $data['container']              = 'absensi/absen_perkaryawan';

    $this->load->view('core', $data);
  }

  function load_all()
  {
    $session_data     = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['user']     = $session_data['fullname'];
    $data['role_id']  = $session_data['role_id'];
    $branch_user      = $session_data['branch_code'];
    $periode_cutoff   = $this->model_setup->get_cutoff();

    foreach($periode_cutoff as $values)
    {
      $from_date = $values->from_date;
      $thru_date = $values->thru_date;
    }

    $get_karyawan_by_branch   = $this->model_karyawan->get_karyawan_by_branch($branch_user);
    $get_count_absensi_manual = $this->model_karyawan->get_count_absensi_manual();

    if($get_count_absensi_manual == '0')
    {
      echo "<script>alert('Periode cutoff belum update!!');history.go(-1)</script>";	
    }
    else
    {
      foreach($get_karyawan_by_branch as $values)
      {
        $nik            = $values->nik;
        $get_data_absen = $this->model_karyawan->get_data_absen($nik);
        $get_absensi_fp = $this->model_karyawan->get_absensi_fp($nik);

        foreach($get_absensi_fp as $values)
        {
          $masuk   = $values->min;
          $keluar  = $values->max;
          $tanggal = $values->tanggal;
          $update_absensi_manual_by_nik = $this->model_karyawan->update_absensi_manual_by_nik($nik, $masuk, $keluar, $tanggal);
        }
      }
    }

    $data['periode_cutoff']         = $this->model_setup->get_cutoff();
    $data['get_karyawan']           = $this->model_karyawan->get_karyawan();
    $data['get_karyawan_by_branch'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);
    $data['get_branch']             = $this->model_karyawan->get_branch();
    $data['get_karyawan_by_nik']    = $this->model_karyawan->get_karyawan_by_nik($nik);
    $data['get_presensi_by_nik']    = $this->model_karyawan->get_presensi_by_nik($nik);
    $data['get_count_absen_by_nik'] = $this->model_karyawan->get_count_absen_by_nik($nik);
    $data['get_libur']              = $this->model_karyawan->get_libur();
    $data['container']              = 'absensi/absen_karyawan';

    $this->load->view('core', $data);
  }

  public function action_add_manual()
  {
		$session_data     = $this->session->userdata('logged_in');
		$data['username'] = $session_data['username'];
		$data['user']     = $session_data['fullname'];
		$data['role_id']  = $session_data['role_id'];
		$branch_user      = $session_data['branch_code'];
		$karyawan         = $this->uri->segment(3);
		$h                = '0';
		$ltg              = '1';
		$m8               = '0';
		$m815             = '0';
		$m830             = '0';
		$k5               = '0';
		$k445             = '0';
		$k430             = '0';
		$keterangan       = 'Tidak Hadir';
		$m12              = '0';
		$k12              = '0';

    for($n = 0; $n < 30; $n++)
    {		
			$tanggal_absen  = $this->input->post('tanggal'.$n);
			$min_waktu      = $this->input->post('datang'.$n);
			$max_waktu      = $this->input->post('pulang'.$n);
			$get_keterangan = $this->input->post('keterangan'.$n);


      $min_waktu = str_replace('SS', '00', $min_waktu);
      $max_waktu = str_replace('SS', '00', $max_waktu);

      // UPDATE NILAI MASUK
      if($min_waktu == '' || $min_waktu == NULL){
				$masuk = '';
				$m8   = '0';
				$m815 = '0';
				$m830 = '0';
				$m12  = '0';
      }elseif($min_waktu > '12:00:00'){
				$masuk = $min_waktu;
				$m8   = '0';
				$m815 = '0';
				$m830 = '0';
				$m12  = '1';
			}else{
				$masuk = $min_waktu;

				if($masuk <= '08:00:00'){
					$m8   = '1';
					$m815 = '0';
					$m830 = '0';
					$m12  = '0';
				}elseif($masuk <= '08:15:00'){
					$m8   = '0';
					$m815 = '1';
					$m830 = '0';
					$m12  = '0';
				}elseif($masuk <= '08:30:00'){
					$m8   = '0';
					$m815 = '0';
					$m830 = '1';
					$m12  = '0';
				}elseif($masuk <= '12:00:00'){
					$m8   = '0';
					$m815 = '0';
					$m830 = '0';
					$m12  = '1';
				}
			}

			$exec_masuk = $this->mcore->update_masuk1($masuk, $h, $ltg, $m8, $m815, $m830, $m12, $karyawan, $tanggal_absen);
			// END UPDATE NILAI MASUK
			// 
			// UPDATE NILAI KELUAR
			if($max_waktu == '' || $max_waktu == NULL){
				$keluar = '';
				$h      = '0';
				$ltg    = '1';
				$k5     = '0';
				$k445   = '0';
				$k430   = '0';
				$k12    = '0';
      }elseif($max_waktu < '12:00:01'){
				$keluar = $max_waktu;
				$h      = '1';
				$ltg    = '0';
				$k5     = '0';
				$k445   = '0';
				$k430   = '0';
				$k12    = '1';
			}else{
				$keluar = $max_waktu;

				if($keluar >= '17:00:00'){
					$k5   = '1';
					$k445 = '0';
					$k430 = '0';
					$k12  = '0';
				}elseif($keluar >= '16:45:00'){
					$k5   = '0';
					$k445 = '1';
					$k430 = '0';
					$k12  = '0';
				}elseif($keluar >= '16:30:00'){
					$k5   = '0';
					$k445 = '0';
					$k430 = '1';
					$k12  = '0';
				}elseif($keluar >= '12:00:01'){
					$k5   = '0';
					$k445 = '0';
					$k430 = '0';
					$k12  = '1';
				}
			}

			$exec_keluar = $this->mcore->update_keluar1($keluar, $h, $ltg, $k430, $k445, $k5, $k12, $karyawan, $tanggal_absen);
			// END UPDATE NILAI KELUAR
			// 
			// UPDATE KETERANGAN
			if($masuk == '' && $keluar == ''){
				$keterangan = 'Tidak Hadir';
				$h          = '0';
				$ltg        = '1';
			}elseif($masuk != '' && $keluar == ''){
				$keterangan = 'Tidak FP Keluar';
				$h          = '1';
				$ltg        = '0';
			}elseif($masuk == '' && $keluar != ''){
				$keterangan = 'Tidak FP Masuk';
				$h          = '1';
				$ltg        = '0';
			}elseif($masuk != '' && $keluar != ''){
				$keterangan = 'Hadir';
				$h          = '1';
				$ltg        = '0';
			}

			$where_keterangan = [
				'nik'     => $karyawan,
				'tanggal' => $tanggal_absen,
				'l        !='   => '1',
				'tlk      !='   => '1',
				'c        !='   => '1',
				'ck       !='   => '1',
				'dnl      !='   => '1',
				'sd       !='   => '1',
				'i        !='   => '1',
			];

			$data_keterangan = [
				'keterangan' => $keterangan,
				'h'          => $h,
				'ltg'        => $ltg,
			];

			$exec_keterangan = $this->mcore->update('app_absensi_manual', $where_keterangan, $data_keterangan);
			// END UPDATE KETERANGAN

    }

    redirect('absensi/get_absen_by_nik/'.$karyawan,'refresh');

  }

  function rekap_bulanan()
  {
    $session_data           = $this->session->userdata('logged_in');
    $data['username']       = $session_data['username'];
    $data['user']           = $session_data['fullname'];
    $data['role_id']        = $session_data['role_id'];
    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $data['get_karyawan']   = $this->model_karyawan->get_karyawan();
    $data['get_branch']     = $this->model_karyawan->get_branch();
    $data['container']      = 'absensi/rekap_bulanan';

    $this->load->view('core', $data);			
  }



  function get_rekap_absen()
  {
    $get_karyawan    = $this->model_karyawan->get_karyawan();
    $get_count_absen = $this->model_karyawan->get_count_absen();
    $iTotalRecords   = $get_count_karyawan;
    $iDisplayLength  = intval($_REQUEST['length']);
    $iDisplayLength  = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
    $iDisplayStart   = intval($_REQUEST['start']);
    $sEcho           = intval($_REQUEST['draw']);
    $records         = array();
    $records["data"] = array(); 
    $end             = $iDisplayStart + $iDisplayLength;
    $end             = $end > $iTotalRecords ? $iTotalRecords : $end;

    $status_list = array(
      array("success" => "Pending"),
      array("info"    => "Closed"),
      array("danger"  => "On Hold"),
      array("warning" => "Fraud")
    );

    foreach($get_karyawan as $key=>$values)
    {
      $karyawan_id[$key] = $values->karyawan_id;
      $nik[$key]         = $values->nik;
      $nama[$key]        = $values->fullname;
      $position[$key]    = $values->change_post;
      $branch[$key]      = $values->thru_branch;
      $status[$key]      = $values->status;
    }

    for($i = $iDisplayStart; $i < $end; $i++) {
      $id = ($i + 1);
      if($status[$i] == '0'){
        $post_status = "Karyawan Tetap"; 
        $color       = "success";
      }elseif($status[$i] == '1'){
        $post_status = "Karyawan Kontrak"; 
        $color       = "info";
      }elseif($status[$i] == '2'){
        $post_status = "Karyawan Training"; 
        $color       = "danger";
      }elseif($status[$i] == '3'){
        $post_status = "Magang"; 
        $color       = "warning";
      }
      
      $records["data"][] = array(
        '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="'.$id.'"/><span></span></label>',
        ''.$nik[$i].'',
        ''.$nama[$i].'',
        ''.$position[$i].'',
        ''.$branch[$i].'',
        '<span class="label label-sm label-'.$color.'">'.$post_status.'</span>',
        '<a href="'.site_url().'karyawan/get_karyawan_by_id/'.$karyawan_id[$i].'" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> View</a>',
      );
    }

    if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
      $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
      $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
    }

    $records["draw"]            = $sEcho;
    $records["recordsTotal"]    = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;

    echo json_encode($records);
  }

  public function import($karyawan){
  	if($karyawan == '' || $karyawan == NULL){
  		http_response_code(400);
  	}else{
			$tgl_obj     = new DateTime();
			$branch_code = NULL;
			$cutoffs     = $this->mcore->get_all_data('app_cutoff');
			$from_period = $tgl_obj->createFromFormat('Y-m-d', $cutoffs->row()->from_date);
			$thru_period = $tgl_obj->createFromFormat('Y-m-d', $cutoffs->row()->thru_date);

  		$arr_absens = $this->mcore->get_all_data_from_fp_temp($from_period->format('Y-m-d'), $thru_period->format('Y-m-d'), $karyawan);

			if($arr_absens->num_rows() == 0){
				http_response_code(400);
			}else{
				foreach ($arr_absens->result() as $arr_absen) {
					$data_masuk    = array();
					$data_keluar   = array();
					$min_waktu     = $arr_absen->min_waktu;
					$max_waktu     = $arr_absen->max_waktu;
					$tanggal_absen = $arr_absen->tanggal;
					$h             = '0';
					$ltg           = '1';
					$m8            = '0';
					$m815          = '0';
					$m830          = '0';
					$k5            = '0';
					$k445          = '0';
					$k430          = '0';
					$keterangan    = 'Tidak Hadir';
					$m12           = '0';
					$k12           = '0';

					// UPDATE NILAI MASUK
					if($min_waktu > '12:00:00'){
						$masuk = '';
					}else{
						$masuk = $min_waktu;
						$h     = '1';
						$ltg   = '0';

						if($masuk <= '08:00:00'){
							$m8 = '1';
						}elseif($masuk <= '08:15:00'){
							$m815 = '1';
						}elseif($masuk <= '08:30:00'){
							$m830 = '1';
						}elseif($masuk <= '12:00:00'){
							$m12 = '1';
						}
					}

					$exec_masuk = $this->mcore->update_masuk($masuk, $h, $ltg, $m8, $m815, $m830, $m12, $karyawan, $tanggal_absen);
					//echo $this->db->last_query();
					//echo $this->db->affected_rows()."<br>";
					// END UPDATE NILAI MASUK
					// 
					// UPDATE NILAI KELUAR
					if($max_waktu < '12:00:01'){
						$keluar = '';
					}else{
						$keluar = $max_waktu;
						$h      = '1';
						$ltg    = '0';

						if($keluar >= '17:00:00'){
							$k5 = '1';
						}elseif($keluar >= '16:45:00'){
							$k445 = '1';
						}elseif($keluar >= '16:30:00'){
							$k430 = '1';
						}elseif($keluar >= '12:00:01'){
							$k12 = '1';
						}
					}

					$exec_keluar = $this->mcore->update_keluar($keluar, $h, $ltg, $k430, $k445, $k5, $k12, $karyawan, $tanggal_absen);
					//echo $this->db->last_query();
					//echo $this->db->affected_rows()."<br>";
					// END UPDATE NILAI KELUAR
					// 
					// UPDATE KETERANGAN
					if($masuk == '' && $keluar == ''){
						$keterangan = 'Tidak Hadir';

					}elseif($masuk != '' && $keluar == ''){
						$keterangan = 'Tidak FP Keluar';
					}elseif($masuk == '' && $keluar != ''){
						$keterangan = 'Tidak FP Masuk';
					}elseif($masuk != '' && $keluar != ''){
						$keterangan = 'Hadir';
					}

					$where_keterangan = [
						'nik'     => $karyawan,
						'tanggal' => $tanggal_absen,
						'l        !='   => '1',
						'tlk      !='   => '1',
						'c        !='   => '1',
						'ck       !='   => '1',
						'dnl      !='   => '1',
						'sd       !='   => '1',
						'i        !='   => '1',
					];

					$data_keterangan = [
						'keterangan' => $keterangan,
					];

					$exec_keterangan = $this->mcore->update('app_absensi_manual', $where_keterangan, $data_keterangan);
					//echo $this->db->affected_rows()."<br>";
					// END UPDATE KETERANGAN
				}

				http_response_code(200);
			}
  	}
  }

  public function logic_masuk_keluar($nik, $tanggal, $min_waktu, $max_waktu)
  {
    $table = 'app_absensi_manual';
    $where = [
      'nik'     => $nik,
      'tanggal' => $tanggal,
    ];
    $arr_am = $this->mcore->get_where($table, $where);

    $count_all_data = $arr_am->num_rows();
    if($count_all_data == 0){
      return 400;
    }else{

      // LOGIC MASUK
      $masuk = $arr_am->row('masuk');
      $ket_masuk = '';

      if(empty($masuk)){
        if($min_waktu <= '12:00:00'){
          if($min_waktu <= '08:00:00'){
            $ket_masuk = 'Masuk Tepat Waktu';
          }else{
            $ket_masuk = 'Masuk Terlambat';
          }

          $table = 'app_absensi_manual';
          $where = [
            'nik'     => $nik,
            'tanggal' => $tanggal,
          ];
          $data = [
            'masuk' => $min_waktu
          ];
          $exec = $this->mcore->update($table, $where, $data);

        }else{
          $ket_masuk = 'Masuk Terlambat';
          $table = 'app_absensi_manual';
          $where = [
            'nik'     => $nik,
            'tanggal' => $tanggal,
          ];
          $data = [
            'masuk' => '12:00:00'
          ];
          $exec = $this->mcore->update($table, $where, $data);
        }

      }
      // END LOGIC MASUK
      
      
      // LOGIC KELUAR
      $keluar = $arr_am->row('keluar');
      $ket_keluar = '';

      if(empty($keluar)){
        if($max_waktu >= '12:00:01'){
          if($max_waktu >= '17:00:00'){
            $ket_keluar = 'Pulang Tepat Waktu';
          }else{
            $ket_keluar = 'Pulang Lebih Cepat';
          }

          $table = 'app_absensi_manual';
          $where = [
            'nik'     => $nik,
            'tanggal' => $tanggal,
          ];
          $data = [
            'keluar' => $max_waktu
          ];
          $exec = $this->mcore->update($table, $where, $data);

        }else{
          $ket_keluar = 'Pulang Lebih Cepat';
          $table = 'app_absensi_manual';
          $where = [
            'nik'     => $nik,
            'tanggal' => $tanggal,
          ];
          $data = [
            'keluar' => '12:00:01'
          ];
          $exec = $this->mcore->update($table, $where, $data);
        }

      }
      // END LOGIC KELUAR
      
      $keterangan = '';
      if($ket_masuk == 'Masuk Tepat Waktu' && $ket_keluar == 'Pulang Tepat Waktu'){
        $keterangan = 'Masuk & Pulang Tepat Waktu';
      }elseif($ket_masuk == 'Masuk Tepat Waktu' && $ket_keluar == 'Pulang Lebih Cepat'){
        $keterangan = 'Masuk Tepat Waktu & Pulang Lebih Cepat';
      }elseif($ket_masuk == 'Masuk Terlambat' && $ket_keluar == 'Pulang Tepat Waktu'){
        $keterangan = 'Masuk Terlambat & Pulang Tepat Waktu';
      }elseif($ket_masuk == 'Masuk Terlambat' && $ket_keluar == 'Pulang Lebih Cepat'){
        $keterangan = 'Masuk Terlambat & Pulang Lebih Cepat';
      }

      $table = 'app_absensi_manual';
      $where = [
        'nik'     => $nik,
        'tanggal' => $tanggal,
      ];
      $data = [
        'keterangan' => $keterangan
      ];
      $exec = $this->mcore->update($table, $where, $data);

      if($exec === TRUE){
        return 200;
      }else{
        return 500;
      }

    }
    
  }

  public function logic_keterangan($nik, $tanggal, $min_waktu, $max_waktu)
  {

  }

  public function hitung_array_import_fp()
  {
		$arr_cutoff    = $this->mcore->get_all_data('app_cutoff');
		$periode_awal  = $arr_cutoff->row()->from_date;
		$periode_akhir = $arr_cutoff->row()->thru_date;

		// if(isset($this->session->userdata('logged_in')['branch_code'])){
		// 	$branch_id = $this->session->userdata('logged_in')['branch_code'];
		// }

		$arr_absen     = $this->mcore->get_all_data_from_fp_temp($periode_awal, $periode_akhir);
    $return = [
      'code' => 200,
      'desc' => 'success',
      'data' => $arr_absen->result(),
    ];
    echo json_encode($return);

  }

  public function import_absen()
  {
  	ini_set('max_execution_time', 3000); 
  	ini_set('memory_limit','2048M');

  	$data = $this->input->post('data');
  	$table = 'app_absensi_manual';

  	foreach ($data as $key) {
  		$min_waktu  = '';
  		$max_waktu  = '';
  		$nik        = $key['nik'];
  		$min_waktu  = $key['min_waktu'];
  		$max_waktu  = $key['max_waktu'];
  		$tanggal    = $key['tanggal'];
  		$masuk      = '';
  		$keluar     = '';
  		$h          = '0';
  		$ltg        = '1';
  		$m8         = '0';
  		$m815       = '0';
  		$m830       = '0';
  		$k5         = '0';
  		$k445       = '0';
  		$k430       = '0';
  		$keterangan = 'Tidak Hadir';
  		$m12        = '0';
  		$k12        = '0';
  		$keterangan = 'Tidak Hadir';
  		$data       = array();

  		$where_check = [
  			'nik'     => $nik,
  			'tanggal' => $tanggal,
  		];
  		$check_table = $this->mcore->get_where($table, $where_check);

			// UPDATE NILAI MASUK
  		if($check_table->row()->masuk == NULL || $check_table->row()->masuk == ""){
  			if($min_waktu > '12:00:00'){
  				$masuk = '';
  				$h   = '0';
  				$ltg = '1';
  			}else{
  				$masuk = $min_waktu;
  				$h     = '1';
  				$ltg   = '0';

  				if($masuk <= '08:00:00'){
  					$m8 = '1';
  				}elseif($masuk <= '08:15:00'){
  					$m815 = '1';
  				}elseif($masuk <= '08:30:00'){
  					$m830 = '1';
  				}elseif($masuk <= '12:00:00'){
  					$m12 = '1';
  				}
  			}

  		}else{
  			$x = 'a';
  			$masuk = $check_table->row()->masuk;
  			$h     = $check_table->row()->h;
  			$ltg   = $check_table->row()->ltg;
  		}
			// END UPDATE NILAI MASUK

			// UPDATE NILAI KELUAR
  		if($check_table->row()->keluar == NULL || $check_table->row()->keluar == ''){
				if($max_waktu < '12:00:01'){
  				$keluar = '';
  				$h      = '0';
  				$ltg    = '1';
  			}else{
  				$keluar = $max_waktu;
  				$h      = '1';
  				$ltg    = '0';

  				if($keluar >= '17:00:00'){
  					$k5 = '1';
  				}elseif($keluar >= '16:45:00'){
  					$k445 = '1';
  				}elseif($keluar >= '16:30:00'){
  					$k430 = '1';
  				}elseif($keluar >= '12:00:01'){
  					$k12 = '1';
  				}
  			}
  		}else{
  			$keluar = $check_table->row()->keluar;
				$h      = $check_table->row()->h;
				$ltg    = $check_table->row()->ltg;
  		}
			// END UPDATE NILAI KELUAR

			// UPDATE KETERANGAN
  		if($masuk == '' && $keluar == ''){
  			$keterangan = 'Tidak Hadir';
  		}elseif($masuk != '' && $keluar == ''){
  			$keterangan = 'Tidak FP Keluar';
  		}elseif($masuk == '' && $keluar != ''){
  			$keterangan = 'Tidak FP Masuk';
  		}elseif($masuk != '' && $keluar != ''){
  			$keterangan = 'Hadir';
  		}
			// END UPDATE KETERANGAN


			// MAIN PROCESS
  		$data = [
  			'masuk'      => $masuk,
  			'keluar'     => $keluar,
  			'h'          => $h,
  			'ltg'        => $ltg,
  			'm8'         => $m8,
  			'm815'       => $m815,
  			'm830'       => $m830,
  			'm12'        => $m12,
  			'k5'         => $k5,
  			'k445'       => $k445,
  			'k430'       => $k430,
  			'k12'        => $k12,
  			'keterangan' => $keterangan,
  		];
  		$this->mcore->update($table, ['nik' => $nik, 'tanggal' => $tanggal], $data);
			// END MAIN PROCESS
  	}

  	http_response_code(200);
  }

}