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
$NO_RANGKA = '';
$NAMA_DEALER = '';
$ALAMAT = '';
$TOTAL_DATA = '';

$KETERANGAN = '';
$NO_MESIN = '';
$NAMA_PENERIMA = '';
$TGL_PENERIMA = '';
$TGL_PENYERAHAN = '';
$DATA_NOMOR = '';
$WILAYAH_DEALER='';

if($list){

  if(is_array($list->message)){

    foreach ($list->message as $key => $value) {
      $NO_RANGKA = $value->NO_RANGKA;
      $NAMA_DEALER = $value->NAMA_DEALER;;
      $ALAMAT = $value->ALAMAT_SURAT;
      $WILAYAH_DEALER = $value->WILAYAH_DEALER;
      $TOTAL_DATA = $list->totaldata;

      switch ($ket) {
        case 'STNK':
          $KETERANGAN = 'STNK';
          $DESKRIPSI = '<p>Dengan hormat,</p>
                        <p>Bersama ini kami serahkan '.$TOTAL_DATA.' Set STNK dengan perincian sebagai berikut :</p>';
          $NAMA_PENERIMA = $value->NAMA_PENERIMA_STNK;
          $TGL_PENYERAHAN = tglfromSql($value->TGL_PENYERAHAN_STNK);
          $DATA_NOMOR = $value->DATA_NOMOR_STNK;
          break;
          
        case 'BPKB':
          $KETERANGAN = 'BPKB';
          $DESKRIPSI = '<p>Dengan hormat,</p>
                        <p>Bersama ini kami serahkan '.$TOTAL_DATA.' Set BPKB kepada :</p>
                        <p>'.($value->KD_FINCOY != NULL?'PT. '.$value->KD_FINCOY : $value->NAMA_PENERIMA_BPKB).'</p>
                        <p>dengan perincian sebagai berikut :</p>';
          $NAMA_PENERIMA = $value->NAMA_PENERIMA_BPKB;
          $TGL_PENYERAHAN = tglfromSql($value->TGL_PENYERAHAN_BPKB);
          $DATA_NOMOR = $value->DATA_NOMOR_BPKB;
          break;
          
        case 'PLAT':
          $KETERANGAN = 'STCK & PLAT';
          $DESKRIPSI = '<p>Dengan hormat,</p>
                        <p>Bersama ini kami serahkan '.$TOTAL_DATA.' Buah STCK & '.$TOTAL_DATA.' Buah Plat dengan perincian sebagai berikut :</p>';
          $NAMA_PENERIMA = $value->NAMA_PENERIMA_PLAT;
          $TGL_PENYERAHAN = tglfromSql($value->TGL_PENYERAHAN_PLAT);
          $DATA_NOMOR = $value->DATA_NOMOR_PLAT;
          break;
          
        case 'HCC':
          $KETERANGAN = 'NOTIS PAJAK & HONDA CLUB CARD (HCC)';
          $DESKRIPSI = '<p>Dengan hormat,</p>
                        <p>Bersama ini kami serahkan '.$TOTAL_DATA.' Buah Notis Pajak & '.$TOTAL_DATA.' Honda Club Card (HCC) dengan perincian sebagai berikut :</p>';
          $NAMA_PENERIMA = $value->NAMA_PENERIMA_HCC;
          $TGL_PENYERAHAN = tglfromSql($value->TGL_PENYERAHAN_HCC);
          $DATA_NOMOR = $value->DATA_NOMOR_HCC;
          break;
      }

    }

  }

}

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tanda Terima Penyerahan</h4>
</div>

