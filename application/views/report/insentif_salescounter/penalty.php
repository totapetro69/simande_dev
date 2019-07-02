<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
  
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
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
            <a class="btn btn-default" id="modal-button" onclick='addForm("<?php echo base_url("Laporan_insentif/rekap_penalty_counter_print");?>?<?php echo $_SERVER["QUERY_STRING"];?>")' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i> Print</a>
        </div>

    </div>

    <div class="col-lg-12 padding-left-right-5">
        <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-list-ul fa-fw"></i> Lap. Penalty Kepala Counter 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
        
            <div class="panel-body panel-body-border panel-body-10" style="display: block;">
                <form id="filterForms" method="GET" action="<?php echo base_url("laporan_insentif/rekap_penalty");?>">
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
                         <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                               <label>Nama Kepala Counter <span id="l_salesman"></span></label>
                                <select class="form-control" id="kd_salesman" name="kd_salesman" title="getSalesman" required="true">
                                    <?php echo ($sales->totaldata >= 1)? "<option value='".$sales->message[0]->NIK."'>".$sales->message[0]->NAMA_SALES."</option>" : "<option value='0'></option>";?>
                                    
                                   
                                    
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
                        <!--<div class="col-xs-3 col-md-3 col-sm-3">
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
                        </div>
 -->
                       <!--  <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Periode</label>
                                <div class="input-group input-append date" id="date">
                                    <input class="form-control" id="tgl" name="tgl" value="<?php echo $dari_tanggal;?>">
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>  -->
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

                                $n=0;$part=array();
                                if(isset($list)){
                                    if($list->totaldata >0){
                                        
                                        ?>
                                        <tr>
                                            <td colspan="11"><strong>Penalty AR</strong></td>
                                        </tr>

                                        <?php

                                        foreach ($list->message as $key => $value) {
                                            $n++;

                                            ?>
                                                <tr>
                                                   
                                                    <td class='text-center table-nowarp'></td>
                                                    <td class='table-nowarp'><?php echo $value->JUAL;?></td>
                                                    <td class='table-nowarp'><?php echo $value->PENALTY_AR;?></td>

                                                    <td class='table-nowarp'>0</td>
                                                    <td class='table-nowarp'>0</td>
                                                    <td class='table-nowarp'></td>

                                                    <td class='table-nowarp'>0</td>
                                                    <td class='table-nowarp'>0</td>
                                                    <td class='table-nowarp'></td>

                                                    <td class='table-nowarp'><?php echo $value->PENALTY_AR; ?></td>
                                                    <td class='table-nowarp'><?php echo $value->PENALTY_AR/10;?></td>



                                                 
                                                   
                                                </tr>
                                            <?php
                                            
                                            //$insentif_dasar += $insentif;

                                          
                                        }?>
                                        <tr>
                                            <td colspan="11"><strong>Penalty Unit</strong></td>
                                        </tr>
                                        <?php
                                         foreach ($list->message as $key => $value) {
                                            $n++;

                                            ?>
                                                <tr>
                                                   
                                                    <td class='text-center table-nowarp'></td>
                                                    <td class='table-nowarp'>0</td>
                                                    <td class='table-nowarp'>0</td>

                                                    <td class='table-nowarp'>0</td>
                                                    <td class='table-nowarp'><?php echo $value->PENALTY_UNIT;?></td>
                                                    <td class='table-nowarp'></td>

                                                    <td class='table-nowarp'>0</td>
                                                    <td class='table-nowarp'>0</td>
                                                    <td class='table-nowarp'>0</td>

                                                    <td class='table-nowarp'><?php echo $value->PENALTY_UNIT; ?></td>
                                                    <td class='table-nowarp'><?php echo $value->PENALTY_UNIT/10;?></td>



                                                 
                                                   
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