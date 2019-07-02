<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambahkan Jenis Kelamin</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('Setup/add_gender_simpan'); ?>">

        <div class="form-group">
            <label>Kode</label>
            <input type="text" name="kd_gender" id="kd_gender" class="form-control" placeholder="Masukkan kode jenis kelamin" >
        </div>

        <div class="form-group">
            <label>Jenis Kelamin</label>
            <input type="text" name="nama_gender" id="nama_gender" class="form-control" placeholder="Masukkan nama jenis kelamin" >
        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

