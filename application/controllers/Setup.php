<?php if(!defined('BASEPATH')) exit('No script access allowed');

class Setup extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')) redirect('login');
		$this->load->model('model_setup');
		$this->load->model('model_karyawan');
    $this->load->model('M_core', 'mcore');
    $this->load->model('M_userless','mkl');
    ini_set('max_execution_time', 0); 
    ini_set('memory_limit','2048M');
	}

	function setup_cutoff()
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

  public function action_update_cutoff_2()
  {
    # PROSES KOSONGKAN TABEL APP_ABSEN
    $exec = $this->mcore->truncate_absen();
    if($exec === TRUE){
      # BEGIN TRANSACTION
      $this->db->trans_begin();
      # END BEGIN TRANSACTION
      # 
      # DECLRAE
      $tanggal_cutoff_awal       = $this->convert_date_db_format($this->input->post('from_date'));
      $tanggal_cutoff_akhir      = $this->convert_date_db_format($this->input->post('thru_date'));
      $temp_tanggal_cutoff_awal  = $tanggal_cutoff_awal;
      $temp_tanggal_cutoff_akhir = $tanggal_cutoff_akhir;
      $hari_efektif              = 0;
      # END DECLRAE
      # 
      # UPDATE PERIODE CUTOFF
      $table = 'app_cutoff';
      $where = NULL;
      $data = [
        'from_date' => $tanggal_cutoff_awal,
        'thru_date' => $tanggal_cutoff_akhir,
      ];
      $exec = $this->mcore->update($table, $where, $data);
      # UPDATE PERIODE CUTOFF
      # 
      # GET DATA ARRAY TANGGAL LIBUR & DESCRIPTION NYA
      $table     = 'app_hari_libur';
      $key       = 'tanggal';
      $value_a   = $tanggal_cutoff_awal;
      $value_b   = $tanggal_cutoff_akhir;
      $arr_libur = $this->mcore->get_where_between($table, $key, $value_a, $value_b);
      # END GET DATA ARRAY TANGGAL LIBUR & DESCRIPTION NYA
      # 
      # MAIN LOGIC
      for ($i=0; $i <= $hari_efektif ; $i++) { 
        # code...
      }
      while(strtotime($temp_tanggal_cutoff_awal) <= strtotime($temp_tanggal_cutoff_akhir))
      {
        if($this->check_weekend($temp_tanggal_cutoff_awal) === FALSE)
        {
          # DECLARE
          $description = NULL;
          # END DECLARE
          # 
          if($arr_libur->num_rows() > 0){
            foreach($arr_libur->result() as $key) {
              # DECLARE
              $tanggal_libur     = $key->tanggal;
              $description_libur = $key->description;
              # END DECLARE
              # 
              if($tanggal_libur == $temp_tanggal_cutoff_awal)
              {
                $description = $description_libur;
              } #endif
            } #endforeach

            $this->create_absensi_manual_per_cutoff($temp_tanggal_cutoff_awal, $description, $value_a, $value_b);

            if($this->db->trans_status() === FALSE){
              $this->db->trans_rollback();
              $return = array(
                'code'        => '500',
                'description' => 'Tidak terkoneksi dengan database, silahkan hubungi Team IT',
              );
              echo json_encode($return);
              exit();
            } #endif

          }else{
            # DECLARE
            $description = '';
            # END DECLARE
            $this->create_absensi_manual_per_cutoff($temp_tanggal_cutoff_awal, $description, $value_a, $value_b);

            if($this->db->trans_status() === FALSE){
              $this->db->trans_rollback();
              $return = array(
                'code'        => '500',
                'description' => 'Tidak terkoneksi dengan database, silahkan hubungi Team IT',
              );
              echo json_encode($return);
              exit();
            } #endif

          } #endif

        } #endif

        $temp_tanggal_cutoff_awal = date('Y-m-d', strtotime($temp_tanggal_cutoff_awal."+1day"));
      } #endwhile

      if($this->db->trans_status() === FALSE){
        $this->db->trans_rollback();
        $return = array(
          'code'        => '500',
          'description' => 'Tidak terkoneksi dengan database, silahkan hubungi Team IT',
        );
        echo json_encode($return);
        exit();
      }else{
        $this->db->trans_commit();
        $return = array(
          'code'        => '200',
          'description' => 'Proses Ganti Cutoff Berhasil',
        );
        echo json_encode($return);
        exit();
      } #endif

      # END MAIN LOGIC
    }else{
      # GAGAL MELAKUKAN PROSES TRUNCATE
      $return = array(
        'code'        => '500',
        'description' => 'Tidak terkoneksi dengan database, silahkan hubungi Team IT',
      );
      echo json_encode($return);
      # END GAGAL MELAKUKAN PROSES TRUNCATE
    }
    # END PROSES KOSONGKAN TABEL APP_ABSEN
  }

  public function action_update_cutoff_3()
  {
    # PROSES KOSONGKAN TABEL APP_ABSEN
    $this->db->trans_begin();
    $exec = $this->mcore->truncate_absen();
    if($exec === TRUE){
      #
      # DECLRAE
      $tanggal_cutoff_awal       = $this->convert_date_db_format($this->input->post('from_date'));
      $tanggal_cutoff_akhir      = $this->convert_date_db_format($this->input->post('thru_date'));
      $temp_tanggal_cutoff_awal  = $tanggal_cutoff_awal;
      $temp_tanggal_cutoff_akhir = $tanggal_cutoff_akhir;
      # END DECLRAE
      # 
      # HITUNG HARI EFEKTIF
      $sttime_awal = strtotime($tanggal_cutoff_awal);
      $sttime_akhir = strtotime($tanggal_cutoff_akhir);
      # END HITUNG HARI EFEKTIF
      # 
      # UPDATE PERIODE CUTOFF
      $table = 'app_cutoff';
      $where = NULL;
      $data = [
        'from_date' => $tanggal_cutoff_awal,
        'thru_date' => $tanggal_cutoff_akhir,
      ];
      $exec = $this->mcore->update($table, $where, $data);
      # UPDATE PERIODE CUTOFF
      # 
      # GET DATA ARRAY TANGGAL LIBUR & DESCRIPTION NYA
      $table     = 'app_hari_libur';
      $key       = 'tanggal';
      $value_a   = $tanggal_cutoff_awal;
      $value_b   = $tanggal_cutoff_akhir;
      $arr_libur = $this->mcore->get_where_between($table, $key, $value_a, $value_b);
      # END GET DATA ARRAY TANGGAL LIBUR & DESCRIPTION NYA
      # 
      # MAIN LOGIC
      for($i = $sttime_awal; $i <= $sttime_akhir; $i += (60*60*24)){
        if(date('w', $i) !== '0' && date('w', $i) !== '6'){
          $temp_tanggal_cutoff_awal = date('Y-m-d', $i);
          # DECLARE
          $description = NULL;
          # END DECLARE
          # 
          if($arr_libur->num_rows() > 0){
            foreach($arr_libur->result() as $key) {
              # DECLARE
              $tanggal_libur     = $key->tanggal;
              $description_libur = $key->description;
              # END DECLARE
              # 
              if($tanggal_libur == $temp_tanggal_cutoff_awal)
              {
                $description = $description_libur;
              } #endif
            } #endforeach

            $this->create_absensi_manual_per_cutoff($temp_tanggal_cutoff_awal, $description, $value_a, $value_b);

            if($this->db->trans_status() === FALSE){
              $this->db->trans_rollback();
              $return = array(
                'code'        => '500',
                'description' => 'Tidak terkoneksi dengan database, silahkan hubungi Team IT',
              );
              echo json_encode($return);
              exit();
            } #endif

          }else{
            # DECLARE
            $description = '';
            # END DECLARE
            $this->create_absensi_manual_per_cutoff($temp_tanggal_cutoff_awal, $description, $value_a, $value_b);

            if($this->db->trans_status() === FALSE){
              $this->db->trans_rollback();
              $return = array(
                'code'        => '500',
                'description' => 'Tidak terkoneksi dengan database, silahkan hubungi Team IT',
              );
              echo json_encode($return);
              exit();
            } #endif

          } #endif

        }
      }

      if($this->db->trans_status() === FALSE){
        $this->db->trans_rollback();
        $return = array(
          'code'        => '500',
          'description' => 'Tidak terkoneksi dengan database, silahkan hubungi Team IT',
        );
        echo json_encode($return);
        exit();
      }else{
        $this->db->trans_commit();
        $return = array(
          'code'        => '200',
          'description' => 'Proses Ganti Cutoff Berhasil',
        );
        echo json_encode($return);
        exit();
      } #endif

      # END MAIN LOGIC
    }else{
      # GAGAL MELAKUKAN PROSES TRUNCATE
      $return = array(
        'code'        => '500',
        'description' => 'Tidak terkoneksi dengan database, silahkan hubungi Team IT',
      );
      echo json_encode($return);
      # END GAGAL MELAKUKAN PROSES TRUNCATE
    }
    # END PROSES KOSONGKAN TABEL APP_ABSEN
  }

  public function check_weekend($tanggal)
  {
    if(date('l', strtotime($tanggal)) == 'Saturday' || date('l', strtotime($tanggal)) == 'Sunday')
    {$this->mcore->get_all_data_e_resign('app_karyawan');
      return TRUE;
    }else{
      return FALSE;
    }
  }

  public function create_absensi_manual_per_cutoff($tanggal, $description, $periode_from_date, $periode_thru_date)
  {
    # DECLARE
    $create_by   = $this->session->userdata('logged_in')['fullname'];
    $create_date = date('Y-m-d');
    # END DECLARE
    # GET LIST KARYAWAN
    $table = 'app_karyawan';
    $exec  = $this->mcore->get_all_data_e_resign('app_karyawan');
    # END GET LIST KARYAWAN
    # 
    # PROSES INSERT ABSENSI MANUAL BERDASARKAN LIST KARYAWAN
    $table = 'app_absensi_manual';
    foreach ($exec->result() as $key) {
      $data = [
        'nik'               => $key->nik,
        'masuk'             => '',
        'keluar'            => '',
        'tanggal'           => $tanggal,
        'keterangan'        => $description,
        'periode_from_date' => $periode_from_date,
        'periode_thru_date' => $periode_thru_date,
        'created_by'        => $create_by,
        'created_date'      => $create_date,
      ];
      $exec = $this->mcore->store($table, $data);
      $exec = $this->mcore->null_keterangan($table, $periode_from_date, $periode_thru_date);
      if($exec === FALSE){
        echo "gagal";
        return FALSE;
        break;
      }
    }

    return TRUE;
    # END PROSES INSERT ABSENSI MANUAL BERDASARKAN LIST KARYAWAN

  }

	function action_update_cutoff()
	{
    # PROSES KOSONGKAN TABEL APP_ABSEN
    $exec = $this->mcore->truncate_absen();
    # END PROSES KOSONGKAN TABEL APP_ABSEN
    
    $session_data = $this->session->userdata('logged_in');
    $user         = $session_data['fullname'];
    $from_date    = $this->convert_date_db_format($this->input->post('from_date'));
    $thru_date    = $this->convert_date_db_format($this->input->post('thru_date'));

    $data = array(
      'from_date' => $from_date,
      'thru_date' => $thru_date
    );

    $get_count_periode = $this->model_setup->get_count_periode();

    // INSERT OR UPDATE TABEL APP_CUTOFF
    if($get_count_periode == '0')
    {
      $action_insert_cutoff = $this->model_setup->action_insert_cutoff($data);
    }else{
      $action_update_cutoff = $this->model_setup->action_update_cutoff($data);
    }
    // END INSERT OR UPDATE TABEL APP_CUTOFF
    
    $table              = 'app_karyawan';
    $join_table         = 'app_karyawan_detail';
    $join_condition     = 'app_karyawan.nik = app_karyawan_detail.nik';
    $join_type          = 'left';
    $get_karyawan       = $this->mcore->get_single_join($table, $join_table, $join_condition, $join_type);
    $get_count_karyawan = $get_karyawan->num_rows();

    foreach($get_karyawan->result() as $key => $values)
    {
      $nik[$key]    = $values->nik;
      $nama[$key]   = $values->fullname;
      $status[$key] = $values->status;
    }

    $from_date_ = $from_date;
    $thru_date_ = $thru_date;

    while(strtotime($from_date) <= strtotime($thru_date))
    {
      for($n = 0; $n < $get_count_karyawan; $n++)
      {
        if(date('l', strtotime($from_date)) != 'Saturday' && date('l', strtotime($from_date)) != 'Sunday')
        {
          $get_libur_by_date   = $this->model_karyawan->get_libur_by_date($from_date);
          $get_cuti_by_tanggal = $this->model_karyawan->get_cuti_by_tanggal($from_date, $nik[$n]);
          $get_tlk_by_tanggal  = $this->model_karyawan->get_tlk_by_tanggal($from_date, $nik[$n]);
          $get_dnl_by_tanggal  = $this->model_karyawan->get_dnl_by_tanggal($from_date, $nik[$n]);

          if(is_null($get_libur_by_date['tanggal']))
          {
            // jika tidak ada hari libur
            if($get_cuti_by_tanggal->num_rows() > 0)
            {
              // jika data form ketidak hadiran nya ada
              $data = array(
                'nik'               => $nik[$n],
                'masuk'             => '',
                'keluar'            => '',
                'tanggal'           => $from_date,
                'keterangan'        => $get_cuti_by_tanggal->row('keterangan'),
                'periode_from_date' => $from_date_,
                'periode_thru_date' => $thru_date_,
                'created_date'      => date('Y-m-d H:i:s'),
                'created_by'        => $user
              );

              $update_absensi_manual = $this->model_karyawan->update_absensi_manual($data);
              // end jika data form ketidak hadiran nya ada
            }else{
              // jika data form ketidak hadiran nya tidak ada, setara form absen null
              $data = array(
                'nik'               => $nik[$n],
                'masuk'             => '',
                'keluar'            => '',
                'tanggal'           => $from_date,
                'keterangan'        => '',
                'periode_from_date' => $from_date_,
                'periode_thru_date' => $thru_date_,
                'created_date'      => date('Y-m-d H:i:s'),
                'created_by'        => $user
              );

              $update_absensi_manual = $this->model_karyawan->update_absensi_manual($data);
              // end jika data form ketidak hadiran nya tidak ada, setara form absen null
            }

          // end jika tidak ada hari libur
          }else{
            // jika ada hari libur
            // insert absensi manual dengan value hari libur
            $data = array(
             'nik'               => $nik[$n],
             'masuk'             => '',
             'keluar'            => '',
             'tanggal'           => $from_date,
             'keterangan'        => $get_libur_by_date['description'],
             'periode_from_date' => $from_date_,
             'periode_thru_date' => $thru_date_,
             'created_date'      => date('Y-m-d H:i:s'),
             'created_by'        => $user
            );

            $update_absensi_manual = $this->model_karyawan->update_absensi_manual($data); 
            // end insert absensi manual dengan value hari libur
          }
        }
 
        if(date('m') == '01'){

          if($status[$n] == 10 || $status[$n] == 11 || $status[$n] == 50 || $status[$n] == 40){
            $hak_ijin = 0;
            $hak_cuti = 0;
          }elseif($status[$n] == 20){
            $hak_ijin = 6;
            $hak_cuti = 0;
          }elseif($status[$n] == 21 || $status[$n] == 30 || $status[$n] == 100){
            $hak_ijin = 6;
            $hak_cuti = 12;
          }else{
            $hak_ijin = 0;
            $hak_cuti = 0;
          }

          $update_hak_cuti = $this->model_karyawan->update_hak_cuti($nik[$n], $hak_ijin, $hak_cuti);
        }
      }

      $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date)));
    } // END WHILE

    $this->session->set_flashdata('cutoff', 'ok');
    $return = array(
      'code'        => '200',
      'description' => 'Berhasil',
    );
    echo json_encode($return);
  }

