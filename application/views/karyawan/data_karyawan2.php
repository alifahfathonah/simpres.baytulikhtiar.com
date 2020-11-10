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
  <h1 class="page-title"> Karyawan
    <small>Detail Karyawan</small>
  </h1>
  <div class="row">
    <div class="col-md-12">

      <div class="portlet light bordered">
        <div class="portlet-title">
          <div class="caption font-dark">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject bold uppercase"> Tabel Karyawan</span>
          </div>
        </div>
        <div class="portlet-body">
          <div class="table-toolbar">
            <div class="row">
              <div class="col-md-6">
                <div class="btn-group">
                  <a href="<?php echo site_url();?>karyawan/add_karyawan">
                    <button id="sample_editable_1_new" class="btn sbold green"> Add New
                      <i class="fa fa-plus"></i>
                    </button>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
            <thead>
              <tr>
                <th>
                  <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                    <span></span>
                  </label>
                </th>
                <th> NIK</th>
                <th> Nama </th>
                <th> Position </th>
                <th> Branch </th>
                <th> Status </th>
                <th> Actions </th>
              </tr>
            </thead>
            <tbody>

              <?php foreach($get_karyawan as $values){ if($values->status == '0'){$color = "success";}else if($values->status == '2'){$color = "info";}else if($values->status == '1'){$color = "warning";}else if($values->status == '3'){$color = "warning";}else if($values->status == '4'){$color = "danger";}else if($values->status == '5'){$color = "warning";}?>                                                    
              <tr class="odd gradeX">
                <td>
                  <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                    <input type="checkbox" class="checkboxes" value="1" />
                    <span></span>
                  </label>
                </td>
                <td><?php echo $values->nik;?></td>
                <td><?php echo $values->fullname;?></td>
                <td><?php echo $values->thru_position;?></td>
                <td><?php echo $values->thru_branch;?></td>
                <td><?php echo $values->post_status;?></td>
                <td><a href="<?php echo site_url()?>karyawan/get_karyawan_by_nik/<?php echo $values->nik;?>" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> View</a></td>
              </tr>
            <?php }?>

          </tbody>
        </table>
      </div>
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
<script src="<?php echo base_url();?>assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>



<script>
  $(document).ready(function() {
    App.init();
    $('#tabel_karyawan').dataTable({
      "bProcessing": true,
      "bServerSide": true,
      "sAjaxSource": '<?=site_url('karyawan/get_karyawan');?>',
      "aoColumns": [
      {"bSearchable": false},
      null,
      null,
      null,
      null,
      null,
      { "bSortable": false, "bSearchable": false }
      ],
      "aLengthMenu": [
      [15, 30, 45, -1],
              [15, 30, 45, "All"] // change per page values here
              ],
          // set the initial value
          "iDisplayLength": 15,
          "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
          "sPaginationType": "bootstrap",
          "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "oPaginate": {
              "sPrevious": "Prev",
              "sNext": "Next"
            }
          },
          "aoColumnDefs": [{
            'bSortable': false,
            'aTargets': [0]
          }
          ]
        });
  })
</script>



