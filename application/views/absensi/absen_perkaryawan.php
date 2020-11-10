<style>
@-webkit-keyframes marquee {
  0% {text-indent:100%;}
  100% {text-indent:-200%}
}

input.marquee {
  -webkit-animation: marquee 8s infinite;
  -webkit-animation-timing-function: linear !important;
}
</style>
<div class="page-content" style="min-height: 860px;">
  <div class="theme-panel hidden-xs hidden-sm"></div>

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

  <h1 class="page-title"> Absen Karyawan <small>Detail Perkaryawan</small></h1>

  <div class="row">
    <div class="col-md-12">
      <div class="portlet light bordered">
        <?php
        foreach($get_karyawan_by_nik->result() as $values)
        {
          $nik = $values->nik;
          $nama = $values->fullname;

          if($nik == '')
          {
            echo "<script>alert('NIK tidak ditemukan!!');history.go(-1)</script>";  
          }
        }?>

        <div class="portlet-title">
          <form action="<?=site_url('absensi/get_absen_by_nik');?>" role="form" method="post">
            <input type="hidden" class="form-control" id="nik_now" name="nik_now" value="<?=$nik;?>">
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
                  <?php 
                  foreach($get_karyawan->result() as $values){
                    echo '<option value="'.$values->nik.'">'.$values->nik.' - '.$values->fullname.'</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <button type="submit" class="btn red-thunderbird sbold uppercase" style="margin-top: 24px" id="show">
                Show
              </button>
              <!--a href="<?=site_url('absensi/load/'.$nik);?>">
                <button type="button" class="btn purple-seance sbold uppercase" style="margin-top: 24px;" disabled="true">Import FP</button>
              </a-->
              <!--a href="<?php echo site_url('absensi/load_all');?>">
                <button type="button" class="btn purple-seance sbold uppercase" style="margin-top: 24px" disabled="true">Import All FP</button>
              </a-->
              <button type="button" class="btn purple-seance sbold uppercase" style="margin-top: 24px" id="importNik" data-nik="<?=$nik;?>">Import Single FP</button>
              <!--button type="button" class="btn purple-seance sbold uppercase" style="margin-top: 24px" id="importAll">Import All FP</button-->
              <a href="<?php echo site_url('absensi');?>">
                <button type="button" class="btn grey-mint sbold uppercase" style="margin-top: 24px">
                  Back
                </button>
              </a>
            </div>
          </form>
        </div>

        <div class="portlet-body">
          <div class="table-scrollable">
            <form method="post" action="<?=site_url('absensi/action_add_manual/'.$nik);?>" role="form">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <th> Periode Cutoff </th>
                    <td colspan="3"> : 
                      <?php 
                      foreach($periode_cutoff as $values){
                        echo $values->from_date.' s/d '.$values->thru_date; 
                        break;
                      }
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <th> NIK </th>
                    <td colspan="3"> : <?php echo $nik;?> </td>
                  </tr>
                  <tr>
                    <th> Nama </th>
                    <td colspan="3"> : <?php echo $nama;?> </td>
                  </tr>
                  <tr class="bg-green-jungle bg-font-green-jungle">
                    <th rowspan="2" class="text-center">Tanggal</th>
                    <th colspan="2" class="text-center">Waktu</th>
                    <th rowspan="2" class="text-center">Keterangan</th>
                  </tr>
                  <tr class="bg-green-jungle bg-font-green-jungle">
                    <th class="text-center" style="width:200px;">Masuk</th>
                    <th class="text-center" style="width:200px;">Keluar</th>
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
                  }else{
                    $i = 0;
                    foreach($get_presensi_by_nik as $values)
                    {
                      # DECLARE
                      $disabled       = '';
                      $marquee        = '';
                      $color          = '';
                      $color2         = '';
                      $color3         = '';
                      $val_masuk      = $values->masuk;
                      $val_pulang     = $values->keluar;
                      $val_keterangan = $values->keterangan;
                      $tanggal        = $values->tanggal;
                      # END DECLARE
                      # 
                      # LOGIC MASUK
                      if($val_masuk > '08:00:00'){
                        $color = 'font-red-thunderbird';
                      }
                      # LOGIC MASUK
                      # 
                      # LOGIC KELUAR
                      if($val_pulang < '17:00:00'){
                        $color2 = 'font-red-thunderbird';
                      }
                      # LOGIC KELUAR
                      # 
                      # LOGIC KETERANGAN
                      foreach ($get_libur->result() as $key) {
                        if($tanggal == $key->tanggal){
                          $disabled = 'readonly="true"';
                          $marquee  = 'marquee';
                          $color3 = 'font-green-jungle';
                        }
                      }

                      if($val_keterangan == 'Hadir'){
                        $color = $color2 = $color3 = 'font-green-jungle';
                      }

                      if($val_keterangan == ''){
                        $val_keterangan = 'Tidak Hadir';
                        $color3 = 'font-red-thunderbird';
                      }elseif($val_keterangan == 'Tidak Hadir'){
                        $color3 = 'font-red-thunderbird';
                      }elseif($val_keterangan == 'Masuk Terlambat & Pulang Cepat' || $val_keterangan == 'Masuk Terlambat' || $val_keterangan == 'Pulang Cepat'){
                        $color3 = 'font-red-thunderbird';
                      }
                      # END LOGIC KETERANGAN
                      ?>
                      <tr class="text-center">
                        <td>
                          <?=$values->tanggal;?>
                          <input type="hidden" value="<?=$values->tanggal;?>" name="tanggal<?=$i;?>">
                        </td>
                        <td >
                          <input class="form-control text-center bold <?=$color;?>" value="<?=$val_masuk;?>" placeholder="HH:MM:SS" id="mask_number" type="text" <?=$disabled;?> name="datang<?=$i;?>">
                        </td>
                          <td>
                            <input class="form-control text-center bold <?=$color2;?>" value="<?=$val_pulang;?>" placeholder="HH:MM:SS" id="mask_number" type="text" <?=$disabled;?> name="pulang<?=$i;?>">
                          </td>
                          <td>
                            <input class="form-control text-left bold <?=$marquee;?> <?=$color3;?>" type="text" value="<?=$val_keterangan;?>" title="<?=$val_keterangan;?>" name="keterangan<?=$i;?>" <?=$disabled;?> placeholder="Keterangan">
                          </td>
                        </tr>
                        <?php                                                         
                        $i++;
                      }           
                    }
                    ?>

                  </tbody>
                </table>

                <hr>

                <table class="table table-striped table-hover table-bordered">
                  <thead>
                    <tr>
                      <th colspan="4"><h3><strong>Lembur</strong></h3></th>
                    </tr>
                    <tr class="bg-green bg-font-green">
                      <th class="text-center">Tanggal</th>
                      <th class="text-center">Jam Masuk</th>
                      <th class="text-center">Jam Keluar</th>
                      <th class="text-center">Jam Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($get_lembur->result() as $key) {
                    ?>
                      <tr>
                        <td class="text-center"><?=$key->tgl;?></td>
                        <td class="text-center"><?=$key->jam_a;?></td>
                        <td class="text-center"><?=$key->jam_b;?></td>
                        <td class="text-center"><?=$key->keterangan;?></td>
                      </tr>
                    <?php } ?>
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
    <script src="<?php echo base_url('vendor/toast/jquery.toast.min.js');?>" type="text/javascript"></script>


    <script type="text/javascript">
      $(".date-picker").datepicker({rtl:App.isRTL(),autoclose:!0});
    </script>
    <script>
     $(document).ready(function() {
      App.init();

      $('#single-prepend-text').val('<?=$nik;?>').trigger('change');

      $('#importNik').on('click', function(){
        var curNik = $(this).data('nik');
        $.ajax({
          url: '<?=site_url('absensi/import/');?>'+curNik,
          type: 'get',
          beforeSend: function(){
            $.blockUI();
          },
          statusCode:{
          	200: function(){
          		generateToast('Success', 'Proses Single Import Berhasil...', 'success');
          		setTimeout(function(){
          			$('#show').trigger('click');
          			$.unblockUI();
          		}, 500);
          	},
          	404: function(){
          		$.unblockUI();
          		generateToast('Oops...', 'Page Not Found', 'error');
          	},
          	400: function(){
          		$.unblockUI();
          		generateToast('Oops...', 'NIK Tidak Boleh Kosong', 'error');
          	},
          	500: function(){
          		$.unblockUI();
          		generateToast('Oops...', 'Internal Server Database Error', 'error');
          	},
          	503: function(){
          		$.unblockUI();
          		generateToast('Oops...', 'Unstable Connection', 'error');
          	},
          }
        });
      });

      $('#importAll').on('click', function(){
        $.ajax({
          url: '<?=site_url('absensi/import/all');?>',
          type: 'get',
          beforeSend: function(){
            $.blockUI();
          }
        })
        .done(function(result){
          result = $.parseJSON(result);
          console.log(result);

          if(result.code == 200){
            generateToast('Success', result.description, 'success');
          }else{
            generateToast('Oops...', result.description, 'error');
          }

          $('#show').trigger('click');

          $.unblockUI();
        });
      });

      $('input[id$="mask_number"]').inputmask("hh:mm:ss", {
        placeholder: "HH:MM:SS", 
        insertMode: false, 
        showMaskOnHover: false
      }
      );

  //$('input[id$="mask_number"]').mask('00:00:00');
  //
  function generateToast(heading, message, color){
    $.toast({
      text: message,
      heading: heading,
      icon: color,
      showHideTransition: 'slide',
      allowToastClose: true,
      hideAfter: 5000,
      stack: 5,
      position: 'bottom-right',
      textAlign: 'left',
      loader: true,
      loaderBg: '#9EC600',    
    });
  }

});
</script>