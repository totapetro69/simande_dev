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
    <h4 class="modal-title" id="myModalLabel">Laporan Insentif KSP</h4>
</div>

<div class="modal-body" id="printarea">

    <table border='0' id="desc" class="">

        <tr>
            <td colspan="9"><h3><strong>REKAP INSENTIVE KSP</strong></h3></td>
        </tr>

        <tr>
            <td></td>
        </tr>

        <tr><td colspan="9">&nbsp;</td></tr>
        <tr>
            <td colspan="2">Cabang</td>
            <td align='center'> : </td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="2">Bulan/Tahun</td>
            <td align='center'> : </td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="2">Nama KSP</td>
            <td align='center'> : </td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="2">Tanggal Pengajuan</td>
            <td align='center'> : </td>
            <td colspan="6"></td>
        </tr>
        <tr><td colspan="9">&nbsp;</td></tr>


        <tr style="border-bottom: 1px solid; border-top: 1px solid;">

            <th style="width:40px;">No.</th>
            <th>Tot. Sales</th>
            <th>RPK</th>
            <th>Margin/Unit</th>
            <th>Insentif/Unit</th>
            <th>Insentif</th>
            <th>Penalty</th>
            <th>PPh21</th>
            <th>Insentif Diterima</th>
        </tr>

<!--        <tbody>
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
        </tbody>-->

    </table>

    <table border='0' id="desc" class="">
        <tr>
            <td colspan="9">Keterangan :</td>

        </tr>
        <tr>
            <td colspan="2">

                <table border='1' id="desc" class="">
                    <tr>
                        <td align='center'>RPK</td>
                        <td align='center'>Insentif</td>
                    </tr>
                    <tr>
                        <td align='center'>A</td>
                        <td align='right'>%  </td>
                    </tr>
                    <tr>
                        <td align='center'>B</td>
                        <td align='right'>%  </td>
                    </tr>
                    <tr>
                        <td align='center'>C</td>
                        <td align='right'>%  </td>
                    </tr>
                    <tr>
                        <td align='center'>D</td>
                        <td align='right'>%  </td>
                    </tr>
                </table>
            </td>
        </tr>


        <tr>
            <td colspan="2">&nbsp;</td>
            <td colspan="7">&nbsp;</td>
        </tr>
        <tr><td colspan="7">&nbsp;</td></tr>
        <tr><td colspan="7">&nbsp;</td></tr>
        <tr>
            <td></td>
            <td align='center' colspan="2">Yang Membuat,</td>
            <td align='center' colspan="4">Mengetahui,</td>
            <td align='center' colspan="2">Menerima,</td>            
        </tr>
        
        <tr><td colspan="8">&nbsp;</td></tr>
        <tr><td colspan="8">&nbsp;</td></tr>
        <tr><td colspan="8">&nbsp;</td></tr>
        
        <tr>
            <td></td>
            <td align='center' colspan="2">  ___________________  </td>
            <td align='center' colspan="2">  ___________________  </td>
            <td align='center' colspan="2">  ___________________  </td>
            <td align='center' colspan="2">  ___________________  </td>            
        </tr>
        
        <tr>
            <td></td>
            <td align='center' colspan="2">Accounting Manager</td>
            <td align='center' colspan="2">Finance Director</td>
            <td align='center' colspan="2">Genereal Manager</td>
            <td align='center' colspan="2">KSP</td>            
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