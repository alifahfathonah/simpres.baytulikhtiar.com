<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_karyawanless extends CI_Model {

  var $table = 'app_karyawan a'; 
  
  //set column field database for datatable orderable 
  var $column_order = array(
    null, 
    'a.nik',
    'a.fullname',
    'c.description',
    'd.description',
    'e.description'
  );

  //set column field database for datatable searchable 
  var $column_search = array(
    'a.nik',
    'a.fullname',
    'c.description',
    'd.description',
    'e.description'
  );

  // default order 
  var $order = array('a.fullname' => 'asc');

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  private function _get_datatables_query()
  {
    $this->db->select('
      a.karyawan_id,
      a.nik,
      a.fullname,
      c.description AS status,
      d.description AS position,
      e.description AS cabang
    ');
    $this->db->from($this->table);
    $this->db->join('app_karyawan_detail b', 'b.nik = a.nik', 'left');
    $this->db->join('app_parameter c', 'c.parameter_group = \'status\' AND c.parameter_id = b.status', 'left');
    $this->db->join('app_parameter d', 'd.parameter_group = \'jabatan\' AND d.parameter_id = b.thru_position', 'left');
    $this->db->join('app_parameter e', 'e.parameter_group = \'cabang\' AND e.parameter_id = b.thru_branch', 'left');

    if($this->session->userdata('logged_in')['role_id'] > 0){
    	$wherex = ['b.thru_branch' => $this->session->userdata('logged_in')['branch_code']];
  		$this->db->where($wherex);
  	}

  	$this->db->where('b.resign', NULL);

    $this->db->group_by('
      a.karyawan_id,
      a.nik,
      a.fullname,
      c.description,
      d.description,
      e.description,
    ');

    $i = 0;

    // loop column 
    foreach ($this->column_search as $item) 
    {
      // if datatable send POST for search
      if($_POST['search']['value'])
      {
        // first loop
        if($i===0) 
        {
          // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->group_start(); 
          $this->db->where($item." ILIKE '%".$_POST['search']['value']."%'");
        }
        else
        {
          $this->db->or_where($item." ILIKE '%".$_POST['search']['value']."%'");
        }

        //last loop
        if(count($this->column_search) - 1 == $i) 
          //close bracket
          $this->db->group_end(); 
      }
      $i++;
    }

    // here order processing
    if(isset($_POST['order'])) 
    {
      $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->order))
    {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function get_datatables()
  {
    $this->_get_datatables_query();

    if($_POST['length'] != -1){
      $this->db->limit($_POST['length'], $_POST['start']);
    }

    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered()
  {
    $this->_get_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all()
  {
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

}

/* End of file M_karyawanless.php */
/* Location: ./application/models/M_karyawanless.php */