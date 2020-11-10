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
            <span class="caption-subject font-green sbold uppercase">Edit Karyawan</span>
          </div>
        </div>
        <div class="portlet-body">
          <form class="form-horizontal" id="form">
            <input type="hidden" class="form-control" id="karyawan_id" name="karyawan_id" value="<?=$arr_karyawan->row('karyawan_id');?>">
            <input type="hidden" class="form-control" id="prev_nik" name="prev_nik" value="<?=$arr_karyawan->row('nik');?>">
            <div class="form-body">
              <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                <div class="alert alert-success display-hide">
                  <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                  <div class="form-group form-md-line-input">
                    <label class="control-label col-md-3">Foto</label>
                    <div class="col-md-9">
                      <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                          <?php
                          if( is_file( FCPATH.'assets/foto_karyawan/'.$arr_karyawan->row('foto_karyawan') ) ){
                            $foto = base_url('assets/foto_karyawan/'.$arr_karyawan->row('foto_karyawan'));
                          }else{
                            $foto = base_url('assets/foto_karyawan/default_user.jpg');
                          }
                          ?>
                          <img src="<?=$foto;?>">
                        </div>
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
                        <input type="text" class="form-control" placeholder="" id="nik" name="nik" value="<?=$arr_karyawan->row('nik');?>">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="no_ktp">No. KTP <span class="required">*</span>
                      </label>
                      <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="" id="no_ktp" name="no_ktp" value="<?=$arr_karyawan->row('no_ktp');?>">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="fullname">Nama
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="" id="fullname" name="fullname" value="<?=$arr_karyawan->row('fullname');?>">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="tmp_lahir">Tempat Lahir
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="" name="tmp_lahir" name="tmp_lahir" value="<?=$arr_karyawan->row('tmp_lahir');?>">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="tgl_lahir">Tanggal Lahir
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9">
                        <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" placeholder="" id="tgl_lahir" name="tgl_lahir" value="<?=date('d-m-Y', strtotime($arr_karyawan->row('tgl_lahir')));?>">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="alamat">Alamat</label>
                      <div class="col-md-9">
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"><?=$arr_karyawan->row('alamat');?></textarea>
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>

                    <div class="form-group form-md-radios">
                      <label class="col-md-3 control-label" for="checkbox1_1">Jenis Kelamin <span class="required">*</span></label>
                      <div class="col-md-9">
                        <?php
                        if($arr_karyawan->row('jk') == "L"){
                          $selected_l = "checked";
                          $selected_p = "";
                        }elseif($arr_karyawan->row('jk') == "P"){
                          $selected_l = "";
                          $selected_p = "checked";
                        }else{
                          $selected_l = "";
                          $selected_p = "";
                        }
                        ?>
                        <div class="radio">
                          <label>
                            <input <?=$selected_l;?> type="radio" id="checkbox1_1" name="jk" value="L" class="md-radiobtn">
                            Laki-Laki
                          </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input <?=$selected_p;?> type="radio" id="checkbox1_1" name="jk" value="P" class="md-radiobtn">
                            Perempuan
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="form-group form-md-radios">
                      <label class="col-md-3 control-label" for="from_pernikahan1">Status Pernikahan</label>
                      <div class="col-md-9">
                        <?php
                        if($arr_karyawan->row('from_pernikahan') == "0"){
                          $selected_0 = "checked";
                          $selected_1 = "";
                          $selected_2 = "";
                        }elseif($arr_karyawan->row('from_pernikahan') == "1"){
                          $selected_0 = "";
                          $selected_1 = "checked";
                          $selected_2 = "";
                        }elseif($arr_karyawan->row('from_pernikahan') == "2"){
                          $selected_0 = "";
                          $selected_1 = "";
                          $selected_2 = "checked";
                        }else{
                          $selected_0 = "";
                          $selected_1 = "";
                          $selected_2 = "";
                        }
                        ?>
                        <div class="radio">
                          <label>
                            <input <?=$selected_0;?> type="radio" id="from_pernikahan1" name="from_pernikahan" value="0" class="md-radiobtn">
                            Lajang
                          </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input <?=$selected_1;?> type="radio" id="from_pernikahan2" name="from_pernikahan" value="1" class="md-radiobtn">
                            Menikah
                          </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input <?=$selected_2;?> type="radio" id="from_pernikahan3" name="from_pernikahan" value="2" class="md-radiobtn">
                            Lainnya
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="no_hp">No. HP</label>
                      <div class="col-md-9">
                        <input class="form-control" id="no_hp" name="no_hp" value="<?=$arr_karyawan->row('no_hp');?>">
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
                        <input class="form-control" id="sd" name="sd" value="<?=$arr_karyawan->row('sd');?>">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="smp">Sekolah Menengah Pertama</label>
                      <div class="col-md-9">
                        <input class="form-control" id="smp" name="smp" value="<?=$arr_karyawan->row('smp');?>">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="sma">Sekolah Menengah Atas</label>
                      <div class="col-md-9">
                        <input class="form-control" id="sma" name="sma" value="<?=$arr_karyawan->row('sma');?>">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="diploma">Diploma</label>
                      <div class="col-md-9">
                        <input class="form-control" id="diploma" name="diploma" value="<?=$arr_karyawan->row('diploma');?>">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="sarjana">Sarjana</label>
                      <div class="col-md-9">
                        <input class="form-control" id="sarjana" name="sarjana" value="<?=$arr_karyawan->row('sarjana');?>">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="sertifikat">Sertifikasi</label>
                      <div class="col-md-9">
                        <input class="form-control" id="sertifikat" name="sertifikat" value="<?=$arr_karyawan->row('sertifikat');?>">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="lainnya">Lainnya</label>
                      <div class="col-md-9">
                        <input class="form-control" id="lainnya" name="lainnya" value="<?=$arr_karyawan->row('lainnya');?>">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>



                    <div class="form-group form-md-line-input">
                      <hr>
                      <label class="col-md-12 control-label" for="form_control_1" style="text-align: center; font-size: 19px;">Masa Kerja</label>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="status">Status Karyawan<span class="required">*</span></label>
                      <div class="col-md-9">
                        <select class="form-control" id="status" name="status">
                          <option value=""></option>
                          <?php
                          foreach($get_status as $values){
                            if($arr_karyawan->row('status') == $values->description){
                              $selected = "selected";
                            }else{
                              $selected = "";
                            }
                            echo '<option '.$selected.' value="'.$values->parameter_id.'">'.$values->description.'</option>';
                          }
                          ?>
                        </select>
                        <div class="form-control-focus"></div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="hak_cuti">Hak Cuti </label>
                      <div class="col-md-3">
                        <input type="number" class="form-control" id="hak_cuti" name="hak_cuti" value="<?=$arr_karyawan->row('hak_cuti');?>">
                        <div class="form-control-focus"></div>
                      </div>
                      <label class="col-md-3 control-label" for="hak_ijin">Hak Ijin </label>
                      <div class="col-md-3">
                        <input type="number" class="form-control" id="hak_ijin" name="hak_ijin" value="<?=$arr_karyawan->row('hak_ijin');?>">
                        <div class="form-control-focus"></div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="tgl_gabung">Mulai Bergabung<span class="required">*</span></label>
                      <div class="col-md-9">
                        <input class="form-control date-picker" id="tgl_gabung" name="tgl_gabung" data-date-format="dd-mm-yyyy" value="<?=normalin_date($arr_karyawan->row('tgl_masuk'));?>">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="from_date_periode">Masa Kerja</label>
                      <div class="col-md-2">
                        <input class="form-control date-picker" data-date-format="dd-mm-yyyy" id="from_date_periode" name="from_date_periode" placeholder="Tanggal Awal" value="<?=normalin_date($arr_karyawan->row('from_date'));?>">
                        <div class="form-control-focus"> </div>
                      </div>
                      <div class="col-md-2">
                        <input class="form-control date-picker" data-date-format="dd-mm-yyyy" id="thru_date_periode" name="thru_date_periode" rows="3" placeholder="Tanggal Akhir" value="<?=normalin_date($arr_karyawan->row('thru_date'));?>">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="form_control_1">Unit Kerja<span class="required">*</span></label>
                      <div class="col-md-9">
                        <select class="form-control" name="from_branch">
                          <option value=""></option>
                          <?php foreach($get_branch as $values){?>
                            <?php
                            if($arr_karyawan->row('thru_branch') == $values->description){
                              $selected = "selected";
                            }else{
                              $selected = "";
                            }
                            ?>
                            <option <?=$selected;?> value="<?php echo $values->parameter_id;?>"><?php echo $values->description;?></option>
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
                            <?php
                            if($arr_karyawan->row('thru_position') == $values->description){
                              $selected = "selected";
                            }else{
                              $selected = "";
                            }
                            ?>
                            <option <?=$selected;?> value="<?php echo $values->parameter_id;?>"><?php echo $values->description;?></option>
                          <?php }?>
                        </select>
                        <div class="form-control-focus"> </div>
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

        <?php
        function normalin_date($date)
        {
          $date = date('d-m-Y', strtotime($date));
          return $date;
        }
        ?>

        <script src="<?php echo base_url();?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <!--script src="<?php echo base_url();?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
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

        <script>
          jQuery(document).ready(function() {
  // STATUS KARYAWAN DOM MANAGEMENT
  $('#status').on('change', function(){
    var status = $('#status').val();
    var tgl_gabung = $('#tgl_gabung').val();

    masaKerjaConf();

    if(status == 50){
      console.log("aaa");
      $('#ar').fadeIn();
    }else{
      console.log("bbb");
      $('#ar').fadeOut();
    }
  });

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
      jk: {
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
      },
      from_position: {
        required: true
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
        url         : '<?=site_url('update_karyawan');?>',
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
            console.log("PROCESS UPDATE SUCCESS");
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
  var status = $('#status').val();
  var tgl_gabung = $('#tgl_gabung').val();
  $('#thru_date_periode').attr('disabled', false);
  $('#from_date_periode').val(tgl_gabung);

  if(status == 30){ // PENGECUALIAN JIKA STATUS KARYAWAN TETAP, DISABLE FORM END MASA KERJA
    $('#thru_date_periode').val('').attr('disabled', true);
  }
}
</script>