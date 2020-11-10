<?php

class Model_webservice extends CI_Model {

	function regis_anggota($cif_no, $ibu_kandung, $tgl_lahir)
	{
		$sql = "SELECT cif_no, tgl_lahir, ibu_kandung, nama FROM mfi_cif
				WHERE cif_no = '$cif_no' AND ibu_kandung = '$ibu_kandung' AND tgl_lahir = '$tgl_lahir'";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function regis_anggota_($cif_no, $ibu_kandung, $tgl_lahir)
	{
		$sql2 = "SELECT count(*) jumlah FROM mfi_cif 
				WHERE cif_no = '$cif_no' AND ibu_kandung = '$ibu_kandung' AND tgl_lahir = '$tgl_lahir'";

		$query = $this->db->query($sql2);
		return $query->result();
	}

	function get_saldo_anggota($cif_no){
		$sql = "SELECT
		ac.tabungan_sukarela,
		ac.tabungan_wajib,
		ac.tabungan_kelompok,
		ac.setoran_lwk,
		ci.cif_no,
		ci.nama
		FROM mfi_account_default_balance AS ac
		JOIN mfi_cif AS ci ON(ac.cif_no = ci.cif_no)
		WHERE ac.cif_no = '$cif_no'";

		$param = array($cif_no);

		$query = $this->db->query($sql,$param);

		return $query->result_array();
	}

	function get_saldo_berencana_anggota($cif_no)
	{
		$sql = "SELECT sum(saldo_memo) saldo_taber FROM mfi_account_saving WHERE status_rekening = '1' AND cif_no = '$cif_no'";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function get_saldo_pembiayaan_anggota($cif_no)
	{
		$sql = "SELECT sum(saldo_pokok) saldo_pokok,
				sum(saldo_margin) saldo_margin
				FROM mfi_account_financing WHERE status_rekening = '1' AND cif_no = '$cif_no'";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function get_data_anggota($cif_no)
	{
		$sql = "SELECT tgl_gabung, nama, panggilan, kelompok, jenis_kelamin, ibu_kandung, tmp_lahir, tgl_lahir, no_ktp, telpon_seluler, 
						alamat, rt_rw, desa, kecamatan, kabupaten, kodepos FROM mfi_cif WHERE cif_no = '$cif_no'";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_count_par_all($from_date)
	{
		$sql = "SELECT count(par) FROM mfi_par WHERE tanggal_hitung = (SELECT max(tanggal_hitung) FROM mfi_par WHERE tanggal_hitung < (SELECT periode_awal FROM mfi_trx_kontrol_periode WHERE status = '1'))";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_count_par_lancar($from_date) 
	{
		$sql = "SELECT count(par) FROM mfi_par WHERE tanggal_hitung = (select max(tanggal_hitung) from mfi_par) AND par < '100'";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_count_anggota()
	{
		$sql = "SELECT count(*) FROM mfi_cif WHERE status = '1'";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function get_sum_saldo()
	{
		$sql = "SELECT sum(saldo_pokok) FROM mfi_account_financing WHERE status_rekening = '1'";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function get_count_majelis()
	{
		$sql = "SELECT count(*) FROM mfi_cm";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_count_cabang()
	{
		$sql = "SELECT count(*) FROM mfi_branch WHERE branch_class = '2'";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_sum_aset($from_date)
	{
		$sql = "SELECT sum(saldo) FROM mfi_closing_ledger_data WHERE closing_from_date = (select max(closing_from_date) from mfi_closing_ledger_data) AND account_code LIKE '1%'";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_sum_shu($from_date)
	{
		$sql = "SELECT sum(saldo * -1) FROM mfi_closing_ledger_data WHERE closing_from_date = (select max(closing_from_date) from mfi_closing_ledger_data) AND (account_code LIKE '4%' OR account_code LIKE '5%')";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_sum_modal($from_date)
	{
		$sql = "SELECT sum(saldo * -1) FROM mfi_closing_ledger_data WHERE closing_from_date = (select max(closing_from_date) from mfi_closing_ledger_data) AND account_code LIKE '3%'";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_outstanding()
	{
		$sql = "SELECT sum(saldo_pokok) FROM 
				mfi_account_financing WHERE status_rekening = '1'";

 		$query = $this->db->query($sql);
 		return $query->result_array();
	}

	function get_droping()
	{
		$sql = "SELECT sum(pokok) FROM 
				mfi_account_financing where status_rekening = '1'";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function get_saldo_awal_sukarela($cif_no, $from_date){
		$sql1 = "SELECT
		COALESCE(SUM(amount),0) AS amount
		FROM mfi_trx_tab_sukarela 
		WHERE flag_debet_credit = 'C' AND cif_no = '$cif_no' AND trx_date < '$from_date'";

		$param1 = array($cif_no);
		$query1 = $this->db->query($sql1,$param1);
		$row1 = $query1->row_array();
		$amount1 = $row1['amount'];

		$sql2 = "SELECT
		COALESCE(SUM(amount),0) AS amount
		FROM mfi_trx_tab_sukarela 
		WHERE flag_debet_credit = 'D' AND cif_no = '$cif_no' AND trx_date < '$from_date'";

		$param2 = array($cif_no);
		$query2 = $this->db->query($sql2,$param2);
		$row2 = $query2->row_array();
		$amount2 = $row2['amount'];

		$sql3 = "SELECT
		SUM(mfi_trx_cm_detail.tab_sukarela_cr - mfi_trx_cm_detail.tab_sukarela_db) AS amount
		FROM mfi_trx_cm_detail JOIN mfi_trx_cm ON(mfi_trx_cm_detail.trx_cm_id = mfi_trx_cm.trx_cm_id)
		WHERE mfi_trx_cm_detail.cif_no = '$cif_no' AND mfi_trx_cm.trx_date < '$from_date'";

		$param3 = array($cif_no);
		$query3 = $this->db->query($sql3,$param3);
		$row3 = $query3->row_array();
		$amount3 = $row3['amount'];

		$sql4 = "SELECT
		SUM(amount) AS amount
		FROM mfi_trx_shu_sukarela
		WHERE cif_no = '$cif_no' AND trx_date < '$from_date'";

		$param4 = array($cif_no);
		$query4 = $this->db->query($sql4,$param4);
		$row4 = $query4->row_array();
		$amount4 = $row4['amount'];

		$saldo_awal = $amount1 + $amount2 + $amount3 + $amount4;

		return $saldo_awal;
	}

	function get_statement_sukarela($cif_no, $from_date, $thru_date){
		$sql = "select 
				trx_date,
				created_stamp,
				(case when trx_type='1' then 'TRX ANGGOTA KELUAR (TAB.WAJIB)'
				      when trx_type='2' then 'TRX ANGGOTA KELUAR (TAB.KELOMPOK)'
				      when trx_type='3' then 'TRX ANGGOTA KELUAR (TAB.BERENCANA)'
				      when trx_type='4' then 'PENCAIRAN TABUNGAN BERENCANA No.'||account_saving_no
				      when trx_type='5' then 'PELUNASAN PEMBIAYAAN No.Rek.'||account_financing_no
				      when trx_type='6' then 'TRX ANGGOTA KELUAR (BONUS)'
				      when trx_type='7' then 'BONUS (TAB.BERENCANA) No.Rek.'||account_saving_no
				      when trx_type='8' then 'BONUS AKHIR TAHUN' end) as description,
				(case when flag_debet_credit='C' then amount else 0 end) as amount_credit,
				(case when flag_debet_credit='D' then amount else 0 end) as amount_debit
				from mfi_trx_tab_sukarela
				where trx_date between '$from_date' and '$thru_date' and cif_no = '$cif_no'
				union all
				select
				b.trx_date,
				b.created_date as created_stamp,
				'SETORAN TABUNGAN' as description,
				a.tab_sukarela_cr as amount_credit,
				0 as amount_debit
				from mfi_trx_cm_detail a, mfi_trx_cm b
				where b.trx_cm_id=a.trx_cm_id and a.tab_sukarela_cr > 0
				and b.trx_date between '$from_date' and '$thru_date' and a.cif_no = '$cif_no'
				union all
				select
				b.trx_date,
				b.created_date as created_stamp,
				'PENARIKAN TABUNGAN' as description,
				0 as amount_credit,
				a.tab_sukarela_db as amount_debit
				from mfi_trx_cm_detail a, mfi_trx_cm b
				where b.trx_cm_id=a.trx_cm_id and a.tab_sukarela_db > 0
				and b.trx_date between '$from_date' and '$thru_date' and a.cif_no = '$cif_no'
				union all
				select
				trx_date,
				created_stamp,
				'SHU' as description,
				amount as amount_credit,
				'0' as amount_debit
				from mfi_trx_shu_sukarela
				where trx_date between '$from_date' and '$thru_date' and cif_no = '$cif_no'
				order by 1,2 asc";

		$query = $this->db->query($sql);

		return $query->result_array();
	}

	function get_saldo_awal_kelompok($cif_no, $from_date)
	{
		$sql = "SELECT SUM(a.tab_kelompok_cr*a.freq) AS s
				FROM mfi_trx_cm_detail a
				INNER JOIN mfi_trx_cm b ON b.trx_cm_id=a.trx_cm_id
				WHERE date(b.trx_date) < '$from_date' AND a.cif_no = '$cif_no' ";

 		$query = $this->db->query($sql);
 		return $query->result_array();
	}

	function get_statement_tab_kelompok($cif_no, $from_date, $thru_date)
	{
		$sql = "SELECT date(b.created_date) created_date, a.tab_kelompok_cr,a.freq
				FROM mfi_trx_cm_detail a
				INNER JOIN mfi_trx_cm b ON b.trx_cm_id=a.trx_cm_id
				WHERE date(b.trx_date) between '$from_date' AND '$thru_date' AND a.cif_no = '$cif_no' ORDER BY 1 ";

 		$query = $this->db->query($sql);
 		return $query->result_array();
	}

	function get_saldo_awal_wajib($cif_no, $from_date)
	{
		$sql = "SELECT SUM(a.tab_wajib_cr*a.freq) AS s
				FROM mfi_trx_cm_detail a
				INNER JOIN mfi_trx_cm b ON b.trx_cm_id=a.trx_cm_id
				WHERE date(b.created_date) < '$from_date' AND a.cif_no = '$cif_no' ";

 		$query = $this->db->query($sql);
 		return $query->result_array();
	}

	function get_statement_tab_wajib($cif_no, $from_date, $thru_date)
	{
		$sql = "SELECT date(b.created_date) created_date, a.tab_wajib_cr,a.freq
				FROM mfi_trx_cm_detail a
				INNER JOIN mfi_trx_cm b ON b.trx_cm_id=a.trx_cm_id
				WHERE date(b.created_date) between '$from_date' AND '$thru_date' AND a.cif_no = '$cif_no' ORDER BY 1 ";

 		$query = $this->db->query($sql);
 		return $query->result_array();
	}

	function get_statement_tab_berencana($cif_no)
	{
		$sql = "select
		a.tanggal as trx_date,
		a.description,
		0 as amount_debit,
		a.amount as amount_credit
		from mfi_trx_konversi_saving a,mfi_product_saving b,mfi_account_saving c
		where a.product_code=b.product_code
		and b.jenis_tabungan='1'
		and a.cif_no=c.cif_no
		and a.product_code=c.product_code
		and a.tanggal>c.tanggal_buka
		and a.flag_debit_credit='C'
		and c.account_saving_no = '301000207000416000201'
		and a.tanggal between '01/01/2017' and '01/04/2017'

		union all

		select
		trx_date,
		description,
		amount as amount_debit,
		0 as amount_credit
		from mfi_trx_account_saving a
		left join mfi_account_saving b on b.account_saving_no=a.account_saving_no
		where a.flag_debit_credit='D' and a.trx_saving_type='5' and b.account_saving_no='301000207000416000201' and a.trx_date between '01/01/2017' and '01/04/2017'

		union all


		select
		d.trx_date,
		'SETORAN TABUNGAN'||' '||UPPER(f.nick_name) as description,
		0 as amount_debit,
		sum(a.amount*a.freq) as amount_credit
		from mfi_trx_cm_detail_savingplan_account a, mfi_trx_cm_detail_savingplan b, mfi_trx_cm_detail c, mfi_trx_cm d, mfi_account_saving e, mfi_product_saving f
		where a.trx_cm_detail_savingplan_id=b.trx_cm_detail_savingplan_id
		and b.trx_cm_detail_id=c.trx_cm_detail_id
		and c.trx_cm_id=d.trx_cm_id
		and a.account_saving_no=e.account_saving_no
		and e.product_code=f.product_code
		and a.account_saving_no='301000207000416000201'
		and d.trx_date between '01/01/2017' and '01/04/2017'
		and a.flag_debet_credit='C'
		and a.freq>0
		group by 1,2,3

		union all

		select
		d.trx_date,
		'TRX KELUAR#PENCAIRAN TABUNGAN'||' '||UPPER(f.nick_name) as description,
		sum(b.amount*b.freq) as amount_debit,
		0 as amount_credit
		from mfi_trx_cm_detail_savingplan a
		join mfi_trx_cm_detail_savingplan_account b on b.trx_cm_detail_savingplan_id=a.trx_cm_detail_savingplan_id
		join mfi_trx_cm_detail c on c.trx_cm_detail_id=a.trx_cm_detail_id
		join mfi_trx_cm d on d.trx_cm_id=c.trx_cm_id
		join mfi_account_saving e on e.account_saving_no=b.account_saving_no
		join mfi_product_saving f on f.product_code=e.product_code
		where e.account_saving_no='301000207000416000201'
		and d.trx_date between '01/01/2017' and '01/04/2017'
		and b.flag_debet_credit='D'
		and b.amount*b.freq > 0
		group by 1,2,4
		order by 1 asc ";

 		$query = $this->db->query($sql);
 		return $query->result_array();
	}

	function get_saldo_awal_berencana($cif_no)
	{
		$sql = "select sum(amount) as s from (
					
					select 
						sum(a.amount) as amount
					from mfi_trx_konversi_saving a,mfi_account_saving b,mfi_product_saving c
					where a.cif_no=b.cif_no
					and a.product_code=b.product_code
					and a.tanggal>b.tanggal_buka
					and b.product_code=c.product_code
					and c.jenis_tabungan='1'
					and a.flag_debit_credit='C'
					and b.account_saving_no = '301000207000416000201'
					and a.tanggal<'01/01/2017'
					
					union all
					
					select 
						sum(a.amount)*-1 as amount
					from mfi_trx_account_saving a
					left join mfi_account_saving b on b.account_saving_no=a.account_saving_no
					where a.flag_debit_credit='D'
					and a.trx_saving_type='5'
					and b.account_saving_no='301000207000416000201'
					and a.trx_date<'01/01/2017'
					
					union all
					
					select
						sum(b.freq*b.amount) as amount
					from mfi_trx_cm_detail_savingplan a
					join mfi_trx_cm_detail_savingplan_account b on b.trx_cm_detail_savingplan_id=a.trx_cm_detail_savingplan_id
					join mfi_trx_cm_detail c on c.trx_cm_detail_id=a.trx_cm_detail_id
					join mfi_trx_cm d on d.trx_cm_id=c.trx_cm_id
					join mfi_account_saving e on e.account_saving_no=b.account_saving_no
					join mfi_product_saving f on f.product_code=e.product_code
					where e.account_saving_no='301000207000416000201'
					and d.trx_date<'01/01/2017'
					and b.flag_debet_credit='C'
					
					union all
					
					select
						sum(b.freq*b.amount)*-1 as amount
					from mfi_trx_cm_detail_savingplan a
					join mfi_trx_cm_detail_savingplan_account b on b.trx_cm_detail_savingplan_id=a.trx_cm_detail_savingplan_id
					join mfi_trx_cm_detail c on c.trx_cm_detail_id=a.trx_cm_detail_id
					join mfi_trx_cm d on d.trx_cm_id=c.trx_cm_id
					join mfi_account_saving e on e.account_saving_no=b.account_saving_no
					join mfi_product_saving f on f.product_code=e.product_code
					where e.account_saving_no='301000207000416000201'
					and d.trx_date<'01/01/2017'
					and b.flag_debet_credit='D'

				) as saldo_awal_tab_berencana";

 		$query = $this->db->query($sql);
 		return $query->result_array();
	}

	function regis_investor($cif_no, $ibu_kandung, $tgl_lahir)
	{
		$sql = "SELECT cif_no, tgl_lahir, ibu_kandung, nama FROM mfi_cif
				WHERE cif_no = '$cif_no' AND ibu_kandung = '$ibu_kandung' AND tgl_lahir = '$tgl_lahir'";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function regis_investor_($cif_no, $ibu_kandung, $tgl_lahir)
	{
		$sql2 = "SELECT count(*) jumlah FROM mfi_cif 
				WHERE cif_no = '$cif_no' AND ibu_kandung = '$ibu_kandung' AND tgl_lahir = '$tgl_lahir'";

		$query = $this->db->query($sql2);
		return $query->result();
	}

	function action_regis_deposit($data)
	{
		$this->db->insert('mfi_cif', $data);
	}

	function action_regis_pembiayaan($data)
	{
		$this->db->insert('mfi_partner', $data);
	}

	function get_deposito_investor($cif_no)
	{
		$sql = "SELECT coalesce(sum(nominal), 0) deposito FROM mfi_account_deposit WHERE status_rekening = '1' AND cif_no = '$cif_no'";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_tabungan_investor($cif_no)
	{
		$sql = "SELECT coalesce(sum(saldo_memo), 0) tabungan FROM mfi_account_saving WHERE status_rekening = '1' AND cif_no = '$cif_no'";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_pembiayaan()
	{
		$sql = "SELECT b.display_text, a.peruntukan, count(a.peruntukan) jumlah, sum(a.amount) nominal, min(a.amount) min, max(a.amount) max
				FROM mfi_account_financing_reg AS a JOIN mfi_list_code_detail AS b ON(a.peruntukan = b.code_value::integer AND b.code_group = 'peruntukan') 
				WHERE status = '0' GROUP BY 1,2 ORDER BY 2 DESC";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_desplay($display_text)
	{
		$sql = "SELECT * FROM mfi_list_code_detail WHERE display_text = '$display_text'";

		$query = $this->db->query($sql);
		return $query->result();
	}

		function action_get_from_client()
		{
			$query = $this->db->query("SELECT * FROM tbkaryawan");
			return $query->result();
		}

}