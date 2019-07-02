<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Faktur Penjualan</title>
    <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/css/pdf-style.css" > -->
    <link rel="stylesheet" href="assets/css/pdf-style.css" >
  </head>
  <body>
    <header class="clearfix">


      <div id="header">
        <div><?php echo $dealer->message[0]->NAMA_DEALER;?></div>
        <div><?php echo $dealer->message[0]->ALAMAT;?></div>
        <div><?php echo $dealer->message[0]->NAMA_KABUPATEN;?></div>
        <div><?php echo $dealer->message[0]->TLP;?></div>
      </div>
      <!-- <div id="logo">
        <img src="assets/images/icon.png">
      </div> -->
      <h1>Faktur Penjualan</h1>
      <table id="desc">
        <tbody>
          <tr>
            <td>
              <div id="project">
                <div><span class="title">Nomor Faktur</span><span class="content"> <?php echo $motors->message[0]->FAKTUR_PENJUALAN;?></span></div>
                <div><span class="title">Tanggal Faktur</span><span class="content"> <?php echo LongTgl(tglfromSql($motors->message[0]->TGL_SPK));?></span></div>
                <div><span class="title">Pembayaran</span><span class="content"> <?php echo $motors->message[0]->TYPE_PENJUALAN;?></span></div>
              </div>

            </td>
            <td>
              <div id="project">
                <div><span class="title">Kepada</span><span class="content"> <?php echo str_replace("\'","'",$motors->message[0]->NAMA_PENERIMA);?></span></div>
                <div><span class="title">Alamat Pembeli</span><span class="content"> <?php echo ucwords(strtolower(str_replace("\'","'",$motors->message[0]->ALAMAT_KIRIM)));?></span></div>
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
      <table id="content">
        <thead>
          <tr>
            <th>No</th>
            <th class="service">Keterangan</th>
            <th>No Rangka</th>
            <th>No Mesin</th>
            <th>Harga Satuan</th>
            <th>JUMLAH</th>
          </tr>
        </thead>
        <tbody>

        <?php 
        $no=1;
        if($motors && (is_array($motors->message) || is_object($motors->message))):
        foreach($motors->message as $motor): 
          $HARGA_OTR = $motor->HARGA_OTR + $motor->DISKON;
        ?>
          <tr>
            <td style="text-align: center;"><?php echo $no; ?></td>
            <td class="service">1 Unit <?php echo $motor->NAMA_ITEM; ?></td>
            <td><?php echo $motor->VNORANGKA1.$motor->NO_RANGKA; ?></td>
            <td><?php echo $motor->NO_MESIN; ?></td>
            <td><?php echo number_format($HARGA_OTR,2); ?></td>
            <td><?php echo number_format($HARGA_OTR,2); ?></td>
          </tr>
        <?php 
        $no++;
        endforeach; 
        endif;

        ?>
          
          <tr>
            <td colspan="5"><?php echo $motors->message[0]->TOT_QTY; ?> Unit</td>
            <td style="border-top: solid 1px"><?php echo number_format($total_bayar,2); ?></td>
          </tr>

          <tr>
            <td colspan="5">Potongan AHM</td>
            <td>(<?php echo number_format($pot_ahm,2); ?>)</td>
          </tr>
          <tr>
            <td colspan="5">Potongan MD</td>
            <td>(<?php echo number_format($pot_md,2); ?>)</td>
          </tr>
          <tr>
            <td colspan="5">Potongan Dealer</td>
            <td>(<?php echo number_format($pot_d,2); ?>)</td>
          </tr>

          <tr>
            <td colspan="5" style="font-size: 1.3em;"><strong>Jumlah Bayar</strong></td>
            <td style="font-size: 1.3em; border-bottom: double; border-top: solid 1px;"><strong><?php echo number_format($total,2);?></strong></td>
          </tr>

          <tr>
            <td colspan="5">Biaya STNK</td>
            <td>(<?php echo number_format($biaya_stnk,2); ?>)</td>
          </tr>

          <tr>
            <td colspan="5">Dasar Pengenaan Pajak</td>
            <td><?php echo number_format(round($dpp, 0),2); ?></td>
          </tr>

          <tr>
            <td colspan="5">Pajak Pertambahan Nilai (PPN) 10%</td>
            <td><span class="biaya"><?php echo number_format(round($ppn, 0),2); ?></span></td>
          </tr>
        </tbody>
      </table>
      <div id="notices">
        <div>NOTICE:</div>
        <!-- <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div> -->
      </div>

      <table style="width: 100%">
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
          <td style="width: 40%; text-align: center;"><u><?php echo str_replace("\'","'",$motors->message[0]->NAMA_PENERIMA);?></u></td>
          <td style="width: 20%"></td>
          <td style="width: 40%; text-align: center;"><u>PIMPINAN</u></td>
        </tr>
      </table>

    </main>
    <footer>
      Invoice was created on a computer and is valid without the signature and seal.
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