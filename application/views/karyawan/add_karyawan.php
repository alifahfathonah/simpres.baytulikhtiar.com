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

      <div class="portlet light portlet-fit portlet-form bordered" id="add">
        <div class="portlet-title">
          <div class="caption">
            <i class=" icon-layers font-green"></i>
            <span class="caption-subject font-green sbold uppercase">Karyawan Baru</span>
          </div>
        </div>
        <div class="portlet-body">
          <form class="form-horizontal" id="form">
            <div class="form-body">
              <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                <div class="alert alert-success display-hide">
                  <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                  <div class="form-group form-md-line-input">
                    <label class="control-label col-md-3">Foto</label>
                    <div class="col-md-9">
                      <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"> </div>
                        <div class="progress">
                          <div id="bar" class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%;" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div style="margin-top: 10px">
                          <span class="btn red btn-outline btn-file">
                            <span class="fileinput-new"> Select image </span>
                            <span class="fileinput-exists"> Change </span>
                            <input type="file" name="userfile" id="userfile" accept="image/*"> </span>
                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="nik">NIK
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="" id="nik" name="nik">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="no_ktp">No. KTP <span class="required">*</span>
                      </label>
                      <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="" id="no_ktp" name="no_ktp">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="fullname">Nama
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="" id="fullname" name="fullname">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="tmp_lahir">Tempat Lahir
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="" name="tmp_lahir" name="tmp_lahir">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="tgl_lahir">Tanggal Lahir
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9">
                        <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" placeholder="" id="tgl_lahir" name="tgl_lahir">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="alamat">Alamat</label>
                      <div class="col-md-9">
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>

                    <div class="form-group form-md-radios">
                      <label class="col-md-3 control-label" for="checkbox1_1">Jenis Kelamin <span class="required">*</span></label>
                      <div class="col-md-9">
                        <div class="radio">
                          <label>
                            <input type="radio" id="checkbox1_1" name="jk" value="L" class="md-radiobtn">
                            Laki-Laki
                          </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" id="checkbox1_1" name="jk" value="P" class="md-radiobtn">
                            Perempuan
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="form-group form-md-radios">
                      <label class="col-md-3 control-label" for="from_pernikahan1">Status Pernikahan</label>
                      <div class="col-md-9">
                        <div class="radio">
                          <label>
                            <input type="radio" id="from_pernikahan1" name="from_pernikahan" value="0" class="md-radiobtn">
                            Lajang
                          </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" id="from_pernikahan2" name="from_pernikahan" value="1" class="md-radiobtn">
                            Menikah
                          </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" id="from_pernikahan3" name="from_pernikahan" value="2" class="md-radiobtn">
                            Lainnya
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="no_hp">No. HP</label>
                      <div class="col-md-9">
                        <input class="form-control" id="no_hp" name="no_hp" rows="3">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>


                    <div class="form-group form-md-line-input">
                      <hr>
                      <label class="col-md-12 control-label" style="text-align: center; font-size: 19px;">Pendidikan</label>
                    </div>

                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="sd">Sekolah Dasar</label>
                      <div class="col-md-9">
                        <input class="form-control" id="sd" name="sd">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="smp">Sekolah Menengah Pertama</label>
                      <div class="col-md-9">
                        <input class="form-control" id="smp" name="smp">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="sma">Sekolah Menengah Atas</label>
                      <div class="col-md-9">
                        <input class="form-control" id="sma" name="sma">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="diploma">Diploma</label>
                      <div class="col-md-9">
                        <input class="form-control" id="diploma" name="diploma">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="sarjana">Sarjana</label>
                      <div class="col-md-9">
                        <input class="form-control" id="sarjana" name="sarjana">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="sertifikat">Sertifikasi</label>
                      <div class="col-md-9">
                        <input class="form-control" id="sertifikat" name="sertifikat">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="lainnya">Lainnya</label>
                      <div class="col-md-9">
                        <input class="form-control" id="lainnya" name="lainnya">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <hr>
                      <label class="col-md-12 control-label" for="form_control_1" style="text-align: center; font-size: 19px;">Masa Kerja</label>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="status">Status Karyawan <span class="required">*</span></label>
                      <div class="col-md-9">
                        <select class="form-control" id="status" name="status">
                          <option value=""></option>
                          <?php
                          foreach($get_status as $values){
                            if($values->parameter_id == "99"){
                              $ss = 'style="display:none;"';
                            }
                            echo '<option value="'.$values->parameter_id.'" '.$ss.' >'.$values->description.'</option>';
                          }
                          ?>
                        </select>
                        <div class="form-control-focus"></div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="hak_cuti">Hak Cuti </label>
                      <div class="col-md-3">
                        <input type="number" class="form-control" id="hak_cuti" name="hak_cuti">
                        <div class="form-control-focus"></div>
                      </div>
                      <label class="col-md-3 control-label" for="hak_ijin">Hak Ijin </label>
                      <div class="col-md-3">
                        <input type="number" class="form-control" id="hak_ijin" name="hak_ijin">
                        <div class="form-control-focus"></div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="tgl_gabung">Mulai Bergabung<span class="required">*</span></label>
                      <div class="col-md-9">
                        <input class="form-control date-picker" id="tgl_gabung" name="tgl_gabung" data-date-format="dd-mm-yyyy" rows="3">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="from_date_periode">Masa Kerja</label>
                      <div class="col-md-2">
                        <input class="form-control date-picker" data-date-format="dd-mm-yyyy" id="from_date_periode" name="from_date_periode" placeholder="Tanggal Awal">
                        <div class="form-control-focus"> </div>
                      </div>
                      <div class="col-md-2">
                        <input class="form-control date-picker" data-date-format="dd-mm-yyyy" id="thru_date_periode" name="thru_date_periode" rows="3" placeholder="Tanggal Akhir">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="form_control_1">Unit Kerja<span class="required">*</span></label>
                      <div class="col-md-9">
                        <select class="form-control" name="from_branch">
                          <option value=""></option>
                          <?php
                          foreach($get_branch as $values){
                          ?>
                            <option value="<?php echo $values->parameter_id;?>"><?php echo $values->description;?></option>
                          <?php }?>
                        </select>
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="form_control_1">Posisi Kerja<span class="required">*</span></label>
                      <div class="col-md-9">
                        <select class="form-control" name="from_position">
                          <option value=""></option>
                          <?php foreach($get_position as $values){?>
                            <option value="<?php echo $values->parameter_id;?>" ><?php echo $values->description;?></option>
                          <?php }?>
                        </select>
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-actions">
                    <div class="row">
                      <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn green">OK</button>
                        <a href="<?php echo site_url();?>karyawan"><button type="button" class="btn default">BACK</button></a>
                      </div>
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
<script src="<?php echo base_url();?>assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/datepicker/js/bootstrap-datepicker.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/datepicker/locales/bootstrap-datepicker.id.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/scripts/app.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/jquery-repeater/jquery.repeater.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/pages/scripts/form-repeater.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>vendor/jquery-inputmask/jquery.inputmask.bundle.js" type="text/javascript"></script>

