<?php 
if(!defined('BASEPATH')) exit('No script access allowed');

class Karyawan extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    if(!$this->session->userdata('logged_in')) redirect('login');    
    $this->load->model('model_setup');
    $this->load->model('model_karyawan');
    $this->load->model('M_core', 'mcore');
    $this->load->model('M_karyawanless','mkl');
    $hak_cuti = 6;
  }

  function index()
  {
    $session_data     = $this->session->userdata('logged_in');
    $data['username'] = $session_data['username'];
    $data['user']     = $session_data['fullname'];
    $data['role_id']  = $session_data['role_id'];
    $branch_user      = $session_data['branch_code'];
    
    if($data['role_id'] == '0'){
      $data['get_karyawan'] = $this->mcore->get_list_karyawan();
    }else{
      $data['get_karyawan'] = $this->mcore->get_karyawan_by_branch($branch_user);
    }

    $data['periode_cutoff'] = $this->model_setup->get_cutoff();
    $data['get_position']   = $this->model_karyawan->get_position();
    $data['get_branch']     = $this->model_karyawan->get_branch();
    $data['container']      = 'karyawan/data_karyawan3';

    $this->load->view('core', $data);
  }

  public function data()
  {
    $list = $this->mkl->get_datatables();
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $karyawan) {
      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $karyawan->nik;
      $row[] = $karyawan->fullname;
      $row[] = $karyawan->position;
      $row[] = $karyawan->cabang;
      $row[] = $karyawan->status;

      if($this->session->userdata('logged_in')['role_id'] > 0){
      	$row[] = '
	      <div class="btn-group">
	        <button type="button" class="btn purple-seance btn-sm" onClick="detail(\''.$karyawan->karyawan_id.'\')"><i class="fa fa-search"></i> View Detail</button>
	      </div>
	      ';
      }else{
      	$row[] = '
	      <div class="btn-group">
	        <button type="button" class="btn purple-seance btn-sm" onClick="detail(\''.$karyawan->karyawan_id.'\')"><i class="fa fa-search"></i> View Detail</button>
	        <a href="'.site_url('edit_karyawan/'.$karyawan->karyawan_id).'" class="btn btn-primary btn-sm">
	          <i class="fa fa-pencil"></i> Edit
	        </a>
	        <button class="btn btn-danger btn-sm" onClick="destroy(\''.$karyawan->nik.'\', \''.$karyawan->fullname.'\');">
	          <i class="fa fa-trash"></i> Delete
	        </button>
	      </div>
	      ';	
      }
      

      $data[] = $row;
    }

    $output = array(
      "draw"            => $_POST['draw'],
      "recordsTotal"    => $this->mkl->count_all(),
      "recordsFiltered" => $this->mkl->count_filtered(),
      "data"            => $data,
      "last_query"      => $this->db->last_query(),
    );
    
    //output to json format
    echo json_encode($output);
  }

  public function detail_karyawan($karyawan_id)
  {
    $table                  = 'app_karyawan';
    $where                  = array('karyawan_id' => $karyawan_id);
    $join_table             = 'app_pendidikan';
    $join_condition         = 'app_pendidikan.nik = app_karyawan.nik';
    $join_type              = 'left';
    $data['arr_karyawan']   = $this->mcore->get_karyawan_by_id($karyawan_id)->result_array();
    return $this->load->view('karyawan/detail', $data, FALSE);
  }

  function datatable_get_karyawan(){
    $aColumns = array(
      '',
      'branch_code',
      'branch_name',
      'branch_class',
      'branch_officer_name',
      'display_text',
      ''
    );

    $sLimit = '';

    if(isset( $_GET['iDisplayStart'] ) and $_GET['iDisplayLength'] != '-1'){
      $sLimit = " OFFSET ".intval($_GET['iDisplayStart'])." LIMIT ".
      intval($_GET['iDisplayLength']);
    }

    $sOrder = '';
    if(isset($_GET['iSortCol_0'])){
      $sOrder = "ORDER BY  ";

      for($i = 0; $i < intval($_GET['iSortingCols']); $i++){
        if($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == 'true'){
          $sOrder .= "".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]." ".($_GET['sSortDir_'.$i] === 'asc' ? 'asc' : 'desc') .", ";
        }
      }

      $sOrder = substr_replace($sOrder,"",-2);
      if($sOrder == "ORDER BY"){
        $sOrder = "";
      }
    }

    $sWhere = "";

    if(isset($_GET['sSearch']) and $_GET['sSearch'] != ''){
      $sWhere = "AND (";

      for($i = 0; $i < count($aColumns); $i++){
        if($aColumns[$i] != ''){
          $sWhere .= "LOWER(CAST(".$aColumns[$i]." AS VARCHAR)) LIKE '%".strtolower($_GET['sSearch'])."%' OR ";
        }
      }

      $sWhere = substr_replace($sWhere,"",-3);
      $sWhere .= ')';
    }

    for($i = 0; $i < count($aColumns); $i++){
      if($aColumns[$i] != ''){
        if(isset($_GET['bSearchable_'.$i]) and $_GET['bSearchable_'.$i] == 'true' and $_GET['sSearch_'.$i] != ''){
          if($sWhere == ''){
            $sWhere = "WHERE ";
          } else {
            $sWhere .= " AND ";
          }

          $sWhere .= "LOWER(CAST(".$aColumns[$i]." AS VARCHAR)) LIKE '%".strtolower($_GET['sSearch_'.$i])."%' ";
        }
      }
    }

    $rResult            = $this->model_cif->datatable_kantor_cabang_setup($sWhere,$sOrder,$sLimit);
    $rResultFilterTotal = $this->model_cif->datatable_kantor_cabang_setup($sWhere,'','');
    $iFilteredTotal     = count($rResultFilterTotal); 
    $rResultTotal       = $this->model_cif->datatable_kantor_cabang_setup('','','');
    $iTotal             = count($rResultTotal); 

    $output = array(
      "sEcho"                => intval($_GET['sEcho']),
      "iTotalRecords"        => $iTotal,
      "iTotalDisplayRecords" => $iFilteredTotal,
      "aaData"               => array()
    );

    foreach($rResult as $aRow){
      $row = array();

      if($aRow['branch_class'] == '0'){
        $jenis = 'Pusat';
      } else if($aRow['branch_class'] == '1'){
        $jenis = 'Wilayah';
      } else if($aRow['branch_class'] == '2'){
        $jenis = 'Cabang';
      } else{
        $jenis = 'Unit';
      }

      $row[] = '<input type="checkbox" value="'.$aRow['branch_id'].'" id="checkbox" class="checkboxes">';
      $row[] = $aRow['branch_code'];
      $row[] = $aRow['branch_name'];
      $row[] = $jenis;
      $row[] = $aRow['branch_officer_name'];
      $row[] = $aRow['display_text'];
      $row[] = '<center><a href="javascript:;" branch_id="'.$aRow['branch_id'].'" class="btn mini blue" id="link-edit">Edit</a></center>';

      $output['aaData'][] = $row;
    }

    echo json_encode( $output );
  }

  function get_karyawan(){
    $aColumns = array(
      '',
      'branch_code',
      'branch_name',
      'branch_class',
      'branch_officer_name',
      'display_text',
      ''
    );

    $sLimit = '';
    if(isset( $_GET['iDisplayStart'] ) and $_GET['iDisplayLength'] != '-1'){
      $sLimit = " OFFSET ".intval($_GET['iDisplayStart'])." LIMIT ".
      intval($_GET['iDisplayLength']);
    }

    $sOrder = '';
    if(isset($_GET['iSortCol_0'])){
      $sOrder = "ORDER BY  ";

      for($i = 0; $i < intval($_GET['iSortingCols']); $i++){
        if($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == 'true'){
          $sOrder .= "".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]." ".($_GET['sSortDir_'.$i] === 'asc' ? 'asc' : 'desc') .", ";
        }
      }

      $sOrder = substr_replace($sOrder,"",-2);
      if($sOrder == "ORDER BY"){
        $sOrder = "";
      }
    }

    $sWhere = "";

    if(isset($_GET['sSearch']) and $_GET['sSearch'] != ''){
      $sWhere = "AND (";

      for($i = 0; $i < count($aColumns); $i++){
        if($aColumns[$i] != ''){
          $sWhere .= "LOWER(CAST(".$aColumns[$i]." AS VARCHAR)) LIKE '%".strtolower($_GET['sSearch'])."%' OR ";
        }
      }

      $sWhere = substr_replace($sWhere,"",-3);
      $sWhere .= ')';
    }

    for($i = 0; $i < count($aColumns); $i++){
      if($aColumns[$i] != ''){
        if(isset($_GET['bSearchable_'.$i]) and $_GET['bSearchable_'.$i] == 'true' and $_GET['sSearch_'.$i] != ''){
          if($sWhere == ''){
            $sWhere = "WHERE ";
          } else {
            $sWhere .= " AND ";
          }

          $sWhere .= "LOWER(CAST(".$aColumns[$i]." AS VARCHAR)) LIKE '%".strtolower($_GET['sSearch_'.$i])."%' ";
        }
      }
    }

    $rResult            = $this->model_cif->datatable_kantor_cabang_setup($sWhere,$sOrder,$sLimit);
    $rResultFilterTotal = $this->model_cif->datatable_kantor_cabang_setup($sWhere,'','');
    $iFilteredTotal     = count($rResultFilterTotal); 
    $rResultTotal       = $this->model_cif->datatable_kantor_cabang_setup('','','');
    $iTotal             = count($rResultTotal); 

    $output = array(
      "sEcho"                => intval($_GET['sEcho']),
      "iTotalRecords"        => $iTotal,
      "iTotalDisplayRecords" => $iFilteredTotal,
      "aaData"               => array()
    );

    foreach($rResult as $aRow){
      $row = array();

      if($aRow['branch_class'] == '0'){
        $jenis = 'Pusat';
      } else if($aRow['branch_class'] == '1'){
        $jenis = 'Wilayah';
      } else if($aRow['branch_class'] == '2'){
        $jenis = 'Cabang';
      } else{
        $jenis = 'Unit';
      }

      $row[] = '<input type="checkbox" value="'.$aRow['branch_id'].'" id="checkbox" class="checkboxes">';
      $row[] = $aRow['branch_code'];
      $row[] = $aRow['branch_name'];
      $row[] = $jenis;
      $row[] = $aRow['branch_officer_name'];
      $row[] = $aRow['display_text'];
      $row[] = '<center><a href="javascript:;" branch_id="'.$aRow['branch_id'].'" class="btn mini blue" id="link-edit">Edit</a></center>';

      $output['aaData'][] = $row;
    }

    echo json_encode( $output );
  }

  function get_karyawan_(){
    $get_count_karyawan = $this->model_karyawan->get_count_karyawan();
    $get_karyawan       = $this->model_karyawan->get_karyawan();
    $iTotalRecords      = $get_count_karyawan;
    $iDisplayLength     = intval($_REQUEST['length']);
    $iDisplayLength     = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
    $iDisplayStart      = intval($_REQUEST['start']);
    $sEcho              = intval($_REQUEST['draw']);
    $records            = array();
    $records["data"]    = array(); 
    $end                = $iDisplayStart + $iDisplayLength;
    $end                = $end > $iTotalRecords ? $iTotalRecords : $end;

    $status_list = array(
      array("success" => "Pending"),
      array("info"    => "Closed"),
      array("danger"  => "On Hold"),
      array("warning" => "Fraud")
    );

    foreach($get_karyawan as $key=>$values)
    {
      $karyawan_id[$key] = $values->karyawan_id;
      $nik[$key]         = $values->nik;
      $nama[$key]        = $values->fullname;
      $position[$key]    = $values->thru_position;
      $branch[$key]      = $values->thru_branch;
      $status[$key]      = $values->post_status;
    }

    for($i = $iDisplayStart; $i < $end; $i++) {
      $id = ($i + 1);
      if($status[$i] == '0'){
        $post_status = "Karyawan Tetap"; 
        $color = "success";
      }elseif($status[$i] == '1'){
        $post_status = "Karyawan Kontrak"; 
        $color = "info";
      }else if($status[$i] == '2'){
        $post_status = "Karyawan Training"; 
        $color = "danger";
      }else if($status[$i] == '3'){
        $post_status = "Magang"; 
        $color = "warning";
      }

      $records["data"][] = array(
        '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="'.$id.'"/><span></span></label>',
        ''.$nik[$i].'',
        ''.$nama[$i].'',
        ''.$position[$i].'',
        ''.$branch[$i].'',
        '<span class="label label-sm label-success">'.$status[$i].'</span>',
        '<a href="'.site_url().'karyawan/get_karyawan_by_nik/'.$nik[$i].'" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> View</a>',
      );
    }

    if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
    $records["customActionStatus"]  = "OK"; // pass custom message(useful for getting status of group actions)
    $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
  }

  $records["draw"]            = $sEcho;
  $records["recordsTotal"]    = $iTotalRecords;
  $records["recordsFiltered"] = $iTotalRecords;

  echo json_encode($records);
}

