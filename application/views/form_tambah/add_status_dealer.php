<form id="addForm" class="bucket-form" action="<?php echo base_url('dealer/add_status_dealer_simpan'); ?>" method="post">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambahkan Jenis Dealer Baru</h4>
    </div>

    <div class="modal-body">

        <div class="form-group">
            <label>Kode Jenis Dealer</label>
            <input id="kd_statusdealer" type="text" name="kd_statusdealer" class="form-control" placeholder="Masukkan Kode Jenis Dealer" maxlength="5" required>
        </div>

        <div class="form-group">
            <label>Nama Jenis Dealer</label>
            <input type="text" name="nama_statusdealer" id="nama_statusdealer" class="form-control" placeholder="Masukkan Nama Jenis Dealer" required>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
    </div>

</form>

