<style type="text/css">
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

<?php


$id = "";
$no_trans = "";
$no_reff = "";
$nama_konsumen = "";
$kd_maindealer = "";
$kd_dealer = "";

$kd_rakbin = "";
$part_number = "";
$jumlah = "";
$price = "";
$harga_jual = "";
$part_batch = "";
$no_reff = "";


if (isset($list)) {
    if (($list->totaldata > 0)) {
        foreach ($list->message as $key => $value) {

            $id = $value->ID;
            $no_trans = $value->NO_TRANS;
            $no_reff = $value->NO_REFF;
            $tgl_trans = date('Y-m-d H:i:s', strtotime($value->CREATED_TIME));
            $nama_konsumen = $value->NAMA_KONSUMEN;
            $kd_maindealer = $value->KD_MAINDEALER;
            $kd_dealer = $value->KD_DEALER;

            $kd_rakbin= $value->KD_RAKBIN;
            $part_number= $value->PART_NUMBER;
            $jumlah= $value->JUMLAH;
            $price= $value->PRICE;
            $harga_jual= $value->HARGA_JUAL;
            $part_batch= $value->PART_BATCH;
            $no_reff= $value->NO_REFF;
        }
    }
}

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Picking Slip</h4>
</div>

<div class="modal-body" id="printarea">

    <table id="desc" class="">
      <tr>
        <td colspan="5" style="text-align: center;"><h3><strong>PICKING SLIP</strong></h3></td>
      </tr>

      <tr><td colspan="5">&nbsp;</td><td></td></tr></tr>

      <tr>
        <td colspan="3" valign="top">
            <div class="project">
              <div><span class="title">Nomor Transaksi</span><span class="content"> <?php echo $no_trans;?></span></div>
              <div><span class="title">Tanggal Jam</span><span class="content"> <?php echo $tgl_trans;?></span></div>
            </div>
        </td>
        <td colspan="2" valign="top">
            <div class="project">
              <div><span class="title">Dokumen</span><span class="content"> <?php echo $no_reff;?></span></div>
            </div>
        </td>
      </tr>

      <tr><td colspan="5">&nbsp;</td></tr>

      <tr style="border-bottom: 2px solid; border-top: 1px solid;">
        <td style="width:45px;">No</td>
        <td>Rak</td>
        <td>Part Number</td>
        <td>Deskripsi</td>
        <td>Qty</td>
      </tr>

      <?php 
      $no = 1;
      $total = 0;

      foreach ($list->message as $key => $row): ?>
      <tr>
        <td><?php echo $no;?></td>
        <td><?php echo $row->KD_RAKBIN;?></td>
        <td><?php echo $row->PART_NUMBER;?></td>
        <td><?php echo $row->PART_DESKRIPSI;?></td>
        <td style="text-align: right;"><?php echo number_format($row->JUMLAH);?></td>
      </tr>
      <?php 
      $no++;
      $total = $total + number_format($row->JUMLAH);
      endforeach;?>
      <tr style="border-top: 1px solid;">
        <td colspan="4" style="text-align: right;">Jumlah Item</td>
        <td colspan="1" style="text-align: right;"><?php echo $total;?></td>
      </tr>

      <tr><td colspan="5">&nbsp;</td></tr>
      <tr><td colspan="5">&nbsp;</td></tr>


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