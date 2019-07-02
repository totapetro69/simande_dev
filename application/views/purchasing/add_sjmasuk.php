
<?php
  $default_dealer=$this->session->userdata("kd_dealer");
  $datalokal  =(is_array($list["message"]))? $list["message"]:array();
  $datamd     =(is_array($listmd))?$listmd:null;//array('status'=>false,'message'=>'Tidak ada data baru');
  /*print_r($datalokal);
  print_r($datamd);
  if($datamd )exit();  */    
  $databaru=($datamd);
  $dataterbaru=array();
  $totalData=0;
  $ttsj="";$ttitem="";
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel"><i class='fa fa-list-ul fa-fw'></i> UNIT MOTOR SHIPPING LIST (UMSL) </h4>
</div>
<div class="modal-body">
  <div class="panel" id="frmpanel">
          <div class="panel-heading">
            <i class="fa fa-list fa-fw"></i> List UMSL 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
           </div>
          <div class="panel-body panel-body-border" >
            <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('umsl/addsj');?>">
              <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6">
                  <div class="form-group">
                    <label>Nama Dealer</label>
                      <select id="kd_dealer" name="kd_dealer" class="form-control" disabled="disabled">
                        <option value=''>--Pilih Dealer--</option>
                        <?php 
                          if($dealer){
                            if(is_array($dealer->message)){
                              foreach ($dealer->message as $key => $value) {
                                $select=($default_dealer==$value->KD_DEALER)?'selected':'';
                                echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
                              }
                            }
                          }
                          ?>
                      </select>
                  </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                  <div class="form-group">
                    <label>Dari Tanggal</label><?php $today=date("Ymd");?>
                    <div class="input-group input-append date" id="date">
                      <input type="text" id="tgl_data" name="tgl_data" value="<?php echo tglfromSql(getPrevDays($today,5));?>" required class="form-control" placeholder="Masukan periode tanggal mundur" >
                      <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                  </div>
                </div>
              </div>
              <!-- <div class="row padding-left-right-10">
                
              </div> -->
            </form>
          </div>
  </div>    
  
  <?php
 // var_dump($databaru);
  if($databaru!=null){
  ?>
  <div class="panel margin-bottom-5">
      <div class="panel-heading"><i class='fa fa-list'></i> List UMSL Terbaru <span id="kdd"></span> </div>
    <div class="panel-body panel-body-border">
      <div class="row">
        <div class="padding-left-right-5">
          <?php
          //if(($databaru["s"]))
          if(is_array($databaru) ){
            //if($databaru["status"]==TRUE){
          ?>
          <div class="table-responsive h350">
          <div class="col-xs-12 col-sm-12 col-md-12">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Tanggal</th>
                  <th>Kode Dealer</th>
                  <th>No.Surat Jalan</th>
                  <th>No. DO</th>
                  <th>Nama Tagihan</th>
                  <th>No.Faktur</th>
                  <th>No.PO</th>
                  <!-- <th>Keterangan</th> -->
                </tr>
                <tr>
                  <th>&nbsp;</th>
                  <th>Kode Item</th>
                  <th>Nama Item</th>
                  <th>No Rangka</th>
                  <th>No Mesin</th>
                  <th>Tahun</th>
                  <th>Vno Rangka1</th>
                  <th colspan="2">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                  <?php 
                    $ttitem=0;
                    for($i=0; $i < count($databaru); $i++){
                      $nosjws="";$cmp=0;$jmlitemws=0;
                      foreach ($databaru[$i] as $key => $value) {
                          $nosjlocal="";$jmlitemLocal=0;
                          $nosjws=$databaru[$i][$key][0]["noSj"];
                          for($xn=0;$xn < count($datalokal);$xn++){
                            if(str_replace(".","/",$nosjws)==($datalokal[$xn]["NO_SJMASUK"])){
                              $cmp=$i;
                              $jmlitemLocal++;
                            }

                          }
                          $jmlitemws=count($databaru[$i][$key]);
                      // if($jmlitemLocal!==$jmlitemws){
                      ?>
                      <tr class="info">
                          <td class="table-nowarp"><?php echo $i+1;?></td>
                          <td class="table-nowarp"><?php echo ($databaru[$i][$key][0]["tglSj"]);?></td>
                          <td class="table-nowarp"><?php echo $databaru[$i][$key][0]["kdDlr"];?></td>
                          <td class="table-nowarp"><?php echo str_replace(".", "/", $key);?></td>
                          <td class="table-nowarp"><?php echo $databaru[$i][$key][0]["noDo"];?></td>
                          <td class="table-nowarp"><?php echo $databaru[$i][$key][0]["namaTagihan"];?></td>
                          <td class="table-nowarp"><?php echo $databaru[$i][$key][0]["nofaktur"];?></td>
                          <td class="table-nowarp"><?php echo $databaru[$i][$key][0]["podlrnopoint"];?></td>
                          <!-- <td><?php echo $jmlitemws."=".$jmlitemLocal;?></td> -->
                      </tr>
                      <?php
                        // }
                        $nom=0;
                        for($x=0;$x <count($databaru[$i][$key]);$x++){
                          $compare=0;$listws="";$listLocal="";$listnosj="";
                          
                          for($nn=0;$nn < count($datalokal);$nn++){
                            
                            if(str_replace(".","/",$nosjws).$databaru[$i][$key][$x]["noMesin"].$databaru[$i][$key][$x]["noRangka"].$databaru[$i][$key][$x]["vnoRangka1"]
                              ==($datalokal[$nn]["NO_SJMASUK"]).$datalokal[$nn]["NO_MESIN"].$datalokal[$nn]["NO_RANGKA"]){
                              $listLocal =$datalokal[$nn]["NO_SJMASUK"].$datalokal[$nn]["NO_MESIN"].$datalokal[$nn]["NO_RANGKA"].$datalokal[$nn]["VNORANGKA1"];
                            }
                          }
                          $listnosj=str_replace(".","/",$databaru[$i][$key][$x]["noSj"]);
                          $listws =$listnosj.$databaru[$i][$key][$x]["noMesin"].$databaru[$i][$key][$x]["noRangka"];
                          $compare=strcasecmp(strtoupper($listLocal),strtoupper($listws));
                       
                        // if($compare!=0){
                          $nom++;
                        ?>
                          <tr>
                            <td class="table-nowarp text-right"><?php echo $nom;?></td>
                            <td class="table-nowarp"><?php echo $databaru[$i][$key][$x]["kdItem"];?></td>
                            <td class="table-nowarp"><?php echo $databaru[$i][$key][$x]["kdItem"];?></td>
                            <td class="table-nowarp"><?php echo $databaru[$i][$key][$x]["noRangka"];?></td>
                            <td class="table-nowarp"><?php echo $databaru[$i][$key][$x]["noMesin"];?></td>
                            <td class="table-nowarp"><?php echo $databaru[$i][$key][$x]["thProduksi"];?></td>
                            <td class="table-nowarp"><?php echo $databaru[$i][$key][$x]["vnoRangka1"];?></td>
                            <td colspan="2"><?php echo($x==0)? $databaru[$i][$key][0]["ekspedisi"]." - ".$databaru[$i][$key][0]["nopolisi"]:"";?></td>
                            <!-- <td class="table-nowarp"><?php echo strcasecmp($listLocal,$listws);?></td> -->
                          </tr>
                        <?php
                            $dataterbaru[$totalData]=($databaru[$i][$key][$x]);
                            $totalData++; 
                          // }
                      }
                      $ttitem +=count($databaru[$i][$key]);

                    }
                  }
                  $ttsj="<i class='fa fa-bookmark-o fa-fw'></i>Total Surat Jalan  <span class='badge'> $i </span><br>
                  <i class='fa fa-sticky-note-o fa-fw'></i> Total Item  <span class='badge'>".($ttitem)."</span>";
                  ?>
              </tbody>
            </table>
          </div>
        </div>
          <?php 
            
            }else{
              echo BelumAdaData(8);
           } ?>
       </div> 
    </div>
  </div>
  </div>
</div>
<?php
  }
  $json="";
    if($dataterbaru){
    $json = str_replace(array("\n"," ")," ",$dataterbaru);
    $json = str_replace(array("\r"," ")," ",$dataterbaru);
    //$json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/','$1"$3":',$dataterbaru);
    $json = json_encode($json);
    $json = str_replace(array("\n"," ")," ",$json);
    $json = str_replace(array("\r"," ")," ",$json);
    //echo addslashes($json);
    }

