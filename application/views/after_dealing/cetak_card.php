<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Surat Penyerahan</title>

    <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css" > -->

    <link rel="stylesheet" href="<?php echo base_url().'assets/css/pdf-style.css'; ?>" >
    <style type="text/css">
      #custom-table{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
      }

      #custom-table tr:nth-child(2n-1) td {
          background: #F5F5F5;
      }

      #custom-table td.service, #custom-table td.desc {
          vertical-align: top;
      }
      #custom-table .service, #custom-table .desc {
          text-align: left;
      }
      #custom-table td {
          padding: 10px;
          text-align: right;
          border-right: 1px solid #C1CED9;
          border-left: 1px solid #C1CED9;
      }
      #custom-table th, #custom-table td {
          text-align: center;
      }

      #custom-table th {
          padding: 5px 20px;
          color: #5D6975;
          border: 1px solid #C1CED9;
          white-space: nowrap;
          font-weight: normal;
      }
      #custom-table th, #custom-table td {
          text-align: center;
      }


      #custom-table {
          border-bottom: 1px solid #C1CED9;
      }
    </style>



  </head>
  <body>


  
<?php

$NO_HP = '';
$NO_TRANS = '';
$TGL_TRANS = '';
$NO_SPK = '';
$KD_DEALER = '';
$NAMA_CUSTOMER = '';
$KD_CUSTOMER = '';
$NO_RANGKA = '';
$NO_MESIN = '';
$ID = '';
$KD_SALES = '';
$NAMA_AKTIVITAS = '';
$TIPE_AKTIVITAS = '';
$STATUS_AKTIVITAS = '';
$WAKTU_MULAI = '';
$WAKTU_SELESAI = '';
$DESKRIPSI = '';
$KETERANGAN = '';
$DETAIL_ID = '';
$NAMA_SALES = '';
$NAMA_KSP = '';
$NAMA_DEALER = '';
$ALAMAT_DEALER = '';
$KABUPATEN_DEALER = '';
$TELEPON_DEALER = '';

if($list){

  if(is_array($list->message)){

    foreach ($list->message as $key => $value) {

      $NO_HP = $value->NO_HP;
      $NO_TRANS = $value->NO_TRANS;
      $TGL_TRANS = tglfromSql($value->TGL_TRANS);
      $NO_SPK = $value->NO_SPK;
      $KD_DEALER = $value->KD_DEALER;
      $NAMA_CUSTOMER = $value->NAMA_CUSTOMER;
      $KD_CUSTOMER = $value->KD_CUSTOMER;
      $NO_RANGKA = $value->NO_RANGKA;
      $NO_MESIN = $value->NO_MESIN;
      $ID = $value->ID;
      $KD_SALES = $value->KD_SALES;
      $NAMA_AKTIVITAS = $value->NAMA_AKTIVITAS;
      $TIPE_AKTIVITAS = $value->TIPE_AKTIVITAS;
      $STATUS_AKTIVITAS = $value->STATUS_AKTIVITAS;
      $WAKTU_MULAI = tglfromSql($value->WAKTU_MULAI);
      $WAKTU_SELESAI = tglfromSql($value->WAKTU_SELESAI);
      $DESKRIPSI = $value->DESKRIPSI;
      $KETERANGAN = $value->KETERANGAN;
      $DETAIL_ID = $value->DETAIL_ID;
      $NAMA_SALES = $value->NAMA_SALES;
      $NAMA_KSP = $value->NAMA_KSP;
      $NAMA_DEALER = $value->NAMA_DEALER;
      $ALAMAT_DEALER = $value->ALAMAT_DEALER;
      $KABUPATEN_DEALER = $value->KABUPATEN_DEALER;
      $TELEPON_DEALER = $value->TELEPON_DEALER;

    }

  }

}

?>



    <header class="clearfix">


      <div id="header">
        <div><?php echo $NAMA_DEALER;?></div>
        <div><?php echo $ALAMAT_DEALER;?></div>
        <div><?php echo $KABUPATEN_DEALER;?></div>
        <div><?php echo $TELEPON_DEALER;?></div>
      </div>
      <!-- <div id="logo">
        <img src="assets/images/icon.png">
      </div> -->
      <h1><?php echo $NAMA_AKTIVITAS;?></h1>
      <table id="desc">
        <tbody>
          <tr>
            <td>
              <div id="project">
                <div><span class="title">Nomor Trans</span><span class="content"> <?php echo $NO_TRANS;?></span></div>
                <div><span class="title">Tanggal Trans</span><span class="content"> <?php echo $TGL_TRANS;?></span></div>
                <div><span class="title">Jadwal</span><span class="content"> <?php echo $WAKTU_MULAI.'-'.$WAKTU_SELESAI;?></span></div>
              </div>

            </td>
            <td>
              <div id="project">
                <div><span class="title">Kepada</span><span class="content"> <?php echo $NAMA_CUSTOMER;?></span></div>
                <div><span class="title">Telepon Customer</span><span class="content"> <?php echo $NO_HP;?></span></div>
                <!-- <div><span class="title">NPWP</span><span class="content"> </span></div> -->
              </div>
              <!-- <div id="company" class="clearfix">
                <div>Company Name</div>
                <div>455 Foggy Heights,<br /> AZ 85004, US</div>
                <div>(602) 519-0450</div>
                <div><a href="mailto:company@example.com">company@example.com</a></div>
              </div> -->
            </td>
          </tr>
        </tbody>
      </table>
    </header>
    <main>
      <table id="custom-table">
        <thead>
          <tr>
            <th rowspan="2">Nama Aktivitas</th>
            <th rowspan="2" class="service">Deskripsi</th>
            <th colspan="2">Status</th>
          </tr>
          <tr>
            <th>In Progress</th>
            <th>Completed</th>
          </tr>
        </thead>
        <tbody>

          <tr>
            <td class="service"><?php echo $NAMA_AKTIVITAS;?></td>
            <td class="service"><?php echo $DESKRIPSI;?></td>
            <td></td>
            <td></td>
          </tr>

        </tbody>
      </table>
      <div id="notices">
        <div>NOTICE:</div>
      </div>

      <table style="width: 100%">
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>

        <tr>
          <td style="width: 40%; text-align: center;">Sales CRM</td>
          <td style="width: 20%"></td>
          <td style="width: 40%; text-align: center;">KSP</td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
          <td style="width: 40%; text-align: center;"><u><?php echo str_replace("\'","'",$NAMA_SALES);?></u></td>
          <td style="width: 20%"></td>
          <td style="width: 40%; text-align: center;"><u><?php echo $NAMA_KSP? str_replace("\'","'",$NAMA_KSP) : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';?></u></td>
          <!-- <td style="width: 40%; text-align: center;"><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td> -->
        </tr>
      </table>

    </main>
    <footer>

      <!-- Document was created on a computer and is not valid without the signature and seal. -->
    </footer>
  
  </body>
</html>