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

    <table class="table table-striped b-t b-light">

        <tr>
            <td colspan="9"><h2><strong>Laporan Part</strong></h2></td>
        </tr>

        <tr>
            <!--<td colspan="8">Periode : <?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d-m-Y', strtotime('first day of this month')); ?> s/d <?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?></td>-->
            <!--<td></td>-->
        </tr>

        <tr><td colspan="9">&nbsp;</td></tr>


        <tr style="border-bottom: 1px solid; border-top: 1px solid;">
            <th style="width:40px;">No.</th>
            <th>Part Number</th>
            <th>Deskripsi Part</th>
            <th>Part Reference</th>
            <th>Kode Supplier</th>
            <th>Kode Group Sales</th>
            <th>Part Status</th>
            <th>HET</th>
            <th>Harga Pokok</th>

        </tr>
        <tbody>
            <?php
            $no = $this->input->get('page');
            if ($list):
                if (is_array($list->message) || is_object($list->message)):
                    foreach ($list->message as $key => $row):
                        $no ++;
                        ?>

                        <tr id="<?php echo $this->session->flashdata('tr-active') == $row->PART_NUMBER ? 'tr-active' : ' '; ?>" >
                            <td><?php echo $no; ?></td>
                            <td class='table-nowarp'><?php echo $row->PART_NUMBER; ?></td>
                            <td class='table-nowarp'><?php echo $row->PART_DESKRIPSI; ?></td>
                            <td class='table-nowarp'><?php echo $row->PART_REFERENCE; ?></td>
                            <td><?php echo $row->KD_SUPPLIER; ?></td>
                            <td><?php echo $row->KD_GROUPSALES; ?></td>
                            <td><?php echo $row->PART_STATUS; ?></td>
                            <td class="text-right" ><?php echo number_format($row->HET, 0); ?></td>
                            <td class="text-right"><?php echo number_format($row->HARGA_BELI, 0); ?></td>
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

        <tr>
            <td colspan="7"></td>
            <td colspan="2"style="text-align: right;" valign="top">
                <div class="project">
                    <div><span class="title" style="text-align: right;"><?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total : " . $list->totaldata . "</i>") : '' ?></span></div>
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