public function get_karyawan_by_nik()
{
  $nik                         = $this->uri->segment(3);
  $data['get_karyawan_by_nik'] = $this->model_karyawan->get_karyawan_by_nik($nik);
  $session_data                = $this->session->userdata('logged_in');
  $data['username']            = $session_data['username'];
  $data['user']                = $session_data['fullname'];
  $data['role_id']             = $session_data['role_id'];
  $data['periode_cutoff']      = $this->model_setup->get_cutoff();
  $data['get_branch']          = $this->model_karyawan->get_branch();
  $data['get_position']        = $this->model_karyawan->get_position();
  $data['container']           = 'karyawan/karyawan_detail';

  $this->load->view('core', $data);   
}

function action_update_karyawan()
{
  $nik            = $this->input->post('nik');
  $date1          = $this->input->post('tgl_lahir');
  $date2          = date('Y-m-d');
  $datetime1      = new DateTime($date1);
  $datetime2      = new DateTime($date2);
  $difference     = $datetime1->diff($datetime2);
  $hari           = $difference->days;
  $umur           = $hari / 365;
  $tgl_gabung     = $this->input->post('tgl_gabung');
  $from_training  = $this->input->post('from_training');
  $thru_training  = $this->input->post('thru_training');
  $thru_kontrak_1 = date('Y-m-d', strtotime('+1 years', strtotime($thru_training))); 

  if($this->input->post('from_kontrak_1') != ""){
    $from_kontrak_1 = $this->input->post('from_kontrak_1');
    $from_kontrak_1 = str_replace('/', '', $from_kontrak_1);
    $from_kontrak_1 = substr($from_kontrak_1,4,4).'-'.substr($from_kontrak_1,0,2).'-'.substr($from_kontrak_1,2,2);
  }else{
    $from_kontrak_1 = null;
  }

  if($this->input->post('tgl_re_status') != ""){
    $tgl_re_status = $this->input->post('tgl_re_status');
    $tgl_re_status = str_replace('/', '', $tgl_re_status);
    $tgl_re_status = substr($tgl_re_status,4,4).'-'.substr($tgl_re_status,0,2).'-'.substr($tgl_re_status,2,2);
  }else{
    $tgl_re_status = null;
  }

  if($this->input->post('tgl_change_post') != ""){
    $tgl_change_post = $this->input->post('tgl_change_post');
    $tgl_change_post = str_replace('/', '', $tgl_change_post);
    $tgl_change_post = substr($tgl_change_post,4,4).'-'.substr($tgl_change_post,0,2).'-'.substr($tgl_change_post,2,2);
  }else{
    $tgl_change_post = null;
  }


  $nama_file_foto          = str_replace(' ', '_', strtoupper($this->input->post('fullname')));
  $userfile                = @$_FILES['userfile'];
  $ext                     = pathinfo(@$userfile['name'], PATHINFO_EXTENSION);
  $file_name               = $nama_file_foto.'.'.$ext;
  $path                    = realpath(APPPATH . '../assets/user');
  $config['upload_path']   = $path;
  $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
  $config['file_name']     = $file_name;
  $config['max_size']      = 5000;

  $this->load->library('upload');
  $this->upload->initialize($config);

  if($this->upload->do_upload()){
    $gbr = $this->upload->data();
    $data = array(
      'nik'             => $this->input->post('nik'),
      'no_ktp'          => $this->input->post('no_ktp'),
      'foto_karyawan'   => $file_name,
      'fullname'        => $this->input->post('fullname'),
      'tmp_lahir'       => $this->input->post('tmp_lahir'),
      'tgl_lahir'       => $this->input->post('tgl_lahir'),
      'umur'            => $umur,
      'jk'              => $this->input->post('jk'),
      'alamat'          => $this->input->post('alamat'),
      'no_hp'           => $this->input->post('no_hp'),
      'from_pernikahan' => $this->input->post('from_pernikahan')
    );

    $datas = array(
      'nik'               => $this->input->post('nik'),
      'tgl_masuk'         => $tgl_gabung,
      'thru_position'     => $this->input->post('from_position'),
      'from_branch'       => $this->input->post('from_branch'),
      'thru_branch'       => $this->input->post('from_branch'),
      'periode_training'  => $from_training.' s/d '.$thru_training,
      'periode_kontrak_1' => $thru_training.' s/d '.$thru_kontrak_1
    );

    $datass = array(
      'nik'        => $this->input->post('nik'),
      'sd'         => $this->input->post('sd'),
      'smp'        => $this->input->post('smp'),
      'sma'        => $this->input->post('sma'),
      'diploma'    => $this->input->post('diploma'),
      'sarjana'    => $this->input->post('sarjana'),
      'sertifikat' => $this->input->post('sertifikat'),
      'lainnya'    => $this->input->post('lainnya')
    );
  }else{
    $data = array(
      'nik'             => $this->input->post('nik'),
      'no_ktp'          => $this->input->post('no_ktp'),
      'foto_karyawan'   => 's',
      'fullname'        => $this->input->post('fullname'),
      'tmp_lahir'       => $this->input->post('tmp_lahir'),
      'tgl_lahir'       => $this->input->post('tgl_lahir'),
      'umur'            => $umur,
      'jk'              => $this->input->post('jk'),
      'alamat'          => $this->input->post('alamat'),
      'no_hp'           => $this->input->post('no_hp'),
      'from_pernikahan' => $this->input->post('from_pernikahan')
    ); 

    $datas = array(
      'nik'               => $this->input->post('nik'),
      'tgl_masuk'         => $tgl_gabung,
      'thru_position'     => $this->input->post('from_position'),
      'from_branch'       => $this->input->post('from_branch'),
      'thru_branch'       => $this->input->post('from_branch'),
      'periode_training'  => $from_training.' s/d '.$thru_training,
      'periode_kontrak_1' => $thru_training.' s/d '.$thru_kontrak_1
    );

    $datass = array(
      'nik'        => $this->input->post('nik'),
      'sd'         => $this->input->post('sd'),
      'smp'        => $this->input->post('smp'),
      'sma'        => $this->input->post('sma'),
      'diploma'    => $this->input->post('diploma'),
      'sarjana'    => $this->input->post('sarjana'),
      'sertifikat' => $this->input->post('sertifikat'),
      'lainnya'    => $this->input->post('lainnya')
    );       
  }

  $this->db->trans_begin();
  $this->model_karyawan->action_update_karyawan($data, $nik);
  $this->model_karyawan->action_update_karyawan_detail($datas, $nik);
  $this->model_karyawan->action_update_pendidikan($datass, $nik);

  if($this->db->trans_status()===true){
    $this->db->trans_commit();
    redirect('karyawan');
  }else{
    $this->db->trans_rollback();
    redirect('karyawan');
  }

  echo json_encode($return);
}

