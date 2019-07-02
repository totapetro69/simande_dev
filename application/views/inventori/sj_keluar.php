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
    width: 90px;
}

.square-checkbox {
  border: 1px solid; width: 20px; height: 20px; 
}

</style>

<?php
$NAMA_DEALER = '';
$ALAMAT_DEALER = '';
$FAKTUR_PENJUALAN = '';
$NO_SURATJALAN = '';
$TGL_FAKTUR = '';
$NAMA_PENERIMA = '';
$NAMA_BPKB = '';
$ALAMAT_KIRIM = '';
$TGL_ESTIMASIKIRIM = '';
$WAKTU_ESTIMASIKIRIM = '';

$NO_MOBIL = '';
$NAMA_SOPIR = '';
$NAMA_PENERIMA = '';
$NAMA_EKSPEDISI = '';
$NAMA_KABUPATEN = '';
$KEPALA_GUDANG = '';
$TOTAL_DATA = $list->totaldata;

$TGL_SURATJALAN = '';


if($list){

  if(is_array($list->message)){

    $jmluraian=count($list->message);

    foreach ($list->message as $key => $value) {
      $NAMA_DEALER = $value->NAMA_DEALER;
      $ALAMAT_DEALER = $value->ALAMAT;
      $FAKTUR_PENJUALAN = $value->FAKTUR_PENJUALAN;
      $NO_SURATJALAN = $value->NO_SURATJALAN;
      $TGL_FAKTUR = tglfromSql($value->TGL_SPK);
      $TGL_SURATJALAN = tglfromSql($value->TGL_SURATJALAN);
      $NAMA_PENERIMA = $value->NAMA_PENERIMA;
      $ALAMAT_KIRIM = $value->ALAMAT_KIRIM;
      $TGL_ESTIMASIKIRIM = $value->TGL_ESTIMASIKIRIM;
      $WAKTU_ESTIMASIKIRIM = $value->WAKTU_ESTIMASIKIRIM;

      $NO_MOBIL = $value->NO_MOBIL;
      $NAMA_SOPIR = $value->NAMA_SOPIR;
      $NAMA_PENERIMA = $value->NAMA_PENERIMA;
      $NAMA_BPKB = $value->NAMA_BPKB;
      $NAMA_EKSPEDISI = $value->NAMA_EKSPEDISI;
      $NAMA_KABUPATEN = $value->NAMA_KABUPATEN;
      $KEPALA_GUDANG = $value->KEPALA_GUDANG;

    }

  }

}

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Surat Jalan</h4>
</div>

