<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambahkan Jenis Pergerakan</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('Setup/add_jenispergerakan_simpan'); ?>">

        <div class="form-group">
            <label>Kode Jenis Pergerakan</label>
            <input type="text" name="kd_jenispergerakan" id="kd_jenispergerakan" class="form-control" placeholder="Masukkan kode jenis pergerakan" >
        </div>

        <div class="form-group">
            <label>Nama Jenis Pergerakan</label>
            <input type="text" name="nama_jenispergerakan" id="nama_jenispergerakan" class="form-control" placeholder="Masukkan nama jenis pergerakan" >
        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

