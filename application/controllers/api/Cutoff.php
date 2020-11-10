<?php
header('Access-Control-Allow-Origin: *');
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Cutoff extends REST_controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_core', 'mcore');
  }

  public function index_get()
  {
    $data = $this->mcore->get_all_data('app_cutoff')->result();
    foreach ($data as $key) {
      $awal = $key->from_date;
      $akhir = $key->thru_date;
    }

    if ($data) {
      $this->response([
        'status' => TRUE,
        'awal'   => $awal,
        'akhir'  => $akhir,
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status' => FALSE,
        'message' => 'DATA NOT FOUND'
      ], REST_Controller::HTTP_NOT_FOUND);
    }
  }

  public function index_post()
  {
    $id = $this->post('id');
    $this->response([
      'status' => TRUE,
      'awal'   => $id,
      'akhir'  => $akhir,
    ], REST_Controller::HTTP_OK);
  }
}