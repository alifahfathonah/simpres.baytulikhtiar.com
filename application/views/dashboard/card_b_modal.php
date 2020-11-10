<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Detail Data Absen Belum Terisi</h4>
    </div>
    <div class="modal-body">
      <table id="table" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>NIK</th>
            <th>Nama</th>
            <th>Cabang</th>
            <th>Absens</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($card_b_modal as $key) {
            ?>
            <tr>
              <td><?=$key->nik;?></td>
              <td><?=$key->nama;?></td>
              <td><?=$key->cabang;?></td>
              <td><?=$key->total;?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>