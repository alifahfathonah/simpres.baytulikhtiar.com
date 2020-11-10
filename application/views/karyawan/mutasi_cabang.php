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
        <span>Karyawan</span>
      </li>
    </ul>
  </div>
  <h1 class="page-title"> Karyawan
    <small>Mutasi Cabang Karyawan</small>
  </h1>
  <div class="row">
    <div class="col-md-8">
      <div class="portlet light bordered">
        <form action="<?php echo site_url(); ?>mutasi/action_update_cabang_karyawan" role="form" method="post">
          <div class="portlet-body">
            <div class="form-group">
              <label for="single" class="control-label">Karyawan</label>
              <select id="nik" class="form-control select2" name="nik">
                <option></option>
                <?php
                foreach ($get_karyawan as $values) { ?>
                  <option value="<?= $values->nik; ?>"><?= $values->nik; ?> - <?= $values->fullname; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label for="default" class="control-label">NIK</label>
              <input id="nik_" type="text" class="form-control" readonly="readonly">
            </div>
            <div class="form-group" id="nama">
              <label for="default" class="control-label">Nama</label>
              <input id="fullname" name="fullname" type="text" class="form-control" readonly="readonly">
            </div>
            <div class="form-group">
              <label for="default" class="control-label">Cabang Awal</label>
              <input id="from_cabang" type="text" class="form-control" readonly="readonly">
              <input type="hidden" name="from_cabang" id="from_cabang_id">
            </div>
            <div class="form-group">
              <label for="default" class="control-label">Periode</label>
              <input id="periode" name="periode" type="text" class="form-control" readonly="readonly">
            </div>
            <div class="form-group">
              <label for="single" class="control-label">Update Cabang</label>
              <select class="form-control" id="cabang" name="cabang">
                <option></option>
                <?php foreach ($get_branch as $values) { ?>
                  <option value="<?php echo $values->parameter_id; ?>"><?php echo $values->description; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group row">
              <div class="col-md-6">
                <label for="from_periode" class="control-label col-md-6">From Periode</label>
                <div class="col-md-6">
                  <input type="text" class="form-control date-picker" placeholder="dd-mm-yyyy" name="from_periode" id="from_periode" data-date-format="dd-mm-yyyy">
                </div>
              </div>
              <div class="col-md-6">
                <label for="hak_ijin" class="control-label col-md-6">To Periode</label>
                <div class="col-md-6">
                  <input type="text" class="form-control date-picker" name="thru_periode" placeholder="dd-mm-yyyy" id="thru_periode" data-date-format="dd-mm-yyyy">
                </div>
              </div>
            </div>

            <div class="form-group">
              <button type="submit" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 24px">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url(); ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
<!--script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js" type="text/javascript"></script>


<script type="text/javascript">
  //$(".date-picker").datepicker({rtl:App.isRTL(),autoclose:!0});
</script>
<script>
  jQuery(document).ready(function() {
    App.init(); // initlayout and core plugins
    $(".date-picker").datepicker({
      autoclose: true
    });

    $('#cabang').select2({
      autoclose: true,
      placeholder: "Pilih Cabang"
    });

    $('#nik').change(function() {
      var nik = $("#nik").val();

      $.ajax({
        type: "POST",
        dataType: "JSON",
        data: {
          nik: nik
        },
        url: '<?php echo site_url('mutasi/get_periode_cabang_by_nik'); ?>',
        success: function(response) {
          console.log(response);
          var from_date = response.from_date + " s/d ";
          var post_periode = from_date + response.thru_date;

          /*var thru_periode1     = response.thru_date.substr(8, 2);
          var thru_periode2     = response.thru_date.substr(5, 2);
          var thru_periode3     = response.thru_date.substr(0, 4);
          var post_thru_periode = thru_periode1+'-'+thru_periode2+'-'+thru_periode3;*/

          $("#nik_").val(nik);
          $("#fullname").val(response.fullname);
          $("#from_cabang").val(response.cabang);
          $("#from_cabang_id").val(response.thru_branch);
          $("#periode").val(post_periode);
          /*$("#from_periode").val(post_thru_periode);*/
          $("#show").attr('data-id', nik);
        }
      });
    });

    $('#status').change(function() {
      var status = $(this).val();

      if (status == 30) {
        $('#update_periode').fadeOut();
      } else {
        $('#update_periode').fadeIn();
      }
    });


    $('#detail').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');

      $.ajax({
        type: "POST",
        dataType: "json",
        data: {
          nik: rowid
        },
        url: '<?php echo site_url('mutasi/get_karyawan_by_nik'); ?>',
        success: function(response) {
          $("#niks").val(response.nik);
          $("#karyawan").val(response.fullname);
          $("#branch").val(response.branch);
          $("#position").val(response.position);

        }
      });
    });

  });
</script>