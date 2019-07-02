<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$tahunAktif = ($this->input->get("tahun")) ? $this->input->get("tahun") : date("Y");
$jenisOrder = ($this->input->get("jenis_order")) ? $this->input->get("jenis_order") : "";
//var_dump($tahunpo);
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
<?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right">  
            <a class="btn btn-default <?php echo $status_c; ?>" href="<?php echo base_url('purchasing/posp_add'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Input PO Baru</a>
            <a class="btn btn-default hidden" href="#" role="button">
                <i class="fa fa-download fa-fw"></i> Download .PDPO
            </a>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                <i class="fa fa-search"></i> Purchase Order
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
                <form id="filterForm" action="<?php echo base_url('purchasing/posp_list') ?>" class="bucket-form" method="get">
                    <div class="col-xs-12 col-sm-3 col-md-3">
                        <div class="form-group">
                            <label>Dealer</label>
                            <select class="form-control" id="kd_dealer" name="kd_dealer" required="true">
                                <option value="">--Pilih Dealer--</option>
                                <?php
                                if (isset($dealer)) {
                                    if ($dealer->totaldata > 0) {
                                        foreach ($dealer->message as $key => $value) {
                                            $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                            echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 col-sm-3">
                        <div class="form-group">
                            <label>Tahun PO</label>
                            <select id="tahun" name="tahun" class="form-control">
                                <option>--Pilih Tahun</option>
                                <?php
                                if ($tahunpo) {
                                    if ((int) $tahunpo->totaldata > 0) {
                                        foreach ($tahunpo->message as $key => $value) {
                                            $pilih = ($tahunAktif == $value->TAHUN) ? "selected" : "";
                                            echo "<option value='" . $value->TAHUN . "' " . $pilih . ">" . $value->TAHUN . "</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3">
                        <div class="form-group">
                            <label>Jenis PO</label>
                            <select class="form-control" id="jenis_order" name="jenis_order">
                                <option value="">--Pilih Jenis Order--</option>
                                <option value='Reguler'<?php echo ($jenisOrder == 'Reguler') ? " selected" : ""; ?>>Reguler</option>
                                <option value='Hotline'<?php echo ($jenisOrder == 'Hotline') ? " selected" : ""; ?>>Hotline</option>
                                <option value='Fix'<?php echo ($jenisOrder == 'Fix') ? " selected" : ""; ?>>Fix</option>
                                <option value='Canvasing'<?php echo ($jenisOrder == 'Canvasing') ? " selected" : ""; ?>>Canvasing</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3">
                        <br>
                        <button class="btn btn-primary hidden" type="submit"><i class="fa fa-search fa-fw"></i> Perview</button>
                    </div>
                </form>
            </div>
        </div>       
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Aksi</th>
                            <th>No.PO</th>
                            <th>Tanggal PO</th>
                            <th>Jenis PO</th>
                            <!-- <th>Kode Dealer</th> -->
                            <!-- <th>Nama Dealer</th> -->
                            <th>Tgl Selesai</th>
                            <!-- <th>Periode</th> -->
                            <th>Status PO</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($po) {
                            if ($po->totaldata > 0) {
                                $n = 0;
                                foreach ($po->message as $key => $value) {
                                    $n++;
                                    
                                    switch ($value->APPROVAL) {
                                        case '-1': $appoval = "Rejected";break;
                                        case '1': $appoval = "Approved";break;
                                        default : $appoval = "Open";break;
                                    }
                                    $keterangan =($value->APPROVAL=='-1')? $value->KETERANGAN:$value->NAMA_KONSUMEN . "-" . $value->KOTA_KONSUMEN;
                                    $sdhDiappove = ($value->APPROVAL == 1) ? "" : "hidden";
                                    $jenispo = ($value->JENIS_PO == "Hotline" )? "hidden" : "";
                                    $tglSelesai =($value->APPROVAL=="-1" || $value->APPROVAL=="3")? tglFromSql($value->APPROVAL_DATE):"";
                                    echo "<tr>
                                                <td class='text-center' valign='middle'>" . $n . "</td>
                                                <td class='text-left table-nowarp' valign='middle'>
                                                    <a href='" . base_url("purchasing/posp_add/?n=") . urlencode(base64_encode($value->NO_PO)) . "'><i class='fa fa-edit fa-fw' title='Edit'></i></a>
                                                    <a class='" . ($value->APPROVAL == 1  || $value->APPROVAL=='-1' ?'hidden':'') . "' onclick=\"__hapusPO('" . $value->ID . "','" . $n . "');\"><i class='fa fa-trash' title='Delete'></i></a>
                                                    <a class='".($value->STATUS_DETAIL == 1 ? '' : 'hidden' )." " . $sdhDiappove . "' href='" . base_url("purchasing/createfile_pdpo_sparepart/?n=") . urlencode(base64_encode($value->NO_PO)) . "' title='Download File PDPO'><i class='fa fa-download'></i></a>
                                                    <a class='".($value->JENIS_PO == "Hotline" ? '' : 'hidden' )." " . $sdhDiappove . "' href='" . base_url("purchasing/createfile_plhlo_sparepart/?n=") . urlencode(base64_encode($value->NO_PO)) . "' title='Download File PLHLO'><i class='fa fa-download '></i></a>
                                                    <span id='ld_".$n."'></span>
                                                </td>
                                                <td class='text-center table-nowarp' valign='middle'>" . $value->NO_PO . "</td>
                                                <td class='text-center' valign='middle'>" . tglFromSql($value->TGL_PO) . "</td>
                                                <td class='text-left'>" . $value->JENIS_PO . "</td>
                                                <!--<td class='text-left'>" . $value->NAMA_DEALER . "</td-->
                                                <td valign='middle' class='text-center'>" .$tglSelesai."</td>
                                                <td valign='middle' class='text-center'>" . $appoval . "</td>
                                                <td valign='middle' class='text-left'>" . $keterangan . "</td>
                                            </tr>
                                             ";
                                    
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function(){

    })
    function __hapusPO(id,baris){
    if(confirm("Yakin akan menghapus data ini?")){
        //var bariske = $("#listpo >tbody").closest('tr').index();
        $('#ld_'+baris).html("<i class='fa fa-spinner fa-spin'></i>");
        $.get("<?php echo base_url("/purchasing/posp_hps");?>",{'id':id},function(result){
            var result=$.parseJSON(result);
            if(result.status==true){
                    $('.success').animate({ top: "0"}, 500);
                    $('.success').html('Data berhasil di hapus').fadeIn();
                    setTimeout(function() {
                        document.location.reload()
                    }, 2000);
                }else{
                    $('.error').animate({ top: "0"}, 500);
                    $('.error').html('Data gagal di hapus').fadeIn();
                    setTimeout(function() {
                        hideAllMessages();
                    },2000)
                    $('#ld_'+baris).html("");
                }
        })
    }
}
</script>