<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Jenis PIT : <?php echo $list->message[0]->NAMA_JENISPIT; ?></h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/apdate_jenispit/' . $list->message[0]->ID); ?>">

        <div class="form-group">
            <label>Kode Jenis Order</label>
            <input type="text" name="kd_jenispit" id="kd_jenispit" class="form-control" value="<?php echo  $list->message[0]->KD_JENISPIT; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Nama Jenis Order</label>
            <input type="text" name="nama_jenispit" id="nama_jenispit" class="form-control" value="<?php echo  $list->message[0]->NAMA_JENISPIT; ?>" >
        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>