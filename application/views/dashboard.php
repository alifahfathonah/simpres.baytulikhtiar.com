<div class="page-wrapper">
  <div class="page-content" style="min-height: 860px;">
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li>
          <a href="index.html">Home</a>
          <i class="fa fa-circle"></i>
        </li>
        <li>
          <span>Dashboards</span>
        </li>
      </ul>
    </div>
    <h1 class="page-title">Dashboard</h1>
    <div class="row">
      <div class="col-md-12">
        <?php
        if( $this->session->flashdata('cutoff') == "ok" ){
          ?>
          <div class="alert alert-success fadeIn">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Berhasil!</strong> Update Cutoff&hellip;
          </div>
          <?php 
        } 
        ?>

        <?php
        if( $this->session->flashdata('repair') == "success" ){
          ?>
          <div class="alert alert-success fadeIn">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Berhasil!</strong> Repair Detik Absensi&hellip;
          </div>
          <?php 
        } elseif( $this->session->flashdata('repair') == "failed" ){
          ?>
          <div class="alert alert-danger fadeIn">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Gagal!</strong> Repair Detik Absensi&hellip;
          </div>
          <?php 
        }
        ?>
      </div>
    </div>
    
    <!-- CARD KARYAWAN -->
    <div class="row">

      <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" id="block_card_a">
        <a id="triggerModalA" class="dashboard-stat dashboard-stat-v2 blue-chambray" href="#">
          <div class="visual">
            <i class="fa fa-users"></i>
          </div>
          <div class="details">
            <div class="number">
              <span id="card_a">0</span>
            </div>
            <div class="desc">
              <p>Total Karyawan</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" id="block_card_b">
        <a id="triggerModalB" class="dashboard-stat dashboard-stat-v2 red-flamingo" href="#">
          <div class="visual">
            <i class="fa fa-user-times"></i>
          </div>
          <div class="details">
            <div class="number">
              <span id="card_b">0</span>
            </div>
            <div class="desc">
              <p>Data Absen Belum Terisi</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" id="block_card_c">
      	<a id="triggerModalC" class="dashboard-stat dashboard-stat-v2 green-jungle" href="#">
          <div class="visual">
            <i class="fa fa-pie-chart"></i>
          </div>
          <div class="details">
            <div class="number">
              <span id="card_c"><?=number_format(0, 2);?></span>%
            </div>
            <div class="desc">
              <p>Persentase Kehadiran</p>
            </div>
          </div>
        </a>
      </div>

    </div>
    <!-- END CARD KARYAWAN -->
    
    <?php
    //if($this->session->userdata('logged_in')['role_id'] == 0){
    ?>
    <div class="row">
      <div class="col-md-12">
        <div class="divider"><hr></div>
        <div class="portlet light">
          <div class="portlet-title">
            <div class="caption">
              <i class="fa fa-users font-dark"></i>
              <span class="caption-subject font-hide bold uppercase">Expired Kontrak <small><?=count($expired_kontrak);?> Data</small></span>
            </div>
          </div>
          <div class="portlet-body">
            <div class="table-responsive">
              <table class="table bg-danger" id="table_expired">
                <thead>
                  <tr>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Cabang</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $x = 0;
                  foreach ($expired_kontrak as $key) {
                    ?>
                    <tr>
                      <td><?=$key->nik;?></td>
                      <td><?=$key->fullname;?></td>
                      <td><?=$key->cabang;?></td>
                      <td><?=$key->status;?></td>
                      <td><?=$key->from_date;?> <small>s/d</small> <?=$key->thru_date;?></td>
                    </tr>
                    <?php 
                    $x++;
                  }
                  ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
        <div class="divider"><hr></div>
      </div>
    </div>
    <?php //} ?>

  </div>
</div>


<div class="modal fade" id="modalA">
</div>

<div class="modal fade" id="modalB">
</div>

<div class="modal fade" id="modalC">
</div>




<?php $this->load->view('wraper/footer');?>

<script src="<?php echo base_url('vendor/sweetalert/sweetalert.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/jquery.validate.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/additional-methods.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/jqueryvalidation/localization/messages_id.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/blockui/jquery.blockUI.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/toast/jquery.toast.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('vendor/waypoint/waypoints.min.js');?>"></script>
<script src="<?=base_url('assets/jquery-counterup/jquery.counterup.min.js');?>"></script>

