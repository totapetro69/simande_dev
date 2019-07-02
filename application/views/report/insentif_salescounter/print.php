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
                            $insentif_dasar        = 0;
                            $target                = 0;  
                            $penjualan             = 0;  
                            $pencapaian            = 0;  
                            $dasar_pengali         = 0;
                            $jumlah_insentif       = 0;  
                            $tambahan              = 0;
                            $personal_jabatan      = 0;
                            $insentif_admin_persen = 0;
                            $insentif_admin        = 0;
                            $penalty               = 0;
                            $total_insentif        = 0;
                            
                        if(isset($list)){    
                            if ($list->totaldata>0) {
                                 foreach ($list->message as $key => $row) {
                                                                      
                                    //$insentif  = ($value->TYPE_PENJUALAN =='CREDIT' )? $value->KREDIT:$value->CASH;
                                    $insentif  = $row->INSENTIF;
                                    $insentif_dasar += $insentif;
                                }
                                $penjualan             = $list->totaldata;  
                                if ($sales) {
                                   $target = ($sales->message[0]->TARGET != NULL || $sales->message[0]->TARGET != 0 )?$sales->message[0]->TARGET:1;
                                   $personal_jabatan = ($sales->message[0]->PERSONAL_JABATAN != NULL || $sales->message[0]->PERSONAL_JABATAN != 0 )?$sales->message[0]->PERSONAL_JABATAN:'';
                                 
                                } else {
                                    $target = 1;
                                }
                                $pencapaian            = ($list->totaldata>0)? round( ($list->totaldata/$target)*100,2): 0;  
                                $dasar_pengali         = ($pencapaian >=100)? 100 : 50;
                                $jumlah_insentif       = ($insentif_dasar*$dasar_pengali)/100;  
                                $tambahan              = ($pencapaian >= 150)? 30000*$list->totaldata : 0;
                               
                                $insentif_admin_persen = ($personal_jabatan = 'Salesman' || $personal_jabatan = 'Kepala Sales' || $personal_jabatan = 'Kepala Counter')? 5 : 10;
                                $insentif_admin        = $insentif_admin_persen/100 * ($jumlah_insentif + $tambahan);
                                $penalty               = 0;
                                $total_insentif        = (($jumlah_insentif + $tambahan) - $insentif_admin)-$penalty;
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
    <h4 class="modal-title" id="myModalLabel">Laporan Insentif Sales Counter</h4>
</div>

<div class="modal-body" id="printarea">

    <table  border="0" id="desc" class="">
        <tr>            
            <td  align="center" colspan="11"><h2><strong>Laporan Insentif Salesman</strong></h2></td>
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
            <td>Nama Salesman</td>
            <td>:</td>
            <td id="sales"><?php echo ($sales->totaldata == 1)? $sales->message[0]->NAMA_SALES.'  - '.$jenisSales:"";  ?> </td>
            <td colspan="8">&nbsp;</td>
            <input type="hidden" name="kd_sales" id="kd_sales" value="<?php echo $defaulD_salesman; ?>" title="getNamaSalesman" >
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
                        <th  style="text-align: center !important">Tgl.Faktur</th>
                        <th  style="text-align: center !important">No.Faktur</th>
                        <th  style="text-align: center !important">Nama Konsumen</th>
                        <th  style="text-align: center !important">Nama Tipe</th>
                        <th  style="text-align: center !important">Kode</th>
                        <th  style="text-align: center !important">Via</th>
                        <th  style="text-align: center !important">DP /OTR</th>
                        <th  style="text-align: center !important">Sub. Dealer + Disc</th>
                        <th  style="text-align: center !important">Program</th>
                        <th  style="text-align: center !important">Insentif</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $this->input->get('page'); 
                    if ($list):
                        if (is_array($list->message) || is_object($list->message)):
                            foreach ($list->message as $key => $row){
                               
                                $no ++;
                                ?>

                                <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                    <td class='text-center' width="10"><?php  echo $no;?></td>
                                    <td class='text-center'><?php  echo tglFromSql($row->TGL_SPK);?></td>
                                    <td class='text-center'><?php  echo $row->FAKTUR_PENJUALAN;?></td>
                                    <td class='text-center'><?php  echo $row->NAMA_CUSTOMER;?></td>
                                    <td class='text-center'><?php  echo $row->NAMA_PASAR;?></td>
                                    <td class='text-center'><?php  echo $row->NAMA_TYPEMOTOR;?></td>
                                    <td class='text-center'><?php  echo $row->PENJUALAN_VIA;?></td>
                                    <td class='text-right '><?php echo ($row->TYPE_PENJUALAN =='CREDIT' )? number_format($row->UANG_MUKA,0): number_format($row->HARGA_OTR,0);?></td>
                                    <td class='text-right '><?php echo ($row->TYPE_PENJUALAN =='CREDIT' )? number_format($row->SUB_DLR_K + $row->DISKON,0): number_format($row->SUB_DLR_C + $row->DISKON,0);?></td>
                                    <td class='text-center'><?php echo (($row->SUB_DLR_K + $row->DISKON) > 150000 || ($row->SUB_DLR_C + $row->DISKON )> 150000 )? 'KHUSUS -K' : 'REGULER -K';?></td>
                                    <td class='text-right '><?php  echo number_format($row->INSENTIF,0);?></td>
                                </tr>

                                <?php
                                 
                            }

                            ?>
                          
                             <tfoot>
                            <tr class='total'>
                                <td colspan="2" class='tetable-nowarp' style="padding-right: 10px">Target</td>
                                <td class="text-right table-nowarp"><?php echo $target; ?></td>
                                <td colspan="5">&nbsp;</td>
                                <td colspan="2">Insentif Dasar</td>
                                <td class='text-right table-nowarp'><?php echo number_format($insentif_dasar,0);?> </td>
                            </tr>
                            <tr>
                                <td colspan="2" class='tetable-nowarp text-right' style="padding-right: 10px">Penjualan</td>
                                <td class="text-right table-nowarp"><?php echo ($list->totaldata>0)? $list->totaldata: 0; ?></td>
                                <td colspan="5">&nbsp;</td>
                                <td colspan="2">Pencapaian</td>
                                <td class="text-right"><?php echo $pencapaian; ?> %</td>
                            </tr>

                            <tr >
                                <td colspan="8">&nbsp;</td>                               
                                <td colspan="2">Pengali Insentif Dasar</td>
                                <td class="text-right"><?php echo $dasar_pengali; ?> %</td>
                            </tr>
                            <tr class='total'>
                                <td colspan="8">&nbsp;</td>                               
                                <td colspan="2">Insentif</td>
                                <td class="text-right"><?php echo number_format($jumlah_insentif,0); ?></td>
                            </tr>
                            <tr >
                                <td colspan="8">&nbsp;</td>                               
                                <td colspan="2">Tambahan </td>
                                <td class="text-right"><?php echo number_format($tambahan,0); ?></td>
                            </tr>
                            <tr >
                                <td colspan="8">&nbsp;</td>                               
                                <td colspan="2">Insentif Admin</td>
                                <td class="text-right"><?php echo number_format($insentif_admin,0); ?></td>
                            </tr> 
                            <tr >
                                <td colspan="8">&nbsp;</td>                               
                                <td colspan="2">Penalti</td>
                                <td class="text-right"><?php echo number_format($penalty,0); ?></td>
                            </tr> 
                            <tr class='total'>
                                <td colspan="8">&nbsp;</td>                               
                                <td colspan="2">Total Insentif</td>
                                <td class="text-right"><?php echo number_format($total_insentif,0); ?></td>
                            </tr>
                        </tfoot>
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