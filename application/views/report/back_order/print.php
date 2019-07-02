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
        width: 150px;
    }

    @page { size: landscape; }
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Laporan Part</h4>
</div>

<div class="modal-body" id="printarea">



        <tr>
            <td colspan="9"><h2><strong>Laporan Back Order</strong></h2></td>
        </tr>

  

        <tr><td colspan="9">&nbsp;</td></tr>


        <?php
        if ($pilih == 0) {
            ?>
            <table class="table table-striped b-t b-light">
                <thead>
                    <tr>
                        <th style="width:40px;">No.</th>
                        <th>Part Number</th>
                        <th>Part Deskripsi</th>
                        <th>Jenis PO</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $this->input->get('page');
                    if ($list):
                        if (is_array($list->message) || is_object($list->message)):
                            foreach ($list->message as $key => $row):
                                $no ++;
                                ?>

                                <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $row->PART_NUMBER; ?></td>
                                    <td><?php echo $row->PART_DESKRIPSI ?></td>
                                    <td><?php echo $row->JENIS_PO ?></td>
                                    <td><?php echo $row->ROW_STATUS == 0 ? 'Aktif':'Tidak Aktif';?></td>
                                </tr>

                                <?php
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
                </tbody>
            </table>


            
            <?php
            }else {
            ?>

            <table class="table table-striped b-t b-light">
                <thead>
                    <tr>
                        <th style="width:40px;">No.</th>
                        <th>Part Number</th>
                        <th>Part Deskripsi</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $this->input->get('page');
                    if ($list):
                        if (is_array($list->message) || is_object($list->message)):
                            foreach ($list->message as $key => $row):
                                $no ++;
                                ?>

                                <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $row->PART_NUMBER ?></td>
                                    <td><?php echo $row->PART_DESKRIPSI ?></td>
                                    <td><?php echo $row->ROW_STATUS == 0 ? 'Aktif':'Tidak Aktif';?></td>
                                </tr>

                                <?php
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
                </tbody>
            </table>
            <?php
        }
        ?>



        <tr><td colspan="9">&nbsp;</td></tr>
        <tr><td colspan="9">&nbsp;</td></tr>

        <tr>
            <td colspan="8"></td>
            <td style="text-align: right;" valign="top">
                <div class="project">
                    <div><span class="title" style="text-align: right;"><?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total : " . $list->totaldata . "</i>") : '' ?></span></div>
                </div>
            </td>
        </tr>



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