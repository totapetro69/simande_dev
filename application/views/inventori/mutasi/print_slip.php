<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$defaultDealer = $this->session->userdata("kd_dealer");
$defaultPart = '';
$kd_gudang_asal="";
$rakbin_asal="";
$jumlah_asal="";

$kd_gudang_tujuan="";
$kd_dealer_tujuan="";
$no_rangka=""; $tgl_mutasi=date("d/m/Y");
$keterangan="";
$no_trans="";
$jenis_mutasi="";
if(isset($list)){
  if($list->totaldata>0){
    foreach ($list->message as $key => $value) {
      $defaultDealer = $value->KD_DEALER;
      $defaultPart = $value->PART_NUMBER;
      $kd_gudang_asal = $value->KD_GUDANG_ASAL;
      $kd_gudang_tujuan = $value->KD_GUDANG_TUJUAN;
      $kd_dealer_tujuan = $value->KD_DEALER_TUJUAN;
      $tgl_mutasi = tglFromSql($value->TGL_TRANS);
      $no_trans = $value->NO_TRANS;
      $keterangan = $value->KETERANGAN;
      $jenis_mutasi = $value->JENIS_TRANS;
    }
  }
}


$status_c = (isBolehAkses('c') ? '' : 'remove-button' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>

<style type="text/css">
  .panel-footer {
    background-color: #fff;
    border: none;
  }


  #desc {
      border-collapse: collapse;
      border-spacing: 0;
      margin-bottom: 20px;
      width: 100%;
  }
  .project {
      /* float: left; */
      text-align: left;
      display: table;
      width: 100%;
  }
  .project div {
      display: table-row;
  }

  .project .title {
      color: #5D6975;
      width: 90px;
  }

  .project span {
      text-align: left;
      /* width: 100px; */
      /* margin-right: 15px; */
      padding: 2px 0;
      display: table-cell;
      /* font-size: 0.8em; */
  }

  .project .content {
      width: 150px;
  }

</style>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Moving Slip</h4>
</div>


<div class="modal-body" id="printarea">

    <table id="desc" class="">
      <tr>
        <td colspan="11" style="text-align: center;"><h3><strong>MOVING SLIP</strong></h3></td>
      </tr>

      <tr><td colspan="11">&nbsp;</td><td></td></tr></tr>

      <tr>
        <td colspan="6" valign="top">
            <div class="project">
              <div><span class="title">Nomor Transaksi</span><span class="content"> <?php echo $no_trans;?></span></div>
              <div><span class="title">Tanggal</span><span class="content"> <?php echo $tgl_mutasi;?></span></div>
            </div>
        </td>
      </tr>

      <tr><td colspan="11">&nbsp;</td></tr>

      <tr style="border-bottom: 2px solid; border-top: 1px solid;">
        <td style="width:45px;">No</td>
        <td>No. Trans</td>
        <td>Tanggal</td>
        <td>No. Part</td>
        <td>Keterangan</td>
        <td>Jenis Mutasi</td>
        <td>Lokasi Asal</td>
        <td>Rakbin Asal</td>
        <td>Lokasi Tujuan</td>
        <td>Rakbin Tujuan</td>
        <td>Jumlah</td>
      </tr>

      <?php 
      $no = 1;
      $total = 0;

      foreach ($list->message as $key => $row): ?>
      <tr>
        <td><?php echo $no;?></td>
        <td><?php echo $row->NO_TRANS;?></td>
        <td><?php echo tglFromSql($row->TGL_TRANS);?></td>
        <td><?php echo $row->PART_NUMBER;?></td>
        <td><?php echo $row->KETERANGAN;?></td>
        <td><?php echo $row->JENIS_TRANS;?></td>
        <td><?php echo $row->KD_GUDANG_ASAL;?></td>
        <td><?php echo $row->RAKBIN_ASAL;?></td>
        <td><?php echo $row->KD_GUDANG_TUJUAN;?></td>
        <td><?php echo $row->RAKBIN_TUJUAN;?></td>
        <td><?php echo $row->JUMLAH;?></td>
      </tr>
      <?php 
      $no++;
      endforeach;?>


      <tr><td colspan="11">&nbsp;</td></tr>
      <tr><td colspan="11">&nbsp;</td></tr>


    </table>

</div>

<div class="modal-footer">
    
    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button type="button" onclick="printSj();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>

</div>

<script src="<?php echo base_url('assets/dist/print.min.js');?>"></script>

<script type="text/javascript">
  function printSj() {
    printJS('printarea','html');
     $('#keluar').click();
  }

</script>

