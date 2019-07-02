<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">List Desa/ Kelurahan Baru</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="table-responsive h350">
        <?php
            $databaru = null;
            $dataterbaru = array();
            $totalData = 0;
            if (isset($listmd)) {
                $databaru = $listmd;
                ?>
                <table class="table table-striped b-t b-light table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kota</th>
                            <th>Nama Kota</th>
                            <th>Kode Kecamatan</th>
                            <th>Nama Kecamatan</th>
                            <th>Kode Kecamatan</th>
                            <th>Kode Desa</th>
                            <th>Nama Desa</th>
                            <th>Kode Desa AHM</th>
                            <th>Nama Desa AHM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page'); $ada=0;
                        if($databaru){
                            if($databaru->totaldata>0){
                                foreach ($databaru->message as $key => $value) {
                                    $no ++;
                                    ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp"><?php echo $value->kdkota; ?></td>
                                        <td class="table-nowarp"><?php echo $value->kota; ?></td>
                                        <td><?php echo $value->kdkec; ?></td>
                                        <td><?php echo $value->kecamatan; ?></td>
                                        <td><?php echo $value->kdKel; ?></td>
                                        <td><?php echo $value->kelurahan; ?></td>
                                        <td><?php echo $value->status; ?></td>
                                        <td><?php echo $value->kdkelahm; ?></td>
                                        <td><?php echo $value->kelurahanahm; ?></td>
                                    </tr>
                                    <?php
                                    $totalData++;
                                }
                                $dataterbaru = ($databaru->message);
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <?php }
            ?>
        </div>
    </div>
</div>
<?php
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
    <div class="hidden" id="datajson"><?php echo (json_encode($dataterbaru)); ?></div>
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button type="button" id="submit-btn" class="btn btn-danger <?php echo ($totalData == 0) ? 'hidden' : ''; ?>" 
            onclick="AddDataJSON('<?php echo base_url("company/update_desa"); ?>')">Update Data (<?php echo $totalData; ?>)</button>
</div>