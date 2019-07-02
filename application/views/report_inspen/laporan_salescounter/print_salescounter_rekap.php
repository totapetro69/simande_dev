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
    <h4 class="modal-title" id="myModalLabel">Rekap Insentif Sales Counter</h4>
</div>

<div class="modal-body" id="printarea">

    <table border='0' id="desc" class="">

        <tr>
            <td colspan="15"><h3><strong>FORM PERSETUJUAN PENGAJUAN INSENTIVE SALES COUNTER</strong></h3></td>
        </tr>

        <tr>
            <td></td>
        </tr>

        <tr><td colspan="15">&nbsp;</td></tr>
        <tr>
            <td colspan="3">Cabang</td>
            <td align='center'> : </td>
            <td colspan="11"></td>
        </tr>
        <tr>
            <td colspan="3">Periode (YYYYMM)</td>
            <td align='center'> : </td>
            <td colspan="11"></td>
        </tr>
        <tr>
            <td colspan="3">Tanggal Pengajuan</td>
            <td align='center'> : </td>
            <td colspan="11"></td>
        </tr>

        <tr><td colspan="15">&nbsp;</td></tr>


        <tr style="border-bottom: 1px solid; border-top: 1px solid;">

            <th style="width:40px;">No.</th>
            <th>Kategori</th>
            <th colspan="2">Nama Sales Counter</th>
            <th>Tgt</th>
            
            <th>Jual</th>
            <th>Tot. Jual</th>
            <th>Capai (%)</th>
            <th>Ins. Dasar</th>
            <th>Pengali (%)</th>
            
            <th>Instentif</th>
            <th>Tambahan</th>
            <th>Ins. Admin</th>
            <th>Penalty</th>
            <th>Tot. Ins</th>
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
                            <td colspan="2"></td>
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
                        <td colspan="14"><b><?php echo ($list->message); ?></b></td>
                    </tr>

                <?php
                endif;
            else:
                echo belumAdaData(15);
            endif;
            ?>
            <tr>

                <td colspan="3"></td>
                <td colspan="5">Total</td>
                <td colspan="2">x</td>
                <td>x</td>
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
            <td colspan="13">&nbsp;</td>
        </tr>
        <tr><td colspan="15">&nbsp;</td></tr>
        <tr><td colspan="15">&nbsp;</td></tr>

        <tr>
            <td align='center' colspan="3">Yang Mengajukan,</td>
            <td align='center' colspan="3">Memeriksa,</td>
            <td align='center' colspan="3">Menyetujui,</td>            
        </tr>

        <tr><td colspan="15">&nbsp;</td></tr>
        <tr><td colspan="15">&nbsp;</td></tr>
        <tr><td colspan="15">&nbsp;</td></tr>

        <tr>
            <td align='center' colspan="3">  ___________________  </td>
            <td align='center' colspan="3">  ___________________  </td>
            <td align='center' colspan="3">  ___________________  </td>            
        </tr>

        <tr>
            <td align='center' colspan="3">KSP</td>
            <td align='center' colspan="3">Accounting Manager</td>
            <td align='center' colspan="3">Direct Sales Manager</td>            
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