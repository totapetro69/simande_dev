<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );


$defaultDealer = ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
$defaultMainDealer = $this->session->userdata("kd_maindealer");
$kd_gc = ""; $nama_program= "";
$start_date = date("d/m/Y"); $end_date= date("d/m/Y");
$s_ahm = ""; $s_md= ""; $no_trans="";
$s_sd = ""; $kd_typemotor= "";
$kd_leasing = ""; $nama_leasing= "";$kd_kabupaten= "";
$nama_kabupaten= ""; $sk_finance=""; $sc_ahm=""; $sc_md="";
$sc_sd=""; $harga_kontrak=""; $fee=""; $pengurusan_stnk="";
$pengurusan_bpkb="";
$leasing=array();
//$kd_kabupaten=array();
if (isset($list)) {
   if(($list->totaldata > 0)) {
      foreach ($list->message as $key => $value) {
         $defaultDealer = $value->KD_DEALER;
         //$defaultDealer = $value->KD_DEALER;
         $defaultLokasi = $value->KD_LOKASIDEALER;
         $no_trans = $value->NO_TRANS;
         $kd_propinsi_bpkb = $value->KD_PROPINSI;
         $kd_kabupaten_bpkb = $value->KD_KABUPATEN;
      }
   }

   $leasing = explode(", ",$kd_leasing);
   $kd_kabupaten = explode(", ",$kd_kabupaten);
}
?>

