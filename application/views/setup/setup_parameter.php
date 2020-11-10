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
    <small>Parameter Lainnya</small>
  </h1>
  <div class="row">
    <div class="col-md-12">
      <div class="portlet light bordered">
        <form action="javascript:;" role="form" method="post">
          <div class="portlet-body">
            <div class="form-group col-md-6" style="padding-left: 0px">
              <label for="single" class="control-label">Kategori Parameter</label>
              <select id="parameter_group" class="form-control" name="parameter_group">
                <option></option>
                <?php foreach($get_kategori_parameter as $values){?>
                  <option value="<?php echo $values->parameter_group;?>"><?php echo $values->parameter_group;?></option>
                <?php }?>
              </select>
            </div>  
            <div class="form-group">
              <button id="show" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 24px">SHOW</button>
              <a href="<?php echo site_url();?>setup/add_parameter"><button type="button" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 24px">Tambah Parameter</button></a>
            </div>
          </div>
        </form>
        <table id="table" class="table table-bordered">
          <thead>
            <tr>
              <th>PARAMETER GROUP</th>
              <th>PARAMETER ID</th>
              <th>PARAMETER DESCRIPTION</th>
              <th class="text-center" width="100"><i class="fa fa-gear"></i> </th>
            </tr>
          </thead>
          <tbody id="vdata">
          </tbody>
        </table>
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
<script src="<?php echo base_url();?>assets/scripts/ui/i18n/grid.locale-en.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/jquery.livequery.js" type="text/javascript"></script>

<!--script src="<?php echo base_url();?>assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script-->

<script src="<?php echo base_url('vendor/datatables/jquery.dataTables.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/datatables/dataTables.bootstrap4.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/sweetalert/sweetalert.min.js');?>" type="text/javascript"></script>

<script>
$(document).ready(function() {
  var datatables = $('#table').DataTable({
    dom: 'ftrip',
    order: [[1,'asc']],
    columnDefs: [
      {
        targets: [3],
        orderable: false,
        searchable: false
      }
    ]
  });

  $('#show').on('click', function(){
    var param = $('#parameter_group').val();
    $.ajax({
      url: '<?=site_url('list_parameter');?>',
      type: 'get',
      data: { param:param },
      beforeSend: function(){
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
    .done(function(res){
      //console.log(res);
      res = $.parseJSON(res);
      datatables.clear();
      datatables.rows.add(res);
      datatables.draw();
      $.unblockUI();
    });
  });
});

function destroy(id, group, desc)
{
  swal({
    title: "Apakah Kamu Yakin ?",
    text: "Kamu akan menghapus "+ group +" *"+ desc +"* ... Data yang sudah terhapus tidak dapat dikembalikan kembali !",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url         : '<?=site_url('destroy_parameter');?>',
        method      : 'POST',
        data        : { id:id, group:group, desc:desc },
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
            window.location.replace('<?=site_url('setup/setup_parameter');?>');
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