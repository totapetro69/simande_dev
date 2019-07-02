<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('company/add_pekerjaan_simpan'); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambahkan Pekerjaan Baru</h4>
    </div>

    <div class="modal-body">

        <div class="form-group">
            <label>Kode Pekerjaan</label>
            <input type="text" name="kd_pekerjaan" id="kd_pekerjaan" class="form-control" placeholder="Masukkan Kode Pekerjaan" maxlength="5" required>
        </div>

        <div class="form-group">
            <label>Nama Pekerjaan</label>
            <input type="text" name="nama_pekerjaan" id="nama_pekerjaan" class="form-control" placeholder="Masukkan Nama Pekerjaan" required>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
    </div>

</form>