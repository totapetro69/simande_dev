<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">List KPB Part</h4>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" style="height: 350px; overflow: auto;">
            <?php
            $databaru = array();
            $datalokal = array();
            //$datamd     = array();
            //$datalokal = (is_array($list["message"])) ? $list["message"] : array();
            $datamd = isset($listmd)?$listmd:array();
            /* var_dump($listmd);
              exit(); */
            $databaru = ($datamd);
            $dataterbaru = array();
            $totalData = 0;
           // var_dump($datalokal);exit();
            if ($databaru) {
                ?>    
                <table class="table table-striped b-t b-light table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No Mesin</th>
                            <th>Motor KPB</th>        
                            <th>No Part Oli</th>
                            <th>No Part Oli2</th>
                            <th>Isi Oli</th>
                            <th>Harga Oli</th>
                            <th>No Part Oli1</th>
                            <th>No Part Oli2</th>
                            <th>Isi Oli2</th>
                            <th>Harga Oli2</th>
                            <th>Nominal Jasa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        for ($i = 0; $i < count($databaru); $i++) {
                            $compare = -1;
                            /*for ($n = 0; $n < count($datalokal); $n++) {
                                $compare = strcasecmp($datalokal[$n]["NO_MESIN"], $databaru[$i]["nomesin"]);
                                $compare += strcasecmp($datalokal[$n]["NO_PART_OLI_1A"], $databaru[$i]["nopartoli"]);
                                $compare += strcasecmp($datalokal[$n]["NO_PART_OLI_1B"], $databaru[$i]["nopartoli2"]);
                                $compare += strcasecmp($datalokal[$n]["ISI_OLI_1"], $databaru[$i]["isioli"]);
                                $compare += strcasecmp($datalokal[$n]["HARGA_OLI_1"], $databaru[$i]["hargaoli"]);
                                $compare += strcasecmp($datalokal[$n]["NO_PART_OLI_2A"], $databaru[$i]["nopartoli_1"]);
                                $compare += strcasecmp($datalokal[$n]["NO_PART_OLI_2B"], $databaru[$i]["nopartoli_2"]);
                                $compare += strcasecmp($datalokal[$n]["ISI_OLI_2"], $databaru[$i]["isioli_2"]);
                                $compare += strcasecmp($datalokal[$n]["HARGA_OLI_2"], $databaru[$i]["hargaoli_2"]);
                                $compare += strcasecmp($datalokal[$n]["NOMINAL_JASA"], $databaru[$i]["nominjasa"]);

                                //var_dump(expression)

                                if ($compare == 0) {
                                    continue 2;
                                }
                            }*/
                            $no ++;
                            
                            if ($compare != 0) {
                                ?>
                        
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["nomesin"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["motorkpb"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["nopartoli"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["nopartoli2"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["isioli"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["hargaoli"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["nopartoli_1"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["nopartoli_2"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["isioli_2"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["hargaoli_2"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["nominjasa"]; ?></td>
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
            onclick="AddDataJSON('<?php echo base_url("sparepart/update_kpb_part"); ?>')">Update Data (<?php echo $totalData; ?>)</button>
</div>
