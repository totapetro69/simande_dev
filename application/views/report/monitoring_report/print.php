<?php
$defaulD = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
?>

<style type="text/css">
    #desc {
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
        width: 100%;
    }
    .project {
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
         width: 100px; 
         margin-right: 15px; 
        padding: 2px 0;
        display: table-cell;
         font-size: 0.8em; 
    }

    .project .content {
        width: 100%;
    }

    /*@page { size: portrait; }*/
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Monitoring Report Harian</h4>
</div>

<div class="modal-body" id="printarea">

    <table border='0' id="desc" class="">

        <tr align='center'>
            <td rowspan="2"></td>
            <td colspan="5"><h2><strong><u>Laporan Daily Unit Entry</u></strong></h2></td>
        </tr>

        <tr align='center'>
            <td><h7>Tanggal Transaksi : <?php echo($this->input->get("tanggal"))?></h7></td>
        </tr>
    </table>

    <table border='0' id="desc" class="">

        <tr>
            <td style="width: 150px">Kode Main Dealer</td>
            <td style="width: 10px">:</td>
            <td><?php echo KodeMainDealer($defaulD); ?></td>
        </tr>

        <tr>
            <td>Nama Main Dealer</td>
            <td>:</td>
            <td><?php echo NamaMainDealer($defaulD); ?></td>
        </tr>
    
        <tr>
            <td>Nomor AHASS</td>
            <td>:</td>
            <td><?php echo KodeDealerAHM($defaulD); ?></td>
        </tr>

        <tr>
            <td>Nama AHASS</td>
            <td>:</td>
            <td><?php echo NamaDealer($defaulD); ?></td>
        </tr>

        <tr>
            <td>Kota</td>
            <td>:</td>
            <td><?php echo KotaDealer($defaulD); ?></td>
        </tr>
    </table>

    <table border='1' class="table table-hover table-striped table-bordered">

        <thead>
            <tr>
                <th>Waktu Transaksi</th>
                <th>No. NJB</th>  
                <th>Nama Customer</th>
                <th>Honda ID</th>
                <!-- <th>No. Polisi</th>
                <th>No. Mesin</th>
                <th>No. Rangka</th> -->
                <th>Jasa/Part Number</th>
                <th>Keterangan Jasa</th>
                <th>Qty</th>
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
                            <td style="width: 5px;"><?php echo date('H:i:s', strtotime($value->CREATED_TIME)); ?></td>
                            <td style="width: 5px;"><?php echo $value->NO_PKB; ?></td>
                            <td style="width: 5px;"><?php echo $value->NAMA_CUSTOMER; ?></td>
                            <td style="width: 10px;"><?php echo $value->KD_HONDA ?></td>
                           <!--  <td style="width: 10px;"><?php echo $value->NO_POLISI; ?></td>
                            <td style="width: 5px;"><?php echo $value->NO_MESIN; ?></td>
                            <td style="width: 5px;"><?php echo $value->NO_RANGKA; ?></td> -->
                            <td style="width: 5px;"><?php echo $value->KD_PEKERJAAN;?></td>
                            <td class="td-overflow"><?php echo $value->KETERANGAN; ?></td> 
                            <td class="text-right"><?php echo number_format($value->QTY, 0); ?></td> 
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