<!DOCTYPE html>
<html lang="en">

<?php $this->load->view('wraper/header');?>
<script src="<?php echo base_url();?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
  <div class="page-wrapper">
    <div class="page-header navbar navbar-fixed-top">
      <div class="page-header-inner ">
        <div class="page-logo">
          <a href="index.html" class="">
            <img src="<?=base_url('assets/pages/img/logo_sm.png');?>" alt="logo" class="logo-default" width="50" style="margin: -12px 0 0 0 !important;" />
          </a>
          <div class="menu-toggler sidebar-toggler" style="margin: 17px 5px 0 0 !important;">
            <span></span>
          </div>
        </div>
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
          <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <?php
        if($this->session->userdata('logged_in')['role_id'] == 0){
          $link_cutoff = site_url('setup/setup_cutoff');
        }else{
          $link_cutoff = site_url('dashboard');
        }
        ?>
        <div class="top-menu">
          <div  class="tooltips btn btn-sm" style="color: white; padding-top: 15px;">
            <i class="icon-calendar"></i>&nbsp;
            <a href="<?=$link_cutoff;?>" style="text-decoration: none; color: white; " title="Periode Cut Off Aktif" data-toggle="tooltip" data-placement="bottom">
              <?php foreach($periode_cutoff as $values){
                echo date('d-m-Y', strtotime($values->from_date)).' <small class="text-muted">s/d</small> '.date('d-m-Y', strtotime($values->thru_date));
              }?>
            </a>                            
          </div>
          <ul class="nav navbar-nav pull-right">
            <li class="dropdown dropdown-user">
              <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                <!--<img alt="" class="img-circle" src="<?php echo base_url();?>assets/layouts/layout/img/avatar3_small.jpg" />-->
                <span class="username username-hide-on-mobile"><?php echo $user;?></span>
                <i class="fa fa-angle-down"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-default">
                <li>
                  <a href="<?php echo site_url();?>user">
                    <i class="icon-key"></i> Change Password
                  </a>
                </li>
                <!--li>
                  <a href="<?=site_url('repair_detik');?>" class="font-purple-seance">
                    <i class="fa fa-bolt font-purple-seance"></i> Repair Detik Absensi
                  </a>
                </li-->
              </ul>
            </li>
            <li class="dropdown dropdown-quick-sidebar-toggler">
              <a href="<?php echo site_url();?>logout" class="dropdown-toggle">
                <i class="icon-logout"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="clearfix"> </div>
    <div class="page-container">
      <div class="page-sidebar-wrapper">
        <?php $this->load->view('wraper/sidebar');?>
      </div>
      <div class="page-content-wrapper">

        <?php $this->load->view($container);?>

      </div>


      <a href="javascript:;" class="page-quick-sidebar-toggler">
        <i class="icon-login"></i>
      </a>
    </div>
    <div class="page-footer">
      <div class="page-footer-inner"> 2019 ~ 2020 © KSPPS Baytul Ikhtiar - HR Management Application V.2.0.1</div>
      <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
      </div>
    </div>
  </div>
  <div class="quick-nav-overlay"></div>

</body>

</html>

<!-- 
DEVELOPER NOTES VERSION

# VERSION 2.0.0
- Perbaikan dari versi sebelumnya yang di buat aiman

# Version 2.0.1 (2020-02-03 ~ 2020-02-XX)
- Perbaikan fitur Mutasi Status Karyawan
- Perbaikan fitur Mutasi Jabatan Karyawan
- Perbaikan fitur Mutasi Cabang Karyawan 
-->

<script src="<?php echo base_url('vendor/toast/jquery.toast.min.js');?>" type="text/javascript"></script>
<script type="text/javascript" src="<?=site_url('vendor/datatables/datatables.min.js');?>"></script>
<script>
////////////////////////////////////////////////////////////////////////////////// RED LINE
$('[data-toggle="tooltip"]').tooltip();

function mtlink()
{
  generateToast("Oops...", "This Page Under Construction!", "info");
}

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
</script>