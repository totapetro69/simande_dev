<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambahkan Tipe Customer</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('Setup/add_typecustomer_simpan'); ?>">

        <div class="form-group">
            <label>Kode Tipe Customer</label>
            <input type="text" name="kd_typecustomer" id="kd_typecustomer" class="form-control" placeholder="Masukkan kode Tipe Customer" >
        </div>

        <div class="form-group">
            <label>Nama Tipe Customer</label>
            <input type="text" name="nama_typecustomer" id="nama_typecustomer" class="form-control" placeholder="Masukkan nama Tipe Customer" >
        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

