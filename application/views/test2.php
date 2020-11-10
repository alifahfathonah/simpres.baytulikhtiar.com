<style>
  body{
    font-family:Arial;
  }
  .table-sm{
    font-size: 11px;
  }
  .table-m{
    font-size: 14px;
  }
  .alignleft {
    float: left;
    clear: left;
  }
  .alignright {
    float: right;
    /*clear: right;*/
  }
  @media print {
    .pagebreak {
      clear: both;
      page-break-after: always;
    }
  }
</style>
<page orientation="p">
<?php
$i = 1;
foreach ($arr_karyawan->result() as $key) {
  if(($i % 2) == 0){
    $ccd = 'alignright';
  }else{
    $ccd = 'alignleft';
  }
  ?>
      <table class="<?=$ccd;?>">
        <tr>
          <td>
            <div>ABSENSI KARYAWAN</div>
            <div>KOPERASI BAYTUL IKHTIAR</div>
            <div>Nama: <?=$key->fullname;?></div>
          </td>
        </tr>
        <tr>
          <td>
            <table border="1" class="table-sm">
              <thead>
                <tr>
                  <th rowspan="2" align="center">Tanggal</th>
                  <th colspan="2" align="center">Absensi</th>
                  <th rowspan="2" align="center">Keterangan</th>
                </tr>
                <tr>
                  <th align="center">Masuk</th>
                  <th align="center">Keluar</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                foreach($get_laporan->result() as $values){
                  if($values->nik == $key->nik){
                    ?>
                    <tr>
                      <td><?=$values->tanggal; ?></td>
                      <td><?=$values->masuk; ?></td>
                      <td><?=$values->keluar; ?></td>
                      <td><?=$values->keterangan; ?></td>
                    </tr>
                    <?php
                  }
                }
                ?>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table class="table-m">
              <thead>
                <tr>
                  <th align="center">Membuat</th>
                  <th align="center" style="text-align:center;width:120px;">&nbsp;</th>
                  <th align="center">Memeriksa,</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td align="center">
                    <br><br><br><br><br>(..........................)
                  </td>
                  <td>&nbsp;</td>
                  <td align="center">
                    <br><br><br><br><br>(..........................)
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </table>
<?php

  if($total_karyawan == $i){
    echo '</page>';
  }else{
    if(($i % 4) == 0){
      echo '</page>';
      echo '<page orientation="p">';
    }

  }

  $i++;
}
?>