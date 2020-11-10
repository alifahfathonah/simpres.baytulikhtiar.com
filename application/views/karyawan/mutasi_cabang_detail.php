
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
                            <small>Mutasi Cabang</small>
                        </h1>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="portlet light bordered">
                                  <form action="<?php echo site_url();?>mutasi/action_mutasi_cabang" role="form" method="post">
                                    <div class="portlet-body">
                                        <!--<div class="form-group">
                                            <label for="single" class="control-label">Karyawan</label>
                                                    <select id="single-prepend-text" class="form-control select2" name="nik">
                                                        <option></option>
                                                        <?php foreach($get_karyawan_by_branch as $values){?>
                                                            <option value="<?php echo $values->nik;?>"><?php echo $values->nik;?> - <?php echo $values->fullname;?></option>
                                                        <?php }?>
                                                    </select>
                                        </div>-->

                                        <?php foreach($get_karyawan_by_nik as $values){?>
                                        <div class="form-group">
                                            <label for="default" class="control-label">NIK</label>
                                            <input id="default" type="text" class="form-control" name="nik_" readonly="readonly" value="<?php echo $values->nik;?>"> 
                                        </div>
                                        <div class="form-group">
                                            <label for="default" class="control-label">Nama</label>
                                            <input id="default" type="text" class="form-control" value="<?php echo $values->fullname;?>" readonly="readonly"> 
                                        </div>
                                        <div class="form-group">
                                            <label for="default" class="control-label">Jabatan</label>
                                            <input id="default" type="text" class="form-control" value="<?php echo $values->position_name;?>" readonly="readonly"> 
                                        </div>
                                        <div class="form-group">
                                            <label for="default" class="control-label">Cabang/Unit</label>
                                            <input id="default" type="text" class="form-control" value="<?php echo $values->branch_name;?>" readonly="readonly"> 
                                        </div>
                                        <div class="form-group">
                                            <label for="single" class="control-label">Mutasi Cabang Ke </label>
                                                    <select id="single-prepend-text" class="form-control select2" name="cabang">
                                                        <option></option>
                                                        <?php foreach($get_branch as $value){?>
                                                            <option value="<?php echo $value->branch_code;?>"><?php echo $value->branch_name;?></option>
                                                        <?php }?>
                                                    </select>
                                        </div>
                                    <?php }?>
                                            <div class="form-group">
                                                <button type="submit" class="btn blue-hoki btn-outline sbold uppercase" style="margin-top: 24px">OK</button>
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