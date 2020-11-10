<?php 
  $CI = get_instance();
?>
<style type="text/css">
<!--
#hor-minimalist-b
{
  
  background: #fff;
  margin: 10px;
  margin-top: 10px;
  border-collapse: collapse;
  text-align: left;
}
#hor-minimalist-b .title {
	font-size: 11px;
	font-weight: bold;
	color: #000;
	padding: 6px;
	border: 1px solid #262626;
	text-align: center;
}

#hor-minimalist-b .konten {
	font-size: 10px;
	color: #000;
	padding: 7px;
	border: 1px solid #262626;
	text-align: center;
}

#hor-minimalist-b .nominal {
	font-size: 9px;
	color: #000;
	padding: 7px;
	border: 1px solid #262626;
	text-align: right;
}

#hor-minimalist-b .total_saldo {
	font-size: 9px;
	font-weight: bold;
	color: #000;
	padding: 7px;
	border: 1px solid #262626;
	text-align: right;
}

#hor-minimalist-b .zero {
	font-size: 9px;
	font-weight: bold;
	color: #000;
	padding: 7px;
	border-right: 1px solid #262626;
	text-align: center;
}

-->
</style>
<div style="width:100%;">
  <div style="text-align:center;padding-top:10px;font-family:Arial;font-size:20px;">
  LAPORAN MUTASI CABANG/UNIT KARYAWAN
  </div>
  <div style="text-align:center;padding-top:10px;font-family:Arial;font-size:20px;">
  KOPERASI BAYTUL IKHTIAR
  </div>
  <div style="text-align:center;padding-top:10px;font-family:Arial;font-size:20px;">
  TANGGAL <?php echo $from_date;?> s/d <?php echo $thru_date;?>
  </div>
</div>
<hr />
<table id="hor-minimalist-b" align="center">
  <thead>
  	<tr>
      <td class="title">Tanggal</td>
      <td class="title">NIK</td>
      <td class="title" style="width: 120px">Nama</td>
      <td class="title">Cabang/Unit Awal</td>
      <td class="title">Cabang/Unit Terakhir</td>
    </tr>
  </thead>
  <tbody>
    <?php 
  $no = 1;
  foreach($get_laporan as $values)
  {
  ?>    
    <tr>
      <td class="konten"><?php echo substr($values->created_date, 0, 10); ?></td>
      <td class="konten"><?php echo $values->nik; ?></td>
      <td class="konten" style="width: 120px"><?php echo $values->fullname; ?></td>
      <td class="konten"><?php echo $values->from_branch; ?></td>
      <td class="konten"><?php echo $values->thru_branch; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