function add_karyawan()
{
  $session_data           = $this->session->userdata('logged_in');
  $data['username']       = $session_data['username'];
  $data['user']           = $session_data['fullname'];
  $data['role_id']        = $session_data['role_id'];

  $data['periode_cutoff'] = $this->model_setup->get_cutoff();
  $data['get_branch']     = $this->model_karyawan->get_branch();
  $data['get_position']   = $this->model_karyawan->get_position();
  $data['get_status']     = $this->model_karyawan->get_status_mutasi();
  $data['container']      = 'karyawan/add_karyawan';

  $this->load->view('core', $data);   
}

function edit_karyawan($karyawan_id)
{
  $session_data           = $this->session->userdata('logged_in');
  $data['username']       = $session_data['username'];
  $data['user']           = $session_data['fullname'];
  $data['role_id']        = $session_data['role_id'];

  $table                  = 'app_karyawan';
  $where                  = array('karyawan_id' => $karyawan_id);
  $join_table             = 'app_pendidikan';
  $join_condition         = 'app_pendidikan.nik = app_karyawan.nik';
  $join_type              = 'left';
  $data['arr_karyawan']   = $this->mcore->get_karyawan_by_id($karyawan_id);
  $data['periode_cutoff'] = $this->model_setup->get_cutoff();
  $data['get_branch']     = $this->model_karyawan->get_branch();
  $data['get_position']   = $this->model_karyawan->get_position();
  $data['get_status']     = $this->model_karyawan->get_status();
  $data['container']      = 'karyawan/edit_karyawan';

  $this->load->view('core', $data);   
}

function action_add_karyawan()
{
  $session_data      = $this->session->userdata('logged_in');
  $user              = $session_data['fullname'];
  $nik               = $this->input->post('nik');
  $date1             = $this->input->post('tgl_lahir');
  $date1             = str_replace('/', '', $date1);
  $date1             = substr($date1,4,4).'-'.substr($date1,2,2).'-'.substr($date1,0,2);

  $date2             = date('Y-m-d');
  $datetime1         = strtotime($date1);
  $datetime2         = strtotime($date2);
  $difference        = $datetime1 - $datetime2;
  $hari              = $difference->days;
  $umur              = $hari / 365;

  $tgl_lahir         = $this->input->post('tgl_lahir');
  $tgl_lahir         = str_replace('/', '', $tgl_lahir);
  $tgl_lahir         = substr($tgl_lahir,4,4).'-'.substr($tgl_lahir,0,2).'-'.substr($tgl_lahir,2,2);

  $tgl_gabung        = $this->input->post('tgl_gabung');
  $tgl_gabung        = str_replace('/', '', $tgl_gabung);
  $tgl_gabung        = substr($tgl_gabung,4,4).'-'.substr($tgl_gabung,0,2).'-'.substr($tgl_gabung,2,2);

  $from_date_periode = $this->input->post('from_date_periode');
  $from_date_periode = str_replace('/', '', $from_date_periode);
  $from_date_periode = substr($from_date_periode,4,4).'-'.substr($from_date_periode,0,2).'-'.substr($from_date_periode,2,2);

  $thru_date_periode = $this->input->post('thru_date_periode');
  $thru_date_periode = str_replace('/', '', $thru_date_periode);
  $thru_date_periode = substr($thru_date_periode,4,4).'-'.substr($thru_date_periode,0,2).'-'.substr($thru_date_periode,2,2);

  if($this->input->post('from_kontrak_1') != ""){
    $from_kontrak_1 = $this->input->post('from_kontrak_1');
    $from_kontrak_1 = str_replace('/', '', $from_kontrak_1);
    $from_kontrak_1 = substr($from_kontrak_1,4,4).'-'.substr($from_kontrak_1,0,2).'-'.substr($from_kontrak_1,2,2);
  }else{
    $from_kontrak_1 = null;
  }

  if($this->input->post('tgl_re_status') != ""){
    $tgl_re_status = $this->input->post('tgl_re_status');
    $tgl_re_status = str_replace('/', '', $tgl_re_status);
    $tgl_re_status = substr($tgl_re_status,4,4).'-'.substr($tgl_re_status,0,2).'-'.substr($tgl_re_status,2,2);
  }else{
    $tgl_re_status = null;
  }

  if($this->input->post('tgl_change_post') != ""){
    $tgl_change_post = $this->input->post('tgl_change_post');
    $tgl_change_post = str_replace('/', '', $tgl_change_post);
    $tgl_change_post = substr($tgl_change_post,4,4).'-'.substr($tgl_change_post,0,2).'-'.substr($tgl_change_post,2,2);
  }else{
    $tgl_change_post = null;
  }

  $periode_cutoff    = $this->model_setup->get_cutoff();
  $get_count_periode = $this->model_setup->get_count_periode();

  if($get_count_periode == '0'){
    echo "<script>alert('Periode Cutoff Belum Terisi!!');history.go(-1)</script>";   
    die(); 
  }

  foreach($periode_cutoff as $value)
  {
    $from_date = $value->from_date; $thru_date = $value->thru_date;
    $from_date_ = $value->from_date; $thru_date_ = $value->thru_date;
  }


  $check_existing_absensi = $this->model_karyawan->check_existing_absensi($nik,$from_date_);
  $jumlah                 = $check_existing_absensi['jumlah'];

  if($jumlah == '0'){
    while(strtotime($from_date) <= strtotime($thru_date)){
      if(date('l', strtotime($from_date)) != 'Saturday' && date('l', strtotime($from_date)) != 'Sunday')
      {
        $get_libur_by_date = $this->model_karyawan->get_libur_by_date($from_date);

        if(is_null($get_libur_by_date['tanggal'])){
          $data = array(
            'nik'               =>  $nik,
            'masuk'             => '',
            'keluar'            => '',
            'tanggal'           => $from_date,
            'keterangan'        => '',
            'periode_from_date' => $from_date_,
            'periode_thru_date' => $thru_date_,
            'created_date'      => date('Y-m-d H:i:s'),
            'created_by'        => $user
          );

          $update_absensi_manual = $this->model_karyawan->update_absensi_manual($data); 
        }else{
          $data = array(
            'nik'               =>  $nik,
            'masuk'             => '',
            'keluar'            => '',
            'tanggal'           => $from_date,
            'keterangan'        => $get_libur_by_date['description'],
            'periode_from_date' => $from_date_,
            'periode_thru_date' => $thru_date_,
            'created_date'      => date('Y-m-d H:i:s'),
            'created_by'        => $user
          );

          $update_absensi_manual = $this->model_karyawan->update_absensi_manual($data); 
        }
      }

      $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date))); 
    }
  }

  $path                    = realpath(APPPATH . '../assets/user');
  $location                = './assets/user/';
  $config['upload_path']   = $path;
  $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; 
  $config['max_size']      = '2048';
  $userfile                = @$_FILES['userfile'];

  $this->load->library('upload',$config);
  $this->upload->initialize($config);

  if($this->upload->do_upload()){
    $gbr = $this->upload->data();
    $data = array(
      'nik'             => $nik,
      'no_ktp'          => $this->input->post('no_ktp'),
      'foto_karyawan'   => $userfile['name'],
      'fullname'        => $this->input->post('fullname'),
      'tmp_lahir'       => $this->input->post('tmp_lahir'),
      'tgl_lahir'       => $tgl_lahir,
      'umur'            => $umur,
      'jk'              => $this->input->post('jk'),
      'alamat'          => $this->input->post('alamat'),
      'no_hp'           => $this->input->post('no_hp'),
      'from_pernikahan' => $this->input->post('from_pernikahan')
    );

    $datas = array(
      'nik'               => $nik,
      'tgl_masuk'         => $tgl_gabung,
      'status'            => $this->input->post('status'),
      'from_position'     => $this->input->post('from_position'),
      'thru_position'     => $this->input->post('from_position'),
      'from_branch'       => $this->input->post('from_branch'),
      'thru_branch'       => $this->input->post('from_branch'),
      'periode_training'  => "",
      'periode_kontrak_1' => "",
      'hak_cuti'          => "6",
      'hak_ijin'          => "6"
    );

    $datass = array(
      'nik'        => $nik,
      'sd'         => $this->input->post('sd'),
      'smp'        => $this->input->post('smp'),
      'sma'        => $this->input->post('sma'),
      'diploma'    => $this->input->post('diploma'),
      'sarjana'    => $this->input->post('sarjana'),
      'sertifikat' => $this->input->post('sertifikat'),
      'lainnya'    => $this->input->post('lainnya')
    );

    $data_ = array(
      'nik'          => $nik,
      'thru_status'  => $this->input->post('status'),
      'from_date'    => $from_date_periode,
      'thru_date'    => $thru_date_periode,
      'created_date' => date('Y-m-d H:i:s'),
      'created_by'   => $user
    );

  }else{
    $data = array(
      'nik'             => $nik,
      'no_ktp'          => $this->input->post('no_ktp'),
      'foto_karyawan'   => '',
      'fullname'        => $this->input->post('fullname'),
      'tmp_lahir'       => $this->input->post('tmp_lahir'),
      'tgl_lahir'       => $tgl_lahir,
      'umur'            => $umur,
      'jk'              => $this->input->post('jk'),
      'alamat'          => $this->input->post('alamat'),
      'no_hp'           => $this->input->post('no_hp'),
      'from_pernikahan' => $this->input->post('from_pernikahan')
    ); 

    $datas = array(
      'nik'               => $nik,
      'tgl_masuk'         => $tgl_gabung,
      'status'            => $this->input->post('status'),
      'from_position'     => $this->input->post('from_position'),
      'thru_position'     => $this->input->post('from_position'),
      'from_branch'       => $this->input->post('from_branch'),
      'thru_branch'       => $this->input->post('from_branch'),
      'periode_training'  => "",
      'periode_kontrak_1' => "",
      'hak_cuti'          => "6",
      'hak_ijin'          => "6"
    );

    $datass = array(
      'nik'        => $nik,
      'sd'         => $this->input->post('sd'),
      'smp'        => $this->input->post('smp'),
      'sma'        => $this->input->post('sma'),
      'diploma'    => $this->input->post('diploma'),
      'sarjana'    => $this->input->post('sarjana'),
      'sertifikat' => $this->input->post('sertifikat'),
      'lainnya'    => $this->input->post('lainnya')
    );       

    $data_ = array(
      'nik'          => $nik,
      'thru_status'  => $this->input->post('status'),
      'from_date'    => $from_date_periode,
      'thru_date'    => $thru_date_periode,
      'created_date' => date('Y-m-d H:i:s'),
      'created_by'   => $user
    );
  }

  $check_existing_staff = $this->model_karyawan->check_existing_staff($nik);
  $jumlah_staff         = $check_existing_staff['jumlah'];

  $this->db->trans_begin();

  if($jumlah_staff == '0'){
    $this->model_karyawan->action_add_karyawan($data);
    $this->model_karyawan->action_add_karyawan_detail($datas);
    $this->model_karyawan->action_add_karyawan_periode($data_);
    $this->model_karyawan->action_add_pendidikan($datass);
  } else {
    @unlink($location.$userfile['name']);
  }

  if($this->db->trans_status()===true){
    $this->db->trans_commit();
    redirect('karyawan');
  }else{
    $this->db->trans_rollback();
    redirect('karyawan');
  }

  echo json_encode($return);
}

