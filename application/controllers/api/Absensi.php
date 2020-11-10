<?php
header('Access-Control-Allow-Origin: *');
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Absensi extends REST_controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_core', 'mcore');
  }

  public function index_post()
  {
    $count = count($this->post('result')['data']);

    if($count == 0){

    }else{
      for ($i=0; $i <= ($count - 1) ; $i++) {
        $tgl = date('Y-m-d', strtotime($this->post('result')['data'][$i]['tgljam']));
        $jam = date('H:i:s', strtotime($this->post('result')['data'][$i]['tgljam']));

        $nik = $this->post('result')['data'][$i]['nik'];

        $data = [
          'nik'     => $nik,
          'waktu'   => $jam,
          'tanggal' => $tgl
        ];
        $countx = $this->mcore->get_where('app_absen', $data);

        if($countx->num_rows() == 0){
          $exec = $this->mcore->store('app_absen', $data);
        }
      }

      $this->response([
        'status' => TRUE,
        'code'   => '200',
        'description'   => 'Upload Data Success',
      ], REST_Controller::HTTP_OK);
    }
    }
}
