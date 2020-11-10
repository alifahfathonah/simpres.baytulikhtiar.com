
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
                            <small>Add User</small>
                        </h1>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="portlet light bordered">
                                    <?php foreach($get_user_by_id as $values){?>
                                  <form action="<?php echo site_url();?>setup/action_update_user/<?php echo $values->nik;?>" role="form" method="post">
                                    <div class="portlet-body">
                                        <div class="form-group" id="nama">
                                            <label for="default" class="control-label">Nama</label>
                                            <input id="fullname" name="fullname" type="text" class="form-control" readonly="readonly" value="<?php echo $values->fullname;?>"> 
                                        </div>
                                        <div class="form-group" id="jabatan">
                                            <label for="default" class="control-label">Jabatan</label>
                                            <input id="thru_position" type="text" class="form-control" readonly="readonly" value="<?php echo $values->jabatan;?>"> 
                                        </div>
                                        <div class="form-group">
                                            <label for="default" class="control-label">Cabang/Unit</label>
                                            <input id="thru_branch" name="branch" type="text" class="form-control" readonly="readonly" value="<?php echo $values->cabang;?>"> 
                                        </div>
                                        <div class="form-group">
                                            <label for="single" class="control-label">Tipe User</label>
                                                    <select class="form-control select2" name="role_id">
                                                        <option value="0" <?php if($values->role_id == '0'){echo "selected";}?>>User</option>
                                                        <option value="1" <?php if($values->role_id == '1'){echo "selected";}?>>Admin</option>
                                                    </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="default" class="control-label">Username</label>
                                            <input id="username" type="text" class="form-control" name="username" value="<?php echo $values->username;?>"> 
                                        </div>
                                        <div class="form-group">
                                            <label for="default" class="control-label">Password</label>
                                            <input id="password" type="password" class="form-control" name="password"> 
                                        </div>
                                        <div class="form-group">
                                            <label for="default" class="control-label">Confirm Password</label>
                                            <input id="password2" type="password" class="form-control" name="password2"> 
                                        </div>
                                            <div class="form-group">
                                              <button type="submit" class="btn green">SAVE</button>
                                              <a href="<?php echo site_url();?>setup/setup_user"><button type="button" class="btn default">BACK</button></a>
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
              var username = fullname.lastIndexOf(" ");

              if(username > 1)
              {
                var cut = username + 2;
                var post_username = fullname.substr(0, cut);
              }else
              {
                var post_username = fullname;
              }

              $("#nik_").val(nik);
              $("#fullname").val(fullname);
              $("#thru_position").val(thru_position);
              $("#thru_branch").val(thru_branch);
              $("#username").val(post_username);
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