<script>
$(document).ready(function() {
  // STATUS KARYAWAN DOM MANAGEMENT
  $('#nik').inputmask("518.9999.9999");
  $('#status').on('change', function(){
    var status   = $(this).val();
    var hak_cuti = $('#hak_cuti');
    var hak_ijin = $('#hak_ijin');
    var thru_periode = $('#thru_date_periode');

    /*
    parameter code
    10 = karyawan training
    11 = perpanjang training
    20 = karyawan kontrak 1
    21 = karyawan kontrak 2
    22 = perpanjang kontrak 2
    30 = karyawan tetap
    40 = karyawan magang
    50 = karyawan resign
     */

    if(status == 10 || status == 11){
      hak_cuti.val(0);
      hak_ijin.val(0);
    }else if(status == 20){
      hak_cuti.val(0);
      hak_ijin.val(6);
    }else if(status == 21 || status == 22 || status == 30){
      hak_cuti.val(12);
      hak_ijin.val(6);
    }else{
      hak_cuti.val(0);
      hak_ijin.val(0);
    }

    if(status == 30){
      thru_periode.val('01-12-2218');
    }else{
      thru_periode.val(null);
    }

  });

  /*$('#status').on('change', function(){
    var status     = $('#status').val();
    var tgl_gabung = $('#tgl_gabung').val();

    masaKerjaConf();

    if(status == 50){
      console.log("aaa");
      $('#ar').fadeIn();
    }else{
      console.log("bbb");
      $('#ar').fadeOut();
    }
  });*/

  $('#tgl_gabung').on('change', function(){
    var status = $('#status').val();
    
    if(status != ''){
      masaKerjaConf();
    }
  });
  // END STATUS KARYAWAN DOM MANAGEMENT

  //$(".date-picker").datepicker({rtl:App.isRTL(),autoclose:!0});
  $(".date-picker").datepicker({
    autoclose: true
  });

  var form1    = $('#form');
  var error1   = $('.alert-danger', form1);
  var success1 = $('.alert-success', form1);


  form1.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-inline', // default input error message class
    focusInvalid: true, // do not focus the last invalid input
    rules: {
      nik: {
        minlength: 13,
        maxlength: 13,
        required: true
      },
      no_ktp: {
        required: true
      },
      fullname: {
        required: true
      },
      tmp_lahir: {
        required: true
      },
      tgl_lahir: {
        required: true
      },
      tgl_gabung: {
        required: true
      },
      from_date_periode: {
        required: true
      },
      thru_date_periode: {
        required: true
      },
      from_branch: {
        required: true
      },
      from_position: {
        required: true
      },
      status: {
        required: true
      },
      hak_cuti: {
        required: true,
        min:0,
        max:12
      },
      hak_ijin: {
        required: true,
        min:0,
        max:12
      }
    },

    invalidHandler: function (event, validator) { //display error alert on form submit              
      success1.hide();
      error1.show();
      App.scrollTo(error1, -200);
    },

    highlight: function (element) { // hightlight error inputs
      $(element).closest('.help-inline').removeClass('ok'); // display OK icon
      $(element).closest('.control-group').removeClass('success').addClass('error'); // set error class to the control group
    },

    unhighlight: function (element) { // revert the change dony by hightlight
      $(element).closest('.control-group').removeClass('error'); // set error class to the control group
    },

    success: function (label) {
      label
      .addClass('valid').addClass('help-inline ok') // mark the current input as valid and display OK icon
      .closest('.control-group').removeClass('error').addClass('success'); // set success class to the control group
    },

    submitHandler: function( form ) {
      $.ajax({
        url         : '<?=site_url('store_karyawan');?>',
        method      : 'post',
        data        : new FormData($('#form')[0]),
        processData : false,
        contentType : false,
        beforeSend  : function(){
          $.blockUI({ message: '<i class="fa fa-spinner fa-spin"></i> Silahkan Tunggu...' });
          var percentVal = '0%';
          $('#bar').width(percentVal);
        },
        uploadProgress: function(event, position, total, percentComplete) {
          var percentVal = percentComplete + '%';
          $('#bar').width(percentVal);
        },
        complete: function(){
          var percentVal = '100%';
          $('#bar').width(percentVal);
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
            console.log("PROCESS STORE SUCCESS");
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

});

/////////////////////////////////////////////////////////////////////////////////////////////////

function masaKerjaConf()
{
  var status     = $('#status').val();
  var tgl_gabung = $('#tgl_gabung').val();
  $('#thru_date_periode').attr('disabled', false);
  $('#from_date_periode').val(tgl_gabung);

  if(status == 30){ // PENGECUALIAN JIKA STATUS KARYAWAN TETAP, DISABLE FORM END MASA KERJA
    $('#thru_date_periode').val('').attr('disabled', true);
  }
}
</script>