function ip_config()
{
  $session_data = $this->session->userdata('logged_in');
  $data['username'] = $session_data['username'];
  $data['user'] = $session_data['fullname'];
  $data['role_id'] = $session_data['role_id'];

  $data['periode_cutoff'] = $this->model_setup->get_cutoff();
  $data['container'] = 'setup/ip_config';

  $this->load->view('core', $data);			
}

  public function setup_user()
  {
    $session_data           = $this->session->userdata('logged_in');
    $data['username']       = $session_data['username'];
    $data['user']           = $session_data['fullname'];
    $data['role_id']        = $session_data['role_id'];
    
    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $data['get_user']       = $this->model_setup->get_user();
    $data['container']      = 'setup/setup_user';

    $this->load->view('core', $data);		
  }

  public function data_user()
  {
    $list = $this->mkl->get_datatables();
    $data = array();
    $no   = $_POST['start'];

    foreach ($list as $karyawan) {
      $no++;

      if($karyawan->role_id == '0'){
        $role_name = 'Master Admin'; 
        $color = "warning";
      }elseif($karyawan->role_id == '1'){
        $role_name = 'Admin'; 
        $color = "info";
      }elseif($karyawan->role_id == '2'){
        $role_name = 'Karyawan'; 
        $color = "danger";
      }

      $row = array();
      $row[] = $no;
      $row[] = $karyawan->fullname;
      $row[] = $karyawan->cabang;
      $row[] = $role_name;
      $row[] = $karyawan->username;
      $row[] = '
      <div class="btn-group">
        <button class="btn btn-info btn-xs" onClick="change('.$karyawan->user_id.', \''.$karyawan->username.'\', \''.$karyawan->role_id.'\');">Ganti Tipe</button>
        <button class="btn btn-danger btn-xs" onClick="destroy('.$karyawan->user_id.', \''.$karyawan->username.'\');">Delete</button>
        <button class="btn btn-warning btn-xs" onClick="reset('.$karyawan->user_id.', \''.$karyawan->username.'\');">Reset Pass</button>
      </div>
      ';

      $data[] = $row;
    }

    $output = array(
      "draw"            => $_POST['draw'],
      "recordsTotal"    => $this->mkl->count_all(),
      "recordsFiltered" => $this->mkl->count_filtered(),
      "data"            => $data,
      "last_query"      => $this->db->last_query(),
    );
    
    //output to json format
    echo json_encode($output);
  }

