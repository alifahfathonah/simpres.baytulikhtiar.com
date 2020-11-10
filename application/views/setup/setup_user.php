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
    <small>User</small>
  </h1>
  <div class="row">
    <div class="col-md-12">
      <!-- BEGIN EXAMPLE TABLE PORTLET-->
      <div class="portlet light bordered">
        <div class="portlet-title">
          <div class="caption font-dark">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject bold uppercase"> Managed Table</span>
          </div>
        </div>
        <div class="portlet-body">
          <div class="table-toolbar">
            <div class="row">
              <div class="col-md-6">
                <div class="btn-group">
                  <a href="<?php echo site_url();?>setup/add_user">
                    <button id="sample_editable_1_new" class="btn sbold green"> Add New
                      <i class="fa fa-plus"></i>
                    </button>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-hover" id="tables">
            <thead class="bg-blue-oleo bg-font-blue-oleo">
              <tr>
                <th> # </th>
                <th> Nama </th>
                <th> Cabang/Unit </th>
                <th> Jenis User </th>
                <th> Username </th>
                <th width="200px" class="text-center"> Actions </th>
              </tr>
            </thead>
            <tfoot class="bg-blue-oleo bg-font-blue-oleo">
              <tr>
                <th> # </th>
                <th> Nama </th>
                <th> Cabang/Unit </th>
                <th> Jenis User </th>
                <th> Username </th>
                <th width="200px" class="text-center"> Actions </th>
              </tr>
            </tfoot>
        </table>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
</div>

<!-- MODAL -->
<form id="reset_pasword">
  <div class="modal fade" id="modal-id">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Reset Password</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="new_pass">New Password</label>
            <input type="text" class="form-control" id="new_pass" name="new_pass">
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" class="form-control" id="id_x" name="id_x" readonly>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Reset Password</button>
        </div>
      </div>
    </div>
  </div>
</form>

<!-- MODAL -->
<form id="ganti_tipe">
  <div class="modal fade" id="modal-id2">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Ganti Tipe Account</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="new_pass">New Tipe</label>
            <select class="form-control select2" id="role_id" name="role_id">
              <option value="1">Admin</option>
              <option value="2">Karyawan</option>
              <option value="0">Master Admin</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" class="form-control" id="id_x2" name="id_x2" readonly>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Ganti Tipe</button>
        </div>
      </div>
    </div>
  </div>
</form>

<script src="<?php echo base_url();?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
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

<script src="<?php echo base_url('vendor/sweetalert/sweetalert.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/jquery.validate.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/additional-methods.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/localization/messages_id.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/blockui/jquery.blockUI.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/toast/jquery.toast.min.js');?>" type="text/javascript"></script>

<script>
$(document).ready(function(){

  table = $('#tables').DataTable({
    "dom": 'Bftrip',
    "processing": true, 
    "responsive": false,     
    "serverSide": true, 
    "order": [[1, 'asc']], 
    "ajax": {
      "url": "<?=site_url('setup/data_user');?>",
      "type": "POST"
    },
    "columnDefs": [
    { 
      "targets": [ 0, 5 ], 
      "orderable": false, 
    },
    ],
    "buttons":[
      {
        extend: 'pdf',
        title: 'Data User',
        text: 'Export PDF',
        className: 'btn btn-danger',
        exportOptions: {
          columns: [1,2,3,4,5]
        }
      },
      {
        extend: 'excel',
        title: 'Data User',
        text: 'Export EXCEL',
        className: 'btn btn-success',
        exportOptions: {
          columns: [1,2,3,4,5]
        }
      },
    ]
  });

  /*var datatables = $('#table').DataTable({
    dom: 'Bftrip',
    order: [[1,'asc']],
    columnDefs: [
      {
        targets: [3,5],
        orderable: false,
        searchable: false
      }
    ],
    buttons:[
      {
       extend: 'pdf',
       text: 'Export PDF',
       className: 'btn btn-danger' 
      },
      {
       extend: 'excel',
       text: 'Export EXCEL',
       className: 'btn btn-success' 
      },
    ]
  });*/
});

function destroy(id, username)
{
  swal({
    title: "Apakah Kamu Yakin ?",
    text: "Kamu akan menghapus Username *"+ username +"* . Data yang sudah terhapus tidak dapat dikembalikan kembali !",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url         : '<?=site_url('destroy_user');?>',
        method      : 'POST',
        data        : { id:id },
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
          swal(result.description, { 
            icon: "success"
          });
          setTimeout(function(){
            console.log("PROCESS UPDATE COMPLETE");
            $.unblockUI();
            window.location.replace('<?=site_url('list_user');?>');
          }, 2000);

        }else if(result.code == 500){
          generateToast('Warning', result.description, 'warning');
          $.unblockUI();
        }
      }); 

    } 
  });
}

function change(id, user, tipe)
{
  $('#id_x2').val(id);
  $('#role_id').val(tipe);
  $('#modal-id2').modal('show');

  // FORM VALIDATE
  $('#ganti_tipe').validate({
    debug: true,
    rules:{
      new_pass:{
        required:true,
        minlength:5
      }
    },
    submitHandler: function( form ) {
      $.ajax({
        url         : '<?=site_url('update_tipe');?>',
        method      : 'post',
        data        : $('#ganti_tipe').serialize(),
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
            window.location.replace('<?=site_url('list_user');?>');
          }, 2000);

        }else if(result.code == 500){
          generateToast('Warning', result.description, 'warning');
          $.unblockUI();
        }
      });
    }
  });
  // END FORM VALIDATE
}

function reset(id, user)
{
  $('#id_x').val(id);
  $('#modal-id').modal('show');

  // FORM VALIDATE
  $('#reset_pasword').validate({
    debug: true,
    rules:{
      new_pass:{
        required:true,
        minlength:5
      }
    },
    submitHandler: function( form ) {
      $.ajax({
        url         : '<?=site_url('update_pass');?>',
        method      : 'post',
        data        : $('#reset_pasword').serialize(),
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
            console.log("PROCESS RESET SUCCESS");
            $.unblockUI();
            window.location.replace('<?=site_url('list_user');?>');
          }, 2000);

        }else if(result.code == 500){
          generateToast('Warning', result.description, 'warning');
          $.unblockUI();
        }
      });
    }
  });
  // END FORM VALIDATE
}
</script>