<section class="wrapper">

   <div class="breadcrumb margin-bottom-10">
      <?php echo breadcrumb(); ?>
      <div class="bar-nav pull-right">
         <a class="btn btn-default" href="<?php echo base_url('setup/proposal_gc'); ?>">
            <i class="fa fa-file-o fa-fw"></i> Baru
         </a>
         <a id="submit-btn"  class="btn btn-default <?php echo $status_c; ?>" role="button" onclick="__simpanData();">
            <i class="fa fa-list-alt fa-fw"></i> Simpan
         </a>
         <a href="<?php echo base_url('setup/cetak_pgc'); ?> "target="_blank" class="<?php echo $status_p?>">
            <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Proposal GC" ></i> Cetak
         </a>
      </div>
   </div>

   <div class="col-lg-12 padding-left-right-5 ">

      <div class="panel margin-bottom-5">

         <div class="panel-heading">
            <i class="fa fa-list-ul fa-fw"></i> Proposal Group Customer
            <span class="tools pull-right">
               <a class="fa fa-chevron-down" href="javascript:;"></a>
            </span>
         </div>

         <div class="panel-body panel-body-border panel-body-12" style="display: block;">

            <form id="form_pgc" method="post" action="<?php echo base_url("setup/proposal_gc"); ?>">

               <div class="row">

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>Dealer</label>
                        <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer" readonly>
                           <option value="0">--Pilih Dealer--</option>
                           <?php
                           if ($dealer) {
                              if (is_array($dealer->message)) {
                                 foreach ($dealer->message as $key => $value) {
                                    $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                    //$aktif = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $aktif;
                                    echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                 }
                              }
                           }
                           ?>
                        </select>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>Nomor Proposal</label>
                        <input type="text" class="form-control" id="no_trans" autocomplete="off" name="no_trans" placeholder="AUTO NUMBER" value="<?php echo $no_trans;?>" readonly="true">
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>Kode Tipe Motor</label>
                        <select name="kd_typemotor" id="kd_typemotor" class="form-control ">
                           <option value="">--Silahkan Pilih--</option>
                           <?php
                           if($gc){
                              if($gc->totaldata>0){
                                 foreach ($gc->message as $key => $value) {
                                    $select=($kd_typemotor==$value->KD_TYPEMOTOR)?"selected":"";
                                    ?>
                                    <option value="<?php echo $value->KD_TYPEMOTOR;?>" <?php echo $select;?>><?php echo $value->KD_TYPEMOTOR;?></option>
                                    <?php
                                 }
                              }
                           }
                           ?>
                        </select>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-1 col-sm-1">
                     <div class="form-group">
                        <label>QTY</label>
                        <input id="qty" type="text" name="qty" class="form-control" placeholder="" value="0" disabled="disabled">
                        <?php
                        echo ! empty($qty) ? $qty[1] :'';
                        ?>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>Kode GC</label>
                        <select class="form-control" name="kd_gc" id="kd_gc">
                           <option value="0">----</option>
                        </select>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-3 col-sm-3">
                     <div class="form-group">
                        <label>Deskripsi Program</label>
                        <textarea rows="1" class="form-control" id="desc_program" name="desc_program" placeholder="Masukkan Deskripsi Program"></textarea>
                     </div>
                  </div>

               </div>

               <div class="row">

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <div class="input-group input-append date disabled-action" id="date">
                           <input type="text" class="form-control" id="start_date" name="start_date" value="" placeholder="dd/mm/yyyy"/>
                           <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <div class="input-group input-append date disabled-action" id="date">
                           <input type="text" class="form-control" id="end_date" name="end_date" value="" placeholder="dd/mm/yyyy"/>
                           <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                     </div>
                  </div>

                  <div class="col-xs-6 col-sm-2 col-md-2">
                     <div class="form-group">
                        <label>Provinsi</label>
                        <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi" required="true">
                           <option value="">--Pilih Propinsi--</option>
                           <?php
                           if ($propinsi) {
                              if (is_array($propinsi->message)) {
                                 foreach ($propinsi->message as $key => $value) {
                                    $terpilih = ($kd_propinsi == $value->KD_PROPINSI) ? "selected" : "";
                                    echo "<option value='" . $value->KD_PROPINSI . "' " . $terpilih . ">" . $value->NAMA_PROPINSI . "</option>";
                                 }
                              }
                           }
                           ?>
                        </select>
                     </div>
                  </div>

                  <div class="col-xs-6 col-sm-2 col-md-2">
                     <div class="form-group">
                        <label>Kabupaten <span id="l_kabupaten"></span></label>
                        <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten" required="true">
                           <option value="">--Pilih Kabupaten--</option>
                        </select>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>Tipe Program</label>
                        <select name="type" class="form-control">
                           <option value="0">-- Pilih Tipe --</option>
                           <option value="G-GCSwasta">G-GCSwasta</option>
                           <option value="D-Dinas">D-Dinas</option>
                        </select>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>Leasing</label>
                        <select class="form-control" name="kd_leasing" id="kd_leasing" title="leasing" required="true">
                           <option value="">--Pilih leasing--</option>
                           <?php
                           if ($company_finance) {
                              if (is_array($company_finance->message)) {
                                 foreach ($company_finance->message as $key => $value) {
                                    $terpilih = ($kd_leasing == $value->KD_LEASING) ? "selected" : "";
                                    echo "<option value='" . $value->KD_LEASING . "' " . $terpilih . ">" . $value->NAMA_LEASING . "</option>";
                                 }
                              }
                           }
                           ?>
                        </select>
                     </div>
                  </div>

               </div>

               <div class="row">

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>S AHM</label>
                        <input id="s_ahm" type="text" name="s_ahm" class="form-control" placeholder="" value="0" disabled="disabled">
                        <?php
                        echo ! empty($s_ahm) ? $s_ahm[1] :'';
                        ?>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>S MD</label>
                        <input id="s_md" type="text" name="s_md" class="form-control" placeholder="" value="0" disabled="disabled">
                        <?php
                        echo ! empty($s_md) ? $s_md[1] :'';
                        ?>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>S SD</label>
                        <input id="s_sd" type="text" name="s_sd" class="form-control" placeholder="" value="0" disabled="disabled">
                        <?php
                        echo ! empty($s_sd) ? $s_sd[1] :'';
                        ?>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>SK FINANCE</label>
                        <input id="sk_finance" type="text" name="sk_finance" class="form-control" placeholder="" value="0" disabled="disabled">
                        <?php
                        echo ! empty($sk_finance) ? $sk_finance[1] :'';
                        ?>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>SC AHM</label>
                        <input id="sc_ahm" type="text" name="sc_ahm" class="form-control" placeholder="" value="0" disabled="disabled">
                        <?php
                        echo ! empty($sc_ahm) ? $sc_ahm[1] :'';
                        ?>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>SC MD</label>
                        <input id="sc_md" type="text" name="sc_md" class="form-control" placeholder="" value="0" disabled="disabled">
                        <?php
                        echo ! empty($sc_md) ? $sc_md[1] :'';
                        ?>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>SC SD</label>
                        <input id="sc_sd" type="text" name="sc_sd" class="form-control" placeholder="" value="0" disabled="disabled">
                        <?php
                        echo ! empty($sc_sd) ? $sc_sd[1] :'';
                        ?>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>HARGA KONTRAK</label>
                        <input id="harga_kontrak" type="text" name="harga_kontrak" class="form-control" placeholder="" value="0" disabled="disabled">
                        <?php
                        echo ! empty($harga_kontrak) ? $harga_kontrak[1] :'';
                        ?>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>FEE</label>
                        <input id="fee" type="text" name="fee" class="form-control" placeholder="" value="0" disabled="disabled">
                        <?php
                        echo ! empty($fee) ? $fee[1] :'';
                        ?>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>Pengurusan STNK</label>
                        <input id="pengurusan_stnk" type="text" name="pengurusan_stnk" class="form-control" placeholder="" value="0" disabled="disabled">
                        <?php
                        echo ! empty($pengurusan_stnk) ? $pengurusan_stnk[1] :'';
                        ?>
                     </div>
                  </div>

                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>Pengurusan BPKB</label>
                        <input id="pengurusan_bpkb" type="text" name="pengurusan_bpkb" class="form-control" placeholder="" value="0" disabled="disabled">
                        <?php
                        echo ! empty($pengurusan_bpkb) ? $pengurusan_bpkb[1] :'';
                        ?>
                     </div>
                  </div>

                  <!-- <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label class="control-label" for="date">Tanggal</label>
                        <div class="input-group input-append date">
                           <input class="form-control" id="tgl_trans" name="tgl_trans" placeholder="DD/MM/YYYY" value="<?php echo $tgl_trans;?>" type="text"/>
                           <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                     </div>
                  </div> -->

                  <div class="col-xs-6 col-md-1 col-sm-1">
                     <div class="form-group">
                        <br>
                        <button id="submit-btn" type='button' onclick="__addItem();" class="btn btn-info" ><i class='fa fa-plus'></i></button>
                     </div>
                  </div>

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
                     <th style="width:2%">No</th>
                     <th style="width:2%">Aksi</th>
                     <th style="width:6%">KD TIPE</th>
                     <th style="width:2%">QTY</th>
                     <th style="width:8%">SK AHM</th>
                     <th style="width:8%">SK MD</th>
                     <th style="width:8%">SK SD</th>
                     <th style="width:8%">SK FINANCE</th>
                     <th style="width:8%">SC AHM</th>
                     <th style="width:8%">SC MD</th>
                     <th style="width:8%">SC SD</th>
                     <th style="width:8%">HRG KONTRAK</th>
                     <th style="width:8%">FEE</th>
                     <th style="width:10%">P STNK</th>
                     <th style="width:10%">P BPKB</th>
                  </tr>
               </thead>

               <tbody>
                  <?php
                  if (isset($detail)) {
                     $no = 0;
                     if (($detail->totaldata >0 )) {
                        foreach ($detail->message as $key => $value) {
                        # code..
                           $no++;
                           ?>
                           <tr id="l_<?php echo $value->ID;?>">
                              <td class="text-center"><?php echo $no; ?></td>
                              <td class="table-nowarp text-center"><a class='hapus-item' onclick="__hapus_item('<?php echo $value->ID;?>');" role='button'><i class='fa fa-trash'></i></a></td>
                              <!-- <td class="table-nowarp"><?php echo $value->NO_TRANS; ?></td> -->
                              <td class="table-nowarp"><?php echo $value->KD_TYPEMOTOR; ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->QTY,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->S_AHM,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->S_MD,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->S_SD,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->SK_FINANCE,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->SC_AHM,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->SC_MD,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->SC_SD,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->HARGA_KONTRAK,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->FEE,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->PENGURUSAN_STNK,0); ?></td>
                              <td class="table-nowarp text-center"><?php echo number_format($value->PENGURUSAN_BPKB,0); ?></td>
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

   function __addItem(){
      var gc = $('#kd_typemotor').val();
      var jmlbaris=$("#detail_list > tbody >tr").length;
      if(!gc){
         alert('Tidak ada data yang dipilih');
         return false;
      }
      var html = '';
      html += '<tr>';
      html += "<td class='text-right'>"+(jmlbaris+1)+"</td> ";
      html += "<td class='text-center'><a class='hapus-item' role='button'><i class='fa fa-trash'></i></a></td>";
      //html += '<td class="table-nowarp">'+$('#no_trans').val()+'</td>';
      html += '<td class="table-nowarp">'+$('#kd_typemotor').val()+'</td>';//KD Tipe
      html += '<td class="table-nowarp">'+$('#qty').val()+'</td>';//qty
      html += '<td class="table-nowarp">'+$('#s_ahm').val()+'</td>';//sk_ahm
      html += '<td class="text-center">'+$('#s_md').val()+'</td>';//sk_md
      html += '<td class="text-center">'+$('#s_sd').val()+'</td>';//sk_sd
      html += '<td class="table-nowarp">'+$('#sk_finance').val()+'</td>';//sk_finance
      html += '<td class="text-center">'+$('#sc_ahm').val()+'</td>';//sc_ahm
      html += '<td class="text-center">'+$('#sc_md').val()+'</td>';//sc_md
      html += '<td class="table-nowarp">'+$('#sc_sd').val()+'</td>';//sc_sd
      html += '<td class="text-center">'+$('#harga_kontrak').val()+'</td>';//harga_kontrak
      html += '<td class="text-center">'+$('#fee').val()+'</td>';//fee
      html += '<td class="text-center">'+$('#pengurusan_stnk').val()+'</td>';//pengurusan_stnk
      html += '<td class="table-nowarp">'+$('#pengurusan_bpkb').val()+'</td>';//pengurusan_bpkb
      html += '</tr>';
      //console.log(html);
      $("#detail_list > tbody").append(html);
      //$('#kd_typemotor').val('');
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
      if(!$('#form_pgc').valid()){return};
      var urls=http+"/setup/simpan_header";
      var datax = __simpanDetail();
      if(datax.length >0){
         $('#loadpage').removeClass("hidden");
         $.ajax({
            type : 'post',
            url : urls,
            dataType : 'json',
            data : $('#form_pgc').serialize()+"&detail="+JSON.stringify(datax),
            success:function(result){
               console.log(result);
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
         //'no_trans': $.trim($("#detail_list > tbody > tr:eq(" + iz + ") td:eq(1)").text()),
         'kd_typemotor'    : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(2)").text(),
         'qty'             : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(3)").text(),
         's_ahm'           : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(4)").text(),
         's_md'            : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(5)").text(),
         's_sd'            : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(6)").text(),
         'sk_finance'      : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(7)").text(),
         'sc_ahm'          : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(8)").text(),
         'sc_md'           : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(9)").text(),
         'sc_sd'           : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(10)").text(),
         'harga_kontrak'   : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(11)").text(),
         'fee'             : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(12)").text(),
         'pengurusan_stnk' : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(13)").text(),
         'pengurusan_bpkb' : $("#detail_list > tbody > tr:eq(" + iz + ") td:eq(14)").text()
      })
      }
      //console.log(dataxx);
      return dataxx;
   }

   $(document).ready(function(){
      ListGc();

      $("#kd_typemotor").change(function(){

         var kd_typemotor = $(this).val();

         $.getJSON("<?php echo base_url("setup/get_gc");?>",
            {'kd_typemotor':kd_typemotor},
            function(result){
               if(result.status == true){

                  var html = '';
                  $.each(result.message,function(e,d){
                  // console.log(d);
                  html += "<option value='"+d.KD_GC+"'>"+d.KD_GC+"</option>";
               })
                  $("#kd_gc").html(html);
               }
            }
            )
      });

      $("#kd_gc").change(function(){

         var kd_typemotor = $("#kd_typemotor").val();
         var kd_gc = $(this).val();

         $.getJSON("<?php echo base_url("setup/get_gc");?>",
            {'kd_gc':kd_gc, 'kd_typemotor':kd_typemotor},
              function(result){
                if(result.status == true){
                  $.each(result.message,function(e,d){
                  //console.log(d);
                  $("#start_date").val(d.START_DATE);
                  $("#end_date").val(d.END_DATE);
                  $("#nama_program").val(d.NAMA_PROGRAM);
                  $("#s_ahm").val(d.S_AHM);
                  $("#s_md").val(d.S_MD);
                  $("#s_sd").val(d.S_SD);
               })
               }
            }
            )
      });
   })

   function ListGc(){
      var datax=[];
   }

   $(document).ready(function(){
      $('#kd_propinsi').change();
      $('#kd_propinsi').on('change', function () {
         loadData('kd_kabupaten', $(this).val(), '')
      })
   });

   function loadData(id, value, select) {
      var param = $('#' + id + '').attr('title');
      $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
      var urls = http + "/customer/" + param+"/";
      var datax = {
         "kd": value
      };
      $('#' + id + '').attr('disabled', 'disabled');
      select = (select == '' || select == "0") ? "0" : select;
      $.ajax({
         type: 'GET',
         url: urls,
         data: datax,
         typeData: 'html',
         success: function(result) {
            $('#' + id + '').empty();
            $('#' + id + '').html(result);
            $('#' + id + '').val(select).select();
            $('#l_' + param + '').html('');
            $('#alamat_lg').html("");
            $('#' + id + '').removeAttr('disabled');
         }
      });
   }

</script>
