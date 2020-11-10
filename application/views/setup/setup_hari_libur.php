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
                  <a href="#add" data-toggle="modal">
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
                <th> Tanggal Libur </th>
                <th> Keterangan Libur </th>
                <th> Actions </th>
              </tr>
            </thead>
            <tbody>

              <?php foreach($get_hari_libur as $values){ ?>                                        
                <tr class="odd gradeX">
                  <td>
                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                      <input type="checkbox" class="checkboxes" value="1" />
                      <span></span>
                    </label>
                  </td>
                  <td><?php echo $values->tanggal;?></td>
                  <td><?php echo $values->description;?></td>
                  <td><a href="<?php echo site_url()?>setup/get_libur_by_id/<?php echo $values->id;?>" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> View</a></td>
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

<div class="modal fade" id="add" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Add New</h4>
      </div>
      <div class="modal-body"> 
        <form action="<?php echo site_url();?>setup/action_setup_hari_libur" role="form" method="post">
          <div class="portlet-body">
            <div class="form-group">
              <label for="default" class="control-label">Tanggal Libur</label>
              <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" placeholder="" name="tgl">
            </div>
            <div class="form-group" id="nama">
              <label for="default" class="control-label">Keterangan Libur</label>
              <input name="ket_libur" type="text" class="form-control"> 
            </div>
            <div class="form-group">
              <input type="submit" id="show" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 24px" value="OK">
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


    });
  </script>