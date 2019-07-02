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
    <h4 class="modal-title" id="myModalLabel">Laporan Unhandled Customer</h4>
</div>

<div class="modal-body" id="printarea">

    <table border='0' id="desc" class="">

        <tr align='center'>
            <td rowspan="2"></td>
            <td colspan="3"><h2><strong>REPORT UNHANDLED CUSTOMER</strong></h2></td>
            <td rowspan="2">
                <div class="project" >
                    <!--<span class="title" style="text-align: center;">Tgl Cetak : <?php echo date('d/m/Y') ?></span>-->
                </div>
            </td>
        </tr>

        <tr align='center'>
            <!--<td><h7>Tanggal : <?php echo($this->input->get("tanggal")) ?></h7></td>-->
        </tr>

    </table>


    <table border='1' class="table table-hover table-striped table-bordered">

        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Transaksi</th>
                <th>Jam Mulai Tak Terlayani</th>
                <th>Keterangan</th>
                <th>Jumlah Customer</th>
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
                            <td class="table-nowarp"></td>
                            <td class="table-nowarp"></td>
                            <td class="table-nowarp"></td>
                            <td class="table-nowarp"></td>
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