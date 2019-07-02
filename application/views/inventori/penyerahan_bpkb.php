<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Surat Penyerahan</title>

    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css" >

    <!-- <link rel="stylesheet" href="assets/css/pdf-style.css" > -->
    
    <style type="text/css">



    /** Define now the real margins of every page in the PDF **/
    body {
        margin-top: 0,25cm;
        margin-left: 0,33cm;
        margin-right: 0,25cm;
        margin-bottom: 0cm;
    }

    /** Define the header rules **/
    header {
        position: fixed;
        top: 0cm;
        /*left: 0cm;*/
        /*right: 0cm;*/
        height: 2cm;

        /*text-align: center;*/
        line-height: 10px;
    }

    /** Define the footer rules **/
    footer {
        position: fixed; 
        bottom: 0cm; 
        /*left: 0cm; */
        /*right: 0cm;*/
        height: 2cm;

        text-align: center;
        /*line-height: 1.5cm;*/
    }

    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
      border-top: none;
    }

    #desc {
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
        width: 100%;
    }
    .project {
        text-align: left;
        display: table;
        width: 100%;
    }
    .project div {
        display: table-row;
    }

    .project .title {
        color: #5D6975;
        width: 0.5cm;
        /*font-size: 7px;*/
    }

    .project span {
        text-align: left;
        padding: 2px 0;
        display: table-cell;
    }

    .project .content {
        width: 6cm;
        /*font-size: 7px;*/
    }

    .page-break-before{
      page-break-before: always;
    }

    .page-break-after{
      page-break-after: always;
    }
    </style>

  </head>
  <body>


  
<?php
$NO_TRANS = '';
$TGLMULAI_PENGURUSAN = '';
$NAMA_KABUPATEN = '';
$NAMA_DEALER = '';
$ALAMAT = '';
$KABUPATEN_DEALER='';
$KETERANGAN = '';
$FAKTUR_PENJUALAN = '';

if($list){

  if(is_array($list->message)){

    foreach ($list->message as $key => $value) {

    }

  }

}

?>
    <main>
        <h4 style="text-align: center;"><u>SURAT PERNYATAAN PENYERAHAN BPKB</u></h4>

        <table class="table">
        <?php 
        $no=1;
        $total = 0;
        foreach($list->message as $key => $detail): 
        ?>

        <tr>
          <td colspan="2">Yang bertanda tangan di bawah ini :</td>
        </tr>
        <tr>
          <td>Nama</td>
          <td>: <?php echo $detail->KSP;?></td>
        </tr>
        <tr>
          <td>Jabatan</td>
          <td>: <?php echo $detail->PERSONAL_JABATAN;?></td>
        </tr>
        <tr>
          <td>Bertindak atas nama</td>
          <td>: <?php echo $detail->NAMA_DEALER;?></td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td>: <?php echo $detail->ALAMAT;?></td>
        </tr>

        <tr><td colspan="2"></td></tr>

        <tr>
          <td colspan="2">
            Dengan ini menyatakan bahwa BPKB dari kendaraan yang dijual dengan fasilitas Pembiayaan Konsumen dengan perincian sebagai berikut :
          </td>

        </tr>
        <tr>
          <td>Jenis</td>
          <td>: Sepeda Motor</td>
        </tr>
        <tr>
          <td>Merk</td>
          <td>: HONDA</td>
        </tr>
        <tr>
          <td>Tahun / CC</td>
          <td>: <?php echo $detail->THN_PERAKITAN.' / '.$detail->CC_MOTOR;?> CC</td>
        </tr>
        <tr>
          <td>No Rangka</td>
          <td>: <?php echo $detail->VNORANGKA1.$detail->NO_RANGKA;?></td>
        </tr>
        <tr>
          <td>No Mesin</td>
          <td>: <?php echo $detail->NO_MESIN;?></td>
        </tr>
        <tr>
          <td>Atas Nama (Nama STNK)</td>
          <td>: <?php echo $detail->NAMA_BPKB;?></td>
        </tr>
        <tr>
          <td>Berdasarkan PO No.</td>
          <td>: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tanggal :</td>
        </tr>
        <tr>
          <td>Atas Nama (Nama Pemohon)</td>
          <td>: <?php echo $detail->NAMA_BPKB;?></td>
        </tr>

        <tr><td colspan="2"></td></tr>

        <tr>
          <td colspan="2">
            Selambat-lambatnya 3 (tiga) bulan sejak tanggal Surat Pernyataan ini, akan diserahkan langsung <?php echo $detail->NAMA_LEASING?'kepada'.$detail->NAMA_LEASING:'';?>, tidak kepada pihak manapun.
          </td>

        </tr>

        <tr><td colspan="2"></td></tr>
        <tr><td colspan="2"></td></tr>

        <tr>
          <td colspan="2">
            Demikianlah Surat Pernyataan ini dibuat untuk dipergunakan.

          </td>

        </tr>

        <tr>
          <td colspan="2">
              <div class="project">
                <div>
                  <span class="content" style="text-align: center;"></span> 
                  <span class="content" style="text-align: center;"></span> 
                  <span class="content" style="text-align: left;"><?php echo $detail->NAMA_KABUPATEN;?>,</span>
                </div>
              </div>
          </td>
        </tr>

        <tr>
          <td colspan="2">
              <div class="project">
                <div>
                  <span class="content" style="text-align: center;">Menyetujui,</span> 
                  <span class="content" style="text-align: center;"></span> 
                  <span class="content" style="text-align: left;">Yang Membuat Pernyataan,</span>
                </div>
              </div>
          </td>
        </tr>


        <tr>
          <td colspan="2">
              <div class="project">
                <div>
                  <span class="content" style="text-align: center; padding-top: 80px;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</span> 
                  <span class="content" style="text-align: center; padding-top: 80px;"></span> 
                  <span class="content" style="text-align: center; padding-top: 80px;">( <?php echo ($detail->KSP?$detail->KSP:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');?> )</span>
                </div>
              </div>
          </td>
        </tr>

        <?php 
        $no++;
        ?>
        </table>


        <?php
        endforeach; 
        ?>


    </main>

  <!-- <script src="<?php echo base_url('assets/js/jquery2.0.3.min.js') ;?>"></script>
  <script src="<?php echo base_url('assets/js/jquery.mask.js'); ?>"></script> -->
  <!-- <script src="assets/js/jquery2.0.3.min.js"></script>
  <script src="assets/js/jquery.mask.js"></script>

  <script type="text/javascript">
    $(document).ready(function(){

      $('.biaya').mask('000.000.000.000,00', {reverse: true});
    });
  </script> -->
  
  </body>
</html>