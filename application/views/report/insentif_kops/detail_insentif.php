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
            <a  class="btn btn-default" href="<?php echo base_url('laporan_insentif/add_insentif_kops?'.$_SERVER["QUERY_STRING"]); ?>"><i class="fa fa-file-o fa-fw"></i> Tambah</a>
            <a class="btn btn-default" id="modal-button" onclick='addForm("<?php echo base_url("Laporan_insentif/rekap_insentif_print");?>?<?php echo $_SERVER["QUERY_STRING"];?>")' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i> Print</a>
        </div>

    </div>

    <div class="col-lg-12 padding-left-right-5">
        <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-list-ul fa-fw"></i> Detail Insentif K.Ops 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
        
            <div class="panel-body panel-body-border panel-body-10" style="display: block;">                
                    <div class="row">
                        <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>NIK</label>                                
                            </div>
                        </div>  
                        <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>NAMA</label>                                
                            </div>
                        </div>  
                        <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>PERIODE</label>                                
                            </div>
                        </div>                     
                    </div>
                
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
                                
                                 <th  style="text-align: center !important">No</th>
                                 <th  style="text-align: center !important">Aksi</th>                                
                                 <th  style="text-align: center !important">Periode</th>
                                 <th  style="text-align: center !important">P1</th>
                                 <th  style="text-align: center !important">P1 Tot</th>
                                 <th  style="text-align: center !important">P2</th>
                                 <th  style="text-align: center !important">P2 Tot</th>
                                 <th  style="text-align: center !important">P3</th>
                                 <th  style="text-align: center !important">P3 Tot</th>
                                 <th  style="text-align: center !important">P4</th>
                                 <th  style="text-align: center !important">P14 Tot</th>
                                 <th  style="text-align: center !important">P5</th>
                                 <th  style="text-align: center !important">P6</th>
                                 <th  style="text-align: center !important">P7</th>
                                 <th  style="text-align: center !important">P8</th>
                                 <th  style="text-align: center !important">P9</th>
                                 <th  style="text-align: center !important">P10</th>
                                 <th  style="text-align: center !important">P11</th>
                                 <th  style="text-align: center !important">Penjualan</th>
                                

                                           
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
                                            <form id="addForm" method="post" action="<?php echo base_url("laporan_insentif/add_insentif_kops_simpan");?>">
                                                <tr>
                                                    
                                                    <td class='text-center table-nowarp'><?php echo $n;?></td>
                                                    <td>
                                                      <a href="<?php echo base_url("Laporan_insentif/rekap_penalty");?>" class="active">
                                                            <i class="fa fa-edit text-success text" data-toggle="tooltip" data-placement="left" title="cetak penalty" ></i>
                                                            <a href="<?php echo base_url("Laporan_insentif/rekap_penalty");?>" class="active">
                                                            <i class='fa fa-trash' data-toggle="tooltip" data-placement="left" title="cetak penalty" ></i>

                                                    </td>
                                                   
                                                    <td class='table-nowarp' id="periode"><?php echo $value->PERIODE;?></td>
                                                    <td class='table-nowarp' id="p1"><?php echo $value->P1;?></td>
                                                    <td class='table-nowarp' id="p1_total"><?php echo $value->P1_TOTAL;?></td>
                                                    <td class='table-nowarp' id="p2"><?php echo $value->P2;?></td>
                                                    <td class='table-nowarp' id="p2_total"><?php echo $value->P2_TOTAL;?></td>
                                                    <td class='table-nowarp' id="p3"><?php echo $value->P3;?></td>
                                                    <td class='table-nowarp' id="p3_total"><?php echo $value->P3_TOTAL;?></td>
                                                    <td class='table-nowarp' id="p4"><?php echo $value->P4;?></td>
                                                    <td class='table-nowarp' id="p4_total"><?php echo $value->P4_TOTAL;?></td>
                                                    <td class='table-nowarp' id="p5"><?php echo $value->P5;?></td>
                                                    <td class='table-nowarp' id="p6"><?php echo $value->P6;?></td>
                                                    <td class='table-nowarp' id="p7"><?php echo $value->P7;?></td>
                                                    <td class='table-nowarp' id="p8"><?php echo $value->P8;?></td>
                                                    <td class='table-nowarp' id="p9"><?php echo $value->P9;?></td>
                                                    <td class='table-nowarp' id="p10"><?php echo $value->P10;?></td>   
                                                    <td class='table-nowarp' id="p11"><?php echo $value->P11;?></td>  
                                                    <td class='table-nowarp' id="penjualan"><?php echo $value->TOTAL_PENJUALAN;?></td>
                                                    
                                                     <input type="hidden" value="<?php echo $value->TOTAL_PENJUALAN;?>" name="total_sales">
                                               
                                                     
                                                </tr>
                                                                                               
                                                
                               
                                             <?php
                                        
                                          
                                        }?>
                                    
                                        <?php
                                    }else{
                                        echo belumAdaData(18 );
                                    }
                                }else{
                                    echo belumAdaData(18 );
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