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
    <h4 class="modal-title" id="myModalLabel">Laporan KSU</h4>
</div>

<div class="modal-body" id="printarea">

    <table  id="desc" class="table table-striped b-t b-light">

        <tr>
            <td colspan="8"><h2><strong>Laporan KSU</strong></h2></td>
        </tr>

        <tr><td colspan="8">&nbsp;</td></tr>


        <tr style="border-bottom: 1px solid; border-top: 1px solid;">

            <th style="width:40px;">No.</th>
            <th>Kode KSU</th>
            <th>Nama KSU</th>
            <th>Total Unit</th>
            <th>KSU Diterima</th>
            <th>KSU Belum Diterima</th>
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
                            <td><?php echo $row->KD_KSU; ?></td>
                            <td><?php echo $row->NAMA_KSU; ?></td>
                            <?php if ($row->KD_KSU == "BPPSG") { ?>
                                <td class='text-center' ><?php echo $row->BPPSG + $row->NBPPSG; ?></td>
                            <?php } else if ($row->KD_KSU == "AKI") { ?>
                                <td class='text-center' ><?php echo $row->AKI + $row->NAKI; ?></td>
                            <?php } else if ($row->KD_KSU == "HELM") { ?>
                                <td class='text-center' ><?php echo $row->HELM + $row->NHELM; ?></td>
                            <?php } else if ($row->KD_KSU == "SPION") { ?>
                                <td class='text-center' ><?php echo $row->SPION + $row->NSPION; ?></td>
                            <?php } else { ?>
                                <td class='text-center' ><?php echo $row->TOOLSET + $row->NTOOLSET; ?></td>
                            <?php }; ?>

                            <?php if ($row->KD_KSU == "BPPSG") { ?>
                                <td class='text-center' ><?php echo $row->BPPSG; ?></td>
                            <?php } else if ($row->KD_KSU == "AKI") { ?>
                                <td class='text-center' ><?php echo $row->AKI; ?></td>
                            <?php } else if ($row->KD_KSU == "HELM") { ?>
                                <td class='text-center' ><?php echo $row->HELM; ?></td>
                            <?php } else if ($row->KD_KSU == "SPION") { ?>
                                <td class='text-center' ><?php echo $row->SPION; ?></td>
                            <?php } else { ?>
                                <td class='text-center' ><?php echo $row->TOOLSET; ?></td>
                            <?php }; ?>

                            <?php if ($row->KD_KSU == "BPPSG") { ?>
                                <td class='text-center' ><?php echo $row->NBPPSG; ?></td>
                            <?php } else if ($row->KD_KSU == "AKI") { ?>
                                <td class='text-center' ><?php echo $row->NAKI; ?></td>
                            <?php } else if ($row->KD_KSU == "HELM") { ?>
                                <td class='text-center' ><?php echo $row->NHELM; ?></td>
                            <?php } else if ($row->KD_KSU == "SPION") { ?>
                                <td class='text-center' ><?php echo $row->NSPION; ?></td>
                            <?php } else { ?>
                                <td class='text-center' ><?php echo $row->NTOOLSET; ?></td>
                            <?php }; ?>
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