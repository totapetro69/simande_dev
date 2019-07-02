<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambah Apparel</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('inventori/add_apparel_simpan'); ?>">

        <div class="form-group">
            <label>Kode</label>
            <input type="text" name="kd_apparel" id="kd_apparel" class="form-control" placeholder="Masukkan kode" >
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama_apparel" id="nama_apparel" class="form-control" placeholder="Masukkan nama" >
        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>