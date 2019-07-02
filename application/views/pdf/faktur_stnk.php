<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Faktur STNK</title>
    <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/css/pdf-style.css" > -->
    <link rel="stylesheet" href="assets/css/pdf-style.css" >
  </head>
  <body>
    <header class="clearfix">


      <div id="header">
        <div>PT. Mitradeka Mandiiri</div>
        <div>00104</div>
        <div>JAKARTA</div>
        <div>022-54312</div>
      </div>
      <!-- <div id="logo">
        <img src="assets/images/icon.png">
      </div> -->
      <h1>FAKTUR STNK</h1>
      <table id="desc">
        <tbody>
          <tr>
            <td>
              <div id="project">
                <div><span class="title">Nomor Faktur</span><span class="content"> INVU/16/02/0001</span></div>
                <div><span class="title">Tanggal Faktur</span><span class="content"> 02-Februari-2017</span></div>
              </div>

            </td>
            <td>
              <div id="project">
                <div><span class="title">Nama Pembeli</span><span class="content"> Bara</span></div>
                <div><span class="title">Alamat Pembeli</span><span class="content"> Serang - Banten, Sumber Jaya, Sumur, Kab Mandeglang, Banten (42283)</span></div>
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
            <th>NO</th>
            <th>NO STNK</th>
            <th>NO BPKB</th>
            <th>NO MESIN</th>
          </tr>
        </thead>
        <tbody>

        <?php 
        $no=1;
        $dpp=0;
        foreach($motors as $motor): ?>
          <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $motor['no_stnk']; ?></td>
            <td><?php echo $motor['no_bpkb']; ?></td>
            <td><?php echo $motor['no_mesin']; ?></td>
          </tr>
        <?php 
        $no++;
        endforeach; ?>
          
        </tbody>
      </table>
      <div id="notices">
        <div>NOTICE:</div>
        <!-- <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div> -->
      </div>
    </main>
    <footer>
      Invoice was created on a computer and is valid without the signature and seal.
    </footer>
  </body>
</html>