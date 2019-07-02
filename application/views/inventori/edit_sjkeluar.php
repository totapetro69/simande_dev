
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit: 
    <?php echo $list->message[0]->NO_SURATJALAN;?></h4>
</div>

<div class="modal-body">

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('inventori/update_sjkeluar');?>">

    <div class="col-xs-6 col-sm-6 col-md-6">
        
        <div class="form-group">
            <label>Kode Main Dealer</label>
            <input type="text" name="kd_maindealer" in="kd_maindealer" value="<?php echo $list->message[0]->KD_MAINDEALER;?>" required class="form-control" placeholder="Masukkan Kode Main Dealer" >
        </div>
        
        <div class="form-group">
            <label>Kode Dealer</label>
            <input type="text" name="kd_dealer" in="kd_dealer" value="<?php echo $list->message[0]->KD_DEALER;?>" required class="form-control" placeholder="Masukkan Kode Dealer" >
        </div>

        <div class="form-group">
            <label>Kode Gudang</label>
            <input type="text" name="kd_dealer" in="kd_gudang" value="<?php echo $list->message[0]->KD_GUDANG;?>" required class="form-control" placeholder="Masukkan Kode Gudang" >
        </div>

        <div class="form-group">
            <label>No Reff</label>
            <input type="text" name="no_reff" in="no_reff" value="<?php echo $list->message[0]->NO_REFF;?>" required class="form-control" placeholder="Masukkan No Reff" >
        </div>

        <div class="form-group">
            <label>Kode Customer</label>
            <input type="text" name="kd_customer" in="kd_customer" value="<?php echo $list->message[0]->KD_CUSTOMER;?>" required class="form-control" placeholder="Masukkan Kode Customer" >
        </div>
    
        <div class="form-group">
            <label>Alamat Kirim</label>
            <input type="text" name="alamat_kirim" in="alamat_kirim" value="<?php echo $list->message[0]->ALAMAT_KIRIM;?>" required class="form-control" placeholder="Masukkan Alamat Kirim" >
        </div>
    
        <div class="form-group">
            <label>Tanggal Kirim</label>
            <div class="input-group input-append date" id="date">
                        <input type="text" class="form-control" name="date" placeholder="MM/DD/YY" />
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <label>Nama Pengirim</label>
            <input type="text" name="nama_pengirim" in="nama_pengirim" value="<?php echo $list->message[0]->NAMA_PENGIRIM;?>" required class="form-control" placeholder="Masukkan Nama Pengirim" >
        </div>

        <div class="form-group">
            <label>No Mobil</label>
            <input type="text" name="no_mobil" in="no_mobil" value="<?php echo $list->message[0]->NO_MOBIL;?>" required class="form-control" placeholder="Masukkan No Mobil" >
        </div>

        <div class="form-group">
            <label>Nama Sopir</label>
            <input type="text" name="nama_sopir" in="nama_sopir" value="<?php echo $list->message[0]->NAMA_SOPIR;?>" required class="form-control" placeholder="Masukkan Nama Sopir" >
        </div>

        <div class="form-group">
            <label>Nama Penerima</label>
            <input type="text" name="nama_penerima" in="nama_penerima" value="<?php echo $list->message[0]->NAMA_PENERIMA;?>" required class="form-control" placeholder="Masukkan Nama Penerima" >
        </div>

        <div class="form-group">
            <label>Status Surat</label>
            <select id="single_select" class="form-control" >
                <option>Process</option>
                <option>Aproved</option>
                <option>Rejected</option>
            </select>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea class="form-control" placeholder="Silahkan masukkan keterangannya"></textarea>
        </div>
    </div>

</form>

      <!-- <input type="submit" name=""> -->

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
    <button type="button" id="submit-btn" class="btn btn-danger" onclick="addData();">Simpan</button>
</div>