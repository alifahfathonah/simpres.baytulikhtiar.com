
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
                                    <span>Laporan</span>
                                </li>
                            </ul>
                        </div>
                        <h1 class="page-title"> Laporan
                            <small>Hak Cuti/Ijin Karyawan</small>
                        </h1>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light bordered">

                                        <form action="javascript:;">
                                            <div class="form-group col-md-4">
                                                <label for="single-prepend-text" class="control-label">Cabang/Unit</label>
                                                <div class="input-group select2-bootstrap-prepend">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                                                            <span class="glyphicon glyphicon-search"></span>
                                                        </button>
                                                    </span>
                                                    <select class="form-control select2" name="branch" id="branch" <?php if($branch_user != '1'){echo "disable='disable' values='".$branch_user."'";}?>>
                                                        <option></option>
                                                        <?php if($branch_user == '1'){?><option value="00000">SEMUA</option><?php }?>
                                                        <?php foreach($get_branch as $values){?>
                                                            <option value="<?php echo $values->parameter_id;?>"><?php echo $values->description;?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <button id="pdf" class="btn red-mint btn-outline sbold uppercase" style="margin-top: 24px">PDF</button>
                                                <button id="excel" class="btn red-mint btn-outline sbold uppercase" style="margin-top: 24px">Excel</button>
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
            /*$('#branch').hide();  
            $('#status').hide();  
        
      $('#jenis').change(function(){
          var jenis = $("#jenis").val();

          if(jenis == 0)
          {
            $('#branch').fadeIn();  
            $('#status').fadeOut();  
          }else if(jenis == '1')
          {
            $('#status').fadeIn();  
            $('#branch').fadeOut();              
          }

      });  

      $('#status').change(function(){
        var status = $(this).val();

        if(status == 3){
          $('#update_periode').fadeOut();
        } else {
          $('#update_periode').fadeIn();          
        }
      });*/


            $('#excel').click(function(){
              var branch = $('#branch').val();

              var site = '<?php echo site_url('laporan/action_laporan_hak_cuti_excel'); ?>';
              var conf = true;
              
                if(branch == '')
                {
                  alert('Cabang belum diisi');
                  var conf = false;
                }

              if(conf == true){
                window.open(site+'/'+branch);
              }
            });

            $('#pdf').click(function(){
              var branch = $('#branch').val();

              var site = '<?php echo site_url('laporan/action_laporan_hak_cuti_pdf'); ?>';
              var conf = true;
              
                if(branch == '')
                {
                  alert('Cabang belum diisi');
                  var conf = false;
                }

              if(conf == true){
                window.open(site+'/'+branch);
              }
            });

   });
</script>