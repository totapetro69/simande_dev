<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">List Sales Kupon</h4>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" style="height: 350px; overflow: auto;">
            <?php
            $databaru = array();
            $datalokal = array();
            //$datamd     = array();
            $datalokal = (is_array($list["message"])) ? $list["message"] : array();
            $datamd = ($listmd === null && json_last_error() != JSON_ERROR_NONE) ? array() : json_decode($listmd, true);
            //var_dump($listmd);
              //exit(); 
            $databaru = ($datamd);
            $dataterbaru = array();
            $totalData = 0;
            //var_dump($datalokal);exit();
            if (isset($databaru)) {
                ?>
                <table class="table table-striped b-t b-light table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode</th>
                            <th>Sales Kupon</th>
                            <th>Dealer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        //print_r($databaru);exit();
                        for ($i = 0; $i < count($databaru); $i++) {
                            $compare = -1;
                            //echo $datamd[$i]["kditem"]."| ".$datamd[$i]["kdtipe"]."\n";
                            $ws="";
                            $ws=md5($databaru[$i]["programid"].$databaru[$i]["kota"].$databaru[$i]["programdesc"]);
                            for ($n = 0; $n < count($datalokal); $n++) {
                                $local="";
                                $local=md5($datalokal[$n]["KD_SALESKUPON"].$datalokal[$n]["KD_DEALER"].$datalokal[$n]["NAMA_SALESKUPON"]);
        //                         $compare = strcasecmp($datalokal[$n]["KD_SALESKUPON"], $databaru[$i]["programid"]);
								// $compare += strcasecmp($datalokal[$n]["KD_DEALER"], $databaru[$i]["kota"]);
                                $compare =strcasecmp($local, $ws);
                                if ($compare == 0) {
                                    continue 2;
                                }
                            }
                            $no ++;
                             
                            if ($compare != 0) {
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["programid"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["programdesc"]; ?></td>
									<td class="table-nowarp"><?php echo $databaru[$i]["kota"]; ?></td>
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
    <div class="hidden" id="datajson"><?php echo addslashes($json); ?></div>
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button type="button" id="submit-btn" class="btn btn-danger <?php echo ($totalData == 0) ? 'hidden' : ''; ?>" 
            onclick="AddDataJSON('<?php echo base_url("setup/update_saleskuponkota"); ?>')">Update Data (<?php echo $totalData; ?>)</button>
</div>
