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
  DAFTAR PENGURUS DAN KARYAWAN
  </div>
  <div style="text-align:center;padding-top:10px;font-family:Arial;font-size:20px;">
  KOPERASI BAYTUL IKHTIAR CABANG/UNIT <?php echo $branch_user; ?>
  </div>
</div>
<hr />
<table id="hor-minimalist-b" align="center">
  <thead>
  	<tr>
      <td class="title" rowspan="2">No.</td>
      <td class="title" rowspan="2">NIK</td>
      <td class="title" rowspan="2">Nama</td>
      <td class="title" rowspan="2">Tempat Tanggal Lahir</td>
      <td class="title" rowspan="2">Alamat</td>
      <td class="title" rowspan="2">No. KTP</td>
      <td class="title" rowspan="2">Jenis Kelamin</td>
      <td class="title" rowspan="2">Status Pernikahan</td>
      <td class="title" rowspan="2">Perubahan Status Pernikahan</td>
      <td class="title" rowspan="2">Pendidikan Terakhir</td>
      <td class="title" rowspan="2">No. HP</td>
      <td class="title" rowspan="2">Mulai Bergabung</td>
      <td class="title" rowspan="2">Status Karyawan</td>
      <td class="title" rowspan="2">Posisi Awal</td>
      <td class="title" rowspan="2">Posisi Terakhir</td>
      <td class="title" rowspan="2">Cabang/Unit Awal</td>
      <td class="title" rowspan="2">Cabang/Unit Terakhir</td>
      <td class="title" rowspan="2">Periode Training</td>
      <td class="title" rowspan="2">Periode Kontrak 1</td>
      <td class="title" rowspan="2">Periode Kontrak 2</td>
      <td class="title" rowspan="2">Pengangkatan Karyawan Tetap</td>
      <td class="title" colspan="6">Perubahan Posisi</td>
      <td class="title" rowspan="2">Resign</td>
    </tr>
    <tr>
      <td class="title" >Posisi</td>
      <td class="title" >Tanggal</td>
      <td class="title" >Posisi</td>
      <td class="title" >Tanggal</td>
      <td class="title" >Posisi</td>
      <td class="title" >Tanggal</td>
    </tr>
  </thead>
  <tbody>
    <?php 
  $no = 1;
  foreach($get_laporan as $values)
  {
              if($values->sarjana != '-'){$pendidikan = $values->sarjana;}else if($values->diploma != '-'){$pendidikan = $values->diploma;}else if($values->sma != '-'){$pendidikan = $values->sma;}else if($values->smp != '-'){$pendidikan = $values->smp;}else if($values->sd != '-'){$pendidikan = $values->sd;}else{$pendidikan = '-';}

              if($values->from_pernikahan == '0'){$from_pernikahan = 'Lajang';}else if($values->from_pernikahan == '1'){$from_pernikahan = 'Menikah';}else if($values->from_pernikahan == '3'){$from_pernikahan = 'Lainnya';}else{$from_pernikahan = '';}

              if($values->thru_pernikahan == '0'){$thru_pernikahan = 'Lajang';}else if($values->thru_pernikahan == '1'){$thru_pernikahan = 'Menikah';}else if($values->thru_pernikahan == '3'){$thru_pernikahan = 'Lainnya';}else{$thru_pernikahan = '';}
  ?>    
    <tr>
      <td class="konten"><?php echo $no++; ?></td>
      <td class="konten"><?php echo $values->nik; ?></td>
      <td class="konten"><?php echo $values->fullname; ?></td>
      <td class="konten"><?php echo $values->tmp_lahir." ".$values->tgl_lahir; ?></td>
      <td class="konten"><?php echo $values->alamat; ?></td>
      <td class="konten"><?php echo $values->no_ktp; ?></td>
      <td class="konten"><?php echo $values->jk; ?></td>
      <td class="konten"><?php echo $pendidikan;?></td>
      <td class="konten"><?php echo $values->no_hp; ?></td>
      <td class="konten"><?php echo $values->tgl_masuk; ?></td>
      <td class="konten"><?php echo $values->post_status; ?></td>
      <td class="konten"><?php echo $values->from_position; ?></td>
      <td class="konten"><?php echo $values->from_branch;?></td>
      <td class="konten"><?php echo $values->periode_training;?></td>
      <td class="konten"><?php echo $values->periode_kontrak_1;?></td>
      <td class="konten"><?php echo $values->periode_kontrak_2;?></td>
      <td class="konten"> </td>
      <td class="konten"> </td>
      <td class="konten"> </td>
      <td class="konten"> </td>
      <td class="konten"> </td>
      <td class="konten"> </td>
      <td class="konten"> </td>
      <td class="konten"><?php echo $values->resign;?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
