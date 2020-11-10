<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Maintenance extends CI_Controller {

  public function index()
  {
    $this->load->view('maintenance/index');
  }

}

/* End of file Maintenance.php */
/* Location: ./application/controllers/Maintenance.php */