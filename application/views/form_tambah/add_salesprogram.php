<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">List Sales Program</h4>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" style="height: 350px; overflow: auto;">
            <?php
            /*$databaru = array();
            $datalokal = array();
            //$datamd     = array();*/
            $databaru = array();
            $datalokal = array();
            //$datalokal = (is_array($list["message"])) ? $list["message"] : array();
            $datamd = (isset($listmd))?$listmd:array();//is_array($listmd)?$listmd:array();
            $databaru = ($datamd);
            $dataterbaru = array();
            $totalData = 0;
            //var_dump($databaru);exit;
            //if (isset($databaru["message"])=== FALSE) {
            if(isset($databaru)){
                //echo $selesai;
                ?>
                <table class="table table-striped b-t b-light table-hover">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>KODE</th>
                            <th>NAMA</th>
                            <th>TIPE</th>
                            <th>KD SALESPROGRAM AHM</th>
							<th>NO SURAT SP</th>
							<th>KHUSUS</th>
							<th>GIFT</th>
							<th>CABANG</th>
							<th>STARTDATE</th>
							<th>POTSTART</th>
							<th>POTEND</th>
							<th>SSU START</th>
							<th>SSU END</th>
                            <th>ENDATE</th>
                            <th>END CLAIM</th>
                            <th>KDTIPE</th>
                            <th>QTY</th>
							<th>SK AKM</th>
							<th>SK MD</th>
							<th>SK SD</th>
							<th>SK FIN</th>
							<th>SC AHM</th>
							<th>SC MD</th>
							<th>SC SD</th>
							<th>CB AHM</th>
							<th>CB MD</th>
							<th>CB SD</th>
							<th>POT FAKTUR</th>
							<th>CASH TEMPO</th>
							<th>SPLITOTR</th>
							<th>SPLITOTR2</th>
							<th>HADIAH LANGSUNG</th>
							<th>HKONTRAK</th>
							<th>FEE</th>
							<th>PSTNK</th>
							<th>PBPKB</th>
							<th>NOPO</th>
							<th>MINSKSD</th>
							<th>MINSCSD</th>
							<th>DPOTR</th>
							<th>TAMBFIN</th>
							<th>TAMBMD</th>
							<th>TAMBSD</th>
							<th>TUNDA FAKTUR</th>
							<th>HADIAH LANGSUNG 2</th>
							<th>KETHADIAH</th>
							<th>TAMBAHM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        
                        foreach ($databaru as $key => $value) {
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td class="table-nowarp"><?php echo $value["programid"]; ?></td>
                                    <td class="table-nowarp"><?php echo $value["programdesc"]; ?></td>
                                    <td class="table-nowarp"><?php echo $value["type"]; ?></td>
                                    <td class="table-nowarp"><?php echo $value["spid"]; ?></td>
									<td class="table-nowarp"><?php echo $value["localspid"]; ?></td>
									<td class="table-nowarp"><?php echo $value["khusus"]; ?></td>
									<td class="table-nowarp"><?php echo $value["gift"]; ?></td>
									<td class="table-nowarp"><?php echo $value["cabang"]; ?></td>
                                    <td class="table-nowarp"><?php echo $value["startdate"]; ?></td>
                                    <td class="table-nowarp"><?php echo $value["potstart"]; ?></td>
									<td class="table-nowarp"><?php echo $value["potend"]; ?></td>
									<td class="table-nowarp"><?php echo $value["ssustart"]; ?></td>
									<td class="table-nowarp"><?php echo $value["ssuend"]; ?></td>
                                    <td class="table-nowarp"><?php echo $value["enddate"]; ?></td>
                                    <td class="table-nowarp"><?php echo $value["endclaim"]; ?></td>
                                    <td class="table-nowarp"><?php echo $value["kdtipe"]; ?></td>
                                    <td class="table-nowarp"><?php echo $value["qty"]; ?></td>
									<td class="table-nowarp"><?php echo $value["skahm"]; ?></td>
									<td class="table-nowarp"><?php echo $value["skmd"]; ?></td>
									<td class="table-nowarp"><?php echo $value["sksd"]; ?></td>
									<td class="table-nowarp"><?php echo $value["skfinance"]; ?></td>
									<td class="table-nowarp"><?php echo $value["scahm"]; ?></td>
									<td class="table-nowarp"><?php echo $value["scmd"]; ?></td>
									<td class="table-nowarp"><?php echo $value["scsd"]; ?></td>
									<td class="table-nowarp"><?php echo $value["cbahm"]; ?></td>
									<td class="table-nowarp"><?php echo $value["cbmd"]; ?></td>
									<td class="table-nowarp"><?php echo $value["cbsd"]; ?></td>
									<td class="table-nowarp"><?php echo $value["potfaktur"]; ?></td>
									<td class="table-nowarp"><?php echo $value["cashtempo"]; ?></td>
									<td class="table-nowarp"><?php echo $value["splitotr"]; ?></td>
									<td class="table-nowarp"><?php echo $value["splitotr2"]; ?></td>
									<td class="table-nowarp"><?php echo $value["hadiahlangsung"]; ?></td>
									<td class="table-nowarp"><?php echo $value["hkontrak"]; ?></td>
									<td class="table-nowarp"><?php echo $value["fee"]; ?></td>
									<td class="table-nowarp"><?php echo $value["pstnk"]; ?></td>
									<td class="table-nowarp"><?php echo $value["pbpkb"]; ?></td>
									<td class="table-nowarp"><?php echo $value["nopo"]; ?></td>
									<td class="table-nowarp"><?php echo $value["minsksd"]; ?></td>
									<td class="table-nowarp"><?php echo $value["minscsd"]; ?></td>
									<td class="table-nowarp"><?php echo $value["dpotr"]; ?></td>
									<td class="table-nowarp"><?php echo $value["tambfinance"]; ?></td>
									<td class="table-nowarp"><?php echo $value["tambmd"]; ?></td>
									<td class="table-nowarp"><?php echo $value["tambsd"]; ?></td>
									<td class="table-nowarp"><?php echo $value["tundafaktur"]; ?></td>
									<td class="table-nowarp"><?php echo $value["hadiahlangsung2"]; ?></td>
									<td class="table-nowarp"><?php echo $value["kethadiah"]; ?></td>
									<td class="table-nowarp"><?php echo $value["tambahm"]; ?></td>
                                </tr>
                                <?php
                                //create new array();
                                $dataterbaru[$totalData] = ($value);
                                $totalData++;
                                //exit();
                        }
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
            onclick="AddDataJSON('<?php echo base_url("setup/update_salesprogram"); ?>')">Update Data (<?php echo $totalData; ?>)</button>
</div>