function setup_cabang()
{
  $session_data           = $this->session->userdata('logged_in');
  $data['username']       = $session_data['username'];
  $data['user']           = $session_data['fullname'];
  $data['role_id']        = $session_data['role_id'];
  
  $data['periode_cutoff'] = $this->model_setup->get_cutoff();
  $data['get_branch']     = $this->model_setup->get_branch();
  $data['container']      = 'setup/setup_cabang';

  $this->load->view('core', $data);				
}

function get_branch_by_id()
{
  $session_data = $this->session->userdata('logged_in');
  $data['username'] = $session_data['username'];
  $data['user'] = $session_data['fullname'];
  $data['role_id'] = $session_data['role_id'];
  $branch_id = $this->uri->segment(3);
  $data['branch_id'] = $this->uri->segment(3);

  $data['periode_cutoff'] = $this->model_setup->get_cutoff();
  $data['get_branch_by_id'] = $this->model_setup->get_branch_by_id($branch_id);
  $data['container'] = 'setup/branch_detail';

  $this->load->view('core', $data);			
}

function update_branch()
{
  $branch_id = $this->uri->segment(3);
  $branch_name = $this->input->post('branch');

  $update_branch = $this->model_setup->update_branch($branch_id, $branch_name);
  redirect('setup/setup_cabang');
}

