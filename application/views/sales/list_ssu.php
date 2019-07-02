<?php
if (!isBolehAkses()) {   redirect(base_url() . 'auth/error_auth');}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>
<section class="wrapper">
   <div class="breadcrumb margin-bottom-10">
      <?php echo breadcrumb(); ?>
      <div class="bar-nav pull-right ">
         <a id="pull-btn" class="btn btn-default <?php echo $status_p;?>" role="button">
            <i class="fa fa-download fa-fw"></i> Tarik Data
         </a>
      </div>
   </div>
   <div class="col-lg-12 padding-left-right-10">
      <div class="panel margin-bottom-10">
         <div class="panel-heading">
            <i class="fa fa-list fa-fw"></i> List Data SSU
            <span class="tools pull-right">
               <a class="fa fa-chevron-up" href="javascript:;"></a>
            </span>
         </div>
         <div class="panel-body panel-body-border" style="display: none;">
            <form id="filterForm" action="<?php echo base_url('ssu/transaksi_ssu') ?>" class="bucket-form" method="get">
               <div class="row">
                  <div class="col-xs-12 col-sm-12">
                     <div class="form-group">
                        <label>Pencarian</label>
                        <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Cari berdasarkan nomor rangka" autocomplete="off" value="<?php echo $this->input->get('keyword');?>">
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
   <div class="col-lg-12 padding-left-right-10">
      <div class="panel panel-default">
         <div class="table-responsive">
               <table id="pkb_list" class="table table-striped table-bordered nowarp">
                  <thead>
                     <tr class="no-hover"><th colspan="15" ><i class="fa fa-list fa-fw"></i> Data Telah Didownload</th></tr>
                     <tr>
                        <th rowspan="2" style="width:40px;">No.</th>
                        <th rowspan="2" style="width:45px;"></th>
                        <th colspan="2">No. Trans</th>
                        <th colspan="4">Nama File</th>
                     </tr>
                     <tr>
                        <th>No. Mesin</th>
                        <th>No. SPK</th>
                        <th>Tanggl SO</th>
                        <th>UDSTK</th>
                        <th>CDDB</th>
                        <th>UDPRG</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $no = $this->input->get('page');
                     if (isset($list)){                            
                        if ($list->totaldata >0){
                           foreach ($list->message as $key => $header) {
                              $no ++;
                              ?>
                              <tr class="total bold">
                                 <td><?php echo $no; ?></td>
                                 <td>
                                    <a title="Reload Data SSU" onclick="reload_data('<?php echo $header->NO_TRANS;?>');" class=""><i class='fa fa-cog'></i></a>
                                    <a id="download_<?php echo $no;?>" onclick='downloadBtn("<?php echo base_url().'ssu/generate_file_ssu?n='.$header->NO_TRANS;?>","<?php echo $no ;?>");' class="active disabled-action">
                                       <i class="fa fa-download" data-toggle="tooltip" data-placement="left" title="download"></i>
                                    </a>
                                    <input type="checkbox" id="chk_all_<?php echo $no;?>" name="" class="a_<?php echo $no;?>">
                                 </td>
                                 <td colspan="2"><?php echo  $header->NO_TRANS; ?></td>
                                 <td colspan="4"><?php echo  $header->NAMA_FILE; ?></td>
                              </tr>
                              <?php
                              if(isset($detail)){
                                $n=0;
                                 if($detail[$header->NO_TRANS]->totaldata >0){
                                    foreach ($detail[$header->NO_TRANS]->message as $key => $row){
                                      $dwn_ok_{$no}=0;
                                      $dwn_ok_{$no} +=$row->UDSTK;
                                      $dwn_ok_{$no} +=$row->CDDB;
                                      $dwn_ok_{$no} +=$row->UDPRG;
                                      $dsbl =($dwn_ok_{$no}==3)?'':"disabled-action";
                                      $chks =((int)$row->STATUS_DOWNLOAD >1)?'':"";
                                      $ttl =((int)$row->STATUS_DOWNLOAD >1)?"tooltip='Sudah di download'":"";
                                      $n++;
                                    ?>
                                    <tr id="<?php echo  $this->session->flashdata('tr-active') == $row->NO_MESIN ? 'tr-active' : ' '; ?>" >
                                       <td class='text-right' style="padding-right: 5px !important"><?php echo $n;?></td>
                                       <td class='text-center'>
                                          <a href="<?php echo base_url('ssu/edit_ssu/' . $row->NO_MESIN); ?>");' role="button"">
                                             <i data-toggle="tooltip" data-placement="left" title="Ubah" class="fa fa-edit text-success text-active"></i>
                                          </a>
                                          <input type="checkbox" <?php echo $ttl;?> value='<?php echo $row->NO_MESIN;?>' id="c_<?php echo $row->NO_MESIN;?>" class="<?php echo "r_".$no." ".$dsbl." ".$chks;?>">
                                       </td>
                                       <td class='table-nowarp'><?php echo  $row->NO_MESIN; ?><span class="pull-right"><?php echo ((int)$row->STATUS_DOWNLOAD >1)? "<i class='fa fa-check'></i>":"";?></span></td>
                                       <td class='table-nowarp'><?php echo  $row->NO_SPK; ?></td>
                                       <td class='table-nowarp'><?php echo  tglFromSql($row->TGL_SO); ?></td>
                                       <td class='text-center'><?php echo  $row->UDSTK == 1? "<i class='fa fa-check-circle'></i>":"<i class='fa fa-times-circle' style='color:red'></i>" ; ?></td>
                                       <td class='text-center'><?php echo  $row->CDDB == 1? "<i class='fa fa-check-circle'></i>":"<i class='fa fa-times-circle' style='color:red'></i>" ; ?></td>
                                       <td class='text-center'><?php echo  $row->UDPRG == 1? "<i class='fa fa-check-circle'></i>":"<i class='fa fa-times-circle' style='color:red'></i>" ; ?></td>
                                    </tr>
                                    <?php
                                    }
                                 }
                              }
                           }                        
                        }else{
                           echo belumAdaData(8);
                        }
                     }
                     ?>
                 </tbody>
             </table>
         </div>
         <footer class="panel-footer">
             <div class="row">
                 <div class="col-sm-5">
                     <small class="text-muted inline m-t-sm m-b-sm"> 
                        <?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total Data " . $list->totaldata . " items</i>") : '' ?>
                     </small>
                 </div>
                 <div class="col-sm-7 text-right text-center-xs">                
                     <?php echo $pagination; ?>
                 </div>
             </div>
         </footer>
      </div>
   </div>
   <?php loading_proses();?>
