<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambah Tipe Service Mekanik</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/add_tipeservicemekanik_simpan'); ?>">

        <div class="form-group">
            <label>Kode</label>
            <input type="text" name="kd_tipeservicemekanik" id="kd_tipeservicemekanik" class="form-control" placeholder="Masukkan kode tipe service mekanik" >
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama_tipeservicemekanik" id="nama_tipeservicemekanik" class="form-control" placeholder="Masukkan nama tipe service mekanik" >
        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