function action_update_status_karyawan()
{
  $nik               = $this->input->post('nik');
  $status            = $this->input->post('status');
  $periode           = $this->input->post('periode');
  $periode_kontrak_1 = '';
  $periode_kontrak_2 = '';

  $from_periode      = $this->input->post('periode');
  $from_periode      = str_replace('/', '', $from_periode);
  $from_periode      = substr($from_periode,0,4).'-'.substr($from_periode,5,2).'-'.substr($from_periode,8,2);

  $thru_periode      = $this->input->post('periode');
  $thru_periode      = str_replace('/', '', $thru_periode);
  $thru_periode      = substr($thru_periode,14,4).'-'.substr($thru_periode,19,2).'-'.substr($thru_periode,22,2);

  $last_periode      = date('Y-m-d', strtotime('+1 years', strtotime($thru_periode))); 

  if($status == '2'){
    $periode_kontrak_1 = $thru_periode." s/d ".$last_periode;
  }elseif($status == '5'){
    $periode_kontrak_2 = $thru_periode." s/d ".$last_periode;
  }

  if($status == '5'){
    $status = '2';
  }else{
    $status = $status;
  }

  $data = array(
    'status'            => $status,
    'periode_kontrak_1' => $periode_kontrak_1,
    'periode_kontrak_2' => $periode_kontrak_2
  );

  $this->db->trans_begin();
  $this->model_karyawan->action_update_status_karyawan($data, $nik);

  if($this->db->trans_status()===true){
    $this->db->trans_commit();
    redirect('karyawan');
  }else{
    $this->db->trans_rollback();
    redirect('karyawan');
  }
}

function regis_absent()
{
  $session_data = $this->session->userdata('logged_in');
  $data['username'] = $session_data['username'];
  $data['user'] = $session_data['fullname'];
  $branch_user = $session_data['branch_code'];
  $data['branch_user'] = $session_data['branch_code'];
  $data['role_id'] = $session_data['role_id'];

  $data['periode_cutoff'] = $this->model_setup->get_cutoff();
  $data['get_karyawan_by_branch'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);
  $data['get_branch'] = $this->model_karyawan->get_branch();
  $data['get_parameter_absent'] = $this->model_setup->get_parameter_absent();
  $data['get_absent'] = $this->model_karyawan->get_absent();
  $data['container'] = 'karyawan/regis_absent';

  $this->load->view('core', $data);     
}

function action_absent()
{
  $session_data        = $this->session->userdata('logged_in');
  $user                = $session_data['fullname'];

  $nik                 = $this->input->post('nik');
  $kategori_cuti       = $this->input->post('kategori_cuti');
  $from_date           = $this->input->post('from_date');
  $thru_date           = $this->input->post('thru_date');
  $aprove              = $this->input->post('aprove');
  $keterangan          = $this->input->post('keterangan');

  $from_date           = $from_date;
  $from_date           = str_replace('/', '', $from_date);
  $from_date           = substr($from_date,4,4).'-'.substr($from_date,0,2).'-'.substr($from_date,2,2);

  $thru_date           = $thru_date;
  $thru_date           = str_replace('/', '', $thru_date);
  $thru_date           = substr($thru_date,4,4).'-'.substr($thru_date,0,2).'-'.substr($thru_date,2,2);

  $get_hak_cuti_by_nik = $this->model_karyawan->get_hak_cuti_by_nik($nik);
  $max_date            = date("Y-m-d", strtotime("-3 day", strtotime($from_date)));

  foreach($get_hak_cuti_by_nik as $value)
  {
    $hak_ijin_ = $value->hak_ijin;
    $hak_cuti_ = $value->hak_cuti;
  }

  if($kategori_cuti == '1')
  {
    if($hak_cuti_ == '0')
    {
      echo "<script>alert('Hak cuti habis!!');history.go(-1)</script>";   
      die();           
    }

    if(date('Y-m-d') >= $max_date)
    {
      echo "<script>alert('Tanggal cuti/ijin salah!!');history.go(-1)</script>";   
      die(); 
    }    
  }
  else if($kategori_cuti == '6')
  {
    if($hak_ijin_ == '0')
    {          
      echo "<script>alert('Hak ijin habis!!');history.go(-1)</script>";   
      die(); 
    }

    if($from_date == date('Y-m-d'))
    {
      if(date('h:i:s') >= '08:00:00')
      {
        echo "<script>alert('Regis ijin maksimal hari ini jam 08:00:00!!');history.go(-1)</script>";   
        die();             
      }
    }
  }

  if($nik == $aprove)
  {
    echo "<script>alert('Approve harus oleh atasan langsung!!');history.go(-1)</script>";   
    die(); 
  }

  $n = 0;

  while(strtotime($from_date) <= strtotime($thru_date))
  {
    $day_from_date = date('N', strtotime($from_date));

    if($day_from_date == '6' || $day_from_date == '7')
    {       
      $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date))); 
    }
    else
    {
      $get_cuti_by_tanggal = $this->model_karyawan->get_cuti_by_tanggal($from_date, $nik);

      if(is_null($get_cuti_by_tanggal['keterangan']))
      {
        $data = array(
          'nik'           => $this->input->post('nik'),
          'kategori_cuti' => $kategori_cuti,
          'tgl_cuti'      => $from_date,
          'aprove_by'     => $aprove,
          'keterangan'    => $keterangan,
          'created_by'    => $user,
          'created_date'  => date('Y-m-d H:i:s')
        ); 

        $ket_cuti = $keterangan;

        $this->model_karyawan->update_cuti_absensi_manual2($nik, $from_date, $ket_cuti);
        $this->model_karyawan->action_regis_absent($data);
        $n = $n + 1;  

      }
      else
      {
        echo "<script>alert('Tanggal ".$get_cuti_by_tanggal['keterangan']." sudah terisi!!');history.go(-1)</script>";   
        die();           
      }

      $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date))); 
    }
  }

  if($kategori_cuti == '1')
  {
    $hak_cuti = $hak_cuti_ - $n;

    $update_hak_cuti_ = $this->model_karyawan->update_hak_cuti_($nik, $hak_cuti);
  }
  else if($kategori_cuti == '6')
  {
    $hak_ijin = $hak_ijin_ - $n;

    $update_hak_ijin = $this->model_karyawan->update_hak_ijin($nik, $hak_ijin);
  }

  redirect('karyawan/regis_absent');

}