<div class="modal-body" id="printarea">
    <h3><strong><?php echo $NAMA_DEALER;?></strong></h3>
    <P><?php echo $ALAMAT_DEALER;?></h3>
    <P></P>

    <h3>SURAT JALAN</h3>

    <div class="project" style="margin-bottom:10px;">
      <div>
        <span class="title" style="width: 20%">Nomor Faktur</span>
        <span class="content" style="width: 30%"> <?php echo $FAKTUR_PENJUALAN;?></span>
        <span class="title" style="width: 20%">Kepada</span>
        <span class="content" style="width: 30%"> <?php echo str_replace("\'","'",$NAMA_PENERIMA);?></span>
      </div>
      <div>
        <span class="title" style="width: 20%">Tanggal Faktur</span>
        <span class="content" style="width: 30%"> <?php echo $TGL_FAKTUR;?></span>
        <span class="title" style="width: 20%">Nama STNK</span>
        <span class="content" style="width: 30%"> <?php echo $NAMA_BPKB;?></span>
      </div>
      <div>
        <span class="title" style="width: 20%">Nomor Surat Jalan</span>
        <span class="content" style="width: 30%"> <?php echo $NO_SURATJALAN;?></span>
        <span class="title" style="width: 20%">Alamat Pembeli</span>
        <span class="content" style="width: 30%"> <?php echo str_replace("\'","'",$ALAMAT_KIRIM);?></span>
      </div>
    </div>

    <table id="desc" class="">

      <tr style="border-bottom: 2px solid; border-top: 1px solid;">
        <td style="width:45px;">No</td>
        <td>Keterangan</td>
        <td>Nomor Mesin</td>
        <td>Nomor Rangka</td>
        <!-- <td>Jumlah</td> -->
      </tr>

      <?php 
      $no = 1;
      foreach ($list->message as $key => $detail_sj): ?>
      <tr>
        <td><?php echo $no;?></td>
        <td><?php echo $detail_sj->KET_UNIT.' - '.$detail_sj->NAMA_ITEM;?></td>
        <td><?php echo $detail_sj->NO_MESIN;?></td>
        <td><?php echo $detail_sj->VNORANGKA1.$detail_sj->NO_RANGKA;?></td>
        <!-- <td style="text-align: right;"><?php echo $detail_sj->JUMLAH;?></td> -->
      </tr>
      <?php 
      $no++;
      endforeach;?>

      <tr><td colspan="4">&nbsp;</td></tr>

      <tr>
        <td colspan="4">
          <p>Petugas pengiriman unit telat menjelaskan poin-poin sebagai berikut :</p>
        </td>
      </tr>

      <?php for ($j=1; $j <= 5; $j++) { 

        switch ($j) {
          case 1:
            $poin = 'Fungsi dan fitur Sepeda Motor Honda';
            break;
          
          
          case 2:
            $poin = 'Jadwal Service';
            break;
          
          
          case 3:
            $poin = 'Ketentuan Garansi';
            break;
          
          
          case 4:
            $poin = 'AHASS Terdekat';
            break;
          
          
          case 5:
            $poin = 'Perlengkapan Standard';
            break;
        }
          
          ?>
      <tr>
        <td><?php echo $j;?>.</td>
        <td>
          <?php echo $poin;?>
        </td>
        <td><div style="border: 1px solid; width: 20px; height: 20px; margin:1px 0 1px 0;"></div><!-- <input type="checkbox" name=""> --></td>
        <td <?php echo ($j == 5?'style="text-align:center;"':'');?>><?php echo ($j == 5?'<u>'.$NAMA_PENERIMA.'</u>':'');?></td>
      </tr>

      <?php  } ?>

      <tr><td colspan="4">&nbsp;</td></tr>
      <tr>
        <td colspan="4"><p>Jika petugas kami tidak menjelaskan poin-poin diatas, maka anda berhak mendapatkan Rp. 300.000,-</p></td></tr>


      <tr>
        <td colspan="2" style="border: 1px solid; padding:5px;"></td>
        <td style="border: 1px solid; padding:5px;">Tanggal</td>
        <td style="border: 1px solid; padding:5px;">Waktu (Jam:Menit)</td>
        <!-- <td>Jumlah</td> -->
      </tr>


      <tr>
        <td colspan="2" style="border: 1px solid; padding:5px;">Janji Pengantaran Unit</td>
        <td style="border: 1px solid; padding:5px;"><?php echo tglfromSql($TGL_ESTIMASIKIRIM);?></td>
        <td style="border: 1px solid; padding:5px;"><?php echo $WAKTU_ESTIMASIKIRIM;?></td>
        <!-- <td>Jumlah</td> -->
      </tr>


      <tr>
        <td colspan="2" style="border: 1px solid; padding:5px;">Unit SMH diterima oleh konsumen</td>
        <td style="border: 1px solid; padding:5px;"></td>
        <td style="border: 1px solid; padding:5px;"></td>
        <!-- <td>Jumlah</td> -->
      </tr>

      <tr><td colspan="4">&nbsp;</td></tr>

      <tr>
        <td colspan="3">
          <p>Untuk masukan dan pengaduan, silahkan hubungin kami pada nomor :</p>
        </td>
        <td></td>
        <!-- <td>Jumlah</td> -->
      </tr>


      <tr>
        <td colspan="3">
          <p>08001402240 (toll free) / 08115114002 (telp/sms)</p>
        </td>
        <td style="text-align:center;"><u><?php echo str_replace("\'","'",$NAMA_PENERIMA);?></u></td>
        <!-- <td>Jumlah</td> -->
      </tr>

      <tr><td colspan="4">&nbsp;</td></tr>
      <tr><td colspan="4">&nbsp;</td></tr>
      <tr><td colspan="4">&nbsp;</td></tr>
      <tr style="border-bottom: 2px solid; box-shadow: 0px 3px 0px #c7c6c6;"><td colspan="4">&nbsp;</td></tr>


      <tr><td colspan="4">&nbsp;</td></tr>
      <tr><td colspan="4">&nbsp;</td></tr>

      <tr>
        <td colspan="2" valign="top">
          <p>PERLENGKAPAN STANDARD :</p>
          <?php echo '<p>'.$TOTAL_DATA.' BUKU KESALAHAN, '.$TOTAL_DATA.' BUKU PEDOMAN, </p>
          <p>'.$TOTAL_DATA.' BUKU SERVICE, '.$TOTAL_DATA.' HELM, '.$TOTAL_DATA.' TOOL SET</p>';?>
        </td>
        <td colspan="2" style="text-align: right;" valign="top">
            <div class="project">
              <div><span class="title" style="text-align: right;"><?php echo $NAMA_KABUPATEN;?>, <?php echo $TGL_SURATJALAN;?>  <!-- <?php echo date('d/m/Y');?> --></span></div>
            </div>
        </td>
      </tr>


      <tr>
        <td colspan="4">
            <div class="project">
              <div>
                <span class="content" style="text-align: center; padding-top: 80px;"></span>
                <span class="content" style="text-align: center; padding-top: 80px;"></span>
                <span class="content" style="text-align: center; padding-top: 80px;"><u><?php echo $this->session->userdata('user_name');?></u></span>
              </div>
            </div>
        </td>
      </tr>


      <tr>
        <td colspan="4">
            <div class="project">
              <div>
                <span class="title" style="text-align: center;">Diserahkan dan diperiksa oleh PDI Tanggal :</span> 
                <span class="title" style="text-align: center;">Diterima oleh Pengantar Unit Tanggal :</span> 
                <span class="title" style="text-align: center;">Diterima oleh Konsumen Tanggal :</span>
              </div>
            </div>
        </td>
      </tr>

      <tr>
        <td colspan="4">
            <div class="project">
              <div>
                <span class="content" style="text-align: center; padding-top: 80px;"><u><?php echo $KEPALA_GUDANG;?></u></span>
                <span class="content" style="text-align: center; padding-top: 80px;"><u><?php echo $NAMA_SOPIR;?></u></span>
                <span class="content" style="text-align: center; padding-top: 80px;"><u><?php echo $NAMA_PENERIMA;?></u></span>
              </div>
            </div>
        </td>
      </tr>
      <tr>
        <td colspan="4">
            <div class="project">
              <div>
                <span class="content" style="text-align: center;"><u><?php //echo $KEPALA_GUDANG;?></u></span>
                <span class="content" style="text-align: center;"><?php echo $NO_MOBIL;?></span>
                <span class="content" style="text-align: center;"><u><?php //echo $NAMA_PENERIMA;?></u></span>
              </div>
            </div>
        </td>
      </tr>
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