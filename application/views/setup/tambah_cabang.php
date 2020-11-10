<div class="page-content" style="min-height: 860px;">
  <div class="page-bar">
    <ul class="page-breadcrumb">
      <li>
        <a href="#">Kantor Cabang</a>
        <i class="fa fa-circle"></i>
      </li>
      <li>
        <span>Tambah Cabang</span>
      </li>
    </ul>
  </div>
  <h1 class="page-title"> Tambah Cabang </h1>
  <div class="row">
    <div class="col-md-6">
      <div class="portlet light bordered">
        <form class="form" id="form">
          <div class="portlet-body">
            <div class="form-group">
              <label for="single" class="control-label">Nama Cabang</label>
              <input type="text" class="form-control" id="nama_cabang" name="nama_cabang">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">SAVE</button>
              <a href="<?php echo site_url('list_cabang');?>"><button type="button" class="btn default">BACK</button></a>
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
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>

<script src="<?php echo base_url('vendor/jqueryvalidation/jquery.validate.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/additional-methods.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/localization/messages_id.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/blockui/jquery.blockUI.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/toast/jquery.toast.min.js');?>" type="text/javascript"></script>

<script>
$(document).ready(function(){
  $('#nama_cabang').focus();

  // FORM VALIDATE
  $('#form').validate({
    debug: true,
    rules:{
      nama_cabang:{
        required:true,
        minlength:3
      }
    },
    submitHandler: function( form ) {
      $.ajax({
        url         : '<?=site_url('store_cabang');?>',
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
            window.location.replace('<?=site_url('list_parameter');?>');
          }, 2000);

        }else if(result.code == 500){
          generateToast('Warning', result.description, 'warning');
          $.unblockUI();
        }
      });
    }
  });
  // END FORM VALIDATE
});
</script>