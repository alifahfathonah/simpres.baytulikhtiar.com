<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parameter extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    if(!$this->session->userdata('logged_in')) redirect('login');
    $this->load->model('model_setup');
    $this->load->model('model_karyawan');
    $this->load->model('M_core', 'mcore');
  }

  public function list_parameter()
  {
    $param = $this->input->get('param');
    $table = 'app_parameter';
    $where = array('parameter_group' => $param);
    $arr   = $this->mcore->get_where($table, $where)->result();

    $render = array();
    foreach ($arr as $key) {
      $nest_render = array(
        $key->parameter_group,
        $key->parameter_id,
        $key->description,
        '<div class="text-center"><a href="'.site_url('edit_parameter/'.$key->parameter_group.'/'.$key->parameter_id).'" class="btn btn-info btn-xs">Edit</a>'.'<button onClick="destroy('.$key->parameter_id.', \''.$key->parameter_group.'\', \''.$key->description.'\');" class="btn btn-danger btn-xs">Delete</button></div>',
      );

      array_push($render, $nest_render);
    }
    
    echo json_encode($render);

  }

  public function edit_parameter($group, $id)
  {
    $table                          = 'app_parameter';
    $where                          = array('parameter_group' => $group, 'parameter_id' => $id);
    $data['arr']                    = $this->mcore->get_where($table, $where);
    
    $session_data                   = $this->session->userdata('logged_in');
    $data['username']               = $session_data['username'];
    $data['user']                   = $session_data['fullname'];
    $data['role_id']                = $session_data['role_id'];
    
    $data['periode_cutoff']         = $this->model_setup->get_cutoff();
    $data['get_kategori_parameter'] = $this->model_setup->get_kategori_parameter();
    $data['container']              = 'setup/edit_parameter';

    $this->load->view('core', $data);
  }

  public function update_parameter()
  {
    $id         = $this->input->post('id');
    $prev_group = $this->input->post('prev_group');
    $group      = $this->input->post('parameter_group');
    $nama       = $this->input->post('description');
    $table      = 'app_parameter';

    $data = array(
      'parameter_group' => $group,
      'description' => $nama
    );

    $where = array('parameter_id' => $prev_group, 'parameter_id' => $id);

    $exec = $this->mcore->update($table, $where, $data);

    if($exec === true){
      $return = array(
        'code'         => '200',
        'description'  => 'Proses update parameter berhasil'
      );
    }else{
      $return = array(
        'code'         => '400',
        'description'  => 'Proses update parameter gagal, silahkan coba kembali'
      );
    }

    echo json_encode($return);
  }

  public function destroy_parameter()
  {
    $id    = $this->input->post('id');
    $group = $this->input->post('group');
    $desc  = $this->input->post('desc');
    
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

}

/* End of file Parameter.php */
/* Location: ./application/controllers/Parameter.php */