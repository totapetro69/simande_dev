<div class="clearfix"></div>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">List Kecamatan</h4>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" style="height: 350px; overflow: auto;"><class="col-xs-12 col-sm-12 col-md-12" style="height: 350px; overflow: auto;">
            <?php
            $databaru = array();
            $datalokal = array();
            $datamd = is_array($listmd)?$listmd:array();
            $databaru = ($datamd);
            $dataterbaru = array();
            $totalData = 0;
            /*var_dump($databaru);
            exit(); */
            if (isset($databaru["message"])=== FALSE) {
                ?>
                <table class="table table-striped b-t b-light table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Kecamatan</th>
                            <th>Nama Kecamatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page'); $ada=0;
                        for ($i = 0; $i < count($databaru); $i++) {
                            $cmp="";$cmp1="";
                            $compare = -1;
                            $no ++;
                            if ($compare != 0) {
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["kdkec"]; ?></td>
                                    <td class="table-nowarp"><?php echo str_replace("'","",rtrim($databaru[$i]["kecamatan"])); ?></td>
                                </tr>
                                <?php
                                //create new array();
                                $dataterbaru[$totalData] = ($databaru[$i]);
                                $totalData++;
                            }//end if
                        } //end for
                        ?>
                    </tbody>
                </table>
                <?php } else {
                ?>
                <i class="fa fa-info-circle big"></i> <b>Tidak ada data baru</b>
                <?php }
            ?>
        </div>
    </div>
</div>
<?php
//$dataterbaru = json_decode($dataterbaru);

$json = "";
if ($dataterbaru) {
    $json = str_replace(array("\n", " "), " ", $dataterbaru);
    $json = str_replace(array("\r", " "), " ", $dataterbaru);
    //$json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/','$1"$3":',$dataterbaru);
    $json = json_encode($json);
    $json = str_replace(array("\n", " "), " ", $json);
    $json = str_replace(array("\r", " "), " ", $json);
    //echo addslashes($json);
}
?>
<div class="modal-footer">

    <div class="hidden" id="datajson"><?php echo addslashes($json);?></div>
    <button type="button" id="keluar" class="btn btn-default" data-dismiss="modal"><i class='fa fa-close fa-fw'></i> Keluar</button>
    <button type="button" id="submit-btn" class="btn btn-danger <?php echo ($totalData==0)?'hidden':'';?>" 
        onclick="AddDataJSON('<?php echo base_url("company/update_kecamatan");?>')"><i class='fa fa-chevron-up'></i> Update Kecamatan<lable class='badge'><?php echo $totalData;?></label>
    </button>

</div>