<script>
  $(document).ready(function(){
    cardA();
    cardB();
    cardC();

    $('#triggerModalA').on('click', function(){
      modalA();
    });

    $('#triggerModalB').on('click', function(){
      modalB();
    });

    $('#triggerModalC').on('click', function(){
      modalC();
    });

    $('#table_expired').DataTable({
      dom: 'ftrip',
      order: [[2,'asc']],
      buttons:[
      {
       extend: 'pdf',
       text: 'Export PDF',
       className: 'btn btn-danger' 
     },
     {
       extend: 'excel',
       text: 'Export EXCEL',
       className: 'btn btn-success' 
     },
     ]
   });

  });

  function cardA()
  {
    $.ajax({
      url         : '<?=site_url('card_a');?>',
      beforeSend  : function(){
        $('#block_card_a').block({ message: '<i class="fa fa-spinner fa-spin"></i>' });
      },
      statusCode  : {
        404: function() {
          $.unblockUI();
          generateToast('Warning', 'Page Not Found.', 'error');
        },
        500: function() {
          $.unblockUI();
          generateToast('Warning', 'Not connect with database.', 'error');
        }
      }
    })
    .done(function(result){
      result = $.parseJSON(result);
      //console.log(result);
      $('#card_a').text(result);
      $('#card_a').counterUp({
        delay: 10
      });
      $('#block_card_a').unblock();
    });
  }

  function cardB()
  {
    $.ajax({
      url         : '<?=site_url('card_b');?>',
      beforeSend  : function(){
        $('#block_card_b').block({ message: '<i class="fa fa-spinner fa-spin"></i>' });
      },
      statusCode  : {
        404: function() {
          $.unblockUI();
          generateToast('Warning', 'Page Not Found.', 'error');
        },
        500: function() {
          $.unblockUI();
          generateToast('Warning', 'Not connect with database.', 'error');
        }
      }
    })
    .done(function(result){
      //console.log(result);
      result = $.parseJSON(result);
      $('#card_b').text(result);
      $('#card_b').counterUp({
        delay: 10
      });
      $('#block_card_b').unblock();
    });
  }

  function cardC()
  {
    $.ajax({
      url         : '<?=site_url('card_c');?>',
      beforeSend  : function(){
        $('#block_card_c').block({ message: '<i class="fa fa-spinner fa-spin"></i>' });
      },
      statusCode  : {
        404: function() {
          $.unblockUI();
          generateToast('Warning', 'Page Not Found.', 'error');
        },
        500: function() {
          $.unblockUI();
          generateToast('Warning', 'Not connect with database.', 'error');
        }
      }
    })
    .done(function(result){
      result = $.parseJSON(result);
      //console.log(result);
      $('#card_c').text(result);
      $('#card_c').counterUp({
        delay: 10
      });
      $('#block_card_c').unblock();
    });
  }

  function modalA()
  {
    $.ajax({
      url         : '<?=site_url('card_a_modal');?>',
      beforeSend  : function(){
        $('#modalA').html('');
        $.blockUI({ message: '<i class="fa fa-spinner fa-spin"></i>' });
      },
      statusCode  : {
        404: function() {
          $.unblockUI();
          generateToast('Warning', 'Page Not Found.', 'error');
        },
        500: function() {
          $.unblockUI();
          generateToast('Warning', 'Not connect with database.', 'error');
        },
        503: function() {
          $.unblockUI();
          generateToast('Warning', 'Unstable Connection.', 'error');
        }
      }
    })
    .done(function(result){
      //console.log(result);
      $('#modalA').html(result);
      $.unblockUI();
    });

    $('#modalA').modal('show');
  }

  function modalB()
  {
    $.ajax({
      url         : '<?=site_url('card_b_modal');?>',
      beforeSend  : function(){
        $('#modalB').html('');
        $.blockUI({ message: '  <i class="fa fa-spinner fa-spin"></i>' });
      },
      statusCode  : {
        404: function() {
          $.unblockUI();
          generateToast('Warning', 'Page Not Found.', 'error');
        },
        500: function() {
          $.unblockUI();
          generateToast('Warning', 'Not connect with database.', 'error');
        }
      }
    })
    .done(function(result){
      //console.log(result);
      $('#modalB').html(result);
      $.unblockUI();
      var datatables = $('#table').DataTable({
        dom: 'ftrip',
        order: [[2,'asc']],
        buttons:[
        {
         extend: 'pdf',
         text: 'Export PDF',
         className: 'btn btn-danger' 
       },
       {
         extend: 'excel',
         text: 'Export EXCEL',
         className: 'btn btn-success' 
       },
       ]
     });
    });

    $('#modalB').modal('show');
  }

  function modalC()
  {
    $.ajax({
      url         : '<?=site_url('card_c_modal');?>',
      beforeSend  : function(){
      	$('#modalC').html('');
        $.blockUI({ message: '  <i class="fa fa-spinner fa-spin"></i>' });
      },
      statusCode  : {
        404: function() {
          $.unblockUI();
          generateToast('Warning', 'Page Not Found.', 'error');
        },
        500: function() {
          $.unblockUI();
          generateToast('Warning', 'Not connect with database.', 'error');
        },
        503: function() {
          $.unblockUI();
          generateToast('Warning', 'Unstable Connection.', 'error');
        }
      }
    })
    .done(function(result){
      //console.log(result);
      $('#modalC').html(result);
      $.unblockUI();
      var datatables = $('#table').DataTable({
        dom: 'ftrip',
        order: [[2,'asc']],
        buttons:[
        {
         extend: 'pdf',
         text: 'Export PDF',
         className: 'btn btn-danger' 
       },
       {
         extend: 'excel',
         text: 'Export EXCEL',
         className: 'btn btn-success' 
       },
       ]
     });
    });

    $('#modalC').modal('show');
  }
</script>