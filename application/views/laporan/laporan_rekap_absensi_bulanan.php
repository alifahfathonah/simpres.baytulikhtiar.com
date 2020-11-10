<style>
  td {
    vertical-align: middle;
    text-align: center;
  }

  th {
    vertical-align: middle;
    text-align: center;
  }
</style>
<div class="page-content" style="min-height: 860px;">
  <div class="theme-panel hidden-xs hidden-sm">
  </div>
  <div class="page-bar">
    <ul class="page-breadcrumb">
      <li>
        <a href="<?=site_url('dashboard');?>">Home</a>
        <i class="fa fa-circle"></i>
      </li>
      <li>
        <span>Laporan</span>
      </li>
    </ul>
  </div>
  <h1 class="page-title"> Laporan
    <small>Rekap Absensi Bulanan</small>
  </h1>
  <div class="row">
    <div class="col-md-12">
      <div class="portlet light bordered">
        <div class="portlet-title">


          <form action="javascript:;">
            <div class="form-group col-md-4">
              <label for="single-prepend-text" class="control-label">Kantor</label>
              <div class="input-group select2-bootstrap-prepend">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                    <span class="glyphicon glyphicon-search"></span>
                  </button>
                </span>
                <select class="form-control select2" name="branch_code" id="branch_code">
                  <option></option>
                  <?php
                  if($this->session->userdata('logged_in')['role_id'] == 0){
                    echo '<option value="semua">SEMUA CABANG</option>';
                  }
                  ?>
                  <?php foreach($get_branch as $values){?>
                    <option value="<?php echo $values->parameter_id;?>"><?php echo $values->description;?></option>
                  <?php }?>
                </select>
              </div>
            </div>
            <div class="form-group col-md-4">
              <label for="single-prepend-text" class="control-label">Periode Cutoff</label>
              <div class="input-group select2-bootstrap-prepend">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                    <span class="glyphicon glyphicon-search"></span>
                  </button>
                </span>
                <select class="form-control select2" name="periode" id="periode">
                  <option></option>
                  <?php foreach($get_periode_from_absensi_manual as $values){?>
                    <option value="<?php echo $values->periode_from_date;?> - <?php echo $values->periode_thru_date;?>"><?php echo $values->periode_from_date;?> s/d <?php echo $values->periode_thru_date;?></option>
                  <?php }?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <button id="preview" class="btn blue-sharp btn-outline sbold uppercase" style="margin-top: 24px">Preview</button>
              <!--button id="pdf" class="btn red-mint btn-outline sbold uppercase" style="margin-top: 24px">PDF</button-->
              <button id="excel" class="btn green-jungle btn-outline sbold uppercase" style="margin-top: 24px">Excel</button>
            </div>
          </form>

        </div>      

        <div class="portlet-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover datatables">
              <thead>
                <tr>
                  <th rowspan="4">No</th>
                  <th rowspan="4">Nama</th>
                  <th rowspan="4">Posisi</th>
                  <th rowspan="4">Cabang</th>
                  <th rowspan="2" colspan="11" style="vertical-align: middle;">Perhitungan Kehadiran</th>
                  <th colspan="14">Presensi</th>
                </tr>
                <tr>
                  <th colspan="5">Kedatangan</th>
                  <th colspan="5">Kepulangan</th>
                  <th colspan="4">Lembur</th>
                </tr>
                <tr>
                  <th rowspan="2">Jumlah</th>
                  <th rowspan="2">L</th>
                  <th rowspan="2">H</th>
                  <th rowspan="2">TLK</th>
                  <th rowspan="2">C</th>
                  <th rowspan="2">CK</th>
                  <th rowspan="2">DnL</th>
                  <th rowspan="2">SD</th>
                  <th rowspan="2">I</th>
                  <th rowspan="2">LTG</th>
                  <th rowspan="2">HK</th>
                  <th>Tepat Waktu</th>
                  <th>Kurang 15mnt</th>
                  <th>s/d 30mnt</th>
                  <th>Lbh dr 30mnt</th>
                  <th>Total Datang</th>

                  <th>Tepat Waktu</th>
                  <th>Kurang 15mnt</th>
                  <th>s/d 30mnt</th>
                  <th>Lbh dr 30mnt</th>
                  <th>Total Pulang</th>

                  <th rowspan="2">Total</th>
                </tr>
                <tr>
                  <th>s/d 08.00</th>
                  <th>08.01 s/d 08.15</th>
                  <th>08.16 s/d 08.30</th>
                  <th>08.31 s/d up</th>
                  <th>Hr</th>
                  
                  <th>17 s/d up</th>
                  <th>16.59 s/d 16.45</th>
                  <th>16.44 s/d 16.31</th>
                  <th>sblm 16.30</th>
                  <th>Hr</th>
                </tr>
              </thead>
              <tbody id="v_p">
              </tbody>
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
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>

