<div class="page-sidebar navbar-collapse collapse">
  <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
    <li class="sidebar-toggler-wrapper hide">
        <div class="sidebar-toggler">
            <span></span>
        </div>
    </li>
    <li class="nav-item start <?=parent_menu('dashboard/index');?>">
      <a href="<?php echo site_url();?>dashboard" class="nav-link nav-toggle">
        <i class="icon-home"></i>
        <span class="title">Dashboard</span>
        <span class="selected"></span>
      </a>
    </li>
    <?php
    if($this->session->userdata('logged_in')['role_id'] == "0"){
    ?>
    <li class="heading active">
      <h3 class="uppercase">Setup</h3>
    </li>
    <!--li class="nav-item <?=parent_menu('setup/setup_cabang');?>">
      <a href="<?php echo site_url('list_cabang');?>" class="nav-link nav-toggle">
        <i class="icon-grid"></i>
        <span class="title">Kantor Cabang</span>
      </a>
    </li-->
    <li class="nav-item <?php echo parent_menu('setup/setup_parameter');?>">
      <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-wallet"></i>
        <span class="title">Parameter</span>
        <span class="arrow"></span>
      </a>
      <ul class="sub-menu">
        <li class="nav-item  ">
          <a href="#" class="nav-link" onClick="mtlink();">
            <i class="icon-users"></i>
            <span class="title">Parameter Tunjangan</span>
          </a>
        </li>
        <li class="nav-item  <?=child_menu('setup_parameter');?>">
          <a href="<?php echo site_url();?>setup/setup_parameter" class="nav-link ">
            <i class="icon-ban"></i>
            <span class="title">Parameter Lainnya</span>
          </a>
        </li>                                    
      </ul>
    </li>
    <li class="nav-item <?php echo parent_menu('setup/setup_user');?>">
      <a href="<?php echo site_url();?>setup/setup_user" class="nav-link nav-toggle">
        <i class="icon-user"></i>
        <span class="title">User</span>
      </a>
    </li>
    <!--li class="nav-item <?php echo parent_menu('setup/setup_cutoff');?>">
      <a href="<?php echo site_url();?>setup/setup_cutoff" class="nav-link nav-toggle">
        <i class="icon-hourglass"></i>
        <span class="title">Periode Cutoff</span>
      </a>
    </li-->
    <li class="nav-item <?php echo parent_menu('cutoff/index');?>">
      <a href="<?php echo site_url();?>cutoff/index" class="nav-link nav-toggle">
        <i class="icon-hourglass"></i>
        <span class="title">Setup Periode Cutoff</span>
      </a>
    </li>
    <li class="nav-item <?php echo parent_menu('setup/setup_hari_libur');?>">
      <a href="<?php echo site_url();?>setup/setup_hari_libur" class="nav-link nav-toggle">
        <i class="icon-hourglass"></i>
        <span class="title">Hari Libur</span>
      </a>
    </li>
    <li class="heading">
      <h3 class="uppercase">Karyawan</h3>
    </li>
    <li class="nav-item <?php echo parent_menu('karyawan/index');?>  ">
      <a href="<?php echo site_url();?>karyawan" class="nav-link nav-toggle">
        <i class="icon-users"></i>
        <span class="title">Data Karyawan</span>
      </a>
    </li>
    <li class="nav-item  <?php echo parent_menu('absensi/index');?>">
      <a href="<?php echo site_url();?>absensi" class="nav-link nav-toggle">
        <i class="icon-graph"></i>
        <span class="title">Absen Karyawan</span>
      </a>
    </li>

    <li class="nav-item  <?php echo parent_menu('CutiController');?>">
      <a href="<?=site_url('list_cuti_cabang');?>" class="nav-link nav-toggle">
        <i class="fa fa-book"></i>
        <span class="title">Form Ketidakhadiran</span>
      </a>
    </li>

    <li class="nav-item  <?php echo parent_menu('LemburController');?>">
      <a href="<?=site_url('lembur');?>" class="nav-link nav-toggle">
        <i class="fa fa-book"></i>
        <span class="title">Form Lembur</span>
      </a>
    </li>

    <!--li class="nav-item <?php echo parent_menu('karyawan/regis_tlk');?>  ">
      <a href="<?php echo site_url();?>karyawan/regis_tlk" class="nav-link nav-toggle">
        <i class="icon-directions"></i>
        <span class="title">Regis Tugas Luar Kantor</span>
      </a>
    </li>
    <li class="nav-item <?php echo parent_menu('karyawan/regis_dnl');?>  ">
      <a href="<?php echo site_url();?>karyawan/regis_dnl" class="nav-link nav-toggle">
        <i class="icon-directions"></i>
        <span class="title">Regis Dinas Luar Kantor</span>
      </a>
    </li-->

    <!--hr style="border-top: 1px solid #606C7D; margin-top: 5px; margin-bottom: 5px"-->
    <li class="heading">
      <h3 class="uppercase">Mutasi & Resign</h3>
    </li>
    <li class="nav-item <?php echo parent_menu('mutasi/mutasi_status');?>  ">
      <a href="<?php echo site_url();?>mutasi/mutasi_status" class="nav-link nav-toggle">
        <i class="icon-directions"></i>
        <span class="title">Mutasi Status</span>
      </a>
    </li>
    <li class="nav-item <?php echo parent_menu('mutasi/mutasi_jabatan');?>  ">
      <a href="<?php echo site_url();?>mutasi/mutasi_jabatan" class="nav-link nav-toggle">
        <i class="icon-directions"></i>
        <span class="title">Mutasi Jabatan</span>
      </a>
    </li>
    <li class="nav-item <?php echo parent_menu('mutasi/mutasi_cabang');?>  ">
      <a href="<?php echo site_url();?>mutasi/mutasi_cabang" class="nav-link nav-toggle">
        <i class="icon-directions"></i>
        <span class="title">Mutasi Cabang</span>
      </a>
    </li>
    <li class="nav-item <?php echo parent_menu('resign/index');?>">
      <a href="<?php echo site_url();?>resign" class="nav-link nav-toggle">
        <i class="icon-user-unfollow"></i>
        <span class="title">Resign Karyawan</span>
      </a>
    </li>
    <li class="heading">
      <h3 class="uppercase">Laporan</h3>
    </li>
    <li class="nav-item <?php echo parent_menu('laporan/laporan_rekap_absensi_bulanan');?> <?php echo parent_menu('laporan/laporan_absensi_karyawan');?>">
      <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-docs"></i>
        <span class="title">Absensi</span>
        <span class="arrow"></span>
      </a>
      <ul class="sub-menu">
        <li class="nav-item  <?php echo child_menu('laporan_rekap_absensi_bulanan');?>">
          <a href="<?php echo site_url();?>laporan/laporan_rekap_absensi_bulanan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Rekap Absen Bulanan</span>
          </a>
        </li>
        <li class="nav-item  <?php echo child_menu('laporan_absensi_karyawan');?>">
          <a href="<?php echo site_url();?>laporan/laporan_absensi_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Form Verifikasi</span>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item  <?php echo parent_menu('laporan/laporan_list_karyawan');?>">
      <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-docs"></i>
        <span class="title">Karyawan</span>
        <span class="arrow"></span>
      </a>
      <ul class="sub-menu">
        <li class="nav-item  <?php echo parent_menu('laporan_list_karyawan');?>">
          <a href="<?php echo site_url();?>laporan/laporan_list_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">List Karyawan</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_rinci_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Profile Rinci Karyawan</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_cuti_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Cuti Karyawan</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_tempo_kontrak_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Jatuh Tempo Kontrak</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_karyawan_resign" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Laporan Karyawan Resign</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_hak_cuti" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Laporan Hak Cuti/Ijin Karyawan</span>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item  ">
      <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-docs"></i>
        <span class="title">Mutasi</span>
        <span class="arrow"></span>
      </a>
      <ul class="sub-menu">
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_mutasi_status" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Mutasi Status</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_mutasi_jabatan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Mutasi Jabatan</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_mutasi_cabang" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Mutasi Cabang</span>
          </a>
        </li>
      </ul>
    </li>
    <?php } ?>
    <?php
    if($this->session->userdata('logged_in')['role_id'] == "1"){
    ?>
    <li class="heading">
      <h3 class="uppercase <?=($this->uri->segment(1) == 'karyawan' || $this->uri->segment(1) == 'absensi' || $this->uri->segment(1) == 'list_cuti_cabang' || $this->uri->segment(1) == 'lembur')? 'font-yellow-gold bold' : '' ?>">Karyawan</h3>
    </li>
    <li class="nav-item <?php echo parent_menu('karyawan/index');?>  ">
      <a href="<?php echo site_url();?>karyawan" class="nav-link nav-toggle">
        <i class="icon-users"></i>
        <span class="title">Data Karyawan</span>
      </a>
    </li>
    <li class="nav-item  <?php echo parent_menu('absensi/index');?>">
      <a href="<?php echo site_url();?>absensi" class="nav-link nav-toggle">
        <i class="icon-graph"></i>
        <span class="title">Absen Karyawan</span>
      </a>
    </li>
    <li class="nav-item  <?php echo parent_menu('CutiController');?>">
      <a href="<?=site_url('list_cuti_cabang');?>" class="nav-link nav-toggle">
        <i class="fa fa-book"></i>
        <span class="title">Form Ketidakhadiran</span>
      </a>
    </li>    
    <li class="nav-item  <?php echo parent_menu('LemburController');?>">
      <a href="<?=site_url('lembur');?>" class="nav-link nav-toggle">
        <i class="fa fa-book"></i>
        <span class="title">Form Lembur</span>
      </a>
    </li>
    
    <hr style="border-top: 1px solid #606C7D; margin-top: 5px; margin-bottom: 5px">
    <li class="heading">
      <h3 class="uppercase <?=($this->uri->segment(1) == 'laporan')? 'font-yellow-gold bold' : '' ?>">Laporan</h3>
    </li>
    <li class="nav-item <?=(
    $this->uri->segment(1) == 'laporan' && ($this->uri->segment(2) == 'laporan_rekap_absensi_bulanan' || $this->uri->segment(2) == 'laporan_absensi_karyawan'))? 'active' : '' ?>">
      <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-docs"></i>
        <span class="title">Absensi</span>
        <span class="arrow"></span>
      </a>
      <ul class="sub-menu">
        <li class="nav-item  <?php echo child_menu('laporan_rekap_absensi_bulanan');?>">
          <a href="<?php echo site_url();?>laporan/laporan_rekap_absensi_bulanan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Rekap Absen Bulanan</span>
          </a>
        </li>
        <li class="nav-item  <?php echo child_menu('laporan_absensi_karyawan');?>">
          <a href="<?php echo site_url();?>laporan/laporan_absensi_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">History Absen Karyawan</span>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item  <?php echo parent_menu('laporan/laporan_list_karyawan');?>">
      <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-docs"></i>
        <span class="title">Karyawan</span>
        <span class="arrow"></span>
      </a>
      <ul class="sub-menu">
        <li class="nav-item  <?php echo parent_menu('laporan_list_karyawan');?>">
          <a href="<?php echo site_url();?>laporan/laporan_list_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">List Karyawan</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_rinci_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Profile Rinci Karyawan</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_cuti_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Cuti Karyawan</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_tempo_kontrak_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Jatuh Tempo Kontrak</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_karyawan_resign" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Laporan Karyawan Resign</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_hak_cuti" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Laporan Hak Cuti/Ijin Karyawan</span>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item  ">
      <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-docs"></i>
        <span class="title">Mutasi</span>
        <span class="arrow"></span>
      </a>
      <ul class="sub-menu">
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_mutasi_status" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Mutasi Status</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_mutasi_jabatan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Mutasi Jabatan</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_mutasi_cabang" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Mutasi Cabang</span>
          </a>
        </li>
      </ul>
    </li>
    <?php } ?>
    <?php
    if($this->session->userdata('logged_in')['role_id'] == "2"){
    ?>
    <li class="heading">
      <h3 class="uppercase">Laporan</h3>
    </li>
    <li class="nav-item <?php echo parent_menu('laporan/laporan_rekap_absensi_bulanan');?> <?php echo parent_menu('laporan/laporan_absensi_karyawan');?>">
      <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-docs"></i>
        <span class="title">Absensi</span>
        <span class="arrow"></span>
      </a>
      <ul class="sub-menu">
        <li class="nav-item  <?php echo child_menu('laporan_rekap_absensi_bulanan');?>">
          <a href="<?php echo site_url();?>laporan/laporan_rekap_absensi_bulanan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Rekap Absen Bulanan</span>
          </a>
        </li>
        <li class="nav-item  <?php echo child_menu('laporan_absensi_karyawan');?>">
          <a href="<?php echo site_url();?>laporan/laporan_absensi_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">History Absen Karyawan</span>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item  <?php echo parent_menu('laporan/laporan_list_karyawan');?>">
      <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-docs"></i>
        <span class="title">Karyawan</span>
        <span class="arrow"></span>
      </a>
      <ul class="sub-menu">
        <li class="nav-item  <?php echo parent_menu('laporan_list_karyawan');?>">
          <a href="<?php echo site_url();?>laporan/laporan_list_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">List Karyawan</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_rinci_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Profile Rinci Karyawan</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_cuti_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Cuti Karyawan</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_tempo_kontrak_karyawan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Jatuh Tempo Kontrak</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_karyawan_resign" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Laporan Karyawan Resign</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_hak_cuti" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Laporan Hak Cuti/Ijin Karyawan</span>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item  ">
      <a href="javascript:;" class="nav-link nav-toggle">
        <i class="icon-docs"></i>
        <span class="title">Mutasi</span>
        <span class="arrow"></span>
      </a>
      <ul class="sub-menu">
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_mutasi_status" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Mutasi Status</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_mutasi_jabatan" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Mutasi Jabatan</span>
          </a>
        </li>
        <li class="nav-item  ">
          <a href="<?php echo site_url();?>laporan/laporan_mutasi_cabang" class="nav-link ">
            <i class="icon-docs"></i>
            <span class="title">Mutasi Cabang</span>
          </a>
        </li>
      </ul>
    </li>
    <?php } ?>


  </ul>
</div>