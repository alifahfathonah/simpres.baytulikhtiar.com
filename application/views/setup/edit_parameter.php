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
        <span>Setup</span>
      </li>
    </ul>
  </div>
  <h1 class="page-title"> Setup
    <small>Edit Parameter</small>
  </h1>
  <div class="row">
    <div class="col-md-6">
      <div class="portlet light bordered">
        <form id="form">
          <input type="hidden" class="form-control" id="id" name="id" value="<?=$arr->row('parameter_id');?>" readonly>
          <input type="hidden" class="form-control" id="prev_group" name="prev_group" value="<?=$arr->row('parameter_group');?>" readonly>
          <div class="portlet-body">
            <div class="form-group">
              <label for="single" class="control-label">Kategori Parameter</label>
              <select id="parameter_group" class="form-control select2" name="parameter_group">
                <option></option>
                <?php
                foreach($get_kategori_parameter as $values){
                  $selected = "";
                  if($arr->row('parameter_group') == $values->parameter_group){
                    $selected = "selected";
                  }
                ?>
                  <option value="<?php echo $values->parameter_group;?>" <?=$selected;?> ><?php echo $values->parameter_group;?></option>
                <?php }?>
              </select>
            </div>
            <div class="form-group">
              <label for="default" class="control-label">Nama Parameter</label>
              <input name="description" type="text" class="form-control" value="<?=$arr->row('description');?>"> 
            </div>
            <div class="form-group">
              <button type="submit" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 24px">Save</button>
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

<script>
$(document).ready(function() {
  // FORM VALIDATE
  $('#form').validate({
    debug: true,
    rules:{
      parameter_group:{
        required:true
      },
      description:{
        required:true,
        minlength:3
      }
    },
    submitHandler: function( form ) {
      $.ajax({
        url         : '<?=site_url('update_parameter');?>',
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
            console.log("PROCESS UPDATE SUCCESS");
            $.unblockUI();
            window.location.replace('<?=site_url('setup/setup_parameter');?>');
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