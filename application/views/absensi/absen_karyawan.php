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
  <h1 class="page-title"> Absen Karyawan
    <small>Detail Perkaryawan</small>
  </h1>
  <div class="row">
    <div class="col-md-12">
      <div class="portlet light bordered">
        <div class="portlet-title">
          <form action="<?php echo site_url();?>absensi/get_absen_by_nik" role="form" method="post">
            <div class="form-group col-md-6">
              <label for="single-prepend-text" class="control-label">Karyawan</label>
              <div class="input-group select2-bootstrap-prepend">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button" data-select2-open="single-prepend-text">
                    <span class="glyphicon glyphicon-search"></span>
                  </button>
                </span>
                <select id="single-prepend-text" class="form-control select2" name="nik" required="true">
                  <option></option>
                  <?php foreach($get_karyawan->result() as $values){?>
                    <option value="<?php echo $values->nik;?>"><?php echo $values->nik;?> - <?php echo $values->fullname;?></option>
                  <?php }?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <button type="submit" class="btn red-thunderbird sbold uppercase" style="margin-top: 24px">Show</button>
              <button type="button" class="btn purple-seance sbold uppercase" style="margin-top: 24px" id="importAll">Import All FP</button>
            </div>
          </form>
        </div>
        <div class="portlet-body">
          <div class="progress progress-striped active">
            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="bar" style="width: 0%">
              <span class="sr-only"> 0% Complete </span>
            </div>
          </div>
        </div>
        <div class="portlet-body hide">
          <div class="table-scrollable">
            <table class="table">
              <tbody>
                <tr>
                  <th> Periode Cutoff </th>
                  <td></td>
                </tr>
                <tr>
                  <th> NIK </th>
                  <td></td>
                </tr>
                <tr>
                  <th> Nama </th>
                  <td></td>
                </tr>
                <tr>
                  <th rowspan="2" style="text-align: center;">Tanggal</th>
                  <th colspan="2" style="text-align: center;">Waktu</th>
                  <th rowspan="2" style="text-align: center;">Keterangan</th>
                </tr>
                <tr>
                  <th style="text-align: center;">Masuk</th>
                  <th style="text-align: center;">Keluar</th>
                </tr>
                <tr>
                  <td style="text-align: center;"><input type="text" name=""></td>
                  <td style="text-align: center;"><input type="text" name=""></td>
                  <td style="text-align: center;"><input type="text" name=""></td>
                  <td style="text-align: center;"><input type="text" name=""></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

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
<script src="<?php echo base_url('vendor/toast/jquery.toast.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/sweetalert/sweetalert.min.js');?>" type="text/javascript"></script>

<script>
  $(document).ready(function(){

    $('#importAll').bind('click', function(e){
      e.preventDefault();
      $.ajax({
        url: '<?=site_url('absensi/hitung_array_import_fp');?>',
        type: 'get',
        dataType: 'json',
        beforeSend: function(){
          $('#importAll').block({'message':'<i class="fa fa-spinner fa-spin"></i>'});
        },
        statusCode: {
        	404: function(){
            $('#importAll').unblock();
            generateToast('Error 404', 'Page Not Found', 'error');
            console.log('Error 404');
          },
          500: function(){
            $('#importAll').unblock();
            generateToast('Error 500', 'Internal Database Server Error', 'error');
            console.log('Error 500');
          },
          503: function(){
            $('#importAll').unblock();
            generateToast('Error 503', 'Unstable Connection', 'error');
            console.log('Error 503');
          }
        }
      })
      .done(function(result){
        console.log(result);
        
        if(result.data.length == 0){
          generateToast('Info', 'Tidak ada data dari mesin finger yang sudah di upload', 'info');
          setTimeout(function(){
            $('#importAll').unblock();
          }, 500);
        }else{
					let total_data   = parseInt(result.data.length); // 1654
					let per_eksekusi = 100;
					let hasil_bagi   = Math.ceil(total_data / 100); // 17
					let kenaikan     = Math.ceil(per_eksekusi / hasil_bagi); // 4
					let x            = 0;
					let awal         = 0;
					let awal_x       = 101;
					let akhir        = 100;

          for (let i = 1; i <= hasil_bagi; i++) {
            let arr = result.data.slice(awal, akhir);
            insertData(arr);

            awal += 100;
            akhir += 100;
            setTimeout( function timer(){
              x += kenaikan;
              console.log(x);
              
              $('#bar').css('width', x+'%');

              if(x >= 100) {
                //generateToast('Success', 'Proses Import Berhasil...', 'success');
                swal("Proses Import Finger Print Berhasil...", { 
						      icon: "success"
						    });
                $('#importAll').unblock();
                setTimeout(function(){
                  //window.location.reload();
                }, 15000);
              };
            }, i * 2000);
          }

        }

      });

    });
  });

  function generateToast(heading, message, color){
    $.toast({
      text: message,
      heading: heading,
      icon: color,
      showHideTransition: 'slide',
      allowToastClose: true,
      hideAfter: 5000,
      stack: 5,
      position: 'bottom-right',
      textAlign: 'left',
      loader: true,
      loaderBg: '#9EC600',    
    });
  }

  function insertData(arr)
  {
    $.ajax({
      url: '<?=site_url('absensi/import_absen');?>',
      type: 'post',
      data: {
        'data': arr
      },
      statusCode: {
      	200: function(){
          //$('#importAll').unblock();
          console.log('Success 200');
          return true;
        },
      	404: function(){
          $('#importAll').unblock();
          generateToast('Error 404', 'Page Not Found', 'error');
          console.log('Error 404');
          return false;
        },
      	500: function(){
          $('#importAll').unblock();
          generateToast('Error 500', 'Internal Server Database Error', 'error');
          console.log('Error 500');
          return false;
        },
        503: function(){
          $('#importAll').unblock();
          generateToast('Error 503', 'Database execute more than 30 seconds', 'error');
          console.log('Error 503');
          return false;
        }
      }
    })
    .done(function(result){
      //console.log(result);
    });
  }
</script>