function action_batal_cuti()
{
  $id = $this->uri->segment(3);
  $nik = $this->uri->segment(4);
  $kategori_cuti = $this->uri->segment(5);
  $tgl_cuti = $this->uri->segment(6);

  $delete_cuti_by_id = $this->model_karyawan->delete_cuti_by_id($id);
  $get_hak_cuti_by_nik = $this->model_karyawan->get_hak_cuti_by_nik($nik);

  foreach($get_hak_cuti_by_nik as $values)
  {
    $get_cuti = $values->hak_cuti;
    $hak_cuti = $get_cuti + 1;

    $get_ijin = $values->hak_ijin;
    $hak_ijin = $get_ijin + 1;
  }

  if($kategori_cuti == '1')
  {
    $update_hak_cuti = $this->model_karyawan->update_hak_cuti_($nik, $hak_cuti);
  }
  else if($kategori_cuti == '6')
  {
    $update_hak_ijin = $this->model_karyawan->update_hak_ijin($nik, $hak_ijin);
  }

  $update_absensi_manual = $this->model_karyawan->update_cuti_absensi_manual_($nik, $tgl_cuti);

  redirect('karyawan/regis_absent');

}

function action_batal_tlk()
{
  $id                    = $this->uri->segment(3);
  $nik                   = $this->uri->segment(4);
  $tgl_tlk               = $this->uri->segment(5);

  $delete_tlk_by_id      = $this->model_karyawan->delete_tlk_by_id($id);
  $update_absensi_manual = $this->model_karyawan->update_tlk_absensi_manual($nik, $tgl_tlk);

  redirect('karyawan/regis_tlk');

}

function action_batal_dnl()
{
  $id                    = $this->uri->segment(3);
  $nik                   = $this->uri->segment(4);
  $tgl_dnl               = $this->uri->segment(5);

  $delete_tlk_by_id      = $this->model_karyawan->delete_dnl_by_id($id);
  $update_absensi_manual = $this->model_karyawan->update_dnl_absensi_manual($nik, $tgl_dnl);

  redirect('karyawan/regis_dnl');

}

function regis_tlk()
{
  $session_data                   = $this->session->userdata('logged_in');
  $data['username']               = $session_data['username'];
  $data['user']                   = $session_data['fullname'];
  $data['role_id']                = $session_data['role_id'];
  $branch_user                    = $session_data['branch_code'];
  $data['branch_user']            = $session_data['branch_code'];

  $data['periode_cutoff']         = $this->model_setup->get_cutoff();
  $data['get_karyawan_by_branch'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);
  $data['get_branch']             = $this->model_karyawan->get_branch();
  $data['get_parameter_absent']   = $this->model_setup->get_parameter_absent();
  $data['get_tlk']                = $this->model_karyawan->get_tlk();
  $data['container']              = 'karyawan/regis_tlk';

  $this->load->view('core', $data);     
}

function action_tlk()
{
  $session_data = $this->session->userdata('logged_in');
  $user         = $session_data['fullname'];

  $nik          = $this->input->post('nik');
  $from_date    = $this->input->post('from_date');
  $thru_date    = $this->input->post('thru_date');
    //$aprove     = $this->input->post('aprove');
  $keterangan   = $this->input->post('keterangan');

  $from_date    = $from_date;
  $from_date    = str_replace('/', '', $from_date);
  $from_date    = substr($from_date,4,4).'-'.substr($from_date,0,2).'-'.substr($from_date,2,2);

  $thru_date    = $thru_date;
  $thru_date    = str_replace('/', '', $thru_date);
  $thru_date    = substr($thru_date,4,4).'-'.substr($thru_date,0,2).'-'.substr($thru_date,2,2);

  $n = 0;

  while(strtotime($from_date) <= strtotime($thru_date))
  {
    $day_from_date = date('N', strtotime($from_date));

    if($day_from_date == '6' || $day_from_date == '7')
    {       
      $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date))); 
    }
    else
    {
      $n = $n + 1;

      $data = array(
        'nik'          => $nik,
        'tgl_tlk'      => $from_date,
        'aprove_by'    => '',
        'keterangan'   => $keterangan,
        'created_by'   => $user,
        'created_date' => date('Y-m-d H:i:s')
      ); 

      $ket_cuti = "TL".$keterangan;

      $this->model_karyawan->update_cuti_absensi_manual($nik, $from_date, $ket_cuti);
      $this->model_karyawan->action_regis_tlk($data);

      $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date))); 
    }

  }

  redirect('karyawan/regis_tlk');

}

function regis_dnl()
{
  $session_data                   = $this->session->userdata('logged_in');
  $data['username']               = $session_data['username'];
  $data['user']                   = $session_data['fullname'];
  $branch_user                    = $session_data['branch_code'];
  $data['branch_user']            = $session_data['branch_code'];
  $data['role_id']                = $session_data['role_id'];

  $data['periode_cutoff']         = $this->model_setup->get_cutoff();
  $data['get_karyawan_by_branch'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);
  $data['get_branch']             = $this->model_karyawan->get_branch();
  $data['get_parameter_absent']   = $this->model_setup->get_parameter_absent();
  $data['get_dnl']                = $this->model_karyawan->get_dnl();
  $data['container']              = 'karyawan/regis_dnl';

  $this->load->view('core', $data);     
}

function action_dnl()
{
  $session_data = $this->session->userdata('logged_in');
  $user         = $session_data['fullname'];

  $nik          = $this->input->post('nik');
  $from_date    = $this->input->post('from_date');
  $thru_date    = $this->input->post('thru_date');
    //$aprove     = $this->input->post('aprove');
  $keterangan   = $this->input->post('keterangan');

  $from_date    = $from_date;
  $from_date    = str_replace('/', '', $from_date);
  $from_date    = substr($from_date,4,4).'-'.substr($from_date,0,2).'-'.substr($from_date,2,2);

  $thru_date    = $thru_date;
  $thru_date    = str_replace('/', '', $thru_date);
  $thru_date    = substr($thru_date,4,4).'-'.substr($thru_date,0,2).'-'.substr($thru_date,2,2);

  $n = 0;

  while(strtotime($from_date) <= strtotime($thru_date))
  {
    $day_from_date = date('N', strtotime($from_date));

    if($day_from_date == '6' || $day_from_date == '7')
    {       
      $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date))); 
    }
    else
    {
      $n = $n + 1;

      $data = array(
        'nik'          => $nik,
        'tgl_dnl'      => $from_date,
        'aprove_by'    => '',
        'keterangan'   => $keterangan,
        'created_by'   => $user,
        'created_date' => date('Y-m-d H:i:s')
      ); 

      $ket_cuti = "TL".$keterangan;

      $this->model_karyawan->update_cuti_absensi_manual($nik, $from_date, $ket_cuti);
      $this->model_karyawan->action_regis_dnl($data);

      $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date))); 
    }

  }

  redirect('karyawan/regis_dnl');

}