?>
<div class="modal-footer">
  <span id="ttl" class="pull-left small"><?php //echo $ttsj;?></span>
  <div class="hidden" id="datajson"><?php echo addslashes($json);?></div>
    <button type="button" id="keluar" class="btn btn-default" data-dismiss="modal"><i class='fa fa-close fa-fw'></i> Keluar</button>
    <?php if($databaru==NULL){ ?>
      <button type="button" id="btnCheck" class="btn btn-primary pull-right"><i class='fa fa-update'></i> Check SJ</button>
    <?php }else{ ?>
     <button type="button" id="submit-btn" class="btn btn-danger <?php echo ($totalData==0)?'hidden':'';?>" 
        onclick="AddDataJSON('<?php echo base_url("umsl/add_sj_simpan");?>')">Update Data <span class='badge'><?php echo $totalData;?></span></button>
    <?php }?>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    var date = new Date();
    date.setDate(date.getDate());

    $('#date').datepicker({ 
        format: 'dd/mm/yyyy',
        daysOfWeekHighlighted:"0",
        autoclose: true,
        setDate:date
    });

    $('#btnCheck').click(function(){
      $('#kd_dealer').removeAttr('disabled');
      $("#btnCheck").addClass("disabled");
      $("#keluar").addClass("disabled");
      $("#btnCheck").html("<span class='fa fa-spinner fa-spin'></span> Loading");
     // $(".alert-message").fadeIn();
        $.ajax({
          url   : $('#addForm').attr("action"),
          type  :"POST",
          data  :$('#addForm').serialize(),
          dataType:'html',
          success:function(result){
            
            $("#myModalLg .modal-content").html($.parseJSON(result));
            $('#frmpanel').hide();
            $("#kdd").html("<b> Dealer : "+$('#kd_dealer option:selected').text()+"</b> -> Per Tanggal : "+$("#tgl_data").val())
            $("#btnCheck").removeClass("disabled");
            $("#keluar").removeClass("disabled");
            $("#btnCheck").html('Check SJ');
            $('#kd_dealer').attr('disabled','disabled');
            },
          error:function(jqXHR, textStatus, errorThrown){
            $("#myModalLg.modal-body").html("&nbsp;&nbsp;<i class='fa fa-info fa-fw'></i> Ada kesalahan koneksi. Hubungi IT Dept ["+jqXHR.status+"]");
            $('#frmpanel').hide();
            $('#kd_dealer').attr('disabled','disabled');
          }
        });//end of ajax
    });//end of button
  })
  
</script>