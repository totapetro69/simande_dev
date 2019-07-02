<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$tgl_trans=date("d/m/Y");
$area_kegiatan="";$lokasi_joinpromo="";$kegiatan="";$no_trans="";$tgl_joinpromo=date('d/m/Y');
$tujuan_joinpromo=""; $target_audiens="";$target_sales="";$target_database="";
$ringkasan_joinpromo="";$approval="";$approval_date="";$approval_by="";
$hilang =($status_c=='' || $status_e=='')?"":"hidden";
if(isset($list)){
   if($list->totaldata >0){
      foreach($list->message as $key=>$value){
         $defaultDealer = $value->KD_DEALER;
         $tgl_trans = TglFromSql($value->TGL_TRANS);
         $tgl_joinpromo = TglFromSql($value->TGL_JOINPROMO);
         $no_trans = $value->NO_TRANS;
         $area_kegiatan = $value->AREA_JOINPROMO;
         $lokasi_joinpromo = $value->LOKASI_JOINPROMO;
         $kegiatan = $value->KEGIATAN_JOINPROMO;
         $tujuan_joinpromo = $value->TUJUAN_JOINPROMO;
         $target_audiens = $value->TARGET_AUDIENS;
         $target_sales = $value->TARGET_SALES;
         $target_database = $value->TARGET_DATABASE;
         $ringkasan_joinpromo = $value->RINGKASAN_JOINPROMO;
         $approval = $value->STATUS_JOINPROMO;
      }
   }
}
$hilang =((int)$approval >0)?"hidden":$hilang;
$apv_mode=($this->input->get('a')=='y')?'':'hidden';
$inp_mode=($this->input->get('a'))?'hidden':'';
$inp_smp =((int)$approval >0)?"disabled-action":$inp_mode;
$apv_level=$this->input->get('v');
$hilang =($this->input->get('a')=='y')?'hidden':$hilang;
$cetak = ($no_trans)?'':'disabled-action';
?>
<section class="wrapper">
   <div class="breadcrumb margin-bottom-10">
      <?php echo breadcrumb(); ?>
      <div class="bar-nav pull-right">
         <a class="btn btn-default <?php echo $status_c.' '.$inp_mode;?>" href="<?php echo base_url('stock_opname/proposal_jp'); ?>">
            <i class="fa fa-file-o fa-fw"></i>Proposal Baru
         </a>
         <a id="submit-btn"  class="btn btn-default <?php echo $status_c .' '.$inp_mode.' '.$inp_smp; ?> <?php echo $status_e; ?>" role="button" onclick="__simpanData();">  
            <i class="fa fa-list-alt fa-fw"></i> Simpan
         </a>
         <a id="submit-btn"  class="btn btn-default <?php echo $status_c .' '.$apv_mode; ?> <?php echo $status_e; ?>" role="button" onclick="__approve_jp('<?php echo $apv_level;?>');">  
            <i class="fa fa-cogs fa-fw"></i> Approval
         </a>
         <a class="btn btn-default  <?php echo $status_p.' '.$inp_mode.' '.$cetak; ?>" id="modal-button" onclick='addForm("<?php echo base_url('stock_opname/proposal_jp_print?n='); ?><?php echo urlencode(base64_encode($no_trans));?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
            <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan " ></i> Cetak
         </a>
         <a class="btn btn-default <?php echo $status_v;?>" href="<?php echo base_url('stock_opname/proposal_jplist?a='.$this->input->get('a')); ?>">
            <i class="fa fa-list-ul fa-fw"></i>List Proposal
         </a>
      </div>
   </div>
   <div class="col-lg-12 padding-left-right-10">
      <div class="panel margin-bottom-10">
         <div class="panel-heading"><i class="fa fa-list-ul"></i> PROPOSAL JOIN PROMO
            <span class="tools pull-right">
               <a class="fa fa-chevron-up" href="javascript:;"></a>
            </span>
         </div>
         <div class="panel-body panel-body-border" style="display:;">
            <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('finance/add_saldoawal_simpan'); ?>">
               <div class="row">
                  <div class="col-xs-6 col-sm-3">
                     <div class="form-group">
                        <label>Dealer</label>
                        <select name="kd_dealer" id="kd_dealer" class="form-control" required="true">
                           <option value="">- Pilih Dealer -</option>
                           <?php
                           if(isset($dealer)){
                              if($dealer->totaldata >0){
                                 foreach ($dealer->message as $key => $value) {
                                    $pilih = ($defaultDealer == $value->KD_DEALER)?'selected':'';
                                    ?>
                                       <option value="<?php echo $value->KD_DEALER;?>" <?php echo $pilih;?>><?php echo $value->NAMA_DEALER;?></option>
                                    <?php
                                 }
                              }
                           }
                         ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-2 col-md-2">
                     <div class="form-group">
                        <label>Tanggal</label><?php //echo urlencode(base64_encode('JPT13201901-00009'));?>
                        <div class="input-group append-group date">
                           <input type="text" class="form-control" id="tgl_trans" name="tgl_trans" value="<?php echo $tgl_trans;?>">
                           <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span> </span>
                        </div>
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-2 col-md-2">
                     <div class="form-group">
                        <label>Area</label>
                        <input type="text" name="area" id="area" value="<?php echo $area_kegiatan;?>" class="form-control" placeholder="Masukkan Area">
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-3 col-md-3">
                     <div class="form-group">
                        <label>Judul Join Promo</label>
                        <input type="text" name="kegiatan" id="kegiatan" value="<?php echo $kegiatan;?>" class="form-control" placeholder="Masukkan kegiatan" >
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-2 col-md-2">
                     <div class="form-group">
                        <label>No Transaksi</label>
                        <input type="text" name="no_trans" id="no_trans" value="<?php echo $no_trans;?>" class="form-control disabled-action" placeholder="Auto generate" value="">
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-3">
                     <div class="form-group">
                        <label>Tanggal Kegiatan</label>
                        <div class="input-group append-group datetime">
                           <input type="text" class="form-control" id="tgl_joinpromo" name="tgl_joinpromo" value="<?php echo $tgl_joinpromo;?>">
                           <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span> </span>
                        </div>
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-3">
                     <div class="form-group">
                        <label>Lokasi Kegiatan</label>
                        <input type="text" name="lokasi_joinpromo" id="lokasi_joinpromo" value="<?php echo $lokasi_joinpromo;?>" class="form-control" placeholder="Masukkan Lokasi Kegiatan">
                     </div>
                  </div>
                  
                  <div class="col-xs-6 col-sm-2 col-md-2">
                     <div class="form-group">
                        <label>Target Audiens</label>
                        <input type="text" name="target_audiens" id="target_audiens" value='<?php echo $target_audiens;?>' class="form-control" placeholder="Masukkan target">
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-2 col-md-2">
                     <div class="form-group">
                        <label>Target Sales</label>
                        <input type="text" name="target_sales" id="target_sales" value='<?php echo $target_sales;?>' class="form-control" placeholder="Masukkan target">
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-2 col-md-2">
                     <div class="form-group">
                        <label>Target Database</label>
                        <input type="text" name="target_database" id="target_database" value='<?php echo $target_database;?>' class="form-control" placeholder="Masukkan target">
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-6">
                     <div class="form-group">
                        <label>Tujuan Kegiatan</label>
                        <textarea  name="tujuan_joinpromo" id="tujuan_joinpromo" class="form-control" placeholder="Masukkan Tujuan Kegiatan"><?php echo $tujuan_joinpromo;?></textarea>
                     </div>
                  </div>
                  <div class="col-xs-6 col-md-6 col-sm-6">
                     <div class="form-group">
                       <label>Ringkasan Kegiatan</label>
                       <textarea  name="ringkasan_joinpromo" id="ringkasan_joinpromo" class="form-control" placeholder="Masukkan URL Ringkasan Kegiatan"><?php echo $ringkasan_joinpromo;?></textarea>
                     </div>
                  </div>
            </div>
            <!-- <fieldset class="no-margin-l no-margin-r" style="background-color: silver"> -->
               <!-- <untuk input data dalam tabel> -->
            <div class="row <?php echo $hilang;?>">
               <hr>
               <div class="col-xs-6 col-md-3 col-sm-3 no-margin-r">
                  <div class="form-group">
                        <label>Uraian Kegiatan</label>
                     <textarea class="form-control" rows="1" id="uraian" name="uraian"></textarea>
                  </div>
               </div>
               <div class="col-xs-6 col-sm-1 col-sm-1 no-margin-l no-margin-r">
                  <div class="form-group">
                     <label>Volume</label>
                     <input type="text" name="volume" id="volume" value='' class="form-control" placeholder="jumlah kegiatan">
                  </div>
               </div>
               <div class="col-xs-2 col-sm-1 col-md-1 no-margin-l no-margin-r">
                  <div class="form-group">
                     <label>Satuan</label>
                     <input type="text" name="satuan" id="satuan" value='' class="form-control" placeholder="unit">
                  </div>
               </div>
               <div class="col-xs-6 col-sm-2 col-md-2 no-margin-l no-margin-r">
                  <div class="form-group">
                     <label>Harga</label>
                     <input type="text" name="harga" id="harga" value='' class="form-control" placeholder="Masukkan Harga">
                  </div>
               </div>
               <div class="col-xs-6 col-sm-2 col-md-2 no-margin-l no-margin-r">
                  <div class="form-group">
                     <label>Total Harga</label>
                     <input type="text" name="jumlah" id="jumlah" value='' class="form-control" placeholder="">
                  </div>
               </div>
               <div class="col-xs-6 col-sm-3 col-md-3 no-margin-l">
                  <div class="form-group">
                     <label>Keterangan</label>
                     <div class="input-group">
                        <textarea rows="1"  name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan Keterangan"></textarea>
                        <span class="input-group-btn"><button class="btn btn-info" type="button" onclick="__addItem();"><i class='fa fa-plus'></i></button></span>
                     </div>
                  </div>
               </div>
            </div>
            <!-- </fieldset> -->
            </form>
         </div>
      </div>
   </div>
   <div class="col-lg-12 padding-left-right-10">
      <div class="panel panel-default">
         <div class="panel margin-bottom-10">
            <div class="table-responsive h250">
               <table class="table table-striped table-bordered" id="detail_list">
                  <thead>
                     <tr>
                       <th style="width:5%;">No.</th>
                       <th style="width:5%;"></th>
                       <th style="width:25%;">URAIAN</th>
                       <th style="width:8%;">VOLUME</th>
                       <th style="width:8%;">SATUAN</th>
                       <th style="width:12%;">HARGA</th>
                       <th style="width:12%;">JUMLAH</th>
                       <th style="">KETERANGAN</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     if (isset($detail)) {
                       $no = 0; $t_harga=0;
                       if (($detail->totaldata >0 )) {
                           foreach ($detail->message as $key => $value) {
                              # code...
                              $no++;
                              ?>
                              <td><?php echo $no; ?></td>
                              <td class="table-nowarp"><a class='hapus-item' onclick="__hapus_item('<?php echo $value->ID;?>');" role='button'><i class='fa fa-trash'></i></a></td>
                              <td class="table-nowarp"><?php echo $value->URAIAN_JOINPROMO;?></td>
                              <td class="table-nowarp"><?php echo $value->VOLUME_JOINPROMO;?></td>
                              <td class="table-nowarp"><?php echo $value->SATUAN_JOINPROMO;?></td>
                              <td class='text-right'><?php echo number_format($value->HARGA_JOINPROMO,0);?></td>
                              <td class='text-right'><?php echo number_format($value->JUMLAH_JOINPROMO,0);?></td>
                              <td class="table-nowarp"><?php echo $value->KETERANGAN_JOINPROMO;?></td>
                           </tr>
                           <?php
                           $t_harga += $value->JUMLAH_JOINPROMO;
                           }
                        }
                     }
                     ?> 
                  </tbody>
                  <tfoot>
                     <?php 
                     if(isset($detail)){
                        if($detail->totaldata >0){
                           ?>
                           <tr class="total">
                              <td colspan="6" class='text-right' style="padding-right: 10px">TOTAL</td>
                              <!-- <td class="text-right"><?php echo number_format($t_harga,0);?></td> -->
                              <td class="text-right"><?php echo number_format($t_harga,0);?></td>
                              <td>&nbsp;</td>
                           </tr>
                           <?php
                        }
                     }
                     ?>
                     
                     <tr class="total" valign="top"">
                        <td colspan="5">
                           <table class="table table-striped table-bordered" id="lst_sharing">
                              <tbody>
                                 <tr>
                                    <td colspan="4">Sharing Budget</td>
                                 </tr>
                                 <tr class="<?php echo $hilang;?>">
                                    <td style="width:10%">&nbsp;</td>
                                    <td style="width:35%">
                                       <select class="form-control" id="kd_fincoy" name="kd_fincoy">
                                          <option value="">--Pilih Leasing--</option>
                                          <?php 
                                             if(isset($fincom)){
                                                if($fincom->totaldata >0){
                                                   foreach ($fincom->message as $key => $value) {
                                                      echo "<option value='".$value->KD_LEASING."'>".$value->NAMA_LEASING."</option>";
                                                   }
                                                }
                                             }

                                          ?>
                                       </select>
                                    </td>
                                    <td style="width:35%">
                                       <div class="input-group">
                                          <input type="text" class="form-control" name="jml_fin" id="jml_fin">
                                          <span class="input-group-btn">
                                             <button type="button" class="btn btn-info" onclick="__addSharing();"><i class="fa fa-plus"></i></button>
                                          </span>
                                       </div>
                                    </td>
                                    <td>&nbsp;</td>
                                 </tr>
                              </tbody>
                              <tfoot>
                                 <?php
                                    if(isset($sharing)){
                                       $n=0; $tts=0;
                                       if($sharing->totaldata >0){
                                          foreach ($sharing->message as $key => $value) {
                                             $n++;
                                             ?>
                                             <tr>
                                                <td class='text-center'><?php echo $n;?></td>
                                                <td class='table-nowarp'><?php echo $value->KD_LEASING;?></td>
                                                <td class='text-right'><?php echo number_format($value->JUMLAH_SHARING,0);?></td>
                                                <td>&nbsp;</td>
                                             </tr>
                                             <?php
                                             $tts +=$value->JUMLAH_SHARING;
                                          }
                                          ?>
                                             <tr class='total'>
                                                <td colspan="2" class="text-right">Total Sharing</td>
                                                <td class='text-right'><?php echo number_format($tts,0);?></td>
                                                <td>&nbsp;</td>
                                             </tr>
                                          <?php
                                       }
                                    }
                                 ?>
                              </tfoot>
                           </table>
                        </td>
                        <td colspan="3">
                           <table class="table table-striped table-hover table-bordered">
                              <tr>
                                 <td colspan="4">Approval Status</td>
                              </tr>
                              <tr class="warning">
                                 <td style="width:15%" class="text-center">Level</td>
                                 <td style="width:35%">Approval By</td>
                                 <td style="width:30%">Approval Date</td>
                                 <td style="width:20%">Status</td>
                              </tr>
                              <tbody>
                                 <?php
                                    if(isset($apv)){
                                       if($apv->totaldata >0){
                                          foreach ($apv->message as $key => $value) {
                                             ?>
                                                <tr>
                                                   <td class='text-center'><?php echo $value->APPROVAL_LEVEL;?></td>
                                                   <td class='table-nowarp'><?php echo $value->APPROVAL_BY;?></td>
                                                   <td class='table-nowarp'><?php echo TglfromSql($value->APPROVAL_DATE);?></td>
                                                   <td class='table-nowarp' title="<?php echo $value->APPROVAL_REMARKS;?>"><?php echo ($value->APPROVAL_STATUS >0)?'Approved':'No Approved';?></td>
                                                </tr>
                                             <?php
                                          }
                                       }
                                    }
                                 ?>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                  </tfoot>
               </table>
            </div>
         </div>
      </div>
   </div>
   <?php echo loading_proses();?>
