<?php
$defaulD = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$defaulD_salesman = ($this->input->get("kd_salesman")) ? $this->input->get("kd_salesman") : $this->session->userdata("kd_salesman");
if ($sales->totaldata==1) {
    if($sales->message[0]->KD_JABATAN == 'S. Re' ||$sales->message[0]->KD_JABATAN == 'SW' || $sales->message[0]->KD_JABATAN == '') {
        $jenisSales = 'REGULER';
    } elseif ($sales->message[0]->KD_JABATAN == 'S.Win' ) {
              $jenisSales = 'WING';
    }elseif ($sales->message[0]->KD_JABATAN == 'SWAT' ) {
              $jenisSales = 'SWAT';
    }else{
        $jenisSales = $sales->message[0]->KD_JABATAN;
    }
}


//print_r($defaulD_salesman);
?>

<?php                 
                            

                            
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
    <h4 class="modal-title" id="myModalLabel">Rekap Insentif KSP</h4>
</div>

<div class="modal-body" id="printarea">

    <table  border="0" id="desc" class="">
        <tr>            
            <td  align="center" colspan="11"><h2><strong>Rekap Insentif KSP</strong></h2></td>
        </tr>

        <tr>
            <th colspan="11" class="text-center"><h5><strong>Periode  <?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal")  : date('m'); ?>   <?php echo ($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('Y'); ?></strong></h5></th>
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
            <td><?php echo $this->input->get("tgl_awal")  .' - '.$this->input->get("tgl_akhir"); ?></td>
            <td colspan="8">&nbsp;</td>
        </tr> 
      
        <tr>
            <td>Tanggal Pengajuan</td>
            <td>:</td>
            <td><?php echo $this->input->get("tgl_pengajuan"); ?></td>
            <td colspan="8">&nbsp;</td>
        </tr>
    </table>        

            <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                                    <th  style="text-align: center !important">No</th>
                                    <th  style="text-align: center !important">Tot. Sales</th>
                                    <th  style="text-align: center !important">RPK</th>
                                    <th  style="text-align: center !important">Margin/Unit</th>
                                    <th  style="text-align: center !important">Insentif/Unit</th>
                                    <th  style="text-align: center !important">Insentif</th>
                                    <th  style="text-align: center !important">Penalty</th>
                                    <th  style="text-align: center !important">PPh21</th>
                                    <th  style="text-align: center !important">Insentif Diterima</th>                               
                                </tr>
                    </thead>
                    <tbody>
            <?php
            $no = $this->input->get('page'); $n=0;
            if ($list){
                if (is_array($list->message) || is_object($list->message)){
                    $t_ins_dasar = 0;
                    $t_insentif = 0;
                    $t_tambahan = 0;
                    $t_ins_admin = 0;
                    $t_penalty = 0;
                    $t_total_insentif = 0;
                    foreach ($list->message as $key => $value) {
                          $n++;                                
                        ?>
                        <tr>
                            <td class='text-center table-nowarp'><?php echo $n;?></td>                                                   
                            <?php 
                            $kategori =($value->KD_JABATAN =='S. Re' || $value->KD_JABATAN=='SW')?'Reguler':''; 
                            $kategori =($value->KD_JABATAN =='S.Win')?'Wing':''; 
                            $kategori =($value->KD_JABATAN =='SWAT')?'SWAT':''; 
                            $kategori =($value->KD_JABATAN =='Kepala Sales')? $value->KD_JABATAN:''; 
                            $kategori =($value->KD_JABATAN =='Kepala Counter')?$value->KD_JABATAN:''; 
                            ?>
                            <td class='table-nowarp'><?php echo $kategori;?></td>
                            <td class='table-nowarp'><?php echo $value->NAMA_SALES;?></td>
                            <td class='table-nowarp'><?php echo $value->NAMA_ATASAN;?></td>
                            <td class='text-right table-nowarp'><?php echo $value->TARGET;?></td>
                            <td class='text-right table-nowarp'><?php echo $value->JUAL;?></td>
                            <td class='text-right table-nowarp'><?php echo $value->JUAL;?></td>
                            <td class='text-right table-nowarp'><?php echo $pencapaian = ($value->TARGET != NULL)?($value->JUAL/$value->TARGET)*100: 0 ;?></td>
                            <td class='text-right table-nowarp'><?php echo number_format($ins_dasar = $value->INS_DASAR,0); ?></td>
                            <td class='text-right table-nowarp'><?php echo $pengali =  ($pencapaian < 100)?50:100;?></td>                                                    
                            <td class='text-right table-nowarp'><?php echo number_format($insentif = $pengali/100*$value->INS_DASAR,0);?></td>
                            <td class='text-right table-nowarp'><?php echo number_format($tambahan = ($pencapaian>=150)?$value->JUAL*30000: 0,0);?></td>
                            <td class='text-right table-nowarp'><?php echo number_format($ins_admin = ($value->PERSONAL_JABATAN = 'Salesman' || $value->PERSONAL_JABATAN = 'Kepala Sales' || $value->PERSONAL_JABATAN = 'Kepala Counter')? 5/100*($insentif+$tambahan) : 10/100*($insentif+$tambahan),0);?></td>
                            <td class='text-right table-nowarp'><?php echo number_format($penalty = 0,0);?></td>
                            <td class='text-right table-nowarp'><?php echo number_format($total_insentif = $insentif + $tambahan - $ins_admin - $penalty,0);?></td>                                    
                           
                        </tr>                                
                    
                    <?php  
                        $t_ins_dasar      += $ins_dasar;
                        $t_insentif       += $insentif;
                        $t_tambahan       += $tambahan;
                        $t_ins_admin      += $ins_admin;
                        $t_penalty        += $penalty;
                        $t_total_insentif += $total_insentif;

                    } 

                    ?>
                        <tr class='total'>
                            <td class="text-right" colspan="4">Total</td>
                            <td colspan="4"></td>
                            <td class="text-right"><?php echo number_format($t_ins_dasar,0); ?></td>
                            <td class="text-right"></td>
                            <td class="text-right"><?php echo number_format($t_insentif,0); ?></td>
                            <td class="text-right"><?php echo number_format($t_tambahan,0); ?></td>
                            <td class="text-right"><?php echo number_format($t_ins_admin,0); ?></td>
                            <td class="text-right"><?php echo number_format($t_penalty,0); ?></td>
                            <td class="text-right"><?php echo number_format($t_total_insentif,0); ?></td>
                        </tr>               
                    <?php
                }else{
                    ?>

                        <tr>
                            <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                            <td colspan="8"><b><?php echo ($list->message); ?></b></td>
                        </tr>

                <?php
                }
            }else{
                ?>
                        <tr>
                            <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                            <td colspan="8"><b>Ada error, harap hubungi bagian IT</b></td>
                        </tr>

            <?php
            }
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