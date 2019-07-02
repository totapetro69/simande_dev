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
    <h4 class="modal-title" id="myModalLabel">Laporan Insentif Salesman</h4>
</div>

<div class="modal-body" id="printarea">

    <table border='0' id="desc" class="">

        <tr>
            <td colspan="12"><h3><strong>FORM PERSETUJUAN PENGAJUAN INSENTIVE SALESMAN</strong></h3></td>
        </tr>

        <tr><td colspan="12">&nbsp;</td></tr>
        <tr>
            <td colspan="2">Cabang</td>
            <td align='center'> : </td>
            <td colspan="9"></td>
        </tr>
        <tr>
            <td colspan="2">Periode (YYYYMM)</td>
            <td align='center'> : </td>
            <td colspan="9"></td>
        </tr>
        <tr>
            <td colspan="2">Nama Salesman</td>
            <td align='center'> : </td>
            <td colspan="9"></td>
        </tr>
        <tr>
            <td colspan="2">Tanggal Pengajuan</td>
            <td align='center'> : </td>
            <td colspan="9"></td>
        </tr>
        <tr><td colspan="12">&nbsp;</td></tr>


        <tr style="border-bottom: 1px solid; border-top: 1px solid;">

            <th style="width:40px;">No.</th>
            <th>Tgl. Fak</th>
            <th>No. Fak</th>
            <th>Nama Konsumen</th>
            <th>Nama Tipe</th>
            <th>Ket. Tipe</th>
            <th>Kode</th>
            <th>Via</th>
            <th>DP/OTR</th>
            <th>Sub.Dlr+Disc</th>
            <th>Program</th>
            <th>Insentif</th>
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
                        <td colspan="11"><b><?php echo ($list->message); ?></b></td>
                    </tr>

                <?php
                endif;
            else:
                echo belumAdaData(11);
            endif;
            ?>
            <tr>
                <td colspan="3">Target</td>
                <td colspan="6">x</td>
                <td colspan="2"><p><font size="2">Insentif Dasar</font></p></td>
                <td><p><font size="2">x</font></p></td>
            </tr>
            <tr>
                <td colspan="3">Penjualan</td>
                <td colspan="6">x</td>
                <td colspan="2"><p><font size="2">Pencapaian</font></p></td>
                <td><p><font size="2">x</font></p></td>
            </tr>
            <tr>
                <td colspan="9"></td>
                <td colspan="2"><p><font size="2">Pengali Insentif Dasar</font></p></td>
                <td><p><font size="2">x</font></p></td>
            </tr>
            <tr>
                <td colspan="9"></td>
                <td colspan="2"><p><font size="2">Insentif</font></p></td>
                <td><p><font size="2">x</font></p></td>
            </tr>
            <tr>
                <td colspan="9"></td>
                <td colspan="2"><p><font size="2">Tambahan</font></p></td>
                <td><p><font size="2">x</font></p></td>
            </tr>
            <tr>
                <td colspan="9"></td>
                <td colspan="2"><p><font size="2">Insentif Admin</font></p></td>
                <td><p><font size="2">x</font></p></td>
            </tr>
            <tr>
                <td colspan="9"></td>
                <td colspan="2"><p><font size="2">Penalty</font></p></td>
                <td><p><font size="2">x</font></p></td>
            </tr>
            <tr>
                <td colspan="9"></td>
                <td colspan="2"><p><font size="2">Total Insentif</font></p></td>
                <td><p><font size="2">x</font></p></td>
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