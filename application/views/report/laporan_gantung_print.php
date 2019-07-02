<style type="text/css">
    #desc {
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
        width: 100%;
    }
    .project {
        /* float: left; */
        text-align: left;
        display: table;
        width: 100%;
    }
    .project div {
        display: table-row;
    }

    .project .title {
        color: #5D6975;
        width: 90px;
    }

    .project span {
        text-align: left;
        /* width: 100px; */
        /* margin-right: 15px; */
        padding: 2px 0;
        display: table-cell;
        /* font-size: 0.8em; */
    }

    .project .content {
        width: 100%;
    }

    /*@page { size: portrait; }*/
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Laporan Gantung</h4>
</div>

<div class="modal-body" id="printarea">

    <table border='0' id="desc" class="">

        <tr align='center'>
            <td rowspan="2"></td>
            <td colspan="3"><h2><strong>REPORT PINJAMAN GANTUNG</strong></h2></td>
            <td rowspan="2">
                <div class="project" >
                    <span class="title" style="text-align: center;">Tgl Cetak : <?php echo date('d/m/Y') ?></span>
                </div>
            </td>
        </tr>

        <tr align='center'>
            <td><h5><strong>Periode : <?php echo ($this->input->get("tgl_trans_aw")); ?> s/d <?php echo($this->input->get("tgl_trans_ak")); ?></strong></h5></td>
        </tr>

    </table>


    <table border='1' class="table table-hover table-striped table-bordered">

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
            $jumlah = 0;
            if ($list) {
                if (is_array($list->message)) {
                    foreach ($list->message as $key => $value) {
                        $n++;
                        $url = base_url('cashier/kasirnew/?n=' . urlencode(base64_encode($value->NO_TRANS)) . "&x=" . rand());
                        $no_lkh = ($value->LKH > 0) ? "" : "info";
                        echo "
                    					<tr class='$no_lkh'>
                    						<td class='text-center'>$n</td>
                    						<td class='text-center table-nowarp'>" . ($value->NO_TRANS) . "</td>
                    						<td class='text-center'>" . tglfromSql($value->TGL_TRANS) . "</td>
                    						<td>" . $value->JENIS_TRANS . " - " . $value->URAIAN_TRANSAKSI . "</td>
                    						<td class='text-right'>" . number_format($value->HARGA, 0) . "</td>
                    					";
                        $jumlah += $value->HARGA;
                    }
                }
            }
            ?>
            <tr>
                <td colspan="5" align="right">Total Rp. <?php echo number_format($jumlah, 0); ?></td>
            </tr>
        </tbody>

    </table>

</div>
<div class="modal-footer">

    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button type="button" onclick="printSj();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>

</div>

<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
        function printSj() {
            printJS('printarea', 'html');
            $('#keluar').click();
        }
</script>