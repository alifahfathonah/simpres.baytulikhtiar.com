<div class="page-content" style="min-height: 860px;">
  <div class="theme-panel hidden-xs hidden-sm">
  </div>
  <div class="page-bar">
    <ul class="page-breadcrumb">
      <li>
        <a href="index.html">Home</a>
        <i class="fa fa-circle"></i>
      </li>
      <li>
        <span>Absen Karyawan</span>
      </li>
    </ul>
  </div>
  <h1 class="page-title"> Absen Karyawan
    <small>Detail Perkaryawan</small>
  </h1>
  <div class="row">
    <div class="col-md-12">
      <div class="portlet light bordered">

        <?php 
        print_r($get_karyawan_by_nik);
        foreach($get_karyawan_by_nik as $values)
        {
          $nik = $values->nik;
          $nama = $values->fullname;

          if($nik == '')
          {
            echo "<script>alert('NIK tidak ditemukan!!');history.go(-1)</script>";  
          }
        }?>

        <div class="portlet-title">
          <form action="<?php echo site_url();?>absensi/get_absen_by_nik" role="form" method="post">
            <div class="form-group col-md-6">
              <label for="single-prepend-text" class="control-label">Karyawan</label>
              <div class="input-group select2-bootstrap-prepend">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                    <span class="glyphicon glyphicon-search"></span>
                  </button>
                </span>
                <select id="single-prepend-text" class="form-control select2" name="nik" required>
                  <option></option>
                  <?php foreach($get_karyawan->result() as $values){?>
                    <option value="<?php echo $values->nik;?>"><?php echo $values->nik;?> - <?php echo $values->fullname;?></option>
                  <?php }?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <button type="submit" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 24px">Show</button>
              <a href="<?php echo site_url();?>absensi/load/<?php echo $nik;?>"><button type="button" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 24px;">Import FP</button></a>
              <a href="<?php echo site_url();?>absensi/load_all"><button type="button" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 24px">Import All FP</button></a>
              <a href="<?php echo site_url();?>absensi"><button type="button" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 24px">Back</button></a>
              <!--<button type="button" class="btn red-mint btn-outline sbold uppercase" style="margin-top: 24px; margin-left: 15px">Show All</button>-->
            </div>
          </form>
        </div>

        <div class="portlet-body">
          <div class="table-scrollable">
            <form  method="post" action="<?php echo site_url(); ?>absensi/action_add_manual/<?php echo $nik;?>" role="form">
              <table class="table table-hover">
                <tbody>
                  <tr>
                    <th> Periode Cutoff </th>
                    <td> : <?php foreach($periode_cutoff as $values){echo $values->from_date.' s/d '.$values->thru_date; break;}?></td>
                  </tr>
                  <tr>
                    <th> NIK </th>
                    <td> : <?php echo $nik;?> </td>
                  </tr>
                  <tr>
                    <th> Nama </th>
                    <td> : <?php echo $nama;?> </td>
                  </tr>
                  <tr>
                    <th rowspan="2" style="text-align: center;">Tanggal</th>
                    <th colspan="2" style="text-align: center;">Waktu</th>
                    <th rowspan="2" style="text-align: center;">Keterangan</th>
                  </tr>
                  <tr>
                    <th style="text-align: center;">Masuk</th>
                    <th style="text-align: center;">Keluar</th>
                  </tr>


                  <?php 
                  foreach($periode_cutoff as $tbpengaturan)
                  {
                    $awal = $tbpengaturan->from_date;
                    $akhir = $tbpengaturan->thru_date;
                  }

                  if($get_count_absen_by_nik == '0')
                  {
                    ?>
                    <div class="m-heading-1 border-green m-bordered">
                      <h3>Warning!!</h3>
                      <p> Mohon input kembali periode cutoff.</p>
                    </div>
                    <?php 
                  }else
                  {
                    $i = 0;
                    foreach($get_presensi_by_nik as $values)                  
                    {
                      if($values->masuk == '' && $values->keluar == '' && $values->tanggal < date('Y-m-d'))
                      {
                        ?>
                        <tr style="text-align: center;">
                          <td><?php echo $values->tanggal;?></td>
                          <input type="text" value="<?php echo $values->tanggal?>" name="tanggal<?php echo $i;?>" hidden="hidden">
                          <td><input class="form-control" placeholder="HH:MM:DD" style="text-align: center;" id="mask_number" type="text" name="datang<?php echo $i;?>" <?php if($values->keterangan != ''){echo "readonly='readonly'";}?>></td>
                          <td><input class="form-control" placeholder="HH:MM:DD" style="text-align: center;" id="mask_number" type="text" name="pulang<?php echo $i;?>" <?php if($values->keterangan != ''){echo "readonly='readonly'";}?>></td>
                          <td><input class="form-control" type="text" name="keterangan<?php echo $i;?>" <?php if($values->keterangan != ''){echo "value='".$values->keterangan."' readonly='readonly' style='color: red; text-align: center'";}else{echo "value='Tidak Hadir' style='text-align: center'";}?>></td>
                        </tr>
                        <?php   

                        $i++;
                      }else{
                        ?>
                        <tr style="text-align: center;">
                          <td><?php echo $values->tanggal;?></td>
                          <input type="text" value="<?php echo $values->tanggal?>" name="tanggal<?php echo $i;?>" hidden="hidden">
                          <td><input placeholder="HH:MM:DD" id="mask_number" type="text" name="datang<?php echo $i;?>" <?php if($values->masuk != ''){ if($values->masuk >= '01:00:00' && $values->masuk <= '08:01:00'){echo "value='".$values->masuk."' readonly='readonly' class='form-control'";}else if($values->masuk > '08:01:00' && $values->masuk < '12:00:00'){ echo "value='".$values->masuk."' readonly='readonly' class='form-control' style='color: red; text-align: center'";}else if($values->masuk == '12:00:00'){echo "value='".$values->masuk."' class='form-control'";}}else{ if($values->keterangan != ''){echo "value='' readonly='readonly' class='form-control'";}else{ echo "value='' class='form-control'";}}?> style="text-align: center;"></td>
                          <td><input placeholder="HH:MM:DD" id="mask_number" type="text" name="pulang<?php echo $i;?>" <?php if($values->keluar != ''){ if($values->keluar == '12:00:00'){echo "value='".$values->keluar."' class='form-control'";}else if($values->keluar > '12:00:00' && $values->keluar < '17:00:00'){echo "value='".$values->keluar."' readonly='readonly'  class='form-control' style='color: red; text-align: center'";}else{echo "value='".$values->keluar."' readonly='readonly'  class='form-control'";}}else{if($values->keterangan != ''){echo "value='' readonly='readonly' class='form-control'";}else{echo "value='' class='form-control'";}}?> style="text-align: center;"></td>
                          <td><input class="form-control" type="text" name="keterangan<?php echo $i;?>" <?php if($values->keterangan != ''){if(substr($values->keterangan, 0, 2) == 'TL'){echo "value='".substr($values->keterangan, 2)."' readonly='readonly' style='text-align: center'";}else{echo "value='".$values->keterangan."' readonly='readonly' style='color: red; text-align: center'";}}else{ if($values->masuk != '' || $values->keluar != ''){echo "value=''";}else{echo "value='Tidak Hadir' style='text-align: center'";}}?>></td>
                        </tr>
                        <?php                                                         
                        $i++;
                      }           
                    }
                  }
                  ?>

                </tbody>
              </table>
              <?php 
              if($get_count_absen_by_nik != '0')
              {
                ?>
                <button type="submit" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 0px; margin-left: 40px; margin-bottom: 20px">Save</button>
                <button type="reset" class="btn red-mint btn-outline sbold uppercase" style="margin-top: 0px; margin-left: 5px; margin-bottom: 20px">Reset</button>
                <?php
              }
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script src="<?php echo base_url();?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>

<script src="<?php echo base_url();?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
<!--script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script-->
<script src="<?=base_url('vendor/jquery-inputmask/jquery.inputmask.bundle.js');?>"></script>


<script type="text/javascript">
  $(".date-picker").datepicker({rtl:App.isRTL(),autoclose:!0});
</script>
<script>
 jQuery(document).ready(function() {    
  App.init();

  $('input[id$="mask_number"]').inputmask("hh:mm:ss", {
    placeholder: "HH:MM:SS", 
    insertMode: false, 
    showMaskOnHover: false
  }
  );

  //$('input[id$="mask_number"]').mask('00:00:00');

});
</script>