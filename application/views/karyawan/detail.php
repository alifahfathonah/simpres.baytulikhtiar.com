<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th colspan="2">
        <?php
        if(file_exists('assets/foto_karyawan/'.$arr_karyawan[0]['foto_karyawan'])){ 
          echo '<img src="'.base_url('assets/foto_karyawan/').$arr_karyawan[0]['foto_karyawan'].'" style="width:150px;">';
        }else{
          echo '<img src="'.base_url('assets/foto_karyawan/default_user.jpg').'" style="width:100px;">';
        }
        ?>  
      </th>
    </tr>
    <tr>
      <th>NIK</th>
      <th><?=$arr_karyawan[0]['nik'];?></th>
    </tr>
    <tr>
      <th>No Ktp</th>
      <th><?=$arr_karyawan[0]['no_ktp'];?></th>
    </tr>
    <tr>
      <th>Nama</th>
      <th><?=$arr_karyawan[0]['fullname'];?></th>
    </tr>
    <tr>
      <th>Tempat, Tanggal Lahir</th>
      <th><?=$arr_karyawan[0]['tmp_lahir'];?>, <?=$arr_karyawan[0]['tgl_lahir'];?></th>
    </tr>
    <tr>
      <th>Alamat</th>
      <th><?=$arr_karyawan[0]['alamat'];?></th>
    </tr>
    <tr>
      <th>Jenis Kelamin</th>
      <th>
        <?php
        $jk = $arr_karyawan[0]['jk'];

        if($jk == 'L'){
          echo "Laki-Laki";
        }else{
          echo "Perempuan";
        }
        ?>
      </th>
    </tr>
    <tr>
      <th>Status Pernikahan</th>
      <th>
        <?php
        $from_pernikahan = $arr_karyawan[0]['from_pernikahan'];

        if($from_pernikahan == 0){
          echo "Lajang";
        }elseif($from_pernikahan == 1){
          echo "Menikah";
        }else{
          echo "Lainya";
        }
        ?>  
      </th>
    </tr>
    <tr>
      <th>No HP</th>
      <th><?=$arr_karyawan[0]['no_hp'];?></th>
    </tr>
    <tr>
      <th>Sekolah Dasar</th>
      <th><?=$arr_karyawan[0]['sd'];?></th>
    </tr>
    <tr>
      <th>Sekolah Menengah Pertama</th>
      <th><?=$arr_karyawan[0]['smp'];?></th>
    </tr>
    <tr>
      <th>Sekolah Menengah Atas</th>
      <th><?=$arr_karyawan[0]['sma'];?></th>
    </tr>
    <tr>
      <th>Diploma</th>
      <th><?=$arr_karyawan[0]['diploma'];?></th>
    </tr>
    <tr>
      <th>Sarjana</th>
      <th><?=$arr_karyawan[0]['sarjana'];?></th>
    </tr>
    <tr>
      <th>Sertifikasi</th>
      <th><?=$arr_karyawan[0]['sertifikat'];?></th>
    </tr>
    <tr>
      <th>Lainnya</th>
      <th><?=$arr_karyawan[0]['lainnya'];?></th>
    </tr>
    <tr>
      <th>Status Karyawan</th>
      <th><?=$arr_karyawan[0]['status'];?></th>
    </tr>
    <tr>
      <th>Sisa Hak Cuti</th>
      <th><?=$arr_karyawan[0]['hak_cuti'];?></th>
    </tr>
    <tr>
      <th>Sisa Hak Ijin</th>
      <th><?=$arr_karyawan[0]['hak_ijin'];?></th>
    </tr>
    <tr>
      <th>Mulai Bergabung</th>
      <th><?=$arr_karyawan[0]['tgl_masuk'];?></th>
    </tr>
    <tr>
      <th>Masa Kerja</th>
      <th><?=$arr_karyawan[0]['from_date'];?> - <?=$arr_karyawan[0]['thru_date'];?></th>
    </tr>
    <tr>
      <th>Unit Kerja</th>
      <th><?=$arr_karyawan[0]['branch'];?></th>
    </tr>
    <tr>
      <th>Posisi Kerja</th>
      <th><?=$arr_karyawan[0]['position'];?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td></td>
    </tr>
  </tbody>
</table>