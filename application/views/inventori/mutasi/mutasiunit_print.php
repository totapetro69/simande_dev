<style type="text/css">
   @page { size: landscape; font-family: courier }
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">SURAT JALAN MUTASI UNIT</h4>
</div>
<?php
  $kd_lr=isset($dlr)?$dlr:$this->session->userdata("kd_dealer");
  $namadle=NamaDealer($kd_lr);
  $alamat="";$alamat_tujuan="";
  $asal="";$tujuan="";$jenis_trans="";$gudange="";$gudang_tujuan="";
  $kota="";$dibuat_oleh="";
  if(isset($list)){
    if($list->totaldata >0){
      $asal         = $list->message[0]->KD_DEALER;
      $tujuan       = $list->message[0]->KD_DEALER_TUJUAN;
      $jenis_trans  = $list->message[0]->JENIS_TRANS;
      $gudange      = $list->message[0]->KD_GUDANG_ASAL;
      $gudang_tujuan = $list->message[0]->KD_GUDANG_TUJUAN;
      $dibuat_oleh  = $list->message[0]->CREATED_BY;
    }
  }
  $alamatasal="";
  $namadtu = NamaDealer($tujuan);
  if(isset($dealer)){
    if($dealer->totaldata>0){
      foreach ($dealer->message as $key => $value) {
        if($kd_lr==$value->KD_DEALER){
          $alamat = $value->ALAMAT_LENGKAP;
          $kota = $value->NAMA_KABUPATEN;
        }
        if($asal== $value->KD_DEALER){
          $alamatasal = $value->ALAMAT;
        }
        if($tujuan== $value->KD_DEALER){
          $alamat_tujuan = $value->ALAMAT_LENGKAP;
        }
      }
    }
  }
  $nama_gdg="";$alm_gdg="";
  $nama_gdg_t="";$alm_gdg_t="";
  if(isset($gudang)){
    if($gudang->totaldata >0){
      foreach ($gudang->message as $key => $value) {
        if($gudange== $value->KD_GUDANG){
          $nama_gdg = $value->NAMA_GUDANG;
          $alm_gdg = $value->ALAMAT;
        }
        if($gudang_tujuan == $value->KD_GUDANG){
          $nama_gdg_t = $value->NAMA_GUDANG;
          $alm_gdg_t  = $value->ALAMAT;
        }
      }
    }
  }
?>
<div class="modal-body" id="printarea">
  <table class="tablex" style="width:100%">
    <tr style="height: 45px;">
      <th  colspan="3" style="text-align: left ; vertical-align: middle ;"><h4>SURAT JALAN MUTASI UNIT</h4></th>
      <td rowspan="3" colspan="3" style="text-align: right ; font-size: small; vertical-align: middle ;"><?php echo "<b>".$namadle."</b><br>".str_replace("\\n\\r","<br>",sentence_case(($alamat)));?></td>
    </tr>
    <tr style="height: 30px">
      <td style="white-space: nowrap;">No Mutasi:</td>
      <td style="white-space: nowrap;" colspan="2"><?php echo (isset($no_trans))?$no_trans:"";?></td>
    </tr>

    <tr style="height: 30px">
      <td style="white-space: nowrap;">TglMutasi:</td>
      <td colspan="2">01-10-2018</td>
    </tr>

    <tr style="height: 30px">
      <td style="white-space: nowrap;" valign="top">Lokasi Asal:</td>
      <td colspan="3">&nbsp;</td>
      <td style="white-space: nowrap; text-align: left;" valign="top">Lokasi Tujuan:</td>     
    </tr>
    <tr style="height: 45px">
      <td>&nbsp;</td>
      <td colspan="3" style="width:40%" valign="top">
          <?php echo ($jenis_trans=='Antar Dealer')? $namadle:$nama_gdg;?><br>
          <?php echo ($jenis_trans=='Antar Dealer')? $alamatasal:$alm_gdg;?>
      </td>
      <!-- <td>&nbsp;</td> -->
      <td colspan="1" style="padding-left: 20px" valign="top">
          <?php echo ($jenis_trans=='Antar Dealer')? $namadtu:$nama_gdg_t;?><br>
          <?php echo ($jenis_trans=='Antar Dealer')? $alamat_tujuan:$alm_gdg_t;?>
      </td>
    </tr>
    <tr><td colspan="5">Ket : </td></tr>
      <tr style="height: 30px; border-bottom: 1px solid; background-color: silver">
        <td style="width:5%; text-align: center ; border-top: 1px solid grey ">No</td>
        <td style="width:15%;text-align: center ; border-top: 1px solid grey ">No Rangka</td>
        <td style="width:15%;text-align: center ; border-top: 1px solid grey ">No Mesin</td>
        <td style="width:10%;text-align: center ; border-top: 1px solid grey ">Kode</td>
        <td style="width:55%;text-align: center ; border-top: 1px solid grey ">Keterangan</td>
      </tr>
    <!-- </tdead> -->
    <tbody>
      <?php 
        $n=0;
        if(isset($list)){
          if($list->totaldata >0){
            foreach ($list->message as $key => $value) {
              $n++;
              $kd_item = explode(" ]",$value->KETERANGAN);
              $kd = explode("[",$kd_item[0]);
              ?>
                <tr style="height: 30px; border-bottom: 1px dotted grey">
                  <td style='text-align: center'><?php echo $n;?></td>
                  <td style="white-space: nowrap;"><?php echo $value->PART_NUMBER;?></td>
                  <td style="white-space: nowrap;"><?php echo NoMesin($value->PART_NUMBER);?></td>
                  <td style="white-space: nowrap;"><?php echo $kd[1];?></td>
                  <td style="white-space: nowrap;"><?php echo $kd_item[1];?></td>
                </tr>
              <?php
            }
          }
        }
      ?>
      
    </tbody>
    <tfoot style="border-top: 1px solid grey">
      <tr style="height: 32px" valign="middle">
        <td colspan="3" class='text-right' style="padding-right: 10px">TOTAL</td>
        <td colspan="2">&nbsp; <?php echo ($n);?> Unit</td>
      </tr>
      <tr style="height: 30px"><td colspan="5">&nbsp;</td></tr>
      <tr>
        <td colspan="2" align="center"  style="text-align: center;">Mengetahui</td>
        <td>&nbsp</td>
        <td colspan="2" align="center"  style="text-align: center; padding-left: 30px"><?php echo ucwords(strtolower($kota)).", ".date('D, d M Y');?><br>Di Buat Oleh</td>
      </tr>
      <tr style="height: 60px"; valign="bottom">
        <td colspan="2" align="center" style="text-align: center; text-decoration-line: overline; ">Kepala Dealer</td>
        <td>&nbsp;</td>
        <td colspan="2" align="center"  style="text-align: center; text-decoration-line: underline; "><?php echo NamaUser($dibuat_oleh);?></td>
      </tr>
    </tfoot>
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