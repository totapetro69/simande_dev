<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$status_detail = ($list->totaldata > 0 ? '' : 'disabled-action');
$status_p = (isBolehAkses('p') ? $status_detail : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
//$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
$usergroup = $this->session->userdata("kd_group");
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">
            <a class="btn btn-default <?php echo $status_p ?>" id="modal-button" onclick='addForm("<?php echo base_url('report/laporan_gantung_print?tgl_trans_aw=' . $this->input->get("tgl_trans_aw") . '&tgl_trans_ak=' . $this->input->get("tgl_trans_ak") . '&kd_dealer=' . $this->input->get("kd_dealer")); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Gantung" ></i> Cetak
            </a>
            </a>

        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                Laporan Pinjaman Gantung
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
                <form id="frmCriteria" action="<?php echo base_url('report/laporan_gantung') ?>" class="bucket-form" method="get">

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select name="kd_dealer" id="kd_dealer" class="form-control">
                                    <option value="">--Pilih Dealer--</option>
                                    <?php
                                    if ($dealer) {
                                        if (is_array($dealer->message)) {
                                            foreach ($dealer->message as $key => $value) {
                                                $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                                                $select = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $select;
                                                echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Periode dari Tanggal</label>
                                    <div class="input-group append-group date">
                                        <input type="text" class="form-control" id="tgl_trans_aw" name="tgl_trans_aw" value="<?php echo ($this->input->get("tgl_trans_aw")) ? $this->input->get("tgl_trans_aw") : date("d/m/Y", strtotime("-1 days")); ?>">
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Sampai Tanggal</label>
                                    <div class="input-group append-group date">
                                        <input type="text" class="form-control" id="tgl_trans_ak" name="tgl_trans_ak" value="<?php echo ($this->input->get("tgl_trans_ak")) ? $this->input->get("tgl_trans_ak") : date("d/m/Y"); ?>"">
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span> </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <!--<div class="col-xs-12 col-sm-2 col-sm-push-2">-->

                            <div class="form-group">
                                <br>
                                <button id="submit-btn" onclick="addData();" class="btn btn-info" ><i class='fa fa-search'></i> Preview</button>
                            </div>

                            <!--</div>-->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No Transaksi</th>
                            <th>Tgl Transaksi</th>
                            <th>Uraian Transaksi</th>
                            <th>Jumlah</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = $this->input->get('page');
                        if ($list) {
                            if (is_array($list->message)) {
                                foreach ($list->message as $key => $value) {
                                    $n++;
                                    $no_lkh = ($value->LKH > 0) ? "" : "info";
                                    echo "
                    					<tr class='$no_lkh'>
                    						<td class='text-center'>$n</td>
                    						<td class='text-center table-nowarp'>" . ($value->NO_TRANS) . "</td>
                    						<td class='text-center'>" . tglfromSql($value->TGL_TRANS) . "</td>
                    						<td>" . $value->JENIS_TRANS . " - " . $value->URAIAN_TRANSAKSI . "</td>
                    						<td class='text-right'>" . number_format($value->HARGA, 0) . "</td>
                    						
                    					";
                                }
                            } else {
                                belumAdaData(20);
                            }
                        } else {
                            belumAdaData(20);
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php echo loading_proses(); ?>
</section>
<script type="text/javascript">
    $(document).ready(function () {
        $('#frmCriteria').submit(function () {
            $('#loadpage').removeClass("hidden");
        })
    })

</script>