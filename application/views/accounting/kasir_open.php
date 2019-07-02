<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 

$tgltrans=date("d/m/Y");$saldo_awal=0;$total_terima=0;$total_keluar=0;$saldo_akhir=0;$id_trans=0;
if(isset($last)){
    if($last->totaldata>0){
      foreach ($last->message as $key => $value) {
        $tgltrans=tglFromSql($value->OPEN_DATE);
        $saldo_akhir = $value->SALDO_AKHIR;
        $saldo_awal = $value->SALDO_AKHIR;
        $id_trans = $value->ID;
      }
    }
  }
  
  $tgltrans=($tgltrans)?$tgltrans:date("d/m/Y");
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('cashier/open_cash');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Open Transaksi : </h4>
</div>

<div class="modal-body">

  <div class="form-group">
    <label>Dealer</label>
    <input id="nama_dealer" type="text" name="nama_dealer" class="form-control" value="<?php echo $this->session->userdata("nama_dealer");?>" readonly>
    <input type="hidden" id="kd_dealer" name="kd_dealer" value="<?php echo $this->session->userdata("kd_dealer");?>" required="true">
  </div>

  <div class="form-group">
    <label>Tanggal</label>
    <input type="text" name="open_date" class="form-control" value="<?php echo $tgltrans;?>"  readonly>
  </div>
  <div class="form-group">
    <label>Saldo Closing</label>
    <input id="saldo_akhir" type="text-area" name="saldo_akhir" class="form-control" placeholder="Saldo Akhir" value="<?php echo (isset($sa))?number_format($sa,0):"0";?>" readonly>
  </div>
  <div class="form-group">
    <label>Saldo Awal</label>
    <input id="saldo_awal" type="text-area" name="saldo_awal" class="form-control disabled-action" placeholder="Saldo Awal" value="<?php echo (isset($sa))?number_format($sa,0):"0";?>" required>
  </div>
  
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
    <button id="submit-btn" type="submit" class="btn btn-danger <?php echo $status_e?>  submit-btn"><i class='fa fa-save'></i> Proses</button>
</div>

</form>
