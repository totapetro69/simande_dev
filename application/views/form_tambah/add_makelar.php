<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambahkan Master Makelar</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('setup/add_makelar_simpan'); ?>">
        <div class="form-group">
            <label>Nama Makelar</label>
            <input type="text" name="nama_makelar" id="nama_makelar" class="form-control" placeholder="Masukkan nama makelar" >
        </div>
		<div class="form-group">
            <label>No. Hp</label>
            <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Masukkan nomor hp" >
        </div>
		<div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukkan alamat" >
        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