</section>
<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function () {
    $("#pull-btn").click(function(){
        var defaultBtn = $("#pull-btn").html();
        $('#loadpage').removeClass("hidden");
        $("#pull-btn").addClass("disabled");
        $("#pull-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
        $(".alert-message").fadeIn();
        var url = http+'/ssu/generate_ssu';
        $.getJSON(url, function(result) {
         console.log(result);
            if (result.status) {
               $('.success').animate({top: "0" }, 500);
               $('.success').html(result.message);
               setTimeout(function(){
                  location.reload();
               }, 2000);
            } 
          else {
              $('.error').animate({
                  top: "0"
              }, 500);
              $('.error').html(result.message);
              setTimeout(function () {
                  hideAllMessages();
                  $("#pull-btn").removeClass("disabled");
                  $("#pull-btn").html(defaultBtn);
                  $('#loadpage').addClass("hidden");
                  location.reload();
              }, 2000);
          }
        });
        return false;
    });
    $("#udprg-btn").click(function(){
    var defaultBtn = $("#udprg-btn").html();
    $("#udprg-btn").addClass("disabled");
    $("#udprg-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();
    var url = http+'/ssu/download_file_ssu';
    $.getJSON(url, function(data, status) {
      if (data.status == true) {
          $('.success').animate({
              top: "0"
          }, 500);
          $('.success').html(data.message);
          location.replace(data.file);
          setTimeout(function(){
              location.reload();
          }, 2000);
      } 
      else {
          $('.error').animate({
              top: "0"
          }, 500);
          $('.error').html(data.message);
          setTimeout(function () {
              hideAllMessages();
              $("#udprg-btn").removeClass("disabled");
              $("#udprg-btn").html(defaultBtn);
              $('#loadpage').addClass("hidden");
          }, 2000);
      }
    });
    return false;
    });
    $('input[type="checkbox"]').click(function(){
      var grup=($(this).attr("class"));
      var lst =grup.split("_");
      // if($.trim(lst[0])=='r'){
         if($("."+$.trim(grup)+":checked").length >0){
           $('#download_'+lst[1]).removeClass("disabled-action");
         }else{
           $('#download_'+lst[1]).addClass("disabled-action");
         }
      // }
      if($.trim(lst[0])=="a"){
         if($("#chk_all_"+lst[1]).is(":checked")){
            $('.r_'+lst[1]).not('.disabled-action').prop("checked",true);
         }else{
            $('.r_'+lst[1]).prop("checked",false);
         }
      }
      console.log(grup+"=="+lst[0])
    })
});    
function downloadBtn(url, id){
    var defaultBtn = $("#download_"+id).html();
    $("#download_"+id).addClass("disabled");
    $("#download_"+id).html("<i class='fa fa-spinner fa-spin'></i>");
    var datax=[]
    $(".r_"+$.trim(id)).each(function(){
      if($(this).is(":checked")){
         datax.push($(this).val());
      }
    })
    console.log(datax);
    $('#loadpage').addClass("hidden");
    $.getJSON(url,{'mesin':datax}, function(data, status) {
      if (data.status == true) {
          $('.success').animate({
              top: "0"
          }, 500);
          $('.success').html(data.message).fadeIn();
         location.replace(data.file);
          setTimeout(function () {
              hideAllMessages();
              //location.reload();
              $("#download_"+id).removeClass("disabled");
              $("#download_"+id).html(defaultBtn);
              $('#loadpage').addClass("hidden");
              window.location.reload();
          }, 3000);
      } 
      else {
          $('.error').animate({
              top: "0"
          }, 500);
          $('.error').html(data.message).fadeIn();
          setTimeout(function () {
              hideAllMessages();
              $("#download_"+id).removeClass("disabled");
              $("#download_"+id).html(defaultBtn);
              $('#loadpage').addClass("hidden");
          }, 2000);
      }
    });
}
function reload_data(notrans){
   $('#loadpage').removeClass("hidden");
   $(".alert-message").fadeIn();
        var url = http+'/ssu/generate_ssu/true';
        $.getJSON(url,{'n':notrans}, function(data, status) {
          if (data.status == true) {
              $('.success').animate({top: "0"
              }, 500);
              $('.success').html(data.message);
              setTimeout(function(){
                  location.reload();
              }, 2000);
          } 
          else {
              $('.error').animate({
                  top: "0"
              }, 500);
              $('.error').html(data.message);
              setTimeout(function () {
                  hideAllMessages();
                  $("#pull-btn").removeClass("disabled");
                  $('#loadpage').addClass("hidden");
              }, 2000);
          }
        });
        return false;
}
</script>