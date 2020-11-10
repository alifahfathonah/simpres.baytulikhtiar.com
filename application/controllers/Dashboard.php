<?php 
if(!defined('BASEPATH')) exit('No script access allowed');

class Dashboard extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    if(!$this->session->userdata('logged_in')) redirect('login');
    $this->load->model('model_setup');
    $this->load->model('model_karyawan');
    $this->load->model('model_user');
    $this->load->model('M_core', 'mcore');
    $this->load->library('session');
  }

  public function index()
  {
    $session_data           = $this->session->userdata('logged_in');
    $data['username']       = $session_data['username'];
    $data['user']           = $session_data['fullname'];
    $data['role_id']        = $session_data['role_id'];
    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $data['container']      = 'dashboard';

    foreach ($data['periode_cutoff'] as $key) {
      $from_date     = $key->from_date;
      $thru_date     = $key->thru_date;
      $thru_date_now = date('Y-m-d');
    }

    //$data['card_a']          = $this->card_a();
    //$data['card_a_modal']    = $this->card_a_modal();
    //$data['card_b']          = $this->card_b($from_date, $thru_date, $thru_date_now);
    //$data['card_b_modal']    = $this->card_b_modal($from_date, $thru_date_now);
    //$data['card_c']          = $this->card_c($from_date, $thru_date, $thru_date_now);
    $data['card_c'] = 0;
    $data['expired_kontrak'] = $this->mcore->expired_kontrak()->result();

    $this->load->view('core', $data);
  }

  public function _hitung_persen($from_date, $thru_date, $parameter_id, $thru_date_now)
  {
    #############################
    #  ( A / ( B * C ) ) * 100  #
    #############################
    # A = (total tidak hadir - ( total ijin + total cuti + total sakit + total cuti khusus )
    # B = hari efektif - hari libur
    # C = total karyawan

    $a = $this->count_tidak_hadir($from_date, $thru_date, $thru_date_now, $parameter_id);
    $b = $this->count_hari_efektif($from_date, $thru_date, $thru_date_now);
    //echo $b;
    exit();
    $c = $this->card_a($parameter_id);
    //return $a;
    
    if($a == 0){
      return 0;
    }else{
      return $persentase = ($a / ( $b * $c ) * 100);
    }
  }

  public function card_a($parameter_id = null)
  {
    $karyawan = $this->mcore->get_card_karyawan($parameter_id);
    echo json_encode($karyawan);
  }

  public function card_a_modal()
  {
    $data['card_a_modal'] = $this->mcore->get_count_karyawan()->result();
    $view = $this->load->view('dashboard/card_a_modal', $data, TRUE);
    echo $view;
  }

  public function card_b()
  {
    # get cutoff date
    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $from_date              = $data['periode_cutoff'][0]->from_date;
    $thru_date              = $data['periode_cutoff'][0]->thru_date;
    $thru_date_now          = date('Y-m-d');
    # end get cutoff date
    # 
    $arr_libur = $this->mcore->get_where('app_hari_libur', [
      'tanggal >= ' => $from_date,
      'tanggal <= ' => $thru_date_now,
    ]);

    $arr_liburnya = '';
    foreach ($arr_libur->result() as $key) {
      $arr_liburnya .= $key->tanggal.',';
    }

    $arr_liburnya = rtrim($arr_liburnya, ',');
    $arr_x = $this->mcore->get_belum_absen($from_date, $thru_date_now, $arr_liburnya);

    $total_belum_absen = 0;
    foreach ($arr_x->result() as $key) {
      $total_belum_absen += $key->total;
    }
    echo json_encode($total_belum_absen);
    exit();
  }

  public function card_b_modal(){
    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    foreach ($data['periode_cutoff'] as $key) {
      $from_date     = $key->from_date;
      $thru_date     = $key->thru_date;
      $thru_date_now = date('Y-m-d');
    }
    $arr_libur = $this->mcore->get_where('app_hari_libur', [
      'tanggal >= ' => $from_date,
      'tanggal <= ' => $thru_date_now,
    ]);

    $arr_liburnya = '';
    foreach ($arr_libur->result() as $key) {
      $arr_liburnya .= $key->tanggal.',';
    }

    $arr_liburnya = rtrim($arr_liburnya, ',');
    $data['card_b_modal'] = $this->mcore->get_belum_absen($from_date, $thru_date_now, $arr_liburnya)->result();
    $view = $this->load->view('dashboard/card_b_modal', $data, TRUE);
    echo $view;
  }

  public function card_c()
  {
    $arr_cutoffs = $this->mcore->get_all_data('app_cutoff');

    if($arr_cutoffs->num_rows() > 0){

			$tgl_obj_now = new DateTime('now');
			$tgl_obj     = new DateTime();

	    foreach ($arr_cutoffs->result() as $arr_cutoff) {
				$from_date  = $tgl_obj->createFromFormat('Y-m-d', $arr_cutoff->from_date);
				$thru_date  = $tgl_obj->createFromFormat('Y-m-d', $arr_cutoff->thru_date);
	    }

	    $now_date   = $tgl_obj_now->format('Y-m-d');
			$interval   = $thru_date->diff($from_date);
			$hari_aktif = $interval->days;

			$periods = new DatePeriod($from_date, new DateInterval('P1D'), $thru_date);

			$arr_liburs = $this->mcore->get_all_data('app_hari_libur');

			$libur = array();

			if($arr_liburs->num_rows() > 0){
				foreach ($arr_liburs->result() as $arr_libur) {
					array_push($libur, $arr_libur->tanggal);
				}
			}

			foreach ($periods as $period) {
				$curr = $period->format('D');

				if($curr == 'Sat' || $curr == 'Sun'){
					$hari_aktif--;
				}elseif(in_array($period->format('Y-m-d'),$libur)){
					$hari_aktif--;
				}
			}

			if($this->session->userdata('logged_in')['role_id'] == '0'){
				$where_parameter = ['parameter_group' => 'cabang'];
			}else{
				$where_parameter = ['parameter_group' => 'cabang', 'parameter_id' => $this->session->userdata('logged_in')['branch_code']];
			}
			$arr_cabangs     = $this->mcore->get_where('app_parameter', $where_parameter);

			if($arr_cabangs->num_rows() > 0){

				$data['html'] = '';
				$total_persentase = 0;

				foreach ($arr_cabangs->result() as $arr_cabang) {
					$total_presensi = 0;
					$total_karyawan = 0;
					$parameter_id   = $arr_cabang->parameter_id;
					$nama_cabang    = $arr_cabang->description;

					$where_karyawan = ['thru_branch' => $parameter_id, 'status !=' => '50'];
					$arr_karyawans = $this->mcore->get_where('app_karyawan_detail', $where_karyawan);

					if($arr_karyawans->num_rows() > 0){

						foreach ($arr_karyawans->result() as $arr_karyawan) {
							$nik = $arr_karyawan->nik;
							$arr_presensis = $this->mcore->get_presensi_by_nik_filled($nik, $from_date->format('Y-m-d'));

							if($arr_presensis->num_rows() > 0){

								foreach ($arr_presensis->result() as $arr_presensi) {
									$total_presensi += $arr_presensi->total;
								}

							}

							$total_karyawan++;

						}

						$persentase[] = number_format(($total_presensi / ($total_karyawan * $hari_aktif)) * 100, 2);

					}

				}

				for($i=0; $i < count($persentase); $i++){
					$total_persentase += $persentase[$i];
				}

				$nilai_akhir = $total_persentase / count($persentase);
				$data['html'] .= number_format($nilai_akhir,2);

				echo json_encode($data['html']);

			}else{
				echo "Cabang Tidak Ditemukan...";
				exit();
			}

	  }else{
	  	echo "Cutoff belum di set...";
	  	exit();
	  }

  }

  public function card_c_modal(){
    $arr_cutoffs = $this->mcore->get_all_data('app_cutoff');

    if($arr_cutoffs->num_rows() > 0){

			$tgl_obj_now = new DateTime('now');
			$tgl_obj     = new DateTime();

	    foreach ($arr_cutoffs->result() as $arr_cutoff) {
				$from_date  = $tgl_obj->createFromFormat('Y-m-d', $arr_cutoff->from_date);
				$thru_date  = $tgl_obj->createFromFormat('Y-m-d', $arr_cutoff->thru_date);
	    }

	    $now_date   = $tgl_obj_now->format('Y-m-d');
			$interval   = $thru_date->diff($from_date);
			$hari_aktif = $interval->days;

			$periods = new DatePeriod($from_date, new DateInterval('P1D'), $thru_date);

			$arr_liburs = $this->mcore->get_all_data('app_hari_libur');

			$libur = array();

			if($arr_liburs->num_rows() > 0){
				foreach ($arr_liburs->result() as $arr_libur) {
					array_push($libur, $arr_libur->tanggal);
				}
			}

			foreach ($periods as $period) {
				$curr = $period->format('D');

				if($curr == 'Sat' || $curr == 'Sun'){
					$hari_aktif--;
				}elseif(in_array($period->format('Y-m-d'),$libur)){
					$hari_aktif--;
				}
			}

			if($this->session->userdata('logged_in')['role_id'] == '0'){
				$where_parameter = ['parameter_group' => 'cabang'];
			}else{
				$where_parameter = ['parameter_group' => 'cabang', 'parameter_id' => $this->session->userdata('logged_in')['branch_code']];
			}
			$arr_cabangs     = $this->mcore->get_where('app_parameter', $where_parameter);

			if($arr_cabangs->num_rows() > 0){

				$data['html'] = '';

				foreach ($arr_cabangs->result() as $arr_cabang) {
					$total_presensi = 0;
					$total_karyawan = 0;
					$parameter_id   = $arr_cabang->parameter_id;
					$nama_cabang    = $arr_cabang->description;

					$where_karyawan = ['thru_branch' => $parameter_id, 'status !=' => '50'];
					$arr_karyawans = $this->mcore->get_where('app_karyawan_detail', $where_karyawan);

					if($arr_karyawans->num_rows() > 0){

						foreach ($arr_karyawans->result() as $arr_karyawan) {
							$nik = $arr_karyawan->nik;
							$arr_presensis = $this->mcore->get_presensi_by_nik_filled($nik, $from_date->format('Y-m-d'));

							if($arr_presensis->num_rows() > 0){

								foreach ($arr_presensis->result() as $arr_presensi) {
									$total_presensi += $arr_presensi->total;
								}

							}

							$total_karyawan++;

						}

					}

					if($total_presensi > 0){
						$persentase = number_format(($total_presensi / ($total_karyawan * $hari_aktif)) * 100, 2);
						$data['html'] .= '<tr><td>'.$nama_cabang.'</td><td>'.$persentase.'</td></tr>';
					}

				}

				$view = $this->load->view('dashboard/card_c_modal', $data, TRUE);

				echo $view;

			}else{
				echo "Cabang Tidak Ditemukan...";
				exit();
			}

	  }else{
	  	echo "Cutoff belum di set...";
	  	exit();
	  }
  }

  public function count_tidak_hadir($from_date, $thru_date, $thru_date_now, $parameter_id = null)
  {
    $hari_efektif_dengan_libur = $this->count_hari_efektif($from_date, $thru_date, $thru_date_now);
    $jumlah_karyawan           = $this->card_a($parameter_id);
    $masuk_total               = $hari_efektif_dengan_libur * $jumlah_karyawan;
    $arr_belum_absen           = $this->mcore->get_card_karyawan_belum_absen($from_date, $thru_date_now, $parameter_id);
    $total                     = 0;

    foreach ($arr_belum_absen->result() as $key) {
      $total += $key->total;
    }

    $jumlah_libur            = $this->mcore->jumlah_hari_libur($from_date, $thru_date_now)->row('total');
    $jumlah_libur_x_karyawan = $jumlah_libur * $jumlah_karyawan;
    $jumlah_absen            = $this->mcore->jumlah_alpha_cutoff($from_date, $thru_date_now, null)->row('total');
    $jumlah_sakit            = $this->mcore->jumlah_sakit_cutoff($from_date, $thru_date_now, $parameter_id)->row('total');
    $jumlah_ijin             = $this->mcore->jumlah_ijin_cutoff($from_date, $thru_date_now, $parameter_id)->row('total');
    $jumlah_cuti             = $this->mcore->jumlah_cuti_cutoff($from_date, $thru_date_now, $parameter_id)->row('total');
    $jumlah_cuti_khusus      = $this->mcore->jumlah_cuti_khusus_cutoff($from_date, $thru_date_now, $parameter_id)->row('total');
    return $belum_absen = $total;
  }

  public function count_hari_efektif($from_date, $thru_date, $thru_date_now)
  {
    $jumlah_kerja = $this->mcore->jumlah_hari_kerja($from_date, $thru_date, $thru_date_now)->row('total');
    $jumlah_libur = $this->mcore->jumlah_hari_libur($from_date, $thru_date_now)->row('total');
    $hari_efektif_dengan_libur = $jumlah_kerja - $jumlah_libur;
    return $jumlah_libur;
  }

  /*public function repair_detik()
  {
    $exec = $this->mcore->repair_detik();

    if($exec === TRUE){
      $this->session->set_flashdata('repair', 'success');
    }else{
      $this->session->set_flashdata('repair', 'failed');
    }

    redirect('dashboard');
  }*/

  
}