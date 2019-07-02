<?php
$defaulD = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$defaulD_salesman = ($this->input->get("kd_salesman")) ? $this->input->get("kd_salesman") : $this->session->userdata("kd_salesman");
if ($sales->totaldata==1) {
    if($sales->message[0]->KD_JABATAN == 'S. Re' ||$sales->message[0]->KD_JABATAN == 'SW' || $sales->message[0]->KD_JABATAN == '') {
        $jenisSales = 'REGULER';
    }elseif ($sales->message[0]->KD_JABATAN == 'S.Win' ) {
              $jenisSales = 'WING';
    }elseif ($sales->message[0]->KD_JABATAN == 'SWAT' ) {
              $jenisSales = 'SWAT';
    }else{
        $jenisSales = $sales->message[0]->KD_JABATAN;
    }
}



?>



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
    .modal-lg {
    width: 1330px;
}

    @page { size: landscape; }
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Laporan Penalty Insentif Sales Unit</h4>
</div>

<div class="modal-body" id="printarea">

    <table  border="0" id="desc" class="">
        <tr>            
            <td  align="center" colspan="11"><h2><strong>Laporan Penalty Insentif Sales Unit</strong></h2></td>
        </tr>

        <tr>
            <th colspan="11" class="text-center"><h5><strong>Periode  <?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal")  : "";  ?> <?php echo ($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir")  : "";?></strong></h5></th>
        </tr>

      

    </table>

    <table border="0" id="desc" class="">
       
       
        <tr>
            <td>Cabang</td>
            <td>:</td>
            <td><?php echo NamaDealer($defaulD); ?></td>
            <td colspan="8">&nbsp;</td>
        </tr>
        <tr>
            <td>Periode</td>
            <td>:</td>
            <td><?php echo $this->input->get("tgl_awal").'-'.$this->input->get("tgl_akhir"); ?></td>
            <td colspan="8">&nbsp;</td>
        </tr> 
        <tr>
            <td>Nama Kepala Sales</td>
            <td>:</td>
            <td><?php echo ($sales->totaldata == 1)? $sales->message[0]->NAMA_SALES.'  - '.$jenisSales:"";  ?> </td>
            <td colspan="8">&nbsp;</td>
           
        </tr>
       <!--  <tr>
            <td>Tanggal Pengajuan</td>
            <td>:</td>
            <td><?php //echo $this->input->get("tgl_pengajuan"); ?></td>
            <td colspan="8">&nbsp;</td>
        </tr> -->
    </table>        

            <table class="table table-striped table-bordered">
              <thead>
                            <tr>
                                <th rowspan="2" style="text-align: center !important">Bulan</th>
                                <th colspan="2" style="text-align: center !important">AR Leasing OD</th>
                                <th colspan="3" style="text-align: center !important">AR Unit Overdue</th>
                                <th colspan="3" style="text-align: center !important">AR Unit Overdue (Khusus CS 1)</th>
                                <th rowspan="2" style="text-align: center !important">Total Overdue (TO)</th>
                                <th rowspan="2" style="text-align: center !important">K. Sales (10% x TO)</th>
                            </tr>
                            <tr>
                               
                                <th  style="text-align: center !important">Unit</th>
                                <th  style="text-align: center !important">Rp</th>

                                <th  style="text-align: center !important">Unit</th>
                                <th  style="text-align: center !important">Rp</th>
                                <th  style="text-align: center !important">OD USia SHM</th>

                                <th  style="text-align: center !important">Unit</th>
                                <th  style="text-align: center !important">Rp</th>
                                <th  style="text-align: center !important">OD USia SHM</th>

                                
                               
                              
                               
                            </tr>
                           
                        </thead>
                        
                        <tbody>
                            <?php
                            $no = $this->input->get('page'); 
                            if ($list):
                                if (is_array($list->message) || is_object($list->message)):
                                    $total = 0;
                                                foreach ($list->message as $key => $value) {
                                                    $no++;

                                                    ?>
                                                        <tr>
                                                           
                                                            <td class='text-center table-nowarp'></td>
                                                            <td class='table-nowarp'><?php echo $value->JUAL;?></td>
                                                            <td class='table-nowarp'><?php echo $value->PENALTY_AR;?></td>

                                                            <td class='text-right table-nowarp'>0</td>
                                                            <td class='text-right table-nowarp'>0</td>
                                                            <td class='table-nowarp'></td>

                                                            <td class='text-right table-nowarp'>0</td>
                                                            <td class='text-right table-nowarp'>0</td>
                                                            <td class='table-nowarp'></td>

                                                            <td class='text-right table-nowarp'><?php echo $value->PENALTY_AR; ?></td>
                                                            <td class='text-right table-nowarp'><?php echo $penalty_k = $value->PENALTY_AR/10;?></td>
                                                        </tr>
                                                    <?php
                                                        $total = $total + $penalty_k;

                                                        }?>
                                                    <tr>
                                                        <td class="text-right" colspan="10"><strong>Total Penalty</strong></td>
                                                        <td class="text-right"><strong><?php echo $total; ?></strong></td>
                                                    </tr>

                                  
                                  
                                    <?php
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

<script type="text/javascript">
     $(document).ready(function () {
            /*pilihan propinsi*/
            
                loadData('kd_sales', $('#kd_sales').val(), '0')         

        })
      function loadData(id, value, select) {

            var param = $('#' + id + '').attr('title');
            
            $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
            var urls = "<?php echo base_url(); ?>laporan_insentif/" + param;
            var datax = {"kd": value};

            $('#' + id + '').attr('disabled', 'disabled');
            //alert(param);


            $.ajax({
                type: 'GET',
                url: urls,
                data: datax,
                typeData: 'html',
                success: function (result) {
                    
                   $("#sales").html(result);
                }
            });
        }
</script>