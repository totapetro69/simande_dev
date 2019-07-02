<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('motor/add_segmen_motor_simpan'); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambahkan Segmen Motor Baru</h4>
    </div>

    <div class="modal-body">

        <div class="form-group">
            <label>Kode Segmen Motor</label>
            <input type="text" name="kd_segmen" id="kd_segmen" class="form-control" placeholder="Masukkan Kode Segmen Motor" maxlength="5" required>
        </div>

        <div class="form-group">
            <label>Nama Segmen Motor</label>
            <input type="text" name="nama_segmen" id="nama_segmen" class="form-control" placeholder="Masukkan Nama Segmen Motor" required>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
    </div>

</form>