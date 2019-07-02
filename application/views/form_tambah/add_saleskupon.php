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
            $datamd = is_array($listmd)?$listmd:array();
            $databaru = ($datamd);
            $dataterbaru = array();
            $totalData = 0;

            //var_dump($datamd);exit;
            if (isset($databaru)) {
                ?>
                <table class="table table-striped b-t b-light table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode</th>
                            <th>Sales Kupon</th>
                            <th>Start Date</th>
							<th>End Date</th>
							<th>End Claim</th>
                            <th>No. Perkiraan</th>
                            <th>No. Sub Perkiraan</th>
                            <th>Tipe Motor</th>
							<th>Nilai</th>
							<th>Top 1</th>
							<th>Top 2</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //print_r($databaru);exit();
                        $no = $this->input->get('page'); $ada=0;
                        for ($i = 0; $i < count($databaru); $i++) {
                            $cmp="";$cmp1="";
                            $compare = -1;
                            $no ++;
                            if ($compare != 0) {
                            ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["programid"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["programdesc"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["startdate"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["enddate"]; ?></td>
									<td class="table-nowarp"><?php echo $databaru[$i]["endclaim"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["noperk"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["nosub"]; ?></td>
                                    <td class="table-nowarp"><?php echo $databaru[$i]["kdtipe"]; ?></td>
									<td class="table-nowarp"><?php echo $databaru[$i]["nilai"]; ?></td>
                                    <td class="table-nowarp text-center"><?php echo $databaru[$i]["top1"]; ?></td>
                                    <td class="table-nowarp text-center"><?php echo $databaru[$i]["top2"]; ?></td>
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
                <?php }else{
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
    {elapsed_time}
    <div class="hidden" id="datajson"><?php echo addslashes($json); ?></div>
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button type="button" id="submit-btn" class="btn btn-danger <?php echo ($totalData == 0) ? 'hidden' : ''; ?>" 
            onclick="AddDataJSON('<?php echo base_url("setup/update_saleskupon"); ?>')">Update Data (<?php echo $totalData; ?>)</button>
</div>

