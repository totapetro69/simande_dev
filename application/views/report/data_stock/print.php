<style type="text/css">
    #desc {
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        width: 100%;
        font-size: 11px;
    }
    table >tbody > tr >td {
        padding-left: 5px;
        white-space: nowrap!important;
    }
  @page { size: landscape; }
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Laporan Data Stock</h4>
</div>

<div class="modal-body" style="width:100%!important" id="printarea">

    <table id="desc" style="width:100%;">

        <tr><th class="text-center"><h3><strong>Laporan Data Stock</strong></h3></th></tr>
        <tr><th class="text-center"><h4><strong>PT. <?php echo $this->session->userdata("nama_dealer"); ?> </strong></h4></th></tr>
        <tr><th  class="text-center"><h5><strong>Periode : <?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d/m/Y', strtotime('first day of this month')); ?> s/d <?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?></strong></h5></th></tr>
        <tr><td style="border-bottom: 1.5px solid">&nbsp;</td></tr>

        <tr><td>
            <table style="width: 100%; border-collapse: collapse;" border="1">
                <thead>
                    <tr style="border-bottom: 1px solid; border-top: 1px solid;" class="text-center">
                        <th style="width: 5%">No.</th>
                        <th style="width: 12%">Kode Item</th>
                        <th style="width: 25%">Nama Item</th>
                        <th style="width: 8%">Jumlah</th> 
                        <th style="width: 15%">No Rangka</th>
                        <th style="width: 15%">No Mesin</th>
                        <th style="width: 10%">Gudang</th>
                        <th style="width: 10%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        echo $html;
                    ?>
                </tbody>
                </table>
            </td>
        </tr>
        <tr><td colspan="10">&nbsp;</td></tr>
        <tr><td colspan="10">&nbsp;</td></tr>
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