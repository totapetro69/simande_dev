<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">List Dealer Baru</h4>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" style="height: 350px; overflow: auto;">
           <?php
            $databaru = array();
            $datalokal = array();
            $datamd = is_array($listmd)?$listmd:array();
            $databaru = ($datamd);
            $dataterbaru = array();
            $totalData = 0;
            //var_dump($datamd);exit(); 
            if (isset($databaru["message"])=== FALSE) {
                ?>
                <table class="table table-striped b-t b-light table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>KD Dealer</th>
                            <th>KD Dealer AHM</th>
                            <th>Nama Dealer</th>
                            <th>Tlp-1</th>
                            <th>Tlp-2</th>
                            <th>Tlp-3</th>
                            <th>Alamat</th>
                            <th>Jenis</th>
                            <th>Status</th> 
                            <th>KD Kabupaten</th>
                            <th>KD Propinsi</th>
                            <th>Rule</th>
                            <th>Kategori</th>
                            <th>NPWP</th> 
                            <th>PKP</th>
                            <th>Group Dealer</th>
                            <th>LAT</th>
                            <th>LNG</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        for ($i = 0; $i < count($databaru); $i++) {
                            $cmp="";$cmp1="";
                            $compare = -1;
                            //echo $datamd[$i]["kditem"]."| ".$datamd[$i]["kdtipe"]."\n";
                            
                            $no ++;
                            /* var_dump($compare);
                              exit(); */
                            if ($compare != 0) {
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["kddlr"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["kddlrahm"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["nmdlr"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["tlp"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["tlp2"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["tlp3"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["alamat"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["cabang"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["status"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["kdkota"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["kdprop"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["dlrrule"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["dlrareawsh"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["dlrnpwp"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["pkp"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["groupdealer"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["laty"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["lonx"]; ?></td>
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
            onclick="AddDataJSON('<?php echo base_url("dealer/update_dealer"); ?>')">Update Data (<?php echo $totalData; ?>)</button>
</div>