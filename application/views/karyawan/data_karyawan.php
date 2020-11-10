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
      <!-- Begin: life time stats -->
      <div class="portlet light portlet-fit portlet-datatable bordered" id="wrapper-table">
        <div class="portlet-title">
          <div class="caption">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase">Tabel Karyawan</span>
          </div>
        </div>
        <div class="portlet-body">
          <div class="table-toolbar">
            <div class="row">
              <div class="col-md-6">
                <div class="btn-group">
                  <button id="btn_add" class="btn sbold green"> Add New <i class="fa fa-plus"></i></button>
                </div>
              </div>
            </div>
          </div>
          <table class="table table-striped table-bordered table-hover table-checkable" id="kantor_cabang_table">
            <thead>
              <tr role="row" class="heading">
                <th width="2%">
                  <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                    <input type="checkbox" class="group-checkable" data-set="#kantor_cabang_table .checkboxes" />
                    <span></span>
                  </label>
                </th>
                <th width="5%"> NIK</th>
                <th width="15%"> Nama </th>
                <th width="200"> Position </th>
                <th width="10%"> Branch </th>
                <th width="10%"> Status </th>
                <th width="10%"> Actions </th>
              </tr>
            </thead>
            <tbody> </tbody>
          </table>
        </div>
      </div>

      <div class="portlet light portlet-fit portlet-form bordered" id="add">
        <div class="portlet-title">
          <div class="caption">
            <i class=" icon-layers font-green"></i>
            <span class="caption-subject font-green sbold uppercase">Karyawan Baru</span>
          </div>
        </div>
        <div class="portlet-body">
          <form class="form-horizontal" id="form_add" action="#" method="post">
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
                        <div style="margin-top: 10px">
                          <span class="btn red btn-outline btn-file">
                            <span class="fileinput-new"> Select image </span>
                            <span class="fileinput-exists"> Change </span>
                            <input type="file" name="userfile" id="filefoto"> </span>
                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="form_control_1">NIK
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="" name="nik">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="form_control_1">No. KTP
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="" name="no_ktp">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="form_control_1">Nama
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="" name="fullname">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="form_control_1">Tempat Lahir
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9">
                        <input type="text" class="form-control" placeholder="" name="tmp_lahir">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="form_control_1">Tanggal Lahir
                        <span class="required">*</span>
                      </label>
                      <div class="col-md-9">
                        <input type="text" class="form-control date-picker" placeholder="" name="tgl_lahir">
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-line-input">
                      <label class="col-md-3 control-label" for="form_control_1">Alamat</label>
                      <div class="col-md-9">
                        <textarea class="form-control" name="alamat" rows="3"></textarea>
                        <div class="form-control-focus"> </div>
                      </div>
                    </div>
                    <div class="form-group form-md-radios">
                      <label class="col-md-3 control-label" for="form_control_1">Jenis Kelamin
                        <span class="required">*</span></label>
                        <div class="col-md-9">
                          <div class="md-radio-inline">
                            <div class="md-radio">
                              <input type="radio" id="checkbox1_1" name="jk" value="L" class="md-radiobtn">
                              <label for="checkbox1_1">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span> Laki - laki </label>
                              </div>
                              <div class="md-radio">
                                <input type="radio" id="checkbox1_2" name="jk" value="P" class="md-radiobtn">
                                <label for="checkbox1_2">
                                  <span></span>
                                  <span class="check"></span>
                                  <span class="box"></span> Perempuan </label>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="form-group form-md-radios">
                            <label class="col-md-3 control-label" for="form_control_2">Status Pernikahan</label>
                            <div class="col-md-9">
                              <div class="md-radio-inline">
                                <div class="md-radio">
                                  <input type="radio" id="checkbox1_3" name="from_pernikahan" value="0" class="md-radiobtn">
                                  <label for="checkbox1_3">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Lajang </label>
                                  </div>
                                  <div class="md-radio">
                                    <input type="radio" id="checkbox1_4" name="from_pernikahan" value="1" class="md-radiobtn">
                                    <label for="checkbox1_4">
                                      <span></span>
                                      <span class="check"></span>
                                      <span class="box"></span> Menikah </label>
                                    </div>
                                    <div class="md-radio">
                                      <input type="radio" id="checkbox1_5" name="from_pernikahan" value="2" class="md-radiobtn">
                                      <label for="checkbox1_5">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> Lainya </label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                  <label class="col-md-3 control-label" for="form_control_1">No. HP</label>
                                  <div class="col-md-9">
                                    <input class="form-control" name="no_hp" rows="3">
                                    <div class="form-control-focus"> </div>
                                  </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                  <label class="col-md-12 control-label" for="form_control_1" style="text-align: center; font-size: 19px;">Pendidikan</label>
                                </div>
                                <div class="form-group form-md-line-input">
                                  <label class="col-md-3 control-label" for="form_control_1">Sekolah Dasar</label>
                                  <div class="col-md-9">
                                    <input class="form-control" name="sd" rows="3" value="-">
                                    <div class="form-control-focus"> </div>
                                  </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                  <label class="col-md-3 control-label" for="form_control_1">Sekolah Menengah Pertama</label>
                                  <div class="col-md-9">
                                    <input class="form-control" name="smp" rows="3" value="-">
                                    <div class="form-control-focus"> </div>
                                  </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                  <label class="col-md-3 control-label" for="form_control_1">Sekolah Menengah Atas</label>
                                  <div class="col-md-9">
                                    <input class="form-control" name="sma" rows="3" value="-">
                                    <div class="form-control-focus"> </div>
                                  </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                  <label class="col-md-3 control-label" for="form_control_1">Diploma</label>
                                  <div class="col-md-9">
                                    <input class="form-control" name="diploma" rows="3" value="-">
                                    <div class="form-control-focus"> </div>
                                  </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                  <label class="col-md-3 control-label" for="form_control_1">Sarjana</label>
                                  <div class="col-md-9">
                                    <input class="form-control" name="sarjana" rows="3" value="-">
                                    <div class="form-control-focus"> </div>
                                  </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                  <label class="col-md-3 control-label" for="form_control_1">Sertifikasi</label>
                                  <div class="col-md-9">
                                    <input class="form-control" name="sertifikat" rows="3" value="-">
                                    <div class="form-control-focus"> </div>
                                  </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                  <label class="col-md-3 control-label" for="form_control_1">Lainnya</label>
                                  <div class="col-md-9">
                                    <input class="form-control" name="lainya" rows="3" value="-">
                                    <div class="form-control-focus"> </div>
                                  </div>
                                </div>
                                                    <!--<div class="form-group form-md-line-input">
                                                        <label class="col-md-12 control-label" for="form_control_1" style="text-align: center; font-size: 19px;">Pekerjaan Sebelumnya</label>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Pengalaman Kerja</label>
                                                        <div class="col-md-9">
                                                            <div class="mt-repeater">
                                                                <div data-repeater-list="group-b">
                                                                    <div data-repeater-item class="row">
                                                                        <input class="form-control" name="pengalaman" rows="3" placeholder="Pengalaman - Lama Kerja">
                                                                    </div>
                                                                </div>
                                                                <a href="javascript:;" data-repeater-create class="btn btn-info mt-repeater-add" style="margin-top: 20px; margin-left: -14px;">
                                                                    <i class="fa fa-plus"></i> </a>
                                                                <br>
                                                                <br> </div>
                                                        </div>
                                                      </div>-->
                                                      <div class="form-group form-md-line-input">
                                                        <label class="col-md-12 control-label" for="form_control_1" style="text-align: center; font-size: 19px;">Masa Kerja</label>
                                                      </div>
                                                      <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Mulai Bergabung
                                                          <span class="required">*</span></label>
                                                          <div class="col-md-9">
                                                            <input class="form-control date-picker" name="tgl_gabung" rows="3">
                                                            <div class="form-control-focus"> </div>
                                                          </div>
                                                        </div>
                                                        <div class="form-group form-md-line-input">
                                                          <label class="col-md-3 control-label" for="form_control_1">Periode Training
                                                            <span class="required">*</span></label>
                                                            <div class="col-md-2">
                                                              <input class="form-control date-picker" name="from_training" rows="3" placeholder="Awal Training">
                                                              <div class="form-control-focus"> </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                              <input class="form-control date-picker" name="thru_training" rows="3" placeholder="Akhir Training">
                                                              <div class="form-control-focus"> </div>
                                                            </div>
                                                          </div>
                                                          <div class="form-group form-md-radios">
                                                            <label class="col-md-3 control-label" for="form_control_1">Status Karyawan
                                                              <span class="required">*</span></label>
                                                              <div class="col-md-9">
                                                                <div class="md-radio-inline">
                                                                  <div class="md-radio">
                                                                    <input type="radio" id="checkbox1_8" name="status" value="2" class="md-radiobtn">
                                                                    <label for="checkbox1_8">
                                                                      <span></span>
                                                                      <span class="check"></span>
                                                                      <span class="box"></span> Karyawan Training </label>
                                                                    </div>
                                                                    <div class="md-radio">
                                                                      <input type="radio" id="checkbox1_9" name="status" value="1" class="md-radiobtn">
                                                                      <label for="checkbox1_9">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Karyawan Kontrak </label>
                                                                      </div>
                                                                      <div class="md-radio">
                                                                        <input type="radio" id="checkbox1_10" name="status" value="0" class="md-radiobtn">
                                                                        <label for="checkbox1_10">
                                                                          <span></span>
                                                                          <span class="check"></span>
                                                                          <span class="box"></span> Karyawan Tetap </label>
                                                                        </div>
                                                                        <div class="md-radio">
                                                                          <input type="radio" id="checkbox1_11" name="status" value="3" class="md-radiobtn">
                                                                          <label for="checkbox1_11">
                                                                            <span></span>
                                                                            <span class="check"></span>
                                                                            <span class="box"></span> Magang </label>
                                                                          </div>
                                                                        </div>
                                                                      </div>
                                                                    </div>
                                                                    <div class="form-group form-md-line-input">
                                                                      <label class="col-md-3 control-label" for="form_control_1">Unit Kerja
                                                                        <span class="required">*</span></label>
                                                                        <div class="col-md-9">
                                                                          <select class="form-control" name="from_branch">
                                                                            <option value=""></option>
                                                                            <?php foreach($get_branch as $values){?>
                                                                              <option value="<?php echo $values->parameter_id;?>"><?php echo $values->description;?></option>
                                                                            <?php }?>
                                                                          </select>
                                                                          <div class="form-control-focus"> </div>
                                                                        </div>
                                                                      </div>
                                                                      <div class="form-group form-md-line-input">
                                                                        <label class="col-md-3 control-label" for="form_control_1">Posisi Kerja
                                                                          <span class="required">*</span></label>
                                                                          <div class="col-md-9">
                                                                            <select class="form-control" name="from_position">
                                                                              <option value=""></option>
                                                                              <?php foreach($get_position as $values){?>
                                                                                <option value="<?php echo $values->parameter_id;?>"><?php echo $values->description;?></option>
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
                                                                            <button type="button" class="btn default" id="cancel">BACK</button>
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
                                                          <script src="<?php echo base_url();?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
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
                                                          <script src="<?php echo base_url(); ?>assets/plugins/data-tables/jquery.dataTables.js" type="text/javascript"></script>
                                                          <script src="<?php echo base_url(); ?>assets/plugins/data-tables/DT_bootstrap.js" type="text/javascript"></script>

                                                          <script>
                                                           jQuery(document).ready(function() {    
                App.init(); // initlayout and core plugins
                $('#add').hide();

                var form1 = $('#form_sample_1');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);

                $("#btn_add").click(function(){
                  $("#wrapper-table").hide();
                  $("#add").show();
                });

                $("#cancel","#form_add").click(function(){
                  success1.hide();
                  error1.hide();
                  $("#add").hide();
                  $("#wrapper-table").show();
                  dTreload();
                });

      // begin first table
      $('#kantor_cabang_table').dataTable({
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": site_url+"cif/datatable_kantor_cabang_setup",
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




