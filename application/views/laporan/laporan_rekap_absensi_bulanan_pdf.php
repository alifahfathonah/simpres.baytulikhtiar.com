<?php 
  $CI = get_instance();
?>
<style type="text/css">
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
</style>
<div style="width:100%;">
  <div style="text-align:center;padding-top:10px;font-family:Arial;font-size:20px;">
  REKAPITULASI ABSENSI
  </div>
  <div style="text-align:center;padding-top:10px;font-family:Arial;font-size:20px;">
  KARYAWAN KOPERASI BAYTUL IKHTIAR
  </div>
  <div style="text-align:center;padding-top:10px;font-family:Arial;font-size:18px;">
  <?php echo $branch_user; ?>
  </div>
  <div style="text-align:center;padding-top:10px;font-family:Arial;font-size:18px;">
  PERIODE :
  </div>
  <div style="text-align:center;padding-top:10px;font-family:Arial;font-size:18px;">
  <?php echo $periode;?>
  </div>
</div>
<hr />
<table id="hor-minimalist-b" align="center">
  <thead>
  	<tr>
      <td class="title" rowspan="4">No.</td>
      <td class="title" rowspan="4">Nama</td>
      <td class="title" rowspan="4">Posisi</td>
      <td class="title" colspan="11">Presensi</td>
    </tr>
    <tr>
      <td class="title" colspan="5">Kedatangan</td>
      <td class="title" colspan="5">Kepulangan</td>
      <td class="title">Lembur</td>
    </tr>
    <tr>
      <td class="title" >Tepat Waku</td>
      <td class="title" >Kurang 15mnt</td>
      <td class="title" >s/d 30mnt</td>
      <td class="title" >Lbh dr 30mnt</td>
      <td class="title" >Total Datang</td>
      <td class="title" >Tepat Waku</td>
      <td class="title" >Kurang 15mnt</td>
      <td class="title" >s/d 30 mnt</td>
      <td class="title" >Lbh dr 30mnt</td>
      <td class="title" >Total Pulang</td>
      <td class="title" rowspan="2">Total</td>
    </tr>
    <tr>
      <td class="title" >s/d 08:00</td>
      <td class="title" >08:01 s/d 08:15</td>
      <td class="title" >08:16 s/d 08:30</td>
      <td class="title" >08:31 s/d up</td>
      <td class="title" >Hr</td>
      <td class="title" >17 s/d up</td>
      <td class="title" >16:59 s/d 16:45</td>
      <td class="title" >16:44 s/d 16:31</td>
      <td class="title" >Sblm 16:30</td>
      <td class="title" >Hr</td>
    </tr>
  </thead>
  <tbody>
    <?php 
  $no = 1;
  $i = count($arr);
  foreach($arr as $data)
  {
    $fullname      = $data['fullname'];
    $position_name = $data['position'];
    $m_tepat_waktu = $data['m_tepat_waktu'];
    $m_telat_1     = $data['m_telat_1'];
    $m_telat_2     = $data['m_telat_2'];
    $m_telat_3     = $data['m_telat_3'];
    $sum_masuk     = $m_tepat_waktu + $m_telat_1 + $m_telat_2 + $m_telat_3;
    $k_tepat_waktu = $data['k_tepat_waktu'];
    $k_telat_1     = $data['k_telat_1'];
    $k_telat_2     = $data['k_telat_2'];
    $k_telat_3     = $data['k_telat_3'];
    $sum_keluar    = $k_tepat_waktu + $k_telat_1 + $k_telat_2 + $k_telat_3;
    $total_jam     = $data['total_jam'];
  ?>    
    <tr>
      <td class="konten"><?php echo $no++; ?></td>
      <td class="konten"><?php echo $fullname; ?></td>
      <td class="konten"><?php echo $position_name; ?></td>
      <td class="konten"><?php echo $m_tepat_waktu; ?></td>
      <td class="konten"><?php echo $m_telat_1; ?></td>
      <td class="konten"><?php echo $m_telat_2; ?></td>
      <td class="konten"><?php echo $m_telat_3; ?></td>
      <td class="konten"><?php echo $sum_masuk;?></td>
      <td class="konten"><?php echo $k_tepat_waktu; ?></td>
      <td class="konten"><?php echo $k_telat_1; ?></td>
      <td class="konten"><?php echo $k_telat_2; ?></td>
      <td class="konten"><?php echo $k_telat_3; ?></td>
      <td class="konten"><?php echo $sum_keluar;?></td>
      <td class="konten"><?php echo $total_jam;?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
