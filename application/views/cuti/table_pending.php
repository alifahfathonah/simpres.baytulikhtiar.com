<div class="page-content" style="min-height: 860px;">
  <h1 class="page-title"> List Cuti & Izin Karyawan 
    <small> Cabang <?=$nama_cabang;?> - Pending</small>
  </h1>

  <div class="row">
    <div class="col-md-12">
      <div class="portlet light bordered">

        <div class="portlet-title">
          <div class="caption font-dark">
            <i class="fa fa-table font-dark"></i>
            <span class="caption-subject bold uppercase">List Cuti & Izin Karyawan</span>
          </div>
        </div>

        <div class="portlet-body">
          <div class="table-toolbar">
            <div class="table-responsive">
              <table class="table table-bordered table-hover" style="width:1500px;">
                <thead>
                  <tr>
                    <th class="text-center" width="500px"><i class="fa fa-cog"></i></th>
                    <th width="500px">NIK</th>
                    <th width="500px">Nama</th>
                    <th width="500px">Cabang</th>
                    <th width="500px">TGL Cuti</th>
                    <th width="500px">Kategori Cuti</th>
                    <th width="500px">Keterangan</th>
                    <th width="500px">Created By</th>
                    <th width="500px">Created Date</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                foreach ($get_karyawan->result() as $key) {
                  $alfa_id              = $key->alfa_id;
                  $nik                  = $key->nik;
                  $fullname             = $key->fullname;
                  $tgl_cuti             = $key->tgl_cuti;
                  $kc                   = $key->kc;
                  $group                = $key->group;
                  $kategori_cuti        = $key->kategori_cuti;
                  $keterangan           = $key->keterangan;
                  $approve_by           = $key->approve_by;
                  $created_by           = $key->created_by;
                  $created_date         = $key->created_date;
                  $nama_cabang_karyawan = $key->nama_cabang_karyawan;
                ?>
                  <tr>
                    <td class="text-center">
                      <div class="btn-group">
                        <button onClick="approve('<?=$alfa_id;?>', '<?=$nik;?>', '<?=$fullname;?>', '<?=$nama_cabang_karyawan;?>', '<?=$group;?>', '<?=$kc;?>', '<?=$tgl_cuti;?>', '<?=$keterangan;?>')" type="button" class="btn btn-success btn-sm">
                          <i class="fa fa-check"></i> Terima
                        </button>
                        <button onClick="reject('<?=$alfa_id;?>', '<?=$nik;?>', '<?=$fullname;?>', '<?=$nama_cabang_karyawan;?>')" type="button" class="btn btn-danger btn-sm">
                          <i class="fa fa-check"></i> Tolak
                        </button>
                      </div>
                    </td>
                    <td><?=$nik;?></td>
                    <td><?=$fullname;?></td>
                    <td><?=$nama_cabang_karyawan;?></td>
                    <td><?=date('d-m-Y', strtotime($tgl_cuti));?></td>
                    <td><?=$kategori_cuti;?></td>
                    <td><?=$keterangan;?></td>
                    <td><?=$created_by;?></td>
                    <td><?=date('d-m-Y H:i:s', strtotime($created_date));?></td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
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
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>

<script src="<?php echo base_url('vendor/datatables/jquery.dataTables.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/datatables/dataTables.bootstrap4.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/datatables/dataTables.buttons.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/datatables/buttons.html5.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/datatables/jszip.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/datatables/pdfmake.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/datatables/vfs_fonts.js');?>" type="text/javascript"></script>

<script src="<?php echo base_url('vendor/sweetalert/sweetalert.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/jquery.validate.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/additional-methods.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/localization/messages_id.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/blockui/jquery.blockUI.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/toast/jquery.toast.min.js');?>" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
  var datatables = $('table').DataTable({
    dom: 'ftrip',
    responsive:true,
    order: [[1,'desc']],
    columnDefs: [
      {
        targets: [0],
        orderable: false,
        searchable: false
      }
    ]
  });
});

////////////////////////////////////////////////////////


function approve(alfa_id, nik, fullname, nama_cabang, group, kc, tgl_cuti, keterangan)
{
  swal({
    title: "Apa kamu yakin?",
    text: "Kamu akan menerima cuti '"+fullname+"' ('"+nik+"') Cabang '"+nama_cabang+"' Untuk tanggal Cuti '"+tgl_cuti+"'",
    icon: "warning",
    buttons: true,
    dangerMode: false,
  })
  .then((willDelete) => {
    if (willDelete) {
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
          swal(result.description, { 
            icon: "success"
          });
          setTimeout(function(){
            console.log("PROCESS APPROVE CUTI DONE");
            $.unblockUI();
            window.location.replace('<?=site_url('list_cuti_pending');?>');
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

function reject(alfa_id, nik, fullname, nama_cabang)
{
  console.log(nik, fullname);
  swal({
    title: "Apa kamu yakin?",
    text: "Kamu akan menolak cuti '"+fullname+"' ('"+nik+"') Cabang '"+nama_cabang+"'",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url         : '<?=site_url('tolak_cuti');?>',
        method      : 'POST',
        data        : { alfa_id:alfa_id },
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
          swal(result.description, { 
            icon: "success"
          });
          setTimeout(function(){
            console.log("PROCESS REJECT CUTI DONE");
            $.unblockUI();
            window.location.replace('<?=site_url('list_cuti_pending');?>');
          }, 2000);

        }else if(result.code == 500){
          generateToast('Warning', result.description, 'warning');
          $.unblockUI();
        }
      }); 

    } 
  });

}
</script>