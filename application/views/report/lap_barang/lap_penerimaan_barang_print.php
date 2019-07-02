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
        width: 100%;
    }

    /*@page { size: portrait; }*/
</style>
<?php
$defaultDealer = ($this->input->get("kd_dealer"))? $this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
$dtgl =isset($d_tgl)?$d_tgl:date("d/m/Y",strtotime("first day of this month"));
$stgl =isset($s_tgl)?$s_tgl:date("d/m/Y",strtotime("-1 day"));
$tp =isset($j_trans)?$j_trans:"0";
  $nama_dealer=""; $tlp=""; $nama_kabupaten=""; $alamat=""; $kd_dealerahm="";

  if(isset($dealer)){
    if($dealer->totaldata >0){
      foreach ($dealer->message as $key => $value) {
        $kd_dealerahm = $value->KD_DEALERAHM;
        $alamat = $value->ALAMAT;
        $nama_kabupaten = NamaWilayah("Kabupaten",$value->KD_KABUPATEN);
        $tlp = $value->TLP;
        $nama_dealer = $value->NAMA_DEALER_ASLI;
      }
    }
  }
 // var_dump($j_trans);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Laporan Penerimaan Barang</h4>
</div>

<div class="modal-body" id="printarea">
    <table style="border: none;" id="desc" border="0" >
      <tr>
        <td style="text-align: left !important;"><span style="font-size: 10px;">
            <?php echo $nama_dealer;?><br>
            <?php echo $alamat;?> <br>
            <?php echo $nama_kabupaten;?><br>
            <?php echo $tlp;?><br></span>
        </td>
        <td style="text-align: center; width:300px !important;"><h4 style="margin:0 0 5px 0;"><strong>Laporan Penerimaan Barang</strong></h4><br><span style="font-size: 10px;">Periode : <?php echo $dtgl; ?> s/d <?php echo $stgl; ?></span>
        </td>
        
      </tr>
    </table>
    <table class="table table-striped b-t b-light"; style="border-collapse: collapse;" border="1">
        <thead>
            <tr align="center">
                <th style="width:5%">No.</th>
                      <th style="width:10%">Kode Barang</th>
                      <th style="width:25%">Nama Barang</th>
                      <?php if($tp=='1'){
                          echo "<th style='width:10%'> No. Trans</th>
                          <th style='width:8%'>Tanggal</th>
                          <th style='width:8%'>No. Reff</th>";
                      }
                      ?>
                      <th style="width:10%">Jumlah</th>
                      <th style="width:10%">Harga</th>
                      <th style="width:10%">Total Harga</th>
                      <?php if($tp=='0'){
                        echo "<th>Keterangan</th>";
                     }?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 0;
            if (isset($list)){
                if ($list->totaldata >0){
                    foreach ($list->message as $key => $row){
                        $no ++;
                        ?>
                        <tr>
                            <td  align='center'><?php echo $no; ?></td>
                            <td  align='center'><?php echo $row->KD_BARANG; ?></td>
                            <td style='white-space: nowrap;'><?php echo $row->NAMA_BARANG; ?></td>
                            <?php if($tp=='1'){
                                echo "<td style='white-space: nowrap;'>".$row->NO_TRANS."</td>
                                    <td style='white-space: nowrap;'>".tglFromSql($row->TGL_TRANS)."</td>
                                    <td style='white-space: nowrap;'>".($row->NO_REFF)."</td>
                                    <td align='right'>".number_format($row->JUMLAH,0)."</td>";
                                 }else{
                                    echo "<td align='right'>".number_format($row->TERIMA,0)."</td>";
                                 }
                            ?>
                              <td class='text-right'><?php echo number_format($row->HARGA,0); ?></td>
                              <td class='text-right'><?php echo number_format($row->TOTAL_HARGA,0); ?></td>
                            <?php if($tp=='0'){
                                echo "<td>&nbsp;</td>";
                            }?>
                        </tr>
                        
                        <?php
                    }
                }
            }else{
                echo belumAdaData(7);
            }
            ?>
        </tbody>
    </table>
</div>
<div class="modal-footer">

    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button type="button" onclick="printSj();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>

</div>

<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
        function printSj() {
            printJS('printarea', 'html');
            $('#keluar').click();
        }
</script>