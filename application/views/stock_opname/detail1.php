<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );

$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$defaultgudang= ($this->input->get("kd_gudang"))?$this->input->get("kd_gudang"):"";
$defaultitem= ($this->input->get("kd_item"))?$this->input->get("kd_item"):"";
$defaultLokasi=($this->input->get("kd_lokasi"))?$this->input->get("kd_lokasi"):$this->session->userdata("kd_lokasi");
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";

$kd_item = "";$no_trans=$this->input->get('n'); 
$kd_gudang= "";
$nama_gudang= "";
$nama_item="";
$no_rangka= "";
$stock_awal= "";
$stock_akhir= "";
$tgl_trans= date("d/m/Y");

if (isset($list)) {
   if(($list->totaldata > 0)) {
      foreach ($list->message as $key => $value) {
         $defaultDealer = $value->KD_DEALER;
         $defaultLokasi = $value->KD_LOKASIDEALER;
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
         <a class="btn btn-default" href="<?php echo base_url('stock_opname/detail1'); ?>">
            <i class="fa fa-file-o fa-fw"></i> Baru
         </a>
         <a id="submit-btn"  class="btn btn-default <?php echo $status_c; ?>" role="button" onclick="__simpanData();">
            <i class="fa fa-list-alt fa-fw"></i> Simpan
         </a>
         <a role="button" href="<?php echo base_url("stock_opname/header1"); ?>" class="btn btn-default <?php echo $status_v; ?>">
            <i class="fa fa-list-ul"></i> List Opname
         </a>
      </div>
   </div>

   <div class="col-lg-12 padding-left-right-5 ">

      <div class="panel margin-bottom-5">
         <div class="panel-heading">
            <i class="fa fa-list-ul fa-fw"></i> Stock Opname Detail H1
            <span class="tools pull-right">
               <a class="fa fa-chevron-down" href="javascript:;"></a>
            </span>
         </div>

         <div class="panel-body panel-body-border panel-body-10" style="display: block;">
            <form id="form_sod" method="post" action="<?php echo base_url("stock_opname/detail1"); ?>">
               <div class="row">
                  <div class="col-xs-6 col-md-3 col-sm-3">
                     <div class="form-group">
                        <label>Nama Dealer</label>
                        <select class="form-control " id="kd_dealer" name="kd_dealer">
                           <option value="">--Pilih Dealer--</option>
                           <?php
                           if($dealer){
                              if($dealer->totaldata >0){
                                 foreach ($dealer->message as $key => $value) {
                                    $select=($defaultDealer==$value->KD_DEALER)?"selected":"";
                                    echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
                                 }
                              }
                           }
                           ?>
                        </select>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-3 col-sm-3">
                     <div class="form-group">
                        <label>Lokasi Dealer</label>
                        <select class="form-control " id="kd_lokasi" name="kd_lokasi">
                           <option value="0">--Pilih Lokasi Dealer--</option>
                           <?php
                           if($lokasidealer){
                              if($lokasidealer->totaldata >0){
                                 foreach ($lokasidealer->message as $key => $value) {
                                    $select=($defaultLokasi==$value->KD_LOKASI)?"selected":"";
                                    echo "<option value='".$value->KD_LOKASI."' ".$select.">".$value->NAMA_LOKASI." [". $value->KD_LOKASI."]</option>";
                                 }
                              }
                           }
                           ?>
                        </select>
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
                           <select class="form-control " id="kd_gudang" name="kd_gudang">
                              <option value="0">--Pilih Gudang--</option>
                              <?php
                              if(isset($gudang)){
                                 if(($gudang->totaldata >0)){
                                    foreach ($gudang->message as $key => $value) {
                                       $select=($defaultgudang==$value->KD_GUDANG)?"selected":$select;
                                       echo "<option value='".$value->KD_GUDANG."' ".$select.">".$value->NAMA_GUDANG."</option>";
                                    } 
                                 }
                              }
                              ?>
                           </select>
                        </div>
                     </div>

                     <div class="col-xs-6 col-md-3 col-sm-3">
                        <div class="form-group">
                           <label>Kode Item <span id="lgd"></span></label>
                           <input type="text" id="kd_item" name="kd_item" class='form-control'>
                           <input type="hidden" name="nama_item" id="nama_item">
                           <input type="hidden" name="jenis_opname" id="jenis_opname" value='UNIT'>
                        </div>
                     </div>
                     <div class="col-xs-6 col-md-2 col-sm-2">
                        <div class="form-group">
                           <label>Stock</label>
                           <input type="text" class="form-control disabled-action" id="qty_stock" autocomplete="off" name="qty_stock" placeholder="" value="0">
                        </div>
                     </div>
                     <div class="col-xs-6 col-md-2 col-sm-2">
                        <div class="form-group">
                           <label>Aktual Stock</label>
                           <input type="text" class="form-control" id="qty_aktual" autocomplete="off" name="qty_aktual" placeholder=" " value="">
                        </div>
                     </div>
                     <div class="col-xs-6 col-md-2 col-sm-2">
                        <div class="form-group">
                           <br>
                           <button id="submit-btn" type='button' onclick="__addItem();" class="btn btn-info" ><i class='fa fa-plus'></i> Add</button>
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
                     <th style="width:4%">No</th>
                     <th style="width:4%">Aksi</th>
                     <th style="width:10%">Gudang</th>
                     <th style="width:12%">No Mesin</th>
                     <th style="width:25%">Nama Item</th>
                     <th style="width:8%">Stock Akhir</th>
                     <th style="width:8%">Aktual Stock</th>
                     <th style="width:8%">Selisih</th>
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
                           ?>
                           <tr id="l_<?php echo $value->ID;?>">
                              <td class="text-center"><?php echo $no; ?></td>
                              <td class="table-nowarp text-center"><a class='hapus-item' onclick="__hapus_item('<?php echo $value->ID;?>');" role='button'><i class='fa fa-trash'></i></a></td>
                              <td class="table-nowarp text-center"><?php echo $value->KD_GUDANG; ?></td>
                              <td class="table-nowarp text-center"><?php echo $value->KD_ITEM; ?></td>
                              <td class="table-nowarp text-center"><?php echo $value->KETERANGAN; ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->QTY_STOCK,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->QTY_AKTUAL,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo ($value->QTY_STOCK-$value->QTY_AKTUAL) ?></td>
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
      $("#kd_gudang").change(function(){
         $("#lgd").html("<i class='fa fa-spinner fa-spin'></i>");
         var item = $(this).val();
         var url = http+'/stock_opname/get_item';
         var datax=[];
         $.getJSON(url,{'kd_gudang':item }, function(data){
            if(data.totaldata >0){
               $.each(data.message,function(e,d){
                  datax.push({
                     'NORANGKA': d.NO_RANGKA,
                     'NOMESIN' : d.NO_MESIN,
                     'STOCK'   : d.STOCK_AKHIR,
                     'KDITEM'  : d.KD_ITEM,
                     'NAMAITEM': d.NAMA_ITEM
                  })
               })
            }
            //console.log(datax);
            
            $('#kd_item').inputpicker({
               data : datax,
               fields :['NOMESIN','STOCK','KDITEM','NAMAITEM'],
               fieldValue : 'NOMESIN',
               fieldText :'NOMESIN',
               filterOpen: true,
               headShow:true
            }).on("change",function(e){
               e.preventDefault();
               var dx=datax.findIndex(obj => obj['NOMESIN'] === $(this).val());
               $('#nama_item').val("[ "+datax[dx]["KDITEM"]+" ] "+datax[dx]["NAMAITEM"]);
               $('#qty_stock').val(datax[dx]["STOCK"]);
               $('#qty_aktual').val('1').focus().select();
            })
            $("#lgd").html('');
         });
      });
      $("#no_rangka").on('change',function(){
         var qty_stock = $(this).val();
         var key = $(this).data("key");
      });
      $('.hapus-item').click(function(){
         var detailId = this.id;
         if(detailId != ''){
            $.getJSON(http+'/stock_opname/delete_stock_opname_detail',{id:detailId}, function(data, status) {
               if (data.status == true) {
                  $("#"+detailId).parents('tr').remove();
               }
            });
         }
      });

      $('#baru').click(function () {
         document.location.reload();
      })
   });

   function __addItem(){
      var gudang = $('#qty_aktual').val();
      var jmlbaris=$("#detail_list > tbody >tr").length;
      if(!gudang){
         alert('Tidak ada data yang dipilih');
         return false;
      }
      var selisih = 0;
      selisih = parseInt($('#qty_aktual').val())-parseInt($('#qty_stock').val());
      var html = '';
      html += '<tr>';
      html += "<td class='text-right'>"+(jmlbaris+1)+"</td> ";
      html += "<td class='text-center'><a class='hapus-item' role='button'><i class='fa fa-trash'></i></a></td>";
      html += '<td>'+$('#kd_gudang').val()+'</td>';//gudang
      html += '<td class="table-nowarp">'+$('#kd_item').val()+'</td>';//kd_item
      html += '<td class="table-nowarp">'+$('#nama_item').val()+'</td>';//kd_item
      html += '<td class="text-center">'+$('#qty_stock').val()+'</td>';//stock
      html += '<td class="text-center">'+$('#qty_aktual').val()+'</td>';//A'stock
      html += '<td class="text-center">'+selisih+'</td>';//Selisih
      //html += '<td class="text-center">'+$('#harga_jual').val()+'</td>';//A'stock
      html += '</tr>';
      //console.log(html);
      $("#detail_list > tbody").append(html);
      //$('#kd_gudang').val("");
      /*$('#qty_aktual').val('');
      $('#qty_stock').val('');
      $('#nama_item').val('');
      $('#kd_item').val('');*/
      $('#inputpicker-1').val('').select();
      __deleteBtn();

   }
   function __deleteBtn(){
      $('.hapus-item').click(function(){
         $(this).parents('tr').remove();
      });
   };
   function __hapus_item(id){
      if(confirm('Yakin data ini akan dihapus?')){
         $.getJSON(http+'/stock_opname/delete_stock_opname_detail/'+id,{'id':id}, function(data, status)
         {
            if (data.status == true) {
               document.location.reload()
            }
         });
      }
   }

   function __simpanData(){
      if(!$('#form_sod').valid()){return};
      var urls=http+"/stock_opname/simpan_header";
      var datax = __simpanDetail();
      if(datax.length >0){
         $('#loadpage').removeClass("hidden");
         $.ajax({
            type : 'post',
            url : urls,
            dataType : 'json',
            data : $('#form_sod').serialize()+"&detail="+JSON.stringify(datax),
            success:function(result){
               //console.log(result);
               if(result){
                  $('.success').animate({ top: "0"}, 500);
                  $('.success').html('Data berhasil di simpan').fadeIn();
                  setTimeout(function() {
                     document.location.reload(); 
                     //console.log(result);
                     location.replace(result.location)
                  }, 2000);
               }else{
                  $('.error').animate({ top: "0"}, 500);
                  $('.error').html('Data gagal di simpan').fadeIn();
                  setTimeout(function() {
                     hideAllMessages();
                  }, 2000);
               }
            }
         })
      }else{
         $('.error').animate({ top: "0"}, 500);
         $('.error').html('Tidak ada data yang disimpan').fadeIn();
         setTimeout(function() {
            hideAllMessages();
         }, 2000);
      }
   }

   function __simpanDetail(){
      var bariskex=0;
      bariskex = $('#detail_list > tbody > tr').length;
      var dataxx=[];
      for(iz=0;iz< bariskex;iz++){
         dataxx.push({
            'kd_gudang': $.trim($("#detail_list > tbody > tr:eq(" + iz + ") td:eq(2)").text()),
            'kd_item' : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(3)").text(),
            'qty_stock'  : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(5)").text(),
            'qty_aktual': $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(6)").text(),
            'selisih'    : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(7)").text(),
            'keterangan'    : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(4)").text(),
            'harga_jual'    : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(8)").text(),
            'jenis_item'    : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(9)").text()
         })
      }
      //console.log(dataxx);
      return dataxx;
   }
</script>