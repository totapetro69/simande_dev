
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambahkan Surat</h4>
</div>

<div class="modal-body">

<form id="addForm" class="bucket-form" action="<?php echo base_url('surat_jalan/add_sj_simpan');?>" method="post">

            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>Nomor Surat</label>
                    <input type="text" name="no_surat_jalan" class="form-control" placeholder="Masukkan Nomor Surat">
                </div>

                <div class="form-group">
                    <label>Tanggal Shipping</label>
                    <input type="date" name="tgl_shipping" class="form-control" placeholder="Masukan Tanggal Shipping" >
                </div>

                <div class="form-group">
                    <label>Kode Dealer</label>
                    <input type="text" name="kd_dealer" id="" class="form-control" placeholder="Masukkan kode dealer">
                </div>

                <div class="form-group">
                    <label>Kode Cabang Dealer</label>
                    <input type="date" name="kd_cabang_dealer" class="form-control" placeholder="Masukan Kode Cabang Dealer" >
                </div>

                <div class="form-group">
                    <label>Kode Tipe Motor</label>
                    <input type="date name="kd_type_motor" id="" class="form-control" placeholder="Masukkan Kode Tipe Motor">
                </div>
            

                <div class="form-group">
                    <label>Kode Warna</label>
                    <input type="text" name="kd_warna" class="form-control" placeholder="Masukkan Kode Warna" >
                </div>

                <div class="form-group">
                    <label>No Rangka</label>
                    <input type="text" name="no_rangka" class="form-control" placeholder="Masukkan No Rangka" >
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label>No Mesin</label>
                    <input type="text" name="no_mesin" class="form-control" placeholder="Masukkan No Mesin" >
                </div>

                <div class="form-group">
                    <label>Thn Perakitan</label>
                    <input type="text" name="thn_perakitan" class="form-control" placeholder="Masukkan Thn Perakitan" >
                </div>

                <div class="form-group">
                    <label>No Ref</label>
                    <input type="text" name="no_ref" class="form-control" placeholder="Masukkan No Ref" >
                </div>

                <div class="form-group">
                    <label>Expedisi</label>
                    <input type="text" name="expedisi" class="form-control" placeholder="Masukkan Expedisi" >
                </div>

                <div class="form-group">
                    <label>No Pol Truk</label>
                    <input type="text" name="no_pol_truk" class="form-control" placeholder="Masukkan No Pol Truk" >
                </div>

                <div class="form-group">
                    <label>No Faktur</label>
                    <input type="text" name="no_faktur" class="form-control" placeholder="Masukkan No Faktur" >
                </div>
            </div>
            

        </div>

    </form>

</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-danger">Save changes</button>
</div>