function setup_position()
{
  $session_data = $this->session->userdata('logged_in');
  $data['username'] = $session_data['username'];
  $data['user'] = $session_data['fullname'];
  $data['role_id'] = $session_data['role_id'];

  $data['periode_cutoff'] = $this->model_setup->get_cutoff();
  $data['get_position'] = $this->model_setup->get_position();
  $data['container'] = 'setup/setup_position';

  $this->load->view('core', $data);			
}

function get_position_by_id()
{
  $session_data = $this->session->userdata('logged_in');
  $data['username'] = $session_data['username'];
  $data['user'] = $session_data['fullname'];
  $data['role_id'] = $session_data['role_id'];
  $position_id = $this->uri->segment(3);
  $data['position_id'] = $this->uri->segment(3);

  $data['periode_cutoff'] = $this->model_setup->get_cutoff();
  $data['get_position_by_id'] = $this->model_setup->get_position_by_id($position_id);
  $data['container'] = 'setup/position_detail';

  $this->load->view('core', $data);			
}

function update_position()
{
  $position_id = $this->uri->segment(3);
  $position = $this->input->post('position');

  $update_position = $this->model_setup->update_position($position_id, $position);
  redirect('setup/setup_position');
}

function update_parameter()
{
  $session_data = $this->session->userdata('logged_in');
  $data['username'] = $session_data['username'];
  $data['user'] = $session_data['fullname'];
  $data['role_id'] = $session_data['role_id'];
  $position_id = $this->uri->segment(3);
  $data['position_id'] = $this->uri->segment(3);

  $data['periode_cutoff'] = $this->model_setup->get_cutoff();
  $data['get_position_by_id'] = $this->model_setup->get_position_by_id($position_id);
  $data['container'] = 'setup/update_parameter';

  $this->load->view('core', $data);			
}

