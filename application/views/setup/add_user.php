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
        <form action="<?php echo site_url();?>setup/action_add_user" role="form" method="post">
          <div class="portlet-body">
            <div class="form-group">
              <label for="single" class="control-label">Cabang/Unit</label>

              <?php if($branch_user == '1'){?>
                <select id="branch_" class="form-control select2" name="branch_">
                  <option></option>
                  <?php foreach($get_branch as $values){?>
                    <option value="<?php echo $values->parameter_id;?>"><?php echo $values->description;?></option>
                  <?php }?>
                </select>
              <?php }else { ?>
                <select class="form-control select2" name="branch_" disabled="disabled">
                  <?php foreach($get_branch as $values){?>
                    <option value="<?php echo $values->parameter_id;?>"><?php echo $values->description;?></option>
                  <?php }?>
                </select>
              <?php }?>

            </div>
            <div class="form-group" id="list">
              <label for="single" class="control-label">Karyawan</label>
              <?php if($branch_user == '1'){?>
                <select class="form-control select2" name="nik" id="nik">
                </select>
              <?php }//else{?>
                <!--select class="form-control select2" name="nik" id="nik">
                  <option></option>
                  <?php //foreach($get_karyawan_by_branch as $values){?>
                    <option value="<?php echo $values->nik;?>"><?php echo $values->nik;?> - <?php echo $values->fullname;?></option>
                  <?php //} ?>
                </select-->
              <?php //} ?>
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
            <!--div class="form-group">
              <label for="default" class="control-label">Cabang/Unit</label>
              <input id="thru_branch" name="branch" type="text" class="form-control" readonly="readonly"> 
            </div-->
            <div class="form-group">
              <label for="single" class="control-label">Tipe User</label>
              <select class="form-control select2" name="role_id">
                <option value="1">Admin</option>
                <option value="2">Karyawan</option>
              </select>
            </div>
            <div class="form-group">
              <label for="username" class="control-label">Username</label>
              <input id="username" type="text" class="form-control" name="username"> 
            </div>
            <div class="form-group">
              <label for="password" class="control-label">Password</label>
              <input id="password" type="password" class="form-control" name="password"> 
            </div>
            <div class="form-group">
              <label for="password2" class="control-label">Confirm Password</label>
              <input id="password2" type="password" class="form-control" name="password2"> 
            </div>
            <div class="form-group">
              <button type="submit" class="btn green">SAVE</button>
              <a href="<?php echo site_url();?>setup/setup_user"><button type="button" class="btn default">BACK</button></a>
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


<script type="text/javascript">
</script>
<script>
 jQuery(document).ready(function() {
      App.init(); // initlayout and core plugins
      $(".date-picker").datepicker({rtl:App.isRTL(),autoclose:!0});

      /*$('#nik').change(function(){
        var nik = $("#nik").val();
        console.log(nik);

        $.ajax({
          type: "POST",
          dataType: "json",
          data: {nik:nik},
          url: '<?php echo site_url('mutasi/get_jabatan_by_nik'); ?>',
          success: function(response){
            console.log(response);
            var fullname = response.fullname;
            var thru_position = "x";
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
      });*/


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
        var id_branch = $("#branch_").val();

        $.ajax({
          type: "get",
          data: { id_branch:id_branch },
          url: '<?=site_url('list_karyawan_branch');?>',
          beforeSend: function(){
            $.blockUI({ message: '<i class="fa fa-spinner fa-spin"></i> Silahkan Tunggu...' });
            $('#nik').html('<option value=""></option>');
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
          var result = $.parseJSON(result);

          $.each(result, function(i,k){
            console.log(k);
            $('#nik').append('<option value="'+k.nik+'">'+k.fullname+'</option>');
          });

          $.unblockUI();
        });

      });

      $('#nik').change(function(){
        var nik = $("#nik").val();

        $.ajax({
          type: "get",
          data: { nik:nik },
          url: '<?=site_url('detail_karyawan_nik');?>',
          beforeSend: function(){
            $.blockUI({ message: '<i class="fa fa-spinner fa-spin"></i> Silahkan Tunggu...' });
            $('#nik_').val('');
            $('#fullname').val('');
            $('#thru_position').val('');
            $('#thru_branch').val('');
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
          var result = $.parseJSON(result);
          console.log(result);

          $.each(result, function(i,k){
            $('#nik_').val(k.nik);
            $('#fullname').val(k.fullname);
            $('#thru_position').val(k.position);
            $('#thru_branch').val(k.thru_branch);
          });
          
          $.unblockUI();
        });

      });

    });
  </script>