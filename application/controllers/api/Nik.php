<?php
header('Access-Control-Allow-Origin: *');
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Nik extends REST_controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_core', 'mcore');
  }

  public function index_get()
  {
    $no_karyawan = $this->get('no_karyawan');
    if($no_karyawan == ''){
      $no_karyawan = '518.2009.0027';
    }

    $data_p = $this->mcore->get_where('app_pendidikan', ['nik' => $no_karyawan]);

    foreach ($data_p->result() as $v) {
    	if(in_array($v->sarjana, ["0", "-0", NULL]) === FALSE){
    		$pendidikan = $v->sarjana;
    	}elseif(in_array($v->diploma, ["0", "-0", NULL]) === FALSE){
    		$pendidikan = $v->diploma;
    	}else{
    		$pendidikan = $v->sma;
    	}
    }

    $data = $this->mcore->get_where('app_karyawan', ['nik' => $no_karyawan]);
    $info = [];

    if($data->num_rows()){
      foreach ($data->result() as $key) {
				$nik       = $key->nik;
				$nama      = $key->fullname;
				$no_ktp    = $key->no_ktp;
				$tgl_lahir = $key->tgl_lahir;
				$umur      = $this->_hitung_umur($tgl_lahir);
				$jk        = $key->jk;

        $info = [
					'nik'        => $nik,
					'nama'       => $nama,
					'no_ktp'     => $no_ktp,
					'tgl_lahir'  => $tgl_lahir,
					'umur'       => $umur,
					'jk'         => $jk,
					'pendidikan' => $pendidikan,
        ];
      }
    }

    if ($data) {
      if($data->num_rows() == 0){
        $this->response([
					'status'  => FALSE,
					'message' => 'DATA NOT FOUND',
					'nik'     => $no_karyawan
        ], REST_Controller::HTTP_BAD_REQUEST);
      }else{
        $this->response([
					'status' => TRUE,
					'nik'    => $data->num_rows(),
					'info'   => $info
        ], REST_Controller::HTTP_OK);
      }
    } else {
      $this->response([
				'status'  => FALSE,
				'message' => 'DATA NOT FOUND',
				'nik'     => $no_karyawan
      ], REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  public function _hitung_umur($tgl)
  {
		$cur_date = new DateTime('now');
		$bday     = new DateTime($tgl);
		$umur     = $bday->diff($cur_date)->y;
		return $umur;
  }
}