function setup_hari_libur()
{
  $session_data = $this->session->userdata('logged_in');
  $data['username'] = $session_data['username'];
  $data['user'] = $session_data['fullname'];
  $data['role_id'] = $session_data['role_id'];
  $position_id = $this->uri->segment(3);
  $data['position_id'] = $this->uri->segment(3);

  $data['periode_cutoff'] = $this->model_setup->get_cutoff();
  $data['get_hari_libur'] = $this->model_setup->get_hari_libur();
  $data['container'] = 'setup/setup_hari_libur';

  $this->load->view('core', $data);			
}

function action_setup_hari_libur()
{
	$this->db->trans_begin();
	$date_obj  = new DateTime();
	$tgl_libur = $date_obj->createFromFormat('d-m-Y', $this->input->post('tgl'));
	$ket_libur = $this->input->post('ket_libur');

	// insert tabel app_hari_libur
	$data_libur = [
		'tanggal'     => $tgl_libur->format('Y-m-d'),
		'description' => $ket_libur,
	];
	$this->mcore->store('app_hari_libur', $data_libur);

	$data_absensi_manual = [
		'masuk'      => '',
		'keluar'     => '',
		'keterangan' => $ket_libur,
		'l'          => '1',
		'h'          => '0',
		'tlk'        => '0',
		'c'          => '0',
		'ck'         => '0',
		'ck'         => '0',
		'dnl'        => '0',
		'sd'         => '0',
		'i'          => '0',
		'ltg'        => '0',
		'm8'         => '0',
		'm815'       => '0',
		'm830'       => '0',
		'm12'        => '0',
		'k5'         => '0',
		'k445'       => '0',
		'k430'       => '0',
		'k12'        => '0',
		'k12'        => '0'
	];

	$where_absensi_manual = ['tanggal' => $tgl_libur->format('Y-m-d')];
	$this->mcore->update('app_absensi_manual', $where_absensi_manual, $data_absensi_manual);

	if($this->db->trans_status()===true){
    $this->db->trans_commit();
    redirect('setup/setup_hari_libur');
  }else{
    $this->db->trans_rollback();
    redirect('setup/setup_hari_libur');
  }

	// $tgl   = $this->convert_date_db_format();
	// $bulan = date('m', strtotime($tgl));

 //  $data = array(
 //    'tanggal'     => $tgl,
 //    'description' => $this->input->post('ket_libur')
 //  );

 //  $this->db->trans_begin();
 //  $this->model_setup->action_setup_hari_libur($data);
 //  if($this->db->trans_status()===true){
 //    $this->db->trans_commit();
 //    redirect('setup/setup_hari_libur');
 //  }else{
 //    $this->db->trans_rollback();
 //    redirect('setup/setup_hari_libur');
 //  }
}

