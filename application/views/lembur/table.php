<div class="page-content" style="min-height: 1000px;">
  <h1 class="page-title"> List Lembur
    <div class="pull-right">
      <a href="<?=site_url('lembur_create');?>" class="btn btn-lg green-turquoise"><i class="fa fa-plus"></i> Form Pengajuan Lembur</a>
    </div>
  </h1>

  <div class="row">
    <div class="col-md-12">
      <div class="portlet light bordered">

        <div class="portlet-body">
          <form class="form-horizontal" role="form" id="form">

            <div class="form-group">
              <legend>
                <i class="fa fa-filter"></i> Filter Pencarian Lembur Karyawan
              </legend>
            </div>

            <div class="form-group">
              <label class="col-sm-1 col-md-1 control-label"><strong>Cabang</strong></label>
              <div class="col-sm-3 col-md-3">
                <select id="id_cabang" name="id_cabang" class="form-control">
                  <?php
                  if($this->session->userdata('logged_in')['role_id'] == 0){
                    echo '<option value="semua">Semua Cabang</option>';
                  }

                  foreach ($get_branch as $key) {
                    echo '<option value="'.$key->parameter_id.'">'.$key->description.'</option>';
                  }
                  ?>
                </select>
              </div>

              <div class="col-sm-4 col-md-4">
                <button type="submit" id="show_data" class="btn btn-outline blue-ebonyclay"><i class="fa fa-filter"></i> Show Data</button>
              </div>

            </div>

          </form>
        </div>

      </div>
    </div>
  </div>

  <div id="v_data"></div>

</div>


<script src="<?php echo base_url();?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>

<script src="<?php echo base_url('vendor/sweetalert/sweetalert2@8.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/jquery.validate.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/additional-methods.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/localization/messages_id.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/blockui/jquery.blockUI.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/toast/jquery.toast.min.js');?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
  // FORM VALIDATE
  $('#form').validate({
    debug: true,
    errorClass: 'help-inline text-danger',
    rules:{
      id_cabang:{
        required:true
      },
      status:{
        required:true
      }
    },
    submitHandler: function( form ) {
      $.ajax({
        url         : '<?=site_url('lembur_data');?>',
        method      : 'GET',
        data        : $('#form').serialize(),
        beforeSend  : function(){
          $.blockUI({ message: '<i class="fa fa-spinner fa-spin"></i> Silahkan Tunggu...' });
           $('table').DataTable().clear();
           $('table').DataTable().destroy();
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
        //console.log(result);
        $('#v_data').html(result);

        $('table').dataTable({
          dom: 'ftrip',
          order: [[1,'desc']],
          columnDefs: [{
            targets: [0],
            searchable: false,
            orderable: false
          }]
        });

        $.unblockUI();
      });
    }
  });    

});

////////////////////////////////////////////////////////

////////////////////////////////////////////////////////


function approve(alfa_id, nik, fullname, nama_cabang, group, kc, tgl_cuti, keterangan, hak_cuti, hak_ijin)
{
  swal.fire({
    type: "question",
    title: "Konfirmasi Persetujuan Cuti",
    html: "Terima pengajuan cuti <br> <b>"+fullname+" <br> "+nik+" <br> Cabang "+nama_cabang+" <br></b> Untuk tanggal Cuti <br><b>"+formatDate(tgl_cuti)+"</b><br>Sisa Ijin: <b>"+hak_ijin+"</b><br>Sisa Cuti: <b>"+hak_cuti+"</b> ",
    focusConfirm: false,
    showConfirmButton: true,
    showCancelButton: true,
    allowOutsideClick: false,
    confirmButtonText: '<i class="fa fa-thumbs-up"></i> Terima',
    customClass: {
      confirmButton: 'btn blue-madison'
    },
    cancelButtonText: '<i class="fa fa-times"></i> Close'
  })
  .then((result) => {
    if (result.value) {
      $.ajax({
        url         : '<?=site_url('terima_cuti');?>',
        method      : 'POST',
        data        : { alfa_id:alfa_id, nik:nik, group:group, kc:kc, tgl_cuti:tgl_cuti, keterangan:keterangan },
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
          swal.fire('Lembur Berhasil Di Terima', result.description, 'success');
          setTimeout(function(){
            console.log("PROCESS APPROVE CUTI DONE");
            $.unblockUI();
            window.location.replace('<?=site_url('list_cuti_cabang');?>');
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

function reject(id, nik, fullname, tgl, jam_a, jam_b)
{
  swal.fire({
    type: "question",
    title: "Konfirmasi Pembatalan Lembur",
    html: "Batalkan pengajuan Lembur <br> <b>"+fullname+"</b> <br> <b>"+nik+"</b> <br> Untuk tanggal <br><b>"+tgl+"</b> ",
    showConfirmButton: true,
    showCancelButton: true,
    allowOutsideClick: false,
    confirmButtonText: '<i class="fa fa-thumbs-down"></i> Batalkan',
    customClass: {
      confirmButton: 'btn red-thunderbird'
    },
    cancelButtonText: '<i class="fa fa-times"></i> Close'
  })
  .then((result) => {
    if (result.value) {
      $.ajax({
        url         : '<?=site_url('lembur_destroy');?>',
        method      : 'POST',
        data        : { id:id },
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
          swal.fire('Lembur Berhasil Di Batalkan', result.description, 'success');
          setTimeout(function(){
            console.log("PROCESS REJECT CUTI DONE");
            $.unblockUI();
            window.location.replace('<?=site_url('lembur');?>');
          }, 2000);

        }else if(result.code == 500){
          generateToast('Warning', result.description, 'warning');
          $.unblockUI();
        }
      }); 

    } 
  });

}

function formatDate (input) {
  var datePart = input.match(/\d+/g),
  year = datePart[0], // get only two digits
  month = datePart[1], day = datePart[2];

  return day+'-'+month+'-'+year;
}
</script>