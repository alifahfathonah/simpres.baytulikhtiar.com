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
    <small>Setup Cabang</small>
  </h1>
  <div class="row">
    <div class="col-md-12">
      <!-- BEGIN EXAMPLE TABLE PORTLET-->
      <div class="portlet light bordered">
        <div class="row">
          <div class="col-md-6">
            <div class="portlet-title">
              <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> Managed Table</span>
              </div>
            </div>
          </div>
          <div class="col-md-6 text-right">
            <a href="<?=site_url('tambah_cabang');?>" class="btn red"><i class="fa fa-plus"></i> Tambah Cabang</a>
          </div>
        </div>
        <div class="portlet-body">
          <table class="table table-bordered table-hover table-checkable" id="table">
            <thead>
              <tr>
                <th class="text-center" width="100"> # </th>
                <th> Nama Cabang </th>
                <th class="text-center" width="140"> Actions </th>
              </thead>
              <tbody>
                <?php foreach($get_branch as $values){?>                                                    
                  <tr class="odd gradeX">
                    <td>
                      <?=$values->parameter_id;?>
                    </td>
                    <td><?php echo $values->description;?></td>
                    <td style="text-align: center;">
                      <a href="<?php echo site_url('')?>setup/get_branch_by_id/<?php echo $values->parameter_id;?>" class="btn btn-sm btn-outline purple"><i class="fa fa-pencil"></i> Edit</a>
                      <button onClick="destroy(<?=$values->parameter_id;?>, '<?php echo $values->description;?>');" class="btn btn-sm btn-outline red"><i class="fa fa-trash"></i> Delete</button>
                    </td>
                  </tr>
                <?php }?>

              </tbody>
            </table>
          </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
      </div>
    </div>
  </div>

  <script src="<?php echo base_url();?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url();?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url();?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url();?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url();?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url();?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url();?>assets/global/scripts/datatable.js" type="text/javascript"></script>
  <script src="<?php echo base_url();?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url();?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
  <script src="<?php echo base_url();?>assets/global/scripts/app.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url();?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url();?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>

  <script src="<?php echo base_url('vendor/sweetalert/sweetalert.min.js');?>" type="text/javascript"></script>
  <!--script src="<?php echo base_url();?>assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script-->
  <!--script src="<?php echo base_url();?>assets/pages/scripts/table-datatables-buttons.min.js" type="text/javascript"></script-->

<script>
$(document).ready(function(){
  var table = $('#table').DataTable({
    order: [[0,'asc']],
    columnDefs: [
      {
        targets: [2],
        orderable: false,
        searchable: false
      }
    ]
  });
});

function destroy(id, cabang)
{
  swal({
    title: "Apakah Kamu Yakin ?",
    text: "Kamu akan menghapus Cabang *"+ cabang +"* . Data yang sudah terhapus tidak dapat dikembalikan kembali !",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url         : '<?=site_url('destroy_cabang');?>',
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
            window.location.replace('<?=site_url('list_cabang');?>');
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