public function destroy_karyawan()
{
  $nik    = $this->input->post('nik');

  $table = 'app_karyawan';
  $where = array('nik' => $nik);
  $exec  = $this->mcore->destroy($table, $where);

  $table = 'app_karyawan_detail';
  $where = array('nik' => $nik);
  $exec  = $this->mcore->destroy($table, $where);

  $table = 'app_pendidikan';
  $where = array('nik' => $nik);
  $exec  = $this->mcore->destroy($table, $where);

  $table = 'app_pengalaman';
  $where = array('nik' => $nik);
  $exec  = $this->mcore->destroy($table, $where);

  $table = 'app_mutasi_status';
  $where = array('nik' => $nik);
  $exec  = $this->mcore->destroy($table, $where);

  $table = 'app_mutasi_jabatan';
  $where = array('nik' => $nik);
  $exec  = $this->mcore->destroy($table, $where);

  $table = 'app_mutasi_cabang';
  $where = array('nik' => $nik);
  $exec  = $this->mcore->destroy($table, $where);

  $table = 'app_karyawan_dnl';
  $where = array('nik' => $nik);
  $exec  = $this->mcore->destroy($table, $where);

  $table = 'app_alfa';
  $where = array('nik' => $nik);
  $exec  = $this->mcore->destroy($table, $where);

  $table = 'app_absensi_manual';
  $where = array('nik' => $nik);
  $exec  = $this->mcore->destroy($table, $where);

  $table = 'app_absen';
  $where = array('nik' => $nik);
  $exec  = $this->mcore->destroy($table, $where);

  $table = 'app_user';
  $where = array('nik' => $nik);
  $exec  = $this->mcore->destroy($table, $where);

  $table = 'app_karyawan_resign';
  $where = array('nik' => $nik);
  $exec  = $this->mcore->destroy($table, $where);

  if($exec === true){
    $return = array(
      'code'         => '200',
      'description'  => 'Proses delete karyawan berhasil'
    );
  }else{
    $return = array(
      'code'         => '400',
      'description'  => 'Proses delete karyawan gagal, silahkan coba kembali'
    );
  }

  echo json_encode($return);
}

