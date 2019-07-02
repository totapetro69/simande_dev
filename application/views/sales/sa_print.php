<?php

  $no_reff="";$nopol="";$customer="";$no_hp="";$alm_cust="";$tgl="";$nama_user="";
  $kd_dealerahm=""; $alamat =""; $nama_kabupaten=""; $tlp =""; $nama_dealer ="";$no_pkb=""; 
  $tgl_servis="";  $no_mesin=""; $no_rangka=""; $type=""; $tahun=""; $km=""; $nama_pembawa="";
  $alamat_pembawa=""; $kec_pembawa=""; $notel_pembawa=""; $nama_pemilik=""; $alamat_pemilik="";
  $kec_pemilik=""; $notel_pemilik=""; $sosmed=""; $email="";
  if(isset($dealer)){
    if($dealer->totaldata >0){
      foreach ($dealer->message as $key => $value) {
        $kd_dealerahm = $value->KD_DEALERAHM;
        $alamat = $value->ALAMAT;
        $tlp = $value->TLP;
        $nama_dealer = $value->NAMA_DEALER;

      }
    }
  }
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h5 class="modal-title" id="myModalLabel">Form Servis Advisor</h5>
</div>

<div class="modal-body" id="printarea">
  <table width="100%">
    <tr>
      <table border="0" width="100%">
        <tr style="border:1px solid">
          <td width="20%">Gambar</td>
          <th colspan="4"><center>AHASS <?php echo $kd_dealerahm;?>&nbsp;<?php echo $nama_dealer;?></center>
          <center><?php echo $alamat;?> </center>
          <center>BOOKING SERVICE : <?php echo $tlp;?></center></th>
          <td colspan="2">Gambar</td>
        </tr>
        <tr style="border:1px solid">
          <th colspan="7"><center>FORM SERVIS ADVISOR</center></th>
        </tr>
        <tr style="border: 0px">
          <td style="border-left:1px solid" colspan="2"><b>Data Motor</b></td>
          <td style="border-right:1px solid" colspan="4"><b>Data Pembawa</b></td>
        </tr>
        <tr style="border: 0px">
          <td style="border-left:1px solid">No. PKB</td>
          <td>: &nbsp;<?php echo $no_pkb;?></td>
          <td>Nama</td>
          <td>:</td>
          <td style="border-right:1px solid" colspan="2"><?php echo str_replace("\'","'",$nama_pembawa);?></td>
        </tr>
        <tr style="border: 0px">
          <td style="border-left:1px solid">Tanggal Servis</td>
          <td>: &nbsp;<?php echo $tgl_servis;?></td>
          <td>Alamat</td>
          <td>:</td>
          <td style="border-right:1px solid" colspan="2"><?php echo str_replace("\'","'",$alamat_pembawa);?></td>
        </tr>
        <tr style="border: 0px">
          <td style="border-left:1px solid">No. Mesin</td>
          <td>: &nbsp;<?php echo $no_mesin;?></td>
          <td>Kel / Kec</td>
          <td>:</td>
          <td style="border-right:1px solid" colspan="2"><?php echo $kec_pembawa;?></td>
        </tr>
        <tr style="border: 0px">
          <td style="border-left:1px solid">No. Rangka</td>
          <td>: &nbsp;<?php echo $no_rangka;?></td>
          <td>No. Telp/HP</td>
          <td>:</td>
          <td style="border-right:1px solid" colspan="2"><?php echo $notel_pembawa;?></td>
        </tr>
        <tr style="border: 0px">
          <td style="border-left:1px solid">No. Polisi</td>
          <td>: &nbsp;<?php echo $nopol;?></td>
          <td style="border-right:1px solid" colspan="5"></td>
        </tr>
        <tr style="border: 0px">
          <td style="border-left:1px solid">Type</td>
          <td>: &nbsp;<?php echo $type;?></td>
          <td style="border-right:1px solid" colspan="4"><b>Data Pemilik</b></td>
        </tr>
        <tr style="border: 0px">
          <td style="border-left:1px solid">Tahun</td>
          <td>: &nbsp;<?php echo $tahun;?></td>
          <td>Nama</td>
          <td>:</td>
          <td style="border-right:1px solid" colspan="2"><?php echo str_replace("\'","'",$nama_pemilik);?></td>
        </tr>
        <tr style="border: 0px">
          <td style="border-left:1px solid">KM</td>
          <td>: &nbsp;<?php echo $km;?></td>
          <td>Alamat</th>
          <td>:</td>
          <td style="border-right:1px solid" colspan="2"><?php echo str_replace("\'","'",$alamat_pemilik);?></td>
        </tr>
        <tr style="border: 0px">
          <td style="border-left:1px solid"><span style="color: red">*</span>Email</td>
          <td>: &nbsp;<?php echo $email;?></td>
          <td>Kel / Kec</td>
          <td>:</td>
          <td style="border-right:1px solid" colspan="2"><?php echo $kec_pemilik;?></td>
        </tr>
        <tr style="border-right: 0px">
          <td style="border-left:1px solid"><span style="color: red">*</span>Sosmed</td>
          <td>: &nbsp;<?php echo $sosmed;?></td>
          <td>No. Telp/HP</td>
          <td>:</td>
          <td style="border-right:1px solid" colspan="2"><?php echo $notel_pemilik;?></td>
        </tr>
        <tr style="border:1px solid">
          <th style="border-right:1px solid">Kondisi Awal SMH</th>
          <th style="border-right:1px solid">Pekerjaan</th>
          <th style="border-right:1px solid">Estimasi Biaya</th>
          <th style="border-right:1px solid" colspan="3">Analisa Service Advisor</th>
        </tr>
        <tr style="border-left:1px solid">
          <td style="border-right:1px solid" rowspan="13"><b>Catatan lain &nbsp;: </b><br>
            Dari Dealer Sendiri 
            <br>
            <br>
            <br>
            Hubungan Pembawa
            <br>
            <br>
            <br>
            <b>Alasan ke AHASS</b>
            <br>a. Inisiatif Sendiri
            <br>b. SMS Reminder
            <br>c. Telp. Reminder
            <br>d. Sticker Reminder
            <br>e. Lainnya
            <br><br>
            <br> Gambar
            <br>
            <br>
            <br>
          </td>
          <td style="border-right:1px solid">1.</td>
          <td style="border-right:1px solid">Rp.</td>
          <td style="border-right:1px solid" rowspan="13" colspan="3"></td>
        </tr>
        <tr style="border-bottom: 0px">
          <td style="border-right:1px solid">2.</td>
          <td style="border-right:1px solid">Rp.</td>
        </tr>
        <tr style="border-bottom: 0px">
          <td style="border-right:1px solid">3.</td>
          <td style="border-right:1px solid">Rp.</td>
        </tr>
        <tr style="border-bottom: 0px">
          <td style="border-right:1px solid">4.</td>
          <td style="border-right:1px solid">Rp.</td>
        </tr>
        <tr style="border-bottom: 0px">
          <td style="border-right:1px solid">5.</td>
          <td style="border-right:1px solid">Rp.</td>
        </tr>
        <tr>
          <td style="border-right:1px solid">6.</td>
          <td style="border-right:1px solid">Rp.</td>
        </tr>
        <tr style="border:1px solid">
          <th style="border-right:1px solid">Suku Cadang</th>
          <th style="border-right:1px solid">Estimasi Harga</th>
        </tr>
        <tr style="border-bottom: 0px">
          <td style="border-right:1px solid">1.</td>
          <td style="border-right:1px solid">Rp.</td>
        </tr>
        <tr style="border-bottom: 0px">
          <td style="border-right:1px solid">2.</td>
          <td style="border-right:1px solid">Rp.</td>
        </tr>
        <tr style="border-bottom: 0px">
          <td style="border-right:1px solid">3.</td>
          <td style="border-right:1px solid">Rp.</td>
        </tr>
        <tr style="border-bottom: 0px">
          <td style="border-right:1px solid">4.</td>
          <td style="border-right:1px solid">Rp.</td>
        </tr>
        <tr>
          <td style="border-right:1px solid">5.</td>
          <td style="border-right:1px solid">Rp.</td>
        </tr>
        <tr style="border:1px solid">
          <th style="border-right:1px solid">Total Harga</th>
          <td style="border-right:1px solid"></td>
        </tr>
      </table>
      <table border="1" width="100%">
        <tr>
          <td colspan="6"><b>Keluhan Konsumen</b></td>
        </tr>
        <tr style="height: 50px"><td colspan="6" rowspan="2"></td></tr>
      </table>
      <table width="100%">
        <tr>
          <td colspan="4">*Apabila ada tambahan <b>PEKERJAAN / PERGANTIAN PART</b> di luar daftar di atas maka &nbsp; : </td>
          <td colspan="2"><b>Syarat dan Ketentuan</b></td>
        </tr>
        <tr>
          <td colspan="2" align="left"><div style="width:10px;height:10px;border:1px solid #000;"></div>Konfirmasi dulu / telp ke</td>
          <td colspan="2" align="left"><div style="width:10px;height:10px;border:1px solid #000;"></div>Langsung di kerjakan</td>
          <td  style="font-size: 8px" colspan="2" align="left">1. Formulir ini adalah surat kuasa pekerjaan/PKB
            <br>2. Bengkel tidak bertanggung Jawab terhadap sepeda motor yang tidak diambil dalam 30 hari
            <br>3. Bengkel tidak bertanggung Jawab Jika terjadi Forco Majeure
          </td>
        </tr>
        <tr>
          <td colspan="2">Part bekas dibawa Konsumen : </td>
          <td><div style="width:10px;height:10px;border:1px solid #000;"></div>Ya </td>
          <td> <div style="width:10px;height:10px;border:1px solid #000;"></div>Tidak</td>
        </tr>
      </table>
      <table border="1" width="100%">
        <tr>
          <th colspan="2">Estimasi Pekerjaan Selesai</th>
          <td></td>
          <th>Tambahan Pekerjaan</th>
          <td></td>
          <td width="2%">Ok</td>
          <td width="8%"></td>
          <td></td>
          <th colspan="2">Penyerahan Motor Oleh SA</th>
        </tr>
        <tr>
          <th>
            <center>Konsumen Ttd</center>
            <br><br>
          </th>
          <th>
            <center>Service Advisor Ttd</center>
            <br><br>
          </th>
          <td></td>
          <th>
            <center>Konsumen Ttd</center>
            <br><br>
          </th>
          <td></td>
          <th colspan="2">
            <center>Paraf Final Ins</center>
            <br><br>
          </th>
          <td></td>
          <th>
            <br>OK<br>
          </th>
          <th>
            <center>Konsumen Ttd</center>
            <br><br>
          </th>
        </tr>
      </table>

      <table border="1" width="100%">
        <tr>
          <td colspan="6"><b>Saran Mekanik</b></td>
        </tr>
        <tr style="height: 60px">
          <td width="80%" colspan="5"></td>
          <td width="20%" style="font-size: 9px"><br>Nama Mekanik : </td>
        </tr>
      </table>

      <table width="80%">
        <tr>
          <td colspan="6">Garansi : </td>
        </tr>
        <tr>
          <td colspan="2" align="left">-500 Km/1 minggu untuk Servis Reguler</td>
          <td>No. Pendaftaran</td>
          <td>:</td>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2">-1.000 Km/1 Bulan untuk Bongkar Mesin Reguler<br>
              -1.000 Km/1 Bulan untuk Servis CBR 250 dan PCX 150</td>
          <td>Estimasi waktu mulai</td>
          <td>:</td>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="2">-1.500 Km/45 Hari untuk Bongkar Mesin CBR 250 dan PCX 150</td>
          <td>Estimasi waktu selesai</td>
          <td>:</td>
          <td colspan="2"></td>
        </tr>
        <tr>
          <d colspan="5" align="left"><b>SERVIS RUTIN DI AHASS MOTOR TERAWAT KANTONG HEMAT</b></td>
        </tr>
      </table>
    </tr>
  </table>

      
</div>
<div class="modal-footer">
    
    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button type="button" onclick="printKw();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>

</div>
<script src="<?php echo base_url('assets/dist/print.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#print_kwts').hide();
    $('#myModalLg').on("hidden.bs.modal",function(){
      $('#print_kwts').show();
    })
  })
   function printKw() {
      printJS('printarea','html');
       $('#keluar').click();
    }
</script>