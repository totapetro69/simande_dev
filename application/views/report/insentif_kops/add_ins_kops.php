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
<form id="addForm" method="post" action="<?php echo base_url("laporan_insentif/add_insentif_kops_simpan");?>">  
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        <div class="bar-nav pull-right">
            <button id="simpan_btn"  class="btn btn-default" role="button">
                <i class="fa fa-save fa-fw"></i> Simpan
            </button>
          <!--   <a class="btn btn-default" id="modal-button" onclick='addForm("<?php //echo base_url("Laporan_insentif/rekap_insentif_print");?>?<?php //echo $_SERVER["QUERY_STRING"];?>")' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i> Print</a> -->
        </div>

    </div>

    <div class="col-lg-12 padding-left-right-5">
        <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-list-ul fa-fw"></i> Rekap Insentif K.Ops 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
        
            <div class="panel-body panel-body-border panel-body-10" style="display: block;">
                <form id="filterForms" method="GET" action="<?php echo base_url("laporan_insentif/add_insentif_kops");?>">
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
                        
                        <!-- <div class="col-xs-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Find by</label>
                                <input class="form-control" type="text" id="keyword" name="keyword" placeholder="cari berdasarkan No Trans atau Nama Salesman">
                            </div>
                        </div> -->
                        <div class="col-xs-3 col-md-3 col-sm-3">
                            <div class="form-group">
                                <br>
                                <button id="filter_btn" class="btn btn-info"><i class="fa fa-search"></i> Preview</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-5">
        <div class="panel margin-bottom-5">
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
                                  
                                 <!-- <th  style="text-align: center !important">No</th>         -->                       
                                <!--  <th  style="text-align: center !important">Periode</th> -->
                                 <th  style="text-align: center !important">P1</th>
                                 <th  style="text-align: center !important">P1 Tot</th>
                                 <th  style="text-align: center !important">P2</th>
                                 <th  style="text-align: center !important">P2 Tot</th>
                                 <th  style="text-align: center !important">P3</th>
                                 <th  style="text-align: center !important">P3 Tot</th>
                                 <th  style="text-align: center !important">P4</th>
                                 <th  style="text-align: center !important">P4 Tot</th>
                                 <th  style="text-align: center !important">P5</th>
                                 <th  style="text-align: center !important">P6</th>
                                 <th  style="text-align: center !important">P7</th>
                                 <th  style="text-align: center !important">P8</th>
                                 <th  style="text-align: center !important">P9</th>
                                 <th  style="text-align: center !important">P10</th>
                                 <th  style="text-align: center !important">P11</th>
                                 <th  style="text-align: center !important">Jual</th>                
                                               
                            </tr>
                           
                        </thead>
                        <tbody>
                             
                            <?php
                                $n=0;$part=array();
                            

                                if(isset($list)){
                                    if($list->totaldata >0){
                                        
                                         
                                        foreach ($list->message as $key => $value) {
                                            $n++;
                                            //$part=explode("-",$value->URAIAN_TRANSAKSI,2);
                                            //$insentif = ($value->TYPE_PENJUALAN =='CREDIT' )? number_format($value->KREDIT,0) :number_format($value->CASH,0);
                                            
                                            ?>
                                            
                                                <tr>
                                                    
                                                   <!--  <td class='text-center table-nowarp'><?php echo $n;?></td> -->
                                                  
                                                    <?php 
                                                    
                                                    ?>
                                                    
                                                        <input type="hidden" class="form-control" id="kd_dealer" value="<?php echo $value->KD_DEALER;?>" name="kd_dealer">
                                                        <input type="hidden" class="form-control" id="kd_maindealer" value="<?php echo $value->KD_MAINDEALER;?>" name="kd_maindealer">
                                                        <input type="hidden" class="form-control" id="tgl_awal" value="<?php echo $this->input->get('tgl_awal');?>" name="tgl_awal">
                                                        <input type="hidden" class="form-control" id="tgl_akhir" value="<?php echo $this->input->get('tgl_akhir');?>" name="tgl_akhir">                                                       
                                                    
                                                     <?php 
                                                       if ($this->input->get('tgl_awal')) {
                                                           $dte  = $this->input->get('tgl_awal');
                                                           $dt   = new DateTime();
                                                           $date = $dt->createFromFormat('d/m/Y', $dte);
                                                        //echo $date->format('Y-m');
                                                       }
                                                        

                                                     ?>
                                                                     
                                                 
                                                 
                                                    <!-- <input type="text" name="periode" value="<?php echo ($this->input->get('tgl_awal'))? $date->format('Y-m'):"-";?>"> -->
                                                    <input type="hidden" name="periode" value="<?php echo ($this->input->get('tgl_awal'));?>">
                                                   <!--  <td class='table-nowarp' id="periode"><?php echo ($this->input->get('tgl_awal'))? $date->format('Y-m'):"-";?></td> -->
                                                    <td class='table-nowarp' id="p1"><input type="text" class="form-control" id="p1" name="p1"></td>
                                                    <td class='table-nowarp' id="p1_total"><input type="text" class="form-control" id="p1_total" name="p1_total[]"></td>
                                                    <td class='table-nowarp' id="p2"><input type="text" class="form-control" id="p2" name="p2[]"></td>
                                                    <td class='table-nowarp' id="p2_total"><input type="text" class="form-control" id="p2_total" name="p2_total[]"></td>
                                                    <td class='table-nowarp' id="p3"><input type="text" class="form-control" id="p3" name="p3[]"></td>
                                                    <td class='table-nowarp' id="p3_total"><input type="text" class="form-control" id="p3_total" name="p3_total[]"></td>
                                                    <td class='table-nowarp' id="p4"><input type="text" class="form-control" id="p4" name="p4[]"></td>
                                                    <td class='table-nowarp' id="p4_total"><input type="text" class="form-control" id="p4_total" name="p4_total[]"></td>
                                                    <td class='table-nowarp' id="p5"><input type="text" class="form-control" id="p5" name="p5[]"></td>
                                                    <td class='table-nowarp' id="p6"><input type="text" class="form-control" id="p6" name="p6[]"></td>
                                                    <td class='table-nowarp' id="p7"><input type="text" class="form-control" id="p7" name="p7[]"></td>
                                                    <td class='table-nowarp' id="p8"><input type="text" class="form-control" id="p8" name="p8[]"></td>
                                                    <td class='table-nowarp' id="p9"><input type="text" class="form-control" id="p9" name="p9[]"></td>
                                                    <td class='table-nowarp' id="p10"><input type="text" class="form-control" id="p10" name="p10[]"></td>   
                                                    <td class='table-nowarp' id="p11"><input type="text" class="form-control" id="p11" name="p11[]"></td>  
                                                    <td class='table-nowarp' id="penjualan"><?php echo $value->JUAL;?></td>
                                                    <input type="hidden" value="<?php echo $value->JUAL;?>" name="total_sales[]">
                                                 </form>
                                                   <!--   <td class='text-right table-nowarp'>  
                                                       <button id="submit-btn" onclick="addData();"  class="btn btn-default"><i class="fa fa-save fa-fw"></i></button>
                                                     </td> -->
                                                </tr>
                                             
                                                                                               
                                                
                               
                                             <?php
                                        
                                          
                                        }?>
                                    
                                        <?php
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
     $(document).ready(function () {            
            $('#filter_btn').on('click', function () {
                $('#filterForms').submit()
            })
            $('#simpan_btn').on('click', function () {
                $('#addForm').submit()
            })
        })
</script>

<script type="text/javascript">

    function set_ins_unit(){
        var rpk = $('#rpk').val();
        var mu = $('#margin_unit').val();
        var ts = $('#tot_sales').html();

        var ins_unit =rpk*mu/100;
        $('#insentif_unit').val(ins_unit);
        $('#total_insentif').val(ins_unit*ts);

    }
    function set_ins_diterima(){
        var insentif = $('#total_insentif').val();
        var penalty = $('#penalty').val();
        var pph21 = $('#pph21').val();

        var ins_diterima =parseInt(insentif)-parseInt(penalty)-parseInt(pph21);
        $('#insentif_terima').val(ins_diterima);
       

    }
        $(document).ready(function () {            
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
        }

 

   /* $('#simpan_ins_ksp').click(function() {
        __simpan_insentif_ksp();
    })


    function __simpan_insentif_ksp() {
   
    
    $('#loadpage').removeClass("hidden");
    $('.header_frm').removeAttr('disabled');
    var urls = $('#addForm').attr('action');
    
    var datax = $('#addForm').serialize();
   
    $.ajax({
        type: 'POST',
        url: urls,
        data: datax,
        success: function(result) {
            console.log(result);
            //PKK7D201802-00002:1031:1
            var d = result.split(":");
            $('#spk_no').val(d[0]);
            if(parseInt(d[2])==1){
                 $('.success').animate({ top: "0"}, 500);
                    $('.success').html('Data berhasil di simpan :'+ result).fadeIn();
                    setTimeout(function() {
                     document.location.href = "?tab=2&id=" + d[1];
                    }, 500);
            }
            //$('#loadpage').addClass("hidden");
        }
    })
}*/


function addData1() {
    var defaultBtn = $("#submit-btn").html();

    $("#submit-btn").addClass("disabled");
    $("#submit-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();
    $('#addForm :input').removeAttr("disabled");

    var formData = $("#addForm").serialize();
    var act = $("#addForm").attr('action');

    $.ajax({
        url: act,
        type: 'POST',
        data: formData,
        dataType: "json",
        success: function(result) {
            if (result.status == true) {
                alert(result);
                $('.success').html(result.message);
                $('.success').animate({ top: "0" }, 500);


                if (result.location != null) {
                    setTimeout(function() {
                        location.replace(result.location)
                        //location.replace(<?php echo base_url('Laporan_insentif/insentif_ksp'); ?>)
                    }, 1000);
                } else {
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            } else {

                $('.error').animate({top: "0"}, 500);
                $('.error').html(result.message);

                setTimeout(function() {
                    hideAllMessages();
                    $("#submit-btn").removeClass("disabled");
                    $("#submit-btn").html(defaultBtn);
                    return;
                }, 2000);
            }
        }

    });

    return false;
}
    </script>