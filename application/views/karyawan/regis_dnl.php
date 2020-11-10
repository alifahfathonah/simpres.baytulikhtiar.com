
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
                            <small>Regis Dinas Luar Kantor</small>
                        </h1>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="portlet light bordered">
                                  <form action="<?php echo site_url();?>karyawan/action_dnl" role="form" method="post">
                                    <div class="portlet-body">
                                        <button id="sample_editable_1_new" class="btn sbold green" style="margin-bottom: 20px"  data-toggle="modal" href="#detail" type="button"> Pembatalan DnL
                                        </button>
                                        <div class="form-group">
                                            <label for="single" class="control-label">Cabang/Unit</label>
                                                    <select id="branch_" class="form-control select2" name="branch_">
                                                        <option></option>
                                                        <?php foreach($get_branch as $values){?>
                                                            <option value="<?php echo $values->parameter_id;?>"><?php echo $values->description;?></option>
                                                        <?php }?>
                                                    </select>
                                        </div>
                                        <div class="form-group" id="list">
                                            <label for="single" class="control-label">Karyawan</label>
                                                    <?php if($branch_user == '1'){?>
                                                    <select class="form-control select2" name="nik" id="nik">
                                                    </select>
                                                    <?php }else{?>
                                                    <select class="form-control select2" name="nik">
                                                        <option></option>
                                                        <?php foreach($get_karyawan_by_branch as $values){?>
                                                            <option value="<?php echo $values->nik;?>"><?php echo $values->nik;?> - <?php echo $values->fullname;?></option>
                                                        <?php }?>
                                                    </select>
                                                  <?php }?>
                                        </div>
                                        <div class="form-group">
                                            <label for="default" class="control-label">NIK</label>
                                            <input id="nik_" name="nik_" type="text" class="form-control" readonly="readonly"> 
                                        </div>
                                        <div class="form-group" id="nama">
                                            <label for="default" class="control-label">Nama</label>
                                            <input id="fullname" name="fullname" type="text" class="form-control" readonly="readonly"> 
                                        </div>
                                        <div class="form-group" id="jabatan">
                                            <label for="default" class="control-label">Jabatan</label>
                                            <input id="thru_position" type="text" class="form-control" readonly="readonly"> 
                                        </div>
                                        <div class="form-group">
                                            <label for="default" class="control-label">Cabang/Unit</label>
                                            <input id="thru_branch" type="text" class="form-control" readonly="readonly"> 
                                        </div>
                                        <div class="form-group">
                                            <label for="default" class="control-label">Tanggal</label>
                                            <input type="text" class="form-control date-picker" placeholder="" name="from_date" style="width: 35%; margin-right: 0px; position: absolute;" placeholder="From Date">
                                            <input type="text" class="form-control date-picker" placeholder="" name="thru_date" style="width: 40%; margin-left: 220px;" placeholder="Thru Date">
                                        </div>
                                        <!--<div class="form-group">
                                            <label for="single" class="control-label">Aprove By</label>
                                                    <select class="form-control select2" name="aprove">
                                                        <option></option>
                                                        <?php foreach($get_karyawan_by_branch as $values){?>
                                                            <option value="<?php echo $values->nik;?>"><?php echo $values->nik;?> - <?php echo $values->fullname;?></option>
                                                        <?php }?>
                                                    </select>
                                        </div>-->
                                        <div class="form-group">
                                            <label for="default" class="control-label">Keterangan</label>
                                            <textarea class="form-control" name="keterangan"></textarea>
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
                                        <div class="modal fade" id="detail" tabindex="-1" role="basic" aria-hidden="true">
                                            <div class="modal-dialog" style="width: 700px;">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Pembatalan Cuti/Ijin</h4>
                                                    </div>
                                                    <div class="modal-body"> 
                                        <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                                            <thead>
                                                <tr>
                                                        <th style="width: 200px;"> Nama </th>
                                                        <th> Tanggal DnL </th>
                                                        <th style="width: 250px;"> Keterangan </th>
                                                        <th> Actions </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <?php foreach($get_dnl as $values){?>                                                    
                                                <tr class="odd gradeX">
                                                    <td><?php echo $values->fullname;?></td>
                                                    <td><?php echo $values->tgl_dnl;?></td>
                                                    <td><?php echo $values->keterangan;?></td>
                                                    <td><a href="<?php echo site_url()?>karyawan/action_batal_dnl/<?php echo $values->dnl_id;?>/<?php echo $values->nik;?>/<?php echo $values->tgl_dnl?>" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-search"></i> Batalkan</a></td>
                                                </tr>
                                                <?php }?>

                                            </tbody>
                                        </table>
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
                    $(".date-picker").datepicker({rtl:App.isRTL(),autoclose:!0});
                </script>
<script>
   jQuery(document).ready(function() {    
      App.init(); // initlayout and core plugins
        
      $('#nik').change(function(){
          var nik = $("#nik").val();
          
        $.ajax({
          type: "POST",
          dataType: "json",
          data: {nik:nik},
          url: '<?php echo site_url('mutasi/get_jabatan_by_nik'); ?>',
          success: function(response){
              var fullname = response.fullname;
              var thru_position = response.position;
              var thru_branch = response.branch;

              $("#nik_").val(nik);
              $("#fullname").val(fullname);
              $("#thru_position").val(thru_position);
              $("#thru_branch").val(thru_branch);
              $("#show").attr('data-id', nik);
          }
        });
      });  


        $('#detail').on('show.bs.modal', function (e) {
            var rowid = $(e.relatedTarget).data('id');

            $.ajax({
                  type: "POST",
                  dataType: "json",
                  data: {nik:rowid},
                  url: '<?php echo site_url('mutasi/get_karyawan_by_nik'); ?>',
                success : function(response){
                    $("#niks").val(response.nik);
                    $("#karyawan").val(response.fullname);

                }
            });
         });
        
      $('#branch_').change(function(){
          var branch = $("#branch_").val();

        $.ajax({
          type: "POST",
          dataType: "json",
          data: {branch:branch},
          url: '<?php echo site_url('resign/get_karyawan_by_branch'); ?>',
          success: function(response){
            html = '<option>PILIH</option>';
            for ( i = 0 ; i < response.length ; i++ )
            {
               html += '<option value="'+response[i].nik+'">'+response[i].nik+' - '+response[i].fullname+'</option>';
            }
            $("#nik", "#list").html(html).focus();
            $("#nik option:first-child", "#list").attr('selected',true);        
          }
        });
      });  

   });
</script>