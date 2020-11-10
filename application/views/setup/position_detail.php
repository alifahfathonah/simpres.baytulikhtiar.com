
                    <div class="page-content" style="min-height: 860px;">
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="#">Home</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Setup</span>
                                </li>
                            </ul>
                        </div>
                        <h1 class="page-title"> Setup
                            <small> Jabatan Detail</small>
                        </h1>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="portlet light bordered">
                                  <form action="<?php echo site_url();?>setup/update_position/<?php echo $position_id;?>" role="form" method="post">
                                    <div class="portlet-body">
                                        <div class="form-group">
                                            <label for="default" class="control-label">Nama Jabatan</label>
                                            <?php foreach($get_position_by_id as $values){?>
                                            <input name="position" type="text" class="form-control" value="<?php echo $values->description?>"> 
                                            <?php }?>
                                        </div>
                                            <div class="form-group">
                                                <input type="submit" id="show" data-toggle="modal" href="#detail" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 24px" value="UPDATE">
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
                    $("#branch").val(response.branch);
                    $("#position").val(response.position);

                }
            });
         });

   });
</script>