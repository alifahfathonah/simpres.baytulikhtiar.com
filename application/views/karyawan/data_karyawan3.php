<div class="page-content" style="min-height: 860px;">
  <div class="page-bar">
    <ul class="page-breadcrumb">
      <li>
        <a href="#">Home</a>
        <i class="fa fa-circle"></i>
      </li>
      <li>
        <span>Karyawan</span>
      </li>
    </ul>
  </div>
  <h1 class="page-title font-green uppercase"> Karyawan
    <small class="font-green uppercase">Detail Karyawan</small>
  </h1>
  <div class="row">
    <div class="col-md-12">

      <div class="portlet light bordered">
        <div class="portlet-title">
          <div class="caption">
            <i class="fa fa-table font-green"></i>
            <span class="caption-subject font-green sbold uppercase"> Tabel Karyawan</span>
          </div>
          <?php
          if($this->session->userdata('logged_in')['role_id'] == 0){
          ?>
          <div class="pull-right">
            <a href="<?=site_url('karyawan/add_karyawan');?>">
              <button id="add_new" class="btn sbold green"> 
                Add New <i class="fa fa-plus"></i>
              </button>
            </a>
          </div>
        	<?php } ?>
        </div>

        <div class="portlet-body">
          <br>
          <table class="table table-bordered table-hover" id="tables">
            <thead class="bg-blue-oleo bg-font-blue-oleo">
              <tr>
                <th class="text-center">#</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Position</th>
                <th>Branch</th>
                <th>Status</th>
                <th class="text-center" style="width:200px;"><i class="fa fa-cogs"></i></th>
              </tr>
            </thead>
            <tfoot class="bg-blue-oleo bg-font-blue-oleo">
              <tr>
                <th>#</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Position</th>
                <th>Branch</th>
                <th>Status</th>
                <th class="text-center" style="width:250px;"><i class="fa fa-cogs"></i></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modal-id">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title font-green"><i class="fa fa-user"></i> <strong>Karyawan Detail</strong></h4>
      </div>
      <div class="modal-body" id="v_d">
        <i class="fa fa-spinner fa-spin"></i>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
      "dom": 'lBftrip',
      "processing": true, 
      "responsive": false,     
      "serverSide": true, 
      "order": [[2, 'asc']], 
      "ajax": {
        "url": "<?=site_url('karyawan/data');?>",
        "type": "POST"
      },
      "columnDefs": [
      { 
        "targets": [ 0, 6 ], 
        "orderable": false, 
      },
      {
      	"targets": [6],
      	"className": "text-center"
      }
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

  });

  function detail(id)
  {
    $.ajax({
      url         : '<?=site_url('detail_karyawan/');?>'+id,
      method      : 'GET',
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
      $('#modal-id').modal('show');
      $('#v_d').html(result);
      $.unblockUI();
    }); 
  }

  function destroy(nik, fullname)
  {
    swal({
      title: "Apakah Kamu Yakin ?",
      text: "Kamu akan menghapus Data Karyawan Dengan NIK  *"+ nik +"* . Data yang sudah terhapus tidak dapat dikembalikan kembali !",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url         : '<?=site_url('destroy_karyawan');?>',
          method      : 'POST',
          data        : { nik:nik },
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
              console.log("PROCESS DELETE COMPLETE");
              $.unblockUI();
              window.location.replace('<?=site_url('list_karyawan');?>');
            }, 2000);

          }else if(result.code == 500){
            generateToast('Warning', result.description, 'warning');
            $.unblockUI();
          }
        }); 

      } 
    });
  }
</script>