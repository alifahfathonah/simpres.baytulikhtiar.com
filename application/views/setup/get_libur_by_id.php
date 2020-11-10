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
    <small>Setup Hari Libur</small>
  </h1>
  <div class="row">
    <div class="col-md-6">
      <div class="portlet light bordered">
        <?php foreach($get_libur_by_id as $values){?>
          <form action="<?php echo site_url();?>setup/action_update_setup_hari_libur/<?php echo $values->id;?>" role="form" method="post">
            <div class="portlet-body">
              <div class="form-group">
                <label for="default" class="control-label">Tanggal Libur</label>
                <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" placeholder="" name="tgl" value="<?php echo date('d-m-Y', strtotime($values->tanggal));?>">
              </div>
              <div class="form-group" id="nama">
                <label for="default" class="control-label">Keterangan Libur</label>
                <input name="ket_libur" type="text" class="form-control" value="<?php echo $values->description;?>"> 
              </div>
              <div class="form-group">
                <input type="submit" id="show" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 24px" value="OK">
              </div>
            </div>
          </form>
        <?php }?>
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

            $("#nik_").val(nik);
            $("#fullname").val(fullname);
            $("#thru_position").val(thru_position);
            $("#thru_branch").val(thru_branch);
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

        $.ajax({
          type: "POST",
          dataType: "json",
          data: {branch:branch},
          url: '<?php echo site_url('resign/get_karyawan_by_branch'); ?>',
          success: function(response){
            html = '<option>PILIH</option>';
            for ( i = 0 ; i < response.length ; i++ )
            {
             html += '<option value="'+response[i].nik+'">'+response[i].nik+' - '+response[i].fullname+'</option>';
           }
           $("#nik", "#list").html(html).focus();
           $("#nik option:first-child", "#list").attr('selected',true);        
         }
       });
      });  

    });
  </script>