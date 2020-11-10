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
    <small>Tambah Parameter</small>
  </h1>
  <div class="row">
    <div class="col-md-6">
      <div class="portlet light bordered">
        <form action="<?php echo site_url();?>setup/action_add_parameter" role="form" method="post">
          <div class="portlet-body">
            <div class="form-group">
              <label for="single" class="control-label">Kategori Parameter</label>
              <select id="parameter_group" class="form-control select2" name="parameter_group">
                <option></option>
                <?php foreach($get_kategori_parameter as $values){?>
                  <option value="<?php echo $values->parameter_group;?>"><?php echo $values->parameter_group;?></option>
                <?php }?>
              </select>
            </div>
            <div class="form-group">
              <label for="default" class="control-label">Nama Parameter</label>
              <input name="description" type="text" class="form-control"> 
            </div>
            <div class="form-group">
              <button type="submit" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 24px">Save</button>
              <a  href="<?=site_url('setup/setup_parameter');?>" class="btn green  btn-outline sbold uppercase" style="margin-top: 24px">Cancel</a>
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


<script type="text/javascript">
  $(".date-picker").datepicker({rtl:App.isRTL(),autoclose:!0});
</script>
<script>
 jQuery(document).ready(function() {    
      App.init(); // initlayout and core plugins

      $('#nik').change(function(){
        var nik = $("#nik").val();

        $.ajax({
          type: "POST",
          dataType: "json",
          data: {nik:nik},
          url: '<?php echo site_url('mutasi/get_jabatan_by_nik'); ?>',
          success: function(response){
            var fullname = response.fullname;
            var thru_position = response.position;
            var thru_branch = response.branch;
            var status = response.status;
            var get_status = response.get_status;
            var from_date = response.from_periode+" s/d ";
            var thru_date = response.thru_periode;
            var post_periode = from_date+thru_date;

            var thru_periode1 = response.thru_periode.substr(8, 2);
            var thru_periode2 = response.thru_periode.substr(5, 2);
            var thru_periode3 = response.thru_periode.substr(0, 4);
            var post_thru_periode = thru_periode2+'/'+thru_periode1+'/'+thru_periode3;


            $("#nik_").val(nik);
            $("#fullname").val(fullname);
            $("#periode").val(post_periode);
            $("#from_periode").val(post_thru_periode);
            $("#get_status").val(get_status);
            $("#from_status").val(status);
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
            $("#branch").val(response.branch);
            $("#position").val(response.position);

          }
        });
      });

    });
  </script>