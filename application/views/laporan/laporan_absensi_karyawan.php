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
    <small>Form Verifikasi</small>
  </h1>
  <div class="row">
    <div class="col-md-12">
      <div class="portlet light bordered">
        <div class="portlet-title">


          <form action="javascript:;">
            <div class="form-group col-md-4">
              <label for="single-prepend-text" class="control-label">Cabang/Units</label>
              <div class="input-group select2-bootstrap-prepend">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                    <span class="glyphicon glyphicon-search"></span>
                  </button>
                </span>
                <select class="form-control select2" name="branch" id="branch" <?php if($branch_user != '1'){echo "disable='disable' values='".$branch_user."'";}?>>
                  <option></option>
                  <?php foreach($get_branch as $values){?>
                    <option value="<?php echo $values->parameter_id;?>"><?php echo $values->description;?></option>
                  <?php }?>
                </select>
              </div>
            </div>
            <div class="form-group col-md-4">
              <label for="single-prepend-text" class="control-label">Karyawan</label>
              <div class="input-group select2-bootstrap-prepend" id="karyawan">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                    <span class="glyphicon glyphicon-search"></span>
                  </button>
                </span>
                <select class="form-control select2" name="nik" id="nik">
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
            <div class="form-group col-md-4">
              <!--button id="pdf" class="btn red-mint btn-outline sbold uppercase" style="margin-top: 24px">PDF</button-->
              <button id="excel" class="btn green-jungle btn-outline sbold uppercase" style="margin-top: 24px">Excel</button>
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
<script src="<?php echo base_url();?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>

<script src="<?php echo base_url();?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>

<script type="text/javascript">
 jQuery(document).ready(function(){
  App.init();
  $(function(){

    $('#excel').click(function(){
      var branch = $('#branch').val();
      var nik = $('#nik').val();
      var periode = $('#periode').val();
      var site = '<?php echo site_url('laporan/action_laporan_absen_excel'); ?>';
      var conf = true;

      if(branch == ''){
        alert('Cabang/Unit belum di isi');
        $('#branch').focus();
        conf = false;
      }else if(nik == ''){
        alert('Kantor belum di isi');
        $('#nik').focus();
        conf = false;
      }else if(periode == ''){
        alert('Periode cutoff belum di isi');
        $('#periode').focus();
        conf = false;
      }else{
      	conf = true;
      }

      if(conf == true){
        window.open(site+'/'+branch+'/'+nik+'/'+periode);
      }
    });

    $('#pdf').click(function(){
      var branch = $('#branch').val();
      var nik = $('#nik').val();
      var periode = $('#periode').val();
      var site = '<?php echo site_url('laporan/action_laporan_absen_pdf'); ?>';
      var conf = true;

      if(nik == ''){
        alert('Kantor belum diisi');
        var conf = false;
      }

      if(periode == ''){
        alert('Periode cutoff belum diisi');
        var conf = false;
      }

      if(conf == true){
        window.open(site+'/'+branch+'/'+nik+'/'+periode);
      }
    });



    $("#keyword","#dialog_cm").keypress(function(e){
      keyword = $(this).val();
      branch_id = $("input[name='branch_id']").val();
      fa_code=$("input[name='fa_code']").val();
      if(e.keyCode==13){
        e.preventDefault();
        $.ajax({
         type: "POST",
         url: site_url+"cif/search_majelis_by_petugas",
         dataType: "json",
         async: false,
         data: {keyword:keyword,branch_id:branch_id,fa_code:fa_code},
         success: function(response){
          html = '<option value="00000" cm_name="SEMUA MAJELIS">00000 - SEMUA MAJELIS</option>';
          for ( i = 0 ; i < response.length ; i++ )
          {
           html += '<option value="'+response[i].nik+'">'+response[i].nik+' - '+response[i].fullname+'</option>';
         }
         $("#nik").html(html).focus();
         $("#nik option:first-child").attr('selected',true);
       }
     });
      }
    });


    $('#branch').change(function(){
      var branch = $("#branch").val();

      $.ajax({
        type: "POST",
        dataType: "json",
        data: {branch:branch},
        url: '<?php echo site_url('laporan/get_karyawan_by_branch'); ?>',
        success: function(response){
          html = '<option value="99999">SEMUA</option>';
          for ( i = 0 ; i < response.length ; i++ )
          {
           html += '<option value="'+response[i].nik+'">'+response[i].nik+' - '+response[i].fullname+'</option>';
         }
         $("#nik", "#karyawan").html(html).focus();
         $("#nik option:first-child", "#karyawan").attr('selected',true);        
       }
     });
    });  

  });
})
</script>

