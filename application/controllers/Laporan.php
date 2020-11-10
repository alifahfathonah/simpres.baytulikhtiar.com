<?php if(!defined('BASEPATH')) exit('No scripts access allowed');

class Laporan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')) redirect('login');
		$this->load->database();
		$this->load->model('model_karyawan');
		$this->load->model('model_setup');
		$this->load->model('M_core', 'mcore');
		$this->load->library('PHPExcel', 'session');
		$this->load->library('Html2pdf');
		ini_set('max_execution_time', 0);
		set_time_limit(0);
		ini_set('memory_limit', '-1');
	}

	public function laporan_rekap_absensi_bulanan()
	{
		$session_data                            = $this->session->userdata('logged_in');
		$data['username']                        = $session_data['username'];
		$data['user']                            = $session_data['fullname'];
		$data['role_id']                         = $session_data['role_id'];
		$branch_user                             = $session_data['branch_code'];
		$data['periode_cutoff']                  = $this->model_setup->get_cutoff();
		$data['get_periode_from_absensi_manual'] = $this->model_karyawan->get_periode_from_absensi_manual();
		$data['get_lembur']                      = $this->mcore->data_karyawan_lembur();

		if($branch_user == '1' || $data['username']== "admin")
		{
			$data['get_branch'] = $this->model_karyawan->get_branch();
		}else{
			$data['get_branch'] = $this->model_karyawan->get_branch_by_user($branch_user);
		}

		$data['container'] = 'laporan/laporan_rekap_absensi_bulanan';

		$this->load->view('core', $data);			
	}

	public function laporan_absensi_karyawan()
	{
		$session_data        = $this->session->userdata('logged_in');
		$data['username']    = $session_data['username'];
		$data['user']        = $session_data['fullname'];
		$data['role_id']     = $session_data['role_id'];
		$branch_user         = $session_data['branch_code'];
		$data['branch_user'] = $session_data['branch_code'];


		if($branch_user == '1' || $data['username'] == "admin")
		{
			$data['get_branch'] = $this->model_karyawan->get_branch();
		}else
		{
			$data['get_branch'] = $this->model_karyawan->get_branch_by_user($branch_user);
		}

		$data['periode_cutoff']                  = $this->model_setup->get_cutoff();
		$data['get_periode_from_absensi_manual'] = $this->model_karyawan->get_periode_from_absensi_manual();
		$data['get_karyawan']                    = $this->model_karyawan->get_karyawan();
		$data['get_karyawan_by_branch']          = $this->model_karyawan->get_karyawan_by_branch($branch_user);

		$data['container']                       = 'laporan/laporan_absensi_karyawan';

		$this->load->view('core', $data);
	}


	public function action_laporan_rekap_absen_excel()
	{
    //////////////////////////////////////////////////////////////////////////////////////////////////// START SHEET 1 (RINCI)
		$row  = 3;
		$row2 = 11;
		$row3 = 11;

		$session_data = $this->session->userdata('logged_in');
		$username     = $session_data['username'];
		$branch_user  = $this->uri->segment(3);
		$periode      = $this->uri->segment(4);
		$awal         = trim(substr($periode, 0, 10));
		$awal_        = trim(substr($periode, 0, 10));
		$akhir        = substr($periode, 17, 27);

		$branch_code = $branch_user;

    /*if($username == "admin")
    {
      $tampil_nik = $this->model_karyawan->get_karyawan();
    }else{
      $tampil_nik = $this->model_karyawan->get_karyawan_by_branch($branch_user);
    }*/

    $tampil_nik    = $this->mcore->get_karyawan_by_branch($branch_user);
    $tampil_cutoff = $this->model_setup->get_cutoff();

    foreach($tampil_nik->result() as $values){
    	$branch_name = $values->thru_branch;

    	if($branch_code == "semua"){
    		$branch_name = 'SEMUA';
    	}

    	$nik         = $values->nik;

      //$get_presensi_by_nik = $this->model_karyawan->get_presensi_by_nik($nik);
    	$get_presensi_by_nik = $this->mcore->get_presensi_by_nik_2($nik);

    	foreach($get_presensi_by_nik->result() as $values)
    	{
    		$nik        = $values->nik;
    		$fullname   = $values->fullname;
    		$masuk      = $values->masuk;
    		$keluar     = $values->keluar;
    		$tanggal    = $values->tanggal;
    		$keterangan = $values->keterangan;

    		if($masuk == '')
    		{
    			if($keluar == '')
    			{
    				$masuk = '';
    				$keluar = '';
    			}
    			else
    			{
    				$masuk = '08:31:00';
    			}
    		}
    		else
    		{
    			if($keluar == '')
    			{
    				$keluar = '16:30:00';
    			}
    		}
    	}
    }

  	// Create new PHPExcel object
    $objPHPExcel = $this->phpexcel;
  	// Set document properties
    $objPHPExcel->getProperties()
    ->setCreator("HR_APP")
    ->setLastModifiedBy("HR_APP")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription("REPORT, generated using PHP classes.")
    ->setKeywords("REPORT")
    ->setCategory("Test result file");

    $objPHPExcel->setActiveSheetIndex(0); 

    $styleArray = array(
    	'borders' => array(
    		'outline' => array(
    			'style' => PHPExcel_Style_Border::BORDER_THIN,
    			'color' => array('rgb' => '000000'),
    		),
    	),
    );


    $objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->setTitle('Rinci');
    $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
    $objPHPExcel->getActiveSheet()->setCellValue("A1", "RINCI ABSENSI");
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->mergeCells('A2:I2');
    $objPHPExcel->getActiveSheet()->setCellValue("A2", "KARYAWAN KOPERASI BAYTUL IKHTIAR");
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
    $objPHPExcel->getActiveSheet()->setCellValue("A3", "CABANG/UNIT ".$branch_name);
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->mergeCells('A4:I4');
    $objPHPExcel->getActiveSheet()->setCellValue("A4", "PERIODE : ");
    $objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->mergeCells("A5:I5");
    $objPHPExcel->getActiveSheet()->setCellValue("A5", $awal_." s/d ".$akhir);
    $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->mergeCells('A7:A8');
    $objPHPExcel->getActiveSheet()->setCellValue("A7", "No");

    $objPHPExcel->getActiveSheet()->mergeCells('B7:B8');
    $objPHPExcel->getActiveSheet()->setCellValue("B7", "NIK");

    $objPHPExcel->getActiveSheet()->mergeCells('C7:C8');
    $objPHPExcel->getActiveSheet()->setCellValue("C7", "Nama");

    $objPHPExcel->getActiveSheet()->mergeCells('D7:D8');
    $objPHPExcel->getActiveSheet()->setCellValue("D7", "Tanggal");

    $objPHPExcel->getActiveSheet()->mergeCells('E7:H7');
    $objPHPExcel->getActiveSheet()->setCellValue("E7", "Absensi");
    $objPHPExcel->getActiveSheet()->setCellValue("E8", "Masuk");
    $objPHPExcel->getActiveSheet()->setCellValue("F8", "Keterangan");
    $objPHPExcel->getActiveSheet()->setCellValue("G8", "Keluar");
    $objPHPExcel->getActiveSheet()->setCellValue("H8", "Keterangan");

    $objPHPExcel->getActiveSheet()->mergeCells('I7:I8');
    $objPHPExcel->getActiveSheet()->setCellValue("I7", "Keterangan");

    $objPHPExcel->getActiveSheet()->getStyle('A1:A5')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1:A5')->getFont()->setSize(13);
    $objPHPExcel->getActiveSheet()->getStyle('A7:I8')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A7:I8')->getFont()->setSize(11);
    $objPHPExcel->getActiveSheet()->getStyle('A7:I8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getStyle('A7:A8')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('B7:B8')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('C7:C8')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('D7:D8')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('E7:F7')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('E8')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('F8')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('G7:H7')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('G8')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('H8')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('I7:I8')->applyFromArray($styleArray);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);

    $no   = 1;
    $t    = 0;
    $row3 = 9;
    $row4 = 10;

    //$get_laporan_detail        = $this->model_karyawan->get_absensi_manual_cutoff($awal, $akhir);
    $get_laporan_detail        = $this->mcore->get_absensi_manual_cutoff_branch_2($awal, $akhir, $branch_code)->result();
    $get_kategori_absen_masuk  = $this->model_karyawan->get_kategori_absen_masuk();
    $get_kategori_absen_keluar = $this->model_karyawan->get_kategori_absen_keluar();
    $get_libur                 = $this->model_karyawan->get_libur();


    foreach($get_kategori_absen_masuk as $key=>$data)
    {
    	$from_time_masuk[$key]  = $data->from_time;
    	$thru_time_masuk[$key]  = $data->thru_time;
    	$keterangan_masuk[$key] = $data->keterangan;
    }

    foreach($get_kategori_absen_keluar as $key=>$datas)
    {
    	$from_time_keluar[$key]  = $datas->from_time;
    	$thru_time_keluar[$key]  = $datas->thru_time;
    	$keterangan_keluar[$key] = $datas->keterangan;
    }


    foreach($get_laporan_detail as $values)
    {
      // $keterangan_masuk[0] == tepat waktu
      // $keterangan_masuk[1] == terlambat 15 menit
      // $keterangan_masuk[2] == terlambat 30 menit
      // $keterangan_masuk[3] == terlambat >30 menit
    	if($values->masuk >= $from_time_masuk[0] && $values->masuk <= $thru_time_masuk[0]){
    		$kategori_masuk = $keterangan_masuk[0];
    	}elseif($values->masuk >= $from_time_masuk[1] && $values->masuk <= $thru_time_masuk[1]){
    		$kategori_masuk = $keterangan_masuk[1];
    	}elseif($values->masuk >= $from_time_masuk[2] && $values->masuk <= $thru_time_masuk[2]){
    		$kategori_masuk = $keterangan_masuk[2];
    	}elseif($values->masuk >= $from_time_masuk[3] && $values->masuk <= $thru_time_masuk[3]){
    		$kategori_masuk = $keterangan_masuk[3];
    	}else{
    		$kategori_masuk = '';
    	}

      // $keterangan_keluar[0] == terlambat >30 menit
      // $keterangan_keluar[1] == terlambat 30 menit
      // $keterangan_keluar[2] == terlambat 15 menit
      // $keterangan_keluar[3] == tepat waktu
    	if($values->keluar >= $from_time_keluar[0] && $values->keluar <= $thru_time_keluar[0]){
    		$kategori_keluar = $keterangan_keluar[0];
    	}elseif($values->keluar >= $from_time_keluar[1] && $values->keluar <= $thru_time_keluar[1]){
    		$kategori_keluar = $keterangan_keluar[1];
    	}elseif($values->keluar >= $from_time_keluar[2] && $values->keluar <= $thru_time_keluar[2]){
    		$kategori_keluar = $keterangan_keluar[2];
    	}elseif($values->keluar >= $from_time_keluar[3] && $values->keluar <= $thru_time_keluar[3]){
    		$kategori_keluar = $keterangan_keluar[3];
    	}else{
    		$kategori_keluar = '';
    	}

    	$objPHPExcel->getActiveSheet()->setCellValue("A".$row3, $no);
    	$objPHPExcel->getActiveSheet()->setCellValue("B".$row3, $values->nik);
    	$objPHPExcel->getActiveSheet()->setCellValue("C".$row3, $values->fullname);
    	$objPHPExcel->getActiveSheet()->setCellValue("D".$row3, $values->tanggal);
    	$objPHPExcel->getActiveSheet()->setCellValue("E".$row3, $values->masuk);
      $objPHPExcel->getActiveSheet()->setCellValue("F".$row3, $kategori_masuk); // penetuannya dari array di atas
      $objPHPExcel->getActiveSheet()->setCellValue("G".$row3, $values->keluar);
      $objPHPExcel->getActiveSheet()->setCellValue("H".$row3, $kategori_keluar);
      $objPHPExcel->getActiveSheet()->setCellValue("I".$row3, $values->keterangan);

      $objPHPExcel->getActiveSheet()->getStyle('A'.$row3.':A'.$row3)->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('B'.$row3.':B'.$row3)->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('C'.$row3.':C'.$row3)->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('D'.$row3.':D'.$row3)->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('E'.$row3.':E'.$row3)->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('F'.$row3.':F'.$row3)->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('G'.$row3.':G'.$row3)->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('H'.$row3.':H'.$row3)->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('I'.$row3.':I'.$row3)->applyFromArray($styleArray);

      $objPHPExcel->getActiveSheet()
      ->getStyle('A'.$row3.':I'.$row3)
      ->getAlignment()
      ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

      $row3++;
      $no++;
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////// END SHEET 1 (RINCI)


    //////////////////////////////////////////////////////////////////////////////////////////////////// START SHEET 2 (REKAP)
    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(1);

    $objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->setTitle('Rekap');
    $objPHPExcel->getActiveSheet()->mergeCells('A1:X1');
    $objPHPExcel->getActiveSheet()->setCellValue("A1", "REKAPITULASI ABSENSI");
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->mergeCells('A2:X2');
    $objPHPExcel->getActiveSheet()->setCellValue("A2", "KARYAWAN KOPERASI BAYTUL IKHTIAR");
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->mergeCells('A3:X3');
    $objPHPExcel->getActiveSheet()->setCellValue("A3", "CABANG/UNIT ".$branch_name);
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->mergeCells('A4:X4');
    $objPHPExcel->getActiveSheet()->setCellValue("A4", "PERIODE : ");
    $objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->mergeCells("A5:X5");
    $objPHPExcel->getActiveSheet()->setCellValue("A5", $awal_." s/d ".$akhir);
    $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->mergeCells('A7:A10');
    $objPHPExcel->getActiveSheet()->setCellValue("A7", "No");

    $objPHPExcel->getActiveSheet()->mergeCells('B7:B10');
    $objPHPExcel->getActiveSheet()->setCellValue("B7", "Nama");

    $objPHPExcel->getActiveSheet()->mergeCells('C7:C10');
    $objPHPExcel->getActiveSheet()->setCellValue("C7", "Posisi");

    $objPHPExcel->getActiveSheet()->mergeCells('D7:D10');
    $objPHPExcel->getActiveSheet()->setCellValue("D7", "Cabang");

    $objPHPExcel->getActiveSheet()->mergeCells('E7:O8');
    $objPHPExcel->getActiveSheet()->setCellValue("E7", "Perhitungan Kehadiran");

    $objPHPExcel->getActiveSheet()->mergeCells('E9:E10');
    $objPHPExcel->getActiveSheet()->setCellValue("E9", "Jumlah");

    $objPHPExcel->getActiveSheet()->mergeCells('F9:F10');
    $objPHPExcel->getActiveSheet()->setCellValue("F9", "L"); // LIBUR

    $objPHPExcel->getActiveSheet()->mergeCells('G9:G10');
    $objPHPExcel->getActiveSheet()->setCellValue("G9", "H"); // HADIR

    $objPHPExcel->getActiveSheet()->mergeCells('H9:H10');
    $objPHPExcel->getActiveSheet()->setCellValue("H9", "TLK"); // TUGAS LUAR KOTA

    $objPHPExcel->getActiveSheet()->mergeCells('I9:I10');
    $objPHPExcel->getActiveSheet()->setCellValue("I9", "C"); // CUTI

    $objPHPExcel->getActiveSheet()->mergeCells('J9:J10');
    $objPHPExcel->getActiveSheet()->setCellValue("J9", "CK"); // ?

    $objPHPExcel->getActiveSheet()->mergeCells('K9:K10');
    $objPHPExcel->getActiveSheet()->setCellValue("K9", "DnL"); // DINAS LUAR KOTA

    $objPHPExcel->getActiveSheet()->mergeCells('L9:L10');
    $objPHPExcel->getActiveSheet()->setCellValue("L9", "SD"); // ?

    $objPHPExcel->getActiveSheet()->mergeCells('M9:M10');
    $objPHPExcel->getActiveSheet()->setCellValue("M9", "I"); // IZIN

    $objPHPExcel->getActiveSheet()->mergeCells('N9:N10');
    $objPHPExcel->getActiveSheet()->setCellValue("N9", "LTG"); // LUAR TANGGUNGAN

    $objPHPExcel->getActiveSheet()->mergeCells('O9:O10');
    $objPHPExcel->getActiveSheet()->setCellValue("O9", "HK"); // HADIR KARYAWAN

    $objPHPExcel->getActiveSheet()->mergeCells('P7:Z7');
    $objPHPExcel->getActiveSheet()->setCellValue("P7", "Presensi");
    $objPHPExcel->getActiveSheet()->mergeCells('P8:T8');
    $objPHPExcel->getActiveSheet()->setCellValue("P8", "Kedatangan");
    $objPHPExcel->getActiveSheet()->setCellValue("P9", "Tepat Waktu");
    $objPHPExcel->getActiveSheet()->setCellValue("P10", "s/d 08.00");
    $objPHPExcel->getActiveSheet()->setCellValue("Q9", "Kurang 15mnt");
    $objPHPExcel->getActiveSheet()->setCellValue("Q10", "08.01 s/d 08.15");
    $objPHPExcel->getActiveSheet()->setCellValue("R9", "s/d 30mnt");
    $objPHPExcel->getActiveSheet()->setCellValue("R10", "08.16 s/d 08.30");
    $objPHPExcel->getActiveSheet()->setCellValue("S9", "Lbh dr 30mnt");
    $objPHPExcel->getActiveSheet()->setCellValue("S10", "08.31 s/d up");
    $objPHPExcel->getActiveSheet()->setCellValue("T9", "Total Datang");
    $objPHPExcel->getActiveSheet()->setCellValue("T10", "Hr");
    $objPHPExcel->getActiveSheet()->mergeCells('U8:Y8');
    $objPHPExcel->getActiveSheet()->setCellValue("U8", "Kepulangan");
    $objPHPExcel->getActiveSheet()->setCellValue("U9", "Tepat Waktu");
    $objPHPExcel->getActiveSheet()->setCellValue("U10", "17 s/d up");
    $objPHPExcel->getActiveSheet()->setCellValue("V9", "Kurang 15mnt");
    $objPHPExcel->getActiveSheet()->setCellValue("V10", "16.59 s/d 16.45");
    $objPHPExcel->getActiveSheet()->setCellValue("W9", "s/d 30mnt");
    $objPHPExcel->getActiveSheet()->setCellValue("W10", "16.44 s/d 16.31");
    $objPHPExcel->getActiveSheet()->setCellValue("X9", "Lbh dr 30mnt");
    $objPHPExcel->getActiveSheet()->setCellValue("X10", "sblm 16.30");
    $objPHPExcel->getActiveSheet()->setCellValue("Y9", "Total Pulang");
    $objPHPExcel->getActiveSheet()->setCellValue("Y10", "Hr");
    $objPHPExcel->getActiveSheet()->setCellValue("Z8", "Lembur");
    $objPHPExcel->getActiveSheet()->mergeCells('Z9:Z10');
    $objPHPExcel->getActiveSheet()->setCellValue("Z9", "Total Jam");


    $objPHPExcel->getActiveSheet()->getStyle('A1:A5')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1:A5')->getFont()->setSize(13);
    $objPHPExcel->getActiveSheet()->getStyle('A7:Z10')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A7:Z10')->getFont()->setSize(11);
    $objPHPExcel->getActiveSheet()->getStyle('A7:Z10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getStyle('A7:A10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('B7:B10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('C7:C10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('D7:D10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('E7:O8')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('E9:E10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('F9:F10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('G9:G10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('H9:H10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('I9:I10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('J9:J10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('K9:K10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('L9:L10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('M9:M10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('N9:N10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('O9:O10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('P7:Y7')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('P8:T8')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('U8:Y8')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('P9')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('Q9')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('R9')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('S9')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('T9')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('U9')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('V9')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('W9')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('X9')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('Y9')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('P10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('Q10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('R10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('S10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('T10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('U10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('V10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('W10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('X10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('Y10')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('Z10')->applyFromArray($styleArray);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(27);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(27);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(7);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(6);
    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(14);

    /* --------------------------------------------------------- */

    $no = 1;

    if($branch_code == "semua"){
    	$get_laporan = $this->model_karyawan->get_laporan('semua', $awal, $akhir);
    }else{
    	$get_laporan = $this->model_karyawan->get_laporan($branch_code, $awal, $akhir);
    }

    //$get_laporan = $this->model_karyawan->get_laporan($branch_code);

    foreach($get_laporan as $values)
    {
    	$nik       = $values->nik;
    	$total_jams = $this->mcore->data_karyawan_lembur_by_nik($nik, $awal, $akhir);
    	$total_jam = 0;
    	if($total_jams->num_rows() === NULL){
    		$total_jam = 0;
    	}else{
    		foreach ($total_jams->result() as $key) {
    			$total_jam += $key->total_jam;
    		}
    	}
    	$bulan     = date('m');

    	$get_count_ltg   = $this->model_karyawan->get_count_ltg_date($nik, $awal, $akhir);
    	$get_count_cuti  = $this->model_karyawan->get_count_cuti_date($nik, $awal, $akhir);
    	$get_count_libur = $this->model_karyawan->get_count_libur_date($awal, $akhir);
    	$get_count_sakit = $this->model_karyawan->get_count_sakit_date($nik, $awal, $akhir);
    	$get_count_ijin  = $this->model_karyawan->get_count_ijin_date($nik, $awal, $akhir);
    	$get_count_hadir = $this->model_karyawan->get_count_hadir_date($nik, $awal, $akhir);
    	$get_count_tlk   = $this->model_karyawan->get_count_tlk_date($nik, $awal, $akhir);
    	$get_count_dnl   = $this->model_karyawan->get_count_dnl_date($nik, $awal, $akhir);
      //$get_count_ck    = $this->model_karyawan->get_count_ck_date($nik, $awal, $akhir);
    	$get_count_ck    = $this->mcore->get_count_ck_date($nik, $awal, $akhir);

    	$sum_masuk  = $values->m_tepat_waktu + $values->m_telat_1 + $values->m_telat_2 + $values->m_telat_3;
    	$sum_keluar = $values->k_tepat_waktu + $values->k_telat_1 + $values->k_telat_2 + $values->k_telat_3;
    	$sum_ltg    = $get_count_ltg - $get_count_tlk - $get_count_cuti - $get_count_ck - $get_count_dnl - $get_count_sakit - $get_count_ijin;

    	$objPHPExcel->getActiveSheet()->setCellValue("A".$row2, $no);
    	$objPHPExcel->getActiveSheet()->setCellValue("B".$row2, $values->fullname);
    	$objPHPExcel->getActiveSheet()->setCellValue("C".$row2, $values->position);
    	$objPHPExcel->getActiveSheet()->setCellValue("D".$row2, $values->branch);
    	$objPHPExcel->getActiveSheet()->setCellValue("E".$row2, "=SUM(F".$row2.":N".$row2.")");
    	$objPHPExcel->getActiveSheet()->setCellValue("F".$row2, $get_count_libur);
    	$objPHPExcel->getActiveSheet()->setCellValue("G".$row2, $get_count_hadir);
    	$objPHPExcel->getActiveSheet()->setCellValue("H".$row2, $get_count_tlk);
    	$objPHPExcel->getActiveSheet()->setCellValue("I".$row2, $get_count_cuti);
    	$objPHPExcel->getActiveSheet()->setCellValue("J".$row2, $get_count_ck);
    	$objPHPExcel->getActiveSheet()->setCellValue("K".$row2, $get_count_dnl);
    	$objPHPExcel->getActiveSheet()->setCellValue("L".$row2, $get_count_sakit);
    	$objPHPExcel->getActiveSheet()->setCellValue("M".$row2, $get_count_ijin);
    	$objPHPExcel->getActiveSheet()->setCellValue("N".$row2, $sum_ltg);
    	$objPHPExcel->getActiveSheet()->setCellValue("O".$row2, "=G".$row2."+H".$row2."+K".$row2."");
    	$objPHPExcel->getActiveSheet()->setCellValue("P".$row2, $values->m_tepat_waktu);
    	$objPHPExcel->getActiveSheet()->setCellValue("Q".$row2, $values->m_telat_1);
    	$objPHPExcel->getActiveSheet()->setCellValue("R".$row2, $values->m_telat_2);
    	$objPHPExcel->getActiveSheet()->setCellValue("S".$row2, $values->m_telat_3);
    	$objPHPExcel->getActiveSheet()->setCellValue("T".$row2, $sum_masuk);
    	$objPHPExcel->getActiveSheet()->setCellValue("U".$row2, $values->k_tepat_waktu);
    	$objPHPExcel->getActiveSheet()->setCellValue("V".$row2, $values->k_telat_1);
    	$objPHPExcel->getActiveSheet()->setCellValue("W".$row2, $values->k_telat_2);
    	$objPHPExcel->getActiveSheet()->setCellValue("X".$row2, $values->k_telat_3);
    	$objPHPExcel->getActiveSheet()->setCellValue("Y".$row2, $sum_keluar);
    	$objPHPExcel->getActiveSheet()->setCellValue("Z".$row2, $total_jam);


    	$objPHPExcel->getActiveSheet()->getStyle('A'.$row2.':A'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':B'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('C'.$row2.':C'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('D'.$row2.':D'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('E'.$row2.':E'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('F'.$row2.':F'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('G'.$row2.':G'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('H'.$row2.':H'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('I'.$row2.':I'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('J'.$row2.':J'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('K'.$row2.':K'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('L'.$row2.':L'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('M'.$row2.':M'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('N'.$row2.':N'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('O'.$row2.':O'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('P'.$row2.':P'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('Q'.$row2.':Q'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('R'.$row2.':R'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('S'.$row2.':S'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('T'.$row2.':T'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('U'.$row2.':U'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('V'.$row2.':V'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('W'.$row2.':W'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('X'.$row2.':X'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('Y'.$row2.':Y'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('Z'.$row2.':Z'.$row2)->applyFromArray($styleArray);


    	$objPHPExcel->getActiveSheet()
    	->getStyle('A'.$row2.':Z'.$row2)
    	->getAlignment()
    	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    	$row2++;
    	$no++;
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////// END SHEET 2 (REKAP)

    ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Laporan_Rekap_Absensi_Periode_'.$awal.'_sd_'.$akhir.'.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    //$objWriter->save('test.xlsx');

  }


  /* last adams */
  public function action_laporan_absen_excel()
  {
		$branch_code  = $this->uri->segment(3);
		$nik          = $this->uri->segment(4);
		$period       = urldecode($this->uri->segment(5));
		$period_awal  = trim(substr($period, 0, 10));
		$period_akhir = trim(substr($period, 12, 11));

		if($branch_code == '99999'){
  		$branch_name = "Semua Cabang/Unit";
  	}else{
  		$branch_name = $this->mcore->get_where('app_parameter', ['parameter_group' => 'cabang', 'parameter_id' => $branch_code])->row()->description;
  	}

		$arr_karyawans = $this->mcore->get_all_data_e_resign_2('app_karyawan', $branch_code, $nik);

		// SHEET 1
		
		$obj = $this->phpexcel;

		# MAIN TITLE
  	$obj->setActiveSheetIndex(0);
  	$obj->getActiveSheet()->setTitle('Form Verifikasi');

  	$obj->getActiveSheet()->getColumnDimension('A')->setWidth(10);
  	$obj->getActiveSheet()->getColumnDimension('B')->setWidth(10);
  	$obj->getActiveSheet()->getColumnDimension('C')->setWidth(10);
  	$obj->getActiveSheet()->getColumnDimension('D')->setWidth(20);

  	$styleMainTitle = array(
  		'font'  => array(
  			'bold'  => true,
  			'color' => array('rgb' => '000000'),
  			'size'  => 10,
  			'name'  => 'Verdana'
  		)
  	);

  	$styleTableTitle = array(
  		'font'  => array(
  			'bold'  => true,
  			'color' => array('rgb' => '000000'),
  			'size'  => 8,
  			'name'  => 'Verdana'
  		),
  		'borders' => array(
  			'outline' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN,
  				'color' => array('rgb' => '000000')
  			)
  		),
  		'alignment' => array(
  			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  		)
  	);

  	$styleTableContent = array(
  		'font'  => array(
  			'bold'  => FALSE,
  			'color' => array('rgb' => '000000'),
  			'size'  => 8,
  			'name'  => 'Verdana'
  		),
  		'borders' => array(
  			'outline' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN,
  				'color' => array('rgb' => '000000')
  			)
  		),
  		'alignment' => array(
  			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  		)
  	);

  	$styleTableFooter = array(
  		'font'  => array(
  			'bold'  => TRUE,
  			'color' => array('rgb' => '000000'),
  			'size'  => 9,
  			'name'  => 'Verdana'
  		),
  		'alignment' => array(
  			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  		)
  	);

  	$nstart = 1;
  	foreach ($arr_karyawans->result() as $arr_karyawan) {
			$nik      = $arr_karyawan->nik;
			$fullname = $arr_karyawan->fullname;

	  	$obj->getActiveSheet()
	  	->setCellValue('A'.$nstart, 'PRESENSI KARYAWAN') // A1
	  	->mergeCells('A'.$nstart.':D'.$nstart) // A1:D1
	  	->getStyle('A'.$nstart.':D'.$nstart)  // A1:D1
	  	->applyFromArray($styleMainTitle)
	  	->getAlignment()
	  	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	  	$nstart++;

	  	$obj->getActiveSheet()
	  	->setCellValue('A'.$nstart, 'KOPERASI BAYTUL IKHTIAR') // A2
	  	->mergeCells('A'.$nstart.':D'.$nstart)  // A2:D2
	  	->getStyle('A'.$nstart.':D'.$nstart) // A2:D2
	  	->applyFromArray($styleMainTitle)
	  	->getAlignment()
	  	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	  	$nstart++;

	  	$obj->getActiveSheet()
	  	->setCellValue('A'.$nstart, 'NAMA: '.$fullname) // A3
	  	->mergeCells('A'.$nstart.':D'.$nstart) // A3:D3
	  	->getStyle('A'.$nstart.':D'.$nstart) // A3:D3
	  	->applyFromArray($styleMainTitle)
	  	->getAlignment()
	  	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	  	$nstart++;
			# END MAIN TITLE

			# TABLE TITLE
	  	$ntemp = $nstart + 1;
	  	$obj->getActiveSheet()
	  	->setCellValue('A'.$nstart, 'Tanggal')  // A4
	  	->mergeCells('A'.$nstart.':A'.$ntemp) // A4:A4
	  	->getStyle('A'.$nstart.':A'.$ntemp)  // A4:A4
	  	->applyFromArray($styleTableTitle);

	  	$obj->getActiveSheet()
	  	->setCellValue('D'.$nstart, 'Keterangan')  // D4
	  	->mergeCells('D'.$nstart.':D'.$ntemp) // D4:D5
	  	->getStyle('D'.$nstart.':D'.$ntemp)  // D4:D5
	  	->applyFromArray($styleTableTitle);

	  	$obj->getActiveSheet()
	  	->setCellValue('B'.$nstart, 'Absensi')  // B4
	  	->mergeCells('B'.$nstart.':C'.$nstart)  // B4:C4
	  	->getStyle('B'.$nstart.':C'.$nstart)   // B4:C4
	  	->applyFromArray($styleTableTitle);

	  	$nstart++;

	  	$obj->getActiveSheet()
	  	->setCellValue('B'.$nstart, 'Masuk')  // B5
	  	->getStyle('B'.$nstart) // B5
	  	->applyFromArray($styleTableTitle);

	  	$obj->getActiveSheet()
	  	->setCellValue('C'.$nstart, 'Keluar')  // C5
	  	->getStyle('C'.$nstart)  // C5
	  	->applyFromArray($styleTableTitle);

	  	$nstart++;
			# END TABLE TITLE

			# TABLE CONTENT
	  	$arr_karyawans = $this->mcore->get_rinci_absensi($period_awal, $branch_code, $nik);

	  	foreach ($arr_karyawans->result() as $arr_karyawan) {
				# DEFINE
				$fullname   = $arr_karyawan->fullname;
				$tanggal    = $arr_karyawan->tanggal;
				$masuk      = $arr_karyawan->masuk;
				$keluar     = $arr_karyawan->keluar;
				$keterangan = $arr_karyawan->keterangan;
				# END DEFINE

	  		$obj->getActiveSheet()
	  		->setCellValue('A'.$nstart, $tanggal)
	  		->getStyle('A'.$nstart)
	  		->applyFromArray($styleTableContent);

	  		$obj->getActiveSheet()
	  		->setCellValue('B'.$nstart, $masuk)
	  		->getStyle('B'.$nstart)
	  		->applyFromArray($styleTableContent);

	  		$obj->getActiveSheet()
	  		->setCellValue('C'.$nstart, $keluar)
	  		->getStyle('C'.$nstart)
	  		->applyFromArray($styleTableContent);

	  		$obj->getActiveSheet()
	  		->setCellValue('D'.$nstart, $keterangan)
	  		->getStyle('D'.$nstart)
	  		->applyFromArray($styleTableContent);

	  		$nstart++;
	  	}
			# END TABLE CONTENT
			
			$obj->getActiveSheet()
	  	->setCellValue('A'.$nstart, '')
	  	->mergeCells('A'.$nstart.':D'.$nstart);
	  	
	  	$nstart++;

	  	$obj->getActiveSheet()
	  	->setCellValue('A'.$nstart, 'Membuat')
	  	->mergeCells('A'.$nstart.':B'.$nstart)
	  	->getStyle('A'.$nstart.':B'.$nstart)
	  	->applyFromArray($styleTableFooter);

	  	$obj->getActiveSheet()
	  	->setCellValue('D'.$nstart, 'Memeriksa')
	  	->getStyle('D'.$nstart)
	  	->applyFromArray($styleTableFooter);
	  	
	  	$nstart++;

	  	for ($i=0; $i < 4; $i++) { 
	  		$obj->getActiveSheet()
		  	->setCellValue('A'.$nstart, '')
		  	->mergeCells('A'.$nstart.':D'.$nstart);
	  		
	  		$nstart++;
	  	}

	  	$obj->getActiveSheet()
	  	->setCellValue('A'.$nstart, '__________')
	  	->mergeCells('A'.$nstart.':B'.$nstart)
	  	->getStyle('A'.$nstart.':B'.$nstart)
	  	->applyFromArray($styleTableFooter);

	  	$obj->getActiveSheet()
	  	->setCellValue('D'.$nstart, '__________')
	  	->getStyle('D'.$nstart)
	  	->applyFromArray($styleTableFooter);
	  	
	  	$nstart++;

	  	$obj->getActiveSheet()
	  	->setCellValue('A'.$nstart, '')
	  	->mergeCells('A'.$nstart.':D'.$nstart);
	  	
	  	$nstart++;
		}

		// END SHEET 1


		// OUTPUT
  	$filename = "Form_Verifikasi_".$period_awal."-".$period_akhir.".xlsx";

  	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  	header('Content-Disposition: attachment;filename="'.$filename.'"');
  	header('Cache-Control: max-age=0');

  	ob_end_clean();

  	$writer = PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
  	$writer->save('php://output');
  	exit;
    // END OUTPUT


  }

  public function action_laporan_rekap_absen_pdf()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$username     = $session_data['username'];
  	$role_id      = $session_data['role_id'];
  	$branch_user  = $this->uri->segment(3);
  	$periode      = $this->uri->segment(4);
  	$awal         = substr($periode, 0, 10);
  	$awal_        = substr($periode, 0, 10);
  	$akhir        = substr($periode, 17, 27);
  	$branch_code  = $branch_user;

  /*if($branch_code == "semua"){
    $tampil_nik = $this->model_karyawan->get_laporan('semua', $awal, $akhir);
  }else{
    $tampil_nik = $this->model_karyawan->get_laporan($branch_code, $awal, $akhir);
  }*/

  if($branch_user == 'semua')
  {
  	$tampil_nik = $this->model_karyawan->get_karyawan()->result();
  }else{
  	$tampil_nik = $this->model_karyawan->get_karyawan_by_branch($branch_user);      
  }

  foreach($tampil_nik as $values){
  	$branch_name = $values->thru_branch; 
  	if($branch_user != '1'){
  		$data['branch'] = "CABANG/UNIT ".$branch_name;
  	}else{
  		$data['branch'] = '';
  	}
  }

  ob_start();

  $config['full_tag_open']  = '<p>';
  $config['full_tag_close'] = '</p>';

  $data['branch_user'] = $branch_name;
  $data['periode']     = $awal.' s/d '.$akhir;
  //$get_laporan = $this->model_karyawan->get_laporan($branch_code);
  if($branch_code == "semua"){
  	$get_laporan = $this->model_karyawan->get_laporan('semua', $awal, $akhir);
  }else{
  	$get_laporan = $this->model_karyawan->get_laporan($branch_code, $awal, $akhir);
  }

  $no = 1;
  foreach($get_laporan as $values)
  {
  	$nik   = $values->nik;
  	$bulan = date('m');

  	$get_count_ltg   = $this->model_karyawan->get_count_ltg_date($nik, $awal, $akhir);
  	$get_count_cuti  = $this->model_karyawan->get_count_cuti_date($nik, $awal, $akhir);
  	$get_count_libur = $this->model_karyawan->get_count_libur_date($awal, $akhir);
  	$get_count_sakit = $this->model_karyawan->get_count_sakit_date($nik, $awal, $akhir);
  	$get_count_ijin  = $this->model_karyawan->get_count_ijin_date($nik, $awal, $akhir);
  	$get_count_hadir = $this->model_karyawan->get_count_hadir_date($nik, $awal, $akhir);
  	$get_count_tlk   = $this->model_karyawan->get_count_tlk_date($nik, $awal, $akhir);
  	$get_count_dnl   = $this->model_karyawan->get_count_dnl_date($nik, $awal, $akhir);
  	$get_count_ck    = $this->mcore->get_count_ck_date($nik, $awal, $akhir);
  	$get_count_lm    = $this->mcore->data_karyawan_lembur_by_nik($nik, $awal, $akhir);

  	($get_count_cuti == null)? $get_count_cuti = 0: $get_count_cuti = $get_count_cuti;
  	($get_count_sakit == null)? $get_count_sakit = 0: $get_count_sakit = $get_count_sakit;
  	($get_count_ijin == null)? $get_count_ijin = 0: $get_count_ijin = $get_count_ijin;
  	($get_count_tlk == null)? $get_count_tlk = 0: $get_count_tlk = $get_count_tlk;
  	($get_count_dnl == null)? $get_count_dnl = 0: $get_count_dnl = $get_count_dnl;
  	($get_count_ck == null)? $get_count_ck = 0: $get_count_ck = $get_count_ck;

  	$total_jam = 0;
  	if($get_count_lm->num_rows() == 0){
  		$total_jam = 0;
  	}else{
  		foreach ($get_count_lm->result() as $key) {
  			$total_jam += $key->total_jam;
  		}
  	}

  	$sum_masuk  = $values->m_tepat_waktu + $values->m_telat_1 + $values->m_telat_2 + $values->m_telat_3;
  	$sum_keluar = $values->k_tepat_waktu + $values->k_telat_1 + $values->k_telat_2 + $values->k_telat_3;

  	$jumlah = $get_count_libur + $get_count_hadir + $get_count_tlk + $get_count_cuti + $get_count_ck + $get_count_dnl + $get_count_sakit + $get_count_ijin + $get_count_ltg;

  	$hk = $get_count_hadir + $get_count_tlk + $get_count_dnl;

  	$sum_masuk  = $values->m_tepat_waktu + $values->m_telat_1 + $values->m_telat_2 + $values->m_telat_3;
  	$sum_keluar = $values->k_tepat_waktu + $values->k_telat_1 + $values->k_telat_2 + $values->k_telat_3;
  	$sum_ltg    = $get_count_ltg - $get_count_tlk - $get_count_cuti - $get_count_ck - $get_count_dnl - $get_count_sakit - $get_count_ijin;

  	$arr[] = array(
  		'no'              => $no,
  		'fullname'        => $values->fullname,
  		'position'        => $values->position,
  		'branch'          => $values->branch,
  		'jumlah'          => $jumlah,
  		'get_count_libur' => $get_count_libur,
  		'get_count_hadir' => $get_count_hadir,
  		'get_count_tlk'   => $get_count_tlk,
  		'get_count_cuti'  => $get_count_cuti,
  		'get_count_ck'    => $get_count_ck,
  		'get_count_dnl'   => $get_count_dnl,
  		'get_count_sakit' => $get_count_sakit,
  		'get_count_ijin'  => $get_count_ijin,
  		'ltg'             => $sum_ltg,
  		'hk'              => $hk,
  		'm_tepat_waktu'   => $values->m_tepat_waktu,
  		'm_telat_1'       => $values->m_telat_1,
  		'm_telat_2'       => $values->m_telat_2,
  		'm_telat_3'       => $values->m_telat_3,
  		'sum_masuk'       => $sum_masuk,
  		'k_tepat_waktu'   => $values->k_tepat_waktu,
  		'k_telat_1'       => $values->k_telat_1,
  		'k_telat_2'       => $values->k_telat_2,
  		'k_telat_3'       => $values->k_telat_3,
  		'sum_keluar'      => $sum_keluar,
  		'total_jam'       => $total_jam
  	);

  	$no++;
  }

  /*foreach ($get_laporan as $key) {
    $nik           = $key->nik;
    $fullname      = $key->fullname;
    $position      = $key->position;
    $m_tepat_waktu = $key->m_tepat_waktu;
    $m_telat_1     = $key->m_telat_1;
    $m_telat_2     = $key->m_telat_2;
    $m_telat_3     = $key->m_telat_3;
    $k_tepat_waktu = $key->k_tepat_waktu;
    $k_telat_1     = $key->k_telat_1;
    $k_telat_2     = $key->k_telat_2;
    $k_telat_3     = $key->k_telat_3;
    $total_jam     = $this->mcore->data_karyawan_lembur_by_nik($nik);
    $total_jams    = 0;
    if($total_jam->num_rows() != NULL){
      foreach ($total_jam->result() as $key) {
        $total_jams += $key->total_jam;
      }
    }

    $nested[] = [
      'nik'           => $nik,
      'fullname'      => $fullname,
      'position'      => $position,
      'm_tepat_waktu' => $m_tepat_waktu,
      'm_telat_1'     => $m_telat_1,
      'm_telat_2'     => $m_telat_2,
      'm_telat_3'     => $m_telat_3,
      'k_tepat_waktu' => $k_tepat_waktu,
      'k_telat_1'     => $k_telat_1,
      'k_telat_2'     => $k_telat_2,
      'k_telat_3'     => $k_telat_3,
      'total_jam'     => $total_jams
    ];
  }*/

  $data['arr'] = $arr;

  $content = $this->load->view('laporan/laporan_rekap_absensi_bulanan_pdf',$data, TRUE);

  try {
  	$html2pdf = new HTML2PDF('L', 'A4', 'en', true, 'UTF-8', 5);
  	$html2pdf->pdf->SetDisplayMode('fullpage');
  	$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
  	$html2pdf->Output('Laporan Rekap Absensi Bulanan Periode '.$awal.' s/d '.$akhir.' Cabang/Unit '.$branch_name.'.pdf');
  } catch(HTML2PDF_exception $e) {
  	echo $e;
  	exit;
  }
  
}

function action_laporan_absen_pdf()
{
	$session_data         = $this->session->userdata('logged_in');
	$username             = $session_data['username'];
	$branch_code          = $this->uri->segment(3);
	$nik                  = $this->uri->segment(4);
	$periode              = $this->uri->segment(5);
	$awal                 = substr($periode, 0, 10);
	$awal_                = substr($periode, 0, 10);
	$akhir                = substr($periode, 17, 27);
	$data['arr_karyawan'] = $this->mcore->get_all_data_e_resign_2('app_karyawan', $branch_code, $nik);

	$table = 'app_parameter';
	$where = [
		'parameter_group' => 'cabang',
		'parameter_id'    => $branch_code
	];
	$arr_par = $this->mcore->get_where($table, $where);
	foreach ($arr_par->result() as $key) {
		$branch_name = $key->description;
	}

  //ob_start();

	$config['full_tag_open']  = '<div>';
	$config['full_tag_close'] = '</div>';

  //$data['branch_user'] = $branch_name;
	$data['periode']     = $awal.' s/d '.$akhir;
  //$data['fullname']    = $fullname;
    //$data['get_laporan'] = $this->model_karyawan->get_laporan_by_nik($nik);
	$data['get_laporan'] = $this->model_karyawan->get_laporan_by_nik_date_2($nik, $awal, $akhir, $branch_code);
	$data['total_karyawan'] = $data['arr_karyawan']->num_rows();
	$data['get_lembur'] = $this->mcore->data_karyawan_lembur_by_nik($nik, $awal, $akhir);
  //$data['get_libur']   = $this->model_karyawan->get_libur();


	$content = $this->load->view('laporan/laporan_absensi_bulanan_pdf', $data, TRUE);
  #exit();

      //$content = ob_get_clean();

	try {
		$html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', 5);
		$html2pdf->pdf->SetDisplayMode('fullpage');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output('Laporan Rekap Absensi Bulanan Periode '.$awal.' s/d '.$akhir.' Cabang/Unit '.$branch_name.'.pdf');
	} catch(HTML2PDF_exception $e) {
		echo $e;
		exit;
	}
}

function get_karyawan_by_branch()
{
	$branch_user = $this->input->post('branch');
	$data = $this->model_karyawan->get_karyawan_by_branch($branch_user);

	echo json_encode($data);
}

public function laporan_list_karyawan()
{
	$session_data     = $this->session->userdata('logged_in');
	$data['username'] = $session_data['username'];
	$data['user']     = $session_data['fullname'];
	$data['role_id']  = $session_data['role_id'];
	$branch_user      = $session_data['branch_code'];

	$data['periode_cutoff']                  = $this->model_setup->get_cutoff();
	$data['get_periode_from_absensi_manual'] = $this->model_karyawan->get_periode_from_absensi_manual();

	if($branch_user == '1' || $data['username'] == "admin")
	{
		$data['get_branch'] = $this->model_karyawan->get_branch();
	}else{
		$data['get_branch'] = $this->model_karyawan->get_branch_by_user($branch_user);
	}

	$data['container'] = 'laporan/laporan_list_karyawan';

	$this->load->view('core', $data);			
}

public function action_laporan_list_karyawan_excel(){
	$row  = 3;
	$row2 = 6;
	$row3 = 11;

	$session_data = $this->session->userdata('logged_in');
	$username     = $session_data['username'];
	$branch_user  = $this->uri->segment(3);

    /*if($branch_user == '1' || $username == "admin"){
      $tampil_nik = $this->model_karyawan->get_karyawan()->result();
    }else{
      $tampil_nik = $this->model_karyawan->get_karyawan_by_branch($branch_user);			
    }*/
    
    $tampil_nik = $this->model_karyawan->get_karyawan_by_branch($branch_user);      


    foreach($tampil_nik as $values){
    	$branch_name = $values->thru_branch; 
    	if($branch_user != '1'){
    		$branch = "CABANG/UNIT ".$branch_name;
    	}else{
    		$branch = '';
    	}
    }

		// Create new PHPExcel object
    $objPHPExcel = $this->phpexcel;

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("HR_APP")
    ->setLastModifiedBy("HR_APP")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription("REPORT, generated using PHP classes.")
    ->setKeywords("REPORT")
    ->setCategory("Test result file");

    $objPHPExcel->setActiveSheetIndex(0); 

    $styleArray = array(
    	'borders' => array(
    		'outline' => array(
    			'style' => PHPExcel_Style_Border::BORDER_THIN,
    			'color' => array('rgb' => '000000'),
    		),
    	),
    );

    $objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
    $objPHPExcel->getActiveSheet()->setCellValue("B1", "DAFTAR PENGURUS DAN KARYAWAN");
    $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->mergeCells('B2:E2');
    $objPHPExcel->getActiveSheet()->setCellValue("B2", "KOPERASI BAYTUL IKHTIAR ".$branch);
    $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->mergeCells('B4:B5');
    $objPHPExcel->getActiveSheet()->setCellValue("B4", "No");
    $objPHPExcel->getActiveSheet()->mergeCells('C4:C5');
    $objPHPExcel->getActiveSheet()->setCellValue("C4", "NIK");
    $objPHPExcel->getActiveSheet()->mergeCells('D4:D5');
    $objPHPExcel->getActiveSheet()->setCellValue("D4", "Nama");
    $objPHPExcel->getActiveSheet()->mergeCells('E4:E5');
    $objPHPExcel->getActiveSheet()->setCellValue("E4", "Tempat Tanggal Lahir");
    $objPHPExcel->getActiveSheet()->mergeCells('F4:F5');
    $objPHPExcel->getActiveSheet()->setCellValue("F4", "Alamat");
    $objPHPExcel->getActiveSheet()->mergeCells('G4:G5');
    $objPHPExcel->getActiveSheet()->setCellValue("G4", "No. KTP");
    $objPHPExcel->getActiveSheet()->mergeCells('H4:H5');
    $objPHPExcel->getActiveSheet()->setCellValue("H4", "Jenis Kelamin");
    $objPHPExcel->getActiveSheet()->mergeCells('I4:I5');
    $objPHPExcel->getActiveSheet()->setCellValue("I4", "Status Pernikahan");
    $objPHPExcel->getActiveSheet()->mergeCells('J4:J5');
    $objPHPExcel->getActiveSheet()->setCellValue("J4", "Perubahan Status Pernikahan");
    $objPHPExcel->getActiveSheet()->mergeCells('K4:K5');
    $objPHPExcel->getActiveSheet()->setCellValue("K4", "Pendidikan Terakhir");
    $objPHPExcel->getActiveSheet()->mergeCells('L4:L5');
    $objPHPExcel->getActiveSheet()->setCellValue("L4", "No HP");
    $objPHPExcel->getActiveSheet()->mergeCells('M4:M5');
    $objPHPExcel->getActiveSheet()->setCellValue("M4", "Mulai Bergabung");
    $objPHPExcel->getActiveSheet()->mergeCells('N4:N5');
    $objPHPExcel->getActiveSheet()->setCellValue("N4", "Status Karyawan");
    $objPHPExcel->getActiveSheet()->mergeCells('O4:O5');
    $objPHPExcel->getActiveSheet()->setCellValue("O4", "Posisi Awal");
    $objPHPExcel->getActiveSheet()->mergeCells('P4:P5');
    $objPHPExcel->getActiveSheet()->setCellValue("P4", "Posisi Terakhir");
    $objPHPExcel->getActiveSheet()->mergeCells('Q4:Q5');
    $objPHPExcel->getActiveSheet()->setCellValue("Q4", "Cabang/Unit Awal");
    $objPHPExcel->getActiveSheet()->mergeCells('R4:R5');
    $objPHPExcel->getActiveSheet()->setCellValue("R4", "Cabang/Unit Terakhir");
    $objPHPExcel->getActiveSheet()->mergeCells('S4:S5');
    $objPHPExcel->getActiveSheet()->setCellValue("S4", "Pengangkatan Karyawan Tetap");
    $objPHPExcel->getActiveSheet()->mergeCells('T4:T5');
    $objPHPExcel->getActiveSheet()->setCellValue("T4", "Resign");

    $objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->setSize(13);
    $objPHPExcel->getActiveSheet()->getStyle('B4:T5')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('B4:T5')->getFont()->setSize(11);
    $objPHPExcel->getActiveSheet()->getStyle('B4:T5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getStyle('B4:B5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('C4:C5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('D4:D5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('E4:E5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('F4:F5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('G4:G5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('H4:H5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('I4:I5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('J4:J5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('K4:K5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('L4:L5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('M4:M5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('N4:N5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('O4:O5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('P4:P5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('Q4:Q5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('R4:R5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('S4:S5')->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('T4:T5')->applyFromArray($styleArray);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(3);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(23);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(22);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(23);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(17);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(26);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(19);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(13);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(16);
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(19);
    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(19);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(16);
    $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(24);
    $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(24);

    /* --------------------------------------------------------- */

    $no = 1;

    $get_laporan_list_karyawan = $this->model_karyawan->get_laporan_list_karyawan($branch_user);


    foreach($get_laporan_list_karyawan as $values)
    {
    	if($values->sarjana != '-'){$pendidikan = $values->sarjana;}else if($values->diploma != '-'){$pendidikan = $values->diploma;}else if($values->sma != '-'){$pendidikan = $values->sma;}else if($values->smp != '-'){$pendidikan = $values->smp;}else if($values->sd != '-'){$pendidikan = $values->sd;}else{$pendidikan = '-';}

    	if($values->from_pernikahan == '0'){$from_pernikahan = 'Lajang';}else if($values->from_pernikahan == '1'){$from_pernikahan = 'Menikah';}else if($values->from_pernikahan == '3'){$from_pernikahan = 'Lainnya';}else{$from_pernikahan = '';}

    	if($values->thru_pernikahan == '0'){$thru_pernikahan = 'Lajang';}else if($values->thru_pernikahan == '1'){$thru_pernikahan = 'Menikah';}else if($values->thru_pernikahan == '3'){$thru_pernikahan = 'Lainnya';}else{$thru_pernikahan = '';}

    	$objPHPExcel->getActiveSheet()->setCellValue("B".$row2, $no);
    	$objPHPExcel->getActiveSheet()->setCellValue("C".$row2, $values->nik);
    	$objPHPExcel->getActiveSheet()->setCellValue("D".$row2, $values->fullname);
    	$objPHPExcel->getActiveSheet()->setCellValue("E".$row2, $values->tmp_lahir." ".$values->tgl_lahir);
    	$objPHPExcel->getActiveSheet()->setCellValue("F".$row2, $values->alamat);
    	$objPHPExcel->getActiveSheet()->setCellValue("G".$row2, $values->no_ktp);
    	$objPHPExcel->getActiveSheet()->setCellValue("H".$row2, $values->jk);
    	$objPHPExcel->getActiveSheet()->setCellValue("I".$row2, $from_pernikahan);
    	$objPHPExcel->getActiveSheet()->setCellValue("J".$row2, $thru_pernikahan);
    	$objPHPExcel->getActiveSheet()->setCellValue("K".$row2, $pendidikan);
    	$objPHPExcel->getActiveSheet()->setCellValue("L".$row2, $values->no_hp);
    	$objPHPExcel->getActiveSheet()->setCellValue("M".$row2, $values->tgl_masuk);
    	$objPHPExcel->getActiveSheet()->setCellValue("N".$row2, $values->post_status);
    	$objPHPExcel->getActiveSheet()->setCellValue("O".$row2, $values->from_position);
    	$objPHPExcel->getActiveSheet()->setCellValue("P".$row2, $values->thru_position);
    	$objPHPExcel->getActiveSheet()->setCellValue("Q".$row2, $values->from_branch);
    	$objPHPExcel->getActiveSheet()->setCellValue("R".$row2, $values->thru_branch);
    	$objPHPExcel->getActiveSheet()->setCellValue("S".$row2, "");
    	$objPHPExcel->getActiveSheet()->setCellValue("T".$row2, $values->resign);


    	$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':B'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('C'.$row2.':C'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('D'.$row2.':D'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('E'.$row2.':E'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('F'.$row2.':F'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('G'.$row2.':G'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('H'.$row2.':H'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('I'.$row2.':I'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('J'.$row2.':J'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('K'.$row2.':K'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('L'.$row2.':L'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('M'.$row2.':M'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('N'.$row2.':N'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('O'.$row2.':O'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('P'.$row2.':P'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('Q'.$row2.':Q'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('R'.$row2.':R'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('S'.$row2.':S'.$row2)->applyFromArray($styleArray);
    	$objPHPExcel->getActiveSheet()->getStyle('T'.$row2.':T'.$row2)->applyFromArray($styleArray);


    	$objPHPExcel->getActiveSheet()->getStyle('A'.$row2.':T'.$row2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    	$row2++;
    	$no++;

    }



		// Redirect output to a client's web browser (Excel2007)
		// Save Excel 2007 file

    ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Laporan_List_karyawan_'.$branch_user.'_tgl_'.date('Y-m-d').'.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
  }

  function action_laporan_list_karyawan_pdf()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$username = $session_data['username'];
  	$branch_user = $this->uri->segment(3);

  	$tampil_nik = $this->model_karyawan->get_karyawan_by_branch($branch_user);
  	foreach($tampil_nik as $values){$branch_name = $values->branch; $branch_code = $values->branch_code;}


  	ob_start();

  	$config['full_tag_open'] = '<p>';
  	$config['full_tag_close'] = '</p>';

  	$data['branch_user'] = $branch_name;
  	$data['get_laporan'] = $this->model_karyawan->get_laporan_list_karyawan($branch_user);


  	$this->load->view('laporan/action_laporan_list_karyawan_pdf',$data);

  	$content = ob_get_clean();

  	try {
  		$html2pdf = new HTML2PDF('L', 'A4', 'fr', true, 'UTF-8', 5);
  		$html2pdf->pdf->SetDisplayMode('fullpage');
  		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
  		$html2pdf->Output('Laporan List Karyawan '.$branch_name.' tgl '.date('Y-m-d').'.pdf');
  	} catch(HTML2PDF_exception $e) {
  		echo $e;
  		exit;
  	}
  }

  function laporan_rinci_karyawan()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$data['username'] = $session_data['username'];
  	$data['user'] = $session_data['fullname'];
  	$data['role_id'] = $session_data['role_id'];
  	$branch_user = $session_data['branch_code'];
  	$data['branch_user'] = $session_data['branch_code'];

  	if($branch_user == '1' || $data['username'] == "admin")
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch();
  	}else
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch_by_user($branch_user);
  	}

  	$data['periode_cutoff'] = $this->model_setup->get_cutoff();
  	$data['get_periode_from_absensi_manual'] = $this->model_karyawan->get_periode_from_absensi_manual();
  	$data['get_karyawan'] = $this->model_karyawan->get_karyawan();
  	$data['get_karyawan_by_branch'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);

  	$data['container'] = 'laporan/laporan_rinci_karyawan';

  	$this->load->view('core', $data);			
  }

  function action_laporan_rinci_karyawan_excel()
  {
  	$row = 3;
  	$row2 = 6;
  	$row3 = 11;

  	$session_data = $this->session->userdata('logged_in');
  	$username = $session_data['username'];
  	$nik = $this->uri->segment(3);

  	$tampil_nik = $this->model_karyawan->get_karyawan_by_nik($nik);


  	foreach($tampil_nik as $values){$branch_name = $values->branch; $branch_code = $values->thru_branch; $fullname = $values->fullname;}

			// Create new PHPExcel object
  	$objPHPExcel = $this->phpexcel;
			// Set document properties
  	$objPHPExcel->getProperties()->setCreator("HR_APP")
  	->setLastModifiedBy("HR_APP")
  	->setTitle("Office 2007 XLSX Test Document")
  	->setSubject("Office 2007 XLSX Test Document")
  	->setDescription("REPORT, generated using PHP classes.")
  	->setKeywords("REPORT")
  	->setCategory("Test result file");

  	$objPHPExcel->setActiveSheetIndex(0); 

  	$styleArray = array(
  		'outline' => array(
  			'color' => array('rgb' => '000000'),
  		),
  	);

  	$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->mergeCells('B1:D1');
  	$objPHPExcel->getActiveSheet()->setCellValue("B1", "DATA RINCI KARYAWAN");
  	$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->mergeCells('B2:D2');
  	$objPHPExcel->getActiveSheet()->setCellValue("B2", "KOPERASI BAYTUL IKHTIAR");
  	$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->setCellValue("B4", "NIK");
  	$objPHPExcel->getActiveSheet()->getStyle('B4:B25')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  	$objPHPExcel->getActiveSheet()->setCellValue("B5", "No. KTP");
  	$objPHPExcel->getActiveSheet()->setCellValue("B6", "Nama");
  	$objPHPExcel->getActiveSheet()->setCellValue("B7", "Tempat Tanggal Lahir");
  	$objPHPExcel->getActiveSheet()->setCellValue("B8", "Alamat");
  	$objPHPExcel->getActiveSheet()->setCellValue("B9", "Jenis Kelamin");
  	$objPHPExcel->getActiveSheet()->setCellValue("B10", "Status Pernikahan");
  	$objPHPExcel->getActiveSheet()->setCellValue("B11", "No. HP");
  	$objPHPExcel->getActiveSheet()->setCellValue("B12", "");
  	$objPHPExcel->getActiveSheet()->setCellValue("B13", "Sekolah Dasar");
  	$objPHPExcel->getActiveSheet()->setCellValue("B14", "Sekolah Menengah Pertama");
  	$objPHPExcel->getActiveSheet()->setCellValue("B15", "Sekolah Menengah Atas");
  	$objPHPExcel->getActiveSheet()->setCellValue("B16", "Diploma");
  	$objPHPExcel->getActiveSheet()->setCellValue("B17", "Sarjana");
  	$objPHPExcel->getActiveSheet()->setCellValue("B18", "Sertifikat");
  	$objPHPExcel->getActiveSheet()->setCellValue("B19", "Lainnya");
  	$objPHPExcel->getActiveSheet()->setCellValue("B20", "");
  	$objPHPExcel->getActiveSheet()->setCellValue("B21", "Mulai Bergabung");
  	$objPHPExcel->getActiveSheet()->setCellValue("B22", "Status Karyawan");
  	$objPHPExcel->getActiveSheet()->setCellValue("B23", "Periode Kontrak");
  	$objPHPExcel->getActiveSheet()->setCellValue("B24", "Posisi Kerja");
  	$objPHPExcel->getActiveSheet()->setCellValue("B25", "Cabang/Unit Kerja");
  	$objPHPExcel->getActiveSheet()->setCellValue("C4", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C5", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C6", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C7", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C8", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C9", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C10", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C11", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C12", "");
  	$objPHPExcel->getActiveSheet()->setCellValue("C13", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C14", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C15", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C16", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C17", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C18", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C19", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C20", "");
  	$objPHPExcel->getActiveSheet()->setCellValue("C21", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C22", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C23", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C24", ":");
  	$objPHPExcel->getActiveSheet()->setCellValue("C25", ":");

  	foreach($tampil_nik as $value)
  	{
  		if($value->jk == 'L'){$jk = "Laki - laki";}else if($value->jk == 'P'){$jk = "Perempuan";}
  		if($value->thru_pernikahan == ''){ if($value->from_pernikahan == '0'){$pernikahan = "Lajang";}else if($value->from_pernikahan == '1'){$pernikahan = 'Menikah';}else if($value->from_pernikahan == '2'){$pernikahan = 'Lainnya';}}else{if($value->thru_pernikahan == '0'){$pernikahan = "Lajang";}else if($value->thru_pernikahan == '1'){$pernikahan = 'Menikah';}else if($value->thru_pernikahan == '2'){$pernikahan = 'Lainnya';}}

  		$objPHPExcel->getActiveSheet()->setCellValue("D4", $value->nik);
  		$objPHPExcel->getActiveSheet()->setCellValue("D5", $value->no_ktp);
  		$objPHPExcel->getActiveSheet()->setCellValue("D6", $value->fullname);
  		$objPHPExcel->getActiveSheet()->setCellValue("D7", $value->tmp_lahir.", ".$value->tgl_lahir);
  		$objPHPExcel->getActiveSheet()->setCellValue("D8", $value->alamat);
  		$objPHPExcel->getActiveSheet()->setCellValue("D9", $jk);
  		$objPHPExcel->getActiveSheet()->setCellValue("D10", $pernikahan);
  		$objPHPExcel->getActiveSheet()->setCellValue("D11", $value->no_hp);
  		$objPHPExcel->getActiveSheet()->setCellValue("D12", "");
  		$objPHPExcel->getActiveSheet()->setCellValue("D13", $value->sd);
  		$objPHPExcel->getActiveSheet()->setCellValue("D14", $value->smp);
  		$objPHPExcel->getActiveSheet()->setCellValue("D15", $value->sma);
  		$objPHPExcel->getActiveSheet()->setCellValue("D16", $value->diploma);
  		$objPHPExcel->getActiveSheet()->setCellValue("D17", $value->sarjana);
  		$objPHPExcel->getActiveSheet()->setCellValue("D18", $value->sertifikat);
  		$objPHPExcel->getActiveSheet()->setCellValue("D19", $value->lainnya);
  		$objPHPExcel->getActiveSheet()->setCellValue("D20", "");
  		$objPHPExcel->getActiveSheet()->setCellValue("D21", $value->tgl_masuk);
  		$objPHPExcel->getActiveSheet()->setCellValue("D22", $values->post_status);
  		$objPHPExcel->getActiveSheet()->setCellValue("D23", "");
  		$objPHPExcel->getActiveSheet()->setCellValue("D24", $value->position);
  		$objPHPExcel->getActiveSheet()->setCellValue("D25", $value->branch);

  	}

  	$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->setSize(13);
  	$objPHPExcel->getActiveSheet()->getStyle('B4:D25')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B4:D25')->getFont()->setSize(11);

  	$objPHPExcel->getActiveSheet()->getStyle('B4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B9')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B10')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B11')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B12')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B13')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B14')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B15')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B16')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B17')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B18')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B19')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B20')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B21')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B22')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B23')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B24')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('B25')->applyFromArray($styleArray);

  	$objPHPExcel->getActiveSheet()->getStyle('C4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C8')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C9')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C10')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C11')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C12')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C13')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C14')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C15')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C16')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C17')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C18')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C19')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C20')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C21')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C22')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C23')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C25')->applyFromArray($styleArray);

  	$objPHPExcel->getActiveSheet()->getStyle('D4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D6')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D8')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D9')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D10')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D11')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D12')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D13')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D14')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D15')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D16')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D17')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D18')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D19')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D20')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D21')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D22')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D23')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D24')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D25')->applyFromArray($styleArray);

  	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(26);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(2);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(23);
  	$objPHPExcel->getActiveSheet()->getRowDimension('12')->setRowHeight(5);
  	$objPHPExcel->getActiveSheet()->getRowDimension('20')->setRowHeight(5);

  	/* --------------------------------------------------------- */

  	$no = 1;




  	ob_end_clean();
  	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  	header('Content-Disposition: attachment;filename="Laporan_rinci_karyawan_'.$fullname.'_tgl_'.date('Y-m-d').'.xlsx"');
  	header('Cache-Control: max-age=0');

  	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  	$objWriter->save('php://output');

  }

  function action_laporan_rinci_karyawan_pdf()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$username     = $session_data['username'];
  	$nik          = $this->uri->segment(3);

  	$tampil_nik          = $this->model_karyawan->get_karyawan_by_nik($nik);
  	$data['get_laporan'] = $this->model_karyawan->get_karyawan_by_nik($nik);

  	foreach($tampil_nik as $values){
  		$branch_name = $values->branch;
  		$fullname    = $values->fullname;
  	}

  	ob_start();

  	$config['full_tag_open'] = '<p>';
  	$config['full_tag_close'] = '</p>';

  	$content = $this->load->view('laporan/action_laporan_rinci_karyawan_pdf',$data, TRUE);

  //$content = ob_get_clean();

  	try {
  		$html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', 5);
  		$html2pdf->pdf->SetDisplayMode('fullpage');
  		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
  		$html2pdf->Output('Laporan Rinci Karyawan '.$fullname.' tgl '.date('Y-m-d').'.pdf');
  	} catch(HTML2PDF_exception $e) {
  		echo $e;
  		exit;
  	}
  }

  function laporan_karyawan_resign()
  {
  	$session_data        = $this->session->userdata('logged_in');
  	$data['username']    = $session_data['username'];
  	$data['user']        = $session_data['fullname'];
  	$data['role_id']     = $session_data['role_id'];
  	$branch_user         = $session_data['branch_code'];
  	$data['branch_user'] = $session_data['branch_code'];

  	if($branch_user == '1' || $data['username'] == "admin")
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch();
  	}else
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch_by_user($branch_user);
  	}

  	$data['periode_cutoff'] = $this->model_setup->get_cutoff();
  	$data['get_periode_from_absensi_manual'] = $this->model_karyawan->get_periode_from_absensi_manual();
  	$data['get_karyawan'] = $this->model_karyawan->get_karyawan();
  	$data['get_karyawan_by_branch'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);

  	$data['container'] = 'laporan/laporan_karyawan_resign';

  	$this->load->view('core', $data);			
  }

  function action_laporan_karyawan_resign_excel()
  {
  	$row = 3;
  	$row2 = 5;
  	$row3 = 11;

  	$session_data = $this->session->userdata('logged_in');
  	$username = $session_data['username'];
  	$branch_user = $session_data['branch_code'];
  	$branch = $this->uri->segment(3);
  	$from_date = $this->uri->segment(4);
  	$thru_date = $this->uri->segment(5);

  	$tampil_nik = $this->model_karyawan->get_karyawan_resign_detail_by_branch_date($branch, $from_date, $thru_date);	


  	foreach($tampil_nik as $values){$branch_name = $values->branch; $fullname = $values->fullname;}

			// Create new PHPExcel object
  	$objPHPExcel = $this->phpexcel;
			// Set document properties
  	$objPHPExcel->getProperties()->setCreator("HR_APP")
  	->setLastModifiedBy("HR_APP")
  	->setTitle("Office 2007 XLSX Test Document")
  	->setSubject("Office 2007 XLSX Test Document")
  	->setDescription("REPORT, generated using PHP classes.")
  	->setKeywords("REPORT")
  	->setCategory("Test result file");

  	$objPHPExcel->setActiveSheetIndex(0); 

  	$styleArray = array(
  		'borders' => array(
  			'outline' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN,
  				'color' => array('rgb' => '000000'),
  			),
  		),
  	);

  	$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->mergeCells('B1:D1');
  	$objPHPExcel->getActiveSheet()->setCellValue("B1", "DATA KARYAWAN RESIGN");
  	$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->mergeCells('B2:D2');
  	$objPHPExcel->getActiveSheet()->setCellValue("B2", "KOPERASI BAYTUL IKHTIAR");
  	$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->setCellValue("B4", "NIK");
  	$objPHPExcel->getActiveSheet()->setCellValue("C4", "Nama");
  	$objPHPExcel->getActiveSheet()->setCellValue("D4", "Posisi Terakhir");
  	$objPHPExcel->getActiveSheet()->setCellValue("E4", "Unit/Cabang Terakhir");
  	$objPHPExcel->getActiveSheet()->setCellValue("F4", "Tanggal Resign");
  	$objPHPExcel->getActiveSheet()->setCellValue("G4", "Alasan Resign");

  	$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->setSize(13);
  	$objPHPExcel->getActiveSheet()->getStyle('B4:G4')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B4:G4')->getFont()->setSize(11);
  	$objPHPExcel->getActiveSheet()->getStyle('B4:G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	$objPHPExcel->getActiveSheet()->getStyle('B4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('E4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('F4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('G4')->applyFromArray($styleArray);

  	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(24);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

  	/* --------------------------------------------------------- */

  	$no = 1;


  	foreach($tampil_nik as $value)
  	{
  		$objPHPExcel->getActiveSheet()->setCellValue("B".$row2, $value->nik);
  		$objPHPExcel->getActiveSheet()->setCellValue("C".$row2, $value->fullname);
  		$objPHPExcel->getActiveSheet()->setCellValue("D".$row2, $value->position);
  		$objPHPExcel->getActiveSheet()->setCellValue("E".$row2, $value->branch);
  		$objPHPExcel->getActiveSheet()->setCellValue("F".$row2, $value->tgl_resign);
  		$objPHPExcel->getActiveSheet()->setCellValue("G".$row2, $value->alasan);

  		$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':B'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('C'.$row2.':C'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('D'.$row2.':D'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('E'.$row2.':E'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('F'.$row2.':F'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('G'.$row2.':G'.$row2)->applyFromArray($styleArray);


  		$objPHPExcel->getActiveSheet()->getStyle('A'.$row2.':T'.$row2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  		$row2++;
  		$no++;

  	}

			// Redirect output to a client's web browser (Excel2007)
			// Save Excel 2007 file
  	ob_end_clean();
  	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  	header('Content-Disposition: attachment;filename="Laporan_karyawan_resign_tgl_'.date('Y-m-d').'.xlsx"');
  	header('Cache-Control: max-age=0');

  	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  	$objWriter->save('php://output');

  }

  function get_karyawan_resign_detail_by_branch()
  {
  	$branch_user = $this->input->post('branch');
  	$data = $this->model_karyawan->get_karyawan_resign_detail_by_branch($branch_user);

  	echo json_encode($data);
  }

  function action_laporan_karyawan_resign_pdf()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$username     = $session_data['username'];
  	$branch_user  = $session_data['branch_code'];
  	$branch       = $this->uri->segment(3);
  	$from_date    = $this->uri->segment(4);
  	$thru_date    = $this->uri->segment(5);

  	$tampil_nik          = $this->model_karyawan->get_karyawan_resign_detail_by_branch_date($branch, $from_date, $thru_date);
  	$data['get_laporan'] = $this->model_karyawan->get_karyawan_resign_detail_by_branch_date($branch, $from_date, $thru_date);	

  	foreach($tampil_nik as $values){$branch_name = $values->branch; $fullname = $values->fullname;}

  	ob_start();

  	$config['full_tag_open'] = '<p>';
  	$config['full_tag_close'] = '</p>';  

  	$content = $this->load->view('laporan/action_laporan_karyawan_resign_pdf',$data, TRUE);

  //$content = ob_get_clean();

  	try {
  		$html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', 5);
  		$html2pdf->pdf->SetDisplayMode('fullpage');
  		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
  		$html2pdf->Output('Laporan Karyawan Resign tgl '.date('Y-m-d').'.pdf');
  	} catch(HTML2PDF_exception $e) {
  		echo $e;
  		exit;
  	}
  }

  function laporan_tempo_kontrak_karyawan()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$data['username'] = $session_data['username'];
  	$data['user'] = $session_data['fullname'];
  	$data['role_id'] = $session_data['role_id'];
  	$branch_user = $session_data['branch_code'];
  	$data['branch_user'] = $session_data['branch_code'];

  	if($branch_user == '1' || $data['username'] == "admin")
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch();
  	}else
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch_by_user($branch_user);
  	}

  	$data['periode_cutoff'] = $this->model_setup->get_cutoff();
  	$data['get_periode_from_absensi_manual'] = $this->model_karyawan->get_periode_from_absensi_manual();
  	$data['get_karyawan'] = $this->model_karyawan->get_karyawan();
  	$data['get_karyawan_by_branch'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);

  	$data['container'] = 'laporan/laporan_tempo_kontrak_karyawan';

  	$this->load->view('core', $data);			
  }

  function action_laporan_jatuh_tempo_kontrak_karyawan_excel()
  {
  	$row = 3;
  	$row2 = 5;
  	$row3 = 11;

  	$session_data = $this->session->userdata('logged_in');
  	$username = $session_data['username'];
  	$branch_user = $session_data['branch_code'];
  	$branch = $this->uri->segment(3);
  	$from_date = $this->uri->segment(4);
  	$thru_date = $this->uri->segment(5);

  	if($branch = '999999')
  	{
  		$tampil_nik = $this->model_karyawan->get_tempo_kontrak($from_date, $thru_date);	
  	}else
  	{
  		$tampil_nik = $this->model_karyawan->get_tempo_kontrak_by_branch($branch, $from_date, $thru_date);
  	}


  	foreach($tampil_nik as $values){$branch_name = $values->branch; $fullname = $values->fullname;}

			// Create new PHPExcel object
  	$objPHPExcel = $this->phpexcel;
			// Set document properties
  	$objPHPExcel->getProperties()->setCreator("HR_APP")
  	->setLastModifiedBy("HR_APP")
  	->setTitle("Office 2007 XLSX Test Document")
  	->setSubject("Office 2007 XLSX Test Document")
  	->setDescription("REPORT, generated using PHP classes.")
  	->setKeywords("REPORT")
  	->setCategory("Test result file");

  	$objPHPExcel->setActiveSheetIndex(0); 

  	$styleArray = array(
  		'borders' => array(
  			'outline' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN,
  				'color' => array('rgb' => '000000'),
  			),
  		),
  	);

  	$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->mergeCells('B1:D1');
  	$objPHPExcel->getActiveSheet()->setCellValue("B1", "DATA JATUH TEMPO KARYAWAN");
  	$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->mergeCells('B2:D2');
  	$objPHPExcel->getActiveSheet()->setCellValue("B2", "KOPERASI BAYTUL IKHTIAR");
  	$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->setCellValue("B4", "NIK");
  	$objPHPExcel->getActiveSheet()->setCellValue("C4", "Nama");
  	$objPHPExcel->getActiveSheet()->setCellValue("D4", "Posisi");
  	$objPHPExcel->getActiveSheet()->setCellValue("E4", "Unit/Cabang");
  	$objPHPExcel->getActiveSheet()->setCellValue("F4", "Status Akhir");
  	$objPHPExcel->getActiveSheet()->setCellValue("G4", "Tanggal Jt.Tempo");
  	$objPHPExcel->getActiveSheet()->setCellValue("H4", "Periode");


  	$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->setSize(13);
  	$objPHPExcel->getActiveSheet()->getStyle('B4:H4')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B4:H4')->getFont()->setSize(11);
  	$objPHPExcel->getActiveSheet()->getStyle('B4:H4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	$objPHPExcel->getActiveSheet()->getStyle('B4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('E4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('F4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('G4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('H4')->applyFromArray($styleArray);

  	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(24);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(21);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(21);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(24);

  	/* --------------------------------------------------------- */

  	$no = 1;

  	foreach($tampil_nik as $value)
  	{
  		$objPHPExcel->getActiveSheet()->setCellValue("B".$row2, $value->nik);
  		$objPHPExcel->getActiveSheet()->setCellValue("C".$row2, $value->fullname);
  		$objPHPExcel->getActiveSheet()->setCellValue("D".$row2, $value->position);
  		$objPHPExcel->getActiveSheet()->setCellValue("E".$row2, $value->branch);
  		$objPHPExcel->getActiveSheet()->setCellValue("F".$row2, $value->status);
  		$objPHPExcel->getActiveSheet()->setCellValue("G".$row2, $value->thru_periode);
  		$objPHPExcel->getActiveSheet()->setCellValue("H".$row2, $value->from_periode." s/d ".$value->thru_periode);

  		$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':B'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('C'.$row2.':C'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('D'.$row2.':D'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('E'.$row2.':E'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('F'.$row2.':F'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('G'.$row2.':G'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('H'.$row2.':H'.$row2)->applyFromArray($styleArray);

  		$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':H'.$row2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  		$row2++;
  	}



			// Redirect output to a client's web browser (Excel2007)
			// Save Excel 2007 file

  	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  	header('Content-Disposition: attachment;filename="Laporan_jatuh_tempo_karyawan_cabang/unit_'.$branch_name.'_tgl_'.date('Y-m-d').'.xlsx"');
  	header('Cache-Control: max-age=0');

  	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  	$objWriter->save('php://output');

  }

  function action_laporan_jatuh_tempo_kontrak_karyawan_pdf()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$username = $session_data['username'];
  	$branch_user = $session_data['branch_code'];
  	$branch = $this->uri->segment(3);
  	$from_date = $this->uri->segment(4);
  	$thru_date = $this->uri->segment(5);

  	if($branch == '999999')
  	{
  		$tampil_nik = $this->model_karyawan->get_tempo_kontrak($from_date, $thru_date);	
  		$data['get_laporan'] = $this->model_karyawan->get_tempo_kontrak($from_date, $thru_date);	
  	}else{
  		$tampil_nik = $this->model_karyawan->get_tempo_kontrak_by_branch($branch, $from_date, $thru_date);
  		$data['get_laporan'] = $this->model_karyawan->get_tempo_kontrak_by_branch($branch, $from_date, $thru_date);
  	}

  	$xfactor = count($tampil_nik);

  	if($xfactor > 0){
  		foreach($tampil_nik as $values){
  			$branch_name         = $values->branch; 

  			if($branch == '999999'){
  				$data['branch_name'] = 'Semua';
  			}else{
  				$data['branch_name'] = $values->branch; 
  			}
  			$fullname            = $values->fullname;
  		}
  	}else{
  		echo "Data Tidak Ditemukan";
  		exit;
  	}


  	ob_start();

  	$config['full_tag_open'] = '<p>';
  	$config['full_tag_close'] = '</p>';



  	$content = $this->load->view('laporan/action_laporan_jatuh_tempo_kontrak_karyawan_pdf',$data, TRUE);

 //$content = ob_get_clean();

  	try {
  		$html2pdf = new HTML2PDF('L', 'A4', 'en', true, 'UTF-8', 5);
  		$html2pdf->pdf->SetDisplayMode('fullpage');
  		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
  		$html2pdf->Output('Laporan Jatuh Tempo Karyawan Cabang/Unit '.$branch_name.' tgl '.date('Y-m-d').'.pdf');
  	} catch(HTML2PDF_exception $e) {
  		echo $e;
  		exit;
  	}
  }

  function laporan_cuti_karyawan()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$data['username'] = $session_data['username'];
  	$data['user'] = $session_data['fullname'];
  	$data['role_id'] = $session_data['role_id'];
  	$branch_user = $session_data['branch_code'];
  	$data['branch_user'] = $session_data['branch_code'];

  	if($branch_user == '1' || $data['username'] == "admin")
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch();
  	}else
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch_by_user($branch_user);
  	}

  	$data['periode_cutoff'] = $this->model_setup->get_cutoff();
  	$data['get_periode_from_absensi_manual'] = $this->model_karyawan->get_periode_from_absensi_manual();
  	$data['get_karyawan'] = $this->model_karyawan->get_karyawan();
  	$data['get_karyawan_by_branch'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);

  	$data['container'] = 'laporan/laporan_cuti_karyawan';

  	$this->load->view('core', $data);			
  }

  function action_laporan_cuti_karyawan_pdf()
  {
  	$session_data        = $this->session->userdata('logged_in');
  	$username            = $session_data['username'];
  	$branch_user         = $session_data['branch_code'];
  	$branch              = $this->uri->segment(3);
  	$from_date           = $this->uri->segment(4);
  	$thru_date           = $this->uri->segment(5);

  //$data['get_laporan'] = $this->model_karyawan->get_absent_by_tgl($from_date, $thru_date);	
  	$data['get_laporan'] = $this->mcore->get_cuti_by_tgl($branch, $from_date, $thru_date);

  	ob_start();

  	$config['full_tag_open']  = '<p>';
  	$config['full_tag_close'] = '</p>';



  	$content = $this->load->view('laporan/action_laporan_cuti_karyawan_pdf',$data, TRUE);

  //$content = ob_get_clean();

  	try {
  		$html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', 5);
  		$html2pdf->pdf->SetDisplayMode('fullpage');
  		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
  		$html2pdf->Output('Laporan Cuti Karyawan tgl '.$from_date.' s/d '.$thru_date.'.pdf');
  	} catch(HTML2PDF_exception $e) {
  		echo $e;
  		exit;
  	}
  }

  function action_laporan_cuti_karyawan_excel()
  {
  	$row = 3;
  	$row2 = 5;
  	$row3 = 11;

  	$session_data = $this->session->userdata('logged_in');
  	$username = $session_data['username'];
  	$branch_user = $session_data['branch_code'];
  	$branch = $this->uri->segment(3);
  	$from_date = $this->uri->segment(4);
  	$thru_date = $this->uri->segment(5);

  	$tampil_nik = $this->mcore->get_absent_by_tgl($from_date, $thru_date);	


			// Create new PHPExcel object
  	$objPHPExcel = $this->phpexcel;
			// Set document properties
  	$objPHPExcel->getProperties()->setCreator("HR_APP")
  	->setLastModifiedBy("HR_APP")
  	->setTitle("Office 2007 XLSX Test Document")
  	->setSubject("Office 2007 XLSX Test Document")
  	->setDescription("REPORT, generated using PHP classes.")
  	->setKeywords("REPORT")
  	->setCategory("Test result file");

  	$objPHPExcel->setActiveSheetIndex(0); 

  	$styleArray = array(
  		'borders' => array(
  			'outline' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN,
  				'color' => array('rgb' => '000000'),
  			),
  		),
  	);

  	$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->mergeCells('B1:D1');
  	$objPHPExcel->getActiveSheet()->setCellValue("B1", "LAPORAN CUTI/IJIN KARYAWAN");
  	$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->mergeCells('B2:D2');
  	$objPHPExcel->getActiveSheet()->setCellValue("B2", "KOPERASI BAYTUL IKHTIAR");
  	$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->setCellValue("B4", "NIK");
  	$objPHPExcel->getActiveSheet()->setCellValue("C4", "Nama");
  	$objPHPExcel->getActiveSheet()->setCellValue("D4", "Tanggal Cuti");
  	$objPHPExcel->getActiveSheet()->setCellValue("E4", "Kategori Cuti");
  	$objPHPExcel->getActiveSheet()->setCellValue("F4", "Keterangan");
  	$objPHPExcel->getActiveSheet()->setCellValue("G4", "Approve By");


  	$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->setSize(13);
  	$objPHPExcel->getActiveSheet()->getStyle('B4:G4')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B4:G4')->getFont()->setSize(11);
  	$objPHPExcel->getActiveSheet()->getStyle('B4:G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	$objPHPExcel->getActiveSheet()->getStyle('B4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('E4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('F4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('G4')->applyFromArray($styleArray);

  	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(24);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(21);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(24);

  	/* --------------------------------------------------------- */

  	$no = 1;

  	foreach($tampil_nik as $values)
  	{
  		$objPHPExcel->getActiveSheet()->setCellValue("B".$row2, $values->nik);
  		$objPHPExcel->getActiveSheet()->setCellValue("C".$row2, $values->fullname);
  		$objPHPExcel->getActiveSheet()->setCellValue("D".$row2, $values->tgl_cuti);
  		$objPHPExcel->getActiveSheet()->setCellValue("E".$row2, $values->kategori_cuti);
  		$objPHPExcel->getActiveSheet()->setCellValue("F".$row2, $values->keterangan);
  		$objPHPExcel->getActiveSheet()->setCellValue("G".$row2, $values->aprove);

  		$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':B'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('C'.$row2.':C'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('D'.$row2.':D'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('E'.$row2.':E'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('F'.$row2.':F'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('G'.$row2.':G'.$row2)->applyFromArray($styleArray);

  		$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':G'.$row2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  		$row2++;
  	}



			// Redirect output to a client's web browser (Excel2007)
			// Save Excel 2007 file

  	ob_end_clean();
  	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  	header('Content-Disposition: attachment;filename="Laporan_cuti_karyawan_tgl_'.$from_date.' s/d '.$thru_date.'.xlsx"');
  	header('Cache-Control: max-age=0');

  	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  	$objWriter->save('php://output');

  }

  function laporan_mutasi_status()
  {
  	$session_data        = $this->session->userdata('logged_in');
  	$data['username']    = $session_data['username'];
  	$data['user']        = $session_data['fullname'];
  	$data['role_id']     = $session_data['role_id'];
  	$branch_user         = $session_data['branch_code'];
  	$data['branch_user'] = $session_data['branch_code'];

  	if($data['role_id'] == '0')
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch();
  	}
  	else
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch_by_user($branch_user);
  	}

  	$data['periode_cutoff']                  = $this->model_setup->get_cutoff();
  	$data['get_periode_from_absensi_manual'] = $this->model_karyawan->get_periode_from_absensi_manual();
  	$data['get_karyawan']                    = $this->model_karyawan->get_karyawan();
  	$data['get_karyawan_by_branch']          = $this->model_karyawan->get_karyawan_by_branch($branch_user);
  	$data['get_status']                      = $this->model_karyawan->get_status();


  	$data['container'] = 'laporan/laporan_mutasi_status';

  	$this->load->view('core', $data);			
  }

  function action_laporan_mutasi_status_pdf()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$username     = $session_data['username'];
  	$branch_user  = $session_data['branch_code'];
  	$branch       = $this->uri->segment(3);
  	$from_date    = $this->uri->segment(4);
  	$thru_date    = $this->uri->segment(5);


  	if($branch == '00000')
  	{			
  		$data['get_laporan'] = $this->model_karyawan->get_mutasi_status($from_date, $thru_date);		
  	}
  	else
  	{
  		$data['get_laporan'] = $this->model_karyawan->get_mutasi_status_by_branch($branch, $from_date, $thru_date);				
  	}

  	ob_start();

  	$config['full_tag_open']  = '<p>';
  	$config['full_tag_close'] = '</p>';
  	$data['from_date']        = $from_date;
  	$data['thru_date']        = $thru_date;

  	$content = $this->load->view('laporan/action_laporan_mutasi_status_pdf',$data, TRUE);

  //$content = ob_get_clean();

  	try {
  		$html2pdf = new HTML2PDF('L', 'A4', 'fr', true, 'UTF-8', 5);
  		$html2pdf->pdf->SetDisplayMode('fullpage');
  		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
  		$html2pdf->Output('Laporan Mutasi Status Karyawan.pdf');
  	} catch(HTML2PDF_exception $e) {
  		echo $e;
  		exit;
  	}
  }

  function action_laporan_mutasi_status_excel()
  {
  	$row = 3;
  	$row2 = 6;
  	$row3 = 11;

  	$session_data = $this->session->userdata('logged_in');
  	$username = $session_data['username'];
  	$branch_user = $session_data['branch_code'];
  	$branch = $this->uri->segment(3);
  	$from_date = $this->uri->segment(4);
  	$thru_date = $this->uri->segment(5);


  	if($branch == '00000')
  	{			
  		$tampil_nik = $this->model_karyawan->get_mutasi_status($from_date, $thru_date);		
  	}else
  	{
  		$tampil_nik = $this->model_karyawan->get_mutasi_status_by_branch($branch, $from_date, $thru_date);				
  	}


			// Create new PHPExcel object
  	$objPHPExcel = $this->phpexcel;
			// Set document properties
  	$objPHPExcel->getProperties()->setCreator("HR_APP")
  	->setLastModifiedBy("HR_APP")
  	->setTitle("Office 2007 XLSX Test Document")
  	->setSubject("Office 2007 XLSX Test Document")
  	->setDescription("REPORT, generated using PHP classes.")
  	->setKeywords("REPORT")
  	->setCategory("Test result file");

  	$objPHPExcel->setActiveSheetIndex(0); 

  	$styleArray = array(
  		'borders' => array(
  			'outline' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN,
  				'color' => array('rgb' => '000000'),
  			),
  		),
  	);

  	$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->mergeCells('B1:D1');
  	$objPHPExcel->getActiveSheet()->setCellValue("B1", "LAPORAN MUTASI STATUS KARYAWAN");
  	$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->mergeCells('B2:D2');
  	$objPHPExcel->getActiveSheet()->setCellValue("B2", "KOPERASI BAYTUL IKHTIAR");
  	$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->mergeCells('B3:D3');
  	$objPHPExcel->getActiveSheet()->setCellValue("B3", "TANGGAL ".$from_date." s/d ".$thru_date);
  	$objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->setCellValue("B5", "Tanggal");
  	$objPHPExcel->getActiveSheet()->setCellValue("C5", "NIK");
  	$objPHPExcel->getActiveSheet()->setCellValue("D5", "Nama");
  	$objPHPExcel->getActiveSheet()->setCellValue("E5", "Cabang/Unit");
  	$objPHPExcel->getActiveSheet()->setCellValue("F5", "Status Awal");
  	$objPHPExcel->getActiveSheet()->setCellValue("G5", "Status Terakhir");
  	$objPHPExcel->getActiveSheet()->setCellValue("H5", "Periode Status");


  	$objPHPExcel->getActiveSheet()->getStyle('B1:B4')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B1:B4')->getFont()->setSize(13);
  	$objPHPExcel->getActiveSheet()->getStyle('B5:H5')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B5:H5')->getFont()->setSize(11);
  	$objPHPExcel->getActiveSheet()->getStyle('B5:H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	$objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($styleArray);

  	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(24);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(21);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(24);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(24);

  	/* --------------------------------------------------------- */

  	$no = 1;

  	foreach($tampil_nik as $values)
  	{
  		$objPHPExcel->getActiveSheet()->setCellValue("B".$row2, substr($values->created_date, 0, 10));
  		$objPHPExcel->getActiveSheet()->setCellValue("C".$row2, $values->nik);
  		$objPHPExcel->getActiveSheet()->setCellValue("D".$row2, $values->fullname);
  		$objPHPExcel->getActiveSheet()->setCellValue("E".$row2, $values->branch);
  		$objPHPExcel->getActiveSheet()->setCellValue("F".$row2, $values->from_status);
  		$objPHPExcel->getActiveSheet()->setCellValue("G".$row2, $values->thru_status);
  		$objPHPExcel->getActiveSheet()->setCellValue("H".$row2, $values->from_date.' s/d '.$values->thru_date);

  		$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':B'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('C'.$row2.':C'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('D'.$row2.':D'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('E'.$row2.':E'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('F'.$row2.':F'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('G'.$row2.':G'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('H'.$row2.':H'.$row2)->applyFromArray($styleArray);

  		$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':H'.$row2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  		$row2++;
  	}



			// Redirect output to a client's web browser (Excel2007)
			// Save Excel 2007 file
  	ob_end_clean();
  	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  	header('Content-Disposition: attachment;filename="Laporan_mutasi_karyawan.xlsx"');
  	header('Cache-Control: max-age=0');

  	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  	$objWriter->save('php://output');

  }

  function laporan_mutasi_jabatan()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$data['username'] = $session_data['username'];
  	$data['user'] = $session_data['fullname'];
  	$data['role_id'] = $session_data['role_id'];
  	$branch_user = $session_data['branch_code'];
  	$data['branch_user'] = $session_data['branch_code'];

  	if($branch_user == '1' || $data['username'] == "admin")
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch();
  	}else
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch_by_user($branch_user);
  	}

  	$data['periode_cutoff'] = $this->model_setup->get_cutoff();
  	$data['get_periode_from_absensi_manual'] = $this->model_karyawan->get_periode_from_absensi_manual();
  	$data['get_karyawan'] = $this->model_karyawan->get_karyawan();
  	$data['get_karyawan_by_branch'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);
  	$data['get_status'] = $this->model_karyawan->get_status();


  	$data['container'] = 'laporan/laporan_mutasi_jabatan';

  	$this->load->view('core', $data);			
  }

  function action_laporan_mutasi_jabatan_pdf()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$username     = $session_data['username'];
  	$branch_user  = $session_data['branch_code'];
  	$branch       = $this->uri->segment(3);
  	$from_date    = $this->uri->segment(4);
  	$thru_date    = $this->uri->segment(5);


  	if($branch == '00000')
  	{
  		$data['get_laporan'] = $this->model_karyawan->get_mutasi_jabatan($from_date, $thru_date);
  	}
  	else
  	{
  		$data['get_laporan'] = $this->model_karyawan->get_mutasi_jabatan_by_branch2($branch, $from_date, $thru_date);				
  	}

  	ob_start();

  	$config['full_tag_open'] = '<p>';
  	$config['full_tag_close'] = '</p>';
  	$data['from_date'] = $from_date;
  	$data['thru_date'] = $thru_date;        

  	$content = $this->load->view('laporan/action_laporan_mutasi_jabatan_pdf',$data, TRUE);

  //$content = ob_get_clean();

  	try {
  		$html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', 5);
  		$html2pdf->pdf->SetDisplayMode('fullpage');
  		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
  		$html2pdf->Output('Laporan Mutasi Status Karyawan.pdf');
  	} catch(HTML2PDF_exception $e) {
  		echo $e;
  		exit;
  	}
  }

  function action_laporan_mutasi_jabatan_excel()
  {
  	$row = 3;
  	$row2 = 6;
  	$row3 = 11;

  	$session_data = $this->session->userdata('logged_in');
  	$username = $session_data['username'];
  	$branch_user = $session_data['branch_code'];
  	$branch = $this->uri->segment(3);
  	$from_date = $this->uri->segment(4);
  	$thru_date = $this->uri->segment(5);


  	if($branch == '00000')
  	{			
  		$tampil_nik = $this->model_karyawan->get_mutasi_jabatan($from_date, $thru_date);
  	}else
  	{
  		$tampil_nik = $this->model_karyawan->get_mutasi_jabatan_by_branch($branch, $from_date, $thru_date);				
  	}


			// Create new PHPExcel object
  	$objPHPExcel = $this->phpexcel;
			// Set document properties
  	$objPHPExcel->getProperties()->setCreator("HR_APP")
  	->setLastModifiedBy("HR_APP")
  	->setTitle("Office 2007 XLSX Test Document")
  	->setSubject("Office 2007 XLSX Test Document")
  	->setDescription("REPORT, generated using PHP classes.")
  	->setKeywords("REPORT")
  	->setCategory("Test result file");

  	$objPHPExcel->setActiveSheetIndex(0); 

  	$styleArray = array(
  		'borders' => array(
  			'outline' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN,
  				'color' => array('rgb' => '000000'),
  			),
  		),
  	);

  	$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->mergeCells('B1:D1');
  	$objPHPExcel->getActiveSheet()->setCellValue("B1", "LAPORAN MUTASI JABATAN KARYAWAN");
  	$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->mergeCells('B2:D2');
  	$objPHPExcel->getActiveSheet()->setCellValue("B2", "KOPERASI BAYTUL IKHTIAR");
  	$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->mergeCells('B3:D3');
  	$objPHPExcel->getActiveSheet()->setCellValue("B3", "TANGGAL ".$from_date." s/d ".$thru_date);
  	$objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->setCellValue("B5", "Tanggal");
  	$objPHPExcel->getActiveSheet()->setCellValue("C5", "NIK");
  	$objPHPExcel->getActiveSheet()->setCellValue("D5", "Nama");
  	$objPHPExcel->getActiveSheet()->setCellValue("E5", "Cabang/Unit");
  	$objPHPExcel->getActiveSheet()->setCellValue("F5", "Jabatan Awal");
  	$objPHPExcel->getActiveSheet()->setCellValue("G5", "Jabatan Terakhir");


  	$objPHPExcel->getActiveSheet()->getStyle('B1:B4')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B1:B4')->getFont()->setSize(13);
  	$objPHPExcel->getActiveSheet()->getStyle('B5:G5')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B5:G5')->getFont()->setSize(11);
  	$objPHPExcel->getActiveSheet()->getStyle('B5:G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	$objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($styleArray);

  	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(24);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(21);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(24);

  	/* --------------------------------------------------------- */

  	$no = 1;

  	foreach($tampil_nik as $values)
  	{
  		$objPHPExcel->getActiveSheet()->setCellValue("B".$row2, substr($values->created_date, 0, 10));
  		$objPHPExcel->getActiveSheet()->setCellValue("C".$row2, $values->nik);
  		$objPHPExcel->getActiveSheet()->setCellValue("D".$row2, $values->fullname);
  		$objPHPExcel->getActiveSheet()->setCellValue("E".$row2, $values->branch);
  		$objPHPExcel->getActiveSheet()->setCellValue("F".$row2, $values->from_position);
  		$objPHPExcel->getActiveSheet()->setCellValue("G".$row2, $values->thru_position);

  		$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':B'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('C'.$row2.':C'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('D'.$row2.':D'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('E'.$row2.':E'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('F'.$row2.':F'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('G'.$row2.':G'.$row2)->applyFromArray($styleArray);

  		$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':G'.$row2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  		$row2++;
  	}



			// Redirect output to a client's web browser (Excel2007)
			// Save Excel 2007 file
  	ob_end_clean();
  	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  	header('Content-Disposition: attachment;filename="Laporan_mutasi_karyawan.xlsx"');
  	header('Cache-Control: max-age=0');

  	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  	$objWriter->save('php://output');

  }

  function laporan_mutasi_cabang()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$data['username'] = $session_data['username'];
  	$data['user'] = $session_data['fullname'];
  	$data['role_id'] = $session_data['role_id'];
  	$branch_user = $session_data['branch_code'];
  	$data['branch_user'] = $session_data['branch_code'];

  	if($branch_user == '1' || $data['username'] == "admin")
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch();
  	}else
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch_by_user($branch_user);
  	}

  	$data['periode_cutoff'] = $this->model_setup->get_cutoff();
  	$data['get_periode_from_absensi_manual'] = $this->model_karyawan->get_periode_from_absensi_manual();
  	$data['get_karyawan'] = $this->model_karyawan->get_karyawan();
  	$data['get_karyawan_by_branch'] = $this->model_karyawan->get_karyawan_by_branch($branch_user);
  	$data['get_status'] = $this->model_karyawan->get_status();


  	$data['container'] = 'laporan/laporan_mutasi_cabang';

  	$this->load->view('core', $data);			
  }

  function action_laporan_mutasi_cabang_pdf()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$username = $session_data['username'];
  	$branch_user = $session_data['branch_code'];
  	$branch = $this->uri->segment(3);
  	$from_date = $this->uri->segment(4);
  	$thru_date = $this->uri->segment(5);


  	if($branch == '00000')
  	{			
  		$data['get_laporan'] = $this->model_karyawan->get_mutasi_cabang($from_date, $thru_date);
  	}else
  	{
  		$data['get_laporan'] = $this->model_karyawan->get_mutasi_cabang_by_branch($branch, $from_date, $thru_date);				
  	}	

  	ob_start();

  	$config['full_tag_open'] = '<p>';
  	$config['full_tag_close'] = '</p>';
  	$data['from_date'] = $from_date;
  	$data['thru_date'] = $thru_date;   



  	$content = $this->load->view('laporan/action_laporan_mutasi_cabang_pdf',$data, TRUE);

 //$content = ob_get_clean();

  	try {
  		$html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', 5);
  		$html2pdf->pdf->SetDisplayMode('fullpage');
  		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
  		$html2pdf->Output('Laporan Mutasi Status Karyawan.pdf');
  	} catch(HTML2PDF_exception $e) {
  		echo $e;
  		exit;
  	}
  }

  function action_laporan_mutasi_cabang_excel()
  {
  	$row = 3;
  	$row2 = 6;
  	$row3 = 11;

  	$session_data = $this->session->userdata('logged_in');
  	$username = $session_data['username'];
  	$branch_user = $session_data['branch_code'];
  	$branch = $this->uri->segment(3);
  	$from_date = $this->uri->segment(4);
  	$thru_date = $this->uri->segment(5);


  	if($branch == '00000')
  	{			
  		$tampil_nik = $this->model_karyawan->get_mutasi_cabang($from_date, $thru_date);
  	}else
  	{
  		$tampil_nik = $this->model_karyawan->get_mutasi_cabang_by_branch($branch, $from_date, $thru_date);				
  	}	

			// Create new PHPExcel object
  	$objPHPExcel = $this->phpexcel;
			// Set document properties
  	$objPHPExcel->getProperties()->setCreator("HR_APP")
  	->setLastModifiedBy("HR_APP")
  	->setTitle("Office 2007 XLSX Test Document")
  	->setSubject("Office 2007 XLSX Test Document")
  	->setDescription("REPORT, generated using PHP classes.")
  	->setKeywords("REPORT")
  	->setCategory("Test result file");

  	$objPHPExcel->setActiveSheetIndex(0); 

  	$styleArray = array(
  		'borders' => array(
  			'outline' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN,
  				'color' => array('rgb' => '000000'),
  			),
  		),
  	);

  	$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->mergeCells('B1:D1');
  	$objPHPExcel->getActiveSheet()->setCellValue("B1", "LAPORAN MUTASI CABANG/UNIT KARYAWAN");
  	$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->mergeCells('B2:D2');
  	$objPHPExcel->getActiveSheet()->setCellValue("B2", "KOPERASI BAYTUL IKHTIAR");
  	$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->mergeCells('B3:D3');
  	$objPHPExcel->getActiveSheet()->setCellValue("B3", "TANGGAL ".$from_date." s/d ".$thru_date);
  	$objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->setCellValue("B5", "Tanggal");
  	$objPHPExcel->getActiveSheet()->setCellValue("C5", "NIK");
  	$objPHPExcel->getActiveSheet()->setCellValue("D5", "Nama");
  	$objPHPExcel->getActiveSheet()->setCellValue("E5", "Cabang/Unit Awal");
  	$objPHPExcel->getActiveSheet()->setCellValue("F5", "Cabang/Unit Terakhir");


  	$objPHPExcel->getActiveSheet()->getStyle('B1:B4')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B1:B4')->getFont()->setSize(13);
  	$objPHPExcel->getActiveSheet()->getStyle('B5:F5')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B5:F5')->getFont()->setSize(11);
  	$objPHPExcel->getActiveSheet()->getStyle('B5:F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	$objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($styleArray);

  	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(24);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(21);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(21);

  	/* --------------------------------------------------------- */

  	$no = 1;

  	foreach($tampil_nik as $values)
  	{
  		$objPHPExcel->getActiveSheet()->setCellValue("B".$row2, substr($values->created_date, 0, 10));
  		$objPHPExcel->getActiveSheet()->setCellValue("C".$row2, $values->nik);
  		$objPHPExcel->getActiveSheet()->setCellValue("D".$row2, $values->fullname);
  		$objPHPExcel->getActiveSheet()->setCellValue("E".$row2, $values->from_branch);
  		$objPHPExcel->getActiveSheet()->setCellValue("F".$row2, $values->thru_branch);

  		$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':B'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('C'.$row2.':C'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('D'.$row2.':D'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('E'.$row2.':E'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('F'.$row2.':F'.$row2)->applyFromArray($styleArray);

  		$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':F'.$row2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  		$row2++;
  	}



			// Redirect output to a client's web browser (Excel2007)
			// Save Excel 2007 file
  	ob_end_clean();
  	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  	header('Content-Disposition: attachment;filename="Laporan_mutasi_karyawan.xlsx"');
  	header('Cache-Control: max-age=0');

  	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  	$objWriter->save('php://output');

  }

  function laporan_hak_cuti()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$data['username'] = $session_data['username'];
  	$data['user'] = $session_data['fullname'];
  	$data['role_id'] = $session_data['role_id'];
  	$branch_user = $session_data['branch_code'];
  	$data['branch_user'] = $session_data['branch_code'];

  	$data['periode_cutoff'] = $this->model_setup->get_cutoff();
  	$data['get_periode_from_absensi_manual'] = $this->model_karyawan->get_periode_from_absensi_manual();

  	if($branch_user == '1' || $data['username'] == "admin")
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch();
  	}else
  	{
  		$data['get_branch'] = $this->model_karyawan->get_branch_by_user($branch_user);
  	}

  	$data['container'] = 'laporan/laporan_hak_cuti';

  	$this->load->view('core', $data);			
  }

  function action_laporan_hak_cuti_pdf()
  {
  	$session_data = $this->session->userdata('logged_in');
  	$username = $session_data['username'];
  	$branch_user = $session_data['branch_code'];
  	$branch = $this->uri->segment(3);
  	$from_date = $this->uri->segment(4);
  	$thru_date = $this->uri->segment(5);


  	if($branch == '00000')
  	{			
  		$data['get_laporan'] = $this->model_karyawan->get_hak_cuti();
  	}else
  	{
  		$data['get_laporan'] = $this->model_karyawan->get_hak_cuti_by_branch($branch);
  	}	

  	ob_start();

  	$config['full_tag_open'] = '<p>';
  	$config['full_tag_close'] = '</p>';
  	$data['from_date'] = $from_date;
  	$data['thru_date'] = $thru_date;   



  	$content = $this->load->view('laporan/action_laporan_hak_cuti_pdf',$data, TRUE);

 //$content = ob_get_clean();

  	try {
  		$html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', 5);
  		$html2pdf->pdf->SetDisplayMode('fullpage');
  		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
  		$html2pdf->Output('Laporan Hak Cuti/Ijin Karyawan.pdf');
  	} catch(HTML2PDF_exception $e) {
  		echo $e;
  		exit;
  	}
  }

  function action_laporan_hak_cuti_excel()
  {
  	$row = 3;
  	$row2 = 5;
  	$row3 = 11;

  	$session_data = $this->session->userdata('logged_in');
  	$username = $session_data['username'];
  	$branch_user = $session_data['branch_code'];
  	$branch = $this->uri->segment(3);
  	$from_date = $this->uri->segment(4);
  	$thru_date = $this->uri->segment(5);


  	if($branch == '00000')
  	{			
  		$tampil_nik = $this->model_karyawan->get_hak_cuti();
  	}else
  	{
  		$tampil_nik = $this->model_karyawan->get_hak_cuti_by_branch($branch);
  	}	

			// Create new PHPExcel object
  	$objPHPExcel = $this->phpexcel;
			// Set document properties
  	$objPHPExcel->getProperties()->setCreator("HR_APP")
  	->setLastModifiedBy("HR_APP")
  	->setTitle("Office 2007 XLSX Test Document")
  	->setSubject("Office 2007 XLSX Test Document")
  	->setDescription("REPORT, generated using PHP classes.")
  	->setKeywords("REPORT")
  	->setCategory("Test result file");

  	$objPHPExcel->setActiveSheetIndex(0); 

  	$styleArray = array(
  		'borders' => array(
  			'outline' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN,
  				'color' => array('rgb' => '000000'),
  			),
  		),
  	);

  	$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->mergeCells('B1:D1');
  	$objPHPExcel->getActiveSheet()->setCellValue("B1", "LAPORAN HAK CUTI/IJIN KARYAWAN");
  	$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->mergeCells('B2:D2');
  	$objPHPExcel->getActiveSheet()->setCellValue("B2", "KOPERASI BAYTUL IKHTIAR");
  	$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  	$objPHPExcel->getActiveSheet()->setCellValue("B4", "NIK");
  	$objPHPExcel->getActiveSheet()->setCellValue("C4", "Nama");
  	$objPHPExcel->getActiveSheet()->setCellValue("D4", "Cabang/Unit");
  	$objPHPExcel->getActiveSheet()->setCellValue("E4", "Hak Cuti");
  	$objPHPExcel->getActiveSheet()->setCellValue("F4", "Hak Ijin");


  	$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->setSize(13);
  	$objPHPExcel->getActiveSheet()->getStyle('B4:F4')->getFont()->setBold(true);
  	$objPHPExcel->getActiveSheet()->getStyle('B4:F4')->getFont()->setSize(11);
  	$objPHPExcel->getActiveSheet()->getStyle('B4:F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	$objPHPExcel->getActiveSheet()->getStyle('B4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('C4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('D4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('E4')->applyFromArray($styleArray);
  	$objPHPExcel->getActiveSheet()->getStyle('F4')->applyFromArray($styleArray);

  	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(24);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(21);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
  	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);

  	/* --------------------------------------------------------- */

  	$no = 1;

  	foreach($tampil_nik as $values)
  	{
  		$objPHPExcel->getActiveSheet()->setCellValue("B".$row2, $values->nik);
  		$objPHPExcel->getActiveSheet()->setCellValue("C".$row2, $values->fullname);
  		$objPHPExcel->getActiveSheet()->setCellValue("D".$row2, $values->branch);
  		$objPHPExcel->getActiveSheet()->setCellValue("E".$row2, $values->hak_cuti);
  		$objPHPExcel->getActiveSheet()->setCellValue("F".$row2, $values->hak_ijin);

  		$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':B'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('C'.$row2.':C'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('D'.$row2.':D'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('E'.$row2.':E'.$row2)->applyFromArray($styleArray);
  		$objPHPExcel->getActiveSheet()->getStyle('F'.$row2.':F'.$row2)->applyFromArray($styleArray);

  		$objPHPExcel->getActiveSheet()->getStyle('B'.$row2.':F'.$row2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  		$row2++;
  	}



			// Redirect output to a client's web browser (Excel2007)
			// Save Excel 2007 file
  	ob_end_clean();
  	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  	header('Content-Disposition: attachment;filename="Laporan_hak_cuti/ijin_karyawan.xlsx"');
  	header('Cache-Control: max-age=0');

  	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  	$objWriter->save('php://output');

  }

  public function get_rekap_bulanan()
  {
	//echo '<pre>',print_r($this->input->get(),1),'</pre>';
	//exit();
  	$branch_code = $this->input->get('branch_code');
  	$periode     = $this->input->get('periode');

  	$awal         = trim(substr($periode, 0, 10));
  	$akhir        = trim(substr($periode, 13, 10));

  	if($branch_code == "semua"){
  		$branch_code = NULL;
  	}

  	$arr_karyawans = $this->mcore->get_karyawan(TRUE, $branch_code, NULL, $awal);

  	if($arr_karyawans->num_rows() > 0){
  		$no = 1;
  		foreach ($arr_karyawans->result() as $arr_karyawan) {
  			$nik             = $arr_karyawan->nik;
  			$fullname        = $arr_karyawan->fullname;
  			$position        = $arr_karyawan->position;
  			$branch          = $arr_karyawan->branch;
  			$jumlah          = '0';
  			$get_count_libur = $arr_karyawan->l;
  			$get_count_hadir = $arr_karyawan->h;
  			$get_count_tlk   = $arr_karyawan->tlk;
  			$get_count_cuti  = $arr_karyawan->c;
  			$get_count_ck    = $arr_karyawan->ck;
  			$get_count_dnl   = $arr_karyawan->dnl;
  			$get_count_sakit = $arr_karyawan->sd;
  			$get_count_ijin  = $arr_karyawan->i;
  			$ltg             = $arr_karyawan->ltg;
  			$hk              = $get_count_hadir + $get_count_tlk + $get_count_dnl;
  			$m_tepat_waktu   = $arr_karyawan->m8;
  			$m_telat_1       = $arr_karyawan->m815;
  			$m_telat_2       = $arr_karyawan->m830;
  			$m_telat_3       = $arr_karyawan->m12;
  			$sum_masuk       = $m_tepat_waktu + $m_telat_1 + $m_telat_2 + $m_telat_3;
  			$k_tepat_waktu   = $arr_karyawan->k5;
  			$k_telat_1       = $arr_karyawan->k445;
  			$k_telat_2       = $arr_karyawan->k430;
  			$k_telat_3       = $arr_karyawan->k12;
  			$sum_keluar      = $k_tepat_waktu + $k_telat_1 + $k_telat_2 + $k_telat_3;
  			$total_jam       = $arr_karyawan->lembur;

  			$jumlah = $get_count_libur+$get_count_cuti+$get_count_ck+$get_count_sakit+$get_count_ijin+$ltg+$hk;

  			$arr[] = [
  				'no'              => $no,
  				'nik'             => $nik,
  				'fullname'        => $fullname,
  				'position'        => $position,
  				'branch'          => $branch,
  				'jumlah'          => $jumlah,
  				'get_count_libur' => $get_count_libur,
  				'get_count_hadir' => $get_count_hadir,
  				'get_count_tlk'   => $get_count_tlk,
  				'get_count_cuti'  => $get_count_cuti,
  				'get_count_ck'    => $get_count_ck,
  				'get_count_dnl'   => $get_count_dnl,
  				'get_count_sakit' => $get_count_sakit,
  				'get_count_ijin'  => $get_count_ijin,
  				'ltg'             => $ltg,
  				'hk'              => $hk,
  				'm_tepat_waktu'   => $m_tepat_waktu,
  				'm_telat_1'       => $m_telat_1,
  				'm_telat_2'       => $m_telat_2,
  				'm_telat_3'       => $m_telat_3,
  				'sum_masuk'       => $sum_masuk,
  				'k_tepat_waktu'   => $k_tepat_waktu,
  				'k_telat_1'       => $k_telat_1,
  				'k_telat_2'       => $k_telat_2,
  				'k_telat_3'       => $k_telat_3,
  				'sum_keluar'      => $sum_keluar,
  				'total_jam'       => $total_jam,
  			];
  			$no++;
  		}
  	}else{
  		http_response_code(400);
  	}
  //echo '<pre>',print_r($arr_karyawans->result(),1),'</pre>';
	//exit();


  	echo json_encode($arr);
  }

  public function action_laporan_absen_pdf2()
  {
  	$session_data         = $this->session->userdata('logged_in');
  	$username             = $session_data['username'];
  	$branch_code          = $this->uri->segment(3);
  	$nik                  = $this->uri->segment(4);
  	$periode              = $this->uri->segment(5);
  	$awal                 = substr($periode, 0, 10);
  	$awal_                = substr($periode, 0, 10);
  	$akhir                = substr($periode, 17, 27);

  	if($nik == "99999"){
  		$nik = NULL;
  	}

  	$data['arr_karyawan'] = $this->mcore->get_karyawan(TRUE, $branch_code, $nik, $awal);

  	echo '<pre>',print_r($data['arr_karyawan']->result(),1),'</pre>';
  	exit();

  	if($data['arr_karyawan']->num_rows() > 0){

  	}else{
  		echo "Karyawan Tidak Ditemukan.";
  		exit();
  	}




  	$table = 'app_parameter';
  	$where = [
  		'parameter_group' => 'cabang',
  		'parameter_id'    => $branch_code
  	];
  	$arr_par = $this->mcore->get_where($table, $where);
  	foreach ($arr_par->result() as $key) {
  		$branch_name = $key->description;
  	}

	  //ob_start();

  	$config['full_tag_open']  = '<div>';
  	$config['full_tag_close'] = '</div>';

	  //$data['branch_user'] = $branch_name;
  	$data['periode']     = $awal.' s/d '.$akhir;
	  //$data['fullname']    = $fullname;
	    //$data['get_laporan'] = $this->model_karyawan->get_laporan_by_nik($nik);
  	$data['get_laporan'] = $this->model_karyawan->get_laporan_by_nik_date_2($nik, $awal, $akhir, $branch_code);
  	$data['total_karyawan'] = $data['arr_karyawan']->num_rows();
  	$data['get_lembur'] = $this->mcore->data_karyawan_lembur_by_nik($nik, $awal, $akhir);
	  //$data['get_libur']   = $this->model_karyawan->get_libur();


  	$content = $this->load->view('laporan/laporan_absensi_bulanan_pdf', $data, TRUE);
	  #exit();

	      //$content = ob_get_clean();

  	try {
  		$html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', 5);
  		$html2pdf->pdf->SetDisplayMode('fullpage');
  		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
  		$html2pdf->Output('Laporan Rekap Absensi Bulanan Periode '.$awal.' s/d '.$akhir.' Cabang/Unit '.$branch_name.'.pdf');
  	} catch(HTML2PDF_exception $e) {
  		echo $e;
  		exit;
  	}
  }

  public function action_laporan_rekap_absen_excel2()
  {
		//require (APPPATH.'third_party/PHPExcel-1.8/Classes/PHPExcel.php');
		//require (APPPATH.'third_party/PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

  	$branch_code  = $this->uri->segment(3);
  	$period       = urldecode($this->uri->segment(4));
  	$period_awal  = trim(substr($period, 0, 10));
  	$period_akhir = trim(substr($period, 12, 11));

  	if($branch_code == 'semua'){
  		$branch_name = "Semua Cabang/Unit";
  	}else{
  		$branch_name = $this->mcore->get_where('app_parameter', ['parameter_group' => 'cabang', 'parameter_id' => $branch_code])->row()->description;
  	}

  	$obj = $this->phpexcel;

  	$obj->getProperties()
  	->setCreator('creator Adam')
  	->setLastModifiedBy('SIMPRES '.date('Y-m-d H:i:s'))
  	->setCompany('KSPPS Baytul Ikhtiar')
  	->setTitle('Rekapitulasi Presensi BAIK')
  	->setSubject('Periode '.$period_awal.'-'.$period_akhir.'')
  	->setDescription('Rekapitulasi Presensi Karyawan Baytul Ikhtiar Periode '.$period_awal.'-'.$period_akhir.'');

		// SHEET 1

		# MAIN TITLE
  	$obj->setActiveSheetIndex(0);
  	$obj->getActiveSheet()->setTitle('Rekapitulasi Presensi');

  	$styleMainTitle = array(
  		'font'  => array(
  			'bold'  => true,
  			'color' => array('rgb' => '000000'),
  			'size'  => 13,
  			'name'  => 'Verdana'
  		)
  	);

  	$obj->getActiveSheet()
  	->setCellValue('A1', 'REKAPITULASI PRESENSI')
  	->mergeCells('A1:Z1')
  	->getStyle('A1')
  	->applyFromArray($styleMainTitle)
  	->getAlignment()
  	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	$obj->getActiveSheet()
  	->setCellValue('A2', 'KARYAWAN KOPERASI BAYTUL IKHTIAR')
  	->mergeCells('A2:Z2')
  	->getStyle('A2')
  	->applyFromArray($styleMainTitle)
  	->getAlignment()
  	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	$obj->getActiveSheet()
  	->setCellValue('A3', 'CABANG/UNIT '.strtoupper($branch_name))
  	->mergeCells('A3:Z3')
  	->getStyle('A3')
  	->applyFromArray($styleMainTitle)
  	->getAlignment()
  	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	$obj->getActiveSheet()
  	->setCellValue('A4', 'PERIODE '.$period_awal.' s/d '.$period_akhir)
  	->mergeCells('A4:Z4')
  	->getStyle('A4')
  	->applyFromArray($styleMainTitle)
  	->getAlignment()
  	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		# END MAIN TITLE

		# TABLE TITLE
  	$styleTableTitle = array(
  		'font'  => array(
  			'bold'  => true,
  			'color' => array('rgb' => '000000'),
  			'size'  => 8,
  			'name'  => 'Verdana'
  		),
  		'borders' => array(
  			'outline' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN,
  				'color' => array('rgb' => '000000')
  			)
  		),
  		'alignment' => array(
  			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  		)
  	);

  	$obj->getActiveSheet()->getColumnDimension('A')->setWidth(3);
  	$obj->getActiveSheet()->getColumnDimension('B')->setWidth(30);
  	$obj->getActiveSheet()->getColumnDimension('C')->setWidth(30);
  	$obj->getActiveSheet()->getColumnDimension('D')->setWidth(27);
  	$obj->getActiveSheet()->getColumnDimension('E')->setWidth(4);
  	$obj->getActiveSheet()->getColumnDimension('F')->setWidth(4);
  	$obj->getActiveSheet()->getColumnDimension('G')->setWidth(4);
  	$obj->getActiveSheet()->getColumnDimension('H')->setWidth(4);
  	$obj->getActiveSheet()->getColumnDimension('I')->setWidth(4);
  	$obj->getActiveSheet()->getColumnDimension('J')->setWidth(4);
  	$obj->getActiveSheet()->getColumnDimension('K')->setWidth(4);
  	$obj->getActiveSheet()->getColumnDimension('L')->setWidth(4);
  	$obj->getActiveSheet()->getColumnDimension('M')->setWidth(4);
  	$obj->getActiveSheet()->getColumnDimension('N')->setWidth(4);
  	$obj->getActiveSheet()->getColumnDimension('O')->setWidth(4);
  	$obj->getActiveSheet()->getColumnDimension('P')->setWidth(16);
  	$obj->getActiveSheet()->getColumnDimension('Q')->setWidth(16);
  	$obj->getActiveSheet()->getColumnDimension('R')->setWidth(16);
  	$obj->getActiveSheet()->getColumnDimension('S')->setWidth(16);
  	$obj->getActiveSheet()->getColumnDimension('T')->setWidth(16);
  	$obj->getActiveSheet()->getColumnDimension('U')->setWidth(16);
  	$obj->getActiveSheet()->getColumnDimension('V')->setWidth(16);
  	$obj->getActiveSheet()->getColumnDimension('W')->setWidth(16);
  	$obj->getActiveSheet()->getColumnDimension('X')->setWidth(16);
  	$obj->getActiveSheet()->getColumnDimension('Y')->setWidth(16);
  	$obj->getActiveSheet()->getColumnDimension('Z')->setWidth(10);

  	$obj->getActiveSheet()
  	->setCellValue('A5', 'No')
  	->mergeCells('A5:A8')
  	->getStyle('A5:A8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('B5', 'Nama')
  	->mergeCells('B5:B8')
  	->getStyle('B5:B8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('C5', 'Posisi')
  	->mergeCells('C5:C8')
  	->getStyle('C5:C8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('D5', 'Cabang')
  	->mergeCells('D5:D8')
  	->getStyle('D5:D8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('E5', 'Perhitungan Kehadiran')
  	->mergeCells('E5:O6')
  	->getStyle('E5:O6')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('E7', 'Jml')
  	->mergeCells('E7:E8')
  	->getStyle('E7:E8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('F7', 'L')
  	->mergeCells('F7:F8')
  	->getStyle('F7:F8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('G7', 'H')
  	->mergeCells('G7:G8')
  	->getStyle('G7:G8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('H7', 'TLK')
  	->mergeCells('H7:H8')
  	->getStyle('H7:H8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('I7', 'C')
  	->mergeCells('I7:I8')
  	->getStyle('I7:I8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('J7', 'CK')
  	->mergeCells('J7:J8')
  	->getStyle('J7:J8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('K7', 'DNL')
  	->mergeCells('K7:K8')
  	->getStyle('K7:K8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('L7', 'SD')
  	->mergeCells('L7:L8')
  	->getStyle('L7:L8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('M7', 'I')
  	->mergeCells('M7:M8')
  	->getStyle('M7:M8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('N7', 'LTG')
  	->mergeCells('N7:N8')
  	->getStyle('N7:N8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('O7', 'HK')
  	->mergeCells('O7:O8')
  	->getStyle('O7:O8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('P5', 'Presensi')
  	->mergeCells('P5:Y5')
  	->getStyle('P5:Y5')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('P6', 'Kedatangan')
  	->mergeCells('P6:T6')
  	->getStyle('P6')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('U6', 'Kepulangan')
  	->mergeCells('U6:Y6')
  	->getStyle('U6:Y6')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('Z5', 'Lembur')
  	->mergeCells('Z5:Z7')
  	->getStyle('Z5:Z7')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('P7', 'Tepat Waktu')
  	->getStyle('P7')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('Q7', '15 Mnt')
  	->getStyle('Q7')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('R7', '30 Mnt')
  	->getStyle('R7')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('S7', '> 30 Mnt')
  	->getStyle('S7')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('T7', 'Total Datang')
  	->getStyle('T7')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('U7', 'Tepat Waktu')
  	->getStyle('U7')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('V7', '15 Mnt')
  	->getStyle('V7')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('W7', '30 Mnt')
  	->getStyle('W7')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('X7', '< 30 Mnt')
  	->getStyle('X7')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('Y7', 'Total Pulang')
  	->getStyle('Y7')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('P8', 's/d 08:00')
  	->getStyle('P8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('Q8', '08:01 s/d 08:15')
  	->getStyle('Q8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('R8', '08:16 s/d 08:30')
  	->getStyle('R8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('S8', '08:31 >')
  	->getStyle('S8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('T8', 'Jam')
  	->getStyle('T8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('U8', '17:00 >')
  	->getStyle('U8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('V8', '16:45 s/d 16:59')
  	->getStyle('V8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('W8', '16:30 s/d 16:44')
  	->getStyle('W8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('X8', '< 16:29')
  	->getStyle('X8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('Y8', 'Jam')
  	->getStyle('Y8')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('Z8', 'Total Jam')
  	->getStyle('Z8')
  	->applyFromArray($styleTableTitle);
		# END TABLE TITLE

		# TABLE CONTENT
  	$styleTableContent = array(
  		'font'  => array(
  			'bold'  => FALSE,
  			'color' => array('rgb' => '000000'),
  			'size'  => 8,
  			'name'  => 'Verdana'
  		),
  		'borders' => array(
  			'outline' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN,
  				'color' => array('rgb' => '000000')
  			)
  		),
  		'alignment' => array(
  			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  		)
  	);

  	if($branch_code == 'semua'){
  		$arr_karyawans = $this->mcore->get_karyawan(TRUE, NULL, NULL, $period_awal);
  	}else{
  		$arr_karyawans = $this->mcore->get_karyawan(TRUE, $branch_code, NULL, $period_awal);
  	}

  	$no = 1;
  	$z  = 9;
  	foreach ($arr_karyawans->result() as $arr_karyawan) {
			# DEFINE
  		$fullname        = $arr_karyawan->fullname;
  		$position        = $arr_karyawan->position;
  		$branch          = $arr_karyawan->branch;
  		$jumlah          = '0';
  		$get_count_libur = $arr_karyawan->l;
  		$get_count_hadir = $arr_karyawan->h;
  		$get_count_tlk   = $arr_karyawan->tlk;
  		$get_count_cuti  = $arr_karyawan->c;
  		$get_count_ck    = $arr_karyawan->ck;
  		$get_count_dnl   = $arr_karyawan->dnl;
  		$get_count_sakit = $arr_karyawan->sd;
  		$get_count_ijin  = $arr_karyawan->i;
  		$ltg             = $arr_karyawan->ltg;
  		$hk              = $get_count_hadir + $get_count_tlk + $get_count_dnl;
  		$m_tepat_waktu   = $arr_karyawan->m8;
  		$m_telat_1       = $arr_karyawan->m815;
  		$m_telat_2       = $arr_karyawan->m830;
  		$m_telat_3       = $arr_karyawan->m12;
  		$sum_masuk       = $m_tepat_waktu + $m_telat_1 + $m_telat_2 + $m_telat_3;
  		$k_tepat_waktu   = $arr_karyawan->k5;
  		$k_telat_1       = $arr_karyawan->k445;
  		$k_telat_2       = $arr_karyawan->k430;
  		$k_telat_3       = $arr_karyawan->k12;
  		$sum_keluar      = $k_tepat_waktu + $k_telat_1 + $k_telat_2 + $k_telat_3;
  		$total_jam       = $arr_karyawan->lembur;
  		$jumlah          = $get_count_libur+$get_count_cuti+$get_count_ck+$get_count_sakit+$get_count_ijin+$ltg+$hk;
			# END DEFINE

  		$obj->getActiveSheet()
  		->setCellValue('A'.$z, $no)
  		->getStyle('A'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('B'.$z, $fullname)
  		->getStyle('B'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('C'.$z, $position)
  		->getStyle('C'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('D'.$z, $branch)
  		->getStyle('D'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('E'.$z, $jumlah)
  		->getStyle('E'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('F'.$z, $get_count_libur)
  		->getStyle('F'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('G'.$z, $get_count_hadir)
  		->getStyle('G'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('H'.$z, $get_count_tlk)
  		->getStyle('H'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('I'.$z, $get_count_cuti)
  		->getStyle('I'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('J'.$z, $get_count_ck)
  		->getStyle('J'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('K'.$z, $get_count_dnl)
  		->getStyle('K'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('L'.$z, $get_count_sakit)
  		->getStyle('L'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('M'.$z, $get_count_ijin)
  		->getStyle('M'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('N'.$z, $ltg)
  		->getStyle('N'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('O'.$z, $hk)
  		->getStyle('O'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('P'.$z, $m_tepat_waktu)
  		->getStyle('P'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('Q'.$z, $m_telat_1)
  		->getStyle('Q'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('R'.$z, $m_telat_2)
  		->getStyle('R'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('S'.$z, $m_telat_3)
  		->getStyle('S'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('T'.$z, $sum_masuk)
  		->getStyle('T'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('U'.$z, $k_tepat_waktu)
  		->getStyle('U'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('V'.$z, $k_telat_1)
  		->getStyle('V'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('W'.$z, $k_telat_2)
  		->getStyle('W'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('X'.$z, $k_telat_3)
  		->getStyle('X'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('Y'.$z, $sum_keluar)
  		->getStyle('Y'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('Z'.$z, $total_jam)
  		->getStyle('Z'.$z)
  		->applyFromArray($styleTableContent);

  		$no++;
  		$z++;
  	}
		# END TABLE CONTENT

		// END SHEET 1


		// SHEET 2

		# MAIN TITLE
  	$obj->createSheet();
  	$obj->setActiveSheetIndex(1);
  	$obj->getActiveSheet()->setTitle('Rinci Presensi');

  	$styleMainTitle = array(
  		'font'  => array(
  			'bold'  => true,
  			'color' => array('rgb' => '000000'),
  			'size'  => 11,
  			'name'  => 'Verdana'
  		)
  	);

  	$obj->getActiveSheet()
  	->setCellValue('A1', 'RINCI PRESENSI')
  	->mergeCells('A1:I1')
  	->getStyle('A1')
  	->applyFromArray($styleMainTitle)
  	->getAlignment()
  	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	$obj->getActiveSheet()
  	->setCellValue('A2', 'KARYAWAN KOPERASI BAYTUL IKHTIAR')
  	->mergeCells('A2:I2')
  	->getStyle('A2')
  	->applyFromArray($styleMainTitle)
  	->getAlignment()
  	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	$obj->getActiveSheet()
  	->setCellValue('A3', 'CABANG/UNIT '.strtoupper($branch_name))
  	->mergeCells('A3:I3')
  	->getStyle('A3')
  	->applyFromArray($styleMainTitle)
  	->getAlignment()
  	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

  	$obj->getActiveSheet()
  	->setCellValue('A4', 'PERIODE '.$period_awal.' s/d '.$period_akhir)
  	->mergeCells('A4:I4')
  	->getStyle('A4')
  	->applyFromArray($styleMainTitle)
  	->getAlignment()
  	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		# END MAIN TITLE

		# TABLE TITLE
  	$styleTableTitle = array(
  		'font'  => array(
  			'bold'  => true,
  			'color' => array('rgb' => '000000'),
  			'size'  => 8,
  			'name'  => 'Verdana'
  		),
  		'borders' => array(
  			'outline' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN,
  				'color' => array('rgb' => '000000')
  			)
  		),
  		'alignment' => array(
  			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  		)
  	);

  	$obj->getActiveSheet()->getColumnDimension('A')->setWidth(4);
  	$obj->getActiveSheet()->getColumnDimension('B')->setWidth(11);
  	$obj->getActiveSheet()->getColumnDimension('C')->setWidth(30);
  	$obj->getActiveSheet()->getColumnDimension('D')->setWidth(10);
  	$obj->getActiveSheet()->getColumnDimension('E')->setWidth(10);
  	$obj->getActiveSheet()->getColumnDimension('F')->setWidth(20);
  	$obj->getActiveSheet()->getColumnDimension('G')->setWidth(10);
  	$obj->getActiveSheet()->getColumnDimension('H')->setWidth(20);
  	$obj->getActiveSheet()->getColumnDimension('I')->setWidth(20);

  	$obj->getActiveSheet()
  	->setCellValue('A5', 'No')
  	->mergeCells('A5:A6')
  	->getStyle('A5:A6')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('B5', 'NIK')
  	->mergeCells('B5:B6')
  	->getStyle('B5:B6')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('C5', 'Nama')
  	->mergeCells('C5:C6')
  	->getStyle('C5:C6')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('D5', 'Tanggal')
  	->mergeCells('D5:D6')
  	->getStyle('D5:D6')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('E5', 'Presensi')
  	->mergeCells('E5:H5')
  	->getStyle('E5:H5')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('E6', 'Masuk')
  	->getStyle('E6')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('F6', 'Keterangan')
  	->getStyle('F6')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('G6', 'Keluar')
  	->getStyle('G6')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('H6', 'Keterangan')
  	->getStyle('H6')
  	->applyFromArray($styleTableTitle);

  	$obj->getActiveSheet()
  	->setCellValue('I6', 'Keterangan')
  	->mergeCells('I5:I6')
  	->getStyle('I5:I6')
  	->applyFromArray($styleTableTitle);
		# END TABLE TITLE

		# TABLE CONTENT
  	$styleTableContent = array(
  		'font'  => array(
  			'bold'  => FALSE,
  			'color' => array('rgb' => '000000'),
  			'size'  => 8,
  			'name'  => 'Verdana'
  		),
  		'borders' => array(
  			'outline' => array(
  				'style' => PHPExcel_Style_Border::BORDER_THIN,
  				'color' => array('rgb' => '000000')
  			)
  		),
  		'alignment' => array(
  			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  		)
  	);

  	$arr_karyawans = $this->mcore->get_rinci_absensi($period_awal, $branch_code);

  	$no = 1;
  	$z  = 7;
  	foreach ($arr_karyawans->result() as $arr_karyawan) {
			# DEFINE
  		$nik               = $arr_karyawan->nik;
  		$fullname          = $arr_karyawan->fullname;
  		$tanggal           = $arr_karyawan->tanggal;
  		$masuk             = $arr_karyawan->masuk;
  		$masuk_keterangan  = $arr_karyawan->masuk_keterangan;
  		$keluar            = $arr_karyawan->keluar;
  		$keluar_keterangan = $arr_karyawan->keluar_keterangan;
  		$keterangan        = $arr_karyawan->keterangan;
			# END DEFINE

  		$obj->getActiveSheet()
  		->setCellValue('A'.$z, $no)
  		->getStyle('A'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('B'.$z, $nik)
  		->getStyle('B'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('C'.$z, $fullname)
  		->getStyle('C'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('D'.$z, $tanggal)
  		->getStyle('D'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('E'.$z, $masuk)
  		->getStyle('E'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('F'.$z, $masuk_keterangan)
  		->getStyle('F'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('G'.$z, $keluar)
  		->getStyle('G'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('H'.$z, $keluar_keterangan)
  		->getStyle('H'.$z)
  		->applyFromArray($styleTableContent);

  		$obj->getActiveSheet()
  		->setCellValue('I'.$z, $keterangan)
  		->getStyle('I'.$z)
  		->applyFromArray($styleTableContent);

  		$no++;
  		$z++;
  	}
		# END TABLE CONTENT

		// END SHEET 2


		// OUTPUT
  	$filename = "Rekap_Presensi_".$period_awal."-".$period_akhir.".xlsx";

  	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  	header('Content-Disposition: attachment;filename="'.$filename.'"');
  	header('Cache-Control: max-age=0');

  	ob_end_clean();

  	$writer = PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
  	$writer->save('php://output');
  	exit;
    // END OUTPUT
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

  ////////////////////////////////////////////////////////////////////////////////////////
  /// FUNCTION HELPER ///////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////

}