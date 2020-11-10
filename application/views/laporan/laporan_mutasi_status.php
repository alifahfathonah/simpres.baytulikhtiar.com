<div class="page-content" style="min-height: 860px;">
  <div class="theme-panel hidden-xs hidden-sm"></div>
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
    <small>Mutasi Status Karyawan</small>
  </h1>
  <div class="row">
    <div class="col-md-12">
      <div class="portlet light bordered">

        <div class="row">
          <form id="form">
            <div class="col-md-4">

              <div class="form-group">
                <label for="branch" class="control-label">Cabang/Unit</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <label for="branch"><i class="glyphicon glyphicon-search"></i></label>
                  </div>
                  <select class="form-control select2" id="branch" name="branch">
                    <option></option>
                    <?php
                    if($branch_user == '1'){?>
                      <option value="00000">SEMUA</option>
                    <?php }?>
                    <?php 
                    foreach($get_branch as $values){
                    ?>
                      <option value="<?php echo $values->parameter_id;?>"><?php echo $values->description;?></option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-4">

              <div class="form-group">
                <label for="from_date" class="control-label">Tanggal</label>
                <div class="input-group">
                  <input type="text" class="form-control date-picker" placeholder="" id="from_date" name="from_date" data-date-format="dd-mm-yyyy">
                  <div class="input-group-addon">
                    <label for="branch">s/d</label>
                  </div>
                  <input type="text" class="form-control date-picker" placeholder="" id="thru_date" name="thru_date" data-date-format="dd-mm-yyyy">
                </div>
              </div>

            </div>

            <div class="col-md-12">

              <div class="form-group">
                <button id="pdf" class="btn red-mint btn-outline sbold uppercase">PDF</button>
                <button id="excel" class="btn red-mint btn-outline sbold uppercase">Excel</button>
              </div>

            </div>
          </form>

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
<script src="<?php echo base_url();?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>


<script type="text/javascript">
  $(".date-picker").datepicker({
    todayHighlight: true,
    autoclose: true
  });
</script>
<script>
  jQuery(document).ready(function() {    
    App.init();

    $('#excel').click(function(){
      var branch = $('#branch').val();
      var get_from_date = $('#from_date').val();
      var get_thru_date = $('#thru_date').val();

      var post_from_date1 = get_from_date.substr(3, 2);
      var post_from_date2 = get_from_date.substr(0, 2);
      var post_from_date3 = get_from_date.substr(6, 4);
      var from_date = post_from_date3+'-'+post_from_date1+'-'+post_from_date2;

      var post_thru_date1 = get_thru_date.substr(3, 2);
      var post_thru_date2 = get_thru_date.substr(0, 2);
      var post_thru_date3 = get_thru_date.substr(6, 4);
      var thru_date = post_thru_date3+'-'+post_thru_date1+'-'+post_thru_date2;

      var site = '<?php echo site_url('laporan/action_laporan_mutasi_status_excel'); ?>';
      var conf = true;

      if(branch == '')
      {
        alert('Cabang belum diisi');
        var conf = false;
      }

      if(from_date == '')
      {
        alert('Tanggal belum diisi');
        var conf = false;
      }

      if(thru_date == '')
      {
        alert('Cabang belum diisi');
        var conf = false;
      }

      if(conf == true){
        window.open(site+'/'+branch+'/'+from_date+'/'+thru_date);
      }
    });

    $('#pdf').click(function(){
      var branch = $('#branch').val();
      var get_from_date = $('#from_date').val();
      var get_thru_date = $('#thru_date').val();

      var post_from_date1 = get_from_date.substr(3, 2);
      var post_from_date2 = get_from_date.substr(0, 2);
      var post_from_date3 = get_from_date.substr(6, 4);
      var from_date = post_from_date3+'-'+post_from_date1+'-'+post_from_date2;

      var post_thru_date1 = get_thru_date.substr(3, 2);
      var post_thru_date2 = get_thru_date.substr(0, 2);
      var post_thru_date3 = get_thru_date.substr(6, 4);
      var thru_date = post_thru_date3+'-'+post_thru_date1+'-'+post_thru_date2;

      var site = '<?php echo site_url('laporan/action_laporan_mutasi_status_pdf'); ?>';
      var conf = true;

      if(branch == '')
      {
        alert('Cabang belum diisi');
        var conf = false;
      }

      if(from_date == '')
      {
        alert('Tanggal belum diisi');
        var conf = false;
      }

      if(thru_date == '')
      {
        alert('Cabang belum diisi');
        var conf = false;
      }

      if(conf == true){
        window.open(site+'/'+branch+'/'+from_date+'/'+thru_date);
      }
    });

  });
</script>