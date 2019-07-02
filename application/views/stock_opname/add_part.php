<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );

$defaultgudang= ($this->input->get("kd_gudang"))?$this->input->get("kd_gudang"):"";
$defaultitem= ($this->input->get("kd_item"))?$this->input->get("kd_item"):"";
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";

$part_number = "";
$no_trans=$this->input->get('n'); 
$kd_gudang= "";
$kd_rakbin= "";
$nama_gudang= "";
$nama_item="";
$no_rangka= "";
$stock_awal= "";
$qty_stock= 0;
$qty_aktual= 0;
$tgl_trans= date("d/m/Y");

$kd_item = "";
$harga_jual = "";
$keterangan = "";
$kd_lokasidealer = "";
$jenis_item = "";

if (isset($list)) {
   if(($list->totaldata > 0)) {
      foreach ($list->message as $key => $value) {
         $no_trans = $value->NO_TRANS;
         $tgl_trans = TglFromSql($value->TGL_TRANS);
      }
   }
}
?>

<section class="wrapper">

   <div class="breadcrumb margin-bottom-10">
      <?php echo breadcrumb(); ?>
      <div class="bar-nav pull-right">
         <a class="btn btn-default" href="<?php echo base_url('stock_opname/add_stock'); ?>">
            <i class="fa fa-file-o fa-fw"></i> Baru
         </a>
         <a role="button" href="<?php echo base_url("stock_opname/list_part"); ?>" class="btn btn-default <?php echo $status_v; ?>">
            <i class="fa fa-list-ul"></i> List Opname
         </a>
      </div>
   </div>

   <div class="col-lg-12 padding-left-right-5 ">

      <div class="panel margin-bottom-5">
         <div class="panel-heading">
            <i class="fa fa-list-ul fa-fw"></i> Add Stock Opname Part
            <span class="tools pull-right">
               <a class="fa fa-chevron-down" href="javascript:;"></a>
            </span>
         </div>

         <div class="panel-body panel-body-border panel-body-10" style="display: block;">
            <form id="form_sop" method="post" action="<?php echo base_url("stock_opname/store_sopart"); ?>">
               <input class="form-control" id="kd_item" name="kd_item" value="<?php echo $kd_item;?>" type="hidden" required=""/>
               <input class="form-control" id="keterangan" name="keterangan" value="<?php echo $keterangan;?>" type="hidden" required=""/>
               <input class="form-control" id="harga_jual" name="harga_jual" value="<?php echo $harga_jual;?>" type="hidden" required=""/>
               <input class="form-control" id="kd_lokasidealer" name="kd_lokasidealer" value="<?php echo $kd_lokasidealer;?>" type="hidden" required=""/>
               <input class="form-control" id="jenis_opname" name="jenis_opname" value="PART" type="hidden" required=""/>
               <input class="form-control" id="jenis_item" name="jenis_item" value="<?php echo $jenis_item;?>" type="hidden" required=""/>

               <div class="row">
                  <div class="col-xs-6 col-md-3 col-sm-3">
                     <div class="form-group">
                        <label>Nama Dealer</label>
                        <select class="form-control " id="kd_dealer" name="kd_dealer" required="">
                          
                          <?php
                              if (isset($dealer)) {
                                  if ($dealer->totaldata > 0) {
                                      foreach ($dealer->message as $key => $value) {
                                          $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                                          $select = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $select;
                                          echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                                      }
                                  }
                              }
                          ?>
                        </select>

                     </div>
                  </div>

                  <div class="col-xs-6 col-md-3 col-sm-3">
                     <div class="form-group">
                        <label>Part Number <span class="detail-loading"></span></label>
                        <input class="form-control" id="part_number" name="part_number" placeholder="Part Number" value="<?php echo $part_number;?>" type="text"/>

                     </div>
                  </div>

                  <div class="col-xs-6 col-md-3 col-sm-3">
                     <div class="form-group">
                        <label class="control-label" for="date">Tanggal</label>
                        <div class="input-group input-append date">
                           <input class="form-control" id="tgl_trans" name="tgl_trans" placeholder="DD/MM/YYYY" value="<?php echo $tgl_trans;?>" type="text"/>
                           <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                     </div>
                  </div>
                  <div class="col-xs-6 col-md-3 col-sm-3">
                     <div class="form-group">
                        <label>No. Transaksi</label>
                        <input type="text" class="form-control" id="no_trans" autocomplete="off" name="no_trans" placeholder="AUTO NUMBER" value="<?php echo $no_trans;?>" readonly="true">
                     </div>
                  </div>

               </div>

               <div class="row">
                  <fieldset>
                     <div class="col-xs-6 col-md-3 col-sm-3">
                        <div class="form-group">
                           <label>Gudang</label>
                           <input type="text" class="form-control disabled-action" id="kd_gudang" name="kd_gudang" placeholder="Gudang" value="<?php echo $kd_gudang;?>" required="">

                        </div>
                     </div>
                     <div class="col-xs-6 col-md-3 col-sm-3">
                        <div class="form-group">
                           <label>Rakbin</label>
                           <input type="text" class="form-control disabled-action" id="kd_rakbin" name="kd_rakbin" placeholder="Rakbin" value="<?php echo $kd_rakbin;?>" required="">

                        </div>
                     </div>
                     <div class="col-xs-6 col-md-2 col-sm-2">
                        <div class="form-group">
                           <label>Stock</label>
                           <input type="text" class="form-control disabled-action" id="qty_stock" autocomplete="off" name="qty_stock" placeholder="Stock di Sistem" value="<?php echo $qty_stock;?>" required="">
                        </div>
                     </div>
                     <div class="col-xs-6 col-md-2 col-sm-2">
                        <div class="form-group">
                           <label>Aktual Stock</label>
                           <input type="text" class="form-control" id="qty_aktual" autocomplete="off" name="qty_aktual" placeholder="Stock Fisik" value="<?php echo $qty_aktual;?>" required="">
                        </div>
                     </div>
                     <div class="col-xs-6 col-md-2 col-sm-2">
                        <div class="form-group">
                           <br>
                           <button id="submit-btn" type='button' class="btn btn-info" ><i class='fa fa-plus'></i> Add</button>
                        </div>
                     </div>
                  </fieldset>
               </div>
            </form>
         </div>
      </div>
   </div>

   <div class="col-lg-12 padding-left-right-5 ">
      <div class="panel panel-default">
         <div class="table-responsive h350">
            <table id="detail_list" class="table table-hover table-striped table-bordered">
               
               <thead>
                  <tr>
                     <th style="width:4%">Aksi</th>
                     <th style="width:10%">No Part</th>
                     <th style="width:10%">Deskripsi</th>
                     <th style="width:10%">HET</th>
                     <th style="width:10%">Gudang</th>
                     <th style="width:10%">Rakbin</th>
                     <th style="width:8%">Qty System</th>
                     <th style="width:8%">Qty Fisik</th>
                     <th style="width:8%">Selisih</th>
                     <th style="width:8%">Amount</th>
                  </tr>
               </thead>

               <tbody>
                  <?php
                  if (isset($detail)) {
                     $no = 0;
                     if (($detail->totaldata >0 )) {
                        foreach ($detail->message as $key => $value) {
                           # code...
                           $no++;
                           $selisih = $value->QTY_AKTUAL - $value->QTY_STOCK;
                           $amount = $value->HARGA_JUAL * $selisih;
                           ?>



                           <tr id="">
                              <td class="table-nowarp text-center"><a id="<?php echo $value->ID; ?>" class="hapus-item" role="button"><i class="fa fa-trash"></i></a></td>
                              <td class="table-nowarp"><?php echo $value->KD_ITEM; ?></td>
                              <td class="table-nowarp"><?php echo $value->KETERANGAN; ?></td>
                              <td class="table-nowarp text-right qurency"><?php echo $value->HARGA_JUAL; ?></td>
                              <td class="table-nowarp text-center"><?php echo $value->KD_GUDANG; ?></td>
                              <td class="table-nowarp text-center"><?php echo $value->KD_RAKBIN; ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->QTY_STOCK,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->QTY_AKTUAL,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo $selisih; ?></td>
                              <td class="table-nowarp text-right qurency"><?php echo $amount; ?></td>
                           </tr>
                           <?php
                        }
                     }
                  }
                  ?> 
               </tbody>

            </table>
         </div>
      </div>
   </div>
