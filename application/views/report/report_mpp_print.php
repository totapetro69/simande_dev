<?php
$dari_tgl = ($this->input->get("tgl_trans")) ? $this->input->get("tgl_trans") : date("d/m/Y", strtotime("-1 Days"));
$no_trx = $this->input->get("n");
$disable = ($no_trx) ? "" : "disabled-action";
$defaulD = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$bulan = ($this->input->get('bulan')) ? $this->input->get('bulan') : date("m");
$tahuns = ($this->input->get("tahun")) ? $this->input->get("tahun") : date('Y');
?>
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
    <h4 class="modal-title" id="myModalLabel">Report Mekanik Performance Parameter</h4>
</div>

<div class="modal-body" id="printarea">

    <table border='0' id="desc" class="">

        <tr align='center'>
            <td><h2><strong>LAPORAN MEKANIK PERFORMANCE PARAMETER</strong></h2></td>
        </tr>
    </table>
    <table border='0' id="desc" class="">
        <tr>
            <td style="width:115px;">Nomor AHASS</td>
            <td style="width:15px;">:</td>
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
        <tr>
            <td>Periode</td>
            <td>:</td>
            <td><?php echo nBulan($bulan) . " " . $tahuns; ?></td>
        </tr>
        <tr>
            <td>Dibuat Tgl</td>
            <td>:</td>
            <td><?php echo date('d/m/Y') ?>  <?php echo date('H:i:s') ?></td>
        </tr>

        <tr align='center'>
            <!--<td><h7>Tanggal : <?php echo($this->input->get("tanggal")) ?></h7></td>-->
        </tr>

    </table>


    <table class="table table-stripped table-hover table-bordered" style="width:100% !important">
        <thead style="background-color: #FFC140 !important">

            <tr style="text-align: center !important;">
                <th colspan="2" style="width:5% !important;" class="text-center"></th>
                <th colspan="11" style="width:5% !important;" class="text-center">Type</th>
            </tr>
            <tr style="text-align: center !important;">
                <th style="width:15% !important;" class="text-center">ID Emp</th>
                <th style="width:30% !important;" class="text-center">Nama</th>
                <th style="width:5% !important;" class="text-center">Data</th>
                <th style="width:5% !important;" class="text-center">ASS</th>
                <th style="width:5% !important;" class="text-center">Claim</th>
                <th style="width:5% !important;" class="text-center">HR</th>
                <th style="width:5% !important;" class="text-center">JR</th>
                <th style="width:5% !important;" class="text-center">LR</th>
                <th style="width:5% !important;" class="text-center">PDI</th>
                <th style="width:5% !important;" class="text-center">CS</th>
                <th style="width:5% !important;" class="text-center">LS</th>
                <th style="width:5% !important;" class="text-center">GOP</th>
                <th style="width:8% !important;" class="text-center">Grand Total</th>
            </tr>

        </thead>

        <tbody>
            <?php
            $n = 0;
            if (isset($mekanik)):
                if ($mekanik->totaldata > 0):
                    // $GTside = 0;

                    $GTASS = 0; $GTCC2 = 0; $GTHR = 0; $GTJR = 0; $GTLR = 0; $GTCS = 0; $GTLS = 0;

                    $GTPG_ASS = 0; $GTPG_CC2 = 0; $GTPG_HR = 0; $GTPG_JR = 0; $GTPG_LR = 0; $GTPG_CS = 0; $GTPG_LS = 0;
                    
                    foreach ($mekanik->message as $key => $value) {
                        $GTside = array_sum([$value->ASS,$value->CC2,$value->HR,$value->JR,$value->LR /*+ $value->PDI*/,$value->CS,$value->LS]) /*+ $value->GOP*/;
                        $PG_GTside = array_sum([$value->PG_ASS,$value->PG_CC2,$value->PG_HR,$value->PG_JR,$value->PG_LR /*+ $value->PG_PDI*/,$value->PG_CS,$value->PG_LS]) /*+ $value->GOP*/;


                        $GTASS = $GTASS + $value->ASS; $GTCC2 = $GTCC2 + $value->CC2; $GTHR = $GTHR +  $value->HR; $GTJR = $GTJR +  $value->JR; $GTLR = $GTLR +  $value->LR; $GTCS = $GTCS +  $value->CS; $GTLS = $GTLS +  $value->LS;

                        $GTPG_ASS = $GTPG_ASS + $value->PG_ASS; $GTPG_CC2 = $GTPG_CC2 + $value->PG_CC2; $GTPG_HR = $GTPG_HR + $value->PG_HR; $GTPG_JR = $GTPG_JR + $value->PG_JR; $GTPG_LR = $GTPG_LR + $value->PG_LR; $GTPG_CS = $GTPG_CS + $value->PG_CS; $GTPG_LS = $GTPG_LS + $value->PG_LS;




                        ?>
                        <tr>
                            <td rowspan="2" class='text-left'><?php echo $value->NIK; ?></td>
                            <td rowspan="2" style="white-space: nowrap;"><?php echo $value->NAMA_MEKANIK; ?></td>
                            <td class="text-left">Unit</td>
                            <td class="text-right"><?php echo $value->ASS; ?>  </td>
                            <td class="text-right"><?php echo $value->CC2; ?>  </td>
                            <td class="text-right"><?php echo $value->HR; ?>  </td>
                            <td class="text-right"><?php echo $value->JR; ?>  </td>
                            <td class="text-right"><?php echo $value->LR; ?>  </td>
                            <td class="text-right"><?php echo 0; ?></td>
                            <td class="text-right"><?php echo $value->CS; ?>  </td>
                            <td class="text-right"><?php echo $value->LS; ?>  </td>
                            <td class="text-right"><?php echo 0; ?></td>
                            <td class="text-right"><?php echo $GTside; ?></td>
                        </tr>
                        <tr>
                            <td class="text-right">Waktu</td> <!-- data -->
                            <td class="text-right"><?php echo $value->PG_ASS; ?>  </td>
                            <td class="text-right"><?php echo $value->PG_CC2; ?>  </td>
                            <td class="text-right"><?php echo $value->PG_HR; ?>  </td>
                            <td class="text-right"><?php echo $value->PG_JR; ?>  </td>
                            <td class="text-right"><?php echo $value->PG_LR; ?>  </td>
                            <td class="text-right"><?php echo 0; ?></td>
                            <td class="text-right"><?php echo $value->PG_CS; ?>  </td>
                            <td class="text-right"><?php echo $value->PG_LS; ?>  </td>
                            <td class="text-right"><?php echo 0; ?></td>
                            <td class="text-right"><?php echo $PG_GTside; ?></td>
                        </tr>
                        </tr>
            <?php
                    }
            ?>
                        <tr>
                            <td colspan="2" rowspan="2" style="text-align: center; vertical-align: middle;">Grand Total</td>
                            <td class="text-left">Unit</td>
                            <td class="text-right"><?php echo $GTASS; ?>  </td>
                            <td class="text-right"><?php echo $GTCC2; ?>  </td>
                            <td class="text-right"><?php echo $GTHR; ?>  </td>
                            <td class="text-right"><?php echo $GTJR; ?>  </td>
                            <td class="text-right"><?php echo $GTLR; ?>  </td>
                            <td class="text-right"><?php echo 0; ?></td>
                            <td class="text-right"><?php echo $GTCS; ?>  </td>
                            <td class="text-right"><?php echo $GTLS; ?>  </td>
                            <td class="text-right"><?php echo 0; ?></td>
                            <td class="text-right"><?php echo array_sum([$GTASS,$GTCC2,$GTHR,$GTJR,$GTLR,$GTCS,$GTLS]); ?></td>
                        </tr>
                        <tr>
                            <td class="text-left">Waktu</td>
                            <td class="text-right"><?php echo $GTPG_ASS; ?>  </td>
                            <td class="text-right"><?php echo $GTPG_CC2; ?>  </td>
                            <td class="text-right"><?php echo $GTPG_HR; ?>  </td>
                            <td class="text-right"><?php echo $GTPG_JR; ?>  </td>
                            <td class="text-right"><?php echo $GTPG_LR; ?>  </td>
                            <td class="text-right"><?php echo 0; ?></td>
                            <td class="text-right"><?php echo $GTPG_CS; ?>  </td>
                            <td class="text-right"><?php echo $GTPG_LS; ?>  </td>
                            <td class="text-right"><?php echo 0; ?></td>
                            <td class="text-right"><?php echo array_sum([$GTPG_ASS,$GTPG_CC2,$GTPG_HR,$GTPG_JR,$GTPG_LR,$GTPG_CS,$GTPG_LS]); ?></td>
                        </tr>
            <?php
                endif;
            endif;
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