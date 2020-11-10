<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Detail Karyawan</h4>
    </div>
    <div class="modal-body">
      <div class="row">
        <?php
        if($this->session->userdata('logged_in')['role_id'] != '0'){
          foreach ($card_a_modal as $key) {
            if( is_file( FCPATH.'assets/foto_karyawan/'.$key->foto_karyawan ) ){
              $foto = base_url('assets/foto_karyawan/'.$key->foto_karyawan);
            }else{
              $foto = base_url('assets/foto_karyawan/default_user.jpg');
            }
            ?>
            <div class="col-md-6">
              <table class="table table-bordered table-hover table-sm">
                <tbody>
                  <tr>
                    <td rowspan="4" width="150px">
                      <img src="<?=$foto;?>" width="150px">
                    </td>
                    <td><strong>Nama: <?=$key->fullname;?> </strong></td>
                  </tr>
                  <tr>
                    <td><strong>NIK: <?=$key->nik;?> </strong></td>
                  </tr>
                  <tr>
                    <td><strong>Jabatan: <?=$key->jabatan;?> </strong></td>
                  </tr>
                  <tr>
                    <td><strong>Status: <?=$key->status;?> </strong></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <?php
          }
          ?>
        </div>
        <?php
      }else{
        ?>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Nama Cabang</th>
              <th>Total Karyawan</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($card_a_modal as $key) {
              ?>
              <tr>
                <td><?=$key->nama_cabang;?></td>
                <td><?=$key->total;?> Karyawan</td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <?php } ?>
      
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>