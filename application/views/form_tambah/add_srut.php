<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">List SRUT</h4>
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
            //var_dump($listmd);exit();
            $databaru = ($datamd);
            $dataterbaru = array();
            $totalData = 0;
            //var_dump($datalokal);exit();
            if (array_key_exists("message", $databaru) === FALSE) {
                ?>    
                <table class="table table-striped b-t b-light table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Dealer</th>
                            <th>No Terima Dealer</th>
                            <th>Tgl Terima</th>        
                            <th>No Mesin</th>
                            <th>No Rangka</th>
                            <th>No SUT</th>
                            <th>No SRUT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        for ($i = 0; $i < count($databaru); $i++) {
                            $compare = -1;
                            for ($n = 0; $n < count($datalokal); $n++) {
                                $compare = strcasecmp($datalokal[$n]["NO_MESIN"], $databaru[$i]["nomesin"]);
                                $compare += strcasecmp($datalokal[$n]["NO_TERIMA_DEALER"], $databaru[$i]["notterimadlr"]);
                                $compare += strcasecmp($datalokal[$n]["KD_DEALER"], $databaru[$i]["kddlr"]);
                                $compare += strcasecmp($datalokal[$n]["TGL_TERIMA"], $databaru[$i]["tgltterima"]);
                                $compare += strcasecmp($datalokal[$n]["NO_RANGKA"], $databaru[$i]["norangka"]);
                                $compare += strcasecmp($datalokal[$n]["NO_SUT"], $databaru[$i]["nosut"]);
                                $compare += strcasecmp($datalokal[$n]["NO_SRUT"], $databaru[$i]["nosrut"]);

                                //var_dump(expression)

                                if ($compare == 0) {
                                    continue 2;
                                }
                            }
                            $no ++;
                            
                            if ($compare != 0) {
                                ?>
                        
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["kddlr"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["notterimadlr"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["tgltterima"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["nomesin"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["norangka"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["nosut"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["nosrut"]; ?></td>
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
            onclick="AddDataJSON('<?php echo base_url("motor/update_srut"); ?>')">Update Data (<?php echo $totalData; ?>)</button>
</div>
