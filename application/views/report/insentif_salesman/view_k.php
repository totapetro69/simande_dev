<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
  
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$dari_tanggal=($this->input->get("tgl"))?$this->input->get("tgl"):date("d/m/Y",strtotime('-1 days'));
$sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y");

             
                           $insentif_dasar=0;
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
                            $t_penalty_u = 0;
                            $t_penalty_ar = 0;
                            
                        if(isset($list)){    
                            if ($list->totaldata>0) {
                                 foreach ($list->message as $key => $row) {
                                                                      
                                    //$insentif  = ($value->TYPE_PENJUALAN =='CREDIT' )? $value->KREDIT:$value->CASH;
                                    $insentif  = $row->INSENTIF;
                                    $insentif_dasar += $insentif;

                                        if ($this->input->get("tgl_akhir")){
                                      $tgl_akr = $this->input->get("tgl_akhir");
                                    }else{
                                        $tgl_akr = date('d/m/y');
                                    }

                                    $tgl_akhir = DateTime::createFromFormat('d/m/Y', $tgl_akr);
                                    $tgl_akhir1 = $tgl_akhir->format('Y-m-d');
                                    $tgl_akhir2 =strtotime($tgl_akhir1);

                                    $tgl_sj =strtotime($row->TGL_SJMASUK);
                                    $hari = ($tgl_akhir2 -$tgl_sj) / (60*60*24);
                                    if ($hari >= 91 && $hari <= 120) {
                                        $penalty_u = 20000;
                                    }else if ($hari > 120){
                                        $penalty_u = 40000;
                                    }else{
                                        $penalty_u = 0;
                                    }


                                    $TGL_SPK1 = DateTime::createFromFormat('d/m/Y', tglFromSql($row->TGL_SPK));
                                    $TGL_SPK = $TGL_SPK1->format('Y-m-d');

                                    $selisih =  (strtotime($TGL_SPK) - strtotime($row->JATUH_TEMPO)) /(60*60*24); 
                                    if ($row->JATUH_TEMPO) {
                                        if ($selisih > 10) {
                                            $penalty_ar = 10000;
                                        }else{
                                            $penalty_ar= 0;
                                        }
                                    }else{
                                        $penalty_ar = 0;
                                    }


                                     $t_penalty_u = $t_penalty_u + $penalty_u; 
                                     $t_penalty_ar = $t_penalty_ar + $penalty_ar;
                                }
                                $penjualan             = $list->totaldata;  
                                if ($sales) {
                                   $target = ($sales->message[0]->TARGET != NULL || $sales->message[0]->TARGET != 0 )?$sales->message[0]->TARGET:10;
                                   $personal_jabatan = ($sales->message[0]->PERSONAL_JABATAN != NULL || $sales->message[0]->PERSONAL_JABATAN != 0 )?$sales->message[0]->PERSONAL_JABATAN:'';
                                 
                                } else {
                                    $target = 10;
                                }
                                $pencapaian            = ($list->totaldata>0)? round( ($list->totaldata/$target)*100,2): 0;  
                                $dasar_pengali         = ($pencapaian >=100)? 100 : 50;
                                $jumlah_insentif       = ($insentif_dasar*$dasar_pengali)/100;  
                                $tambahan              = ($pencapaian >= 150)? 30000*$list->totaldata : 0;
                               
                                $insentif_admin_persen = ($personal_jabatan = 'Salesman' || $personal_jabatan = 'Kepala Sales' || $personal_jabatan = 'Kepala Counter')? 5 : 10;
                                $insentif_admin        = $insentif_admin_persen/100 * ($jumlah_insentif + $tambahan);
                               $penalty               = ($t_penalty_ar)/10;
                                $total_insentif        = (($jumlah_insentif + $tambahan) - $insentif_admin)-$penalty;
                            }
                       
                        }

                            
                            ?>
 
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        <div class="bar-nav pull-right">
            <a class="btn btn-default" id="modal-button" onclick='addForm("<?php echo base_url("Laporan_insentif/insentif_k_salesman_print");?>?<?php echo $_SERVER["QUERY_STRING"];?>")' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i> Print</a>
        </div>

    </div>

    <div class="col-lg-12 padding-left-right-5">
        <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-list-ul fa-fw"></i> Lap. Insentif Kepala Salesman 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
        
            <div class="panel-body panel-body-border panel-body-10" style="display: block;">
                <form id="filterForms" method="GET" action="<?php echo base_url("laporan_insentif/insentif_k_salesman");?>">
                    <div class="row">
                        <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select class="form-control" id="kd_dealer" name="kd_dealer">
                                    <option value="">--Pilih Dealer--</option>
                                    <?php
                                    if (isset($dealer)) {
                                        if ($dealer->totaldata>0) {
                                            foreach ($dealer->message as $key => $row) {
                                                $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                $aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
                                                echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                            }
                                        }
                                    }
                                    ?> 
                                </select>
                            </div>
                        </div>
                         <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                               <label>Nama Kepala Salesman <span id="l_salesman"></span></label>
                                <select class="form-control" id="kd_salesman" name="nik_salesman" title="getKSalesman" required="true">
                                    <?php echo ($sales->totaldata >= 1)? "<option value='".$sales->message[0]->NIK."'>".$sales->message[0]->NAMA_SALES."</option>" : "<option value='0'>--Pilih Salesman--</option>";?>
                                    
                                   
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Awal</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                     
                        <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Pengajuan</label>
                                <div class="input-group input-append date" id="date">
                                    <input class="form-control" id="tgl_pengajuan" name="tgl_pengajuan" value="<?php echo $dari_tanggal;?>">
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Find by</label>
                                <input class="form-control" type="text" id="keyword" name="keyword" placeholder="cari berdasarkan No Trans atau Nama Salesman">
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class="form-group">
                                <br>
                                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Preview</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-5">
        <div class="printarea">
            <div id="head" class='hidden'>
                <table class="table">
                    <tbody>
                        <?php echo (isset($judul))?$judul:"";?>
                    </tbody>
                </table>
            </div>
            <div class="panel panel-default">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
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

                                $n=0;$part=array();
                                if(isset($list)){
                                    if($list->totaldata >0){
                                        
                                      
                                        foreach ($list->message as $key => $value) {
                                            $n++;

                                  
                                            ?>
                                                <tr>
                                                    <td class='text-center table-nowarp'><?php echo $n;?></td>
                                                    <td class='text-center table-nowarp'><?php echo tglFromSql($value->TGL_SPK);?></td>
                                                    <td class='table-nowarp'><?php echo $value->FAKTUR_PENJUALAN;?></td>
                                                    <td class='table-nowarp'><?php echo $value->NAMA_CUSTOMER;?></td>
                                                    <td class='table-nowarp'><?php echo $value->NAMA_PASAR;?></td>
                                                    <td class='table-nowarp'><?php echo $value->NAMA_TYPEMOTOR;?></td>
                                                    <td class='table-nowarp'><?php echo $value->PENJUALAN_VIA;?></td>
                                                    <td class='text-right table-nowarp'><?php echo ($value->TYPE_PENJUALAN =='CREDIT' )? number_format($value->UANG_MUKA,0): number_format($value->HARGA_OTR,0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo ($value->TYPE_PENJUALAN =='CREDIT' )? number_format($value->SUB_DLR_K + $value->DISKON,0): number_format($value->SUB_DLR_C + $value->DISKON,0);?></td>
                                                    <td class='table-nowarp'><?php echo $program = (($value->SUB_DLR_K + $value->DISKON) > 150000 || ($value->SUB_DLR_C + $value->DISKON )> 150000 )? 'KHUSUS -K' : 'REGULER -K';?></td>   
                                                  
                                                  
                                                   
                                                   <td class='text-right table-nowarp'><?php echo number_format($value->INSENTIF,0);?></td>                                                    
                                                   
                                                </tr>
                                            <?php
                                            
                                            //$insentif_dasar += $insentif;

                                          
                                        }
                                        
                                    }else{
                                        echo belumAdaData(12);
                                    }
                                }else{
                                    echo belumAdaData(12);
                                }
                            ?>
                        </tbody>
                        <tfoot>
                          
                        
                            <tr class='total'>
                                <td colspan="2" class='tetable-nowarp' style="padding-right: 10px">Target</td>
                                <td class="text-right table-nowarp"><?php echo $target; ?></td>
                                <td colspan="5">&nbsp;</td>
                                <td colspan="2"colspan="2">Insentif Dasar</td>
                                <td class='text-right table-nowarp'><?php echo number_format($insentif_dasar,0);?> </td>
                            </tr>
                            <tr>
                                <td colspan="2" class='tetable-nowarp'xt-right' style="padding-right: 10px">Penjualan</td>
                                <td class="text-right table-nowarp"><?php echo $penjualan; ?></td>
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
                           <!--  <tr >
                                <td colspan="8">&nbsp;</td>                               
                                <td colspan="2">Penaltiu</td>
                                <td class="text-right"><?php echo number_format($t_penalty_u ,0); ?></td>
                            </tr>   <tr >
                                <td colspan="8">&nbsp;</td>                               
                                <td colspan="2">Penaltiar</td>
                                <td class="text-right"><?php echo number_format($t_penalty_ar,0); ?></td>
                            </tr>    -->
                            <tr >
                                <td colspan="8">&nbsp;</td>                               
                                <td colspan="2">Penalti</td>
                                <td class="text-right"><?php echo number_format($penalty ,0); ?></td>
                            </tr> 
                            <tr class='total'>
                                <td colspan="8">&nbsp;</td>                               
                                <td colspan="2">Total Insentif</td>
                                <td class="text-right"><?php echo number_format($total_insentif,0); ?></td>
                            </tr>

                        </tfoot>
                    </table>
                    
                </div>
            </div>
        </div>

    </div>

</section>
<script type="text/javascript" src="<?php echo base_url('assets/dist/print.min.js');?>"></script>
<script type="text/javascript">
    function printKw() {
         printJS('printarea','html');
     }
</script>

<script type="text/javascript">
        $(document).ready(function () {


            /*pilihan propinsi*/
            $('#kd_dealer').on('click', function () {
                loadData('kd_salesman', $('#kd_dealer').val(), '0')
            })
            
         

        })

        function loadData(id, value, select) {

            var param = $('#' + id + '').attr('title');
            $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
            var urls = "<?php echo base_url(); ?>laporan_insentif/" + param;
            var datax = {"kd": value};
            $('#' + id + '').attr('disabled', 'disabled');

            $.ajax({
                type: 'GET',
                url: urls,
                data: datax,
                typeData: 'html',
                success: function (result) {
                    $('#' + id + '').empty();
                    $('#' + id + '').html(result);
                    $('#' + id + '').val(select).select();
                    $('#l_' + param + '').html('');
                    $('#' + id + '').removeAttr('disabled');
                }
            });
        }
    </script>