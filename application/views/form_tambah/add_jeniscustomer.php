<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambahkan Jenis Customer</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('setup/add_jeniscustomer_simpan'); ?>">

        <div class="form-group">
            <label>Kode Jenis Customer</label>
            <input type="text" name="kd_jeniscustomer" id="kd_jeniscustomer" class="form-control" placeholder="Masukkan kode jenis customer" >
        </div>

        <div class="form-group">
            <label>Nama Jenis Customer</label>
            <input type="text" name="nama_jeniscustomer" id="nama_jeniscustomer" class="form-control" placeholder="Masukkan nama jenis customer" >
        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

