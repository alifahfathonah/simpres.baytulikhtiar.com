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
        <span>Laporan</span>
      </li>
    </ul>
  </div>
  <h1 class="page-title"> Laporan
    <small>Cuti Karyawan</small>
  </h1>
  <div class="row">
    <div class="col-md-12">
      <div class="portlet light bordered">
        <div class="portlet-title">


          <form action="javascript:;">
            <div class="form-group col-md-4">
              <label for="single-prepend-text" class="control-label">Cabang/Unit</label>
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
            <div class="form-group col-md-3">
              <label for="single-prepend-text" class="control-label">Tanggal</label>
              <div class="input-group select2-bootstrap-prepend">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                    <span class="glyphicon glyphicon-search"></span>
                  </button>
                </span>
                <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" placeholder="" id="from_date" name="from_date">
              </div>
            </div>
            <div class="form-group col-md-2" style="margin-top: 30px; margin-right: -120px;">
              s/d
            </div>
            <div class="form-group col-md-3">
              <label for="single-prepend-text" class="control-label">  </label>
              <div class="input-group select2-bootstrap-prepend" style="margin-top: 6px;">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                    <span class="glyphicon glyphicon-search"></span>
                  </button>
                </span>
                <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" placeholder="" id="thru_date" name="thru_date">
              </div>
            </div>
            <div class="form-group col-md-4">
              <button id="pdf" class="btn red-mint btn-outline sbold uppercase" style="margin-top: 24px">PDF</button>
              <button id="excel" class="btn red-mint btn-outline sbold uppercase" style="margin-top: 24px">Excel</button>
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
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>

<script type="text/javascript">
  $(".date-picker").datepicker({rtl:App.isRTL(),autoclose:!0});
</script>

<script type="text/javascript">
 jQuery(document).ready(function(){
  App.init();
  $(function(){

    $('#excel').click(function(){
      var branch = $('#branch').val();
      var get_from_date = $('#from_date').val();
      var from_date2 = get_from_date.substr(0, 2);
      var from_date3 = get_from_date.substr(3, 2);
      var from_date4 = get_from_date.substr(6, 4);
      var from_date = from_date4+'-'+from_date2+'-'+from_date3;

      var get_thru_date = $('#thru_date').val();
      var thru_date2 = get_thru_date.substr(0, 2);
      var thru_date3 = get_thru_date.substr(3, 2);
      var thru_date4 = get_thru_date.substr(6, 4);
      var thru_date = thru_date4+'-'+thru_date2+'-'+thru_date3;

      var site = '<?php echo site_url('laporan/action_laporan_cuti_karyawan_excel'); ?>';
      var conf = true;

      if(branch == ''){
        alert('Kantor belum diisi');
        var conf = false;
      }

      if(from_date == ''){
        alert('Tanggal belum diisi');
        var conf = false;
      }

      if(thru_date == ''){
        alert('Tanggal belum diisi');
        var conf = false;
      }

      if(conf == true){
        window.open(site+'/'+branch+'/'+from_date+'/'+thru_date);
      }
    });

    $('#pdf').click(function(){
      var branch = $('#branch').val();
      var get_from_date = $('#from_date').val();
      var from_date2 = get_from_date.substr(0, 2);
      var from_date3 = get_from_date.substr(3, 2);
      var from_date4 = get_from_date.substr(6, 4);
      var from_date = from_date4+'-'+from_date3+'-'+from_date2;

      var get_thru_date = $('#thru_date').val();
      var thru_date2 = get_thru_date.substr(0, 2);
      var thru_date3 = get_thru_date.substr(3, 2);
      var thru_date4 = get_thru_date.substr(6, 4);
      var thru_date = thru_date4+'-'+thru_date3+'-'+thru_date2;
      var site = '<?php echo site_url('laporan/action_laporan_cuti_karyawan_pdf'); ?>';
      var conf = true;

      if(branch == ''){
        alert('Kantor belum diisi');
        var conf = false;
      }

      if(from_date == ''){
        alert('Tanggal belum diisi');
        var conf = false;
      }

      if(thru_date == ''){
        alert('Tanggal belum diisi');
        var conf = false;
      }

      if(conf == true){
        window.open(site+'/'+branch+'/'+from_date+'/'+thru_date);
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
        url: '<?php echo site_url('laporan/get_karyawan_resign_detail_by_branch'); ?>',
        success: function(response){
          html = '<option value="00000">SEMUA</option>';
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

