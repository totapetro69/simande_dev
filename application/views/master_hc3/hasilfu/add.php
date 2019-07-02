<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_hc3/add_hasilfu_simpan'); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Status</h4>
    </div>

    <div class="modal-body">


        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori" class="form-control">
                <option value="Hasil FU H1">Hasil FU H1</option>
                <option value="Hasil FU H2">Hasil FU H2</option>\
            </select>
        </div>

        <div class="form-group">
            <label>Status</label>
            <input type="text" name="status" id="status" class="form-control" placeholder="Masukkan status" >
        </div>

        <div class="form-group">
            <label>Klasifikasi</label>
            <input type="text" name="klasifikasi" id="klasifikasi" class="form-control" placeholder="Masukkan klasifikasi" >
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
    </div>

</form>
