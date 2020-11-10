
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
                                <div class="note note-danger hide">
                                    <p> NOTE: The below datatable is not connected to a real database so the filter and sorting is just simulated for demo purposes only. </p>
                                </div>

                                    <div class="portlet light portlet-fit portlet-form bordered" id="add">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class=" icon-layers font-green"></i>
                                                <span class="caption-subject font-green sbold uppercase">Biodata</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                        <?php foreach($get_karyawan_by_nik as $values){?>
                                            <form class="form-horizontal" id="form_sample_1" enctype="multipart/form-data" action="<?php echo site_url();?>karyawan/action_update_karyawan/<?php echo $values->nik;?>" method="post">
                                                <div class="form-body">
                                                    <div class="alert alert-danger display-hide">
                                                        <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                                    <div class="alert alert-success display-hide">
                                                        <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="control-label col-md-3">Foto</label>
                                                        <div class="col-md-9">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"><?php if($values->foto_karyawan != ''){?><img src="<?php echo base_url();?>assets/user/<?php echo $values->foto_karyawan;?>"><?php }?></div>
                                                                <div style="margin-top: 10px">
                                                                    <span class="btn red btn-outline btn-file">
                                                                        <span class="fileinput-new"> Select image </span>
                                                                        <span class="fileinput-exists"> Change </span>
                                                                        <input type="file" name="userfile" id="userfile" /> </span>
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
                                                            <input type="text" class="form-control" placeholder="" name="nik" readonly="readonly" value="<?php echo $values->nik;?>">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">No. KTP
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" placeholder="" name="no_ktp" value="<?php echo $values->no_ktp;?>">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Nama
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" placeholder="" name="fullname" value="<?php echo $values->fullname;?>">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Tempat Lahir
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" placeholder="" name="tmp_lahir" value="<?php echo $values->tmp_lahir;?>">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Tanggal Lahir
                                                            <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control date-picker" placeholder="" name="tgl_lahir" value="<?php echo date('d F Y', strtotime($values->tgl_lahir));?>">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Alamat</label>
                                                        <div class="col-md-9">
                                                            <textarea class="form-control" name="alamat" rows="3"><?php echo $values->alamat;?></textarea>
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-radios">
                                                        <label class="col-md-3 control-label" for="form_control_1">Jenis Kelamin
                                                            <span class="required">*</span></label>
                                                        <div class="col-md-9">
                                                        <div class="md-radio-inline">
                                                            <div class="md-radio">
                                                                <input type="radio" id="checkbox1_1" name="jk" value="L" class="md-radiobtn" <?php if($values->jk == 'L'){echo 'checked="checked"';}?>>
                                                                <label for="checkbox1_1">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Laki - laki </label>
                                                            </div>
                                                            <div class="md-radio">
                                                                <input type="radio" id="checkbox1_2" name="jk" value="P" class="md-radiobtn" <?php if($values->jk == 'P'){echo 'checked="checked"';}?>>
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
                                                                <input type="radio" id="checkbox1_8" name="from_pernikahan" value="0" class="md-radiobtn" <?php if($values->from_pernikahan == '0'){echo 'checked="checked"';}?>>
                                                                <label for="checkbox1_8">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Lajang </label>
                                                            </div>
                                                            <div class="md-radio">
                                                                <input type="radio" id="checkbox1_9" name="from_pernikahan" value="1" class="md-radiobtn" <?php if($values->from_pernikahan == '1'){echo 'checked="checked"';}?>>
                                                                <label for="checkbox1_9">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Menikah </label>
                                                            </div>
                                                            <div class="md-radio">
                                                                <input type="radio" id="checkbox1_10" name="from_pernikahan" value="2" class="md-radiobtn" <?php if($values->from_pernikahan == ''){echo 'checked="checked"';}?>>
                                                                <label for="checkbox1_10">
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
                                                            <input class="form-control" name="no_hp" rows="3" value="<?php echo $values->no_hp?>">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-12 control-label" for="form_control_1" style="text-align: center; font-size: 19px;">Pendidikan</label>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Sekolah Dasar</label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" name="sd" rows="3" value="<?php echo $values->sd;?>">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Sekolah Menengah Pertama</label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" name="smp" rows="3" value="<?php echo $values->smp;?>">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Sekolah Menengah Atas</label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" name="sma" rows="3" value="<?php echo $values->sma;?>">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Diploma</label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" name="diploma" rows="3" value="<?php echo $values->diploma;?>">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Sarjana</label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" name="sarjana" rows="3" value="<?php echo $values->sarjana;?>">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Lainnya</label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" name="lainya" rows="3" value="<?php echo $values->lainnya;?>">
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
                                                            <input class="form-control" name="tgl_gabung" rows="3" readonly="readonly" value="<?php echo $values->tgl_masuk;?>">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Status Karyawan
                                                            <span class="required">*</span></label>
                                                        <div class="col-md-9">
                                                            <input class="form-control" readonly="readonly" value="<?php echo $values->post_status;?>" rows="3" placeholder="Akhir Training">
                                                        </div>
                                                        <!--<div class="col-md-9">
                                                            <div class="md-radio-inline">
                                                                <div class="md-radio">
                                                                    <input type="radio" id="checkbox1_3" name="status" value="2" class="md-radiobtn" <?php if($values->status == '2'){echo 'checked="checked"';}?>>
                                                                    <label for="checkbox1_3">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Karyawan Training </label>
                                                                </div>
                                                                <div class="md-radio">
                                                                    <input type="radio" id="checkbox1_4" name="status" value="1" class="md-radiobtn" <?php if($values->status == '1'){echo 'checked="checked"';}?>>
                                                                    <label for="checkbox1_4">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Karyawan Kontrak </label>
                                                                </div>
                                                                <div class="md-radio">
                                                                    <input type="radio" id="checkbox1_5" name="status" value="0" class="md-radiobtn" <?php if($values->status == '0'){echo 'checked="checked"';}?>>
                                                                    <label for="checkbox1_5">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Karyawan Tetap </label>
                                                                </div>
                                                                <div class="md-radio">
                                                                    <input type="radio" id="checkbox1_6" name="status" value="3" class="md-radiobtn" <?php if($values->status == '3'){echo 'checked="checked"';}?>>
                                                                    <label for="checkbox1_6">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> Magang </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                            <input type="button" id="show" data-toggle="modal" data-id="<?php echo $values->nik;?>" href="#detail" class="btn btn-sm default" style="margin-left: 14px; margin-top: 5px;" value="Rubah Status Karyawan">-->
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Periode
                                                            <span class="required">*</span></label>
                                                        <div class="col-md-2">
                                                            <input class="form-control" name="from_training" readonly="readonly" value="<?php echo $values->from_periode;?>" rows="3" placeholder="Periode Awal">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input class="form-control" name="thru_training" readonly="readonly" value="<?php echo $values->thru_periode;?>" rows="3" placeholder="Periode Akhir">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Unit Kerja</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control" disabled="disabled">
                                                                <option value=""></option>
                                                                <?php foreach($get_branch as $value){?>
                                                                    <option value="<?php echo $value->parameter_id;?>" <?php if($value->parameter_id == $values->thru_branch){echo 'selected';}?>><?php echo $value->description;?></option>
                                                                <?php }?>
                                                            </select>
                                                            <input type="hidden" name="from_branch" value="<?php echo $values->thru_branch;?>">
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-md-line-input">
                                                        <label class="col-md-3 control-label" for="form_control_1">Posisi Kerja</label>
                                                        <div class="col-md-9">
                                                            <select class="form-control" disabled="disabled">
                                                                <option value=""></option>
                                                                <?php foreach($get_position as $valuess){?>
                                                                    <option value="<?php echo $valuess->parameter_id;?>" <?php if($valuess->parameter_id == $values->thru_position){echo 'selected';}?>><?php echo $valuess->description;?></option>
                                                                <?php }?>
                                                            </select>
                                                            <input type="hidden" name="from_position" value="<?php echo $values->thru_position;?>">
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
                                        <?php break; }?>
                                        </div>
                                    </div>  

                            </div>
                        </div>
                    </div>

                                        <div class="modal fade" id="detail" tabindex="-1" role="basic" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Mutasi Cabang</h4>
                                                    </div>
                                                    <div class="modal-body"> 
                                                        <form  method="post" action="<?php echo site_url();?>karyawan/action_update_status_karyawan" role="form">
                                                        <table class="table table-hover">
                                                            <tbody>
                                                                <tr>
                                                                    <th style="text-align: center;"> Status Awal </th>
                                                                    <td><input id="status" type="text" class="form-control" readonly="readonly"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="text-align: center;"> Periode </th>
                                                                    <td><input id="periode" type="text" class="form-control" readonly="readonly" name="periode"><input id="niks" type="hidden" name="nik" class="form-control" readonly="readonly"></td>
                                                                </tr>
                                                                <tr>
                                                                  <th style="text-align: center;"> Status Akhir </th>
                                                                    <th>
                                                                        <div class="md-radio">
                                                                            <input type="radio" id="checkbox1_4" name="status" value="1" class="md-radiobtn">
                                                                            <label for="checkbox1_4">
                                                                                <span></span>
                                                                                <span class="check"></span>
                                                                                <span class="box"></span> Karyawan Training </label>
                                                                        </div>
                                                                        <div class="md-radio">
                                                                            <input type="radio" id="checkbox1_5" name="status" value="2" class="md-radiobtn">
                                                                            <label for="checkbox1_5">
                                                                                <span></span>
                                                                                <span class="check"></span>
                                                                                <span class="box"></span> Karyawan Kontrak 1 </label>
                                                                        </div>
                                                                        <div class="md-radio">
                                                                            <input type="radio" id="checkbox1_3" name="status" value="5" class="md-radiobtn">
                                                                            <label for="checkbox1_3">
                                                                                <span></span>
                                                                                <span class="check"></span>
                                                                                <span class="box"></span> Karyawan Kontrak 2 </label>
                                                                        </div>
                                                                        <div class="md-radio">
                                                                            <input type="radio" id="checkbox1_6" name="status" value="0" class="md-radiobtn">
                                                                            <label for="checkbox1_6">
                                                                                <span></span>
                                                                                <span class="check"></span>
                                                                                <span class="box"></span> Karyawan Tetap </label>
                                                                        </div>
                                                                        <div class="md-radio">
                                                                            <input type="radio" id="checkbox1_7" name="status" value="3" class="md-radiobtn">
                                                                            <label for="checkbox1_7">
                                                                                <span></span>
                                                                                <span class="check"></span>
                                                                                <span class="box"></span> Magang </label>
                                                                        </div>
                                                                    </th>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                            <button type="submit" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 0px; margin-left: 500px; margin-bottom: 20px">Save</button>
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

        

        <script type="text/javascript">
            var TableDatatablesButtons = function(){   
                n = function(){
                    $(".date-picker").datepicker({rtl:App.isRTL(),autoclose:!0});
                    var e=new Datatable;

                    e.init({
                        src:$("#datatable_ajax"),
                        onSuccess:function(e,t){},
                        onError:function(e){},
                        onDataLoad:function(e){},
                        loadingMessage:"Loadingss...",
                        dataTable:{
                            bStateSave:!0,
                            lengthMenu:[[10,20,50,100,150,-1],[10,20,50,100,150,"All"]],
                            pageLength:10,ajax:{url:"<?php echo site_url('karyawan/get_karyawan');?>"},
                            order:[[1,"asc"]],
                            buttons:[
                            {extend:"print",className:"btn default"},
                            {extend:"copy",className:"btn default"},
                            {extend:"pdf",className:"btn default"},
                            {extend:"excel",className:"btn default"},
                            {extend:"csv",className:"btn default"}]}
                    }),                  

                    $("#datatable_ajax_tools > li > a.tool-action").on("click",function(){
                        var t=$(this).attr("data-action");
                        e.getDataTable().button(t).trigger()})

                      $("#btn_add").click(function(){
                        $("#wrapper-table").hide();
                        $("#add").show();
                      });

                      $("#cancel","#form_sample_1").click(function(){
                        success1.hide();
                        error1.hide();
                        $("#add").hide();
                        $("#wrapper-table").show();
                        dTreload();
                      });


                    var form1 = $('#form_sample_1');
                    var error1 = $('.alert-danger', form1);
                    var success1 = $('.alert-success', form1);

                    form1.validate({
                      errorElement: 'span', //default input error message container
                      errorClass: 'help-block help-block-error', // default input error message class
                      focusInvalid: false, // do not focus the last invalid input
                      ignore: "",
                      rules: {
                                    nik: {
                                        minlength: 13,
                                        maxlength:13,
                                        required: true
                                    },
                                    no_ktp: {
                                        required: true
                                    },
                                    nama: {
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
                                    tgl_gabung: {
                                        required: true
                                    },
                                    thru_training: {
                                        required: true
                                    },
                                    status: {
                                        required: true
                                    }
                      },

                      invalidHandler: function (event, validator) { //display error alert on form submit              
                          success1.hide();
                          error1.show();
                          App.scrollTo(error1, -200);
                      },

                      highlight: function (element) { // hightlight error inputs
                          $(element)
                              .closest('.help-inline').removeClass('ok'); // display OK icon
                          $(element)
                              .closest('.control-group').removeClass('success').addClass('error'); // set error class to the control group
                      },

                      unhighlight: function (element) { // revert the change dony by hightlight
                          $(element)
                              .closest('.control-group').removeClass('error'); // set error class to the control group
                      },

                      success: function (label) {
                          label
                              .addClass('valid').addClass('help-inline ok') // mark the current input as valid and display OK icon
                          .closest('.control-group').removeClass('error').addClass('success'); // set success class to the control group
                      },

                        submitHandler: false
                    });

                };
                
                  $("button[type=submit]","#form_sample_1").click(function(e){

                    if($(this).valid()==true)
                    {
                      form1.ajaxForm({
                          data: form1.serialize(),
                          dataType: "json",
                          success: function(response) {
                            if(response.success==true){
                              success1.show();
                              error1.hide();
                              form1.trigger('reset');
                              form1.children('div').removeClass('success');
                              $("#cancel",form_sample_1).trigger('click')
                              alert('Successfully Saved Data');
                            }else{
                              success1.hide();
                              error1.show();
                            }
                          },
                          error:function(){
                              success1.hide();
                              error1.show();
                          }
                      });
                    }
                    else
                    {
                      alert('Please fill the empty field before.');
                    }

                  });

        $('#detail').on('show.bs.modal', function (e) {
            var rowid = $(e.relatedTarget).data('id');

            $.ajax({
                  type: "POST",
                  dataType: "json",
                  data: {nik:rowid},
                  url: '<?php echo site_url('mutasi/get_karyawan_by_nik'); ?>',
                success : function(response){
                    var get_status = response.status;
                    var post_status = '';
                    var get_periode_training = response.periode_training;
                    var get_periode_kontrak_1 = response.periode_kontrak_1;
                    var get_periode_kontrak_2 = response.periode_kontrak_2;
                    var post_periode = '';

                    if(get_status == '0'){post_status = "Karyawan Tetap";}else if(get_status == '1'){post_status = "Karyawan Training";}else if(get_status == '2'){post_status = "Karyawan Kontrak";}else if(get_status == '3'){post_status = "Karyawan Magang";}else if(get_status == '4'){post_status = "Resign";}else{post_status = '';}

                    if(get_status == '1'){post_periode = get_periode_training;}else if(get_status == '2'){if(get_periode_kontrak_2 == ''){post_periode = get_periode_kontrak_1;}else{post_periode = get_periode_kontrak_2;}}else{post_periode = '';}

                    $("#niks").val(response.nik);
                    $("#karyawan").val(response.fullname);
                    $("#status").val(post_status);
                    $("#position").val(response.position);
                    $("#periode").val(post_periode);

                }
            });
         });
                
                  
                    return{
                        init:function(){
                            jQuery().dataTable&&(n());
                            handleValidation1();                            
                        }
                    }
                }();

                        jQuery(document).ready(function(){
                            TableDatatablesButtons.init()
                        });




        </script>




                    