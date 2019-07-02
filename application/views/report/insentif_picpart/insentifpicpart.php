<?php
if (!isBolehAkses()) {
   redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
//$status_p = '';
$tipe=($this->input->get("tp"))?$this->input->get("tp"):"0";
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$kd_lokasidealer = "";
$tgl_trans = "";
$no_trans = "";
$nama_dealer=""; $tlp=""; $nama_kabupaten=""; $alamat=""; $kd_dealerahm="";
if (isset($list)) {
  if(($list->totaldata > 0)) {
    foreach ($list->message as $key => $value) {
      $kd_dealer = $value->KD_DEALER;
      $nik = $value->NIK;
      
    }
  }
}
if (($this->session->userdata('kd_group')=='root') || ($this->session->userdata('kd_group')=='DS')){
    $actionapprove = "";
} else {
    $actionapprove = "hidden";
    $dealeruser = $this->session->userdata('kd_dealer');
}
?>


<section class="wrapper">
   <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

             <a id="proses" class="btn btn-info <?php if (!isset($list)) echo 'disabled'; else echo ''; ?>" >
                  
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Pengajuan" ></i> Proses Pengajuan
            </a>
            
             <a class="btn btn-info <?php if (!isset($list)) echo 'disabled'; else echo ''; ?>" href="<?php echo base_url('report/insentifpicpart_print?q=' . $this->input->get("q") . '&tahun=' . $this->input->get("tahun")); ?> "target="_blank">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print" ></i> Cetak
            </a>
            
           
            
        </div>

    </div>

   <div class="col-lg-12 padding-left-right-10 ">
      <div class="panel margin-bottom-10">
         <div class="panel-heading">
            Laporan Insentif PIC Part
            <span class="tools pull-right">
               <a class="fa fa-chevron-up" href="javascript:;"></a>
            </span>
         </div>

         <div class="panel-body panel-body-border" >
            <form id="filterFormz" action="<?php echo base_url('report/insentifpicpart') ?>" class="bucket-form" method="get">
               <div class="row">
                  <div class="col-xs-12 col-sm-3 col-md-3">
                     <div class="form-group">
                        <label>Nama Dealer</label>
                       <select name="kd_dealer" id="kd_dealer" class="form-control">
                        <?php       
             
                           if (($this->session->userdata('kd_group')=='root') || ($this->session->userdata('kd_group')=='DS')){
                        ?>
                                <option value="">--Pilih Dealer--</option>
                        <?php
                                if(is_array($dealer->message)){
                                    foreach ($dealer->message as $key => $value) {
                                        $len =(strlen($value->KD_DEALER) >=3)?"":"&nbsp;";
                                        $select=($dealerpilih==$value->KD_DEALER)?"selected":"";
                                        echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
                                    }
                                }
                            } else {
                                if(is_array($dealer->message)){
                                    foreach ($dealer->message as $key => $value) {
                                        $len =(strlen($value->KD_DEALER) >=3)?"":"&nbsp;";
                                        if ($dealeruser==$value->KD_DEALER) {
                                            echo "<option value='".$value->KD_DEALER."' selected>".$value->NAMA_DEALER."</option>";
                                        }
                                    }
                                }
                            }
                            
                        ?>
                    </select>
                     </div>
                  </div>

                  <div class="col-xs-12 col-sm-2">
                     <div class="form-group">
                        <label>Kuartal</label>
                       <select name="q" id="q" class="form-control">
                            <option value="">- Pilih Kuartal -</option>
                            <option value="Q1" <?php
                                          if ($qpilih == 'Q1') {
                                              echo 'selected';
                                          }
                                          ?> >Q1</option>
                            <option value="Q2" <?php
                                          if ($qpilih == 'Q2') {
                                              echo 'selected';
                                          }
                                          ?>>Q2</option>
                            <option value="Q3" <?php
                                          if ($qpilih == 'Q3') {
                                              echo 'selected';
                                          }
                                          ?>>Q3</option>
                            <option value="Q4" <?php
                                          if ($qpilih == 'Q4') {
                                              echo 'selected';
                                          }
                                          ?>>Q4</option>
                        </select>
                     </div>
                  </div>
                    <div class="col-xs-12 col-sm-2">
                        <div class="form-group">
                            <label>Tahun</label>
                            <select class="form-control" name="tahun" id="tahun">
                            </select>
                        </div>
                    </div>

                  <div class="col-xs-3 col-md-1 col-sm-1">
                     <div class="form-group">
                        <br>
                        <button type="submit" class="btn btn-info"><i class='fa fa-search'></i> Preview</button>
                    </div>
                  </div>
                   
                 
               </div>
            </form>
         </div>
      </div>
   </div>

   <div class="col-lg-12 padding-left-right-20" >
      <div class="panel panel-default">
         <div class="table-responsive h350">

            <table class="table table-stripped table-hover table-bordered" style="font-size: 12px">
               <thead>
                  <tr>
                     <th class="text-center" style="width:40px;" >NO</th>
                     <th class="text-center">Dealer</th>
                     <th class="text-center">NIK</th>
                     <th class="text-center">Nama</th>
                     <th class="text-center">Persentase</th>
                     <th class="text-center">Insentif</th>
                  </tr>

                 

               </thead>

               <tbody>
                  <?php
                  if (isset($list)) {
                     $no = 0;
                     if (($list->totaldata >0 )) {
                        foreach ($list->message as $key => $value) {
                        # code...
                           $no++;
                           ?>
                           <tr>
                              <td class='table-nowarp'><?php echo $no; ?></td>
                              <td class='table-nowarp'><?php echo ($value->NAMA_DEALER);?></td>
                              <td class='table-nowarp'><?php echo ($value->NIK);?></td>
                              <td class='table-nowarp'><?php echo ($value->NAMA);?></td>
                              <td class='table-nowarp'><?php echo ($value->PERSENTASE);?></td>
                              <td class='text-right'><?php echo ($value->INSENTIF); ?></td>
                           </tr>
                           <?php
                        }
                     }
                  }
                  ?>
               </tbody>
            </table>
         </div>
         <footer class="panel-footer">
            <div class="row">
               <div class="col-sm-5">
                  <small class="text-muted inline m-t-sm m-b-sm"> 
                     <?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total Data " . $list->totaldata . " items</i>") : '' ?>
                        
                     </small>
               </div>
               <div class="col-sm-7 text-right text-center-xs">                
                  <?php echo $pagination; ?>    
               </div>
            </div>
         </footer>
      </div>
   </div>
</section>
<script type="text/javascript">
    
    var max = new Date().getFullYear(),
    min = max - 2,
    select = document.getElementById('tahun');

    for (var i = max; i >= min; i--) {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        select.appendChild(opt);
        //console.log("Tes");
        //select2.appendChild(opt);
    }
   
    
    $(document).ready(function(){
        
            var kd_dealer = $('#kd_dealer').val();
            var q = $('#q').val();
            var tahun = $('#tahun').val();
            
            $('#proses').click(function(){

                console.log("Kd Dealer "+kd_dealer);
                console.log("Q "+q);
                console.log("Tahun "+tahun);
                __proses(kd_dealer, q, tahun);
            })
	})
	function __proses(kd_dealer, q, tahun){
	    //document.location.reload();
            //$('.success').animate({top: "0"}, 500);
            //$('.success').html("Sedang diajukan").fadeIn();
            
             $("#proses").addClass("disabled");
            $("#proses").html("<i class='fa fa-spinner fa-spin'></i> Loading");
            
            $('#loadpage').removeClass("hidden");
            $.post("<?php echo base_url();?>report/proses_insentifpicpart/"+kd_dealer+"/"+q+"/"+tahun, function(result){
                //console.log(result);
               
            $('.success').animate({top: "0"}, 500);
            $('.success').html("Pengajuan Berhasil").fadeIn();
            if (result.location != null) {
                setTimeout(function() {
                    location.replace(result.location)
                }, 2000);
            } else {
                setTimeout(function() {
                    location.reload();
                }, 2000);
            }
                
                //alert("Pengajuan Berhasil");
                //$('#loadpage').removeClass("hidden");

            })
		
	}
</script>