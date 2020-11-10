<div class="row">
  <div class="col-md-12">
    <div class="portlet light bordered">

      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="fa fa-table font-dark"></i>
          <span class="caption-subject bold uppercase"><?=$nama_cabang;?> - <small>Periode <?=$awal;?> ~ <?=$akhir;?></small></span>
        </div>
      </div>

      <div class="portlet-body">
        <div class="table-toolbar">
          <div class="table table-responsive">
            <table class="table table-bordered table-hover table-condensed table-sm">
              <thead>
                <tr>
                  <th class="text-center" width="200"><i class="fa fa-cogs"></i></th>
                  <th>#</th>
                  <th>NIK</th>
                  <th>Nama</th>
                  <th>Cabang</th>
                  <th>Tgl Tidak Hadir</th>
                  <th>Hari</th>
                  <th>Kategori</th>
                  <th>Keterangan</th>
                  <th>Created By</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach ($get_karyawan->result() as $key) {
                  $alfa_id              = $key->alfa_id;
                  $nik                  = $key->nik;
                  $fullname             = $key->fullname;
                  $tgl_cuti             = $key->tgl_cuti;
                  $tgl_cuti2            = $key->tgl_cuti2;
                  $hari                 = $key->hari;
                  $kc                   = $key->kc;
                  $kategori_cuti        = $key->kategori_cuti;
                  $group                = $key->group;
                  $keterangan           = $key->keterangan;
                  $approve_by           = $key->approve_by;
                  $created_by           = $key->created_by;
                  $created_date         = $key->created_date;
                  $nama_cabang_karyawan = $key->nama_cabang_karyawan;
                  $hak_cuti             = $key->hak_cuti;
                  $hak_ijin             = $key->hak_ijin;
                  ?>
                  <tr>
                    <td class="text-center">
                      <div class="btn-group">
                        <button  onClick="reject('<?=$alfa_id;?>', '<?=$nik;?>', '<?=$fullname;?>', '<?=$nama_cabang_karyawan;?>', '<?=$group;?>', '<?=$tgl_cuti;?>', '<?=$tgl_cuti2;?>' , '<?=$hari;?>')" type="button" class="btn btn-danger btn-sm">
                          <i class="fa fa-check"></i> Batalkan
                        </button>
                      </div>
                    </td>
                    <td><?=$no;?></td>
                    <td><?=$nik;?></td>
                    <td><?=$fullname;?></td>
                    <td><?=$nama_cabang_karyawan;?></td>
                    <td>
                      <?=date('d-m-Y', strtotime($tgl_cuti));?> s/d <?=date('d-m-Y', strtotime($tgl_cuti2));?>
                    </td>
                    <td><?=$hari;?></td>
                    <td><?=$kategori_cuti;?></td>
                    <td><?=$keterangan;?></td>
                    <td><?=$created_by;?></td>
                  </tr>
                  <?php 
                  $no++;
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>