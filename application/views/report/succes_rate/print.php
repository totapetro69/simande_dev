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

    @page { size: landscape; }
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Laporan Succes Rate</h4>
</div>

<div class="modal-body" id="printarea">

    <table id="desc"  class="">

        <tr>
            <th colspan="11" class="text-center"><h2><strong>Laporan Succes Rate</strong></h2></th>

        </tr>
        <tr>
            <th colspan="11" class="text-center"><h4><strong> <?php echo $this->session->userdata("nama_dealer"); ?> </strong></h4></th>

        </tr>
        <tr>
            <th colspan="11" class="text-center"><h5><strong>Periode : <?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d/m/Y', strtotime('first day of this month')); ?> s/d <?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?></strong></h5></th>
        </tr>

        <tr>
            <!--<td></td>-->
        </tr>

        <tr><td colspan="10">&nbsp;</td></tr>

        
        <tr style="border-bottom: 1px solid; border-top: 1px solid;" class="text-center">
            <th class="text-center">No.</th>
            <th class="text-left">Kode Customer</th>
            <th class="text-left">Nama Customer</th>
            <th class="text-left">No. SPK</th>
            <th class="text-left">Tipe Motor</th>
            <th class="text-left">Keterangan Warna</th>
            <th class="text-left">Nama Sales</th>
            <th class="text-left">Tanggal</th>
        </tr>

        <?php
        echo $html;
        ?>

        <tr><td colspan="11">&nbsp;</td></tr>
        <tr><td colspan="11">&nbsp;</td></tr>

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