function get_libur_by_id()
{
  $session_data = $this->session->userdata('logged_in');
  $data['username'] = $session_data['username'];
  $data['user'] = $session_data['fullname'];
  $data['role_id'] = $session_data['role_id'];
  $id = $this->uri->segment(3);

  $data['periode_cutoff'] = $this->model_setup->get_cutoff();
  $data['get_libur_by_id'] = $this->model_setup->get_libur_by_id($id);
  $data['container'] = 'setup/get_libur_by_id';

  $this->load->view('core', $data);			
}

function action_update_setup_hari_libur()
{
  $id = $this->uri->segment(3);
  $tgl       = $this->convert_date_db_format($this->input->post('tgl'));

  $data = array(
    'tanggal'     => $tgl,
    'description' => $this->input->post('ket_libur')
  );

  $this->db->trans_begin();
  $this->model_setup->action_update_setup_hari_libur($data, $id);
  if($this->db->trans_status()===true){
    $this->db->trans_commit();
    redirect('setup/setup_hari_libur');
  }else{
    $this->db->trans_rollback();
    redirect('setup/setup_hari_libur');
  }
}

function setup_parameter()
{
  $session_data                   = $this->session->userdata('logged_in');
  $data['username']               = $session_data['username'];
  $data['user']                   = $session_data['fullname'];
  $data['role_id']                = $session_data['role_id'];
  
  $data['periode_cutoff']         = $this->model_setup->get_cutoff();
  $data['get_kategori_parameter'] = $this->model_setup->get_kategori_parameter();
  $data['container']              = 'setup/setup_parameter';

  $this->load->view('core', $data);			
}

function jqgrid_list_parameter(){
  $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
  $limit_rows = isset($_REQUEST['rows'])?$_REQUEST['rows']:15;
  $sidx = isset($_REQUEST['sidx'])?$_REQUEST['sidx']:'registration_no';
  $sort = isset($_REQUEST['sord'])?$_REQUEST['sord']:'DESC';
  $parameter_group = $_REQUEST['parameter_group'];

  $totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows'] : FALSE;

  if($totalrows){
   $limit_rows = $totalrows;
 }

 $count = $this->model_setup->jqgrid_count_parameter_by_parameter_group($parameter_group);

 if ($count > 0){
   $total_pages = ceil($count / $limit_rows);
 } else {
   $total_pages = 0;
 }

 if ($page > $total_pages)
  $page = $total_pages;
$start = $limit_rows * $page - $limit_rows;
if ($start < 0) $start = 0;

$result = $this->model_setup->jqgrid_list_parameter_by_parameter_group($parameter_group);

$responce['page'] = $page;
$responce['total'] = $total_pages;
$responce['records'] = $count;

$i = 0;

foreach ($result as $row){

 $parameter_group = $row['parameter_group'];
 $parameter_id = $row['parameter_id'];
 $description = $row['description'];

 $responce['rows'][$i]['parameter_group'] = $parameter_group;
 $responce['rows'][$i]['cell'] = array($parameter_group,$parameter_id,$description);

 $i++;
}

echo json_encode($responce);
}

