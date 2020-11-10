<div class="page-content">
  <h1 class="page-title"> Form Lembur
    <small>Form Pengajuan Lembur Karyawan</small>
  </h1>

  <div class="row">
    <div class="col-md-12">
      <div class="portlet light bordered">

        <div class="portlet-title">
          <div class="caption font-dark">
            <i class="fa fa-table font-dark"></i>
            <span class="caption-subject bold uppercase">Form Pengajuan Lembur Karyawan</span>
          </div>
          <div class="pull-right">
            <a href="<?=site_url('lembur');?>" class="btn grey-mint">
              <i class="fa fa-backward"></i> Kembali ke List Lembur
            </a>
          </div>
        </div>

        <div class="portlet-body">
          <form class="form-horizontal" id="form">
            <div class="form-group">
              <label class="control-label col-sm-2" for="id_cabang">Cabang</label>
              <div class="col-sm-6">
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
              <div class="col-sm-6">
                <select class="form-control" id="nik" name="nik">
                  <option value=""></option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="keterangan">Tanggal</label>
              <div class="col-sm-6">
                <input type="text" class="form-control datepicker" id="tgl" name="tgl" placeholder="Tanggal Lembur">
              </div>
            </div>

            <div class="form-group row">
              <label class="control-label col-sm-2" for="jam_a">Dari Jam</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" id="jam_a" name="jam_a" placeholder="HH:MM:SS">
              </div>
              <label class="control-label col-sm-2" for="jam_b">Sampai Jam</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" id="jam_b" name="jam_b" placeholder="HH:MM:SS">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="keterangan">Keterangan</label>
              <div class="col-sm-6">
                <textarea class="form-control" id="keterangan" name="keterangan" rows="5" placeholder="Masukan alasan untuk pengajuan Lembur"></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="approval">Approval</label>
              <div class="col-sm-6">
                <select class="form-control" id="approval" name="approval">
                  <option value=""></option>
                  <?php
                  foreach ($get_karyawan->result() as $key) {
                    echo '<option value="'.$key->nik.'">'.$key->fullname.' / '.$key->position.' '.$key->cabang.'</option>';
                  }
                  ?>
                </select>
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
<script src="<?=base_url('vendor/jquery-inputmask/jquery.inputmask.bundle.js');?>"></script>

<script type="text/javascript">
$(document).ready(function(){

  $(".datepicker").datepicker({
    placeholder: 'YYYY-mm-dd',
    autoclose: true,
    todayHighlight: true,
    language: 'id',
    format: 'yyyy-mm-dd',
    clearBtn: true,
  });

  $('#id_cabang').select2({
    placeholder: 'Pilih Cabang',
    //minimumInputLength: 1,
  });

  $('#approval').select2({
    placeholder: 'Pilih Approval'
  });

  $('#nik').select2({
    placeholder: 'Pilih Karyawan',
    //minimumInputLength: 1,
  });

  $('#jam_a').inputmask("hh:mm:ss", 
    {
      placeholder: "HH:MM:SS", 
      insertMode: false, 
      showMaskOnHover: false
    }
  );

  $('#jam_b').inputmask("hh:mm:ss", 
    {
      placeholder: "HH:MM:SS", 
      insertMode: false, 
      showMaskOnHover: false
    }
  );

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
      tgl:{
        required:true
      },
      jam_a:{
        required:true
      },
      jam_b:{
        required:true
      },
      keterangan:{
        required:true
      },
      approval:{
        required:true
      }
    },
    submitHandler: function( form ) {
      var jam_a  = new Date( $('#jam_a').val() );
      var jam_b = new Date( $('#jam_b').val() );

      if(jam_a.getTime() > jam_b.getTime()){
        swal.fire('Warning', 'Jam Akhir Lembur kurang dari Jam Awal Lembur', 'warning');
        return false;
      }else{
        $.ajax({
          url         : '<?=site_url('lembur_store');?>',
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
              window.location.replace('<?=site_url('lembur');?>');
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