</section>
<script type="text/javascript">
   $(document).ready(function(){
      $('#volume').mask("#,##0",{reverse: true});
      $('#harga').mask("#,##0",{reverse: true});
      $('#jml_fin').mask("#,##0",{reverse: true});
      $('#harga').on("focusout",function(){
         var vol=$('#volume').val();
            vol =(parseInt(vol)>0)?vol.replace(/,/g,''):0;
         var hasil = $(this).val().replace(/,/g,'');;
            $('#jumlah').val(parseFloat(hasil)* vol);
            $('#jumlah').mask("#,##0",{reverse: true});
      });
   });
   function __validity(){
      console.log($('#uraian').val()+':'+$('#volume').val()+":"+ $('#harga').val())
      if($('#uraian').val()=='' && $('#volume').val()=='' && $('#harga').val()==''){
         return false;
      }
   }

   function __addItem(){
      if(__validity()==false){alert("Tidak ada data yang di simpan");return false;};
      var uraian = $('#uraian').val();
      var jmlbaris=$("#detail_list > tbody >tr").length;
      var html = '';
      html += '<tr>';
      html += "<td class='text-center'>"+(jmlbaris+1)+"</td> "; 
      html += "<td class='text-center'><a class='hapus-item' role='button'><i class='fa fa-trash'></i></a></td>";
      html += '<td class="table-nowarp">'+$('#uraian').val()+' </td>';//uraian
      html += '<td class="table-nowarp text-center">'+$('#volume').val()+' </td>';//volume
      html += '<td class="table-nowarp">'+$('#satuan').val()+' </td>';//satuan
      html += '<td class="table-nowarp text-right">'+$('#harga').val()+' </td>';//harga
      html += '<td class="table-nowarp text-right">'+$('#jumlah').val()+' </td>';//jumlah
      html += '<td class="table-nowarp">'+$('#keterangan').val()+' </td>';//keterangan
      html += '</tr>';
      $("#detail_list > tbody").append(html);
      $('#uraian').val("");
      $('#volume').val('');
      $('#satuan').val('');
      $('#harga').val('');
      $('#jumlah').val('');
      $('#keterangan').val('');
      __deleteBtn();
   }
   function __addSharing(){
      var htmls ="";
      if($('#kd_fincoy').val()=='' && $('#jml_fin').val()==''){
         return false;
      }
      var jmlbaris=$("#lst_sharing > tfoot >tr").length;
         htmls +="<tr><td class='text-center'><a class='hapus-itemx' role='button'><i class='fa fa-trash'></i></a></td>";
         htmls +="<td>"+$('#kd_fincoy option:selected').text()+"</td>";
         htmls +="<td class='text-right'>"+$('#jml_fin').val()+"</td><td>&nbsp;</td>";
         htmls +="<td class='hidden'>"+$('#kd_fincoy').val()+"</td>";
         htmls +="</tr>";
         $("#lst_sharing > tfoot").append(htmls);
         $('#kd_fincoy').val('').select();
         $('#jml_fin').val('');
         __deleteBtns();
   }
   function __deleteBtn(){
      $('.hapus-item').click(function(){
         $(this).parents('tr').remove();
      });
   };
   function __deleteBtns(){
      $('.hapus-itemx').click(function(){
         $(this).closest('tr').remove();
      })
   };
   function __simpanData(){
      var detail = __simpan_detail();
      var sharing= __simpan_sharing();
      if(detail.length==0){return;}
      $('#loadpage').removeClass("hidden");
      $.ajax({
         type :'POST',
         url :"<?php echo base_url('stock_opname/simpan_proposal');?>",
         data : $('#addForm').serialize()+"&d="+JSON.stringify(detail)+"&s="+JSON.stringify(sharing),
         dataType :'json',
         success :function(result){
            console.log(result);
            if(result){
               $('.success').animate({ top: "0"}, 500);
               $('.success').html('Data berhasil di simpan').fadeIn();
               setTimeout(function() {
                  //document.location.reload(); 
                  //console.log(result);
                  location.replace(result.location)
              }, 2000);
            }else{
               $('.error').animate({ top: "0"}, 500);
               $('.error').html('Data gagal di simpan').fadeIn();
               setTimeout(function() {
                  hideAllMessages();
               }, 2000);

               $('#loadpage').addClass("hidden");
            }
         }
      });
   }
   function __simpan_detail(){
      var jmlbaris=$("#detail_list > tbody >tr").length;
      var detail=[];
      for(i=0;i < jmlbaris; i++){
         detail.push({
            'uraian' : $("#detail_list > tbody >tr:eq("+i+") td:eq(2)").text(),
            'volumne': $("#detail_list > tbody >tr:eq("+i+") td:eq(3)").text(),
            'satuan' : $("#detail_list > tbody >tr:eq("+i+") td:eq(4)").text().replace(/,/g,''),
            'harga'  : $("#detail_list > tbody >tr:eq("+i+") td:eq(5)").text().replace(/,/g,''),
            'jumlah' : $("#detail_list > tbody >tr:eq("+i+") td:eq(6)").text().replace(/,/g,''),
            'keterangan': $("#detail_list > tbody >tr:eq("+i+") td:eq(7)").text()
         })
      }
      return detail;
   }
   function __simpan_sharing(){
      var jmlbaris=$("#lst_sharing > tfoot >tr").length;
      var detail=[];
      for(i=0;i < jmlbaris; i++){
         detail.push({
            'kd_leasing' : $("#lst_sharing > tfoot >tr:eq("+i+") td:eq(4)").text(),
            'sharing': $("#lst_sharing > tfoot >tr:eq("+i+") td:eq(2)").text().replace(/,/g,'')
         })
      }
      return detail;
   }
   //approval process
   function __approve_jp(apv_lvl){
      var apv_status=0;var remark="";
      var sts=confirm("Proposal ini akan di Approve");
      if(sts){
         apv_status =1;
      }else{
         apv_status = -1;
         remark =prompt('Masukan Alasan tidak di approved');
      }
      var datax={
         'no_trans' : "<?php echo $no_trans;?>",
         'approval_level' :apv_lvl,
         'approval_by' : "<?php echo $this->session->userdata("user_name");?>",
         'approval_date': "<?php echo date('d/m/Y');?>",
         'approval_status' : apv_status,
         'approval_remarks' : remark
      }
      $('#loadpage').removeClass("hidden");
      $.ajax({
         type :'POST',
         url :"<?php echo base_url('stock_opname/approval_jp');?>",
         dataType :'json',
         data : datax,
         success : function(result){
            console.log(result);
            if(result){
               $('.success').animate({ top: "0"}, 500);
               $('.success').html('Data berhasil di simpan').fadeIn();
               setTimeout(function() {
                  document.location.reload(); 
                  $('#loadpage').addClass("hidden");
              }, 2000);
            }else{
               $('.error').animate({ top: "0"}, 500);
               $('.error').html('Data gagal di simpan').fadeIn();
               setTimeout(function() {
                  hideAllMessages();
               }, 2000);
               $('#loadpage').addClass("hidden");
            }
         }
      })
   }
</script>