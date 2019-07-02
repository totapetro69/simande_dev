<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
/**
 * RULE OF PICKING PART 
 * PART YANG AKAN MUNCUL STOCK NYA HANYA PART YANG SESUAI DENGAN 
 * KODE DEALER  DAN KODE LOKASI USER
 * STATUS STOCK AKAN DI GENERATE OTOMATIS KETIKA PAGE REFRESH
 * @var string
 */
$default="";$KD_DEALER="";
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get('kd_dealer'))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
$tgl_trans = date('d/m/Y');
$id = "";
$no_trans = "";
$no_reff = "";
$nama_konsumen = "";
$kd_maindealer = "";
$kd_dealer = "";
if (isset($part_picking)) {
    if (($part_picking->totaldata > 0)) {
        foreach ($part_picking->message as $key => $value) {
            $id = $value->ID;
            $no_trans = $value->NO_TRANS;
            $no_reff = $value->NO_REFF;
            $tgl_trans = tglfromSql($value->TGL_TRANS);
            $nama_konsumen = $value->NAMA_KONSUMEN;
            $kd_maindealer = $value->KD_MAINDEALER;
            $kd_dealer = $value->KD_DEALER;
        }
    }
}
$disabled = $no_trans != '' ? 'readonly':'';
?>
<section class="wrapper">
   <div class="breadcrumb margin-bottom-10">
      <?php echo breadcrumb(); ?>
      <div class="bar-nav pull-right ">
         <a class="btn btn-default baru" href="<?php echo base_url().'part/add_picking_part'; ?>"><i class="fa fa-file-o fa-fw"></i> Baru</a>
         <a id="submit-btn" type="button" class="btn btn-default <?php echo $status_c; ?>"><i class="fa fa-save fa-fw"></i> Simpan Picking</a>
         <a role="button" href="<?php echo base_url("part/picking_part"); ?>" class="btn btn-default <?php echo $status_v; ?>"><i class="fa fa-list-ul"></i> List Picking Part</a>
      </div>
   </div>
      <div class="col-xs-12 padding-left-right-10">
         <div class="panel margin-bottom-10">
            <div class="panel-heading panel-custom">
                <i class="fa fa-user fa-fw"></i> Form Input Picking Part
            </div>
            <div class="panel-body panel-body-border">
            <form class="partform" id="partform" method="post" action="<?php echo base_url("part/picking_part_simpan"); ?>" autocomplete="off">
               <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
               <input type="hidden" id="tgl_trans" name="tgl_trans" value="<?php echo $tgl_trans; ?>">
               <div class="row">
                  <div class="col-xs-6 col-sm-3 col-md-3">
                     <div class="form-group">
                           <label>Dealer</label>
                           <select name="kd_dealer" id="kd_dealer" class="form-control" required="true">
                             <option value="">- Pilih Dealer -</option>
                             <?php 
                               if(isset($dealer)){
                                 if($dealer->totaldata >0){
                                   foreach ($dealer->message as $key => $value) {
                                     $default =($defaultDealer==$value->KD_DEALER)?'selected':'';
                                     ?>
                                       <option value="<?php echo $value->KD_DEALER;?>" <?php echo $default;?> ><?php echo $value->NAMA_DEALER;?></option>
                                     <?php
                                   }
                                 }
                               }
                             ?>
                           </select>
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-3 col-md-3">
                     <div class="form-group">
                         <label>Jenis Picking <span class="load-form"></span></label>
                         <select name="jenis_picking" id="jenis_picking" class="form-control" required="true">
                             <option value="">- Pilih Jenis Picking -</option>
                             <option value="SO">SO Part</option>
                             <option value="PKB">PKB (WO)</option>
                             <option value="RETUR">RETUR</option>
                         </select>
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-3 col-md-3">
                     <div class="form-group">
                         <label>No. Reff <span class="load-form"></span></label>
                         <div id="picking_reff">
                          <input type="text" name="no_reff" id="no_reff" class="form-control" placeholder="Masukkan Nomor REFF">
                         </div>
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-3 col-md-3">
                     <div class="form-group">
                         <label>No. Trans</label>
                         <input type="text" value="<?php echo $no_trans; ?>" class="form-control" id="no_trans" autocomplete="off" name="no_trans" placeholder="AUTO GENERATE"  readonly>
                     </div>
                  </div>
               </div>
            </form>
               <div id="frminp" class="row disabled-action">
                  <div class="col-xs-6 col-md-4 col-sm-4">
                     <div class="form-group">
                        <label>Part Number</label>
                        <input type="text" id="part_number" name="part_number" class="form-control" required="true">
                        <input type="hidden" id="part_desc" value="">
                        <input type="hidden" id="harga_jual" value="">
                        <input type="hidden" id="harga_sp" value="">
                        <input type="hidden" id="picking_asli" value="">
                        <input type="hidden" id="detail_id" value="">
                     </div>
                  </div>
                  <div class="col-xs-6 col-md-1 col-sm-1">
                     <div class="form-group">
                        <label>Stock <span id="stk"></span></label>
                        <input type="text" id="soh" name="soh" class="form-control disabled-action">
                     </div>
                  </div>
                  <div class="col-xs-6 col-md-2 col-sm-2">
                   <div class="form-group">
                     <label>Gudang <span id="gdg"></span></label>
                     <select class="form-control" id="kd_gudang" required="true">
                        <option value=''>--Pilih Gudang--</option>
                     </select>
                   </div>
                  </div>
                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>Rak <span id="bin"></span></label>
                        <select class="form-control" id="kd_bin" required="true">
                           <option value=''>--Pilih Rak--</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-xs-6 col-md-2 col-sm-2">
                     <div class="form-group">
                        <label>Qty Picking</label>
                        <input type="text" id="qty_picking" name="qty_picking" class="form-control text-right">
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-1 col-md-1">
                     <div class="form-group">
                       <br>
                       <button class="btn btn-info form-control" onclick="__addItem();" type="button"><i class="fa fa-plus fa-fw"></i> Add</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   <div class="col-lg-12 padding-left-right-10">
      <div class="panel panel-default">
         <div class="table-responsive">
            <table id="pkb_list" class="table table-bordered table-hover b-t b-light">
               <thead>
                  <tr class="no-hover"><th colspan="6" ><i class="fa fa-list fa-fw"></i> List Picking Detail</th></tr>
                  <tr>
                      <th style="width:40px;">No.</th>
                      <th style="width:50px;"></th>
                      <th>Data Part <span class='pull-right'><abbr title='Stock On Hand'>SOH</abbr></span></th>
                      <th class="text-center" style="width:80px;">Qty</th>
                      <th class="text-justify" style="width:200px;">Gudang</th>
                      <th class="text-justify" style="width:200px;">Rakbin</th>
                      <!-- <th>Kategori</th> -->
                  </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <?php echo loading_proses(); ?>
