<div class="row">
  <div class="col-md-12">
    <div class="portlet light bordered">

      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="fa fa-table font-dark"></i>
          <span class="caption-subject bold uppercase">Nama Cabang</span>
        </div>
      </div>

      <div class="portlet-body">
        <div class="table-toolbar">
          <div class="table table-responsive">
            <table class="table table-bordered table-hover table-condensed" style="width:1500px;">
              <thead>
                <tr>
                  <th class="text-center" width="200"><i class="fa fa-cogs"></i></th>
                  <th>#</th>
                  <th>NIK</th>
                  <th>Nama</th>
                  <th>Cabang</th>
                  <th>Tgl Lembur</th>
                  <th>Jam</th>
                  <th>Keterangan</th>
                  <th>Approval</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $no = 1;
              foreach ($get_karyawan->result() as $key) {
                $id         = $key->id;
                $nik        = $key->nik;
                $fullname   = $key->fullname;
                $tgl        = $key->tgl;
                $jam_a      = $key->jam_a;
                $jam_b      = $key->jam_b;
                $keterangan = $key->keterangan;
                $approval   = $key->approval;
              ?>
                <tr>
                  <td class="text-center">
                    <div class="btn-group">
                      <button  onClick="reject('<?=$id;?>', '<?=$nik;?>', '<?=$fullname;?>', '<?=$tgl;?>', '<?=$jam_a;?>', '<?=$jam_b;?>')" type="button" class="btn btn-danger btn-sm">
                        <i class="fa fa-check"></i> Batalkan
                      </button>
                    </div>
                  </td>
                  <td><?=$no;?></td>
                  <td><?=$nik;?></td>
                  <td><?=$fullname;?></td>
                  <td><?=$fullname;?></td>
                  <td><?=$tgl;?></td>
                  <td>
                    <?=date('H-i-s', strtotime($jam_a));?> s/d <?=date('H-i-s', strtotime($jam_b));?>
                  </td>
                  <td><?=$keterangan;?></td>
                  <td><?=$approval;?></td>
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