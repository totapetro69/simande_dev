<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">List Master KPB by Tipe Motor</h4>
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
                              <th>No Mesin</th>
                              <th>Tipe Motor</th>
                              <th>Premium</th>
                              <th>BKM 1</th>
                              <th>BKM 2</th>
                              <th>BKM 3</th>
                              <th>BKM 4</th>
                              <th>BSE 1</th>
                              <th>BSE 2</th>
                              <th>BSE 3</th>
                              <th>BSE 4</th>
                              <th>BCL 1</th>
                              <th>BCL 2</th>
                              <th>BCL 3</th>
                              <th>BCL 4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        for ($i = 0; $i < count($databaru); $i++) {
                            $compare = -1;
                            for ($n = 0; $n < count($datalokal); $n++) {
                                $compare = strcasecmp($datalokal[$n]["NO_MESIN"], $databaru[$i]["nomesin"]);
                                $compare += strcasecmp($datalokal[$n]["TIPE_MOTOR"], $databaru[$i]["tipemotor"]);
                                $compare += strcasecmp($datalokal[$n]["PREMIUM"], $databaru[$i]["premium"]);
                                if ($compare == 0) {
                                    continue 2;
                                }
                            }
                            $no ++;
                            if ($compare != 0) {
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["nomesin"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["tipemotor"]; ?></td>
									<td class="table-nowarp"><?php echo $databaru[$i]["premium"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["bkm1"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["bkm2"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["bkm3"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["bkm4"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["bse1"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["bse2"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["bse3"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["bse4"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["bcl1"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["bcl2"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["bcl3"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["bcl4"]; ?></td>
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
            onclick="AddDataJSON('<?php echo base_url("master_service/update_kpb"); ?>')">Update Data (<?php echo $totalData; ?>)</button>
</div>
