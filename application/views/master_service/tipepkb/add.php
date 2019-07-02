<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambah Tipe PKB</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/add_tipepkb_simpan'); ?>">

        <div class="form-group">
            <label>Kode</label>
            <input type="text" name="kd_tipepkb" id="kd_tipepkb" class="form-control" placeholder="Masukkan kode tipe pkb" >
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama_tipepkb" id="nama_tipepkb" class="form-control" placeholder="Masukkan nama tipe pkb" >
        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

