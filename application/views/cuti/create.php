<div class="page-content">
  <h1 class="page-title"> Form Ketidakhadiran
    <small>Pengajuan Form Ketidakhadiran Karyawan</small>
  </h1>

  <div class="row">
    <div class="col-md-12">
      <input type="hidden" id="from_date" value="<?=$from_date;?>" readonly="true">
      <input type="hidden" id="thru_date" value="<?=$thru_date;?>" readonly="true">
      <div class="portlet light bordered">

        <div class="portlet-title">
          <div class="caption font-dark">
            <i class="fa fa-table font-dark"></i>
            <span class="caption-subject bold uppercase">Pengajuan Form Ketidakhadiran Karyawan</span>
          </div>
          <div class="pull-right">
            <a href="<?=site_url('list_cuti_cabang');?>" class="btn grey-mint">
              <i class="fa fa-backward"></i> Kembali ke List Form Ketidakhadiran
            </a>
          </div>
        </div>

        <div class="portlet-body">
          <form class="form-horizontal" id="form">
            <div class="form-group">
              <label class="control-label col-sm-2" for="id_cabang">Cabang</label>
              <div class="col-sm-9">
                <select class="form-control" id="id_cabang" name="id_cabang">
                  <option value=""></option>
                  <?php
                  foreach ($get_branch as $key) {
                    echo '<option value="'.$key->parameter_id.'">'.$key->description.'</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="nik">Karyawan</label>
              <div class="col-sm-9">
                <select class="form-control" id="nik" name="nik">
                  <option value=""></option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="control-label col-sm-2" for="tgl_from">Sisa Cuti</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" id="sisa_cuti" name="sisa_cuti" readonly="true">
              </div>
              <label class="control-label col-sm-2" for="tgl_to">Sisa Ijin</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" id="sisa_ijin" name="sisa_ijin" readonly="true">
              </div>
              <div class="col-sm-6 col-sm-offset-2">
                <label class="small text-info" style="margin-top:5px;">Informasikan team HR jika nilai <strong>cuti</strong> & <strong>ijin</strong> tidak sesuai</label>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2" for="tipe">Tipe</label>
              <div class="col-sm-2">
                <select class="form-control" id="tipe" name="tipe">
                  <option value="ijin">Ijin</option>
                  <option value="cuti">Cuti</option>
                  <option value="sakit">Sakit</option>
                  <option value="cuti_khusus">Cuti Khusus</option>
                  <option value="tlk">Tugas Luar Kota</option>
                  <option value="dnl">Dinas Luar Kota</option>
                </select>
              </div>

              <!--div class="col-sm-6">
                <label class="small text-primary" style="margin-top:5px;">Ketentuan:</label>
                <ul class="list-group small text-primary">
                  <li class="list-group-item"> 
                    <i class="fa fa-asterisk"></i> Sakit & Cuti Khusus tidak akan mengurangi Hak Cuti & Ijin 
                  </li>
                  <li class="list-group-item"> 
                    <i class="fa fa-asterisk"></i> Jika Hak Cuti & Ijin telah habis maka proses pengajuan tidak dapat diproses 
                  </li>
                </ul> 
              </div-->

            </div>
            <div id="khusus_group" class="form-group display-hide">
              <label class="control-label col-sm-2" for="khusus">Khusus</label>
              <div class="col-sm-2">
                <select class="form-control" id="khusus" name="khusus">
                  <?php
                  foreach ($arr_cuti_khusus->result() as $key) {
                    echo '<option value="'.$key->parameter_id.'">'.$key->description.'</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="control-label col-sm-2" for="tgl_cuti">Dari Tanggal</label>
              <div class="col-sm-2">
                <input type="text" class="form-control datepicker" id="tgl_cuti" name="tgl_cuti" placeholder="dd-mm-yyyy">
              </div>
              <label class="control-label col-sm-2" for="tgl_cuti2">Sampai Tanggal</label>
              <div class="col-sm-2">
                <input type="text" class="form-control datepicker" id="tgl_cuti2" name="tgl_cuti2" placeholder="dd-mm-yyyy">
              </div>
            </div>

            <!--div class="form-group row">
              <div class="col-sm-6 col-sm-offset-2">
                <label class="small text-info" style="margin-top:5px; margin-left: -20px;">
                  <ul>
                    <li>Untuk cuti & ijin di hari sabtu dan minggu silahkan hubungi team HR</li>
                    <li>Jika Cuti, Ijin, Sakit & Cuti Khusus melebihi tanggal cut off, silahkan dibuat sampai periode maksimal tanggal cut off aktif terlebih dahulu, sisanya dilanjutkan setelah periode cut off baru di mulai</li>
                  </ul>
                </label>
              </div>
            </div-->

            <div class="form-group">
              <label class="control-label col-sm-2" for="keterangan">Keterangan</label>
              <div class="col-sm-6">
                <textarea class="form-control" id="keterangan" name="keterangan" rows="5" placeholder="Masukan alasan untuk pengajuan Ijin, Cuti, Sakit, Cuti Khusus, Tugas Luar Kota  & Dinas Luar Kota"></textarea>
              </div>
            </div>
            <div class="form-group"> 
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn green-jungle">Submit</button>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>

</div>

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
<script src="<?php echo base_url('vendor/sweetalert/sweetalert2@8.js');?>" type="text/javascript"></script>


<script src="<?php echo base_url('vendor/jqueryvalidation/jquery.validate.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/additional-methods.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/localization/messages_id.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/datepicker/js/bootstrap-datepicker.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/datepicker/locales/bootstrap-datepicker.id.min.js');?>" type="text/javascript"></script>
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js" type="text/javascript"></script-->

<script type="text/javascript">
$(document).ready(function(){
  const from_date = $('#from_date').val();
  const thru_date = $('#thru_date').val();

  $(".datepicker").datepicker({
    placeholder: 'dd-mm-YYYY',
    autoclose: true,
    todayHighlight: true,
    language: 'id',
    format: 'dd-mm-yyyy',
    clearBtn: true,
    startDate: from_date,
    //endDate: thru_date,
    //todayBtn: true,
    //daysOfWeekDisabled: [0,6] // 0 = ejeung poe minggu, 6 for ejeung poe saptu ~~adampm
  });

  $('#id_cabang').select2({
    placeholder: 'Pilih Cabang',
    //minimumInputLength: 1,
  });

  $('#nik').select2({
    placeholder: 'Pilih Karyawan',
    //minimumInputLength: 1,
  });

  $('#id_cabang').on('change', function(){
    var id_cabang = $('#id_cabang').val();
    $.ajax({
      url         : '<?=site_url('get_karyawan_cabang');?>',
      method      : 'GET',
      data        : { id_cabang:id_cabang },
      beforeSend  : function(){
        $.blockUI({ message: '<i class="fa fa-spinner fa-spin"></i> Silahkan Tunggu...' });
        $('#nik').html('');
      },
      statusCode  : {
        404: function() {
          $.unblockUI();
          generateToast('Warning', 'Page Not Found.', 'error');
        },
        500: function() {
          $.unblockUI();
          generateToast('Warning', 'Not connect with databasae.', 'error');
        }
      }
    })
    .done(function(result){
      console.log(result);

      var result = $.parseJSON(result);
      if(result.code == 400){
        generateToast('Something Wrong', result.description, 'info');
        $.unblockUI();
      }else if(result.code == 200){
        $('#nik').html('<option value=""></option>');
        //$('#nik').html('');
        $.each(result.data, function(i,k){
          $('#nik').append('<option value="'+k.nik+'">'+k.fullname+' / '+k.nik+'</option>');
        });
        
        generateToast('Success', result.description, 'success');
        $.unblockUI();

      }else if(result.code == 500){
        generateToast('Warning', result.description, 'warning');
        $.unblockUI();
      }

      $.unblockUI();
    });

  });

  $('#nik').on('change', function(){
    var nik = $('#nik').val();
    if(nik == ''){

    }else{
      $.ajax({
        url         : '<?=site_url('get_cuti_remain');?>',
        method      : 'GET',
        data        : { nik:nik },
        beforeSend  : function(){
          $.blockUI({ message: '<i class="fa fa-spinner fa-spin"></i> Silahkan Tunggu...' });
          $('#sisa_cuti').val('');
          $('#sisa_ijin').val('');
        },
        statusCode  : {
          404: function() {
            $.unblockUI();
            generateToast('Warning', 'Page Not Found.', 'error');
          },
          500: function() {
            $.unblockUI();
            generateToast('Warning', 'Not connect with databasae.', 'error');
          }
        }
      })
      .done(function(result){
        console.log(result);

        var result = $.parseJSON(result);
        if(result.code == 400){
          generateToast('Something Wrong', result.description, 'info');
          $.unblockUI();
        }else if(result.code == 200){
          $('#sisa_cuti').val(result.hak_cuti);
          $('#sisa_ijin').val(result.hak_ijin);
          
          generateToast('Success', result.description, 'success');
          $.unblockUI();

        }else if(result.code == 500){
          generateToast('Warning', result.description, 'warning');
          $.unblockUI();
        }

        $.unblockUI();
      });  

    }
      
  });

  $('#tipe').on('change', function(){
    var tipe = $('#tipe').val();
    console.log(tipe);

    if(tipe == "cuti_khusus"){
      $('#khusus_group').fadeIn();
    }else{
      $('#khusus_group').fadeOut();
    }

  });

  // INITIATE
  var role_id     = <?=$this->session->userdata('logged_in')['role_id'];?>;
  var branch_code = <?=$this->session->userdata('logged_in')['branch_code'];?>;

  if(role_id != '0' && branch_code != null){
    $('#id_cabang').val(branch_code).trigger('change').attr('disabled', true);
  }
  // END INITIATE
  
  // FORM VALIDATE
  $('#form').validate({
    debug: true,
    errorClass: 'help-inline text-danger',
    rules:{
      id_cabang:{
        required:true
      },
      nik:{
        required:true
      },
      tgl_cuti:{
        required:true
      },
      tgl_cuti2:{
        required:true
      },
      keterangan:{
        required:true
      }
    },
    submitHandler: function( form ) {
      var tgl_cuti  = new Date( $('#tgl_cuti').val() );
      var tgl_cuti2 = new Date( $('#tgl_cuti2').val() );
      var sisa_cuti = $('#sisa_cuti').val();
      var sisa_ijin = $('#sisa_ijin').val();
      var tipe = $('#tipe').val();

      if(sisa_cuti == 0 && sisa_ijin == 0 && (tipe == 'ijin' || tipe == 'cuti')){
        swal.fire('Warning', 'Nilai Cuti & Ijin tidak mencukupi', 'warning');
        return false;
      }else if(tgl_cuti.getTime() > tgl_cuti2.getTime()){
        swal.fire('Warning', 'Tanggal Akhir Cuti kurang dari Tanggal Awal Cuti', 'warning');
        return false;
      }else{
        $.ajax({
          url         : '<?=site_url('store_cuti');?>',
          method      : 'POST',
          data        : $('#form').serialize(),
          beforeSend  : function(){
            $.blockUI({ message: '<i class="fa fa-spinner fa-spin"></i> Silahkan Tunggu...' });
          },
          statusCode  : {
            404: function() {
              $.unblockUI();
              generateToast('Warning', 'Page Not Found.', 'error');
            },
            500: function() {
              $.unblockUI();
              generateToast('Warning', 'Not connect with databasae.', 'error');
            }
          }
        })
        .done(function(result){
          console.log(result);
          var result = $.parseJSON(result);

          if(result.code == 400){
            generateToast('Something Wrong', result.description, 'info');
            $.unblockUI();
          }else if(result.code == 200){
            generateToast('Success', result.description, 'success');
            setTimeout(function(){
              console.log("PROCESS STORE SUCCESS");
              $.unblockUI();
              window.location.replace('<?=site_url('list_cuti_cabang');?>');
            }, 2000);

          }else if(result.code == 500){
            generateToast('Warning', result.description, 'warning');
            $.unblockUI();
          }
        });
      }

    }
  });


});

/*function countDay()
{
  var tgl_from = $('#tgl_from').val();
  var tgl_to   = $('#tgl_to').val();

  if(tgl_from != "" && tgl_to != ""){
    tgl_from = tgl_from.split('-');
    tgl_to   = tgl_to.split('-');
    tgl_from = tgl_from[2] + '-' + tgl_from[1] + '-' + tgl_from[0];
    tgl_to   = tgl_to[2] + '-' + tgl_to[1] + '-' + tgl_to[0];

    console.log(tgl_from, tgl_to);

    tgl_from = new Date(tgl_from);
    tgl_to   = new Date(tgl_to);

    var oneDay  = 24*60*60*1000;
    var diffDays = Math.abs((tgl_from.getTime() - tgl_to.getTime()) / oneDay);
    diffDays = diffDays + 1;
    
    $('#hari').val(diffDays);

  }
}*/
</script>