<!-- </div>
</div> -->

<?php echo loading_proses();?>
</section>

<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function(){
   get_stock();

   $('#part_number').change(function(e){
      var allData=$(this).val();
      var items        = allData.split('|');
      
      var part_number  = items[0];
      var kd_gudang    = items[1];
      var kd_rakbin    = items[2];
      var kd_lokasi    = items[3];
      var jumlah       = items[4];
      var harga_jual   = items[5];
      var part_deskripsi   = items[6];
      var jenis_item   = items[7] == 'OIL'?'OLI':'PART';

      $('#kd_item').val(part_number);
      $('#kd_gudang').val(kd_gudang);
      $('#kd_rakbin').val(kd_rakbin);
      $('#kd_lokasidealer').val(kd_lokasi);
      $('#qty_stock').val(jumlah);
      $('#harga_jual').val(harga_jual);
      $('#keterangan').val(part_deskripsi);
      $('#jenis_item').val(jenis_item);
   });

   $("#submit-btn").click(function(){
      $("#form_sop").valid();
        $("#form_sop").validate({
            focusInvalid: false,
            invalidHandler: function(form, validator) {
                if (!validator.numberOfInvalids())
                    return;
                $('html, body').animate({
                    scrollTop: $(validator.errorList[0].element).offset().top
                }, 2000);
            }
        });
        if (jQuery("#form_sop").valid()) {
          storeData();
        }
   });

   $("#detail_list").on('click', '.hapus-item', function(){
      var detail_id = this.id;

      var defaultBtn = $(this).html();
      var url = "<?php echo base_url('stock_opname/delete_detail');?>";
      var result = confirm("Apakah anda yakin ingin menghapus data ini ?");

      $(".alert-message").fadeIn();
      $(this).html("<i class='fa fa-spinner fa-spin'></i>");


      if (result) {

         $(this).html("<i data-toggle='tooltip' data-placement='left' title='hapus' class='fa fa-spinner fa-spin text-danger text'></i>");
         $(".alert-message").fadeIn();

         $.getJSON(url,{'id':detail_id}, function(data, status) {


             if (data.status == true) {

               $('.success').animate({top: "0"}, 500);
               $('.success').html(data.message);
               get_stock();

               setTimeout(function() {
                  hideAllMessages();
                  $("#" + detail_id).parents("tr").remove();
               }, 2000);


             } else {
               $('.error').animate({top: "0"}, 500);
               $('.error').html(data.message);
               setTimeout(function() {
                  hideAllMessages();
                  $("#" + detail_id).html(defaultBtn);
               }, 2000);
             }

         });
      }
   });

});

