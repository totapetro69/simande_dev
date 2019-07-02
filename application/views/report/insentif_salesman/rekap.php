<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
  
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$tgl_pengajuan=($this->input->get("tgl_pengajuan"))?$this->input->get("tgl_pengajuan"):date("d/m/Y",strtotime('days'));
$dari_tanggal=($this->input->get("tgl"))?$this->input->get("tgl"):date("d/m/Y",strtotime('-1 days'));
$sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y");
//$tahun=($this->input->get("tahun"))?$this->input->get("tahun"):date("Y");
/*if ($this->input->get("tahun")) {
    $tahun = $this->input->get("tahun");
}else{
    for ($i=2010; $i <= date("Y"); $i++) { 
       $tahun = $i;
    }
}*/


?>
  
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        <div class="bar-nav pull-right">
            <a class="btn btn-default" id="modal-button" onclick='addForm("<?php echo base_url("Laporan_insentif/rekap_insentif_print");?>?<?php echo $_SERVER["QUERY_STRING"];?>")' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i> Print</a>
        </div>

    </div>

    <div class="col-lg-12 padding-left-right-5">
        <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-list-ul fa-fw"></i> Rekap Insentif Salesman 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
        
            <div class="panel-body panel-body-border panel-body-10" style="display: block;">
                <form id="filterForms" method="GET" action="<?php echo base_url("laporan_insentif/rekap_insentif");?>">
                    <div class="row">
                        <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select class="form-control" id="kd_dealer" name="kd_dealer">
                                    <option value="">--Pilih Dealer--</option>
                                    <?php
                                        if (isset($dealer)) {
                                            if ($dealer->totaldata>0) {
                                                foreach ($dealer->message as $key => $value) {
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
                        <!--  <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                               <label>Jabatan <span id="l_salesman"></span></label>
                                <select class="form-control"   title="getSalesman"  name="jabatan">
                                   <option value="">--Pilih Jabatan Sales--</option>
                                 
                                   <option value="Sales"  <?php echo ($this->input->get("jabatan") =='Sales')?"selected":"";?> >Salesman</option>
                                   <option value="Sales Counter" <?php echo ($this->input->get("jabatan") =='Sales Counter')?"selected":"";?> >Sales Counter</option>
                                   
                                   
                                    <?php
                                        if (isset($sales)) {
                                            if ($sales->totaldata>0) {
                                                foreach ($sales->message as $key => $value) {
                                                    //$aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                    $aktif = ($this->input->get("kd_jabatan") == $value->KD_JABATAN) ? "selected" : "";
                                                    echo "<option value='" . $value->KD_JABATAN . "' " . $aktif . ">" . $value->KD_JABATAN . "</option>";
                                                }
                                            }
                                        }
                                    ?> 
                                    
                                   
                                    
                                </select>
                            </div>
                        </div> -->
                       <!--  <div class="col-xs-3 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Periode Bulan</label>
                                <select id="bulan" name="bulan" class="form-control">
                                    <option value="">--Pilih Bulan</option>
                                    <?php 
                                        for($i=1;$i<=12; $i++){
                                            $pilih=(date("m")==$i)?"selected":"";
                                            $pilih=((int)$this->input->get("bulan")==$i)?"selected":"";
                                            echo "<option value='".$i."' ".$pilih.">".nBulan($i)."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-2 col-sm-2">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select id="tahun" name="tahun" class="form-control">
                                    <option value="">--Pilih Tahun</option>
                                    <?php 
                                        for ($i=2010; $i <= date("Y"); $i++) { 
                                            $pilih=(date("Y")==$i)?"selected":"";
                                            $pilih=((int)$this->input->get("tahun")==$i)?"selected":"";
                                            echo "<option value='".$i."' ".$pilih.">".$i."</option>";
                                        
                                       
                                        }
                                    ?>
                                </select>
                            </div>
                        </div> -->

                        
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
                        
                        <div class="col-xs-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Find by</label>
                                <input class="form-control" type="text" id="keyword" name="keyword" placeholder="cari berdasarkan No Trans atau Nama Salesman">
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3 col-sm-3">
                            <div class="form-group">
                                <br>
                                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Preview</button>
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
                                <th  style="text-align: center !important">Aksi</th>
                                <th  style="text-align: center !important">Kateg</th>
                                <th  style="text-align: center !important">Nama Salesman</th>
                                <th  style="text-align: center !important">Koord Salesman</th>
                                <th  style="text-align: center !important">Target</th>
                                <th  style="text-align: center !important">Jual</th>
                                <th  style="text-align: center !important">Total Jual</th>
                                <th  style="text-align: center !important">capai (%)</th>
                                <th  style="text-align: center !important">Ins. Dasar</th>
                                <th  style="text-align: center !important">Pengali (%)</th>
                                <th  style="text-align: center !important">Insentif</th>
                                <th  style="text-align: center !important">Tambahan</th>
                                <th  style="text-align: center !important">Ins. Admin</th>
                                <th  style="text-align: center !important">Penalty</th>
                                <th  style="text-align: center !important">Total Insentif</th>                               
                            </tr>
                           
                        </thead>
                        <tbody>
                            <?php
                                $n=0;$part=array();
                                $t_ins_dasar      = 0;
                                $t_insentif       = 0;
                                $t_tambahan       = 0;
                                $t_ins_admin      = 0;
                                $t_penalty        = 0;
                                $t_total_insentif = 0;

                                if(isset($list)){
                                    if($list->totaldata >0){
                                        
                                         
                                        foreach ($list->message as $key => $value) {
                                            $n++;
                                         
                                            ?>
                                                <tr>
                                                    <td class='text-center table-nowarp'><?php echo $n;?></td>
                                                    <td>
                                                   
                                                        <a href="<?php echo base_url("Laporan_insentif/insentif_salesman");?>?kd_dealer=<?php echo $defaultDealer;?>&kd_salesman=<?php echo $value->KD_SALES;?>&tgl_awal=<?php echo $this->input->get('tgl_awal');?>&tgl_akhir=<?php echo $this->input->get('tgl_akhir');?>" class="active">
                                                            <i class='fa fa-print' data-toggle="tooltip" data-placement="left" title="follow up Service Reminder" >Insentif</i>
                                                        </a>
                                                     
                                                    </td>
                                                    <?php 
                                                    $kategori =($value->KD_JABATAN =='S. Re' || $value->KD_JABATAN=='SW')?'Reguler':''; 
                                                    $kategori =($value->KD_JABATAN =='S.Win')?'Wing':''; 
                                                    $kategori =($value->KD_JABATAN =='SWAT')?'SWAT':''; 
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
                                                    <td class='text-right table-nowarp'><?php echo number_format($ins_admin = ($value->KD_JABATAN = 'Salesman' || $value->KD_JABATAN = 'Kepala Sales' || $value->PERSONAL_JABATAN = 'Kepala Counter')? 5/100*($insentif+$tambahan) : 10/100*($insentif+$tambahan),0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($penalty = 0,0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($total_insentif = $insentif + $tambahan - $ins_admin-$penalty,0);?></td>
                                                    
                                                   
                                                </tr>
                                            <?php
                                            $t_ins_dasar      += $ins_dasar;
                                            $t_insentif       += $insentif;
                                            $t_tambahan       += $tambahan;
                                            $t_ins_admin      += $ins_admin;
                                            $t_penalty        += $penalty;
                                            $t_total_insentif += $total_insentif;
                                          
                                        }

                                    }else{
                                        echo belumAdaData(12);
                                    }
                                }else{
                                    echo belumAdaData(12);
                                }


                            $t_ins_dasar2      = $t_ins_dasar;
                            $t_insentif2       = $t_insentif;
                            $t_tambahan2       = $t_tambahan;
                            $t_ins_admin2      = $t_ins_admin;
                            $t_penalty2        = $t_penalty;
                            $t_total_insentif2 = $t_total_insentif;
                            
                            if(isset($list_k_sales)){
                                    if($list_k_sales->totaldata >0){
                                        
                                         
                                        foreach ($list_k_sales->message as $key => $value) {
                                            $n++;
                                            //$part=explode("-",$value->URAIAN_TRANSAKSI,2);
                                            //$insentif = ($value->TYPE_PENJUALAN =='CREDIT' )? number_format($value->KREDIT,0) :number_format($value->CASH,0);
                                            
                                            ?>
                                                <tr>
                                                    <td class='text-center table-nowarp'><?php echo $n;?></td>
                                                    <td>
                                                  
                                                            <a href="<?php echo base_url("Laporan_insentif/insentif_k_salesman");?>?kd_dealer=<?php echo $defaultDealer;?>&nik_salesman=<?php echo $value->ATASAN_LANGSUNG;?>&tgl_awal=<?php echo $this->input->get('tgl_awal');?>&tgl_akhir=<?php echo $this->input->get('tgl_akhir');?>" class="active">
                                                            <i class='fa fa-print' data-toggle="tooltip" data-placement="left" title="cetak insentif" >Insentif</i>
                                                        </a> 

                                                        <a href="<?php echo base_url("Laporan_insentif/rekap_penalty");?>?kd_dealer=<?php echo $defaultDealer;?>&nik_salesman=<?php echo $value->ATASAN_LANGSUNG;?>&tgl_awal=<?php echo $this->input->get('tgl_awal');?>&tgl_akhir=<?php echo $this->input->get('tgl_akhir');?>" class="active">
                                                            <i class='fa fa-print' data-toggle="tooltip" data-placement="left" title="cetak penalty" >Penalty</i>
                                                        </a>
                                                        <a href="<?php echo base_url("Laporan_insentif/list_exclude");?>?kd_main=<?php echo $value->KD_MAINDEALER ?>&kd_dealer=<?php echo $defaultDealer;?>&nik_salesman=<?php echo $value->ATASAN_LANGSUNG;?>&tgl_awal=<?php echo $this->input->get('tgl_awal');?>&tgl_akhir=<?php echo $this->input->get('tgl_akhir');?>" class="active">
                                                            <i class='fa fa-search' data-toggle="tooltip" data-placement="left" title="insput exclude penalty" >Exclude</i>
                                                        </a> 
                                                      <!--   <a id="modal-button" class="active" onclick='addForm("<?php echo base_url('laporan_insentif/exclude/'.$value->ATASAN_LANGSUNG.'/'.$defaultDealer.'/'.$this->input->get('tgl_awal').'/'.$this->input->get('tgl_akhir')); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                                                              <i class="fa fa-pencil">Exclude</i> 
                                                        </a> -->


                                                 
                                                    </td>
                                                    <?php 
                                                    $kategori2 =($value->KD_JABATAN =='S. Re' || $value->KD_JABATAN=='SW')?'Reguler':''; 
                                                    $kategori2 =($value->KD_JABATAN =='S.Win')?'Wing':''; 
                                                    $kategori2 =($value->KD_JABATAN =='SWAT')?'SWAT':''; 
                                                    $kategori2 =($value->PERSONAL_JABATAN =='Kepala Sales')?'Reguler':''; 
                                                    ?>
                                                    <td class='table-nowarp'><?php echo $kategori2;?></td>
                                                    <td class='table-nowarp'><?php echo $value->NAMA_ATASAN;?></td>
                                                    <td class='table-nowarp'><?php //echo $value->NAMA_ATASAN;?></td>
                                                    <td class='text-right table-nowarp'><?php echo $value->TARGET;?></td>
                                                    <td class='text-right table-nowarp'><?php echo $value->JUAL;?></td>
                                                    <td class='text-right table-nowarp'><?php echo $value->JUAL;?></td>
                                                    <td class='text-right table-nowarp'><?php echo $pencapaian2 = ($value->TARGET != NULL)?($value->JUAL/$value->TARGET)*100: 0 ;?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($ins_dasar2 = $value->INS_DASAR,0); ?></td>
                                                    <td class='text-right table-nowarp'><?php echo $pengali2 =  ($pencapaian2 < 100)?50:100;?></td>                                                    
                                                    <td class='text-right table-nowarp'><?php echo number_format($insentif2 = $pengali2/100*$value->INS_DASAR,0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($tambahan2 = ($pencapaian2>=150)?$value->JUAL*30000: 0,0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($ins_admin2 = ($value->KD_JABATAN = 'Salesman' || $value->KD_JABATAN = 'Kepala Sales' || $value->PERSONAL_JABATAN = 'Kepala Counter')? 5/100*($insentif2+$tambahan2) : 10/100*($insentif2+$tambahan2),0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($penalty2 = $value->PENALTY_AR/10,0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($total_insentif2 = $insentif2 + $tambahan2 - $ins_admin2-$penalty2,0);?></td>
                                                    
                                                   
                                                </tr>
                                            <?php
                                            $t_ins_dasar2      += $ins_dasar2;
                                            $t_insentif2       += $insentif2;
                                            $t_tambahan2       += $tambahan2;
                                            $t_ins_admin2      += $ins_admin2;
                                            $t_penalty2        += $penalty2;
                                            $t_total_insentif2 += $total_insentif2;
                                          
                                        }
                                        
                                    }else{
                                        
                                    }
                                }else{
                                   
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class='total'>
                                <td class="text-right" colspan="5">Total</td>
                                <td colspan="4"></td>
                                <td class="text-right"><?php echo number_format($t_ins_dasar2,0); ?></td>
                                <td class="text-right"></td>
                                <td class="text-right"><?php echo number_format($t_insentif2,0); ?></td>
                                <td class="text-right"><?php echo number_format($t_tambahan2,0); ?></td>
                                <td class="text-right"><?php echo number_format($t_ins_admin2,0); ?></td>
                                <td class="text-right"><?php echo number_format($t_penalty2,0); ?></td>
                                <td class="text-right"><?php echo number_format($t_total_insentif2,0); ?></td>
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
       /* $(document).ready(function () {


            
            $('#kd_dealer').on('click', function () {
                loadData('kd_jabatan', $('#kd_dealer').val(), '0')
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
        }*/
    </script>