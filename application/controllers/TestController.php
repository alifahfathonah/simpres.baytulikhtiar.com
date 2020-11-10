<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TestController extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_core', 'mcore');
    $this->load->model('Model_karyawan', 'model_karyawan');
  }


  public function index($periode_from_date, $periode_thru_date)
  {
    $table = 'app_absensi_manual';
    $exec = $this->mcore->null_keterangan($table, $periode_from_date, $periode_thru_date);
    
  }

  public function single_cutoff($nik, $from_date, $thru_date)
  {
    # BEGIN TRANSACTION
    $this->db->trans_begin();
    # END BEGIN TRANSACTION
    # 
    # DECLRAE
    $tanggal_cutoff_awal       = $from_date;
    $tanggal_cutoff_akhir      = $thru_date;
    $temp_tanggal_cutoff_awal  = $tanggal_cutoff_awal;
    $temp_tanggal_cutoff_akhir = $tanggal_cutoff_akhir;
    $hari_efektif              = 0;
    $description               = NULL;
    # END DECLRAE
    # 
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
    while(strtotime($temp_tanggal_cutoff_awal) <= strtotime($temp_tanggal_cutoff_akhir)){
      if($this->check_weekend($temp_tanggal_cutoff_awal) === FALSE)
      {
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

          $this->create_absensi_manual_per_cutoff($nik, $temp_tanggal_cutoff_awal, $description, $value_a, $value_b);

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
          $this->db->trans_begin();
          $this->create_absensi_manual_per_cutoff($nik, $temp_tanggal_cutoff_awal, $description, $value_a, $value_b);

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

      $temp_tanggal_cutoff_awal = date('Y-m-d', strtotime($temp_tanggal_cutoff_awal."+1 day"));
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
        'description' => 'Proses Absensi Manual Berhasil',
      );
      echo json_encode($return);
      exit();
    } #endif

    # END MAIN LOGIC

  }

  public function create_absensi_manual_per_cutoff($nik, $tanggal, $description, $periode_from_date, $periode_thru_date)
  {
    # DECLARE
    $create_by   = $this->session->userdata('logged_in')['fullname'];
    $create_date = date('Y-m-d');
    # END DECLARE
    # 
    # PROSES INSERT ABSENSI MANUAL BERDASARKAN LIST KARYAWAN
    $table = 'app_absensi_manual';
    $data = [
      'nik'               => $nik,
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

    return TRUE;
    # END PROSES INSERT ABSENSI MANUAL BERDASARKAN LIST KARYAWAN

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

  }

  /* End of file TestController.php */
/* Location: ./application/controllers/TestController.php */