<div class="modal-body" id="printarea">

    <table id="desc" class="">
      <tr>
        <td class="text-center" colspan="4"><h3><strong>TANDA TERIMA PENYERAHAN <?php echo $KETERANGAN;?></strong></h3></td>
      </tr>

      <tr><td colspan="4">&nbsp;</td><td></td></tr></tr>

      <tr>
        <td colspan="4" class="text-right"><p>PT. TRIOMOTOR <?php echo $NAMA_DEALER;?></p></td>
      </tr>

      <tr>
        <td colspan="4"><?php echo $DESKRIPSI;?></td>
      </tr>

      <!-- <tr>
        <td colspan="2" valign="top">
            <div class="project">
              <div><span class="title">Nomor <?php echo $KETERANGAN;?></span><span class="content"> <?php echo $DATA_NOMOR;?></span></div>
              <div><span class="title">Tgl Penyerahan</span><span class="content"> <?php echo $TGL_PENYERAHAN;?></span></div>
              <div><span class="title">Nomor HP</span><span class="content"> </span></div>
            </div>
        </td>
        <td colspan="2" valign="top">
            <div class="project">
              <div><span class="title">Kepada</span><span class="content"> <?php echo $NAMA_PENERIMA;?></span></div>
              <div><span class="title">Alamat Pembeli</span><span class="content"> <?php echo $ALAMAT;?></span></div>
            </div>
        </td>
      </tr> -->

      <tr><td colspan="4">&nbsp;</td></tr>

      <tr style="border-bottom: 2px solid; border-top: 1px solid;">
        <td style="width: 25px;">No.</td>
        <td>No. Mesin</td>
        <td>No. Polisi</td>
        <td>Nama</td>
        <td>Alamat</td>
      </tr>

      <?php 
      $no = 1;
      foreach ($list->message as $key => $detail): ?>
      <tr>
        <td><?php echo $no;?></td>
        <td><?php echo $detail->KD_MESIN.$detail->NO_MESIN;?></td>
        <td><?php echo $detail->DATA_NOMOR_PLAT;?></td>
        <td><?php echo $detail->NAMA_PEMILIK;?></td>
        <td><?php echo $detail->ALAMAT_SURAT;?></td>
      </tr>
      <tr>
        <td colspan="4">Nama STNK : <?php echo $detail->NAMA_PEMILIK;?></td>
      </tr>
      <?php 
      $no++;
      endforeach;?>

      <tr><td colspan="4">&nbsp;</td></tr>
      <tr><td colspan="4">&nbsp;</td></tr>

      <tr><td colspan="4">
        <p>Keterangan :</p>
        <p><?php echo $KETERANGAN;?> diserahkan dalam keadaan benar dan lengkap sesuai dengan yang dimohonkan.</p>
      </td></tr>
      
      <tr>
        <td colspan="4">
            <div class="project">
              <div>
                <span class="content" style="width: 33%; text-align: left; padding: 0 10px 0 0;"></span>
                <span class="content" style="width: 33%; text-align: left; padding: 0 5px 0 5px;"></span>
                <span class="content" style="width: 33%; text-align: left; padding: 0 0 0 10px;"><?php echo $WILAYAH_DEALER;?>, <?php echo $TGL_PENYERAHAN;?></span>
              </div>
            </div>
        </td>
      </tr>
      <tr>
        <td colspan="4">
            <div class="project">
              <div>
                <span class="content" style="width: 33%; text-align: left; padding: 0 10px 0 0;">Diterima oleh,</span>
                <span class="content" style="width: 33%; text-align: left; padding: 0 5px 0 5px;">Dibuat oleh,</span>
                <span class="content" style="width: 33%; text-align: left; padding: 0 0 0 10px;">Diserahkan oleh,</span>
              </div>
            </div>
        </td>
      </tr>
      <tr>
        <td colspan="4">
            <div class="project">
              <div>
                <span class="title" style="width: 33%; text-align: left; padding: 100px 10px 0 0;"><p style="border-bottom: 1px solid; "><?php echo $NAMA_PENERIMA;?></p></span> 
                <span class="title" style="width: 33%; text-align: left; padding: 100px 5px 0 5px;"><p style="border-bottom: 1px solid; ">&nbsp;</p></span> 
                <span class="title" style="width: 33%; text-align: left; padding: 100px 0 0 10px;"><p style="border-bottom: 1px solid; ">&nbsp;</p></span>
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