<script src="<?php echo base_url();?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>

<script type="text/javascript">
 jQuery(document).ready(function(){
  App.init();
  $(function(){

    $('#preview').on('click', function(){
      var branch_code = $('#branch_code').val();
      var periode     = $('#periode').val();
      if(branch_code == ""){
        generateToast('Warning', 'Silahkan pilih kantor / cabang', 'warning');
        $('#branch_code').focus();
      }else if(periode == ""){
        generateToast('Warning', 'Silahkan pilih periode cutoff', 'warning');
        $('#periode').focus();
      }else{

        $.ajax({
          type: "get",
          data: { branch_code:branch_code, periode:periode },
          url: '<?=site_url('get_rekap_bulanan');?>',
          beforeSend: function(){
          	$('.datatables').DataTable().clear().destroy();
            $.blockUI({ message: '<i class="fa fa-spinner fa-spin"></i> Silahkan Tunggu...' });
            $('#v_p').html(`
              <tr>
                <td colspan="24" class="text-center">-</td>
              </tr>
            `);
          },
          statusCode  : {
            400: function() {
              $.unblockUI();
              generateToast('Info', 'Karyawan Tidak Ditemukan.', 'info');
            },
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
          var result = $.parseJSON(result);
          console.log(result);
          $('#v_p').html('');
          $.each(result, function(i,k){
            console.log(k);

            $('#v_p').append(`
              <tr>
                <td>`+k.no+`</td>
                <td>`+k.fullname+`</td>
                <td>`+k.position+`</td>
                <td>`+k.branch+`</td>
                <td>`+k.jumlah+`</td>
                <td>`+k.get_count_libur+`</</td>
                <td>`+k.get_count_hadir+`</td>
                <td>`+k.get_count_tlk+`</td>
                <td>`+k.get_count_cuti+`</td>
                <td>`+k.get_count_ck+`</td>
                <td>`+k.get_count_dnl+`</td>
                <td>`+k.get_count_sakit+`</td>
                <td>`+k.get_count_ijin+`</td>
                <td>`+k.ltg+`</td>
                <td>`+k.hk+`</td>
                <td>`+k.m_tepat_waktu+`</td>
                <td>`+k.m_telat_1+`</td>
                <td>`+k.m_telat_2+`</td>
                <td>`+k.m_telat_3+`</td>
                <td>`+k.sum_masuk+`</td>
                <td>`+k.k_tepat_waktu+`</td>
                <td>`+k.k_telat_1+`</td>
                <td>`+k.k_telat_2+`</td>
                <td>`+k.k_telat_3+`</td>
                <td>`+k.sum_keluar+`</td>
                <td>`+k.total_jam+`</td>
              </tr>
            `);
          });

          $('.datatables').DataTable({
          	destroy: true,
          	dom: 'Bfrtip',
    				pageLength: 5
          });

          $.unblockUI();
        });
          
      }

      
    });

    $('#excel').click(function(){
      var branch_code = $('#branch_code').val();
      var periode = $('#periode').val();
      var site = '<?php echo site_url('laporan/action_laporan_rekap_absen_excel2'); ?>';
      var conf = true;

      if(branch_code == ''){
        alert('Kantor belum diisi');
        var conf = false;
      }

      if(periode == ''){
        alert('Periode cutoff belum diisi');
        var conf = false;
      }

      if(conf == true){
        window.open(site+'/'+branch_code+'/'+periode);
      }
    });

    $('#pdf').click(function(){
      var branch_code = $('#branch_code').val();
      var periode = $('#periode').val();
      var site = '<?php echo site_url('laporan/action_laporan_rekap_absen_pdf'); ?>';
      var conf = true;

      if(branch_code == ''){
        alert('Kantor belum diisi');
        var conf = false;
      }

      if(periode == ''){
        alert('Periode cutoff belum diisi');
        var conf = false;
      }

      if(conf == true){
        window.open(site+'/'+branch_code+'/'+periode);
      }
    });

  });
})
</script>