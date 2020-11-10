<?php	if(!defined('BASEPATH')) exit ('no direct script access allowed');

class Authentication extends CI_Controller{

  public function __Construct()
  {
    parent :: __construct();
    $this->load->model('model_user');
    $this->load->library('form_validation', 'session');
  }

  function index()
  {
    $username = $this->input->post('username');
    $password = md5($this->input->post('password'));
    $result   = $this->model_user->login($username, $password);

    if($result){
      $sess_array = array();
      foreach ($result as $row){
        $sess_array = array(
          'user_id'     => $row->user_id,
          'username'    => $row->username,
          'fullname'    => $row->fullname,
          'branch_code' => $row->branch_code,
          'role_id'     => $row->role_id
        );
        $this->session->set_userdata('logged_in', $sess_array);
      }

      redirect('dashboard');
      
    }else{
      $this->session->set_flashdata('login_message','Incorrect Username or Password !');
      redirect('login','refresh');				
    }
  }

}
?>