</section>
<script type="text/javascript">
   var path = window.location.pathname.split('/');
   var http = window.location.origin + '/' + path[1];
   $(document).ready(function () {
      //__getBarangSP();
      $('.qurency').mask('#.##0', {reverse: true});
      $('#jenis_picking').change(function(){
        var pickingVal = $(this).val();
        $('.load-form').html("<i class='fa fa-spinner fa-spin'></i>");
        var url = '';
        $('#no_reff').val('');
           if(pickingVal == 'PKB'){
              url = http+"/part/picking_typeahead/pkb/true";
           }else if(pickingVal == 'SO'){
              url = http+"/part/picking_typeahead/so/true";
           }else if(pickingVal == 'RETUR'){
              url = http+"/part/picking_typeahead/retur/true";
           }
           // __generate_stock();
           var dataPicking=[];
           $.getJSON(url,function(result){
            //console.log(result);
            //console.log(result.totaldata);
              if(result.totaldata>0){
                 $.each(result.message,function(e,d){
                    dataPicking.push({
                       'NO TRANS' :d.NO_TRANS,
                       'CUSTOMER NAME': d.NAMA_CUSTOMER,
                       'ALAMAT' : d.ALAMAT
                    });
                 })
              }
              $('.load-form').html('');
              $('#no_reff').inputpicker({
                 data:dataPicking,
                 fields:['NO TRANS','CUSTOMER NAME','ALAMAT'],
                 fieldText:'NO TRANS',
                 fieldValue:'NO TRANS',
                 filterOpen: true,
                 headShow:true,
                 pagination: true,
                 pageMode: '',
                 pageField: 'p',
                 pageLimitField: 'per_page',
                 limit: 10,
                 pageCurrent: 1,
                 urlDelay:1
              }).change(function(){
                 var jenis = $('#jenis_picking').val();
                 var no_trans = $(this).val();
                 var url = '';
                 if(jenis == 'PKB'){ url = http+'/part/get_picking/pkb/true';
                 }else if(jenis == 'SO'){ url = http+'/part/get_picking/so/true';
                 }else if(jenis == 'RETUR'){ url = http+'/part/get_picking/retur/true';
                 }
                 $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");
                 var datax=[];
                 $.getJSON(url,{no_trans:no_trans}, function(results, status){
                    $('#kd_gudang').val('').select();
                    $('#kd_bin').val('').select();
                    $('#frminp').removeClass('disabled-action');
                    if(results.status){
                       $.each(results.message,function(e,d){
                          datax.push({
                             'PARTNUMBER' : d.PART_NUMBER,
                             'DESKRIPSI' : d.PART_DESKRIPSI,
                             'JUMLAH' : d.JUMLAH,
                             'text': d.PART_NUMBER +'-'+d.PART_DESKRIPSI,
                             'harga' : d.HARGA_JUAL,
                             'price' : d.PRICE,
                             'id'  :d.ID
                          })
                       })
                    }
                    $('#part_number').inputpicker({
                       data: datax,
                       fields :["PARTNUMBER","DESKRIPSI","JUMLAH"],
                       fieldValue :'PARTNUMBER',
                       fieldText : 'text',
                       headShow :true,
                       autoOpen:false
                    }).on("change",function(){
                        var xx=0;
                        var dx=datax.findIndex(obj => obj['PARTNUMBER'] === $(this).val());
                        console.log(datax);
                        if(parseInt(dx)>-1){
                          xx =(typeof datax[dx]["JUMLAH"]==='undefined')?0:datax[dx]["JUMLAH"];
                          check_list($(this).val(),xx,datax);
                        }
                    })
                      $(".load-form").html('');
                 });
              })
           })  
        });
        
        $('#baru').click(function () {
           document.location.reload();
        });
        $('#submit-btn').click(function(){
           $("#partform").valid();
           $("#partform").validate({
              focusInvalid: false,
              invalidHandler: function(form, validator) {
                if (!validator.numberOfInvalids())
                   return;
                $('html, body').animate({
                   scrollTop: $(validator.errorList[0].element).offset().top
                }, 2000);
              }
           });
           if (jQuery("#partform").valid()) {storeData();}
        });
         $('#kd_gudang').on('change',function(){
           // __getGudang($('#part_number').val(),'BIN');
           $('#kd_bin option:not(.'+$(this).val()+')').addClass("hidden")
           $('#kd_bin option.'+$(this).val()).removeClass("hidden")
         })
      });
    function __getDatax(datax,dx,jml){
    }
    function __getGudang(part_number,jenis){
      var html="<option value=''>--Pilih--</option>";
      $('#'+jenis.toLowerCase()).html("<i class='fa fa-spinner fa-spin'></i>").attr('style','color:red');
      var direct=(jenis=='STK')?1:0;
      var posisi ="";
      switch(jenis){case'GDG': case'STK': posisi='50';break;case 'BIN': posisi='0,1,2,9';break;}
      $.getJSON(http+"/part/getStock/"+part_number+"/"+jenis+"/true",function(result){
        console.log(result);
         if(result){
            switch (jenis){
               case 'GDG':
               $.each(result,function(e,d){
                  html +="<option value='"+d.kd_gudang+"'>"+d.kd_gudang+" [ "+$.number(d.stocked)+" ]</option>";
               })
               $('#kd_gudang').html(html);
               $('#'+jenis.toLowerCase()).html("");
               __getGudang(part_number,'BIN')
               $('#kd_gudang').removeClass("disabled-action");
               $('#kd_bin').addClass("disabled-action");
               break;
               case 'BIN':
                  $.each(result,function(e,d){
                     html +="<option value='"+d.kd_bin+"' class='"+d.kd_gudang+" hidden'>"+d.kd_bin+" [ "+$.number(d.stocked)+" ]</option>";
                  })
                  $('#kd_bin').html(html);
                  $('#'+jenis.toLowerCase()).html("");
                  $('#kd_bin').removeClass("disabled-action");
               break;
               case 'STK':
               $('#soh').val(result["stock"]);
               __getGudang(part_number,'GDG')
               $('#kd_gudang').addClass("disabled-action");
               $('#'+jenis.toLowerCase()).html("");
               break;
            }
         }
      })
    }
    function __typeahead(){
       $(".form-control").click(function() {
           var id = this.id;
           var inputUrl = $(this).attr("typeaheadurl");
           var length = $(this).attr("length");
           // alert(ajaxUrl);
           if (inputUrl != null) {
               $("#"+id).typeahead({
                   source:function(query,process){
                     $('#fd, .fd').html("<i class='fa fa-spinner fa-spin'></i>");
                     return $.get(inputUrl,function(data){
                       data=$.parseJSON(data);
                       $('#fd, .fd').html('');
                       return process(data.keyword);
                     })
                   },
                   minLength:length,
                   limit:20
               }).focus();
           }
       });
       $("#no_reff").change(function(){
           var jenis = $('#jenis_picking').val();
           var no_trans = $(this).val();
           var url = '';
           if(jenis == 'PKB'){
               url = http+'/part/get_picking/pkb';
           }else if(jenis == 'SO'){
               url = http+'/part/get_picking/so';
           }else if(jenis == 'RETUR'){
               url = http+'/part/get_picking/retur';
           }
           $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");
           $.getJSON(url,{
           no_trans:no_trans
           }, function(data, status){
               if(status == 'success'){
                 $('#pkb_list tbody').html(data);
               }
               $(".load-form").html('');
           });
       });
    }
    function check_list(part_number,jml_asli,datax){
      var bariskes = $('#pkb_list > tbody > tr').length;
      var jml=0;
      for (x=0; x < bariskes; x++){
        var partnum=$("#pkb_list > tbody > tr:eq("+x+") > td:eq(1)").text();
        if(part_number === partnum){
          jml += parseFloat($("#pkb_list > tbody > tr:eq(" + x + ") > td:eq(6)").text());
        }
        /*console.log(part_number);
        console.log(partnum);
        console.log(jml);*/
      }
      var total_picking = parseFloat(jml_asli);
      if((total_picking - jml)==0){
        alert('Part Number sudah ada di list..');
        return false;
      }else{
        if(datax){
          var dx=datax.findIndex(obj => obj['PARTNUMBER'] === part_number);
          $('#qty_picking').val(parseFloat(total_picking) - parseFloat(jml)).focus().select().removeClass("disabled-action")
          $('#part_desc').val(datax[dx]["text"])
          $('#harga_jual').val(datax[dx]["harga"])
          $('#harga_sp').val(datax[dx]["price"])
          $('#picking_asli').val(jml_asli);
          $('#detail_id').val(datax[dx]["id"])
          __getGudang(part_number,'STK');
        }
      }
    };
    function __addItem(){
      var bariskes=0; var stok=0;
      var total_bayars=$('#kd_bin option:selected').text();
      var total_bayar = total_bayars.split('[');
      total_bayar = total_bayar[1].split(']');
      stok = total_bayar[0].replace(/,/g,'');
      var total_beli= $('#qty_picking').val().replace(/,/g,'');
      var pick_asli = $('#picking_asli').val().replace(/,/g,'');
      var bin= $('#kd_bin').val();
      var gdd= $('#kd_gudang').val()
      if(!gdd || !bin){return false;}
      if(parseFloat(total_beli)===0){ return false;}
      var part_number = $('#part_number').val();
      check_list(part_number,pick_asli);
      var html="";
      if(parseFloat(stok) < parseFloat(total_beli)){ alert("Qty Stock Tidak Mencukupi!"); $('#qty_picking').focus().select(); return}
      if(parseFloat(total_beli) > parseFloat(pick_asli)){ alert("Qty Picking Tidak Sesuai");$('#qty_picking').focus().select(); return }
      bariskes = $('#pkb_list > tbody > tr').length;
      html +="<tr id='"+bariskes+"'>";
      html +="<td class='text-center'>"+(bariskes+1)+"</td>";//0
      html +="<td class='hidden'>"+$('#part_number').val()+"</td>";//1
      html +="<td class='hidden'>"+ $('#harga_jual').val() +"</td>";//2
      html +="<td class='hidden'>"+ $('#harga_sp').val() +"</td>";//3
      html +="<td class='text-center'><a class='hapus-item' onclick='hapuse("+bariskes+");' role='button'><i class='fa fa-trash'></i></a></td>"; //4
      html +="<td class='table-nowarp'>"+ $('#part_desc').val() +"<span class='badge pull-right' title='Stock On Hand'>"+$('#soh').val()+"</span></td>";
      html +="<td class='text-right'>"+ $('#qty_picking').val() +"</td>";//6
      html +="<td class='text-center'>"+ $('#kd_gudang').val() +"</td>";//7
      html +="<td class='text-center'>"+ $('#kd_bin').val() +"</td>";//8
      html +="<td class='text-center'>"+ $('#detail_id').val() +"</td>";//9
      html +="</tr>";
      $('#pkb_list > tbody').append(html);
      $('#kd_gudang').val('').select().addClass("disabled-action");
      $('#kd_bin').val('').select().addClass("disabled-action");
      $('#qty_picking').val('0').addClass("disabled-action");
      $('#soh').val('0').addClass("disabled-action");
      $('#part_number').val('').select();
   }
   function __generate_stock(){
      $.getJSON(http+"/part/parts4gen",{'d':'1'},function(result){
         //console.log(result);
      });
   }
   function __deleteBtn(){
       $('.hapus-item').click(function(){
           $(this).parents('tr').remove();
       });
   };
   function __getBarangSP(){
      var kd_kategori = 'Part';
      $.getJSON(http+"/inventori/list_sp_w_stock/true",{'jt':kd_kategori},function(result){
         var datax=[];
         console.log(result);
         $.each(result.message,function(e,d){
            datax.push({
              'value' :d.PART_NUMBER,
              'ID' : d.PART_NUMBER,
              'DESKRIPSI' : d.PART_DESKRIPSI ,
              'STOCK': d.JUMLAH_SAK,
              'text': d.PART_DESKRIPSI,
              'description':d.JUMLAH_SAK
            });
         })
         $('#kd_part').inputpicker({
            data:datax,
            fields:['ID','DESKRIPSI','STOCK'],
            fieldText:'text',
            fieldValue:'value',
            filterOpen: true,
            headShow:true,
            pagination: true,
            pageMode: '',
            pageField: 'p',
            pageLimitField: 'per_page',
            limit: 10,
            pageCurrent: 1,
            urlDelay:1
         })
         .on("change",function(e){
            e.preventDefault();
            var part_number=$(this).val();
            var url = http+"/sparepart/hargapart/true";
            $.getJSON(url,{"part_number":part_number},function(result){
            $.each(result,function(e,d){
               var harga_jual=0;
               harga_jual =(typeof(d.HARGA_JUAL)!= "undefined" && d.HARGA_JUAL !== null)?d.HARGA_JUAL:d.HET
               $('#part_desc').val(d.PART_DESKRIPSI);
               $('#qty').val("1");
               $('#harga_sp').val(parseFloat(harga_jual));     
               $('#harga_sp').mask("#.##0",{reverse: true});
            })
         })
       })
      })
   }
   function __data(){
      var bariskex=0;
      bariskex = $('#pkb_list > tbody > tr').length;
      var dataxx=[];
      for(iz=0;iz< bariskex;iz++){
          $(".qurency").unmask();
          dataxx.push({
             'part_number': $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(1)").text(),
             'price'      : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(2)").text(),
             'harga_jual' : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(3)").text(),
             'jumlah'    : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(6)").text(),
             'kd_gudang' : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(7)").text(),
             'kd_rakbin' : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(8)").text(),
             'id'        : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(9)").text()
          });
          $(".qurency").mask('#.##0', {reverse: true});
      }
      // console.log('jmlbaris: '+bariskex)
      // console.log(dataxx)
      return dataxx;
   }
   function storeData(){
      var data_form=__data();
      // console.log(data_form);
      $('#loadpage').removeClass("hidden");
      var total_detail = data_form.length;
      var defaultBtn = $("#submit-btn").html();
      $("#submit-btn").addClass("disabled");
      $("#submit-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
      $(".alert-message").fadeIn();
      var formData = $("#partform").serialize();
      var act = $("#partform").attr('action');
         if(total_detail > 0){
            $.ajax({
               url: act,
               type: 'POST',
               data: formData+"&detail="+JSON.stringify(data_form),
               dataType: "json",
               success: function (result) {
                  if (result.status == true) {
                     $('.success').animate({  top: "0" }, 500);
                     $('.success').html(result.message).show();
                     if (result.location != null) {
                        setTimeout(function () {location.replace(result.location)}, 1000);
                     } else {
                        setTimeout(function () {location.reload();}, 1000);
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
            });
         }else{
            $('.error').animate({
               top: "0"
            }, 500);
            $('.error').html('Tidak ada data yang di simpan!');
            setTimeout(function () {
               hideAllMessages();
               $("#submit-btn").removeClass("disabled");
               $("#submit-btn").html(defaultBtn);
               $('#loadpage').addClass("hidden");
            }, 2000);
         }
      return false;
   }
   function hapuse(id){
      $('tr#'+id).remove();
   }
</script> 