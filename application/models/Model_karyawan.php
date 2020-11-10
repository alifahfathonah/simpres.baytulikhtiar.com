
<?php if (!defined('BASEPATH')) exit('No script access allowed');

class Model_karyawan extends CI_Model
{
	function update_branch($branch)
	{
		$this->db->insert('app_branch', $branch);
	}

	function update_position($position)
	{
		$this->db->insert('app_position', $position);
	}

	function truncate_branch()
	{
		$this->db->truncate('app_branch');
	}

	function truncate_position()
	{
		$this->db->truncate('app_position');
	}

	function get_count_karyawan()
	{
		$query = $this->db->query("SELECT count(a.*) num FROM app_karyawan AS a
			JOIN app_karyawan_detail AS b ON a.nik = b.nik
			JOIN app_parameter AS c ON b.thru_position = c.parameter_id AND c.parameter_group = 'jabatan'
			JOIN app_parameter AS d ON b.thru_branch = d.parameter_id AND d.parameter_group = 'cabang'");

		$row = $query->row_array();
		return $row['num'];
	}

	function get_karyawan()
	{
		/*$this->db->select('
      a.nik,
      a.fullname,
      c.description AS status,
      d.description AS position,
      e.description AS cabang
      ');*/
		$this->db->select('
      	a.karyawan_id, 
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
      	d.description AS position, 
      	e.description AS thru_position, 
      	f.description AS from_branch, 
      	g.description AS thru_branch, 
      	c.periode_training, 
      	c.periode_kontrak_1, 
      	c.periode_kontrak_2, 
      	(SELECT tgl_resign FROM app_karyawan_resign WHERE a.nik = nik) AS resign,
      	h.description AS status, 
      	g.description AS branch
      	');
		$this->db->distinct();
		$this->db->join('app_pendidikan AS b', 'a.nik = b.nik', 'left');
		$this->db->join('app_karyawan_detail AS c', 'a.nik = c.nik', 'left');
		$this->db->join('app_parameter AS d', "c.from_position = d.parameter_id AND d.parameter_group = 'jabatan'", 'left');
		$this->db->join('app_parameter AS e', "c.thru_position = e.parameter_id AND e.parameter_group = 'jabatan'", 'left');
		$this->db->join('app_parameter AS f', "c.from_branch = f.parameter_id AND f.parameter_group = 'cabang'", 'left');
		$this->db->join('app_parameter AS g', "c.thru_branch = g.parameter_id AND g.parameter_group = 'cabang'", 'left');
		$this->db->join('app_parameter AS h', "c.status = h.parameter_id AND h.parameter_group = 'status'", 'left');

		/*$this->db->join('app_karyawan_detail b', 'b.nik = a.nik', 'left outer join');
    $this->db->join('app_parameter c', 'c.parameter_group = \'status\' AND c.parameter_id = b.status', 'left outer join');
    $this->db->join('app_parameter d', 'd.parameter_group = \'jabatan\' AND d.parameter_id = b.from_position', 'left outer join');
    $this->db->join('app_parameter e', 'e.parameter_group = \'cabang\' AND e.parameter_id = b.from_branch', 'left outer join');*/
		$this->db->order_by('a.fullname', 'asc');
		$this->db->group_by('
    	a.karyawan_id, 
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
    	c.periode_training, 
    	c.periode_kontrak_1, 
    	c.periode_kontrak_2,
    	d.description, 
    	e.description, 
    	f.description, 
    	g.description, 
    	h.description,
    	');
		/*$this->db->group_by('
      a.nik,
      a.fullname,
      c.description,
      d.description,
      e.description,
      ');*/
		$query = $this->db->get('app_karyawan a');
		return $query;

		/*$this->db->select(
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
      d.description AS position, 
      e.description AS thru_position, 
      f.description AS from_branch, 
      g.description AS thru_branch, 
      c.periode_training, 
      c.periode_kontrak_1, 
      c.periode_kontrak_2, 
      (SELECT tgl_resign FROM app_karyawan_resign WHERE a.nik = nik) AS resign,
      h.description AS status, 
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
    $this->db->distinct();
    $query = $this->db->get();*/

		/*$query = $this->db->query("SELECT DISTINCT a.karyawan_id, a.nik, a.fullname, a.tmp_lahir, a.tgl_lahir, a.alamat, a.no_ktp, a.jk, a.from_pernikahan, a.thru_pernikahan, 
									a.no_hp, b.sd, b.smp, b.sma, b.diploma, b.sarjana, c.tgl_masuk, c.status, d.description AS from_position, 
									e.description AS thru_position, f.description AS from_branch, g.description AS thru_branch, c.periode_training, 
									c.periode_kontrak_1, c.periode_kontrak_2, (SELECT tgl_resign FROM app_karyawan_resign WHERE a.nik = nik) AS resign,
									h.description AS post_status, g.description AS branch
									FROM app_karyawan AS a
									JOIN app_pendidikan AS b ON a.nik = b.nik
									JOIN app_karyawan_detail AS c ON a.nik = c.nik
									JOIN app_parameter AS d ON c.from_position = d.parameter_id AND d.parameter_group = 'jabatan'
									JOIN app_parameter AS e ON c.thru_position = e.parameter_id AND e.parameter_group = 'jabatan'
									JOIN app_parameter AS f ON c.from_branch = f.parameter_id AND f.parameter_group = 'cabang'
									JOIN app_parameter AS g ON c.thru_branch = g.parameter_id AND g.parameter_group = 'cabang' 
									JOIN app_parameter AS h ON c.status = h.parameter_id AND h.parameter_group = 'status'
									ORDER BY 1");*/
		return $query;
	}

	function get_karyawan_by_nik_($nik)
	{
		/*$this->db->select(
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
      e.description AS thru_position, 
      f.description AS from_branch, 
      g.description AS thru_branch, 
      c.periode_training, 
      c.periode_kontrak_1, 
      c.periode_kontrak_2, 
      (SELECT tgl_resign FROM app_karyawan_resign WHERE a.nik = nik) AS resign,
      h.description AS post_status, g.description AS branch'
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
    return $this->db->get();*/
		return $query = $this->db->query("SELECT a.karyawan_id, a.nik, a.fullname, a.foto_karyawan, a.no_ktp, a.tmp_lahir, a.tgl_lahir, a.jk, a.from_pernikahan, a.alamat, a.no_hp, e.sd, e.smp, e.sma, e.diploma, b.thru_branch, b.thru_position, e.sarjana, e.lainnya, b.tgl_masuk, b.tgl_masuk, g.from_date AS from_date_status, g.thru_date AS thru_date_status, c.description AS position, d.description AS branch, b.status, f.description AS get_status FROM app_karyawan AS a JOIN app_karyawan_detail AS b ON a.nik = b.nik
    	JOIN app_parameter AS c ON b.thru_position = c.parameter_id AND c.parameter_group = 'jabatan'
    	JOIN app_parameter AS d ON b.thru_branch = d.parameter_id AND d.parameter_group = 'cabang'
    	JOIN app_pendidikan AS e ON a.nik = e.nik
    	JOIN app_parameter AS f ON b.status = f.parameter_id AND f.parameter_group = 'status'
    	/*JOIN app_mutasi_status AS g ON b.status = g.thru_status::int*/
    	WHERE a.nik = '$nik' ORDER BY 2  ");
	}

	function action_update_karyawan($data, $nik)
	{
		$this->db->where('nik', $nik);
		$this->db->update('app_karyawan', $data);
	}

	function action_update_karyawan_detail($data, $nik)
	{
		$this->db->where('nik', $nik);
		$this->db->update('app_karyawan_detail', $data);
	}

	function action_update_pendidikan($data, $nik)
	{
		$this->db->where('nik', $nik);
		$this->db->update('app_pendidikan', $data);
	}
	function action_add_karyawan($datasss)
	{
		$this->db->insert('app_karyawan', $datasss);
	}

	function action_add_karyawan_detail($datas)
	{
		$this->db->insert('app_karyawan_detail', $datas);
	}

	function action_add_pendidikan($datass)
	{
		$this->db->insert('app_pendidikan', $datass);
	}

	function get_branch()
	{
		$query = $this->db->query("SELECT DISTINCT * FROM app_parameter WHERE parameter_group = 'cabang' ORDER BY parameter_id");
		return $query->result();
	}

	function get_branch_by_user($branch_user)
	{
		$query = $this->db->query("SELECT DISTINCT * FROM app_parameter WHERE parameter_group = 'cabang' AND parameter_id = '$branch_user' ORDER BY parameter_id");
		return $query->result();
	}

	function post_absen_by_client($data)
	{
		$this->db->insert('app_absen', $data);
	}

	function get_karyawan_by_nik($nik)
	{
		if ($nik == '99999') {
			$x = '';
		} else {
			$x =  "WHERE a.nik = '$nik'";
		}

		return $query = $this->db->query("SELECT DISTINCT a.karyawan_id, a.nik, a.fullname, a.foto_karyawan, a.no_ktp, a.tmp_lahir, a.tgl_lahir, a.jk, a.from_pernikahan, a.alamat, 
  		a.no_hp, e.sd, e.smp, e.sma, e.diploma, e.sertifikat, e.lainnya, b.thru_branch, b.thru_position,
  		e.sarjana, e.lainnya, b.tgl_masuk, b.tgl_masuk, a.thru_pernikahan, a.from_pernikahan,
  		f.description AS post_status, g.from_date AS from_periode, g.thru_date AS thru_periode,
  		c.description AS position, d.description AS branch, b.status FROM app_karyawan AS a
  		JOIN app_karyawan_detail AS b ON a.nik = b.nik
  		JOIN app_parameter AS c ON b.thru_position = c.parameter_id AND c.parameter_group = 'jabatan'
  		JOIN app_parameter AS d ON b.thru_branch = d.parameter_id AND d.parameter_group = 'cabang'
  		JOIN app_pendidikan AS e ON a.nik = e.nik
  		JOIN app_parameter AS f ON b.status::int = f.parameter_id AND f.parameter_group = 'status'
  		JOIN app_mutasi_status AS g ON b.nik = g.nik 
  		$x ORDER BY 2");
		# return $query->result();
	}

	function get_karyawan_by_branch($branch_user)
	{
		$query = $this->db->query("SELECT DISTINCT a.nik, a.fullname, a.tmp_lahir, a.tgl_lahir, a.alamat, a.no_ktp, a.jk, a.from_pernikahan, a.thru_pernikahan, 
  		a.no_hp, b.sd, b.smp, b.sma, b.diploma, b.sarjana, c.tgl_masuk, c.status, d.description AS from_position, 
  		e.description AS thru_position, f.description AS from_branch, g.description AS thru_branch, c.periode_training, 
  		c.periode_kontrak_1, c.periode_kontrak_2, (SELECT tgl_resign FROM app_karyawan_resign WHERE a.nik = nik) AS resign,
  		h.description AS post_status
  		FROM app_karyawan AS a
  		JOIN app_pendidikan AS b ON a.nik = b.nik
  		JOIN app_karyawan_detail AS c ON a.nik = c.nik
  		JOIN app_parameter AS d ON c.from_position = d.parameter_id AND d.parameter_group = 'jabatan'
  		JOIN app_parameter AS e ON c.thru_position = e.parameter_id AND e.parameter_group = 'jabatan'
  		JOIN app_parameter AS f ON c.from_branch = f.parameter_id AND f.parameter_group = 'cabang'
  		JOIN app_parameter AS g ON c.thru_branch = g.parameter_id AND g.parameter_group = 'cabang' 
  		JOIN app_parameter AS h ON c.status = h.parameter_id AND h.parameter_group = 'status'
  		WHERE c.thru_branch = '$branch_user' 
  		AND c.status != '50' 
  		ORDER BY 1");
		return $query->result();
	}


	function get_data_absen($nik)
	{
		$ip = "192.168.1.2";
		$password_mesin = "177";
		$nik = substr($nik, 4, 14);

		if ($ip != "") {

			$Connect = fsockopen($ip, "80", $errno, $errstr, 1);
			if ($Connect) {
				$soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">" . $password_mesin . "</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">" . $nik . "</PIN></Arg></GetAttLog>";
				$newLine = "\r\n";
				fputs($Connect, "POST /iWsService HTTP/1.0" . $newLine);
				fputs($Connect, "Content-Type: text/xml" . $newLine);
				fputs($Connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
				fputs($Connect, $soap_request . $newLine);
				$buffer = "";
				while ($Response = fgets($Connect, 1024)) {
					$buffer = $buffer . $Response;
				}
				$buffer = Parse_Data($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
				$buffer = explode("\r\n", $buffer);
				for ($a = 0; $a < count($buffer); $a++) {

					$angka = "518.";

					$data = Parse_Data($buffer[$a], "<Row>", "</Row>");
					$PIN = Parse_Data($data, "<PIN>", "</PIN>");
					$DateTime = Parse_Data($data, "<DateTime>", "</DateTime>");
					$Verified = Parse_Data($data, "<Verified>", "</Verified>");
					$Status = Parse_Data($data, "<Status>", "</Status>");
					$full_pin = $angka . $PIN;
					$tanggal = substr($DateTime, 0, 10);
					$waktu = substr($DateTime, 11, 8);
					$ins = array(
						'nik' => $full_pin,
						'tanggal' => $tanggal,
						'waktu' => $waktu
					);
					//if(!$this->if_exist_check($PIN, $DateTime) && $PIN && $DateTime)
					//{
					$this->db->insert('app_absen', $ins);
					//}
				}
			} else {
				echo "<script>alert('Tidak terkoneksi dengan fingerprint');history.go(-2)</script>";
			}
		}
	}

	function get_presensi_by_nik($nik)
	{
		$query = $this->db->query("SELECT a.nik, b.fullname, a.masuk, a.keluar, a.tanggal, a.keterangan FROM app_absensi_manual AS a 
  		JOIN app_karyawan AS b ON a.nik = b.nik WHERE a.nik = '$nik' ORDER BY a.tanggal");
		return $query->result();
	}

	function get_presensi_by_nik_date($nik, $from_date, $thru_date)
	{
		$query = $this->db->query("SELECT a.nik, b.fullname, a.masuk, a.keluar, a.tanggal, a.keterangan FROM app_absensi_manual AS a 
  		JOIN app_karyawan AS b ON a.nik = b.nik WHERE a.nik = '$nik' AND a.tanggal BETWEEN '" . $from_date . "' AND '" . $thru_date . "' ORDER BY a.tanggal");
		return $query->result();
	}

	function get_libur()
	{
		$query = $this->db->query("SELECT * FROM app_hari_libur");
		return $query->result();
	}

	function get_libur_array()
	{
		$query = $this->db->query("SELECT * FROM app_hari_libur");
		return $query->result_array();
	}

	function action_add_verifikasi($data)
	{
		$this->db->insert('app_absensi_manual', $data);
	}

	function get_count_absen()
	{
		$query = $this->db->query("SELECT count(*) num FROM app_absensi_manual");

		$row = $query->row_array();

		if ($row['num']) {
		}
		return $row['num'];
	}

	function get_user_detail($username)
	{
		$query = $this->db->query("SELECT * FROM app_user WHERE username = '$username'");
		return $query->result();
	}

	public function get_laporan($branch_code, $awal = NULL, $akhir = NULL)
	{
		if ($branch_code == 'semua') {
			$x = "";
			if ($awal != NULL && $akhir != NULL) {
				$x .= "WHERE ccd.periode_from_date = '" . $awal . "' AND ccd.periode_thru_date = '" . $akhir . "' ";
				$x .= "AND b.status != '50' ";
			}
		} else {
			$x = "WHERE b.thru_branch = '" . $branch_code . "' ";
			$x .= "AND b.status != '50' ";

			if ($awal != NULL && $akhir != NULL) {
				$x .= "AND ccd.periode_from_date = '" . $awal . "' AND ccd.periode_thru_date = '" . $akhir . "' ";
			}
		}

		$query = $this->db->query("SELECT 
  		a.nik, 
  		a.fullname, 
  		c.description AS position, 
  		d.description AS branch, 
  		(
  		SELECT COUNT(nik) 
  		FROM app_absensi_manual
  		WHERE nik = a.nik 
  		AND tanggal BETWEEN CAST(
  		(
  		'$awal'
  		) AS character varying
  		) 
  		AND CAST(
  		(
  		'$akhir'
  		) AS character varying
  		) AND masuk BETWEEN '01:00:00' AND '08:00:59'
  		) AS m_tepat_waktu,
  		(
  		SELECT COUNT(nik) 
  		FROM app_absensi_manual 
  		WHERE nik = a.nik 
  		AND tanggal BETWEEN CAST(
  		(
  		'$awal'
  		) AS character varying
  		)
  		AND CAST(
  		(
  		'$akhir'
  		) AS character varying
  		)
  		AND masuk BETWEEN '08:01:00' AND '08:15:59'
  		) AS m_telat_1,
  		(
  		SELECT COUNT(nik) 
  		FROM app_absensi_manual 
  		WHERE nik = a.nik 
  		AND tanggal BETWEEN CAST(
  		(
  		'$awal'
  		) AS character varying
  		) 
  		AND CAST(
  		(
  		'$akhir'
  		) AS character varying
  		)
  		AND masuk BETWEEN '08:16:00' AND '08:30:59'
  		) AS m_telat_2,
  		(
  		SELECT COUNT(nik) 
  		FROM app_absensi_manual 
  		WHERE nik = a.nik 
  		AND tanggal BETWEEN CAST(
  		(
  		'$awal'
  		) AS character varying
  		)
  		AND CAST(
  		(
  		SELECT thru_date FROM app_cutoff
  		) AS character varying
  		)
  		AND masuk BETWEEN '08:31:00' AND '13:00:00'
  		) AS m_telat_3,
  		(
  		SELECT COUNT(nik) 
  		FROM app_absensi_manual 
  		WHERE nik = a.nik 
  		AND tanggal BETWEEN CAST(
  		(
  		'$awal'
  		) AS character varying
  		) 
  		AND CAST(
  		(
  		'$akhir'
  		) AS character varying
  		)
  		AND keluar BETWEEN '17:00:00' AND '23:58:00'
  		) AS k_tepat_waktu,
  		(
  		SELECT COUNT(nik) 
  		FROM app_absensi_manual 
  		WHERE nik = a.nik 
  		AND tanggal BETWEEN CAST(
  		(
  		'$awal'
  		) AS character varying
  		) 
  		AND CAST(
  		(
  		'$akhir'
  		) AS character varying
  		)
  		AND keluar BETWEEN '16:45:00' AND '16:59:59'
  		) AS k_telat_1,
  		(
  		SELECT COUNT(nik) 
  		FROM app_absensi_manual 
  		WHERE nik = a.nik 
  		AND tanggal BETWEEN CAST(
  		(
  		'$awal'
  		) AS character varying
  		) 
  		AND CAST(
  		(
  		'$akhir'
  		) AS character varying
  		)
  		AND keluar BETWEEN '16:31:59' AND '16:44:59'
  		) AS k_telat_2,
  		(
  		SELECT COUNT(nik) 
  		FROM app_absensi_manual 
  		WHERE nik = a.nik 
  		AND tanggal BETWEEN CAST(
  		(
  		'$awal'
  		) AS character varying
  		) 
  		AND CAST(
  		(
  		'$akhir'
  		) AS character varying
  		)
  		AND keluar BETWEEN '11:00:00' AND '16:32:00'
  		) AS k_telat_3 
  		FROM app_karyawan AS a
  		LEFT JOIN app_karyawan_detail AS b ON a.nik = b.nik
  		LEFT JOIN app_absensi_manual AS ccd ON ccd.nik = a.nik
  		LEFT JOIN app_parameter AS c ON b.thru_position = c.parameter_id AND c.parameter_group = 'jabatan'
  		LEFT JOIN app_parameter AS d ON b.thru_branch = d.parameter_id AND d.parameter_group = 'cabang' " . $x . "  GROUP BY 1,2,3,4 ORDER BY nik");

		return $query->result();
	}

	function get_laporan_all()
	{
		$query = $this->db->query("SELECT a.nik, a.fullname, c.description AS position, d.description AS branch, (SELECT COUNT(nik) FROM app_absensi_manual WHERE nik = a.nik AND tanggal BETWEEN CAST((SELECT from_date FROM app_cutoff) AS character varying) 
  		AND CAST((SELECT thru_date FROM app_cutoff) AS character varying)
  		AND masuk BETWEEN '01:00:00' AND '08:00:59' ) AS m_tepat_waktu,(SELECT COUNT(nik) FROM app_absensi_manual WHERE nik = a.nik AND tanggal BETWEEN CAST((SELECT from_date FROM app_cutoff) AS character varying) 
  		AND CAST((SELECT thru_date FROM app_cutoff) AS character varying)
  		AND masuk BETWEEN '08:01:00' AND '08:15:59') AS m_telat_1,(SELECT COUNT(nik) FROM app_absensi_manual WHERE nik = a.nik AND tanggal BETWEEN CAST((SELECT from_date FROM app_cutoff) AS character varying) 
  		AND CAST((SELECT thru_date FROM app_cutoff) AS character varying)
  		AND masuk BETWEEN '08:16:00' AND '08:30:59') AS m_telat_2,(SELECT COUNT(nik) FROM app_absensi_manual WHERE nik = a.nik AND tanggal BETWEEN CAST((SELECT from_date FROM app_cutoff) AS character varying) 
  		AND CAST((SELECT thru_date FROM app_cutoff) AS character varying)
  		AND masuk BETWEEN '08:31:00' AND '13:00:00') AS m_telat_3,(SELECT COUNT(nik) FROM app_absensi_manual WHERE nik = a.nik AND tanggal BETWEEN CAST((SELECT from_date FROM app_cutoff) AS character varying) 
  		AND CAST((SELECT thru_date FROM app_cutoff) AS character varying)
  		AND keluar BETWEEN '17:00:00' AND '23:58:00') AS k_tepat_waktu,(SELECT COUNT(nik) FROM app_absensi_manual WHERE nik = a.nik AND tanggal BETWEEN CAST((SELECT from_date FROM app_cutoff) AS character varying) 
  		AND CAST((SELECT thru_date FROM app_cutoff) AS character varying)
  		AND keluar BETWEEN '16:45:00' AND '16:59:59') AS k_telat_1,(SELECT COUNT(nik) FROM app_absensi_manual WHERE nik = a.nik AND tanggal BETWEEN CAST((SELECT from_date FROM app_cutoff) AS character varying) 
  		AND CAST((SELECT thru_date FROM app_cutoff) AS character varying)
  		AND keluar BETWEEN '16:31:59' AND '16:44:59') AS k_telat_2,(SELECT COUNT(nik) FROM app_absensi_manual WHERE nik = a.nik AND tanggal BETWEEN CAST((SELECT from_date FROM app_cutoff) AS character varying) 
  		AND CAST((SELECT thru_date FROM app_cutoff) AS character varying)
  		AND keluar BETWEEN '11:00:00' AND '16:32:00') AS k_telat_3 FROM app_karyawan AS a 
  		JOIN app_karyawan_detail AS b ON a.nik = b.nik 
  		JOIN app_parameter AS c ON b.thru_position = c.parameter_id AND c.parameter_group = 'jabatan'
  		JOIN app_parameter AS d ON b.thru_branch = d.parameter_id AND d.parameter_group = 'cabang' GROUP BY 1,2,3, 4 ORDER BY nik");

		return $query->result();
	}

	function get_laporan_by_nik($nik)
	{
		$query = $this->db->query("SELECT a.nik, b.fullname, a.tanggal, a.masuk, a.keluar, a.keterangan FROM app_absensi_manual AS a JOIN app_karyawan AS b ON a.nik = b.nik WHERE a.tanggal BETWEEN CAST((SELECT from_date FROM app_cutoff) AS character varying) AND CAST((SELECT thru_date FROM app_cutoff) AS character varying) AND a.nik = '$nik' ORDER BY a.tanggal");

		return $query->result();
	}

	public function get_laporan_by_nik_date($nik, $from, $to)
	{
		return $this->db->query("SELECT a.nik, b.fullname, a.tanggal, a.masuk, a.keluar, a.keterangan FROM app_absensi_manual AS a JOIN app_karyawan AS b ON a.nik = b.nik WHERE a.tanggal BETWEEN '" . $from . "' AND '" . $to . "' AND a.nik = '$nik' ORDER BY a.tanggal");

		return $query->result();
	}

	public function get_laporan_by_nik_date_2($nik, $from, $to, $branch_code)
	{
		if ($nik == '99999') {
			return $this->db->query("SELECT 
  			a.nik, b.fullname, a.tanggal, a.masuk, a.keluar, a.keterangan 
  			FROM app_absensi_manual AS a 
  			JOIN app_karyawan AS b ON a.nik = b.nik 
  			JOIN app_karyawan_detail AS c ON a.nik = c.nik
  			WHERE a.tanggal BETWEEN '" . $from . "' AND '" . $to . "' 
  			AND c.thru_branch = '$branch_code'
  			AND c.status != '50'
  			ORDER BY 
  			a.tanggal,
  			b.fullname ASC
  			");
		} else {
			return $this->db->query("SELECT 
  			a.nik, b.fullname, a.tanggal, a.masuk, a.keluar, a.keterangan 
  			FROM app_absensi_manual AS a 
  			JOIN app_karyawan AS b ON a.nik = b.nik 
  			JOIN app_karyawan_detail AS c ON a.nik = c.nik
  			WHERE a.tanggal BETWEEN '" . $from . "' AND '" . $to . "' 
  			AND a.nik = '$nik' 
  			AND c.thru_branch = '$branch_code' 
  			AND c.status != '50' 
  			ORDER BY a.tanggal, 
  			b.fullname ASC");
		}

		# return $query->result();
	}

	function truncate_absensi_manual()
	{
		$this->db->truncate('app_absensi_manual');
	}

	function update_absensi_manual($data)
	{
		$this->db->insert('app_absensi_manual', $data);
	}

	function get_count_absen_by_nik($nik)
	{
		$query = $this->db->query("SELECT count(*) num FROM app_absensi_manual WHERE nik = '$nik'");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_absensi_fp($nik)
	{
		$query = $this->db->query("SELECT nik, tanggal, min(waktu) min, max(waktu) max FROM app_absen WHERE nik = '$nik' and tanggal between CAST((SELECT 								from_date FROM app_cutoff) AS character varying) AND CAST((SELECT thru_date FROM app_cutoff) AS character varying) group by 						1,2 order by 1,2 ");
		return $query->result();
	}

	function update_absensi_manual_by_nik($nik, $masuk, $keluar, $tanggal)
	{
		$query = $this->db->query("UPDATE app_absensi_manual SET nik = '$nik', masuk = '$masuk', keluar = '$keluar', tanggal = '$tanggal' WHERE nik = '$nik' AND tanggal = '$tanggal'");
		return $query;
	}

	function update_absensi_manual_by_nik_($nik, $masuk, $keluar, $tanggal, $keterangan)
	{
		$this->db->set('masuk', $masuk);
		$this->db->set('keluar', $keluar);
		if ($keterangan == '') {
			$this->db->set('keterangan', NULL);
		} else {
			$this->db->set('keterangan', $keterangan);
		}
		$this->db->where('nik', $nik);
		$this->db->where('tanggal', $tanggal);
		$query = $this->db->update('app_absensi_manual');
		#$query = $this->db->query("UPDATE app_absensi_manual SET nik = '$nik', masuk = '$masuk', keluar = '$keluar', tanggal = '$tanggal', keterangan = '".$keterangan."' WHERE nik = '$nik' AND tanggal = '$tanggal'");
		return $query;
	}

	function truncate_absen_by_nik($nik)
	{
		$this->db->truncate('app_absensi_manual');
		$this->db->where('nik', $nik);
	}

	function get_absen_by_tanggal($from_date)
	{
		$query = $this->db->query("SELECT count(*) num FROM app_absen WHERE tanggal = '$from_date'");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_absensi_by_nik($nik)
	{
		$query = $this->db->query("SELECT count(*) num FROM app_absensi_manual WHERE nik = '$nik'");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_periode_from_absensi_manual()
	{
		$query = $this->db->query("SELECT DISTINCT periode_from_date, periode_thru_date FROM app_absensi_manual ORDER BY periode_from_date DESC");
		return $query->result();
	}

	function get_count_absensi_manual()
	{
		$query = $this->db->query("SELECT count(*) num FROM app_absensi_manual");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_position()
	{
		$query = $this->db->query("SELECT * FROM app_parameter WHERE parameter_group = 'jabatan' ORDER BY parameter_id");
		return $query->result();
	}

	function action_update_jabatan($jabatan, $nik)
	{
		$query = $this->db->query("UPDATE app_karyawan_detail SET thru_position = '$jabatan' WHERE nik = '$nik'");
		return $query;
	}

	function action_update_cabang($cabang, $nik)
	{
		$query = $this->db->query("UPDATE app_karyawan_detail SET thru_branch = '$cabang' WHERE nik = '$nik'");
		return $query;
	}

	function action_resign($data)
	{
		$this->db->insert('app_karyawan_resign', $data);
	}

	function action_update_status_resign($nik)
	{
		$query = $this->db->query("UPDATE app_karyawan_detail SET status = '5' WHERE nik = '$nik'");
		return $query;
	}

	function get_laporan_list_karyawan($branch_user)
	{
		$query = $this->db->query("SELECT DISTINCT a.nik, a.fullname, a.tmp_lahir, a.tgl_lahir, a.alamat, a.no_ktp, a.jk, a.from_pernikahan, a.thru_pernikahan, 
  		a.no_hp, b.sd, b.smp, b.sma, b.diploma, b.sarjana, c.tgl_masuk, c.status, d.description AS from_position, 
  		d.description AS thru_position, f.description AS from_branch, g.description AS thru_branch, c.periode_training, 
  		c.periode_kontrak_1, c.periode_kontrak_2, (SELECT tgl_resign FROM app_karyawan_resign WHERE a.nik = nik) AS resign,
  		h.description AS post_status
  		FROM app_karyawan AS a
  		JOIN app_pendidikan AS b ON a.nik = b.nik
  		JOIN app_karyawan_detail AS c ON a.nik = c.nik
  		JOIN app_parameter AS d ON c.from_position = d.parameter_id AND d.parameter_group = 'jabatan'
  		JOIN app_parameter AS e ON c.thru_position = e.parameter_id AND e.parameter_group = 'jabatan'
  		JOIN app_parameter AS f ON c.from_branch = f.parameter_id AND f.parameter_group = 'cabang'
  		JOIN app_parameter AS g ON c.thru_branch = g.parameter_id AND g.parameter_group = 'cabang' 
  		JOIN app_parameter AS h ON c.status::int = h.parameter_id AND h.parameter_group = 'status'
  		WHERE c.thru_branch = '$branch_user' ORDER BY 1");
		return $query->result();
	}

	function get_karyawan_resign_detail_by_nik($nik)
	{
		$query = $this->db->query("SELECT a.nik, b.fullname, d.description AS position, e.description AS branch, a.tgl_resign, a.alasan_resign 
  		FROM app_karyawan_resign AS a JOIN app_karyawan AS b ON a.nik = b.nik
  		JOIN app_karyawan_detail AS c ON a.nik = c.nik JOIN app_parameter AS d ON c.thru_position = d.parameter_id AND
  		parameter_group = 'jabatan' JOIN app_parameter AS e ON c.thru_branch = e.parameter_id AND e.parameter_group = 'cabang'
  		WHERE a.nik = '$nik'");
		return $query->result();
	}

	function get_karyawan_resign_detail_by_branch_date($branch_user, $from_date, $thru_date)
	{
		$query = $this->db->query("SELECT a.nik, b.fullname, d.description AS position, e.description AS branch, a.tgl_resign, a.alasan 
  		FROM app_resign AS a LEFT JOIN app_karyawan AS b ON a.nik = b.nik
  		LEFT JOIN app_karyawan_detail AS c ON a.nik = c.nik LEFT JOIN app_parameter AS d ON c.thru_position::int = d.parameter_id AND
  		parameter_group = 'jabatan' LEFT JOIN app_parameter AS e ON c.thru_branch = e.parameter_id AND e.parameter_group = 'cabang'
  		WHERE c.thru_branch = '$branch_user' AND a.tgl_resign BETWEEN '$from_date' AND '$thru_date'");
		return $query->result();
	}

	function action_update_status_karyawan($data, $nik)
	{
		$this->db->where('nik', $nik);
		$this->db->update('app_karyawan_detail', $data);
	}

	function get_status()
	{
		$this->db->select('description, parameter_id');
		$this->db->where('parameter_group', 'status');
		$this->db->order_by('parameter_id', 'asc');
		$query = $this->db->get('app_parameter');
		return $query->result();
	}

	public function get_status_mutasi()
	{
		$this->db->select('description, parameter_id');
		$this->db->where('parameter_group', 'status');
		#$this->db->where('parameter_id !=', '50');
		$this->db->order_by('parameter_id', 'asc');
		$query = $this->db->get('app_parameter');
		return $query->result();
	}

	function action_insert_mutasi_status($datas)
	{
		$this->db->insert('app_mutasi_status', $datas);
	}

	function get_tempo_kontrak_by_date($from_date, $thru_date)
	{
		$query = $this->db->query("SELECT a.nik, b.fullname, d.description AS branch, f.description AS position, e.description AS status, a.from_date, 
  		a.thru_date FROM app_mutasi_status AS a
  		JOIN app_karyawan AS b ON a.nik = b.nik
  		JOIN app_karyawan_detail AS c ON a.nik = c.nik
  		JOIN app_parameter AS d ON c.thru_branch = d.parameter_id AND d.parameter_group = 'cabang'
  		JOIN app_parameter AS e ON a.thru_status = e.parameter_id AND e.parameter_group = 'status'
  		JOIN app_parameter AS f ON c.thru_position = f.parameter_id AND f.parameter_group = 'jabatan'
  		WHERE thru_date BETWEEN '$from_date' AND '$thru_date'");
		return $query->result();
	}

	function action_insert_mutasi_cabang($data)
	{
		$this->db->insert('app_mutasi_cabang', $data);
	}

	function action_insert_mutasi_jabatan($data)
	{
		$this->db->insert('app_mutasi_jabatan', $data);
	}

	function action_update_absensi_manual_by_tgl($nik, $tanggal, $masuk, $keluar)
	{
		$query = $this->db->query("UPDATE app_absensi_manual SET masuk = '$masuk', keluar = '$keluar' WHERE nik = '$nik' AND tanggal = '$tanggal'");
		return $query;
	}

	function get_absensi_manual()
	{
		$query = $this->db->query("SELECT a.nik, b.fullname, a.masuk, a.keluar, a.tanggal, a.keterangan FROM app_absensi_manual AS a
  		JOIN app_karyawan AS b ON a.nik = b.nik ORDER BY a.nik, a.tanggal");
		return $query->result();
	}

	function get_absensi_manual_cutoff($from, $thru)
	{
		$query = $this->db->query("SELECT a.nik, b.fullname, a.masuk, a.keluar, a.tanggal, a.keterangan FROM app_absensi_manual AS a
  		JOIN app_karyawan AS b ON a.nik = b.nik WHERE a.tanggal BETWEEN '" . $from . "' AND '" . $thru . "' ORDER BY a.nik, a.tanggal");
		return $query->result();
	}

	function get_kategori_absen_masuk()
	{
		$query = $this->db->query("SELECT * FROM app_kategori_absen WHERE jenis_kategori = '0' ORDER BY code_kategori");
		return $query->result();
	}

	function get_kategori_absen_keluar()
	{
		$query = $this->db->query("SELECT * FROM app_kategori_absen WHERE jenis_kategori = '1' ORDER BY code_kategori DESC");
		return $query->result();
	}

	function action_regis_absent($data)
	{
		$this->db->insert('app_alfa', $data);
	}

	function action_add_karyawan_periode($data_)
	{
		$this->db->insert('app_mutasi_status', $data_);
	}

	function get_tempo_kontrak_by_branch($branch, $from_date, $thru_date)
	{
		$query = $this->db->query("SELECT b.nik, a.fullname, d.description AS status, c.from_date AS from_periode, c.thru_date AS thru_periode,
  		e.description AS branch, f.description AS position
  		FROM app_karyawan AS a
  		JOIN app_karyawan_detail AS b ON a.nik = b.nik
  		JOIN app_mutasi_status AS c ON a.nik = c.nik
  		JOIN app_parameter AS d ON b.status::int = d.parameter_id AND d.parameter_group = 'status'
  		JOIN app_parameter AS e ON b.thru_branch = e.parameter_id AND e.parameter_group = 'cabang'
  		JOIN app_parameter AS f ON b.thru_position = f.parameter_id AND f.parameter_group = 'jabatan'
  		WHERE b.thru_branch = '$branch' AND c.thru_date BETWEEN '$from_date' AND '$thru_date' AND b.status IN (
  		'10', '11', '20', '21'
  		) 
  		");
		return $query->result();
	}

	function get_tempo_kontrak($from_date, $thru_date)
	{
		$query = $this->db->query("SELECT b.nik, a.fullname, d.description AS status, c.from_date AS from_periode, c.thru_date AS thru_periode,
  		e.description AS branch, f.description AS position
  		FROM app_karyawan AS a
  		JOIN app_karyawan_detail AS b ON a.nik = b.nik
  		JOIN app_mutasi_status AS c ON a.nik = c.nik
  		JOIN app_parameter AS d ON b.status::int = d.parameter_id AND d.parameter_group = 'status'
  		JOIN app_parameter AS e ON b.thru_branch = e.parameter_id AND e.parameter_group = 'cabang'
  		JOIN app_parameter AS f ON b.thru_position = f.parameter_id AND f.parameter_group = 'jabatan'
  		WHERE c.thru_date BETWEEN '$from_date' AND '$thru_date'");
		return $query->result();
	}

	function action_insert_karyawan_periode($datass)
	{
		$this->db->insert('app_karyawan_periode', $datass);
	}

	function get_periode_status_by_nik($nik)
	{
		$query = $this->db->query("
  		SELECT 
  		a.nik, 
  		a.fullname, 
  		b.thru_status, 
  		c.description AS status, 
  		b.from_date, 
  		b.thru_date 
  		FROM app_karyawan AS a 
  		LEFT JOIN app_karyawan_detail AS b ON a.nik = b.nik
  		LEFT JOIN app_parameter AS c ON b.status::int = c.parameter_id AND c.parameter_group = 'status' 
  		WHERE a.nik = '$nik'
  		ORDER BY b.thru_date DESC
  		");
		return $query->row_array();
	}

	function get_absent_by_nik($nik)
	{
		$query = $this->db->query("SELECT a.nik, c.fullname, a.tgl_cuti, b.description AS kategori_cuti, a.keterangan, 
  		(SELECT fullname FROM app_karyawan WHERE nik = a.aprove_by) AS aprove
  		FROM app_alfa AS a 
  		JOIN app_parameter AS b ON a.kategori_cuti::int = b.parameter_id AND parameter_group = 'absent' 
  		JOIN app_karyawan AS c ON a.nik = c.nik WHERE a.nik = '$nik'");
		return $query->result();
	}

	function get_absent_by_tgl($from_date, $thru_date)
	{
		$query = $this->db->query("SELECT a.nik, c.fullname, a.tgl_cuti, b.description AS kategori_cuti, a.keterangan, 
  		(SELECT fullname FROM app_karyawan WHERE nik = a.aprove_by) AS aprove
  		FROM app_alfa AS a 
  		JOIN app_parameter AS b ON a.kategori_cuti::int = b.parameter_id AND parameter_group = 'absent' 
  		JOIN app_karyawan AS c ON a.nik = c.nik WHERE a.tgl_cuti BETWEEN '$from_date' AND '$thru_date'");
		return $query->result();
	}

	function update_libur_absensi_manual($tgl_libur, $keterangan)
	{
		$query = $this->db->query("UPDATE app_absensi_manual SET keterangan = '$keterangan'::varchar WHERE tanggal = $tgl_libur");
		return $query;
	}

	function get_libur_by_date($from_date)
	{
		$query = $this->db->query("SELECT * FROM app_hari_libur WHERE tanggal = '$from_date'");
		return $query->row_array();
	}

	function update_cuti_absensi_manual($nik, $from_date, $ket_cuti)
	{
		$query = $this->db->query("UPDATE app_absensi_manual SET keterangan = '$ket_cuti', masuk = '08:00:00', keluar = '17:00:00' WHERE nik = '$nik' AND tanggal = '$from_date'");
		return $query;
	}

	function update_cuti_absensi_manual2($nik, $from_date, $ket_cuti)
	{
		$query = $this->db->query("UPDATE app_absensi_manual SET keterangan = '$ket_cuti' WHERE nik = '$nik' AND tanggal = '$from_date'");
		return $query;
	}

	function get_mutasi_status_by_branch($branch, $from_date, $thru_date)
	{
		$query = $this->db->query("SELECT a.created_date, a.nik, b.fullname, d.description AS branch, 
  		(SELECT description FROM app_parameter WHERE parameter_id = a.from_status::int AND parameter_group = 'status') AS from_status,
  		(SELECT description FROM app_parameter WHERE parameter_id = a.thru_status::int AND parameter_group = 'status') AS thru_status,
  		a.from_date, a.thru_date
  		FROM app_mutasi_status AS a
  		JOIN app_karyawan AS b ON a.nik = b.nik
  		JOIN app_karyawan_detail AS c ON a.nik = c.nik
  		JOIN app_parameter AS d ON c.thru_branch::int = d.parameter_id AND d.parameter_group = 'cabang'
  		WHERE c.thru_branch = '$branch' AND a.created_date BETWEEN '$from_date' AND '$thru_date' ORDER BY thru_date DESC");
		return $query->result();
	}

	function get_mutasi_jabatan_by_branch($branch, $from_date, $thru_date)
	{
		$query = $this->db->query("SELECT a.created_date, a.nik, b.fullname, d.description AS branch, 
  		(SELECT description FROM app_parameter WHERE parameter_id = a.from_position::int AND parameter_group = 'jabatan') AS from_position,
  		(SELECT description FROM app_parameter WHERE parameter_id = a.thru_position::int AND parameter_group = 'jabatan') AS thru_position
  		FROM app_mutasi_jabatan AS a
  		JOIN app_karyawan AS b ON a.nik = b.nik
  		JOIN app_karyawan_detail AS c ON a.nik = c.nik
  		JOIN app_parameter AS d ON c.thru_branch::int = d.parameter_id AND d.parameter_group = 'cabang'
  		WHERE c.thru_branch = '$branch' AND a.created_date BETWEEN '$from_date' AND '$thru_date' ORDER BY thru_date DESC");
		return $query->result();
	}

	public function get_mutasi_jabatan_by_branch2($branch, $from_date, $thru_date)
	{
		$this->db->select("
  		a.tgl_mutasi created_date,
  		a.nik,
  		(SELECT b.fullname FROM app_karyawan b WHERE a.nik = b.nik) fullname,
  		(SELECT c.description FROM app_parameter c WHERE xxx.thru_branch::int = c.parameter_id AND c.parameter_group = 'cabang') branch,
  		(SELECT c.description FROM app_parameter c WHERE a.from_position::int = c.parameter_id AND c.parameter_group = 'jabatan') from_position,
  		(SELECT c.description FROM app_parameter c WHERE a.thru_position::int = c.parameter_id AND c.parameter_group = 'jabatan') thru_position,
  		");
		$this->db->join('app_karyawan_detail xxx', 'xxx.nik = a.nik', 'left');
		$this->db->where('xxx.thru_branch', $branch);
		$query = $this->db->get('app_mutasi_jabatan a');
		/*$query = $this->db->query("SELECT a.created_date, a.nik, b.fullname, d.description AS branch, 
                  (SELECT app_parameter.description FROM app_parameter WHERE app_parameter.parameter_id = a.from_position::int AND app_parameter.parameter_group = 'jabatan') AS from_position,
                  (SELECT description FROM app_parameter WHERE parameter_id = a.thru_position::int AND parameter_group = 'jabatan') AS thru_position
                  FROM app_mutasi_jabatan AS a
                  JOIN app_karyawan AS b ON a.nik = b.nik
                  JOIN app_karyawan_detail AS c ON a.nik = c.nik
                  WHERE c.thru_branch = '$branch' AND a.tgl_mutasi BETWEEN '$from_date' AND '$thru_date' ORDER BY a.tgl_mutasi DESC");*/
		return $query->result();
	}

	function get_mutasi_cabang_by_branch($branch, $from_date, $thru_date)
	{
		$query = $this->db->query("SELECT a.created_date, a.nik, b.fullname,
                		(SELECT description FROM app_parameter WHERE parameter_id = a.from_branch AND parameter_group = 'cabang') AS from_branch,
                		(SELECT description FROM app_parameter WHERE parameter_id = a.thru_branch AND parameter_group = 'cabang') AS thru_branch
                		FROM app_mutasi_cabang AS a
                		JOIN app_karyawan AS b ON a.nik = b.nik
                		JOIN app_karyawan_detail AS c ON a.nik = c.nik
                		JOIN app_parameter AS d ON c.thru_branch = d.parameter_id AND d.parameter_group = 'cabang'
                		WHERE c.thru_branch = '$branch' AND a.created_date BETWEEN '$from_date' AND '$thru_date' ORDER BY thru_date DESC");
		return $query->result();
	}

	function get_mutasi_cabang($from_date, $thru_date)
	{
		$query = $this->db->query("SELECT a.created_date, a.nik, b.fullname,
                		(SELECT description FROM app_parameter WHERE parameter_id = a.from_branch::int AND parameter_group = 'cabang') AS from_branch,
                		(SELECT description FROM app_parameter WHERE parameter_id = a.thru_branch::int AND parameter_group = 'cabang') AS thru_branch
                		FROM app_mutasi_cabang AS a
                		JOIN app_karyawan AS b ON a.nik = b.nik
                		JOIN app_karyawan_detail AS c ON a.nik = c.nik
                		JOIN app_parameter AS d ON c.thru_branch = d.parameter_id AND d.parameter_group = 'cabang'
                		WHERE a.created_date BETWEEN '$from_date' AND '$thru_date' ORDER BY thru_date DESC");
		return $query->result();
	}

	function get_mutasi_jabatan($from_date, $thru_date)
	{
		$query = $this->db->query("SELECT a.created_date, a.nik, b.fullname, d.description AS branch, 
                		(SELECT description FROM app_parameter WHERE parameter_id = a.from_position::int AND parameter_group = 'jabatan') AS from_position,
                		(SELECT description FROM app_parameter WHERE parameter_id = a.thru_position::int AND parameter_group = 'jabatan') AS thru_position
                		FROM app_mutasi_jabatan AS a
                		JOIN app_karyawan AS b ON a.nik = b.nik
                		JOIN app_karyawan_detail AS c ON a.nik = c.nik
                		JOIN app_parameter AS d ON c.thru_branch::int = d.parameter_id AND d.parameter_group = 'cabang'
                		WHERE a.created_date BETWEEN '$from_date' AND '$thru_date' ORDER BY thru_date DESC");
		return $query->result();
	}

	function get_mutasi_status($from_date, $thru_date)
	{
		$query = $this->db->query("SELECT a.created_date, a.nik, b.fullname, d.description AS branch, 
                		(SELECT description FROM app_parameter WHERE parameter_id::int = a.from_status::int AND parameter_group = 'status') AS from_status,
                		(SELECT description FROM app_parameter WHERE parameter_id::int = a.thru_status::int AND parameter_group = 'status') AS thru_status,
                		a.from_date, a.thru_date
                		FROM app_mutasi_status AS a
                		JOIN app_karyawan AS b ON a.nik = b.nik
                		JOIN app_karyawan_detail AS c ON a.nik = c.nik
                		JOIN app_parameter AS d ON c.thru_branch::int = d.parameter_id::int AND d.parameter_group = 'cabang'
                		WHERE a.created_date BETWEEN '$from_date' AND '$thru_date' ORDER BY thru_date DESC");
		return $query->result();
	}

	function update_hak_cuti($nik, $hak_ijin, $hak_cuti)
	{
		$query = $this->db->query("UPDATE app_karyawan_detail SET hak_ijin = '$hak_ijin', hak_cuti = '$hak_cuti' WHERE nik = '$nik'");
		return $query;
	}

	function get_hak_cuti_by_nik($nik)
	{
		$query = $this->db->query("SELECT hak_cuti, hak_ijin FROM app_karyawan_detail WHERE nik = '$nik'");
		return $query->result();
	}

	function update_tlk_absensi_manual($nik, $tgl_tlk)
	{
		$query = $this->db->query("UPDATE app_absensi_manual SET masuk = '', keluar = '', keterangan = '' WHERE tanggal = '$tgl_tlk' AND nik = '$nik' ");
		return $query;
	}

	function update_dnl_absensi_manual($nik, $tgl_dnl)
	{
		$query = $this->db->query("UPDATE app_absensi_manual SET masuk = '', keluar = '', keterangan = '' WHERE tanggal = '$tgl_dnl' AND nik = '$nik' ");
		return $query;
	}

	function update_cuti_absensi_manual_($nik, $tgl_cuti)
	{
		$query = $this->db->query("UPDATE app_absensi_manual SET masuk = '', keluar = '', keterangan = '' WHERE tanggal = '$tgl_cuti' AND nik = '$nik' ");
		return $query;
	}

	function update_hak_cuti_($nik, $hak_cuti)
	{
		$query = $this->db->query("UPDATE app_karyawan_detail SET hak_cuti = '$hak_cuti' WHERE nik = '$nik'");
		return $query;
	}

	function update_hak_ijin($nik, $hak_ijin)
	{
		$query = $this->db->query("UPDATE app_karyawan_detail SET hak_ijin = '$hak_ijin' WHERE nik = '$nik'");
		return $query;
	}

	function get_hak_cuti_by_branch($branch)
	{
		$query = $this->db->query("SELECT a.nik, b.fullname, c.description AS branch, a.hak_cuti, a.hak_ijin FROM app_karyawan_detail AS a
                		JOIN app_karyawan AS b ON a.nik = b.nik 
                		JOIN app_parameter AS c ON a.thru_branch = c.parameter_id AND c.parameter_group = 'cabang'
                		WHERE a.thru_branch = '$branch'");
		return $query->result();
	}

	function get_hak_cuti()
	{
		$query = $this->db->query("SELECT a.nik, b.fullname, c.description AS branch, a.hak_cuti, a.hak_ijin FROM app_karyawan_detail AS a
                		JOIN app_karyawan AS b ON a.nik = b.nik 
                		JOIN app_parameter AS c ON a.thru_branch = c.parameter_id AND c.parameter_group = 'cabang'");
		return $query->result();
	}

	function get_karyawan_absent()
	{
		$query = $this->db->query("SELECT DISTINCT a.nik, b.fullname FROM app_alfa AS a JOIN app_karyawan AS b ON a.nik = b.nik");
		return $query->result();
	}

	function get_absent()
	{
		$query = $this->db->query("SELECT a.alfa_id, a.nik, c.fullname, a.tgl_cuti, a.kategori_cuti,  b.description AS kat_cuti, a.keterangan, 
                		(SELECT fullname FROM app_karyawan WHERE nik = a.aprove_by) AS aprove
                		FROM app_alfa AS a 
                		JOIN app_parameter AS b ON a.kategori_cuti::int = b.parameter_id AND parameter_group = 'absent' 
                		JOIN app_karyawan AS c ON a.nik = c.nik ");
		return $query->result();
	}

	function get_tlk()
	{
		$query = $this->db->query("SELECT a.tlk_id, a.nik, b.fullname, a.tgl_tlk, a.keterangan FROM app_karyawan_tlk AS a
                		JOIN app_karyawan AS b ON (a.nik = b.nik)");
		return $query->result();
	}

	function get_dnl()
	{
		$query = $this->db->query("SELECT a.dnl_id, a.nik, b.fullname, a.tgl_dnl, a.keterangan FROM app_karyawan_dnl AS a
                		JOIN app_karyawan AS b ON (a.nik = b.nik)");
		return $query->result();
	}

	function delete_cuti_by_id($id)
	{
		$query = $this->db->query("DELETE FROM app_alfa WHERE alfa_id = '$id'");
		return $query;
	}

	function delete_tlk_by_id($id)
	{
		$query = $this->db->query("DELETE FROM app_karyawan_tlk WHERE tlk_id = '$id'");
		return $query;
	}

	function delete_dnl_by_id($id)
	{
		$query = $this->db->query("DELETE FROM app_karyawan_dnl WHERE dnl_id = '$id'");
		return $query;
	}

	function get_count_ltg($nik)
	{
		$query = $this->db->query("SELECT count(*) num FROM app_absensi_manual WHERE nik = '$nik' AND masuk = '' AND keluar = '' AND keterangan = ''");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_ltg_date($nik, $from, $to)
	{
		$query = $this->db->query("SELECT count(*) num FROM app_absensi_manual WHERE nik = '" . $nik . "' AND masuk = '' AND keluar = '' AND tanggal BETWEEN '" . $from . "' AND '" . $to . "' ");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_cuti($nik)
	{
		$query = $this->db->query("SELECT count(*) num FROM app_alfa WHERE nik = '$nik' AND kategori_cuti = '1' AND \"group\" = 'cuti' AND tgl_cuti BETWEEN (SELECT from_date FROM app_cutoff) AND (SELECT thru_date FROM app_cutoff)");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_cuti_date($nik, $from, $to)
	{
		$query = $this->db->query("SELECT sum(hari) num FROM app_alfa WHERE nik = '" . $nik . "' AND kategori_cuti = '1' AND \"group\" = 'cuti' AND tgl_cuti BETWEEN '" . $from . "' AND '" . $to . "'");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_libur()
	{
		$query = $this->db->query("SELECT count(*) num FROM app_hari_libur WHERE tanggal BETWEEN (SELECT from_date FROM app_cutoff) AND (SELECT thru_date FROM app_cutoff)");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_libur_date($from, $to)
	{
		$query = $this->db->query("SELECT count(*) num FROM app_hari_libur WHERE tanggal BETWEEN '" . $from . "' AND '" . $to . "'");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_sakit($nik)
	{
		/*
    $query = $this->db->query("SELECT count(*) num FROM app_alfa WHERE nik = '$nik' AND kategori_cuti = '7' AND tgl_cuti BETWEEN (SELECT from_date FROM app_cutoff) AND (SELECT thru_date FROM app_cutoff)");    
		$row = $query->row_array();
    return 0;
    */

		$query = $this->db->query("SELECT count(*) num FROM app_alfa WHERE nik = '$nik' AND kategori_cuti = '1' AND \"group\" = 'sakit' AND tgl_cuti BETWEEN (SELECT from_date FROM app_cutoff) AND (SELECT thru_date FROM app_cutoff)");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_sakit_date($nik, $from, $to)
	{
		$query = $this->db->query("SELECT sum(hari) num FROM app_alfa WHERE nik = '$nik' AND kategori_cuti = '1' AND \"group\" = 'sakit' AND tgl_cuti BETWEEN '" . $from . "' AND '" . $to . "'");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_ijin($nik)
	{
		$query = $this->db->query("SELECT count(*) num FROM app_alfa WHERE nik = '$nik' AND kategori_cuti = '1' AND \"group\" = 'ijin' AND tgl_cuti BETWEEN (SELECT from_date FROM app_cutoff) AND (SELECT thru_date FROM app_cutoff)");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_ijin_date($nik, $from, $to)
	{
		$query = $this->db->query("SELECT sum(hari) num FROM app_alfa WHERE nik = '$nik' AND kategori_cuti = '1' AND \"group\" = 'ijin' AND tgl_cuti BETWEEN '" . $from . "' AND '" . $to . "'");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_hadir($nik)
	{
		$query = $this->db->query("SELECT count(*) num FROM app_absensi_manual WHERE nik = '$nik' AND masuk <> '' AND keluar <> ''");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_hadir_date($nik, $from, $to)
	{
		$query = $this->db->query("SELECT count(*) num FROM app_absensi_manual WHERE nik = '$nik' AND masuk <> '' AND keluar <> '' AND tanggal BETWEEN '" . $from . "' AND '" . $to . "' ");
		$row = $query->row_array();
		return $row['num'];
	}

	function action_regis_tlk($data)
	{
		$this->db->insert('app_karyawan_tlk', $data);
	}

	function action_regis_dnl($data)
	{
		$this->db->insert('app_karyawan_dnl', $data);
	}

	function get_count_tlk($nik)
	{
		$query = $this->db->query("SELECT count(*) num FROM app_karyawan_tlk WHERE nik = '$nik'
  		AND tgl_tlk BETWEEN (SELECT from_date FROM app_cutoff) AND (SELECT thru_date FROM app_cutoff)");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_tlk_date($nik, $from, $to)
	{
		$query = $this->db->query("SELECT sum(hari) num FROM app_alfa WHERE nik = '$nik' AND kategori_cuti = '1' AND \"group\" = 'tlk' AND tgl_cuti BETWEEN '" . $from . "' AND '" . $to . "'");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_dnl($nik)
	{
		$query = $this->db->query("SELECT count(*) num FROM app_karyawan_dnl WHERE nik = '$nik'
  		AND tgl_dnl BETWEEN (SELECT from_date FROM app_cutoff) AND (SELECT thru_date FROM app_cutoff)");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_dnl_date($nik, $from, $to)
	{
		$query = $this->db->query("SELECT sum(hari) num FROM app_alfa WHERE nik = '$nik' AND kategori_cuti = '1' AND \"group\" = 'dnl' AND tgl_cuti BETWEEN '" . $from . "' AND '" . $to . "'");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_ck($nik)
	{
		$query = $this->db->query("SELECT count(a.*) num FROM app_alfa AS a
  		JOIN app_parameter AS b ON a.kategori_cuti::int = b.parameter_id AND b.parameter_group = 'absent'
  		WHERE a.nik = '$nik' AND b.parameter_id = '2' OR b.parameter_id = '3'
  		OR b.parameter_id = '4' OR b.parameter_id = '5'
  		AND a.tgl_cuti BETWEEN (SELECT from_date FROM app_cutoff) AND (SELECT thru_date FROM app_cutoff)");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_count_ck_date($nik, $from, $to)
	{
		$query = $this->db->query("SELECT count(a.*) num FROM app_alfa AS a
  		JOIN app_parameter AS b ON a.kategori_cuti::int = b.parameter_id AND b.parameter_group = 'absent'
  		WHERE a.nik = '$nik' AND b.parameter_id = '2' OR b.parameter_id = '3'
  		OR b.parameter_id = '4' OR b.parameter_id = '5'
  		AND a.tgl_cuti BETWEEN '" . $from . "' AND '" . $to . "'");
		$row = $query->row_array();
		return $row['num'];
	}

	function get_keterangan_by_nik($nik)
	{
		$query = $this->db->query("SELECT tgl_cuti, keterangan FROM app_alfa WHERE nik = '$nik' AND tgl_cuti BETWEEN (SELECT from_date FROM app_cutoff) AND (SELECT thru_date FROM app_cutoff)");

		return $query->row_array();
	}

	function get_cuti_by_tanggal($from_date, $nik)
	{
		//$this->db->where('a.group', 'cuti');
		$this->db->where('a.nik', $nik);
		$this->db->where('\'' . $from_date . '\' BETWEEN tgl_cuti AND tgl_cuti2', NULL, FALSE);
		$query = $this->db->get('app_alfa a');
		return $query;
	}

	function get_tlk_by_tanggal($from_date, $nik)
	{
		$query = $this->db->query("SELECT * FROM app_karyawan_tlk WHERE nik = '$nik' AND tgl_tlk = '$from_date'");

		return $query->row_array();
	}

	function get_dnl_by_tanggal($from_date, $nik)
	{
		$query = $this->db->query("SELECT * FROM app_karyawan_dnl WHERE nik = '$nik' AND tgl_dnl = '$from_date'");

		return $query->row_array();
	}

	function action_get_from_client($get_fp)
	{
		$this->db->set($get_fp);
		$this->db->insert('app_absen', $get_fp);
	}

	function action_get_from_client_($nik, $tanggal, $waktu)
	{
		$query = $this->db->query("INSERT INTO app_absen (nik, tanggal, waktu) VALUES ('$nik', '$tanggal', '$waktu')");
		return $query;
	}

	function action_get_nik_by_fp($nik)
	{
		$query = $this->db->query("SELECT a.nik, a.fullname FROM app_karyawan AS a
  		JOIN app_karyawan_detail AS b ON a.nik = b.nik WHERE thru_branch = '1'
  		AND a.nik NOT IN($nik)");
		return $query->result();
	}

	function check_existing_absensi($nik, $from_date)
	{
		$sql = "SELECT COUNT(*) AS jumlah FROM app_absensi_manual WHERE nik = '$nik' and periode_from_date = '$from_date'";

		$query = $this->db->query($sql);

		return $query->row_array();
	}

	function check_existing_staff($nik)
	{
		$sql = "SELECT COUNT(*) AS jumlah FROM app_karyawan WHERE nik = ?";

		$param = array($nik);

		$query = $this->db->query($sql, $param);

		return $query->row_array();
	}

	public function get_karyawan_by_cabang($id_cabang)
	{
		$this->db->select('
  		app_karyawan.nik, 
  		app_karyawan.fullname
  		');
		$this->db->where('app_karyawan_detail.status !=', '50');
		$this->db->where('app_karyawan_detail.thru_branch', $id_cabang);
		$this->db->join('app_karyawan_detail', 'app_karyawan_detail.nik = app_karyawan.nik', 'left');
		$this->db->order_by('app_karyawan.fullname', 'asc');
		return $this->db->get('app_karyawan');
	}

	public function get_karyawan_info_by_nik($nik)
	{
		$this->db->select('
  		app_karyawan.nik, 
  		app_karyawan.fullname,
  		param_cabang.description as cabang,
  		app_karyawan_detail.status as id_status,
  		param_status.description as status,
  		mutasi_status.from_date,
  		mutasi_status.thru_date
  		');
		$this->db->join('app_karyawan_detail', 'app_karyawan_detail.nik = app_karyawan.nik', 'left');
		$this->db->join('app_parameter as param_status', "param_status.parameter_id = app_karyawan_detail.status and param_status.parameter_group = 'status'", 'left');
		$this->db->join('app_parameter as param_cabang', "param_cabang.parameter_id = app_karyawan_detail.thru_branch and param_cabang.parameter_group = 'cabang'", 'left');
		$this->db->join('app_mutasi_status as mutasi_status', "mutasi_status.nik = app_karyawan.nik", 'left');
		$this->db->where('app_karyawan.nik', $nik);
		$this->db->order_by('mutasi_status.from_date', 'desc');
		return $this->db->get('app_karyawan', '1');
	}

	public function get_param_status()
	{
		$this->db->where('parameter_group', 'status');
		$this->db->order_by('parameter_id', 'asc');
		return $this->db->get('app_parameter');
	}

	public function insert_batch_absensi_manual($array_batch)
	{
		// echo '<pre>'.print_r($array_batch, 1).'</pre>';
		// exit;
		$sql          = "";
		$nama_field   = "";
		$datanya      = "";
		$object_count = count($array_batch);
		$arr_key = array_keys($array_batch);

		for ($i = 0; $i < $object_count; $i++) {
			echo $arr_key[$i] . "<br>";
		}


		print_r($arr_key);
		exit;

		$this->db->trans_start();
		for ($i = 0; $i < $object_count; $i++) {
			$nama_field .= $object[$i] . ",";
			$datanya .= "'..'";
		}

		$nama_field = rtrim($nama_field, ",");
		$sql .= "
  	INSERT INTO app_absensi_manual (" . $nama_field . ") VALUES (" . $datanya . ")
  	";
		$this->db->query($sql);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
}
