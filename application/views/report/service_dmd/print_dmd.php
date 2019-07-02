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
    <h4 class="modal-title" id="myModalLabel">Report Service Rate</h4>
</div>

<div class="modal-body" id="printarea">

    <table border='0' id="desc" class="">

        <tr align='center'>
            <td rowspan="2" colspan="2"><?php echo ($pilih == 0) ? 'Main Dealer to Dealer' : 'Dealer to Customer'; ?> </td>
                <td colspan="7"><h2><strong>REPORT SERVICE RATE BY QUANTITY / AMOUNT</strong></h2></td>
            <td rowspan="2" colspan="2">
                <div class="project" >
                    <span class="title" style="text-align: center;">Tgl Proses : <?php echo date('d/m/Y') ?></span>
                </div>
            </td>
        </tr>

        <tr align='center'>
            <td><h7>Periode : <?php echo sprintf("%'.02d", $this->input->get('bulan'))."/".$this->input->get("tahun") ?></h7></td>
        </tr>

    </table>


    <table border='0' class="table table-striped b-t b-light">

        <thead>
            <tr>
                <th style="width:40px;">No.</th>
                <th>Part Number</th>
                <th>Deskripsi</th>
                <th>Qty. Order</th>
                <th>Qty. Supply</th>
                <th>Amount Order</th>
                <th>Amount Supply</th>
                <th>SR Q</th>
                <th>SR A</th>
            </tr>
        </thead>
        <?php
        if ($pilih == 0) {
            ?>
            <tbody>
                <?php
                $tqtyorder = 0;
                $tqtysupply = 0;
                $tamountorder = 0;
                $tamountsupply = 0;
                $srqpersen = 0;
                $tsrq = 0;
                $srapersen = 0;
                $tsra = 0;
                $no = $this->input->get('page');
                if ($list):
                    if (is_array($list->message) || is_object($list->message)):
                        foreach ($list->message as $key => $row):
                            $AMOUNT_SUPPLY = $row->JUMLAH2 * $row->HARGA;
                            $SRQ = $row->JUMLAH2 == 0 ? 0 : ($row->JUMLAH1) / ($row->JUMLAH2) * 100;
                            $SRA = $AMOUNT_SUPPLY == 0 ? 0 : $row->HARGA / $AMOUNT_SUPPLY * 100;

                            $no ++;
                            ?>

                            <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                <td><?php echo $no; ?></td>
                                <td><?php echo $row->PART_NUMBER; ?></td>
                                <td><?php echo $row->PART_DESKRIPSI; ?></td>
                                <td align="center"><?php echo $row->JUMLAH1; ?></td>                      <!--QTY ORDER-->
                                <td align="center"><?php echo $row->JUMLAH2; ?></td>                            <!--QTY SUPPLY-->
                                <td align="center"><?php echo number_format($row->HARGA, 2); ?></td>      <!--AMOUNT ORDER-->
                                <td align="center"><?php echo number_format($AMOUNT_SUPPLY, 2); ?></td>        <!--AMOUNT SUPPLY-->
                                <td align="center"><?php echo $SRQ; ?>%</td>                                    <!--SRQ-->
                                <td align="center"><?php echo $SRA; ?>%</td>     

                                <?php
                                $tqtyorder += $row->JUMLAH1;
                                $tqtysupply += $row->JUMLAH2;
                                $tamountorder += $row->HARGA;
                                $tamountsupply += $AMOUNT_SUPPLY;
                                $srqpersen += $SRQ;
                                $tsrq = ($srqpersen / $no);
                                $srapersen += $SRA;
                                $tsra = ($srapersen / $no);
                            endforeach;
                        else:
                            ?>
                        <tr>
                            <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                            <td colspan="8"><b><?php echo ($list->message); ?></b></td>
                        </tr>
                    <?php
                    endif;
                else:
                    ?>
                    <tr>
                        <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                        <td colspan="8"><b>Ada error, harap hubungi bagian IT</b></td>
                    </tr>
                <?php
                endif;
                ?>
                <tr>
                    <td colspan="3" style="text-align: right;" valign="top">
                        <div class="project">
                            <div><span class="title" style="text-align: right;">TOTAL</span></div>
                        </div>
                    </td>
                    <td align="center"><?php echo number_format($tqtyorder, 2); ?></td>
                    <td align="center"><?php echo number_format($tqtysupply, 2); ?></td>
                    <td align="center"><?php echo number_format($tamountorder, 2); ?></td>
                    <td align="center"><?php echo number_format($tamountsupply, 2); ?></td>
                    <td align="center"><?php echo number_format($tsrq, 2); ?>%</td>
                    <td align="center"><?php echo number_format($tsra, 2); ?>%</td>
                </tr>
            </tbody>

            <?php
        }elseif ($pilih == 1) {
            ?>

            <tbody>
                <?php
                $tqtyorder = 0;
                $tqtysupply = 0;
                $tamountorder = 0;
                $tamountsupply = 0;
                $srqpersen = 0;
                $tsrq = 0;
                $srapersen = 0;
                $tsra = 0;
                $no = $this->input->get('page');
                if ($list):
                    if (is_array($list->message) || is_object($list->message)):
                        foreach ($list->message as $key => $row):
                            $AMOUNT_SUPPLY = $row->JUMLAH * $row->HARGA_JUAL;
                            $SRQ = $row->JUMLAH == 0 ? 0 : ($row->JUMLAH_ORDER) / ($row->JUMLAH) * 100;
                            $SRA = $AMOUNT_SUPPLY == 0 ? 0 : $row->HARGA_JUAL / $AMOUNT_SUPPLY * 100;

                            $no ++;
                            ?>

                            <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                <td><?php echo $no; ?></td>
                                <td><?php echo $row->PART_NUMBER; ?></td>
                                <td><?php echo $row->PART_DESKRIPSI; ?></td>
                                <td align="center"><?php echo $row->JUMLAH_ORDER; ?></td>                      <!--QTY ORDER-->
                                <td align="center"><?php echo $row->JUMLAH; ?></td>                            <!--QTY SUPPLY-->
                                <td align="center"><?php echo number_format($row->HARGA_JUAL, 2); ?></td>      <!--AMOUNT ORDER-->
                                <td align="center"><?php echo number_format($AMOUNT_SUPPLY, 2); ?></td>        <!--AMOUNT SUPPLY-->
                                <td align="center"><?php echo $SRQ; ?>%</td>                                    <!--SRQ-->
                                <td align="center"><?php echo $SRA; ?>%</td>                                    <!--SRA-->
                            </tr>

                            <?php
                            $tqtyorder += $row->JUMLAH_ORDER;
                            $tqtysupply += $row->JUMLAH;
                            $tamountorder += $row->HARGA_JUAL;
                            $tamountsupply += $AMOUNT_SUPPLY;
                            $srqpersen += $SRQ;
                            $tsrq = ($srqpersen / $no);
                            $srapersen += $SRA;
                            $tsra = ($srapersen / $no);
                        endforeach;
                    else:
                        ?>
                        <tr>
                            <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                            <td colspan="8"><b><?php echo ($list->message); ?></b></td>
                        </tr>
                    <?php
                    endif;
                else:
                    ?>
                    <tr>
                        <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                        <td colspan="8"><b>Ada error, harap hubungi bagian IT</b></td>
                    </tr>
                <?php
                endif;
                ?>
                <tr>
                    <td colspan="3" style="text-align: right;" valign="top">
                        <div class="project">
                            <div><span class="title" style="text-align: right;">TOTAL</span></div>
                        </div>
                    </td>
                    <td align="center"><?php echo number_format($tqtyorder, 2); ?></td>
                    <td align="center"><?php echo number_format($tqtysupply, 2); ?></td>
                    <td align="center"><?php echo number_format($tamountorder, 2); ?></td>
                    <td align="center"><?php echo number_format($tamountsupply, 2); ?></td>
                    <td align="center"><?php echo number_format($tsrq, 2); ?>%</td>
                    <td align="center"><?php echo number_format($tsra, 2); ?>%</td>
                </tr>
            </tbody>



            <?php
        }
        ?>
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