<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>LHB</title>

   <style type="text/css">
   /** Define now the real margins of every page in the PDF **/
   body {
      margin-top: 3cm;
      margin-bottom: 2cm;
   }

   /** Define the header rules **/
   header {
      position: fixed;
      top: 0cm;
      left: 0cm;
      right: 0cm;

      text-align: center;

      border-top: 0px solid  #5D6975;
      border-bottom: 0px solid  #5D6975;
      color: #5D6975;
      line-height: 1.4em;
      font-weight: normal;
      margin: 5px 0 5px 0;
      /*background: url("../images/dimension.png");*/

      min-height: 150px;
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
      width: 100px;
      height: 100px;
      float: left; 
      /*background: url("../images/honda_logo.png");*/
      background-size: 100px;
   }

   #header-right
   {
      width: 100px;
      height: 100px;
      float: right; 
      /*background: url("../images/ahass.png");*/
      background-size: 100px;
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
        width: 90px;
        font-size: 10px;
    }

    .project span {
        text-align: left;
        padding: 2px 0;
        display: table-cell;
    }

    .project .content {
        width: 200px;
        font-size: 10px;
    }

    .project p{
      margin: 2px 0;
    }

    .page-break-before{
      page-break-before: always;
    }

    .page-break-after{
      page-break-after: always;
    }
    main{
      font-size: 11px;
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
      font-size: 10px;
    }

    td.title{
      font-size: 11px;
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
$nama_dealer=""; $tlp=""; $nama_kabupaten=""; $alamat=""; $kd_dealerahm="";$nama_user=""; $no=""; $tgl="";

  if(isset($dealer)){
    if($dealer->totaldata >0){
      foreach ($dealer->message as $key => $value) {
        $kd_dealerahm = $value->KD_DEALERAHM;
        $alamat = $value->ALAMAT;
        $nama_kabupaten = NamaWilayah("Kabupaten",$value->KD_KABUPATEN);
        $tlp = $value->TLP;
        $nama_dealer = $value->NAMA_DEALER;
      }
    }
  }

  $nama_user = NamaUser($this->session->userdata("user_id"));
?>

<header class="clearfix">
  <div id="header" style="width:750px; display: flex !important; align-items:center;">
    <table style="border: none;" id="desc" >
      <tr>
        <td style="text-align: left;  width:200px !important;"><span style="font-size: 7px;">Nama Dealer : &nbsp; &nbsp; <?php echo $nama_dealer;?>
        <br> Alamat : &nbsp; &nbsp; <?php echo $alamat;?> <br> No. Telp : &nbsp; &nbsp; <?php echo $tlp;?></span></td>
        <td style="text-align: center; width:250px !important;"><h4 style="margin:0 0 5px 0;"><strong><u>SURAT PESANAN KENDARAAN</u></strong></h4></td>
        <td style="text-align: right; width:150px !important;"><span style="font-size: 7px;">Nomor : &nbsp; &nbsp; <?php echo $no;?> <br> Tanggal : &nbsp; &nbsp; <?php echo $tgl;?></span></td>
      </tr>
    </table>
  </div>
</header>
<main>
  <form>
    <div style="width:750px; display: flex !important; align-items:center;">
      <table>
        <tr>
          <td>Nama Pemesanan</td>
          <td>:</td>
          <td></td>
          <td style="text-align: right; width:300px !important;">BPKB atas nama</td>
          <td>:</td>
          <td></td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td>:</td>
          <td></td>
          <td style="text-align: right; width:300px !important;">Alamat</td>
          <td>:</td>
          <td></td>
        </tr>
        <tr><td></td></tr>
        <tr>
          <td>Telepon / HP</td>
          <td>:</td>
          <td></td>
        </tr>
        <tr>
          <td>NPWP / No. KTP</td>
          <td>:</td>
          <td></td>
        </tr>
        <tr>
          <td>Pembelian</td>
          <td>:</td>
          <td> (    )     CASH / TUNAI    (   )KREDIT</td>
        </tr>
      </table>
    </div>
    <div style="width:550px; display: flex !important; align-items:center;">
      <table border='1' >
        <thead>
          <tr>
          <td>UNIT</td>
          <td>TYPE</td>
          <td>WARNA</td>
          <td>HARGA</td>
          <td>JUMLAH</td>
          <td>SYARAT KETENTUAN</td>
        </tr>
        </thead>
        <tbody>
            <td style="white-space: nowrap !important;"></td>
            <td style="white-space: nowrap !important;"></td>
            <td style="white-space: nowrap !important;"></td>
            <td style="white-space: nowrap !important;"></td>
            <td style="white-space: nowrap !important;"></td>
            <td rowspan="2" style="white-space: nowrap !important;"> 1. HARGA yang tercantum dalam Surat Pesanan ini mengikat jika 100% harga kendaraan lunas<br>di bayar oleh pemesanan. <br> 2. Surat Pesanan ini BUKAN merupakan BUKTI PEMBAYARAN. <br> 3. Surat Pesanan ini dianggap SAH, Apabila : <br>
              &nbsp; &nbsp; &nbsp; - Telah ditanda tangani Pemesan.<br>
              &nbsp; &nbsp; &nbsp; - Telah ditanda tangani oleh Kepala Cabang / Pimpinan Dealer. <br> 4. Proses Pengurusan surat kendaraan dan pengiriman akan dilaksanakan setelah 100% harga kendaraan lunas. <br> 5. NAMA pada BPKB yang tercantum dalam surat pesanan ini TIDAK DAPAT BERUBAH.</td>
          </tr>
          <tr>
            <td style="white-space: nowrap !important;"></td>
            <td style="white-space: nowrap !important;"></td>
            <td style="white-space: nowrap !important;"></td>
            <td style="white-space: nowrap !important;"></td>
            <td style="white-space: nowrap !important;"></td>
          </tr>

          <tr>
            <td style="white-space: nowrap !important;"></td>
            <td style="white-space: nowrap !important;"></td>
            <td style="white-space: nowrap !important;"></td>
            <td style="white-space: nowrap !important;"></td>
            <td style="white-space: nowrap !important;"></td>
          </tr>

          <tr>
            <td colspan="4">TOTAL</td>
            <td style="white-space: nowrap !important;"></td>
            <td rowspan='2'>
      <table>
        <tr>
          <td style="text-align: left; !important;"> Sales People <br><br><br>(............)</td>
          <td colspan="5" style="text-align: right; !important;"> Pemesan <br><br><br>(............)</td>
        </tr>
        <tr>
          <td style="text-align: center; !important;"> Kacab/SPV <br><br><br>(............)</td>
        </tr>
      </table>
    </td>
          </tr>
          <tr>
            <td colspan="5">
              <table>
                <tr>
                  <td>KOMPOSISI KREDIT</td>
                </tr>
                <tr>
                  <td>Uang Muka </td>
                  <td>:</td>
                  <td colspan="3"></td>
                </tr>
                <tr>
                  <td>Jangka Waktu</td>
                  <td>:</td>
                  <td></td>
                </tr>
                <tr>
                  <td>Angsuran</td>
                  <td>:</td>
                  <td></td>
                </tr>
                <tr>
                  <td>Fincoy</td>
                  <td>:</td>
                  <td></td>
                </tr>
                <tr><td></td></tr>
                <tr>
                  <td>Hadiah</td>
                  <td>:</td>
                  <td></td>
                </tr>
                <tr><td></td></tr>
                <tr>
                  <td>Perlengkapan</td>
                  <td>:</td>
                  <td>Helm/Tools set/</td>
                </tr>
                <tr>
                  <td>Alamat Pengantaran</td>
                  <td>:</td>
                  <td></td>
                </tr>
                <br>
                <tr>
                  <td>CATATAN : <br>
                  Saya menyetujui membayar biaya kompensasi sebesar Rp. 100.000 (seratus ribu rupiah) jika terjadi pembatalan atas SPK</td>
                </tr>
              </table>
            </td> 
          </tr>
        </tbody>
      </table>
    </div>
  </form>
</main>

<footer>
  <div class="project">
  </footer>
</body>
</html>