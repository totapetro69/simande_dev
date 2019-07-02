<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambahkan Jenis Penerimaan</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('Setup/add_jenisreceiving_simpan'); ?>">

        <div class="form-group">
            <label>Kode Jenis Penerimaan</label>
            <input type="text" name="kd_jenisreceiving" id="kd_jenisreceiving" class="form-control" placeholder="Masukkan kode jenis penerimaan" >
        </div>

        <div class="form-group">
            <label>Nama Jenis Penerimaan</label>
            <input type="text" name="nama_jenisreceiving" id="nama_jenisreceiving" class="form-control" placeholder="Masukkan nama jenis penerimaan" >
        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

