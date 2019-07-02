<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = $this->session->userdata("kd_dealer");
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambah Master Perkiraan</h4>
</div>

<div class="modal-body">
    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('finance/add_perkiraan_simpan'); ?>">
            <div class="form-group">
                <label>Kode Perkiraan</label>
                <input type="text" name="kd_akun" id="kd_akun" class="form-control" placeholder="Masukkan kode Perkiraan">
            </div>
            <div class="form-group">
                <label>Nama Perkiraan</label>
                <input type="text" name="nama_akun" id="nama_akun" class="form-control" placeholder="Masukkan nama Perkiraan" >
            </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>