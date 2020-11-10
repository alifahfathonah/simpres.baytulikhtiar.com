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
        <span>Karyawan</span>
      </li>
    </ul>
  </div>
  <h1 class="page-title"> Karyawan
    <small>Resign Karyawan</small>
  </h1>
  <div class="row">
    <div class="col-md-6">
      <div class="portlet light bordered">
        <!--form action="<?=site_url('resign/action_resign');?>"-->
        <form id="form">
          <div class="portlet-body">
            <div class="form-group">
              <label for="branch_" class="control-label">Cabang/Unit</label>
              <select id="branch_" class="form-control select2" name="branch_">
                <option></option>
                <?php foreach($get_branch as $values){?>
                  <option value="<?php echo $values->parameter_id;?>"><?php echo $values->description;?></option>
                <?php }?>
              </select>
            </div>
            <div class="form-group" id="list">
              <label for="single" class="control-label">Karyawan</label>
              <?php if($branch_user == '0'){?>
                <select class="form-control select2" name="nik" id="nik">
                </select>
              <?php }else{?>
                <select class="form-control select2" id="nik" name="nik">
                  <option></option>
                  <?php foreach($get_karyawan_by_branch as $values){?>
                    <option value="<?php echo $values->nik;?>"><?php echo $values->nik;?> - <?php echo $values->fullname;?></option>
                  <?php }?>
                </select>
              <?php }?>
            </div>
            <div class="form-group">
              <label for="default" class="control-label">NIK</label>
              <input id="nik_" name="nik_" type="text" class="form-control" readonly="readonly"> 
            </div>
            <div class="form-group" id="nama">
              <label for="default" class="control-label">Nama</label>
              <input id="fullname" name="fullname" type="text" class="form-control" readonly="readonly"> 
            </div>
            <div class="form-group" id="jabatan">
              <label for="default" class="control-label">Jabatan</label>
              <input id="thru_position" type="text" class="form-control" readonly="readonly"> 
            </div>
            <div class="form-group">
              <label for="default" class="control-label">Cabang/Unit</label>
              <input id="thru_branch" type="text" class="form-control" readonly="readonly"> 
            </div>
            <div class="form-group">
              <label for="default" class="control-label">Tanggal Gabung</label>
              <input id="tgl_masuk" type="text" class="form-control" readonly="readonly"> 
            </div>
            <div class="form-group">
              <label for="default" class="control-label">Status Terakhir</label>
              <input id="get_status" type="text" class="form-control" readonly="readonly"> 
            </div>
            <div class="form-group">
              <label for="default" class="control-label">Periode Status Terakhir</label>
              <input id="date_status" type="text" class="form-control" readonly="readonly"> 
            </div>
            <div class="form-group">
              <label for="default" class="control-label">Tanggal Resign</label>
              <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" placeholder="" name="tgl_resign">
            </div>
            <div class="form-group">
              <label for="default" class="control-label">Alasan</label>
              <textarea class="form-control" name="alasan"></textarea>
            </div>
            <div class="form-group">
              <input type="submit" id="show" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 24px" value="OK">
            </div>
          </div>
        </form>
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
<script src="<?php echo base_url();?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
<!--script src="<?php echo base_url();?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/jquery.validate.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/additional-methods.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/localization/messages_id.js');?>" type="text/javascript"></script>


<script>
  jQuery(document).ready(function() {    
    App.init();
    $(".date-picker").datepicker({
      autoclose: true
    });

    $('#nik').change(function(){
      var nik = $("#nik").val();

      $.ajax({
        type: "POST",
        dataType: "JSON",
        data: { nik:nik },
        url: '<?php echo site_url('mutasi/get_jabatan_by_nik'); ?>',
        beforeSend: function(){
          $.blockUI();
        },
        error: function(response){
          console.log(response);
        },
        success: function(response){
          $.unblockUI();
          var fullname = response[0].fullname;
          var thru_position = response[0].position;
          var thru_branch = response[0].branch;
          var tgl_masuk = response[0].tgl_masuk;
          var get_status = response[0].status;
          var from_date = response[0].from_date_status+' s/d ';
          var date_status = from_date+response[0].thru_date_status;

          $("#nik_").val(nik);
          $("#fullname").val(fullname);
          $("#thru_position").val(thru_position);
          $("#thru_branch").val(thru_branch);
          $("#tgl_masuk").val(tgl_masuk);
          $("#date_status").val(date_status);
          $("#get_status").val(get_status);

          $("#show").attr('data-id', nik);
        }
      });
    });  


    $('#detail').on('show.bs.modal', function (e) {
      var rowid = $(e.relatedTarget).data('id');

      $.ajax({
        type: "POST",
        dataType: "json",
        data: {nik:rowid},
        url: '<?php echo site_url('mutasi/get_karyawan_by_nik'); ?>',
        success : function(response){
          $("#niks").val(response.nik);
          $("#karyawan").val(response.fullname);

        }
      });
    });

    $('#branch_').change(function(){
      var branch = $("#branch_").val();
      console.log(branch);

      $.ajax({
        type: "POST",
        dataType: "json",
        data: {branch:branch},
        url: '<?php echo site_url('resign/get_karyawan_by_branch'); ?>',
        beforeSend: function(){
          $.blockUI();
          $('#nik').html('<option value=""></option>');
        },
        success: function(response){
          html = '';
          for ( i = 0 ; i < response.length ; i++ )
          {
            $("#nik").append('<option value="'+response[i].nik+'">'+response[i].nik+' - '+response[i].fullname+'</option>');
          }

          $.unblockUI();     
        }
      });
    });

    // FORM VALIDATE
    $('#form').validate({
      debug: true,
      errorClass: 'help-inline text-danger',
      rules:{
        branch_:{
          required:true
        },
        nik:{
          required:true
        },
        tgl_resign:{
          required:true
        }
      },
      submitHandler: function( form ) {
        $.ajax({
          url         : '<?=site_url('update_resign');?>',
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
              window.location.replace('<?=site_url('karyawan');?>');
            }, 2000);

          }else if(result.code == 500){
            generateToast('Warning', result.description, 'warning');
            $.unblockUI();
          }
        });
      }
    });

  });
</script>