<div class="page-content">
  <div class="page-bar">
    <ul class="page-breadcrumb">
      <li>
        <a href="#">Home</a>
        <i class="fa fa-circle"></i>
      </li>
      <li>
        <a href="#">Setup</a>
        <i class="fa fa-circle"></i>
      </li>
      <li>
        <span>Setup Cutoff</span>
      </li>
    </ul>
  </div>
  <h1 class="page-title"> Setup</h1>
  <div class="row">
    <div class="col-md-7">
      <div class="portlet box purple ">
        <div class="portlet-title">
          <div class="caption">
            <i class="fa fa-gift"></i> Cutoff
          </div>
        </div>
        <div class="portlet-body">
          <h5 class="block">Periode yang sedang berjalan : 
            <?php foreach($periode_cutoff as $values){
              echo date('d-m-Y', strtotime($values->from_date)).' s/d '.date('d-m-Y', strtotime($values->thru_date));
              echo '<input type="hidden" id="from_date_active" value="'.$from_date.'">';
              echo '<input type="hidden" id="thru_date_active" value="'.$thru_date.'">';
            }
            ?>
          </h5>
          <h4 class="block">Periode Cutoff</h4>
          <form id="form">
            <div class="row">
              <div class="col-md-12">
                <div class="errornya"></div>
                <div class="input-group">
                  <input type="text" id="from_date" name="from_date" class="form-control datepicker" placeholder="<?=EX_DATE;?>">
                  <div class="input-group-addon">s/d</div>
                  <input type="text" id="thru_date" name="thru_date" class="form-control datepicker" placeholder="<?=EX_DATE;?>">
                </div>
              </div>
              <div class="col-md-9" style="padding-top: 20px;">
                <button id="submit" type="submit" class="btn green">OK</button>
                <button type="reset" class="btn default">Reset</button>
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
<script src="<?php echo base_url();?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/jquery-repeater/jquery.repeater.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/pages/scripts/form-repeater.min.js" type="text/javascript"></script>

<script src="<?php echo base_url('vendor/sweetalert/sweetalert2@8.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/jquery.validate.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/additional-methods.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/localization/messages_id.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/blockui/jquery.blockUI.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/toast/jquery.toast.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/datepicker/js/bootstrap-datepicker.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/datepicker/locales/bootstrap-datepicker.id.min.js');?>" type="text/javascript"></script>
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js" type="text/javascript"></script-->



<script>
$(document).ready(function(){
  const from_date_active = $('#from_date_active').val();
  const thru_date_active = $('#thru_date_active').val();
  $(".datepicker").datepicker({
    placeholder: 'dd-mm-YYYY',
    todayHighlight: true,
    autoclose: true,
    language: 'id',
    format: 'dd-mm-yyyy',
    clearBtn: false,
    startDate: thru_date_active,
    todayBtn: true,
    //daysOfWeekDisabled: [0,6], // 0 = ejeung poe minggu, 6 for ejeung poe saptu ~~adampm
  });

  // FORM VALIDATE
  $('#form').validate({
    debug: true,
    errorClass: 'help-inline text-danger',
    rules:{
      from_date:{
        required:true
      },
      thru_date:{
        required:true
      }
    },
    submitHandler: function( form ) {

      const from_date = $('#from_date').val();
      const thru_date = $('#thru_date').val();

      swal.fire({
        type: "question",
        title: "Konfirmasi Periode Cutoff",
        html: "Setup periode cutoff dari tanggal <br><b>"+from_date+"</b><br> sampai dengan<br><b>"+thru_date+"</b>",
        focusConfirm: false,
        showConfirmButton: true,
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: '<i class="fa fa-thumbs-up"></i> Ya',
        customClass: {
          confirmButton: 'btn blue-madison'
        },
        cancelButtonText: '<i class="fa fa-times"></i> Close'
      })
      .then((result) => {
        if (result.value) {
          $.ajax({
            url         : '<?=site_url('cutoff/store');?>',
            method      : 'GET',
            data        : { from_date:$('#from_date').val(), thru_date:$('#thru_date').val() },
            beforeSend  : function(){
              $.blockUI({ message: '<i class="fa fa-spinner fa-spin"></i> Silahkan Tunggu...' });
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
              //generateToast('Success', result.description, 'success');
              swal.fire('Setup Cutoff ', result.description, 'success');
              setTimeout(function(){
                console.log("PROCESS SETUP CUTOFF DONE");
                $.unblockUI();
                window.location.replace('<?=site_url('dashboard');?>');
              }, 2000);

            }else if(result.code == 500){
              generateToast('Warning', result.description, 'warning');
              $.unblockUI();
            }

            $.unblockUI();
          }); 

        } 
      });

    }
  });
  
});

function formatDate (input) {
  var datePart = input.match(/\d+/g),
  year = datePart[0], // get only two digits
  month = datePart[1], day = datePart[2];

  return day+'-'+month+'-'+year;
}
</script>