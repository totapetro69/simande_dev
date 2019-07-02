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
$NAMA_KABUPATEN = '';
$FAKTUR_PENJUALAN = '';
$TGL_SURATJALAN = date('d-m-Y');
$TGL_SO = date('d-m-Y');
$NAMA_PENERIMA = '';
$ALAMAT_KIRIM = '';

$NAMA_SALESPROGRAM = '';
$NO_RANGKA = '';
$NO_MESIN = '';

$JML_SUBSIDI = 0;
$JML_SUBSIDI_TERBILANG = 'NOL';

$JUMLAH_HADIAH = '';
$NAMA_PROGRAM = '';
$NAMA_HADIAH = '';
$START_DATE = '';
$END_DATE = '';
$NO_HP = '';



if($motors){

  if(is_array($motors->message)){

    foreach ($motors->message as $key => $value) {
      $NAMA_DEALER = $value->NAMA_DEALER;
      $ALAMAT_DEALER = $value->ALAMAT;
      $NAMA_KABUPATEN =  $value->NAMA_KABUPATEN;
      $FAKTUR_PENJUALAN = $value->FAKTUR_PENJUALAN;
      // $TGL_SURATJALAN = tglfromSql($value->TGL_SURATJALAN);
      $NAMA_PENERIMA = $value->NAMA_PENERIMA;
      $ALAMAT_KIRIM = $value->ALAMAT_KIRIM;

      $NAMA_SALESPROGRAM = $value->NAMA_SALESPROGRAM;
      $NO_RANGKA = $value->NO_RANGKA;
      $NO_MESIN = $value->NO_MESIN;
      $TGL_SO = tglfromSql($value->TGL_SO);


      $JML_SUBSIDI = $subsidi;
      $JML_SUBSIDI_TERBILANG = ($subsidi_terbilang != '')?$subsidi_terbilang.' Rupiah':'Nol Rupiah';

      $JUMLAH_HADIAH = $value->JUMLAH_HADIAH;
      $NAMA_PROGRAM = $value->NAMA_PROGRAM;
      $NAMA_HADIAH = $value->NAMA_HADIAH;
      $START_DATE = tglfromSql($value->START_DATE);
      $END_DATE = tglfromSql($value->END_DATE);
      $NO_HP = $value->NO_HP;


    }

  }

}

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">BAST HADIAH</h4>
</div>

<div class="modal-body">

  <div id="printarea">
    <table id="desc" class="">
      <tr>
        <td colspan="4">
          <h3><strong><?php echo $NAMA_DEALER;?></strong></h3>
          <P><?php echo $ALAMAT_DEALER;?></h3>
          <P></P>
        </td>
      </tr>

      <tr><td colspan="4">&nbsp;</td><td></td></tr></tr>

      <tr>
        <th class="text-center" colspan="4">
          <h3><strong>BAST HADIAH</strong></h3>
          <p><strong><?php echo $NAMA_PROGRAM;?></strong></p>
          <p><strong>Periode : <?php echo $START_DATE.' s/d '.$END_DATE;?></strong></p>
        </th>
      </tr>


      <tr><td colspan="4">&nbsp;</td><td></td></tr></tr>

      <tr>
        <td colspan="4" valign="top">
            <div class="project">
              <div><span class="title" style="width: 40%;"><strong>DATA KONSUMEN</strong></span></div>
              <div><span class="title" style="width: 40%;">Nama</span><span class="content">: <?php echo $NAMA_PENERIMA;?></span></div>
              <div><span class="title" style="width: 40%;">Alamat</span><span class="content">: <?php echo $ALAMAT_KIRIM;?></span></div>
              <div><span class="title" style="width: 40%;">No. Telp/HP</span><span class="content">: <?php echo $NO_HP;?></span></div>
            </div>
        </td>
      </tr>

      <tr>
        <td colspan="4">Menyatakan telah diterima dari Dealer <?php echo $NAMA_DEALER;?></td>
      </tr>

      <tr>
        <td colspan="4" valign="top">
            <div class="project">
              <div><span class="title" style="width: 40%;">Hadiah berupa</span><span class="content">:</span></div>
              <div><span class="title" style="width: 40%;"></span><span class="content"><ul><li><?php echo $NAMA_HADIAH;?></li></ul></span></div>
              <!-- <div><span class="title" style="width: 40%;"></span><span class="content"><?php echo $JUMLAH_HADIAH.' '.$NAMA_HADIAH;?></span></div> -->
            </div>
        </td>
      </tr>

      <tr><td colspan="4">&nbsp;</td></tr>
      <tr><td colspan="4">&nbsp;</td></tr>




      <tr>
        <td colspan="4">
            <div class="project">
              <div>
                <span class="title" style="text-align: center;">Yang Menerima,</span>
                <span class="title" style="text-align: center;"><?php echo $NAMA_KABUPATEN.', '.$TGL_SO;?></span> 
              </div>
            </div>
        </td>
      </tr>

      <tr>
        <td colspan="4">
            <div class="project">
              <div>
                <span class="content" style="text-align: center; padding-top: 80px;"><u><?php echo $NAMA_PENERIMA;?></u></span>
                <span class="content" style="text-align: center; padding-top: 80px;"><u>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <!-- <?php echo $NAMA_DEALER;?> --></u></span>
              </div>
            </div>
        </td>
      </tr>
    </table>
  </div>


  <footer class="panel-footer">

      <div class="row">

          <div class="col-sm-5">
              <small class="text-muted inline m-t-sm m-b-sm"> 
                  <?php echo ($motors) ? ($motors->totaldata == '' ? "" : "<i>Total Data " . $motors->totaldata . " items</i>") : '' ?>
              </small>
          </div>

          <div class="col-sm-7 text-right text-center-xs">                
              <?php echo $pagination; ?>
          </div>

      </div>

  </footer>

</div>
<div class="modal-footer">
    
    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button type="button" onclick="printSj();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>

</div>

<script src="<?php echo base_url('assets/dist/print.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){

    $(".modal-body .pagination li a").click(function(e){
      e.preventDefault();

      var url = $(this).attr('href');

      var modalId = $(this).parents('.modal').attr('id');



      $.getJSON(url, function(data, status) {

        // console.log(data);

          $("#"+modalId).find(".modal-content").html(data);


      });

      // $(this).removeAttr('href');
      // alert(modalId);
    });
  })

   function printSj() {
      printJS('printarea','html');
       $('#keluar').click();
    }
</script>