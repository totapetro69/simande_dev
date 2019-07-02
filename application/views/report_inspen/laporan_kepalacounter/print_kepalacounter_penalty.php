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
    <h4 class="modal-title" id="myModalLabel">Laporan Penalti Kepala Counter</h4>
</div>

<div class="modal-body" id="printarea">

    <table border='0' id="desc" class="">

        <tr>
            <td colspan="14"><h3><strong>REKAPITULASI PENALTI INSENTIVE SALES UNIT</strong></h3></td>
        </tr>

        <tr>
            <td></td>
        </tr>

        <tr><td colspan="14">&nbsp;</td></tr>
        <tr>
            <td colspan="2">Cabang</td>
            <td align='center'> : </td>
            <td colspan="11"></td>
        </tr>
        <tr>
            <td colspan="2">Periode</td>
            <td align='center'> : </td>
            <td colspan="11"></td>
        </tr>
<!--        <tr>
            <td colspan="2">Salesman</td>
            <td align='center'> : </td>
            <td colspan="11"></td>
        </tr>-->

        <tr><td colspan="14">&nbsp;</td></tr>
        <tr><td colspan="14">&nbsp;</td></tr>

        <tr>
            <td colspan="14">Jenis : AR</td>
        </tr>
        <tr style="border-bottom: 1px solid; border-top: 1px solid;">

            <th rowspan="2">Bulan</th>
            <th colspan="2">AR Leasing OD</th>
            <th colspan="3">AR Unit Overdue</th>
            <th colspan="3">AR Unit Overdue (khusus CS1)</th>
            <th rowspan="2">Total Overdue (TO)</th>
            <th rowspan="2">KSP (40%) x TO</th>
            <th rowspan="2">Ka. OPS (40%) x TO</th>
            <th rowspan="2">K. Counter (10%) x TO</th>
            <th rowspan="2">K. Sales (10%) x TO</th>

        </tr>
        <tr style="border-bottom: 1px solid; border-top: 1px solid;">

            <th>Unit</th>
            <th> ( RP ) </th>
            <th>Unit</th>
            <th> ( RP ) </th>
            <th>OD Usia SMH</th>
            <th>Unit</th>
            <th> ( RP ) </th>
            <th>OD Usia SMH</th>
        </tr>

        <tbody>
            <?php
            $no = $this->input->get('page');
            if ($list):
                if (is_array($list->message) || is_object($list->message)):
                    foreach ($list->message as $key => $row):
                        $no ++;
                        ?>

                        <tr>
                            <td><?php echo $no; ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <?php
                    endforeach;
                else:
                    ?>

                    <tr>
                        <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                        <td colspan="40"><b><?php echo ($list->message); ?></b></td>
                    </tr>

                <?php
                endif;
            else:
                echo belumAdaData(40);
            endif;
            ?>
            <tr>

                <td colspan="10"></td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
            </tr>
        </tbody>

    </table>
    
    <table border='0' id="desc" class="">

        <tr>
            <td colspan="14">Jenis : Unit</td>
        </tr>
        <tr style="border-bottom: 1px solid; border-top: 1px solid;">

            <th rowspan="2">Bulan</th>
            <th colspan="2">AR Leasing OD</th>
            <th colspan="3">AR Unit Overdue</th>
            <th colspan="3">AR Unit Overdue (khusus CS1)</th>
            <th rowspan="2">Total Overdue (TO)</th>
            <th rowspan="2">KSP (100%) x TO</th>
            <th rowspan="2">Ka. OPS (0%) x TO</th>
            <th rowspan="2">K. Counter (0%) x TO</th>
            <th rowspan="2">K. Sales (0%) x TO</th>

        </tr>
        <tr style="border-bottom: 1px solid; border-top: 1px solid;">

            <th>Unit</th>
            <th> ( RP ) </th>
            <th>Unit</th>
            <th> ( RP ) </th>
            <th>OD Usia SMH</th>
            <th>Unit</th>
            <th> ( RP ) </th>
            <th>OD Usia SMH</th>
        </tr>

        <tbody>
            <?php
            $no = $this->input->get('page');
            if ($list):
                if (is_array($list->message) || is_object($list->message)):
                    foreach ($list->message as $key => $row):
                        $no ++;
                        ?>

                        <tr>
                            <td><?php echo $no; ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <?php
                    endforeach;
                else:
                    ?>

                    <tr>
                        <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                        <td colspan="40"><b><?php echo ($list->message); ?></b></td>
                    </tr>

                <?php
                endif;
            else:
                echo belumAdaData(40);
            endif;
            ?>
            <tr>

                <td colspan="10"></td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
                <td>x</td>
            </tr>
        </tbody>

    </table>

    <table border='0' id="desc" class="">

        <tr>
            <td colspan="2">&nbsp;</td>
            <td colspan="7">&nbsp;</td>
        </tr>
        <tr><td colspan="7">&nbsp;</td></tr>
        <tr><td colspan="7">&nbsp;</td></tr>

        <tr>
            <td align='center' colspan="2">Yang Membuat,</td>
            <td align='center' colspan="2">Mengetahui,</td>
            <td align='center' colspan="2">Menerima,</td>            
        </tr>

        <tr><td colspan="8">&nbsp;</td></tr>
        <tr><td colspan="8">&nbsp;</td></tr>
        <tr><td colspan="8">&nbsp;</td></tr>

        <tr>
            <td align='center' colspan="2">  ___________________  </td>
            <td align='center' colspan="2">  ___________________  </td>
            <td align='center' colspan="2">  ___________________  </td>            
        </tr>

        <tr>
            <td align='center' colspan="2">PIC Accounting</td>
            <td align='center' colspan="2">Accounting</td>
            <td align='center' colspan="2">KSP</td>            
        </tr>
        <tr><td colspan="8">&nbsp;</td></tr>

    </table>

    <table border='0' id="desc" class="">

        <tr>

            <td><p><font size="2">NB</font></p></td>
            <td><p><font size="2">:</font></p></td>
            <td colspan="6"><p><font size="2">-, AR via Leasing dibebankan sebesar RP. 10.000 / unit untuk keterlambatan pencarian > 10 hari dari tanggal penjualan</font></p></td>
        </tr>
        <tr>
            <td colspan="2"><p><font size="2"></font></p></td>
            <td colspan="6"><p><font size="2">-, AR unit dibebankan sesuai dengan usia smh terjual sejak DO Surat Jalan MD per tanggal 1 Februari 2007</font></p></td>
        </tr>

        <tr>
            <td colspan="7"></td>
            <td style="text-align: right;" valign="top">
                <div class="project">
                    <!--<div><span class="title" style="text-align: right;"><?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total : " . $list->totaldata . "</i>") : '' ?></span></div>-->
                </div>
            </td>
        </tr>

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