public function store_karyawan()
{
  if($_FILES['userfile']['name'] != null){
      //$config['upload_path']   = realpath(APPPATH . '../assets/foto_karyawan');
    $config['upload_path']   = './assets/foto_karyawan/';
    $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
    $config['encrypt_name']  = TRUE;
    $this->load->library('upload',$config);
    $this->upload->initialize($config);

    if($this->upload->do_upload('userfile'))
    {
        // STORE APP_KARYAWAN DENGAN FOTO
      $file_name     = $this->upload->data()['file_name'];
      $tgl_lahir     = $this->convert_date_db_format($this->input->post('tgl_lahir'));

      $arr_karyawan = array(
        'nik'             => $this->input->post('nik'),
        'no_ktp'          => $this->input->post('no_ktp'),
        'foto_karyawan'   => $file_name,
        'fullname'        => $this->input->post('fullname').$file_name,
        'tmp_lahir'       => $this->input->post('tmp_lahir'),
        'tgl_lahir'       => $tgl_lahir,
        'jk'              => $this->input->post('jk'),
        'alamat'          => $this->input->post('alamat'),
        'no_hp'           => $this->input->post('no_hp'),
        'from_pernikahan' => $this->input->post('from_pernikahan')
      );

      $table = 'app_karyawan';
      $exec = $this->mcore->store($table, $arr_karyawan);
        // END STORE APP_KARYAWAN DENGAN FOTO

      if($exec === true){
        $result = array(
          'code'        => '200',
          'description' => 'Tambah Karyawan Berhasil...'
        );
      }else{
        $result = array(
          'code'        => '400',
          'description' => 'Tambah karyawan gagal, silahkan coba kembali...',
        );
      }

    }else{
      $result = array(
        'code'        => '400',
        'description' => $this->upload->display_errors(),
        'path'        => urldecode($config['upload_path'])
      );

    }

  }else{
      // STORE APP_KARYAWAN TANPA FOTO
    $tgl_lahir     = $this->convert_date_db_format($this->input->post('tgl_lahir'));

    $arr_karyawan = array(
      'nik'           => $this->input->post('nik'),
      'no_ktp'        => $this->input->post('no_ktp'),
      'fullname'      => $this->input->post('fullname'),
      'tmp_lahir'     => $this->input->post('tmp_lahir'),
      'tgl_lahir'     => $tgl_lahir,
      'jk'            => $this->input->post('jk'),
      'alamat'        => $this->input->post('alamat'),
      'no_hp'         => $this->input->post('no_hp'),
      'from_pernikahan' => $this->input->post('from_pernikahan')
    );

    $table = 'app_karyawan';
    $exec = $this->mcore->store($table, $arr_karyawan);
      // END STORE APP_KARYAWAN TANPA FOTO
  }

    ///////////////////////////////////////////////////////////////////////////////////

    // STORE APP_PENDIDIKAN
  $tgl_lahir     = $this->convert_date_db_format($this->input->post('tgl_lahir'));

  $arr_pendidikan = array(
    'nik'        => $this->input->post('nik'),
    'sd'         => $this->input->post('sd'),
    'smp'        => $this->input->post('smp'),
    'sma'        => $this->input->post('sma'),
    'diploma'    => $this->input->post('diploma'),
    'sarjana'    => $this->input->post('sarjana'),
    'sertifikat' => $this->input->post('sertifikat'),
    'lainnya'    => $this->input->post('lainnya')
  );

  $table = 'app_pendidikan';
  $exec = $this->mcore->store($table, $arr_pendidikan);
    // END STORE APP_PENDIDIKAN

    // STORE APP_KARYAWAN_DETAIL & APP_MUTASI_STATUS & APP_MUTASI_JABATAN & APP_MUTASI_CABANG
    /*  
      16-03-2019 last updated
      KODE STATUS KARYAWAN 
        10 => karyawawn training, 
        11 => perpanjang training, 
        20 => karyawan kontrak 1, 
        21 => karyawan kontrak 2, 
        22 => perpanjang kontrak 2, 
        30 => karyawan tetap,
        40 => karyawan magang,
        99 => resign 
    */

   ///////////////////////////////////////////////////////// KARYAWAN DETAIL
        $tgl_masuk = $this->convert_date_db_format($this->input->post('tgl_gabung'));

        $resign = null;

    if($this->input->post('status') == "99"){ // KARYAWAN RESIGN
      $resign = $this->convert_date_db_format($this->input->post('thru_date_periode'));
    }

    $arr_karyawan_detail = array(
      'nik'                 => $this->input->post('nik'),
      'tgl_masuk'           => $tgl_masuk,
      'status'              => $this->input->post('status'),
      'from_position'       => $this->input->post('from_position'),
      'thru_position'       => $this->input->post('from_position'),
      'from_branch'         => $this->input->post('from_branch'),
      'thru_branch'         => $this->input->post('from_branch'),
      'resign'              => $resign,
      'hak_cuti'            => $this->input->post('hak_cuti'),
      'hak_ijin'            => $this->input->post('hak_ijin')
    );

    $table = 'app_karyawan_detail';
    $exec = $this->mcore->store($table, $arr_karyawan_detail);
    ///////////////////////////////////////////////////////// END KARYAWAN DETAIL

    ////////////////////////////////////////////////////////// MUTASI STATUS
    $from_date = null;
    $thru_date = null;

    if($this->input->post('status') == "30"){ // KARYAWAN TETAP
      $from_date = $this->convert_date_db_format($this->input->post('from_date_periode'));
    }elseif($this->input->post('status') == "99"){ // KARYAWAN RESIGN
      $thru_date = $this->convert_date_db_format($this->input->post('thru_date_periode'));
    }else{
      $from_date = $this->convert_date_db_format($this->input->post('from_date_periode'));
      $thru_date = $this->convert_date_db_format($this->input->post('thru_date_periode'));
    }

    $arr_mutasi_status = array(
      'nik'          => $this->input->post('nik'),
      'from_status'  => $this->input->post('status'),
      'thru_status'  => $this->input->post('status'),
      'from_date'    => $from_date,
      'thru_date'    => $thru_date,
      'created_by'   => $this->session->userdata('logged_in')['fullname'],
      'created_date' => date('Y-m-d H:i:s')
    );

    $table = 'app_mutasi_status';
    $exec = $this->mcore->store($table, $arr_mutasi_status);
    ////////////////////////////////////////////////////////// END MUTASI STATUS
    

    ////////////////////////////////////////////////////////// MUTASI JABATAN
    $from_date = null;
    $thru_date = null;

    if($this->input->post('status') == "30"){ // KARYAWAN TETAP
      $from_date = $this->convert_date_db_format($this->input->post('from_date_periode'));
    }elseif($this->input->post('status') == "99"){ // KARYAWAN RESIGN
      $thru_date = $this->convert_date_db_format($this->input->post('thru_date_periode'));
    }else{
      $from_date = $this->convert_date_db_format($this->input->post('from_date_periode'));
      $thru_date = $this->convert_date_db_format($this->input->post('thru_date_periode'));
    }

    $arr_mutasi_jabatan = array(
      'nik'           => $this->input->post('nik'),
      'from_position' => $this->input->post('status'),
      'thru_position' => $this->input->post('status'),
      'from_date'     => $from_date,
      'thru_date'     => $thru_date,
      'tgl_mutasi'    => $from_date,
      'created_by'    => $this->session->userdata('logged_in')['fullname'],
      'created_date'  => date('Y-m-d H:i:s')
    );

    $table = 'app_mutasi_jabatan';
    $exec = $this->mcore->store($table, $arr_mutasi_jabatan);
    ////////////////////////////////////////////////////////// END MUTASI STATUS
    
    ////////////////////////////////////////////////////////// MUTASI CABANG
    $from_date = null;
    $thru_date = null;

    if($this->input->post('status') == "30"){ // KARYAWAN TETAP
      $from_date = $this->convert_date_db_format($this->input->post('from_date_periode'));
    }elseif($this->input->post('status') == "99"){ // KARYAWAN RESIGN
      $thru_date = $this->convert_date_db_format($this->input->post('thru_date_periode'));
    }else{
      $from_date = $this->convert_date_db_format($this->input->post('from_date_periode'));
      $thru_date = $this->convert_date_db_format($this->input->post('thru_date_periode'));
    }

    $arr_mutasi_cabang = array(
      'branch_id'    => $this->input->post('from_branch'),
      'nik'          => $this->input->post('nik'),
      'from_branch'  => $this->input->post('from_branch'),
      'thru_branch'  => $this->input->post('from_branch'),
      'from_date'    => $from_date,
      'thru_date'    => $from_date,
      'tgl_mutasi'   => $from_date,
      'created_by'   => $this->session->userdata('logged_in')['fullname'],
      'created_date' => date('Y-m-d H:i:s')
    );

    $table = 'app_mutasi_cabang';
    $exec = $this->mcore->store($table, $arr_mutasi_cabang);
    ////////////////////////////////////////////////////////// END MUTASI CABANG
    
    ////////////////////////////////////////////////////////// IF STATUS == RESIGN
    if($this->input->post('status') == "99"){ // KARYAWAN TETAP
      $resign = $this->convert_date_db_format($this->input->post('thru_date_periode'));
      $arr_resign = array(
        'nik'           => $this->input->post('nik'),
        'tgl_resign'    => $resign,
        'created_by'    => $this->session->userdata('logged_in')['fullname'],
        'created_date'  => date('Y-m-d H:i:s'),
        'alasan_resign' => $this->input->post('alasan_resign')
      );

      $table = 'app_karyawan_resign';
      $exec = $this->mcore->store($table, $arr_resign);
    }
    ////////////////////////////////////////////////////////// END IF STATUS == RESIGN
    
    /// INSERT ABSENSI MANUAL BY CUTOFF
    $arr_date_cutoff = $this->mcore->get_all_data('app_cutoff');
    $from_cutoff = $arr_date_cutoff->row('from_date');
    $thru_cutoff = $arr_date_cutoff->row('thru_date');

    $this->action_update_cutoff_by_nik($from_cutoff, $thru_cutoff, $this->input->post('nik'), $this->input->post('fullname'));
    /// END INSERT ABSENSI MANUAL BY CUTOFF

    if($exec === true){
      $result = array(
        'code'        => '200',
        'description' => 'Tambah Karyawan Berhasil...'
      );
    }else{
      $result = array(
        'code'        => '400',
        'description' => 'Tambah karyawan gagal, silahkan coba kembali...',
      );
    }
    
    echo json_encode($result);
  }

  function action_update_cutoff_by_nik($from_date, $thru_date, $nik, $nama)
  {
    $session_data = $this->session->userdata('logged_in');
    $user         = $session_data['fullname'];
    $from_date    = $this->convert_date_db_format($from_date);
    $thru_date    = $this->convert_date_db_format($thru_date);

    $get_count_karyawan      = $this->model_karyawan->get_count_karyawan();

    $hak_cuti = HAK_CUTI;
    $hak_ijin = HAK_IJIN;

    $from_date_ = $from_date;
    $thru_date_ = $thru_date;

    while(strtotime($from_date) <= strtotime($thru_date))
    {
      if(date('l', strtotime($from_date)) != 'Saturday' && date('l', strtotime($from_date)) != 'Sunday')
      {
        $get_libur_by_date   = $this->model_karyawan->get_libur_by_date($from_date);
        $get_cuti_by_tanggal = $this->model_karyawan->get_cuti_by_tanggal($from_date, $nik);
        $get_tlk_by_tanggal  = $this->model_karyawan->get_tlk_by_tanggal($from_date, $nik);
        $get_dnl_by_tanggal  = $this->model_karyawan->get_dnl_by_tanggal($from_date, $nik);

        $cc = $get_cuti_by_tanggal->num_rows();

        if($cc != 0){
          foreach ($get_cuti_by_tanggal->result() as $key) {
            $tgl_cutix = $key->tgl_cuti;
          }  
        }else{
          $tgl_cutix = 0;
        }
        

        if(is_null($get_libur_by_date['tanggal']))
        {
          if($tgl_cutix  == $from_date)
          {
            $data = array(
              'nik'               => $nik,
              'masuk'             => '',
              'keluar'            => '',
              'tanggal'           => $from_date,
              'keterangan'        => $get_cuti_by_tanggal['keterangan'],
              'periode_from_date' => $from_date_,
              'periode_thru_date' => $thru_date_,
              'created_date'      => date('Y-m-d H:i:s'),
              'created_by'        => $user
            );

            $update_absensi_manual = $this->model_karyawan->update_absensi_manual($data);
          }else if($get_tlk_by_tanggal['tgl_tlk'] == $from_date){
            $data = array(
              'nik'               => $nik,
              'masuk'             => '',
              'keluar'            => '',
              'tanggal'           => $from_date,
              'keterangan'        => $get_tlk_by_tanggal['keterangan'],
              'periode_from_date' => $from_date_,
              'periode_thru_date' => $thru_date_,
              'created_date'      => date('Y-m-d H:i:s'),
              'created_by'        => $user
            );

            $update_absensi_manual = $this->model_karyawan->update_absensi_manual($data);
          }else if($get_dnl_by_tanggal['tgl_dnl'] == $from_date){
            $data = array(
              'nik'               => $nik,
              'masuk'             => '',
              'keluar'            => '',
              'tanggal'           => $from_date,
              'keterangan'        => $get_dnl_by_tanggal['keterangan'],
              'periode_from_date' => $from_date_,
              'periode_thru_date' => $thru_date_,
              'created_date'      => date('Y-m-d H:i:s'),
              'created_by'        => $user
            );

            $update_absensi_manual = $this->model_karyawan->update_absensi_manual($data);                   
          }else{
            $data = array(
              'nik'               => $nik,
              'masuk'             => '',
              'keluar'            => '',
              'tanggal'           => $from_date,
              'keterangan'        => '',
              'periode_from_date' => $from_date_,
              'periode_thru_date' => $thru_date_,
              'created_date'      => date('Y-m-d H:i:s'),
              'created_by'        => $user
            );

            $update_absensi_manual = $this->model_karyawan->update_absensi_manual($data);                     
          }
        }else{
          $data = array(
           'nik'               => $nik,
           'masuk'             => '',
           'keluar'            => '',
           'tanggal'           => $from_date,
           'keterangan'        => $get_libur_by_date['description'],
           'periode_from_date' => $from_date_,
           'periode_thru_date' => $thru_date_,
           'created_date'      => date('Y-m-d H:i:s'),
           'created_by'        => $user
         );

          $update_absensi_manual = $this->model_karyawan->update_absensi_manual($data); 
        }
      } 

      $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date)));
    } // END WHILE

    return true;
  }

  public function update_karyawan()
  {
    $karyawan_id = $this->input->post('karyawan_id');
    $prev_nik = $this->input->post('prev_nik');

    if($_FILES['userfile']['name'] != null){
      //$config['upload_path']   = realpath(APPPATH . '../assets/foto_karyawan');
      $config['upload_path']   = './assets/foto_karyawan/';
      $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
      $config['encrypt_name']  = TRUE;
      $this->load->library('upload',$config);
      $this->upload->initialize($config);

      if($this->upload->do_upload('userfile'))
      {
        // STORE APP_KARYAWAN DENGAN FOTO
        $file_name     = $this->upload->data()['file_name'];
        $tgl_lahir     = $this->convert_date_db_format($this->input->post('tgl_lahir'));

        $arr_karyawan = array(
          'nik'             => $this->input->post('nik'),
          'no_ktp'          => $this->input->post('no_ktp'),
          'foto_karyawan'   => $file_name,
          'fullname'        => $this->input->post('fullname'),
          'tmp_lahir'       => $this->input->post('tmp_lahir'),
          'tgl_lahir'       => $tgl_lahir,
          'jk'              => $this->input->post('jk'),
          'alamat'          => $this->input->post('alamat'),
          'no_hp'           => $this->input->post('no_hp'),
          'from_pernikahan' => $this->input->post('from_pernikahan')
        );

        $table = 'app_karyawan';
        $where = array('nik' => $prev_nik);
        $exec = $this->mcore->update($table, $where, $arr_karyawan);
        // END STORE APP_KARYAWAN DENGAN FOTO

        if($exec === true){
          $result = array(
            'code'        => '200',
            'description' => 'Tambah Karyawan Berhasil...'
          );
        }else{
          $result = array(
            'code'        => '400',
            'description' => 'Tambah karyawan gagal, silahkan coba kembali...',
          );
        }

      }else{
        $result = array(
          'code'        => '400',
          'description' => $this->upload->display_errors(),
          'path'        => urldecode($config['upload_path'])
        );

      }

    }else{
      // STORE APP_KARYAWAN TANPA FOTO
      $tgl_lahir     = $this->convert_date_db_format($this->input->post('tgl_lahir'));

      $arr_karyawan = array(
        'nik'           => $this->input->post('nik'),
        'no_ktp'        => $this->input->post('no_ktp'),
        'fullname'      => $this->input->post('fullname'),
        'tmp_lahir'     => $this->input->post('tmp_lahir'),
        'tgl_lahir'     => $tgl_lahir,
        'jk'            => $this->input->post('jk'),
        'alamat'        => $this->input->post('alamat'),
        'no_hp'         => $this->input->post('no_hp'),
        'from_pernikahan' => $this->input->post('from_pernikahan')
      );

      $table = 'app_karyawan';
      $where = array('nik' => $prev_nik);
      $exec = $this->mcore->update($table, $where, $arr_karyawan);
      // END STORE APP_KARYAWAN TANPA FOTO
    }

    ///////////////////////////////////////////////////////////////////////////////////

    // STORE APP_PENDIDIKAN
    $tgl_lahir     = $this->convert_date_db_format($this->input->post('tgl_lahir'));

    $arr_pendidikan = array(
      'nik'        => $this->input->post('nik'),
      'sd'         => $this->input->post('sd'),
      'smp'        => $this->input->post('smp'),
      'sma'        => $this->input->post('sma'),
      'diploma'    => $this->input->post('diploma'),
      'sarjana'    => $this->input->post('sarjana'),
      'sertifikat' => $this->input->post('sertifikat'),
      'lainnya'    => $this->input->post('lainnya')
    );

    $table = 'app_pendidikan';
    $where = array('nik' => $prev_nik);
    $exec = $this->mcore->update($table, $where, $arr_pendidikan);
    // END STORE APP_PENDIDIKAN

    // STORE APP_KARYAWAN_DETAIL & APP_MUTASI_STATUS & APP_MUTASI_JABATAN & APP_MUTASI_CABANG
    /*  
      16-03-2019 last updated
      KODE STATUS KARYAWAN 
        10 => karyawawn training, 
        11 => perpanjang training, 
        20 => karyawan kontrak 1, 
        21 => karyawan kontrak 2, 
        22 => perpanjang kontrak 2, 
        30 => karyawan tetap,
        40 => karyawan magang,
        50 => resign 
    */

   ///////////////////////////////////////////////////////// KARYAWAN DETAIL

        $arr_karyawan_detail = array(
          'nik'           => $this->input->post('nik'),
          'from_branch'   => $this->input->post('from_branch'),
          'thru_branch'   => $this->input->post('from_branch'),
          'status'        => $this->input->post('status'),
          'tgl_masuk'     => $this->convert_date_db_format($this->input->post('tgl_gabung')),
          'from_branch'   => $this->input->post('from_branch'),
          'thru_branch'   => $this->input->post('from_branch'),
          'from_position' => $this->input->post('from_position'),
          'thru_position' => $this->input->post('from_position'),
          'hak_cuti'      => $this->input->post('hak_cuti'),
          'hak_ijin'      => $this->input->post('hak_ijin'),
        );

        $table = 'app_karyawan_detail';
        $where = array('nik' => $prev_nik);
        $exec = $this->mcore->update($table, $where, $arr_karyawan_detail);
    ///////////////////////////////////////////////////////// END KARYAWAN DETAIL

    ////////////////////////////////////////////////////////// MUTASI STATUS
        $from_date = null;
        $thru_date = null;
    if($this->input->post('status') == "30"){ // KARYAWAN TETAP
      $from_date = $this->convert_date_db_format($this->input->post('from_date_periode'));
    }elseif($this->input->post('status') == "99"){ // KARYAWAN RESIGN
      $thru_date = $this->convert_date_db_format($this->input->post('thru_date_periode'));
    }else{
      $from_date = $this->convert_date_db_format($this->input->post('from_date_periode'));
      $thru_date = $this->convert_date_db_format($this->input->post('thru_date_periode'));
    }

    $arr_mutasi_status = array(
      'nik'       => $this->input->post('nik'),
      'from_date' => $from_date,
      'thru_date' => $thru_date
    );

    $table = 'app_mutasi_status';
    $where = array('nik' => $prev_nik);
    $exec = $this->mcore->update($table, $where, $arr_mutasi_status);
    ////////////////////////////////////////////////////////// END MUTASI STATUS
    

    ////////////////////////////////////////////////////////// MUTASI JABATAN
    $arr_mutasi_jabatan = array('nik' => $this->input->post('nik'));

    $table = 'app_mutasi_jabatan';
    $where = array('nik' => $prev_nik);
    $exec = $this->mcore->update($table, $where, $arr_mutasi_jabatan);
    ////////////////////////////////////////////////////////// END MUTASI STATUS

    ////////////////////////////////////////////////////////// MUTASI CABANG
    /*$from_date = null;
    $thru_date = null;

    if($this->input->post('status') == "30"){ // KARYAWAN TETAP
      $from_date = $this->convert_date_db_format($this->input->post('from_date_periode'));
    }elseif($this->input->post('status') == "99"){ // KARYAWAN RESIGN
      $thru_date = $this->convert_date_db_format($this->input->post('thru_date_periode'));
    }else{
      $from_date = $this->convert_date_db_format($this->input->post('from_date_periode'));
      $thru_date = $this->convert_date_db_format($this->input->post('thru_date_periode'));
    }

    $arr_mutasi_cabang = array(
      'branch_id'    => $this->input->post('from_branch'),
      'nik'          => $this->input->post('nik'),
      'from_branch'  => $this->input->post('from_branch'),
      'thru_branch'  => $this->input->post('from_branch'),
      'from_date'    => $from_date,
      'thru_date'    => $from_date,
      'tgl_mutasi'   => $from_date,
      'created_by'   => $this->session->userdata('logged_in')['fullname'],
      'created_date' => date('Y-m-d H:i:s')
    );

    $table = 'app_mutasi_cabang';
    $where = array('nik' => $prev_nik);
    $exec = $this->mcore->update($table, $where, $arr_mutasi_cabang);

    
    echo $this->db->last_query();
    exit();*/
    ////////////////////////////////////////////////////////// END MUTASI CABANG
    
    ////////////////////////////////////////////////////////// IF STATUS == RESIGN
    $arr_resign = array('nik' => $this->input->post('nik'));

    $table = 'app_karyawan_resign';
    $where = array('nik' => $prev_nik);
    $exec = $this->mcore->update($table, $where, $arr_resign);
    ////////////////////////////////////////////////////////// END IF STATUS == RESIGN

    if($exec === true){
      $result = array(
        'code'        => '200',
        'description' => 'Update Karyawan Berhasil...'
      );
    }else{
      $result = array(
        'code'        => '400',
        'description' => 'Update karyawan gagal, silahkan coba kembali...',
      );
    }
    
    echo json_encode($result);
  }


  ////////////////////////////////////////////////////////////////////////////////////////
  /// FUNCTION HELPER ///////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////
  
  private function convert_date_db_format($date)
  {
    $str_date = strtotime($date);
    $db_date  = date('Y-m-d', $str_date);
    return $db_date;
  }

  public function manual_cutoff($nik)
  {
    $session_data      = $this->session->userdata('logged_in');
    $user              = $session_data['fullname'];
    $periode_cutoff    = $this->model_setup->get_cutoff();
    $get_count_periode = $this->model_setup->get_count_periode();

    if($get_count_periode == '0'){
      echo "<script>alert('Periode Cutoff Belum Terisi!!');history.go(-1)</script>";   
      die(); 
    }

    foreach($periode_cutoff as $value)
    {
      $from_date = $value->from_date; $thru_date = $value->thru_date;
      $from_date_ = $value->from_date; $thru_date_ = $value->thru_date;
    }


    $check_existing_absensi = $this->model_karyawan->check_existing_absensi($nik,$from_date_);
    $jumlah                 = $check_existing_absensi['jumlah'];

    if($jumlah == '0'){
      while(strtotime($from_date) <= strtotime($thru_date)){
        if(date('l', strtotime($from_date)) != 'Saturday' && date('l', strtotime($from_date)) != 'Sunday')
        {
          $get_libur_by_date = $this->model_karyawan->get_libur_by_date($from_date);

          if(is_null($get_libur_by_date['tanggal'])){
            $data = array(
              'nik'               => $nik,
              'masuk'             => '',
              'keluar'            => '',
              'tanggal'           => $from_date,
              'keterangan'        => '',
              'periode_from_date' => $from_date_,
              'periode_thru_date' => $thru_date_,
              'created_date'      => date('Y-m-d H:i:s'),
              'created_by'        => $user
            );

            $update_absensi_manual = $this->model_karyawan->update_absensi_manual($data); 
          }else{
            $data = array(
              'nik'               =>  $nik,
              'masuk'             => '',
              'keluar'            => '',
              'tanggal'           => $from_date,
              'keterangan'        => $get_libur_by_date['description'],
              'periode_from_date' => $from_date_,
              'periode_thru_date' => $thru_date_,
              'created_date'      => date('Y-m-d H:i:s'),
              'created_by'        => $user
            );

            $update_absensi_manual = $this->model_karyawan->update_absensi_manual($data); 
          }
        }

        $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date))); 
      }
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  /// FUNCTION HELPER ///////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////

}