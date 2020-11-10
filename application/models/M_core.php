<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_core extends CI_Model {

  public function get_all_data($table)
  {
    return $this->db->get($table);
  }

  public function get_arr_tgl_libur()
  {
    $this->db->select('tanggal');
    return $this->db->get('app_hari_libur');
  }

  public function get_all_data_e_resign($table)
  {
    $this->db->join('app_karyawan_detail', 'app_karyawan_detail.nik = app_karyawan.nik', 'left');
    $this->db->where('app_karyawan_detail.status !=', '50');
    $this->db->where('app_karyawan_detail.resign IS NULL');
    $this->db->order_by('app_karyawan.fullname', 'asc');
    return $this->db->get($table);
  }

  public function get_all_data_e_resign_2($table, $branch_code, $nik)
  {
    $this->db->join('app_karyawan_detail', 'app_karyawan_detail.nik = app_karyawan.nik', 'left');
    if($nik != '99999'){
      $this->db->where('app_karyawan.nik', $nik);
    }
    $this->db->where('app_karyawan_detail.status !=', '50');
    $this->db->where('app_karyawan_detail.resign IS NULL');
    $this->db->where('app_karyawan_detail.thru_branch', $branch_code);
    $this->db->order_by('app_karyawan.nik', 'ASC');
    return $this->db->get($table);
  }

  public function get_where($table, $where)
  {
    $this->db->where($where);
    return $this->db->get($table);
  }

  public function get_sum_flag_where($from_date, $thru_date)
  {
  	$sql = "

  	";
  	return $this->db->query($sql);

  }

  public function get_where_between($table, $key, $value_a, $value_b)
  {
    $this->db->where($key." BETWEEN '".$value_a."' AND '".$value_b."' ");
    return $this->db->get($table);
  }

  public function get_single_join($table, $join_table, $join_condition, $join_type)
  {
    $this->db->join($join_table, $join_condition, $join_type);
    return $this->db->get($table);
  }

  public function get_where_single_join($table, $where, $join_table, $join_condition, $join_type)
  {
    $this->db->distinct();
    $this->db->where($where);
    $this->db->join($join_table, $join_condition, $join_type);
    return $this->db->get($table);
  }

  public function get_paramater_last_id($table, $where, $order_key, $order_type, $limit, $offset)
  {
    $this->db->order_by($order_key, $order_type);
    $this->db->where($where);
    return $this->db->get($table, $limit, $offset);
  }

  public function store($table, $data)
  {
    return $this->db->insert($table, $data);
  }

  public function update($table, $where, $data)
  {
    if(!empty($where)){
      $this->db->where($where);
    }
    
    return $this->db->update($table, $data);
  }

  public function destroy($table, $where)
  {
    $this->db->where($where);
    return $this->db->delete($table);
  }

  public function get_list_karyawan()
  {
    $this->db->select('
      a.karyawan_id,
      a.nik,
      a.fullname,
      c.description AS status,
      d.description AS position,
      e.description AS cabang
      ');
    $this->db->join('app_karyawan_detail b', 'b.nik = a.nik', 'left');
    $this->db->join('app_parameter c', 'c.parameter_group = \'status\' AND c.parameter_id = b.status', 'left');
    $this->db->join('app_parameter d', 'd.parameter_group = \'jabatan\' AND d.parameter_id = b.thru_position', 'left');
    $this->db->join('app_parameter e', 'e.parameter_group = \'cabang\' AND e.parameter_id = b.thru_branch', 'left');
    $this->db->order_by('a.fullname', 'asc');

    $this->db->group_by('
      a.karyawan_id,
      a.nik,
      a.fullname,
      c.description,
      d.description,
      e.description,
      ');
    return $this->db->get('app_karyawan a');
  }

  public function get_karyawan_by_nik($nik)
  {
    $this->db->select(
      'a.karyawan_id, 
      a.nik, 
      a.fullname, 
      a.tmp_lahir, 
      a.tgl_lahir, 
      a.alamat, 
      a.no_ktp, 
      a.jk, 
      a.from_pernikahan, 
      a.thru_pernikahan, 
      a.no_hp, 
      b.sd, 
      b.smp, 
      b.sma, 
      b.diploma, 
      b.sarjana, 
      c.tgl_masuk, 
      c.status id_status, 
      d.parameter_id AS from_position_id, 
      d.description AS from_position, 
      d.description AS position, 
      e.parameter_id AS thru_position_id, 
      e.description AS thru_position, 
      f.description AS from_branch, 
      g.description AS thru_branch, 
      c.periode_training, 
      c.periode_kontrak_1, 
      c.periode_kontrak_2, 
      (SELECT tgl_resign FROM app_karyawan_resign WHERE a.nik = nik) AS resign,
      h.description AS status, 
      (select from_date from app_mutasi_status where nik = a.nik order by status_id desc limit 1) from_date_status, 
      (select thru_date from app_mutasi_status where nik = a.nik order by status_id desc limit 1) thru_date_status, 
      g.description AS branch'
    );
    $this->db->from('app_karyawan AS a');
    $this->db->join('app_pendidikan AS b', 'a.nik = b.nik', 'left');
    $this->db->join('app_karyawan_detail AS c', 'a.nik = c.nik', 'left');
    $this->db->join('app_parameter AS d', "c.from_position = d.parameter_id AND d.parameter_group = 'jabatan'", 'left');
    $this->db->join('app_parameter AS e', "c.thru_position = e.parameter_id AND e.parameter_group = 'jabatan'", 'left');
    $this->db->join('app_parameter AS f', "c.from_branch = f.parameter_id AND f.parameter_group = 'cabang'", 'left');
    $this->db->join('app_parameter AS g', "c.thru_branch = g.parameter_id AND g.parameter_group = 'cabang'", 'left');
    $this->db->join('app_parameter AS h', "c.status = h.parameter_id AND h.parameter_group = 'status'", 'left');
    $this->db->where('a.nik', $nik);
    return $this->db->get();
  }

  public function get_karyawan_by_id($id)
  {
    $this->db->select(
      'a.karyawan_id, 
      a.foto_karyawan, 
      a.nik, 
      a.fullname, 
      a.tmp_lahir, 
      a.tgl_lahir, 
      a.alamat, 
      a.no_ktp, 
      a.jk, 
      a.from_pernikahan, 
      a.thru_pernikahan, 
      a.no_hp, 
      b.sd, 
      b.smp, 
      b.sma, 
      b.diploma, 
      b.sarjana, 
      b.sertifikat, 
      b.lainnya, 
      c.tgl_masuk, 
      c.status, 
      c.hak_cuti, 
      c.hak_ijin, 
      d.description AS from_position, 
      d.description AS position, 
      e.description AS thru_position, 
      f.description AS from_branch, 
      g.description AS thru_branch, 
      c.periode_training, 
      c.periode_kontrak_1, 
      c.periode_kontrak_2, 
      (SELECT tgl_resign FROM app_karyawan_resign WHERE a.nik = nik) AS resign,
      h.description AS status,
      g.description AS branch,
      i.from_date,
      i.thru_date
      '
    );
    $this->db->from('app_karyawan AS a');
    $this->db->join('app_pendidikan AS b', 'a.nik = b.nik', 'left');
    $this->db->join('app_karyawan_detail AS c', 'a.nik = c.nik', 'left');
    $this->db->join('app_parameter AS d', "c.from_position = d.parameter_id AND d.parameter_group = 'jabatan'", 'left');
    $this->db->join('app_parameter AS e', "c.thru_position = e.parameter_id AND e.parameter_group = 'jabatan'", 'left');
    $this->db->join('app_parameter AS f', "c.from_branch = f.parameter_id AND f.parameter_group = 'cabang'", 'left');
    $this->db->join('app_parameter AS g', "c.thru_branch = g.parameter_id AND g.parameter_group = 'cabang'", 'left');
    $this->db->join('app_parameter AS h', "c.status = h.parameter_id AND h.parameter_group = 'status'", 'left');
    $this->db->join('app_mutasi_status AS i', "i.nik = a.nik", 'left');
    $this->db->where('a.karyawan_id', $id);
    $this->db->order_by('i.status_id', 'desc');
    return $this->db->get();
  }

  function get_karyawan_by_branch($branch_user)
  {
    $this->db->select(
      'a.karyawan_id, 
      a.nik, 
      a.fullname, 
      a.tmp_lahir, 
      a.tgl_lahir, 
      a.alamat, 
      a.no_ktp, 
      a.jk, 
      a.from_pernikahan, 
      a.thru_pernikahan, 
      a.no_hp, 
      b.sd, 
      b.smp, 
      b.sma, 
      b.diploma, 
      b.sarjana, 
      c.tgl_masuk, 
      c.status, 
      d.description AS from_position, 
      d.description AS position, 
      e.description AS thru_position, 
      f.description AS from_branch, 
      g.description AS thru_branch, 
      c.periode_training, 
      c.periode_kontrak_1, 
      c.periode_kontrak_2, 
      (SELECT tgl_resign FROM app_karyawan_resign WHERE a.nik = nik) AS resign,
      h.description AS status, 
      g.description AS cabang'
    );
    $this->db->from('app_karyawan AS a');
    $this->db->join('app_pendidikan AS b', 'a.nik = b.nik', 'left');
    $this->db->join('app_karyawan_detail AS c', 'a.nik = c.nik', 'left');
    $this->db->join('app_parameter AS d', "c.from_position = d.parameter_id AND d.parameter_group = 'jabatan'", 'left');
    $this->db->join('app_parameter AS e', "c.thru_position = e.parameter_id AND e.parameter_group = 'jabatan'", 'left');
    $this->db->join('app_parameter AS f', "c.from_branch = f.parameter_id AND f.parameter_group = 'cabang'", 'left');
    $this->db->join('app_parameter AS g', "c.thru_branch = g.parameter_id AND g.parameter_group = 'cabang'", 'left');
    $this->db->join('app_parameter AS h', "c.status = h.parameter_id AND h.parameter_group = 'status'", 'left');

    if($branch_user != 'semua'){
      $this->db->where('c.thru_branch', $branch_user);
    }

    $this->db->order_by('a.fullname', 'asc');
    $this->db->distinct();
    return $this->db->get();
    //  $this->db->last_query();
    //exit();
  }

  function get_karyawan_by_branch_2($branch_user)
  {
    $this->db->select(
      'a.karyawan_id, 
      a.nik, 
      a.fullname, 
      a.tmp_lahir, 
      a.tgl_lahir, 
      a.alamat, 
      a.no_ktp, 
      a.jk, 
      a.from_pernikahan, 
      a.thru_pernikahan, 
      a.no_hp, 
      b.sd, 
      b.smp, 
      b.sma, 
      b.diploma, 
      b.sarjana, 
      c.tgl_masuk, 
      c.status, 
      d.description AS from_position, 
      d.description AS position, 
      e.description AS thru_position, 
      f.description AS from_branch, 
      g.description AS thru_branch, 
      c.periode_training, 
      c.periode_kontrak_1, 
      c.periode_kontrak_2, 
      (SELECT tgl_resign FROM app_karyawan_resign WHERE a.nik = nik) AS resign,
      h.description AS status, 
      g.description AS cabang'
    );
    $this->db->from('app_karyawan AS a');
    $this->db->join('app_pendidikan AS b', 'a.nik = b.nik', 'left');
    $this->db->join('app_karyawan_detail AS c', 'a.nik = c.nik', 'left');
    $this->db->join('app_parameter AS d', "c.from_position = d.parameter_id AND d.parameter_group = 'jabatan'", 'left');
    $this->db->join('app_parameter AS e', "c.thru_position = e.parameter_id AND e.parameter_group = 'jabatan'", 'left');
    $this->db->join('app_parameter AS f', "c.from_branch = f.parameter_id AND f.parameter_group = 'cabang'", 'left');
    $this->db->join('app_parameter AS g', "c.thru_branch = g.parameter_id AND g.parameter_group = 'cabang'", 'left');
    $this->db->join('app_parameter AS h', "c.status = h.parameter_id AND h.parameter_group = 'status'", 'left');

    if($branch_user != 'semua'){
      $this->db->where('c.thru_branch', $branch_user);
    }
    
    $this->db->where('c.status !=', '50');

    $this->db->order_by('a.fullname', 'asc');
    $this->db->distinct();
    return $this->db->get();
    //  $this->db->last_query();
    //exit();
  }

  public function get_karyawan_by_branch_e_res($branch_user)
  {
    $this->db->select(
      'a.karyawan_id, 
      a.nik, 
      a.fullname, 
      a.tmp_lahir, 
      a.tgl_lahir, 
      a.alamat, 
      a.no_ktp, 
      a.jk, 
      a.from_pernikahan, 
      a.thru_pernikahan, 
      a.no_hp, 
      b.sd, 
      b.smp, 
      b.sma, 
      b.diploma, 
      b.sarjana, 
      c.tgl_masuk, 
      c.status, 
      d.description AS from_position, 
      d.description AS position, 
      e.description AS thru_position, 
      f.description AS from_branch, 
      g.description AS thru_branch, 
      c.periode_training, 
      c.periode_kontrak_1, 
      c.periode_kontrak_2, 
      (SELECT tgl_resign FROM app_karyawan_resign WHERE a.nik = nik) AS resign,
      h.description AS status, 
      g.description AS cabang'
    );
    $this->db->from('app_karyawan AS a');
    $this->db->join('app_pendidikan AS b', 'a.nik = b.nik', 'left');
    $this->db->join('app_karyawan_detail AS c', 'a.nik = c.nik ', 'left');
    $this->db->join('app_parameter AS d', "c.from_position = d.parameter_id AND d.parameter_group = 'jabatan'", 'left');
    $this->db->join('app_parameter AS e', "c.thru_position = e.parameter_id AND e.parameter_group = 'jabatan'", 'left');
    $this->db->join('app_parameter AS f', "c.from_branch = f.parameter_id AND f.parameter_group = 'cabang'", 'left');
    $this->db->join('app_parameter AS g', "c.thru_branch = g.parameter_id AND g.parameter_group = 'cabang'", 'left');
    $this->db->join('app_parameter AS h', "c.status = h.parameter_id AND h.parameter_group = 'status'", 'left');
    $this->db->where('c.status !=', '50');

    if($branch_user != 'semua'){
      $this->db->where('c.thru_branch', $branch_user);
    }

    $this->db->order_by('a.fullname', 'asc');
    $this->db->distinct();
    return $this->db->get();
    //  $this->db->last_query();
    //exit();
  }

  public function get_presensi_by_nik($nik)
  {
    $this->db->select('
      a.nik, 
      b.fullname, 
      a.masuk, 
      a.keluar, 
      a.tanggal, 
      a.keterangan
      ');
    $this->db->join('app_karyawan b', 'b.nik = a.nik', 'left');
    $this->db->where('a.nik', $nik);
    $this->db->order_by('a.tanggal', 'asc');
    return $this->db->get('app_absensi_manual a');
  }

  public function get_presensi_by_nik_2($nik)
  {
    $this->db->select('
      a.nik, 
      b.fullname, 
      a.masuk, 
      a.keluar, 
      a.tanggal, 
      a.keterangan
      ');
    $this->db->join('app_karyawan b', 'b.nik = a.nik', 'left');
    $this->db->join('app_karyawan_detail c', 'a.nik = b.nik', 'left');
    $this->db->where('a.nik', $nik);
    $this->db->where('c.status !=', '50');
    $this->db->order_by('a.tanggal', 'asc');
    return $this->db->get('app_absensi_manual a');
  }

  public function get_absensi_manual_cutoff_branch($from, $thru, $branch)
  {
    $this->db->select('
      a.nik, 
      b.fullname, 
      a.masuk, 
      a.keluar, 
      a.tanggal, 
      a.keterangan
      ');
    $this->db->join('app_karyawan b', 'b.nik = a.nik', 'left');
    $this->db->join('app_karyawan_detail c', 'c.nik = b.nik', 'left');
    $this->db->where("a.tanggal BETWEEN '".$from."' AND '".$thru."'");
    
    if($branch != 'semua'){
      $this->db->where('c.thru_branch', $branch);
    }

    $this->db->order_by('a.nik', 'asc');
    $this->db->order_by('a.tanggal', 'asc');
    return $this->db->get('app_absensi_manual a');
  }

  public function get_absensi_manual_cutoff_branch_2($from, $thru, $branch)
  {
    $this->db->select('
      a.nik, 
      b.fullname, 
      a.masuk, 
      a.keluar, 
      a.tanggal, 
      a.keterangan
      ');
    $this->db->join('app_karyawan b', 'b.nik = a.nik', 'left');
    $this->db->join('app_karyawan_detail c', 'c.nik = b.nik', 'left');
    $this->db->where("a.tanggal BETWEEN '".$from."' AND '".$thru."'");
    
    if($branch != 'semua'){
      $this->db->where('c.thru_branch', $branch);
    }
    
    $this->db->where('c.status !=', '50');

    $this->db->order_by('a.nik', 'asc');
    $this->db->order_by('a.tanggal', 'asc');
    return $this->db->get('app_absensi_manual a');
  }

  public function data_karyawan_alpha($id_cabang = null, $status = null, $awal = null, $akhir = null)
  {
    $this->db->select(
      '
      a.alfa_id, 
      a.nik,
      a.tgl_cuti,
      a.tgl_cuti2,
      a.hari,
      a.group,
      a.kategori_cuti kc,
      d.description kategori_cuti,
      a.keterangan,
      g.fullname approve_by,
      f.fullname created_by,
      a.created_date,
      b.fullname,
      e.description nama_cabang_karyawan,
      c.hak_cuti,
      c.hak_ijin,
      '
    );
    $this->db->join('app_karyawan b', 'b.nik = a.nik', 'left');
    $this->db->join('app_karyawan_detail c', 'c.nik = a.nik', 'left');
    $this->db->join('app_parameter d', "d.parameter_id::int = a.kategori_cuti::int AND d.parameter_group = a.group", 'left', FALSE);
    $this->db->join('app_parameter e', "e.parameter_id = c.thru_branch AND e.parameter_group = 'cabang'", 'left');
    $this->db->join('app_karyawan f', "f.nik = a.created_by", 'left');
    $this->db->join('app_karyawan g', "g.nik = a.aprove_by", 'left');

    if($status == 'pending'){
      $this->db->where('a.aprove_by', NULL);
      $this->db->or_where('a.aprove_by', '');
    }elseif($status == 'terima'){
      $this->db->where('a.aprove_by !=', NULL);
      $this->db->or_where('a.aprove_by !=', '');
    }

    if($id_cabang != null){
      $this->db->where('c.thru_branch', $id_cabang);
    }

    if($awal != null && $akhir != null){
      $this->db->where("a.tgl_cuti BETWEEN '".$awal."' AND '".$akhir."' ");
    }

    $this->db->order_by('a.aprove_by', 'desc');
    return $this->db->get('app_alfa a');
  }

  public function get_card_karyawan($parameter_id = null)
  {

    if($this->session->userdata('logged_in')['role_id'] != 0){
      $this->db->where('b.thru_branch', $this->session->userdata('logged_in')['branch_code']);
    }
    
    $this->db->join('app_karyawan_detail b', 'b.nik = a.nik', 'left');
    $this->db->where('b.status !=', 50);
    return $this->db->count_all_results('app_karyawan a');
  }

  public function get_card_karyawan_belum_absen($from_date, $thru_date, $parameter_id = null)
  {
    $today = date('Y-m-d');
    $this->db->select('count(a.nik) total');
    $this->db->from('app_absensi_manual a'); 
    $this->db->join('app_karyawan_detail b', 'a.nik = b.nik', 'left');  
    $this->db->where('a.tanggal >=', $from_date);
    $this->db->where('a.tanggal <', $thru_date);
    $this->db->where('a.masuk !=', '');
    //$this->db->where('a.keterangan =', "");
    
    if($this->session->userdata('logged_in')['role_id'] != '0'){
      $this->db->where('b.thru_branch', $this->session->userdata('logged_in')['branch_code']);
    }else{
      if($parameter_id != null){
        $this->db->where('b.thru_branch', $parameter_id);
      }
    }
    // END IF SESSION ROLE ID NOT 0
    
    return $this->db->get();
  }

  public function get_card_karyawan_belum_absen_2($from_date, $thru_date, $parameter_id = null)
  {
    $today = date('Y-m-d');
    $this->db->select('count(a.nik) total');
    $this->db->from('app_absensi_manual a'); 
    $this->db->join('app_karyawan_detail b', 'a.nik = b.nik', 'left');  
    $this->db->where('a.tanggal >=', $from_date);
    $this->db->where('a.tanggal <', $thru_date);
    $this->db->where('a.masuk =', '');
    $this->db->where('a.keterangan IS NULL', NULL, FALSE);
    
    if($this->session->userdata('logged_in')['role_id'] != '0'){
      $this->db->where('b.thru_branch', $this->session->userdata('logged_in')['branch_code']);
    }else{
      if($parameter_id != null){
        $this->db->where('b.thru_branch', $parameter_id);
      }
    }
    // END IF SESSION ROLE ID NOT 0
    
    return $this->db->get();
  }

  public function translate_nama_cabang($branch_code)
  {
    $this->db->select('description nama_cabang');
    $this->db->from('app_parameter');
    $this->db->where('parameter_id', $branch_code);
    $this->db->where('parameter_group', 'cabang');
    $que = $this->db->get();
    return $que->row('nama_cabang');

  }

  public function get_nik_by_username($user_id)
  {
    $this->db->select('nik');
    $this->db->from('app_user');
    $this->db->where('user_id', $user_id);
    $que = $this->db->get();
    return $que->row('nik');
  }

  public function get_cuti_khusus()
  {
    $this->db->where('parameter_group', 'cuti_khusus');
    return $this->db->get('app_parameter');
  }

  public function get_cuti_remain($nik)
  {
    $this->db->select('hak_cuti, hak_ijin');
    $this->db->where('nik', $nik);
    return $this->db->get('app_karyawan_detail', 1);
  }

  public function pengurangan_ijin($nik, $hari)
  {
    $this->db->set('hak_ijin ', 'hak_ijin::int - '.$hari.'::int', false);
    $this->db->where('nik', $nik);
    return $this->db->update('app_karyawan_detail');
  }

  public function pengurangan_cuti($nik, $hari)
  {
    $this->db->set('hak_cuti ', 'hak_cuti::int - '.$hari.'::int', false);
    $this->db->where('nik', $nik);
    return $this->db->update('app_karyawan_detail');
  }

  public function get_count_ck_date($nik, $from, $to)
  {
    $query = $this->db->query("SELECT sum(hari) num FROM app_alfa WHERE nik = '$nik' AND \"group\" = 'cuti_khusus' AND tgl_cuti BETWEEN '".$from."' AND '".$to."'");
    $row = $query->row_array();
    return $row['num'];       
  }

  public function get_absent_by_tgl($from_date, $thru_date)
  {
    $this->db->select('
      a.nik, 
      b.fullname,
      a.tgl_cuti, 
      (select c.description from app_parameter c where c.parameter_id::int = a.kategori_cuti::int and a.group = c.parameter_group) AS kategori_cuti, 
      a.keterangan,
      d.fullname aprove,
      ');
    $this->db->from('app_alfa a');
    $this->db->join('app_karyawan b', 'a.nik = b.nik', 'left');
    $this->db->join('app_karyawan d', 'd.nik = a.aprove_by', 'left');
    //$this->db->join('app_parameter c', 'a.kategori_cuti in = c.parameter_id', 'left');
    $query = $this->db->get();



    /*$query = $this->db->query("SELECT  
                  (SELECT fullname FROM app_karyawan WHERE nik = a.aprove_by) AS aprove
                  FROM app_alfa AS a 
                  JOIN app_parameter AS b ON a.kategori_cuti::int = b.parameter_id 
                  JOIN app_karyawan AS c ON a.nik = c.nik WHERE a.tgl_cuti BETWEEN '$from_date' AND '$thru_date'
                  ");*/
                  return $query->result();
                }

                public function get_cuti_by_tgl($branch = null, $from_date, $thru_date)
                {
                  $this->db->select('
                    a.nik, 
                    b.fullname,
                    a.tgl_cuti, 
                    (select c.description from app_parameter c where c.parameter_id::int = a.kategori_cuti::int and a.group = c.parameter_group) AS kategori_cuti, 
                    a.keterangan,
                    d.fullname aprove,
                    ');
                  $this->db->from('app_alfa a');
                  $this->db->join('app_karyawan b', 'a.nik = b.nik', 'left');
                  $this->db->join('app_karyawan d', 'd.nik = a.aprove_by', 'left');
                  $this->db->join('app_karyawan_detail e', 'e.nik = a.nik', 'left');
                  if($branch != null){
                    $this->db->where('e.thru_branch', $branch);
                  }
    //$this->db->join('app_parameter c', 'a.kategori_cuti in = c.parameter_id', 'left');
                  $query = $this->db->get();



    /*$query = $this->db->query("SELECT  
                  (SELECT fullname FROM app_karyawan WHERE nik = a.aprove_by) AS aprove
                  FROM app_alfa AS a 
                  JOIN app_parameter AS b ON a.kategori_cuti::int = b.parameter_id 
                  JOIN app_karyawan AS c ON a.nik = c.nik WHERE a.tgl_cuti BETWEEN '$from_date' AND '$thru_date'
                  ");*/
                  return $query->result();
                }

                public function get_password($user_id)
                {
                  $this->db->select('password');
                  $this->db->where('user_id', $user_id);
                  return $this->db->get('app_user', 1);
                }

                public function jumlah_alpha_cutoff($from_date, $thru_date, $parameter_id = null)
                {
                  $this->db->select('a.nik');
                  $this->db->where("a.tanggal >= ", $from_date);
                  $this->db->where("a.tanggal < ", $thru_date);
                  $this->db->where("a.keterangan", '');

                  if($this->session->userdata('logged_in')['role_id'] != '0'){
                    $this->db->join('app_karyawan_detail b', 'a.nik = b.nik', 'left');
                    $this->db->where('b.thru_branch', $this->session->userdata('logged_in')['branch_code']);
                  }

                  return $this->db->get('app_absensi_manual a');
                }

                public function jumlah_sakit_cutoff($from_date, $thru_date, $parameter_id = null)
                {
                  $today = date('Y-m-d');
                  $this->db->select('count(a.nik) total');
                  $this->db->where('a.group = ', 'sakit');
                  $this->db->where('a.kategori_cuti =', '1');
                  $this->db->where('a.aprove_by !=', NULL);
                  $this->db->where('a.tgl_cuti >=', $from_date);
    //$this->db->where('a.tgl_cuti <=', $thru_date);
                  $this->db->where('a.tgl_cuti <', $thru_date);

                  if($parameter_id != NULL){
                    $this->db->join('app_karyawan_detail b', 'a.nik = b.nik', 'left');
                    $this->db->where('b.thru_branch', $parameter_id);
                  }elseif($this->session->userdata('logged_in')['role_id'] != 0){
                    $this->db->join('app_karyawan_detail b', 'a.nik = b.nik', 'left');
                    $this->db->where('b.thru_branch', $this->session->userdata('logged_in')['branch_code']);
                  }

                  return $this->db->get('app_alfa a');
                }

                public function jumlah_ijin_cutoff($from_date, $thru_date, $parameter_id = null)
                {
                  $today = date('Y-m-d');
                  $this->db->select('count(a.nik) total');
                  $this->db->where('a.group =', 'ijin');
                  $this->db->where('a.kategori_cuti =', '1');
                  $this->db->where('a.aprove_by !=', null);
                  $this->db->where('a.tgl_cuti >=', $from_date);
    //$this->db->where('a.tgl_cuti <=', $thru_date);
                  $this->db->where('a.tgl_cuti <=', $thru_date);

                  if($parameter_id != null){
                    $this->db->join('app_karyawan_detail b', 'a.nik = b.nik', 'left');
                    $this->db->where('b.thru_branch', $parameter_id);
                  }elseif($this->session->userdata('logged_in')['role_id'] != 0){
                    $this->db->join('app_karyawan_detail b', 'a.nik = b.nik', 'left');
                    $this->db->where('b.thru_branch', $this->session->userdata('logged_in')['branch_code']);
                  }

                  return $this->db->get('app_alfa a');
                }

                public function jumlah_cuti_cutoff($from_date, $thru_date, $parameter_id = null)
                {
                  $today = date('Y-m-d');
                  $this->db->select('count(a.nik) total');
                  $this->db->where('a.group =', 'cuti');
                  $this->db->where('a.kategori_cuti =', '1');
                  $this->db->where('a.aprove_by !=', null);
                  $this->db->where('a.tgl_cuti >=', $from_date);
    //$this->db->where('a.tgl_cuti <=', $thru_date);
                  $this->db->where('a.tgl_cuti <=', $thru_date);

                  if($parameter_id != null){
                    $this->db->join('app_karyawan_detail b', 'a.nik = b.nik', 'left');
                    $this->db->where('b.thru_branch', $parameter_id);
                  }elseif($this->session->userdata('logged_in')['role_id'] != 0){
                    $this->db->join('app_karyawan_detail b', 'a.nik = b.nik', 'left');
                    $this->db->where('b.thru_branch', $this->session->userdata('logged_in')['branch_code']);
                  }

                  return $this->db->get('app_alfa a');
                }

                public function jumlah_cuti_khusus_cutoff($from_date, $thru_date, $parameter_id = null)
                {
                  $today = date('Y-m-d');
                  $this->db->select('count(a.nik) total');
                  $this->db->where('a.group =', 'cuti_khusus');
                  $this->db->where('a.aprove_by !=', null);
                  $this->db->where('a.tgl_cuti >=', $from_date);
    //$this->db->where('a.tgl_cuti <=', $thru_date);
                  $this->db->where('a.tgl_cuti <=', $thru_date);

                  if($parameter_id != null){
                    $this->db->join('app_karyawan_detail b', 'a.nik = b.nik', 'left');
                    $this->db->where('b.thru_branch', $parameter_id);
                  }elseif($this->session->userdata('logged_in')['role_id'] != 0){
                    $this->db->join('app_karyawan_detail b', 'a.nik = b.nik', 'left');
                    $this->db->where('b.thru_branch', $this->session->userdata('logged_in')['branch_code']);
                  }

                  return $this->db->get('app_alfa a');
                }

                public function jumlah_hari_kerja($from_date, $thru_date, $thru_date_active = null)
                {
                  $this->db->select('nik');
                  $this->db->where('periode_from_date', $from_date);
                  $this->db->where('periode_thru_date', $thru_date);
                  $dummy_nik = $this->db->get('app_absensi_manual', 1)->row('nik');

                  $this->db->select('count(a.nik) total');
                  if($thru_date_active != null){
                    $this->db->where('a.tanggal >=', $from_date);
                    $this->db->where('a.tanggal <', $thru_date_active);
                  }else{
                    $this->db->where('a.periode_from_date >=', $from_date);
                    $this->db->where('a.periode_thru_date <=', $thru_date);
                  }
                  $this->db->where('a.nik', $dummy_nik);
                  $query = $this->db->get('app_absensi_manual a');
                  return $query;
                }

                public function jumlah_hari_libur($from_date, $thru_date)
                {
                  $today = date('Y-m-d');
                  $this->db->select('count(id) total');
                  $this->db->where('tanggal >=', $from_date);
                  $this->db->where('tanggal <', $thru_date);
                  return $this->db->get('app_hari_libur');
                }

                public function get_count_karyawan()
                {
    // IF SESSION ROLE ID NOT 0
                  if($this->session->userdata('logged_in')['role_id'] != '0'){
                    $this->db->select('
                      a.nik, 
                      a.fullname, 
                      a.foto_karyawan, 
                      (select x.description from app_parameter x where x.parameter_id = b.status  and x.parameter_group = \'status\') status, 
                      (select x.description from app_parameter x where x.parameter_id = b.thru_position  and x.parameter_group = \'jabatan\') jabatan, 
                      c.parameter_id, 
                      c.description nama_cabang, 
                      count(a.karyawan_id) total
                      ');
                    $this->db->join('app_karyawan_detail b', 'b.nik = a.nik', 'left');
                    $this->db->join('app_parameter c', "c.parameter_id = b.thru_branch AND c.parameter_group = 'cabang'", 'left');
                    $this->db->where('b.thru_branch', $this->session->userdata('logged_in')['branch_code']);
                    $this->db->group_by('a.nik');
                    $this->db->group_by('a.fullname');
                    $this->db->group_by('a.foto_karyawan');
                    $this->db->group_by('b.status');
                    $this->db->group_by('b.thru_position');
                    $this->db->group_by('c.description');
                    $this->db->group_by('c.parameter_id');
                    $this->db->order_by('a.nik', 'asc');
                    return $this->db->get('app_karyawan a');
                  }else{
                    $this->db->select('
                      c.parameter_id, 
                      c.description nama_cabang, 
                      count(a.karyawan_id) total
                      ');
                    $this->db->join('app_karyawan_detail b', 'b.nik = a.nik', 'left');
                    $this->db->join('app_parameter c', "c.parameter_id = b.thru_branch AND c.parameter_group = 'cabang'", 'left');
                    $this->db->where('b.status !=', '50', FALSE);
                    $this->db->group_by('c.description');
                    $this->db->group_by('c.parameter_id');
                    $this->db->order_by('c.parameter_id', 'asc');
                    return $this->db->get('app_karyawan a');
                  }
    // END IF SESSION ROLE ID NOT 0
                }

                public function get_periode_cabang_by_nik($nik)
                {
                  $this->db->select('b.nik, b.fullname, a.thru_branch, c.description AS cabang, d.from_date, d.thru_date');
                  $this->db->join('app_mutasi_cabang d', 'd.nik = a.nik', 'left');
                  $this->db->join('app_parameter c', 'c.parameter_id = a.thru_branch AND c.parameter_group = \'cabang\'', 'left');
                  $this->db->join('app_karyawan b', 'b.nik = a.nik', 'left');
                  $this->db->where('a.nik', $nik);
                  $query = $this->db->get('app_karyawan_detail a');

    /*$query = $this->db->query("SELECT a.nik, a.fullname, b.thru_branch, c.description AS cabang, b.from_date, b.thru_date FROM app_karyawan AS a 
                  LEFT JOIN app_mutasi_cabang AS b ON a.nik = b.nik
                  LEFT JOIN app_parameter AS c ON b.thru_branch::int = c.parameter_id AND c.parameter_group = 'cabang' WHERE a.nik = '$nik'
                  ORDER BY b.thru_date DESC");*/
                  return $query->row_array();
                }

                public function get_karyawan_belum_absen($from_date, $thru_date)
                {
    //select count(nik) from app_absensi_manual where tanggal between sample '2019-03-21' and '2019-04-20' group by nik
                  $this->db->select('
                    a.nik,
                    xxx.fullname nama,
                    c.description cabang,
                    (
                    count(a.nik) 
                    - (SELECT count(lll.tanggal) from app_hari_libur lll where lll.tanggal >=  \''.$from_date.'\' AND lll.tanggal < \''.$thru_date.'\')
                    - (SELECT count(zzz.nik) from app_alfa zzz where zzz.aprove_by IS NOT NULL AND zzz.nik = a.nik AND zzz.tgl_cuti >= \''.$from_date.'\' AND zzz.tgl_cuti < \''.$thru_date.'\' )
                    ) total
                    ');
                  $this->db->from('app_absensi_manual a');
                  $this->db->join('app_karyawan xxx', 'xxx.nik = a.nik', 'left');
                  $this->db->join('app_karyawan_detail b', 'a.nik = b.nik', 'left');
                  $this->db->join('app_parameter c', 'c.parameter_id = b.thru_branch AND c.parameter_group = \'cabang\'', 'left');


    // IF SESSION ROLE ID NOT 0
                  if($this->session->userdata('logged_in')['role_id'] != '0'){
                    $this->db->where('b.thru_branch', $this->session->userdata('logged_in')['branch_code']);
                  }
    // END IF SESSION ROLE ID NOT 0

                  $this->db->where('a.tanggal >=', $from_date);
    //$this->db->where('a.tanggal <=', $thru_date);
                  $this->db->where('a.tanggal <', $thru_date);
                  $this->db->where('a.keluar = ', '');

                  $this->db->group_by('a.nik');
                  $this->db->group_by('b.thru_branch');
                  $this->db->group_by('c.description');
                  $this->db->group_by('xxx.fullname');
                  $this->db->order_by('a.nik', 'asc');
                  return $this->db->get();
                }

                public function get_absensi_f_t_by_nik($nik, $from, $to)
                {
                  $this->db->where('nik', $nik);
                  $this->db->where("tanggal BETWEEN '".$from."' AND '".$to."' ", NULL, FALSE);
                  return $this->db->get('app_absensi_manual');
                }

                public function get_libur_by_tanggal($tanggal)
                {
                  $this->db->where("tanggal", $tanggal);
                  return $this->db->get('app_hari_libur');
                }

                public function get_branch_own()
                {
                  if($this->session->userdata('logged_in')['role_id'] == 0)
                  {
                    $query  = $this->db->query("SELECT DISTINCT * FROM app_parameter WHERE parameter_group = 'cabang' ORDER BY parameter_id");      
                  }else{
                    $branch = $this->session->userdata('logged_in')['branch_code'];
                    $query  = $this->db->query("SELECT DISTINCT * FROM app_parameter WHERE parameter_group = 'cabang' AND parameter_id = '".$branch."' ORDER BY parameter_id");      
                  }

                  return $query->result();
                }

                public function hitung_hari_pengajuan_cuti($nik, $tgl_cuti, $tgl_cuti2)
                {
                  $this->db->select('count(nik) hari');
                  $this->db->where('tanggal >= ', $tgl_cuti);
                  $this->db->where('tanggal <=', $tgl_cuti2);
                  $this->db->where('nik = ', $nik);
                  return $this->db->get('app_absensi_manual');
                }

                public function cek_tgl_libur($tgl)
                {
                  $this->db->where('tanggal = ', $tgl);
                  return $this->db->count_all_results('app_hari_libur');
                }

                public function return_cuti_ijin($nik, $kc, $hari)
                {
                  $this->db->trans_start();

                  if($kc == "ijin"){
                    $this->db->set('hak_ijin', 'hak_ijin::int + '.$hari, FALSE);
                  }elseif($kc == "cuti"){
                    $this->db->set('hak_cuti', 'hak_cuti::int + '.$hari, FALSE); 
                  }

                  $this->db->where('nik', $nik);
                  $this->db->update('app_karyawan_detail');

                  $this->db->trans_complete();
                  return $this->db->trans_status();
                }

                public function forget_holiday($nik, $tgl, $keterangan)
                {
                  $this->db->trans_start();

                  $this->db->set('keterangan', $keterangan);
                  $this->db->where('nik', $nik);
                  $this->db->where('tanggal', $tgl);
                  $this->db->update('app_absensi_manual');

                  $this->db->trans_complete();
                  return $this->db->trans_status();
                }

                public function total_cabang()
                {
                  $this->db->where('parameter_group', 'cabang');
                  $this->db->from('app_parameter');
                  return $this->db->count_all_results();
                }

                public function repair_detik()
                {
    # update app_absensi_manual set masuk = replace(masuk, 'SS', '00') where masuk like '%SS%';

                  $this->db->trans_start();

                  $this->db->set('masuk', 'REPLACE(masuk, \'SS\', \'00\')', FALSE);
                  $this->db->where('masuk LIKE', '\'%SS%\'', FALSE);
                  $this->db->update('app_absensi_manual');

                  $this->db->set('keluar', 'REPLACE(keluar, \'SS\', \'00\')', FALSE);
                  $this->db->where('keluar LIKE', '\'%SS%\'', FALSE);
                  $this->db->update('app_absensi_manual');

                  $this->db->trans_complete();

                  return $this->db->trans_status();
                }

                public function expired_kontrak()
                {
                  $xfactor = '';
                  $today = date("Y-m-d", strtotime("-1 month"));
                  if($this->session->userdata('logged_in')['role_id'] != 0){
                    $xfactor = "AND detail.thru_branch = '".$this->session->userdata('logged_in')['branch_code']."'";
                  }
                  $que = "
                  SELECT 
                  karyawan.nik,
                  karyawan.fullname,
                  (
                  SELECT parameter1.description
                  FROM app_karyawan_detail detail
                  LEFT JOIN app_parameter parameter1 
                  ON parameter1.parameter_id = detail.thru_branch 
                  AND parameter1.parameter_group = 'cabang' 
                  WHERE detail.nik = karyawan.nik 
                  ) cabang,
                  (
                  SELECT parameter2.description
                  FROM app_karyawan_detail detail
                  LEFT JOIN app_parameter parameter2 
                  ON cast(parameter2.parameter_id as integer) = detail.status 
                  AND parameter2.parameter_group = 'status' 
                  WHERE detail.nik = karyawan.nik 
                  ) status,
                  max_from_date from_date,
                  max_thru_date thru_date 
                  FROM app_karyawan karyawan
                  INNER JOIN app_karyawan_detail detail 
                  ON detail.nik = karyawan.nik 
                  AND detail.status IN (
                  '10', '11', '20', '21'
                  ) 
                  $xfactor
                  LEFT JOIN (
                  SELECT DISTINCT ON (status.nik)
                  status.nik,
                  MAX(status.from_date) AS max_from_date, 
                  MAX(status.thru_date) AS max_thru_date 
                  FROM app_mutasi_status status
                  GROUP BY 
                  status.nik,
                  status.thru_date
                  ) status
                  ON karyawan.nik = status.nik
                  WHERE status.max_thru_date <= '$today'
                  ORDER BY karyawan.fullname ASC
                  ";
                  return $this->db->query($que);
    /*$today = date("Y-m-d", strtotime("-1 month"));
    $this->db->distinct('a.nik');
    $this->db->select('
      a.nik,
      b.fullname,
      c.description status,
      d.description cabang,
      a.from_date,
      a.thru_date
    ');
    $this->db->join('app_karyawan b', 'a.nik = b.nik', 'left');
    $this->db->join('app_karyawan_detail b2', 'a.nik = b2.nik', 'left');
    $this->db->join('app_parameter c', 'cast(a.thru_status as integer) = cast(c.parameter_id as integer) and c.parameter_group = \'status\'', 'left');
    $this->db->join('app_parameter d', 'cast(b2.thru_branch as integer) = cast(d.parameter_id as integer) and d.parameter_group = \'cabang\'', 'left');
    $this->db->where("a.thru_status IN ('10', '11', '20', '21')", NULL, FALSE);
    $this->db->where('a.thru_date <= ', $today);
    if($this->session->userdata('logged_in')['role_id'] != 0){
      $this->db->where('b2.thru_branch = ', $this->session->userdata('logged_in')['branch_code']);
    }

    $this->db->order_by('a.thru_date', 'asc');
    $this->db->order_by('b.fullname', 'asc');
    $this->db->group_by('a.nik');
    $this->db->group_by('b.fullname');
    $this->db->group_by('c.description');
    $this->db->group_by('d.description');
    $this->db->group_by('a.from_date');
    $this->db->group_by('a.thru_date');
    //return $this->db->get('app_mutasi_status a', 10, 0);
    return $this->db->get('app_mutasi_status a');*/
  }

  public function data_karyawan_lembur($id_cabang = null)
  {
    $this->db->select(
      '
      a.id, 
      a.nik,
      a.tgl,
      a.jam_a,
      a.jam_b,
      sum(
      cast(
      concat(
      (extract(hour from jam_b)),
      '.',
      (extract(minute from jam_b))
      )
      as float) - 
      cast(
      concat(
      (extract(hour from jam_a)),
      '.',
      (extract(minute from jam_a))
      )
      as float)
      ) as total_jam,
      a.keterangan,
      b.fullname,
      (select d.fullname from app_karyawan d where d.nik = a.approval) approval
      '
    );
    $this->db->join('app_karyawan b', 'b.nik = a.nik', 'left');
    $this->db->group_by('a.id');
    $this->db->group_by('a.nik');
    $this->db->group_by('a.tgl');
    $this->db->group_by('a.jam_a');
    $this->db->group_by('a.jam_b');
    $this->db->group_by('a.keterangan');
    $this->db->group_by('b.fullname');
    $this->db->order_by('a.id', 'desc');
    return $this->db->get('app_lembur a');
  }

  public function data_karyawan_lembur_by_nik($nik, $from_date, $thru_date)
  {
    $this->db->select(
      '
      a.id, 
      a.nik,
      a.tgl,
      cast (a.jam_a as time) jam_a,
      cast (a.jam_b as time) jam_b,
      sum(
      cast(
      concat(
      (extract(hour from jam_b)),
      \'.\',
      (extract(minute from jam_b))
      )
      as float) - 
      cast(
      concat(
      (extract(hour from jam_a)),
      \'.\',
      (extract(minute from jam_a))
      )
      as float)
      ) as total_jam,
      a.keterangan,
      b.fullname,
      (select d.fullname from app_karyawan d where d.nik = a.approval) approval
      '
    );
    $this->db->join('app_karyawan b', 'b.nik = a.nik', 'left');
    $this->db->where('a.nik', $nik);
    $this->db->where("a.tgl BETWEEN '".$from_date."' AND '".$thru_date."'");
    $this->db->group_by('a.id');
    $this->db->group_by('a.nik');
    $this->db->group_by('a.tgl');
    $this->db->group_by('a.jam_a');
    $this->db->group_by('a.jam_b');
    $this->db->group_by('a.keterangan');
    $this->db->group_by('b.fullname');
    $this->db->order_by('a.id', 'desc');
    return $this->db->get('app_lembur a');
  }

  public function check_data_absensi($nik, $tgl, $jam)
  {
    $this->db->where('nik', $nik);
    $this->db->where('waktu', $jam);
    $this->db->where('tanggal', $tgl);
    return $this->db->get('app_absen');
  }

  public function get_absensi($awal, $akhir, $nik = null)
  {
    $this->db->select('
      nik, 
      tanggal, 
      case 
        when MIN(waktu) = MAX(waktu) AND MIN(waktu) < \'12:00:00\' then MIN(waktu)
        when MIN(waktu) = MAX(waktu) AND MIN(waktu) > \'12:00:00\' then \'12:00:00\'
        when MIN(waktu) < \'12:00:00\' then MIN(waktu)
        when MIN(waktu) > \'12:00:00\' then MIN(waktu)
        else NULL
      end min_waktu,
      case 
        when MAX(waktu) = MIN(waktu) AND MAX(waktu) < \'12:00:00\' then \'12:00:01\'
        when MAX(waktu) = MIN(waktu) AND MAX(waktu) > \'12:00:00\' then MAX(waktu)
        when MAX(waktu) < \'12:00:00\' then MAX(waktu)
        when MAX(waktu) > \'12:00:00\' then MAX(waktu)
        else NULL
      end max_waktu
      ');
    $this->db->where("tanggal between '".$awal."' and '".$akhir."' ", null, false);

    if($nik != null){
      $this->db->where('nik', $nik);
    }

    $this->db->group_by('nik');
    $this->db->group_by('tanggal');
    $this->db->order_by('tanggal', 'asc');
    return $this->db->get('app_absen');
  }

  public function truncate_absen()
  {
    $this->db->trans_start();
    $this->db->truncate('app_absen');
    $this->db->trans_complete();
    return $this->db->trans_status();
  }

  public function cek_absensi($nik, $tanggal, $tipe)
  {
    if($tipe == 'masuk'){
      $this->db->select('masuk');
      $this->db->where('nik', $nik);
      $this->db->where('tanggal', $tanggal);
      return $this->db->get('app_absensi_manual');
    }elseif($tipe == 'keluar'){
      $this->db->select('keluar');
      $this->db->where('nik', $nik);
      $this->db->where('tanggal', $tanggal);
      return $this->db->get('app_absensi_manual');
    }else{
      $this->db->select('keterangan');
      $this->db->where('nik', $nik);
      $this->db->where('tanggal', $tanggal);
      return $this->db->get('app_absensi_manual');
    }
  }

  public function update_absensi_manual_by_nik_masuk($nik, $min_waktu, $tanggal)
  {
    $this->db->trans_start();
    $object = ['masuk' => $min_waktu];
    $this->db->where('nik', $nik);
    $this->db->where('tanggal', $tanggal);
    $this->db->update('app_absensi_manual', $object);
    $this->db->trans_complete();
    return $this->db->trans_status();
  }

  public function update_absensi_manual_by_nik_keluar($nik, $max_waktu, $tanggal)
  {
    $this->db->trans_start();
    $object = ['keluar' => $max_waktu];
    $this->db->where('nik', $nik);
    $this->db->where('tanggal', $tanggal);
    $this->db->update('app_absensi_manual', $object);
    $this->db->trans_complete();
    return $this->db->trans_status();
  }

  public function null_keterangan($table, $periode_from_date, $periode_thru_date)
  {
    $this->db->query("update app_absensi_manual set keterangan = NULL where keterangan = '' and periode_from_date = '".$periode_from_date."' and periode_thru_date = '".$periode_thru_date."'");
  }

  public function insert_batch($table, $object)
  {
    $this->db->trans_start();
    $this->db->insert_batch($table, $object);
    $this->db->trans_complete();
    return $this->db->trans_status();
  }

  public function get_belum_absen($from_date  = NULL, $thru_date = NULL, $arr_liburnya = NULL)
  { 
    $sql = "
    select 
      karyawan.nik,
      karyawan.fullname nama,
      parcab.description cabang,
      count(absensi.nik) total
    from app_karyawan_detail karyawan_detail
    left join app_karyawan karyawan on karyawan.nik = karyawan_detail.nik
    left join app_parameter parcab on parcab.parameter_id = karyawan_detail.thru_branch and parcab.parameter_group = 'cabang'
    left join app_absensi_manual absensi on absensi.nik = karyawan.nik
    where
      (
        absensi.keluar IS NULL or
        absensi.keluar = ''
      ) and
      absensi.tanggal NOT IN ('$arr_liburnya') and
      absensi.tanggal >= '$from_date' and
      absensi.tanggal <= '$thru_date' and
      karyawan_detail.status not in ('50')
    ";

    if($this->session->userdata('logged_in')['role_id'] != '0')
    {
      $sql .= "and karyawan_detail.thru_branch IN ('".$this->session->userdata('logged_in')['branch_code']."')";
    }

    $sql .= "
    group by
      karyawan.nik,
      karyawan.fullname,
      parcab.description
    ";
    return $this->db->query($sql);
  }

  public function get_sudah_absen($from_date  = NULL, $thru_date = NULL, $arr_liburnya = NULL)
  { 
    $sql = "
    select 
      karyawan.nik,
      karyawan.fullname nama,
      parcab.description cabang,
      count(absensi.nik) total
    from app_karyawan_detail karyawan_detail
    left join app_karyawan karyawan on karyawan.nik = karyawan_detail.nik
    left join app_parameter parcab on parcab.parameter_id = karyawan_detail.thru_branch and parcab.parameter_group = 'cabang'
    left join app_absensi_manual absensi on absensi.nik = karyawan.nik
    where
      (
        absensi.keluar != ''
      ) and
      absensi.tanggal NOT IN ('$arr_liburnya') and
      absensi.tanggal >= '$from_date' and
      absensi.tanggal <= '$thru_date' and
      karyawan_detail.status not in ('50')
    ";

    if($this->session->userdata('logged_in')['role_id'] != '0')
    {
      $sql .= "and karyawan_detail.thru_branch IN ('".$this->session->userdata('logged_in')['branch_code']."')";
    }

    $sql .= "
    group by
      karyawan.nik,
      karyawan.fullname,
      parcab.description
    ";
    return $this->db->query($sql);
  }

  public function only_belum_absen()
  {
    $sql = "
    select 
      karyawan.nik,
      karyawan.fullname nama,
      parcab.description cabang,
      count(absensi.nik) total
    from app_karyawan_detail karyawan_detail
    left join app_karyawan karyawan on karyawan.nik = karyawan_detail.nik
    left join app_parameter parcab on parcab.parameter_id = karyawan_detail.thru_branch and parcab.parameter_group = 'cabang'
    left join app_absensi_manual absensi on absensi.nik = karyawan.nik
    where
      (
        absensi.keluar IS NULL or
        absensi.keluar = ''
      ) and
      absensi.tanggal NOT IN ('$arr_liburnya') and
      absensi.tanggal >= '$from_date' and
      absensi.tanggal <= '$thru_date' and
      karyawan_detail.status not in ('50')
    ";

    if($this->session->userdata('logged_in')['role_id'] != '0')
    {
      $sql .= "and karyawan_detail.thru_branch IN ('".$this->session->userdata('logged_in')['branch_code']."')";
    }

    $sql .= "
    group by
      karyawan.nik,
      karyawan.fullname,
      parcab.description
    ";
    return $this->db->query($sql);
  }

  public function get_presensi_by_nik_filled($nik, $from_date)
  {
  	$sql = "select count(am.nik) total from app_absensi_manual am where nik = '".$nik."' and am.periode_from_date = '".$from_date."' and am.masuk is not null and keluar is not null and masuk != '' and keluar != '' group by am.nik ";
  	return $this->db->query($sql);
  }

  public function get_all_data_from_fp_temp($from_date, $thru_date, $nik = NULL)
  {
  	$extend_where = "";

  	if($this->session->userdata('logged_in')['role_id'] > 0){
  		$extend_where = " AND kd.thru_branch = '".$this->session->userdata('logged_in')['branch_code']."' ";
  	}

  	if($nik != NULL){
  		$extend_where .= " AND absen.nik = '".$nik."' ";
  	}

  	$sql = "
  	SELECT
	  	absen.nik,
			min(absen.waktu) as min_waktu,
			max(absen.waktu) as max_waktu,
			absen.tanggal
		FROM app_absen as absen
		LEFT JOIN app_karyawan_detail as kd ON kd.nik = absen.nik
		WHERE absen.tanggal BETWEEN '".$from_date."' AND '".$thru_date."'
		".$extend_where."
		GROUP BY 
			absen.nik,
			absen.tanggal
		ORDER BY absen.nik asc, absen.tanggal asc
		";
  	return $this->db->query($sql);
  }

  public function get_karyawan($resign = TRUE, $branch_code = NULL, $nik = NULL, $periode_from_date = NULL)
  {
  	$where = " WHERE k.nik IS NOT NULL ";

  	if($resign = TRUE){
  		$where .= " AND kd.status NOT IN ('50') ";
  	}

  	if($branch_code != NULL){
  		$where .= " AND kd.thru_branch = '".$branch_code."' ";
  	}

  	if($nik != NULL){
  		$where .= " AND k.nik = '".$nik."' ";
  	}

  	if($periode_from_date != NULL){
  		$where .= " AND am.periode_from_date = '".$periode_from_date."' ";
  	}




  	$sql = "
  	SELECT
  		k.nik,
  		k.fullname,
  		(select description from app_parameter as pp where pp.parameter_group = 'jabatan' and pp.parameter_id = kd.thru_position) as position,
  		(select description from app_parameter as pb where pb.parameter_group = 'cabang' and pb.parameter_id = kd.thru_branch) as branch,
  		SUM ( am.l ) AS l,
			SUM ( am.h ) AS h,
			SUM ( am.tlk ) AS tlk,
			SUM ( am.c ) AS c,
			SUM ( am.ck ) AS ck,
			SUM ( am.dnl ) AS dnl,
			SUM ( am.sd ) AS sd,
			SUM ( am.i ) AS i,
			SUM ( am.ltg ) AS ltg,
			SUM ( am.m8 ) AS m8,
			SUM ( am.m815 ) AS m815,
			SUM ( am.m830 ) AS m830,
			SUM ( am.m12 ) AS m12,
			SUM ( am.k12 ) AS k12,
			SUM ( am.k430 ) AS k430,
			SUM ( am.k445 ) AS k445,
			SUM ( am.k5 ) AS k5,
			SUM ( am.lembur ) AS lembur
  	FROM app_karyawan AS k
  	LEFT JOIN app_karyawan_detail AS kd ON kd.nik = k.nik
  	LEFT JOIN app_absensi_manual AS am ON am.nik = k.nik
  	".$where."
  	GROUP BY 
  	k.nik,
  	k.fullname,
  	kd.thru_branch,
		kd.thru_position
  	ORDER BY kd.thru_branch, k.nik ASC
  	";

  	return $this->db->query($sql);
  }

  public function update_masuk($masuk, $h, $ltg, $m8, $m815, $m830, $m12, $nik, $tanggal)
  {
  	$sql = "
  	UPDATE app_absensi_manual 
		SET 
		masuk = '".$masuk."',
		h = '".$h."',
		ltg = '".$ltg."',
		m8 = '".$m8."',
		m815 = '".$m815."',
		m830 = '".$m830."',
		m12 = '".$m12."' 
		WHERE
			nik = '".$nik."' 
			AND tanggal = '".$tanggal."' 
			AND 
			(masuk = '12:00:00' or masuk = '' or masuk = NULL)
			AND l != '1' 
			AND tlk != '1' 
			AND c != '1' 
			AND ck != '1' 
			AND dnl != '1' 
			AND sd != '1' 
			AND i != '1'
  	";
  	return $this->db->query($sql);
  }

  public function update_masuk1($masuk, $h, $ltg, $m8, $m815, $m830, $m12, $nik, $tanggal)
  {
  	$sql = "
  	UPDATE app_absensi_manual 
		SET 
		masuk = '".$masuk."',
		h = '".$h."',
		ltg = '".$ltg."',
		m8 = '".$m8."',
		m815 = '".$m815."',
		m830 = '".$m830."',
		m12 = '".$m12."' 
		WHERE
			nik = '".$nik."' 
			AND tanggal = '".$tanggal."' 
			AND l != '1' 
			AND tlk != '1' 
			AND c != '1' 
			AND ck != '1' 
			AND dnl != '1' 
			AND sd != '1' 
			AND i != '1'
  	";
  	return $this->db->query($sql);
  }

  public function update_keluar($keluar, $h, $ltg, $k430, $k445, $k5, $k12, $nik, $tanggal)
  {
  	$sql = "
  	UPDATE app_absensi_manual 
		SET 
		keluar = '".$keluar."',
		h = '".$h."',
		ltg = '".$ltg."',
		k430 = '".$k430."',
		k445 = '".$k445."',
		k5 = '".$k5."',
		k12 = '".$k12."' 
		WHERE
			nik = '".$nik."' 
			AND tanggal = '".$tanggal."' 
			AND 
			(keluar = '12:00:01' or keluar = '' or keluar = NULL)
			AND l != '1' 
			AND tlk != '1' 
			AND c != '1' 
			AND ck != '1' 
			AND dnl != '1' 
			AND sd != '1' 
			AND i != '1'
  	";
  	return $this->db->query($sql);
  }

  public function update_keluar1($keluar, $h, $ltg, $k430, $k445, $k5, $k12, $nik, $tanggal)
  {
  	$sql = "
  	UPDATE app_absensi_manual 
		SET 
		keluar = '".$keluar."',
		h = '".$h."',
		ltg = '".$ltg."',
		k430 = '".$k430."',
		k445 = '".$k445."',
		k5 = '".$k5."',
		k12 = '".$k12."' 
		WHERE
			nik = '".$nik."' 
			AND tanggal = '".$tanggal."' 
			AND l != '1' 
			AND tlk != '1' 
			AND c != '1' 
			AND ck != '1' 
			AND dnl != '1' 
			AND sd != '1' 
			AND i != '1'
  	";
  	return $this->db->query($sql);
  }

  public function get_rinci_absensi($period_awal, $branch_code, $nik = NULL)
  {
  	$where_extend = '';
  	if($branch_code != 'semua' && $branch_code != '99999'){
  		$where_extend .= " AND kd.thru_branch = '".$branch_code."' ";
  	}

  	if($nik != NULL){
  		$where_extend .= " AND am.nik = '".$nik."' ";
  	}

  	$sql = "
  	SELECT
			am.nik,
			k.fullname,
			am.tanggal,
			am.masuk,
			(
			CASE 
			WHEN (am.masuk IS NULL OR am.masuk = '') THEN ''
			WHEN (am.masuk <= '08:00:00') THEN 'Tepat Waktu'
			WHEN (am.masuk <= '08:15:00') THEN '15 Menit'
			WHEN (am.masuk <= '08:30:00') THEN '30 Menit'
			WHEN (am.masuk > '08:30:00') THEN 'Lebih 30 Menit'
			ELSE ''
			END
			) AS masuk_keterangan,
			am.keluar,
			(
			CASE 
			WHEN (am.keluar IS NULL OR am.keluar = '') THEN ''
			WHEN (am.keluar < '16:30:00') THEN 'Kurang 30 Menit'
			WHEN (am.keluar >= '17:00:00') THEN 'Tepat Waktu'
			WHEN (am.keluar <= '16:45:00') THEN '15 Menit'
			WHEN (am.keluar >= '16:30:00') THEN '30 Menit'
			ELSE ''
			END
			) AS keluar_keterangan,
			am.keterangan
		FROM
			app_absensi_manual AS am
		LEFT JOIN app_karyawan AS k ON am.nik = k.nik
		LEFT JOIN app_karyawan_detail AS kd ON am.nik = kd.nik
		WHERE
			periode_from_date = '".$period_awal."'
			AND kd.status != '50'
			".$where_extend."
		ORDER BY kd.thru_branch, am.nik, am.tanggal ASC 
  	";

  	return $this->db->query($sql);
  }

  public function penambahan_lembur($nik, $periode_from_date)
  {
  	$sql = "select sum(lembur) from app_absensi_manual where nik = '' and periode_from_date = '' ";
    $this->db->set('lembur ', 'lembur::int - '.$jam.'::int', false);
    $this->db->where('nik', $nik);
    return $this->db->update('app_karyawan_detail');
  }

}



/* End of file M_core.php */
/* Location: ./application/models/M_core.php */