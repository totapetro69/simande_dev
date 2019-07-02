<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Surat Penyerahan</title>

    <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css" > -->

    <!-- <link rel="stylesheet" href="assets/css/pdf-style.css" > -->
    
    <style type="text/css">



    /** Define now the real margins of every page in the PDF **/
    body {
        margin-top: 2cm;
        margin-bottom: 2cm;
        font-size: 10px;
    }

    /** Define the header rules **/
    header {
        position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;

        border-top: 1px solid  #5D6975;
        border-bottom: 1px solid  #5D6975;
        color: #5D6975;
        line-height: 1.4em;
        font-weight: normal;
        margin: 5px 0 5px 0;
        /*background: url("../images/dimension.png");*/

        /*height: 60px;*/
        /*min-height: 60px;*/
    }

    /** Define the footer rules **/
    footer {
        position: fixed; 
        bottom: 0cm; 
        left: 0cm; 
        right: 0cm;
        height: 2cm;

        text-align: center;
        /*line-height: 1.5cm;*/
    }

    #header-left
    {
      /*width: 100px;*/
      /*height: 100px;*/
      float: left; 
      /*background: url("../images/honda_logo.png");*/
      /*background-size: 100px;*/
    }
    #header-right
    {
      /*width: 100px;*/
      /*height: 100px;*/
      float: right; 
      /*background: url("../images/ahass.png");*/
      /*background-size: 100px;*/
    }

    /*#desc {
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
        width: 100%;
    }*/
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
        width: 90px;
        font-size: 9px;
    }

    .project span {
        text-align: left;
        padding: 2px 0;
        display: table-cell;
    }

    .project .content {
        width: 200px;
        font-size: 9px;
    }

    .project p{
      margin: 0;
    }

    .page-break-before{
      page-break-before: always;
    }

    .page-break-after{
      page-break-after: always;
    }
    main{
      font-size: 8px;
    }

    table.table-full {
      width: 100%;
    }

    table.table-border{
      border-collapse: collapse;
    }

    table.table-border td {
       border: 1px solid  #5D6975;
    }

    td{
      vertical-align: top;
      padding: 5px;
    }

    td.content{
      font-size: 9px;
    }

    td.title{
      font-size: 9px;
    }

    input.type1
    {
       border: 1px solid  #5D6975;
       height: 10px;
       width: 20px;

    }
    input.type2{
       border: 1px solid  #5D6975;
       height: 10px;
       width: 10px;

    }
    </style>

  </head>
  <body>


  
<?php

$NO_PKB = ''; //PKB
$TANGGAL_PKB = ''; //PKB
$NO_MESIN = '';
$NO_RANGKA = '';
$NO_POLISI = '';
$KD_TYPEMOTOR = '';
$TAHUN = ''; //PKB
$KM_SAATINI = '';
$EMAIL = ''; //QUISIONER
$SOSMED = ''; //QUISIONER
$NAMA_PEMILIK = '';
$NAMA_COMINGCUSTOMER = '';
$ALAMAT_PEMILIK = '';
$ALAMAT_COMINGCUSTOMER = '';
$KELKEC = ''; //MASTER CUSTOMER
$NO_HP = '';
$HP_COMINGCUSTOMER = '';
$ESTIMASI_PENGERJAAN = '';
$ESTIMASI_SELESAI = '';
$KEBUTUHAN_KONSUMEN = '';
$HASIL_ANALISA_SA ='';

if($list){

  if(is_array($list->message)){

    foreach ($list->message as $key => $value) {
      $NO_MESIN = $value->NO_MESIN;
      $NO_RANGKA = $value->NO_RANGKA;
      $NO_POLISI = $value->NO_POLISI;
      $KD_TYPEMOTOR = $value->KD_TYPEMOTOR;
      $KM_SAATINI = $value->KM_SAATINI;
      $NAMA_PEMILIK = $value->NAMA_PEMILIK;
      $NAMA_COMINGCUSTOMER = $value->NAMA_COMINGCUSTOMER;
      $ALAMAT_PEMILIK = $value->ALAMAT;
      $ALAMAT_COMINGCUSTOMER = $value->ALAMAT_COMINGCUSTOMER;
      $NO_HP = $value->NO_HP;
      $HP_COMINGCUSTOMER = $value->HP_COMINGCUSTOMER;
      $ESTIMASI_PENGERJAAN = $value->ESTIMASI_PENGERJAAN;
      $ESTIMASI_SELESAI = $value->ESTIMASI_SELESAI;
      $KEBUTUHAN_KONSUMEN = $value->KEBUTUHAN_KONSUMEN;
      $TAHUN = $value->TAHUN;
      $HASIL_ANALISA_SA = $value->HASIL_ANALISA_SA;
           
    }

  }

}

