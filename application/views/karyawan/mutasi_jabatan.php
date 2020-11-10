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
    <small>Mutasi Jabatan</small>
  </h1>
  <div class="row">
    <div class="col-md-8">
      <div class="portlet light bordered">
        <form id="form">
          <div class="portlet-body">
            <div class="form-group">
              <label for="single" class="control-label">Karyawan</label>
              <select id="nik" class="form-control" name="nik">
                <option></option>
                <?php foreach ($get_karyawan as $values) { ?>
                  <option value="<?= $values->nik; ?>"><?= $values->nik; ?> / <?= $values->fullname; ?></option>
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
              <label for="default" class="control-label">Cabang/Unit</label>
              <input id="thru_branch" type="text" class="form-control" readonly="readonly">
            </div>
            <div class="form-group" id="jabatan">
              <label for="default" class="control-label">Jabatan</label>
              <input id="thru_position" type="text" class="form-control" readonly="readonly">
            </div>
            <div class="form-group">
              <label for="thru_branch" class="control-label">Mutasi ke Jabatan</label>
              <select class="form-control" id="thru_position" name="thru_position">
                <option value=""></option>
                <?php
                foreach ($get_position as $values) {
                  echo '<option value="' . $values->parameter_id . '">' . $values->description . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group row">
              <div clas="col-md-4">
                <label for="tgl_mutasi" class="control-label col-md-2">TGL Mutasi</label>
                <div class="col-md-3">
                  <input type="text" class="form-control datepicker" id="tgl_mutasi" name="tgl_mutasi" data-date-format="dd-mm-yyyy">
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div clas="col-md-4">
                <label for="from_date" class="control-label col-md-2">Dari Periode</label>
                <div class="col-md-3">
                  <input type="text" class="form-control datepicker" id="from_date" name="from_date" data-date-format="dd-mm-yyyy">
                </div>
              </div>
              <div clas="col-md-4">
                <label for="thru_date" class="control-label col-md-2">Ke Periode</label>
                <div class="col-md-3">
                  <input type="text" class="form-control datepicker" id="thru_date" name="thru_date" data-date-format="dd-mm-yyyy">
                </div>
              </div>
            </div>
            <div class="form-group">
              <input type="hidden" class="form-control" id="from_position" name="from_position" readonly="true">
              <button type="submit" class="btn btn-primary">Submit</button>
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

<script src="<?php echo base_url('vendor/jqueryvalidation/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/additional-methods.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/localization/messages_id.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">
</script>

<script>
  jQuery(document).ready(function() {
    App.init();

    $('#nik').select2({
      autoclose: true,
      placeholder: 'Pilih NIK'
    });

    $(".date-picker").datepicker({
      rtl: App.isRTL(),
      autoclose: !0
    });
    $(".datepicker").datepicker({
      autoclose: true,
      todayHighlight: true
    });

    $('#nik').change(function() {
      var nik = $("#nik").val();

      $.ajax({
          url: '<?php echo site_url('mutasi/get_karyawan_detail'); ?>',
          type: "GET",
          dataType: "JSON",
          data: {
            nik: nik
          },
          beforeSend: function() {
            $.blockUI({
              message: '<i class="fa fa-spinner fa-spin"></i> Silahkan Tunggu...'
            });
          }
        })
        .done(function(response) {
          $("#nik_").val(response[0].nik);
          $("#fullname").val(response[0].fullname);
          $("#from_position").val(response[0].thru_position_id);
          $("#thru_position").val(response[0].thru_position);
          $("#thru_branch").val(response[0].thru_branch);
          $("#show").attr('data-id', response[0].nik);
          $.unblockUI();
        });
    });

    // FORM VALIDATE
    $('#form').validate({
      debug: true,
      rules: {
        nama_cabang: {
          required: true,
          minlength: 3
        },
        thru_position: {
          required: true
        },
        tgl_mutasi: {
          required: true
        },
        from_date: {
          required: true
        },
        thru_date: {
          required: true
        }
      },
      submitHandler: function(form) {
        $.ajax({
            url: '<?= site_url('mutasi/store_jabatan'); ?>',
            method: 'POST',
            data: $('#form').serialize(),
            dataType: 'json',
            beforeSend: function() {
              $.blockUI({
                message: '<i class="fa fa-spinner fa-spin"></i> Silahkan Tunggu...'
              });
            },
            statusCode: {
              404: function() {
                $.unblockUI();
                generateToast('Warning', 'Page Not Found.', 'error');
              },
              500: function() {
                $.unblockUI();
                generateToast('Warning', 'Not connect with databasae.', 'error');
              }
            },
            error: function(res) {
              $.unblockUI();
            }
          })
          .done(function(result) {
            console.log(result);

            if (result.code == 400) {
              generateToast('Something Wrong', result.description, 'info');
            } else if (result.code == 200) {
              generateToast('Success', result.description, 'success');
              setTimeout(function() {
                console.log("PROCESS STORE SUCCESS");
                window.location.reload();
              }, 2000);

            } else if (result.code == 500) {
              generateToast('Warning', result.description, 'warning');
            }

            $.unblockUI();
          });
      }
    });
    // END FORM VALIDATE

  });
</script>