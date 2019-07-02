<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">List Master GC</h4>
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
            if (array_key_exists("message", $databaru) === FALSE) {
                ?>
                <table class="table table-striped b-t b-light table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode GC</th>
                          <th>Nama Program</th>
                          <th>Tanggal Mulai</th>
                          <th>Tanggal Selesai</th>
                          <th>Kode Motor</th>
                          <th>S_AHM</th>
                          <th>S_MD</th>
                          <th>S_SD</th>
                          <th>LKPP Kalsel</th>
                          <th>LKPP Kalteng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        for ($i = 0; $i < count($databaru); $i++) {
                            $compare = -1;
                            //echo $datamd[$i]["kditem"]."| ".$datamd[$i]["kdtipe"]."\n";
                            for ($n = 0; $n < count($datalokal); $n++) {

                                $compare = strcasecmp($datalokal[$n]["KD_GC"], $databaru[$i]["programid"]);
                                $compare += strcasecmp($datalokal[$n]["KD_TYPEMOTOR"], $databaru[$i]["kdtipe"]);
                                $compare += strcasecmp($datalokal[$n]["START_DATE"], $databaru[$i]["startdate"]);
                                $compare += strcasecmp($datalokal[$n]["END_DATE"], $databaru[$i]["enddate"]);
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
                                    <td class="table-nowarp"><?php echo $databaru[$i]["programdc"]; ?></td>
									<td class="table-nowarp"><?php echo $databaru[$i]["startdate"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["enddate"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["kdtipe"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["sahm"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["smd"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["ssd"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["lkppkalsel"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["lkppkalteng"]; ?></td>
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
            onclick="AddDataJSON('<?php echo base_url("setup/update_gc"); ?>')">Update Data (<?php echo $totalData; ?>)</button>
</div>
