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
        <span>Absen Karyawan</span>
      </li>
    </ul>
  </div>
  <h1 class="page-title"> Rekap Absen Karyawan
    <small>Perbulan</small>
  </h1>
  <div class="row">
    <div class="col-md-12">
      <div class="note note-danger hide">
        <p> NOTE: The below datatable is not connected to a real database so the filter and sorting is just simulated for demo purposes only. </p>
      </div>
      <!-- Begin: life time stats -->
      <div class="portlet light portlet-fit portlet-datatable bordered" id="wrapper-table">
        <div class="portlet-title">
          <div class="caption">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase">Tabel Absensi</span>
          </div>
        </div>
        <div class="portlet-body">
          <div class="table-container">
            <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
              <thead>
                <tr role="row" class="heading">
                  <th width="2%">
                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                      <input type="checkbox" class="group-checkable" data-set="#sample_2 .checkboxes" />
                      <span></span>
                    </label>
                  </th>
                  <th width="5%"> NIK</th>
                  <th width="25%"> Nama </th>
                  <th width="20"> Tanggal </th>
                  <th width="20%"> Masuk </th>
                  <th width="20%"> Keluar </th>
                </tr>
                                                    <!--<tr role="row" class="filter">
                                                        <td> </td>
                                                        <td>
                                                            <input type="text" class="form-control form-filter input-sm" name="order_id"> </td>
                                                        <td>
                                                            <input type="text" class="form-control form-filter input-sm" name="order_customer_name"> </td>
                                                        <td>
                                                            <select name="order_status" class="form-control form-filter input-sm">
                                                                <option value="">Select...</option>
                                                                <option value="pending">Pending</option>
                                                                <option value="closed">Closed</option>
                                                                <option value="hold">On Hold</option>
                                                                <option value="fraud">Fraud</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="order_status" class="form-control form-filter input-sm">
                                                                <option value="">Select...</option>
                                                                <option value="pending">Pending</option>
                                                                <option value="closed">Closed</option>
                                                                <option value="hold">On Hold</option>
                                                                <option value="fraud">Fraud</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="order_status" class="form-control form-filter input-sm">
                                                                <option value="">Select...</option>
                                                                <option value="pending">Pending</option>
                                                                <option value="closed">Closed</option>
                                                                <option value="hold">On Hold</option>
                                                                <option value="fraud">Fraud</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div class="margin-bottom-5">
                                                                <button class="btn btn-sm green btn-outline filter-submit margin-bottom">
                                                                    <i class="fa fa-search"></i> Search</button>
                                                            </div>
                                                            <button class="btn btn-sm red btn-outline filter-cancel">
                                                                <i class="fa fa-times"></i> Reset</button>
                                                        </td>
                                                      </tr>-->
                                                    </thead>
                                                    <tbody> </tbody>
                                                  </table>
                                                </div>
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



                                      <script type="text/javascript">
                                        var TableDatatablesButtons = function(){   
                                          n = function(){
                                            $(".date-picker").datepicker({rtl:App.isRTL(),autoclose:!0});
                                            $("#add").hide();
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
                                                pageLength:10,ajax:{url:"<?php echo site_url('absensi/get_rekap_absen');?>"},
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




