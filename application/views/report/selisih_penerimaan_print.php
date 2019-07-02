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
    .modal-lg {
    width: 1130px;
    /*@page { size: portrait; }*/
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Laporan Shipping List Tidak Diterima</h4>
</div>

<div class="modal-body" id="printarea">

    <table id="desc" >
     
            <tr>
                <td colspan="13"><h2><strong>Laporan Shipping List Tidak Diterima</strong></h2></td>
            </tr>

            <tr>
                <td colspan="13">Periode : <?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d-m-Y', strtotime('first day of this month')); ?> s/d <?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?></td>
                <!--<td></td>-->
            </tr>
            <tr><td colspan="13">&nbsp;</td></tr>
    </table>
    <table id="desc" class="table table-striped table-bordered">

            

            <tr style="border-bottom: 1px solid; border-top: 1px solid;" class="text-center">
                <th rowspan="2" style="width:40px; vertical-align: middle;">No.</th>
                <th colspan="6" style="text-align: center;">No Shipping List</th>
                <th colspan="6" style="text-align: center;">Tanggal Pengiriman / Shipping</th>
            </tr>
            <tr style="border-bottom: 1px solid; border-top: 1px solid;">
                <th>KD Tipe Unit</th>
                <th>Deskripsi Unit</th>
                <th>KD Warna</th>
                <th>Deskripsi Warna</th>                           
                <th>Kuantitas Diterima</th>                           
                <th>No. Rangka</th>
                <th>No. Mesin</th>
              <!--   <th>Kelengkapan Unit</th> -->
                <th>No. SJ</th>
                <th>No. PO</th>
                <th>No. Faktur</th>
                <th>Nama Tagihan</th>
            </tr>
        <tbody>
            <?php
//            var_dump($list);
            $no = $this->input->get('page');
            if ($list):
                if (is_array($list->message) || is_object($list->message)):
                    foreach ($list->message as $key => $group_row):
                        $no ++;
                        ?>
                        <tr style="border-bottom: 1px solid;" class="info bold">
                            <td class="text-bold"><?php echo $no; ?></td>
                            <td colspan="5" style="text-align: center;"><?php echo $group_row->NO_SJMASUK; ?></td>
                            <td colspan="6" style="text-align: center;"><?php echo $group_row->TGL_SJMASUK; ?></td>
                        </tr>

                        <?php
                        if ($list_group) {
                            if ($list_group->totaldata > 0) {

                                foreach ($list_group->message as $row):
                                    if ($group_row->NO_SJMASUK == $row->NO_SJMASUK):
                                        ?>

                                        <tr>
                                           <!--  <td colspan="2"> <?php //echo str_replace("/","-",tglfromSql($row->TGL_TRANS)); ?> </td> -->
                                            <td></td>
                                            <td><?php echo $row->KD_TYPEMOTOR; ?></td>
                                            <td><?php echo $row->NAMA_TYPEMOTOR; ?></td>
                                            <td><?php echo $row->KD_WARNA; ?></td>
                                            <td><?php echo $row->KET_WARNA; ?></td>
                                            <td><?php echo $row->JUMLAH; ?></td>
                                            <td><?php echo $row->NO_RANGKA; ?></td>
                                            <td><?php echo $row->NO_MESIN; ?></td>
                                            
                                            <td><?php echo $row->NO_SJMASUK; ?></td>
                                            <td><?php echo $row->NO_PO; ?></td>
                                            <td><?php echo $row->NO_FAKTUR; ?></td>
                                            <td><?php echo $row->NAMA_TAGIHAN; ?></td>
                                        </tr>
                                        <!--<tr  style="border-bottom: 2px solid; "></tr>-->

                                        <?php
                                    endif;
                                endforeach;
                            }
                        }?>
                        <tr style="border-bottom: 1px solid; "></tr>
                        <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                        <td colspan="13"><b><?php echo ($list->message); ?></b></td>
                    </tr>
                <?php
                endif;
            else:

                belumAdaData(13);

            endif;
            ?>
        </tbody>

    </table>
    <table>
        <tr><td colspan="13">&nbsp;</td></tr>
        <tr><td colspan="13">&nbsp;</td></tr>

        <tr>
            <td colspan="13"></td>
            <td style="text-align: right;" valign="top">
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