$KD_DEALERAHM = '';
$NAMA_DEALER = '';
$ALAMAT = '';
$TLP = '';

if($dealer){

  if(is_array($dealer->message)){

    foreach ($dealer->message as $key => $value) {
     
      $KD_DEALERAHM = $value->KD_DEALERAHM;
      $NAMA_DEALER = $value->NAMA_DEALER;
      $ALAMAT = $value->ALAMAT;
      $TLP = $value->TLP.($value->TLP2?' / '.$value->TLP2:'').($value->TLP3?' / '.$value->TLP3:'');
    }

  }

}

?>
    <header class="clearfix">
      <div id="header" style="display: flex !important; align-items:center;">
        <span id="header-left">
          <img src="<?php echo base_url();?>assets/images/honda_logo.png" width="60px">
        </span>
        <!-- <span style="text-align: center; padding:0 100px;"> -->
          <p  style="text-align: center; padding:0 65px 0 65px;">
          <strong>AHASS <?php echo $KD_DEALERAHM.' '.$NAMA_DEALER;?></strong><br>
          <span style="font-size: 8px;">
          <?php echo $ALAMAT;?>
          </span><br>
          <strong style="font-size: 10px;">BOOKING SERVICE : <?php echo $TLP;?></strong>

          </p>
        <!-- </span> -->
        <span id="header-right">
          <img src="<?php echo base_url();?>assets/images/ahass.png" width="60px">
        </span>
      </div>

    </header>

    <main>
      <div id="sa-header" style="text-align: center; border-bottom: 1px solid  #5D6975;">
        <h5 style="margin:0 0 5px 0;">FORM SERVIS ADVISOR</h5>        
      </div>


      <div class="project">
        <div style="">
          <span class="title"><p><strong>Data Motor</strong></p></span> 
          <span class="content"><p></p></span>
          <span class="title"><p><strong>Data Pembawa</strong></p></span> 
          <span class="content"><p></p></span>
        </div>


        <div style="">
          <span class="title"><p>No. PKB</p></span> 
          <span class="content"><p><?php echo $NO_PKB;?></p></span>
          <span class="title"><p>Nama</p></span> 
          <span class="content"><p><?php echo $NAMA_COMINGCUSTOMER;?></p></span>
        </div>

        <div style="">
          <span class="title"><p>Tanggal Servis</p></span> 
          <span class="content"><p><?php echo $TANGGAL_PKB;?></p></span>
          <span class="title"><p>Alamat</p></span> 
          <span class="content"><p><?php echo $ALAMAT_COMINGCUSTOMER;?></p></span>
        </div>

        <div style="">
          <span class="title"><p>No. Mesin</p></span> 
          <span class="content"><p><?php echo $NO_MESIN;?></p></span>
          <span class="title"><p>Kel/Kec</p></span> 
          <span class="content"><p><?php echo $KELKEC;?></p></span>
        </div>

        <div style="">
          <span class="title"><p>No. Rangka</p></span> 
          <span class="content"><p><?php echo $NO_RANGKA;?></p></span>
          <span class="title"><p>No. Telp/HP</p></span> 
          <span class="content"><p><?php echo $HP_COMINGCUSTOMER;?></p></span>
        </div>

        <div style="">
          <span class="title"><p>No. Polisi</p></span> 
          <span class="content"><p><?php echo $NO_POLISI;?></p></span>
          <span class="title"><p></p></span> 
          <span class="content"><p></p></span>
        </div>

        <div style="">
          <span class="title"><p>Type</p></span> 
          <span class="content"><p><?php echo $KD_TYPEMOTOR;?></p></span>
          <span class="title"><p><strong>Data Pemilik</strong></p></span> 
          <span class="content"><p></p></span>
        </div>

        <div style="">
          <span class="title"><p>Tahun</p></span> 
          <span class="content"><p><?php echo $TAHUN;?></p></span>
          <span class="title"><p>Nama</p></span> 
          <span class="content"><p><?php echo $NAMA_PEMILIK;?></p></span>
        </div>

        <div style="">
          <span class="title"><p>KM</p></span> 
          <span class="content"><p><?php echo $KM_SAATINI;?></p></span>
          <span class="title"><p>Alamat</p></span> 
          <span class="content"><p><?php echo $ALAMAT_PEMILIK;?></p></span>
        </div>

        <div style="">
          <span class="title"><p>*Email</p></span> 
          <span class="content"><p><?php echo $EMAIL;?></p></span>
          <span class="title"><p>Kel/Kec</p></span> 
          <span class="content"><p><?php echo $KELKEC;?></p></span>
        </div>

        <div style="">
          <span class="title"><p>*Sosmed</p></span> 
          <span class="content"><p><?php echo $SOSMED;?></p></span>
          <span class="title"><p>No. Telp/HP</p></span> 
          <span class="content"><p><?php echo $NO_HP;?></p></span>
        </div>
      </div>

      <table class="table-full table-border" style=" height: 200px;">
        <tr>
          <td class="title">Kondisi Awal SMH</td>
          <td class="title">Pekerjaan</td>
          <td class="title">Estimasi Biaya</td>
          <td class="title">Analisa Service Advisor</td>
        </tr>

        <tr>
          <td class="content" style=" border: none; border-left: 1px solid  #5D6975;">
            <b>Catatan lain :</b><br/>
            Dari Delaer Sendiri<br/>
            <br/>
            Hubungan Pembawa

          </td>
          <td class="content">
            <?php foreach ($list->message as $key => $value): 
              $key++;
              if($value->KATEGORI == 'Jasa'):
            ?>
              <?php echo $key.'. '.$value->PART_DESKRIPSI;?> <br/>
            <?php endif; endforeach; ?>
          </td>
          <td class="content">
            <?php foreach ($list->message as $key => $value): 
              $key++;
              if($value->KATEGORI == 'Jasa'):
            ?>
              <?php echo 'Rp '.$value->TOTAL_HARGA;?> <br/>
            <?php endif; endforeach; ?>
          </td>
          <td class="content" rowspan="4"><?php echo $HASIL_ANALISA_SA;?></td>
        </tr>
        <tr>
          <td style="border: none; border-left: 1px solid  #5D6975;"></td>
          <td class="title">Suku Cadang</td>
          <td class="title">Estimasi Harga</td>
        </tr>
        <tr>

          <td class="content" style="width: 100px; border: none; border-left: 1px solid  #5D6975;">
            <b>Alasan ke AHASS</b><br/>
            a. Inisiatif Sendiri<br/>
            b. SMS Reminder<br/>
            c. Telp Reminder<br/>
            d. Sticker Reminder<br/>
            e. Lainnya<br/>
            
            <img src="<?php echo base_url();?>assets/images/bensin.png" width="100%">
          </td>
          <td class="content">
            <?php foreach ($list->message as $key => $value): 
              $key++;
              if($value->KATEGORI == 'Part'):
            ?>
              <?php echo $key.'. '.$value->PART_DESKRIPSI;?> <br/>
            <?php endif; endforeach; ?>
          </td>
          <td class="content">
            <?php foreach ($list->message as $key => $value): 
              $key++;
              if($value->KATEGORI == 'Part'):
            ?>
              <?php echo 'Rp '.$value->TOTAL_HARGA;?> <br/>
            <?php endif; endforeach; ?>
          </td>
        </tr>
        <tr>
          <td style="border: none; border-left: 1px solid  #5D6975; border-bottom: 1px solid  #5D6975;"></td>
          <td class="title">Total Harga</td>
          <td class="title"></td>
        </tr>
      </table>

      <br>

      <table class="table-full table-border">
        <tr>
          <td class="title">Keluhan Konsumen</td>
        </tr>
        <tr>
          <td class="content">
            <?php echo $KEBUTUHAN_KONSUMEN;?>
            <br/>
          </td>
        </tr>
      </table>

      <table class="table-full">
        <tr>
          <td colspan="2">
            *Apabila ada tambahan <strong>PEKERJAAN / PENGGANTIAN PART</strong> di luar daftar di atas maka : <strong>Syarat dan Ketentuan</strong>
          </td>
        </tr>
        <tr>
          <td width="60%">
            <input type="text" class="type1" name=""> Konfirmasi dulu / tekp ke             <input type="text" class="type1" name=""> Langsung dikerjakan <br>
            Part bekas dibawa konsumen : <input type="text" class="type2" name=""> Ya <input type="text" class="type2" name=""> Tidak
          </td>
          
          <td width="40%">
          1. Formulir ini adalah surat kuasa pekerjaan PKB<br/>
          2. Bengkel tidak bertanggung jawab terhadap sepeda motor yang tidak diambil selama 30 hari<br/>
          3. Bengkel tidak bertanggung jawab apabila terjadi Force Majoure<br/>
          </td>
        </tr>
      </table>

      <!-- <span class="page-break-after"></span> -->

      <table class="table-border">
        <tr>
          <td colspan="2">Estimasi Pekerjaan Selesai</td>
          <td style="border: none;"></td>
          <td>Tambahan Pekerjaan</td>
          <td style="border: none;"></td>
          <td style="width: 10px !important;">OK</td>
          <td style="width: 100px !important;"></td>
          <td style="border: none;"></td>
          <td colspan="2">Penyerahan Motor Oleh SA</td>
        </tr>
        <tr>
          <td height="40px">Konsumen Ttd</td>
          <td height="40px">Service Advisor Ttd</td>
          <td height="40px" style="border: none;"></td>
          <td height="40px">Konsumen Ttd</td>
          <td height="40px" style="border: none;"></td>
          <td colspan="2" height="40px">Paraf Final lns</td>
          <td height="40px" style="border: none;"></td>
          <td height="40px" style="vertical-align: middle; text-align: center; font-size: 15px;">OK</td>
          <td>Konsumen Ttd</td>
        </tr>
      </table>

      <br>

      <table class="table-full table-border">
        <tr>
          <td colspan="2">Saran Mekanik</td>
        </tr>
        <tr>
          <td height="40px"></td>
          <td height="40px" style="vertical-align: bottom;">Nama Mekanik:</td>
        </tr>
      </table>

      <br>

      <table class="table-full">
        <tr>
          <td>
            Garansi : <br>
            - 500 KM/1 Minggu untuk Servis Reguler <br>
            - 1.000 KM/1 Bulan untuk Bongkar Mesin Reguler <br>
            - 1.000 KM/1 Bulan untuk Servis CBR 250 dan PCX 150 <br>
            - 1.500 KM/45 Hari Bongkar Mesin CBR 250 dan PCX 150 <br>
            <strong>SERVIS RUTIN DI AHASS MOTOR TERAWAT KANTONG HEMAT</strong>
          </td>
          <td style="text-align: right;">
            No. Pendaftaran<br/>
            Estimasi Waktu Mulai<br/>
            Estimasi Waktu Selesai<br/>
          </td>
          <td>
            :  <br/>
            : <?php echo $ESTIMASI_PENGERJAAN;?> <br/>
            : <?php echo $ESTIMASI_SELESAI;?> <br/>
          </td>
        </tr>
      </table>
      
    </main>

    <footer>


      <!-- <div class="project">
        <div style="">
          <span class="content" style="width: 33%; text-align: left; padding: 0 10px 0 0;">Menyetujui</span>

          <span class="content"></span>

          <span class="content" style="width: 33%; text-align: right; padding: 0 0 0 10px;">Pengurus</span>
        </div>
      </div> -->
    </footer>

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