function get_stock()
{
   var no_trans = $("#no_trans").val();
   var url = "<?php echo base_url('stock_opname/get_stock');?>";

   $('#part_number').inputpicker({
      url:url,
      urlParam:{"no_trans":no_trans},
      fields:['PART_NUMBER','KD_GUDANG','KD_RAKBIN','JUMLAH'],
      fieldText:'PART_NUMBER',
      fieldValue:'VALUE_ALL',
      filterOpen: true,
      headShow:true,
      pagination: true,
      pageMode: '',
      pageField: 'p',
      pageLimitField: 'per_page',
      limit: 15,
      pageCurrent: 1,
      urlDelay:2,
      delimiter:true
   });

}

function storeData()
{
   var defaultBtn = $("#submit-btn").html();
   var formData = $("#form_sop").serialize();
   var act = $("#form_sop").attr('action');

   $("#submit-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
   $(".alert-message").fadeIn();
   $(".form-control").removeAttr('disabled');
   $('#loadpage').removeClass("hidden");


   $.ajax({
      url: act,
      type: 'POST',
      data: formData,
      dataType: "json",
      success: function (result) {

         if (result.status == true) {
            $('.success').animate({top: "0"}, 500);
            $('.success').html(result.message);
            $("#no_trans").val(result.location);
            get_stock();
            var successCreate = create_table(result.dataid);


            $('#kd_item').val('');
            $('#kd_gudang').val('');
            $('#kd_rakbin').val('');
            $('#kd_lokasidealer').val('');
            $('#qty_stock').val(0);
            $('#harga_jual').val('');
            $('#keterangan').val('');

            if(successCreate == true){
               setTimeout(function () {
                  hideAllMessages();
                  $("#submit-btn").removeClass("disabled");
                  $("#submit-btn").html(defaultBtn);
                  $('#loadpage').addClass("hidden");
               }, 2000);
            }


         } else {
            $('.error').animate({top: "0"}, 500);
            $('.error').html(result.message);
            setTimeout(function () {
               hideAllMessages();
               $("#submit-btn").removeClass("disabled");
               $("#submit-btn").html(defaultBtn);
               $('#loadpage').addClass("hidden");
            }, 2000);
         }
      }
   })

}

function create_table(dataid)
{
   var html = '';
   // var no = $("#detail_list tbody tr").length + 1;

   var selisih = $("#qty_aktual").val() - $("#qty_stock").val();
   var amount = $("#harga_jual").val() * selisih;

   html += '<tr id="">'+
               // '<td class="text-center">'+no+'</td>'+
               '<td class="table-nowarp text-center"><a id='+dataid+' class="hapus-item" role="button"><i class="fa fa-trash"></i></a></td>'+
               '<td class="table-nowarp">'+$("#kd_item").val()+'</td>'+
               '<td class="table-nowarp">'+$("#keterangan").val()+'</td>'+
               '<td class="table-nowarp text-right qurency">'+Number($("#harga_jual").val())+'</td>'+
               '<td class="table-nowarp text-center">'+$("#kd_gudang").val()+'</td>'+
               '<td class="table-nowarp text-center">'+$("#kd_rakbin").val()+'</td>'+
               '<td class="table-nowarp text-center">'+$("#qty_stock").val()+'</td>'+
               '<td class="table-nowarp text-center">'+$("#qty_aktual").val()+'</td>'+
               '<td class="table-nowarp text-center">'+selisih+'</td>'+
               '<td class="table-nowarp text-right qurency">'+Number(amount)+'</td>'+
            '</tr>';
   
   // $('.qurency').mask('#.##0', {reverse: true});

   $("#detail_list tbody").append(html);
   
   $('#detail_list').floatThead('reflow')
   return true;
}

</script>