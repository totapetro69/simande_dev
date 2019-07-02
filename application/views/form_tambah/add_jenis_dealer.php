<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('dealer/add_jenis_dealer_simpan'); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambahkan Rule Dealer Baru</h4>
    </div>

    <div class="modal-body">

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="form-group">
                    <label>Kode Rule Dealer</label>
                    <input id="kd_jenisdealer" type="text" name="kd_jenisdealer" class="form-control" placeholder="Masukkan Kode Rule Dealer" maxlength="5" required>
                </div>

                <div class="form-group">
                    <label>Nama Rule Dealer</label>
                    <input id="nama_jenisdealer" type="text" name="nama_jenisdealer" class="form-control" placeholder="Masukkan Nama Rule Dealer" required>
                </div>

            </div>

        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
    </div>

</form>