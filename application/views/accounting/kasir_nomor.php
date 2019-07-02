<?php 
//if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

/*$status_c = (isBolehAkses('c') ? '' : 'remove-button' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); */

$tgl_setting=date("d/m/Y");
$username=$this->session->userdata("user_name");
$kd_docno="";
$darinomor="";
$sampainomor="";
$nomorpakai="";
$tahun=date("Y");
$editable="";
if(isset($nomor)){
  if($nomor->totaldata>0){
    foreach ($nomor->message as $key => $value) {
        $kd_docno = $value->KD_DOCNO;
        $darinomor = $value->FROM_DOCNO;
        $sampainomor = $value->TO_DOCNO;
        $nomorpakai = $value->LAST_DOCNO;
        $tahun = $value->TAHUN_DOCNO;
        $tgl_setting = TglFromSql($value->CREATED_TIME);
        $editable="disabled-action";
    }
  }
}
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('cashier/setup_nomorator/');?>" method="post">

  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Setup Nomorator Kwitansi : </h4>
  </div>

    <div class="modal-body">
      <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8">
          <div class="form-group">
            <label>Dealer</label>
           <input id="kd_dealer" type="text" name="kd_dealer" class="form-control <?php echo $editable;?>" value='<?php echo $this->session->userdata("nama_dealer");?>' readonly required>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4">
          <div class="form-group">
            <label>Tahun</label>
            <input type="text" name="tahun_docno" id="tahun_docno" class="form-control <?php echo $editable;?>" value="<?php echo $tahun;?>">
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 ">
          <div class="form-group">
            <label>Tanggal</label>
            <input type="text" name="open_date" class="form-control <?php echo $editable;?>" value="<?php echo $tgl_setting;?>" placeholder="dd/mm/yyyy"  readonly>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="form-group">
            <label>User Name</label>
            <input id="user_name" type="text" name="user_name" value='<?php echo $username;?>' class="form-control <?php echo $editable;?>" placeholder="Masukkan user name" required>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="form-group">
            <label>Dari Nomor</label>
            <input id="from_docno" type="text" name="from_docno" class="form-control <?php echo $editable;?>" value='<?php echo $darinomor;?>' required>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="form-group">
            <label>Sampai Dengan</label>
            <input id="to_docno" type="text" name="to_docno" class="form-control  <?php echo $editable;?>"  value='<?php echo $sampainomor;?>' required>
          </div>
        </div>
        <div class="col-xs-12 col-sm-5 col-md-5 ">
          <div class="form-group">
            <label>Nomor Terakhir Dipakai</label>
            <input id="last_docno" type="text" name="last_docno" class="form-control  <?php echo $editable;?>" value='<?php echo $nomorpakai;?>' >
          </div>
        </div>
        <div class="col-xs-12 col-sm-7 col-md-7 hidden">
          <div class="form-group">
            <label>Keterangan</label>
            <input id="keterangan" type="text-area" name="keterangan" class="form-control" placeholder="Keterangan" >
          </div>
        </div>
      </div>
    </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button type="submit" class="btn btn-danger  <?php echo $editable;?>">Simpan</button>
  </div>

</form>
