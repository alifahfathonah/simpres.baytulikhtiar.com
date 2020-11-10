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
	font-size: 10px;
	font-weight: bold;
	color: #000;
	padding: 6px;
	border: 1px solid #262626;
	text-align: center;
}

#hor-minimalist-b .konten {
	font-size: 9px;
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
  LAPORAN CUTI/IJIN KARYAWAN
  </div>
  <div style="text-align:center;padding-top:10px;font-family:Arial;font-size:20px;">
  KOPERASI BAYTUL IKHTIAR
  </div>
</div>
<hr />
<table id="hor-minimalist-b" align="center">
  <thead>
  	<tr>
      <td class="title">NIK</td>
      <td class="title" style="width: 120px">Nama</td>
      <td class="title">Tanggal Cuti</td>
      <td class="title">Kategori Cuti</td>
      <td class="title">Keterangan</td>
      <td class="title">Approve By</td>
    </tr>
  </thead>
  <tbody>
    <?php 
  $no = 1;
  foreach($get_laporan as $values)
  {
  ?>    
    <tr>
      <td class="konten"><?php echo $values->nik; ?></td>
      <td class="konten" style="width: 120px"><?php echo $values->fullname; ?></td>
      <td class="konten"><?php echo $values->tgl_cuti; ?></td>
      <td class="konten"><?php echo $values->kategori_cuti; ?></td>
      <td class="konten"><?php echo $values->keterangan; ?></td>
      <td class="konten"><?php echo $values->aprove;?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
