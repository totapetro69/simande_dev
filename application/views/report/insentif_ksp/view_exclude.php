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
            <a id="modal-button" class="btn btn-default" onclick='addForm("<?php echo base_url('laporan_insentif/exclude_ksp/?'.$_SERVER["QUERY_STRING"]); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-file-o fa-fw"></i> Tambah</a>
            <a class="btn btn-default" id="modal-button" onclick='addForm("<?php echo base_url("Laporan_insentif/exclude_ksp_print");?>?<?php echo $_SERVER["QUERY_STRING"];?>")' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i> Print</a>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-5">
        <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-list-ul fa-fw"></i> Exclude
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
        
            <div class="panel-body panel-body-border panel-body-10" style="display: block;">
                <form id="filterForms" method="GET" action="<?php echo base_url("laporan_insentif/insentif_ksp");?>">
                    <div class="row">                      
                         <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                               <label>NIK  <span id="l_salesman"></span></label>
                                <select class="form-control" id="kd_salesman" name="kd_salesman" title="getSalesman" required="true">
                                    <?php echo ($sales->totaldata >= 1)? "<option value='".$sales->message[0]->KD_SALES."'>".$sales->message[0]->NAMA_SALES."</option>" : "<option value='0'>--Pilih Salesman--</option>";?>
                                    
                                   
                                    
                                </select>
                            </div>
                        </div>
                         <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                               <label>Nama  <span id="l_salesman"></span></label>
                                <select class="form-control" id="kd_salesman" name="kd_salesman" title="getSalesman" required="true">
                                    <?php echo ($sales->totaldata >= 1)? "<option value='".$sales->message[0]->KD_SALES."'>".$sales->message[0]->NAMA_SALES."</option>" : "<option value='0'>--Pilih Salesman--</option>";?>
                                    
                                   
                                    
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
                      
                     <!--    <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class="form-group">
                                <br>
                                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Preview</button>
                            </div>
                        </div> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
   <div class="col-lg-12 padding-left-right-5">
        
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
                                <th  style="text-align: center !important">Tanggal</th>
                                <th  style="text-align: center !important">NIK</th>
                                <th  style="text-align: center !important">No. Mesin</th>
                                <th  style="text-align: center !important">Main Dealer</th>
                                <th  style="text-align: center !important">Dealer</th>
                              
                               
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
                                                    <td class='table-nowarp'><?php echo $n;?></td>
                                                    <td class='table-nowarp'><?php echo tglFromSql($value->TGL);?></td>
                                                    <td class='table-nowarp'><?php echo $value->NIK;?></td>
                                                    <td class='table-nowarp'><?php echo $value->NO_MESIN;?></td>
                                                    <td class='table-nowarp'><?php echo $value->KD_MAINDEALER;?></td>
                                                    <td class='table-nowarp'><?php echo $value->KD_DEALER;?></td>
                                                </tr>
                                            <?php
                                          
                                        }
                                        
                                    }else{
                                        echo belumAdaData(6);
                                    }
                                }else{
                                    echo belumAdaData(6);
                                }
                            ?>
                        </tbody>
                       
                    </table>
                    
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