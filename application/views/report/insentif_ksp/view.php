<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
  
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$dari_tanggal=($this->input->get("tgl"))?$this->input->get("tgl"):date("d/m/Y",strtotime('-1 days'));
$sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y");



?>
 
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        <div class="bar-nav pull-right">
            <a  class="btn btn-default" href="<?php echo base_url('laporan_insentif/add_insentif_ksp?'.$_SERVER["QUERY_STRING"]); ?>"><i class="fa fa-file-o fa-fw"></i> Tambah</a>
            <!-- <a class="btn btn-default" id="modal-button" onclick='addForm("<?php echo base_url("Laporan_insentif/insentif_ksp_print");?>?<?php echo $_SERVER["QUERY_STRING"];?>")' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i> Print</a> -->
        </div>

    </div>

    <div class="col-lg-12 padding-left-right-5">
        <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-list-ul fa-fw"></i> Lap. Insentif KSP 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
        
            <div class="panel-body panel-body-border panel-body-10" style="display: block;">
                <form id="filterForms" method="GET" action="<?php echo base_url("laporan_insentif/insentif_ksp");?>">
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
                      
                       
                        <div class="col-xs-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Find by</label>
                                <input class="form-control" type="text" id="keyword" name="keyword" placeholder="cari berdasarkan NIK atau Nama">
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
                                <th  style="text-align: center !important">Aksi</th>
                                <th  style="text-align: center !important">NIK</th>
                                <th  style="text-align: center !important">Nama</th>
                                <th  style="text-align: center !important">Periode Awal</th>
                                <th  style="text-align: center !important">Periode Akhir</th>
                                <th  style="text-align: center !important">Total Insentif</th>
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
                                                <tr id="<?php echo  $this->session->flashdata('tr-active') == $value->ID ? 'tr-active' : ' '; ?>" >
                                                    <td class='text-center table-nowarp'><?php echo $n;?></td>
                                                    <td>
                                                        <a class="active" id="modal-button" onclick='addForm("<?php echo base_url("Laporan_insentif/insentif_ksp_print");?>?id=<?php echo $value->ID;?>")' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print">Print</i></a>
                                                        
                                                        <a href="<?php echo base_url("Laporan_insentif/penalty_ksp");?>?kd_dealer=<?php echo $defaultDealer;?>&tgl_awal=<?php echo $value->PERIODE_AWAL;?>&tgl_akhir=<?php echo $value->PERIODE_AKHIR;?>" class="active">
                                                            <i class='fa fa-print' data-toggle="tooltip" data-placement="left" title="cetak penalty" >Penalty</i>
                                                        </a>
                                                        <a href="<?php echo base_url("Laporan_insentif/list_exclude_ksp");?>?kd_dealer=<?php echo $defaultDealer;?>&tgl_awal=<?php echo$value->PERIODE_AWAL;?>&tgl_akhir=<?php echo $value->PERIODE_AKHIR;?>" class="active">
                                                            <i class='fa fa-search' data-toggle="tooltip" data-placement="left" title="insput exclude penalty" >Exclude</i>
                                                        </a> </td>
                                                    <td class='text-center table-nowarp'><?php echo $value->NIK;?></td>
                                                    <td class='text-center table-nowarp'><?php //echo $value->NIK;?></td>
                                                    <td class='text-center table-nowarp'><?php echo tglFromSql($value->PERIODE_AWAL);?></td>
                                                    <td class='text-center table-nowarp'><?php echo tglFromSql($value->PERIODE_AKHIR);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($value->INSENTIF_TERIMA,0);?></td>                                                    
                                                                                                       
                                                   
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
                loadData('kd_ksp', $('#kd_dealer').val(), '0')
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