function add_parameter()
{
  $session_data                   = $this->session->userdata('logged_in');
  $data['username']               = $session_data['username'];
  $data['user']                   = $session_data['fullname'];
  $data['role_id']                = $session_data['role_id'];
  
  $data['periode_cutoff']         = $this->model_setup->get_cutoff();
  $data['get_kategori_parameter'] = $this->model_setup->get_kategori_parameter();
  $data['container']              = 'setup/add_parameter';

  $this->load->view('core', $data);
}

function action_add_parameter()
{
  $parameter_group = $this->input->post('parameter_group');
  $id = $this->model_setup->get_id_parameter($parameter_group);

  foreach($id as $values)
  {
   $last_id = $values['max'] + 1;
 }

 $data = array('parameter_group' => $parameter_group,
  'parameter_id' => $last_id,
  'description' => $this->input->post('description'));

 $this->db->trans_begin();
 $this->model_setup->action_add_parameter($data);
 if($this->db->trans_status()===true){
  $this->db->trans_commit();
  redirect('setup/setup_parameter');
}else{
  $this->db->trans_rollback();
  redirect('setup/setup_parameter');
}

}

function add_user()
{
  $session_data        = $this->session->userdata('logged_in');
  $data['username']    = $session_data['username'];
  $data['user']        = $session_data['fullname'];
  $data['role_id']     = $session_data['role_id'];
  $data['branch_user'] = $session_data['branch_code'];
  $branch_user         = $session_data['branch_code'];

  if($data['role_id'] == "1" || $data['role_id'] == "0")
  {
    $data['get_branch'] = $this->model_karyawan->get_branch();
  }else
  {
    $data['get_branch'] = $this->model_karyawan->get_branch_by_user($branch_user);
  }

  $data['periode_cutoff'] = $this->model_setup->get_cutoff();
  $data['get_kategori_parameter'] = $this->model_setup->get_kategori_parameter();
  $data['get_karyawan_by_branch'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);
  $data['container'] = 'setup/add_user';

  $this->load->view('core', $data);			
}

function action_add_user()
{
  $nik         = $this->input->post('nik_');
  $fullname    = $this->input->post('fullname');
  $branch_code = $this->input->post('branch_');
  $username    = $this->input->post('username');
  $password    = $this->input->post('password');
  $password2   = $this->input->post('password2');
  $role_id     = $this->input->post('role_id');

  if($password == $password2)
  {
   $data = array(
    'nik'          => $nik,
    'fullname'     => $fullname,
    'branch_code'  => $branch_code,
    'username'     => $username,
    'password'     => md5($password),
    'role_id'      => $role_id,
    'created_date' => date('Y-m-d')
   );

   $action_add_user = $this->model_setup->action_add_user($data);
   redirect('setup/setup_user');

 }else
 {
  echo "<script>alert('Password tidak sama!!');history.go(-1)</script>";   
  die(); 			
}
}

