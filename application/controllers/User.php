<?php if(!defined('BASEPATH')) exit('No script access allowed');

class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')) redirect('login');
		$this->load->model('model_user');
		$this->load->model('model_setup');
    $this->load->model('M_core', 'mcore');
	}

	function index()
	{
    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');

    $this->form_validation->set_rules('old_password', 'Old Password', 
      array(
        'required', 
        'min_length[5]', 
        'max_length[20]', 
        'callback_old_password'
      )
    );
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|max_length[20]');
    $this->form_validation->set_rules('password2', 'Confirmation Password', 'required|min_length[5]|max_length[20]|matches[password]');

    $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

    if($this->form_validation->run() == FALSE)
    {
      $session_data           = $this->session->userdata('logged_in');
      $data['username']       = $session_data['username'];
      $data['user']           = $session_data['fullname'];
      $data['id']             = $session_data['user_id'];
      $data['role_id']        = $session_data['role_id'];
      
      $data['periode_cutoff'] = $this->model_setup->get_cutoff();
      $data['container']      = 'user/user';

      $this->load->view('core', $data);
    }
    else
    {
      $password = md5($this->input->post('password'));
      $exec = $this->update_password($password);

      if($exec === true){
        $this->session->set_flashdata('reset', 'berhasil');
      }else{
        $this->session->set_flashdata('reset', 'gagal');
      }
      redirect('user');
    }
  }

  public function old_password($old_password)
  {
    $old_password = md5($old_password);
    $user_id      = $this->session->userdata('logged_in')['user_id'];
    $db_pass      = $this->mcore->get_password($user_id)->row('password');

    if($old_password == $db_pass){
      return TRUE;
    }else{
      $this->form_validation->set_message('old_password', "Old Password Wrong!");
      return FALSE;
    }
  }

  public function update_password($password)
  {
    $user_id = $this->session->userdata('logged_in')['user_id'];
    $table   = 'app_user';
    $where   = array('user_id' => $user_id);
    $data    = array('password' => $password);
    $exec    = $this->mcore->update($table, $where, $data);

    if($exec){
      return TRUE;
    }else{
      return FALSE;
    }
  }
}