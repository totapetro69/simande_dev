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
    <h4 class="modal-title" id="myModalLabel">Laporan Harian Bengkel</h4>
</div>

<div class="modal-body" id="printarea">

    <table border='0' id="desc" class="">

        <tr align='center'>
            <td rowspan="2"></td>
            <td colspan="5"><h2><strong>REPORT PKB HARIAN</strong></h2></td>
            <td rowspan="2">
                <div class="project" >
                    <span class="title" style="text-align: center;">Tgl Cetak : <?php echo date('d/m/Y') ?></span>
                </div>
            </td>
        </tr>

        <tr align='center'>
            <td><h7>Tanggal : <?php echo($this->input->get("tanggal"))?></h7></td>
        </tr>

    </table>

    <table border='1' class="table table-hover table-striped table-bordered">

        <thead>
            <tr>
                <th rowspan="2" colspan="8"></th>
                <th colspan="3">Kredit</th>
                <th colspan="3">Tunai</th>
                <th rowspan="2" colspan="2"></th>
            </tr>
            <tr>
                
                <th>NJB</th>
                <th>NSC</th>
                <th></th>
                <th>NJB</th>
                <th colspan="2">NSC</th>
            </tr>
            <tr>
                <th>No</th>
                <th>No. PKB</th>
                <th>Type</th>
                <th>Jasa</th>
                <th>Tanggal</th>
                <th>No. Polisi</th>
                <th>Customer</th>
                <th>Mekanik</th>
                <th>Jasa</th>
                <th>Oli-Part</th>
                <th>Subtotal</th>
                <th>Jasa</th>
                <th>Oli</th>
                <th>Part</th>
                <th>Subtotal</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            <?php
            if ($list) {
                $no = 0;
                if (is_array($list->message)) {
                    foreach ($list->message as $key => $value) {
                        # code...

                        $no++;
                        ?>

                        <tr>
                            <td><?php echo $no; ?></td>
                            <td class="table-nowarp"><?php echo $value->NO_PKB; ?></td>
                            <td class="table-nowarp"><?php echo $value->KD_TIPEPKB; ?></td>
                            <td class="table-nowarp">-</td>
                            <td class="table-nowarp"><?php echo($this->input->get("tanggal"))?></td>
                            <td class="table-nowarp"><?php echo $value->NO_POLISI; ?></td>
                            <td class="table-nowarp"><?php echo $value->NAMA_COMINGCUSTOMER; ?></td>
                            <td class="table-nowarp"><?php echo $value->NAMA; ?></td>
                            <td class="table-nowarp"><?php echo $value->KD_TYPECOMINGCUSTOMER; ?></td>
                            <td class="table-nowarp">-</td>
                            <td class="table-nowarp">-</td>
                            <td class="table-nowarp">-</td>
                            <td class="table-nowarp">-</td>
                            <td class="table-nowarp">-</td>
                            <td class="table-nowarp">-</td>
                            <td class="table-nowarp">-</td>
                        </tr>
                        <?php
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