function get_user_by_id()
{
  $session_data = $this->session->userdata('logged_in');
  $data['username'] = $session_data['username'];
  $data['user'] = $session_data['fullname'];
  $data['role_id'] = $session_data['role_id'];
  $data['branch_user'] = $session_data['branch_code'];
  $branch_user = $session_data['branch_code'];
  $user_id = $this->uri->segment(3);

  $data['periode_cutoff'] = $this->model_setup->get_cutoff();
  $data['get_kategori_parameter'] = $this->model_setup->get_kategori_parameter();
  $data['get_user_by_id'] = $this->model_setup->get_user_by_id($user_id);
  $data['container'] = 'setup/get_user_by_id';

  $this->load->view('core', $data);			
}

  function action_update_user()
  {
    $nik         = $this->input->post('nik_');
    $fullname    = $this->input->post('fullname');
    $branch_code = $this->input->post('branch_');
    $username    = $this->input->post('username');
    $password    = $this->input->post('password');
    $password2   = $this->input->post('password2');
    $role_id     = $this->input->post('role_id');

    if($password == $password2)
    {
      $this->db->trans_begin();
      $this->model_setup->action_update_user($nik, $username, $password, $role_id);
   
      if($this->db->trans_status()===true){
        $this->db->trans_commit();
        echo "<script>alert('Berhasil');</script>";   
        redirect('setup/setup_user');
      }else{
        $this->db->trans_rollback();
        echo "<script>alert('Gagal');</script>";  
        redirect('setup/setup_user');
      }

    }else{
      echo "<script>alert('Password tidak sama!!');history.go(-1)</script>";   
      die(); 			
    }
  }

  public function tambah_cabang()
  {
    # REQUIRED
    $session_data           = $this->session->userdata('logged_in');
    $data['username']       = $session_data['username'];
    $data['user']           = $session_data['fullname'];
    $data['role_id']        = $session_data['role_id'];
    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $data['get_branch']     = $this->model_setup->get_branch();
    # END REQUIRED
    
    $data['container'] = 'setup/tambah_cabang';
    $this->load->view('core', $data);
  }

  public function store_cabang()
  {
    $nama_cabang     = $this->input->post('nama_cabang');
    $parameter_group = 'cabang';
    $table           = 'app_parameter';
    $where           = array('parameter_group' => $parameter_group);
    $order_key       = 'parameter_id';
    $order_type      = 'desc';
    $limit           = 1;
    $offset          = 0;
    $new_id          = $this->mcore->get_paramater_last_id($table, $where, $order_key, $order_type, $limit, $offset)->row('parameter_id') + 1;

    $store = array(
      'parameter_group' => $parameter_group,
      'parameter_id'    => $new_id,
      'description'     => $nama_cabang
    );

    $exec = $this->mcore->store($table, $store);

    if($exec === true){
      $return = array(
        'code'         => '200',
        'description'  => 'Proses tambah cabang berhasil',
        '$nama_cabang' => $nama_cabang
      );
    }else{
      $return = array(
        'code'         => '400',
        'description'  => 'Proses tambah cabang gagal, silahkan coba kembali',
        '$nama_cabang' => $nama_cabang
      );
    }

    echo json_encode($return);
  }

  public function destroy_cabang()
  {
    $id    = $this->input->post('id');
    
    $table = 'app_parameter';
    $where = array('parameter_id' => $id);
    $exec  = $this->mcore->destroy($table, $where);

    if($exec === true){
      $return = array(
        'code'         => '200',
        'description'  => 'Proses delete cabang berhasil'
      );
    }else{
      $return = array(
        'code'         => '400',
        'description'  => 'Proses delete cabang gagal, silahkan coba kembali'
      );
    }

    echo json_encode($return);
  }

  ///////////////////////////////////////////////////////////////////////////////////////////
  /// USER
  //////////////////////////////////////////////////////////////////////////////////////////
 
  public function list_karyawan_branch()
  {
    
    $id_branch      = $this->input->get('id_branch');
    
    $table          = 'app_karyawan';
    $where          = array('app_karyawan_detail.thru_branch' => $id_branch);
    $join_table     = 'app_karyawan_detail';
    $join_condition = 'app_karyawan_detail.nik = app_karyawan.nik';
    $join_type      = 'left';
    $arr            = $this->mcore->get_where_single_join($table, $where, $join_table, $join_condition, $join_type)->result_array();
    echo json_encode($arr);
  }

  public function detail_karyawan_nik()
  {
    $nik = $this->input->get('nik');

    //$arr = $this->model_karyawan->get_karyawan_by_nik_($nik)->result();
    $arr = $this->mcore->get_karyawan_by_nik($nik)->result();

    echo json_encode($arr);
  }

  public function destroy_user()
  {
    $id    = $this->input->post('id');
    
    $table = 'app_user';
    $where = array('user_id' => $id);
    $exec  = $this->mcore->destroy($table, $where);

    if($exec === true){
      $return = array(
        'code'         => '200',
        'description'  => 'Proses delete user berhasil'
      );
    }else{
      $return = array(
        'code'         => '400',
        'description'  => 'Proses delete user gagal, silahkan coba kembali'
      );
    }

    echo json_encode($return);
  }

  public function update_pass()
  {
    $id = $this->input->post('id_x');
    $new_pass = $this->input->post('new_pass', true);
    $new_pass = md5($new_pass);

    $table = 'app_user';
    $where = array('user_id' => $id);
    $data = array('password' => $new_pass);
    $exec = $this->mcore->update($table, $where, $data);

    if($exec === true){
      $return = array(
        'code'         => '200',
        'description'  => 'Proses reset password user berhasil'
      );
    }else{
      $return = array(
        'code'         => '400',
        'description'  => 'Proses reset password user gagal, silahkan coba kembali'
      );
    }

    echo json_encode($return);
  }

  public function update_tipe()
  {
    $id = $this->input->post('id_x2');
    $tipe = $this->input->post('role_id');

    $table = 'app_user';
    $where = array('user_id' => $id);
    $data = array('role_id' => $tipe);
    $exec = $this->mcore->update($table, $where, $data);

    if($exec === true){
      $return = array(
        'code'         => '200',
        'description'  => 'Proses ganti tipe user berhasil'
      );
    }else{
      $return = array(
        'code'         => '400',
        'description'  => 'Proses ganti tipe user gagal, silahkan coba kembali'
      );
    }

    echo json_encode($return);
  }

  ///////////////////////////////////////////////////////////////////////////////////////////
  /// END USER
  //////////////////////////////////////////////////////////////////////////////////////////
  
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