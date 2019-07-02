
<?php
  /*if (!isBolehAkses()) {
        redirect(base_url() . 'auth/error_auth');
    }*/

    $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
    $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
    $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
    $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

    // data perbandingan
   /* $localdata  =(is_array($local["message"]))?$local["message"]:array();
    $datamd     =(is_array($datamd))?$datamd:NULL;
    $dataterbaru="";
    $totaldata  =0;*/
    $databaru = array();
    $datalokal = array();
    $datamd = is_array($listmd)?$listmd:array();
    $databaru = ($datamd);
    $dataterbaru = array();
    $totalData = 0;
    
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel"><i class='fa fa-list-ul fa-fw'></i> List Harga Motor</h4>
</div>

<div class="modal-body">
<div class="table-responsive">
  <?php
    //if(!isset($databaru["message"])){
      //var_dump($databaru);
      ?>
       <table class="table table-hover table-bordered">
        <thead>
           <tr>
                <th>#</th>
                <th>KodeItem</th>
                <th>NamaItem</th>
                <th>Area</th>
                <th>Harga</th>
                <th>Harga OTR</th>
                <th>BBN</th>
                <th>Harga Dealer</th>
                <th>Harga DlrD</th>
                <th>PPH DlrK</th>
                <th>PPH DlrK2</th>
            </tr>
        </thead>
            <tbody>
                <?php
                $no = $this->input->get('page'); $ada=0;
                  for ($i = 0; $i < count($databaru); $i++) {
                    $cmp="";$cmp1="";
                    $compare = -1;
                    $no ++;
                    ?>
                          <tr><td><?php echo $no; ?></td>
                            <td><?php echo $databaru[$i]["kditem"]; ?></td>
                            <td class='table-nowarp'><?php echo $databaru[$i]["nama"]; ?></td>
                            <td class='text-center'><?php echo $databaru[$i]["area"]; ?></td>
                            <td class='text-right'><?php echo number_format($databaru[$i]["harga"],2,'.',','); ?></td>
                            <td class='text-right'><?php echo number_format($databaru[$i]["hrgotr"],2); ?></td>
                            <td class='text-right'><?php echo number_format($databaru[$i]["bbn"],2); ?></td>
                            <td class='text-right'><?php echo number_format($databaru[$i]["hrgdlr"],2); ?></td>
                            <td class='text-right'><?php echo number_format($databaru[$i]["hrgdlrd"],2); ?></td>
                            <td class='text-right'><?php echo number_format($databaru[$i]["pphdlrk"],2); ?></td>
                            <td class='text-right'><?php echo number_format($databaru[$i]["pphdlrk2"],2); ?></td>
                            
                          </tr>
                      <?php
                          $dataterbaru[$totalData] = ($databaru[$i]);
                          $totalData++;
                    
                  } //end for
                ?>
            </tbody>
        </table>
    <?php 
      /*}else{
      echo belumAdaData(10);
    }*/
    ?>
  </div>
</div>
<?php
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
    <div class="hidden" id="datajson"><?php echo addslashes($json);?></div>
    <button type="button" id="keluar" class="btn btn-default" data-dismiss="modal"><i class='fa fa-close fa-fw'></i> Keluar</button>
    <button type="button" id="submit-btn" class="btn btn-danger <?php echo ($totalData==0)?'hidden':'';?>" 
        onclick="AddDataJSON('<?php echo base_url("umsl/updatehargamotor");?>')"><i class='fa fa-chevron-up'></i> Update Harga <lable class='badge'><?php echo $totalData;?></label></button>
</div>

<script type="text/javascript">
    var $table = $('table.table');
    $table.floatThead({
        scrollContainer: function($table){
            return $table.closest('.table-responsive');
        }
    });
    $('#submit-btn').click(function(){
      $("#loadpage").removeClass('hidden');
    })
</script>