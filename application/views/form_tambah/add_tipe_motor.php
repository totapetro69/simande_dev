<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">List Tipe Motor Baru</h4>
</div>
 <?php
    function filter_by_value ($array, $index, $value){ 
        $newarray=array();
        if(is_array($array) && count($array)>0)  
        { 
            foreach(array_keys($array) as $key){ 
                $temp[$key] = $array[$key][$index]; 
                 
                if ($temp[$key] == $value){ 
                    $newarray = $array[$key]; 
                } 
            } 
        } 
      return $newarray; 
    } 
?>
<div class="modal-body">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" style="height: 350px; overflow: auto;">
            <?php
            $databaru = array();
            $datalokal = array();
            $js=array();
            $totalData=0;
            $list=array();
            $data=array();
            //var_dump($listmd);
            if(isset($listmd)){
                if($listmd->status==true){
                    $js=json_decode($listmd->message,true);
                    $n=0;$webservice="";$simande="";
                    $compare=-1;
                    for($i=0;$i < count($js);$i++){
                         if(isset($dataxx)){
                            if($dataxx["totaldata"] >0){
                                $datax=array();
                                $datax =(filter_by_value($dataxx["message"],"KD_ITEM",$js[$i]["kditem"]));
                                $datax =(json_encode($datax));
                                $datax =json_decode($datax);
                                if(isset($datax)){
                                    if(isset($datax->KD_TYPEMOTOR)){
                                        $simande = (substr($datax->TGL_AWALEFF,0,4)!='1900')?$datax->KD_TYPEMOTOR.$datax->NAMA_TYPEMOTOR.$datax->KD_WARNA.$datax->KET_WARNA.$datax->NAMA_PASAR.number_format((double)$datax->CC_MOTOR,2).$datax->KD_ITEM.$datax->NAMA_ITEM.$datax->JENIS_MOTOR.str_replace('-','',$datax->TGL_AWALEFF).str_replace('-','',$datax->TGL_AKHIREFF).number_format((double)$datax->CBU,2).$datax->SEGMENT.$datax->TRIO_STATUS.$datax->SUB_KATEGORI:
                                        $datax->KD_TYPEMOTOR.$datax->NAMA_TYPEMOTOR.$datax->KD_WARNA.$datax->KET_WARNA.$datax->NAMA_PASAR.number_format((double)$datax->CC_MOTOR,2).$datax->KD_ITEM.$datax->NAMA_ITEM.$datax->JENIS_MOTOR.number_format((double)$datax->CBU,2).$datax->SEGMENT.$datax->TRIO_STATUS.$datax->SUB_KATEGORI;
                                    }
                                }
                                $webservice = ($js[$i]["kdtipe"].$js[$i]["ket1"].$js[$i]["kdwarna"].$js[$i]["ketwarna"].$js[$i]["ket2"].number_format((double)$js[$i]["kapasitas"],2).$js[$i]["kditem"].$js[$i]["ketwarna2"].$js[$i]["kategori"].tglToSql($js[$i]["bgneffd"]).tglToSql($js[$i]["lsteffd"],'-').number_format((double)$js[$i]["vcbu"],2).$js[$i]["segment"].$js[$i]["triostatus"].$js[$i]["subkategori"]);
                                $compare =strcasecmp(md5(strtoupper(trim($webservice))),md5(strtoupper(trim($simande))));
                                
                            }
                        }
                        //break;
                        if((int)$compare != 0){
                            // echo $compare."<br>".(strtoupper(trim($webservice)))."<br>".strtoupper(trim($simande))."<br>";
                            $list['status'] =true;
                            $list["message"][$n]= $js[$i];
                            $n++;
                        }
                        //$compare=-1;
                        if($n==300){
                            break;
                        }
                        $compare=-1;
                        
                    }
                    if($list){
                        $data=(object)($list);
                    }
                
                //var_dump($data);exit();
                $databaru =(isset($data->message))? (array)($data->message):array();           
                $dataterbaru = array();
                $totalData = 0;
                    if ($databaru) {
                        ?>
                        <table class="table table-striped b-t b-light table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode TM</th>
                                    <th>Nama TM</th>
                                    <th>KodeWarna</th>
                                    <th>Ket.Warna</th>
                                    <th>NamaPasar</th>
                                    <th>CCMotor</th>
                                    <th>KodeItem</th>
                                    <th>NamaItem</th>
                                    <th>JenisMotor</th>
                                    <th>TglAwalEff</th>
                                    <th>TglAkhirEff</th>
                                    <th>Ket</th>
                                    <th>Segment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = $this->input->get('page');
                                for ($i = 0; $i < count($databaru); $i++) {
                                    $compare = -1;
                                    $no ++;
                                    if ($compare != 0) {
                                        ?>
                                        <tr>
                                            <td><?php echo $no; ?></td>
                                            <td class="table-nowarp"><?php echo $databaru[$i]["kdtipe"]; ?></td>
                                            <td class="table-nowarp"><?php echo $databaru[$i]["ket1"]; ?></td>
                                            <td class="table-nowarp"><?php echo $databaru[$i]["kdwarna"]; ?></td>
                                            <td class="table-nowarp"><?php echo $databaru[$i]["ketwarna"]; ?></td>
                                            <td class="table-nowarp"><?php echo $databaru[$i]["ket2"]; ?></td>
                                            <td class="table-nowarp"><?php echo $databaru[$i]["kapasitas"]; ?></td>
                                            <td class="table-nowarp"><?php echo $databaru[$i]["kditem"]; ?></td>
                                            <td class="table-nowarp"><?php echo $databaru[$i]["ketwarna2"]; ?></td>
                                            <td class="table-nowarp"><?php echo $databaru[$i]["kategori"]; ?></td>
                                            <td class="table-nowarp"><?php echo $databaru[$i]["bgneffd"]; ?></td>
                                            <td class="table-nowarp"><?php echo $databaru[$i]["lsteffd"]; ?></td>
                                            <td class="table-nowarp"><?php echo $databaru[$i]["vcbu"]; ?></td>
                                            <td class="table-nowarp"><?php echo $databaru[$i]["segment"]; ?></td>
                                        </tr>
                                        <?php
                                        //create new array();
                                        $dataterbaru[$totalData] = ($databaru[$i]);
                                        $totalData++;
                                    } //end if
                                } //end for
                                ?>
                            </tbody>
                        </table>
                        <?php } else {
                        ?>
                        <i class="fa fa-info-circle big"></i> <b>Tidak ada data baru</b>
                        <?php 
                    }

                }else{
                    ?>
                    <h2><i class="fa fa-info-circle fa-fw"></i> Webservice Error : <?php echo $listmd->message;?></h2>
                    <?php
                }    
            } ?>

        </div>
    </div>
</div>
<?php
//$dataterbaru = json_decode($dataterbaru);
 
$json = "";
if (isset($dataterbaru)) {
    $json = str_replace(array("\n", " "), " ", $dataterbaru);
    $json = str_replace(array("\r", " "), " ", $dataterbaru);
    //$json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/','$1"$3":',$dataterbaru);
    $json = json_encode($json);
    $json = str_replace(array("\n", " "), " ", $json);
    $json = str_replace(array("\r", " "), " ", $json);
    //echo addslashes($json);
}
?>

<div class="modal-footer"><!-- {elapsed_time} -->
    <div class="hidden" id="datajson"><?php echo addslashes($json); ?></div>
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button type="button" id="submit-btn" class="btn btn-danger <?php echo ($totalData == 0) ? 'hidden' : ''; ?>" 
            onclick="AddDataJSON('<?php echo base_url("motor/update_tipe_motor"); ?>')">Update Data (<?php echo $totalData; ?>)</button>
</div>