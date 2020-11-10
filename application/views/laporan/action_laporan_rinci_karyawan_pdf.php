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
   font-size: 13px;
   font-weight: bold;
   color: #000;
   padding: 6px;
   text-align: center;
 }

 #hor-minimalist-b .konten {
   font-size: 12px;
   color: #000;
   padding: 7px;
   border-bottom: 1px dotted #262626;
   text-align: center;
   width: 300px;
   height: 3px;
 }

 #hor-minimalist-b .separator {
  font-size: 12px;
  color: #000;
  padding: 7px;
  text-align: center;
  width: 300px;
}

#hor-minimalist-b .nominal {
  font-size: 13px;
  color: #000;
  padding: 7px;
  border: 1px solid #262626;
  text-align: right;
}

#hor-minimalist-b .total_saldo {
  font-size: 13px;
  font-weight: bold;
  color: #000;
  padding: 7px;
  border: 1px solid #262626;
  text-align: right;
}

#hor-minimalist-b .zero {
  font-size: 13px;
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
    LAPORAN PROFIL KARYAWAN
  </div>
  <div style="text-align:center;padding-top:10px;font-family:Arial;font-size:20px;">
    KOPERASI BAYTUL IKHTIAR
  </div>
</div>
<hr />
<?php 
foreach($get_laporan as $values){ 
  if($values->foto_karyawan != ''){
    ?>
    <img style="height: 150px; margin-left: 290px; margin-bottom: 10px; margin-top: 20px;" src="<?php echo base_url();?>assets/foto_karyawan/<?php echo $values->foto_karyawan;?>">
  <?php }else{?>
    <img style="height: 150px; margin-left: 290px; margin-bottom: 10px; margin-top: 20px;" src="<?php echo base_url();?>assets/foto_karyawan/default_user.jpg">
  <?php }?>
  <table id="hor-minimalist-b" style="margin-left: 110px">
    <tr><td class="title">Data Pribadi</td></tr>
    <tr>
      <td class="title" align="right">NIK</td>
      <td class="konten" align="left"><?php echo $values->nik;?></td>
    </tr>
    <tr>
      <td class="title" align="right">No. KTP</td>
      <td class="konten" align="left"><?php echo $values->no_ktp;?></td>
    </tr>
    <tr>
      <td class="title" align="right">Nama</td>
      <td class="konten" align="left"><?php echo $values->fullname;?></td>
    </tr>
    <tr>
      <td class="title" align="right">Tempat Tanggal Lahir</td>
      <td class="konten" align="left"><?php echo $values->tmp_lahir;?>, <?php echo $values->tgl_lahir;?></td>
    </tr>
    <tr>
      <td class="title" align="right">Alamat</td>
      <td class="konten" align="left"><?php echo $values->alamat;?></td>
    </tr>
    <tr>
      <td class="title" align="right">Jenis Kelamin</td>
      <td class="konten" align="left"><?php if($values->jk == 'L'){echo "Laki - laki";}else if($values->jk == 'P'){echo "Perempuan";}?></td>
    </tr>
    <tr>
      <td class="title" align="right">Status Pernikahan</td>
      <td class="konten" align="left"><?php if($values->thru_pernikahan == ''){ if($values->from_pernikahan == '0'){echo "Lajang";}else if($values->from_pernikahan == '1'){echo "Menikah";}else if($values->from_pernikahan == '2'){echo "Lainnya";}}else{if($values->thru_pernikahan == '0'){echo "Lajang";}else if($values->thru_pernikahan == '1'){echo "Menikah";}else if($values->thru_pernikahan == '2'){echo "Lainnya";}} ?></td>
    </tr>
    <tr>
      <td class="title" align="right">No. HP</td>
      <td class="konten" align="left"><?php echo $values->no_hp;?></td>
    </tr>
    <tr><td class="title" style="padding-top: 20px">Data Pendidikan</td></tr>
    <tr>
      <td class="title" align="right">Sekolah Dasar</td>
      <td class="konten" align="left"><?php echo $values->sd;?></td>
    </tr>
    <tr>
      <td class="title" align="right">Sekolah Menengah Pertama</td>
      <td class="konten" align="left"><?php echo $values->smp;?></td>
    </tr>
    <tr>
      <td class="title" align="right">Sekolah Menengah Atas</td>
      <td class="konten" align="left"><?php echo $values->sma;?></td>
    </tr>
    <tr>
      <td class="title" align="right">Diploma</td>
      <td class="konten" align="left"><?php echo $values->diploma;?></td>
    </tr>
    <tr>
      <td class="title" align="right">Sarjana</td>
      <td class="konten" align="left"><?php echo $values->sarjana;?></td>
    </tr>
    <tr>
      <td class="title" align="right">Sertifikat</td>
      <td class="konten" align="left"><?php echo $values->sertifikat;?></td>
    </tr>
    <tr>
      <td class="title" align="right">Lainnya</td>
      <td class="konten" align="left"><?php echo $values->lainnya;?></td>
    </tr>
    <tr><td class="title" style="padding-top: 20px">Data Status Karyawan</td></tr>
    <tr>
      <td class="title" align="right">Mulai Bergabung</td>
      <td class="konten" align="left"><?php echo $values->tgl_masuk;?></td>
    </tr>
    <tr>
      <td class="title" align="right">Status Karyawan</td>
      <td class="konten" align="left"><?php echo $values->post_status;?></td>
    </tr>
    <tr>
      <td class="title" align="right">Periode Kontrak</td>
      <td class="konten" align="left"><?php echo $values->from_periode?> s/d <?php echo $values->thru_periode?></td>
    </tr>
    <tr>
      <td class="title" align="right">Posisi Kerja</td>
      <td class="konten" align="left"><?php echo $values->position;?></td>
    </tr>
    <tr>
      <td class="title" align="right">Cabang/Unit Kerja</td>
      <td class="konten" align="left"><?php echo $values->branch;?></td>
    </tr>
  <?php }?>
</table>
