<div class="page-content" style="min-height: 860px;">
  <h1 class="page-title"> List Cuti & Izin Karyawan 
    <small> Cabang <?=$nama_cabang;?> - Terima</small>
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
            <div class="table table-responsive">
              <table class="table table-bordered table-hover" style="width:1500px;">
                <thead>
                  <tr>
                    <th class="text-center" width="100">Status</th>
                    <th>NIK</th>
                    <th>NAMA</th>
                    <th>TGL CUTI</th>
                    <th>KATEGORI CUTI</th>
                    <th>KETERANGAN</th>
                    <th>APPROVE BY</th>
                    <th>CREATED BY</th>
                    <th>CREATED DATE</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                foreach ($get_karyawan->result() as $key) {
                  $alfa_id       = $key->alfa_id;
                  $nik           = $key->nik;
                  $fullname      = $key->fullname;
                  $tgl_cuti      = $key->tgl_cuti;
                  $kategori_cuti = $key->kategori_cuti;
                  $keterangan    = $key->keterangan;
                  $approve_by    = $key->approve_by;
                  $created_by    = $key->created_by;
                  $created_date  = $key->created_date;
                  $status        = '<span class="label bg-grey-mint bg-font-grey-mint">Pending</span>';

                  if($approve_by){
                    $status = '<span class="label bg-green-jungle bg-font-green-jungle">Di Terima</span>';

                  }
                ?>
                  <tr>
                    <td class="text-center"><?=$status;?></td>
                    <td><?=$nik;?></td>
                    <td><?=$fullname;?></td>
                    <td><?=date('d-m-Y', strtotime($tgl_cuti));?></td>
                    <td><?=$kategori_cuti;?></td>
                    <td><?=$keterangan;?></td>
                    <td><?=$approve_by;?></td>
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
    order: [[0,'asc']]
  });
});

////////////////////////////////////////////////////////


function destroy(nik, fullname)
{

}
</script>