
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambahkan Dokumen Baru</h4>
</div>

<div class="modal-body">

<form id="addForm" class="bucket-form" action="<?php echo base_url('setup/add_data_simpan');?>" method="post">
                <div class="form-group">
                    <label>Kode Nomor Dokumen</label>
                    <input type="text" name="kd_docno" class="form-control" placeholder="Masukkan kode dokumen">
                </div>

                <div class="form-group">
                    <label>Nama Nomor Dokumen</label>
                    <input type="text" name="nama_docno" class="form-control" placeholder="Masukan Nama" >
                </div>

                <div class="form-group">
                    <label>Kode Dealer</label>
                    <input type="text" name="kd_dealer" id="" class="form-control" placeholder="Masukkan kode dealer">
                </div>

                <div class="form-group">
                    <label>Tahun</label>
                    <input type="date" name="tahun_docno" class="form-control" placeholder="Masukan Tahun" >
                </div>

                <div class="form-group">
                    <label>Bulan</label>
                    <input type="date name="bulan_docno" id="" class="form-control" placeholder="Masukkan Bulan">
                </div>

                <div class="form-group">
                    <label>Urutan</label>
                    <input type="text" name="urutan_docno" class="form-control" placeholder="Masukkan urutan" >
                </div>

                <div class="form-group">
                    <label>Reset</label>
                    <input type="text" name="reset_docno" class="form-control" placeholder="" >
                </div>

            </div>

        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-danger">Save changes</button>
</div>
