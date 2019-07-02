<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");

$jps=($this->input->get('jps'))?$this->input->get('jps'):'PK014';
$sts=($this->input->get('sts'))?$this->input->get('sts'):'0';
$thn=($this->input->get("thn"))?$this->input->get("thn"):date('Y');
$bln=($this->input->get("bln"))?$this->input->get("bln"):date('m');

$modeApv=$this->input->get("m");
$apvmode =($this->input->get("m"))?"hidden":"";
$judul =($this->input->get("m"))?"Approval ":"";
$reprint =($this->input->get("m"))?"Apv RePrint":"";
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
		<div class="bar-nav pull-right">
			<a class="btn btn-info disabled-action" href=""><i class=" fa fa-list-ul"></i> List Piutang</a>
		</div>
	</div>
	<div class="col-lg-12 padding-left-right-5">
		<div class="panel margin-bottom-5">
			<div class="panel-heading">
				<i class="fa fa-list-ul"></i> List Piutang <?php echo $judul;?>
				<span class="tools pull-right"><a class="fa fa-chevron-down" href="javascript:;"></a></span>
			</div>
			<div class="panel-body panel-body-border panel-body-10">
				<form id="filterForm" method="GET" action="<?php echo base_url("report/tagihan"); ?>">
					<div class="row">
						<div class="col-xs-6 col-md-2 col-sm-2">
							<div class="form-group">
                        <label>Nama Dealer</label>
                        <select class="form-control " id="kd_dealer" name="kd_dealer">
                           <option value="0">--Pilih Dealer--</option>
                           <?php
                           if (isset($dealer)){
                              if (($dealer->totaldata >0)) {
                                 foreach ($dealer->message as $key => $value) {
                                    $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                    echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . " </option>";
                                 }
                              }
                           }
                           ?> 
                        </select>
                     </div>
                  </div>
                  <div class="col-xs-6 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label>Periode Bulan</label>
                          <select class="form-control" name="bln">
                              <option value=''>--Pilih Bulan--</option>
                              <?php
                                  for($i=1; $i<=12; $i++){
                                      $pilih=($bln==$i)?'selected':'';
                                      echo "<option value='".$i."' ".$pilih.">".nBulan($i)."</option>";
                                  }
                              ?>
                          </select>
                      </div>
                  </div>
                  <div class="col-xs-6 col-md-2 col-sm-2">
                      <div class="form-group">
                          <label>Tahun</label>
                          <select class="form-control" name="thn">
                              <option value=''>--Pilih Tahun--</option>
                              <?php
                                  if(isset($tahun)){
                                      if($tahun->totaldata >0){
                                          foreach ($tahun->message as $key => $value) {
                                              $pilih=($thn==$value->TAHUN)?'selected':'';
                                              echo "<option value='".$value->TAHUN."' ".$pilih.">".$value->TAHUN."</option>";
                                          }
                                      }else{
                                          echo "<option value='".date('Y')."' selected='true'>".date('Y')."</option>";
                                      }
                                  }else{
                                      echo "<option value='".date('Y')."' selected='true'>".date('Y')."</option>";
                                  }
                              ?>
                          </select>
                      </div>
                  </div>
                  <div class="col-xs-2 col-md-1 col-sm-1">
                     <div class="form-group">
                        <br>
                        <?php $tabaktife=$this->input->get("t");?>
                        <input type="hidden" id="tabaktif" name="t" value="<?php echo $tabaktife;?>">
                        <input type="hidden" name="m" value='<?php echo $this->input->get("m");?>'>
                        <button type="submit" class="btn btn-info"><i class='fa fa-search'></i> Preview</button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
   <div class="col-lg-12 padding-left-right-5">
    	<div class="panel margin-bottom-10">
    		<div class="panel-heading">
    			<i class="fa fa-list-ul"></i> Detail Piutang
    			<span class="tools pull-right"><a class="fa fa-chevron-down" href="javascript:;"></a></span>
    		</div>
    	
	    	<div class="panel-body panel-body-border">
	    		<div class="row">
	    			<div class="col-sm-12 col-md-12 col-xs-12">
                  <ul class="nav nav-tabs" role="tablist">
	    					<li role="presentation" class="<?php echo ($tabaktife=="" || $tabaktife=="1")? "active tbs":" tbs";?>">
                    	   <a href="#tabs-1" aria-controls="nontunai" role="tab" data-toggle="tab"><i class="fa fa-credit-card fa-fw"></i> <?php echo $judul;?> Penjualan Non Tunai </a>
                  	</li>
                  	<li role="presentation" class="<?php //echo $apvmode;?> <?php echo ($tabaktife=="2")? "active tbs":" tbs";?>">
                    	   <a href="#tabs-2" aria-controls="kredit" role="tab" data-toggle="tab"><i class="fa fa-university fa-fw"></i> <?php echo $reprint;?> Tagihan Leasing </a>
                  	</li>
                     <li role="presentation" class="<?php //echo $apvmode;?> <?php echo ($tabaktife=="3")? "active tbs":" tbs";?>">
                        <a href="#tabs-3" aria-controls="kupon" role="tab" data-toggle="tab"><i class="fa fa-envelope fa-fw"></i> <?php echo $reprint;?> Tagihan Kupon </a>
                     </li>
                     <li role="presentation" class="<?php //echo $apvmode;?> <?php echo ($tabaktife=="4")? "active tbs":" tbs";?>">
                        <a href="#tabs-4" aria-controls="program" role="tab" data-toggle="tab"><i class="fa fa-gift fa-fw"></i> <?php echo $reprint;?> Tagihan Program </a>
                     </li>
                  	<li role="presentation" class="<?php echo $apvmode;?> <?php echo ($tabaktife=="5")? "active tbs":" tbs";?>">
                    	   <a href="#tabs-5" aria-controls="joinpromo" role="tab" data-toggle="tab"><i class="fa fa-puzzle-piece fa-fw"></i> Join Promo</a>
                  	</li>
                  	<li role="presentation" class="<?php echo $apvmode;?> <?php echo ($tabaktife=="6")? "active tbs":" tbs";?>">
                    	   <a href="#tabs-6" aria-controls="lainnya" role="tab" data-toggle="tab"><i class="fa fa-share-alt fa-fw"></i> Piutang Lain</a>
                  	</li>
	              	</ul>
              	</div>
          	</div>
            <div class="tab-content">
               <!-- tagihan penjualan non tunai -->
               <div id="tabs-1" class="tab-pane <?php echo ($tabaktife=="" || $tabaktife=="1")? "active":"";?>" role="tabpanel">
                  <fieldset>
                     <div class="table-responsive h250">
     	          			<table class="table table-hover table-bordered table-stripped padding-top-5 font-small">
     	          				<thead>
     	          					<tr>
     	          						<th style="width:5%">No</th>
     	          						<th style="width:5%">#</th>
     	          						<th style="width:10%">No. SPK</th>
     	          						<th style="width:8%">Tgl SPK</th>
     	          						<th style="width:20%">Customer</th>
     	          						<th style="width:10%">Tipe Motor</th>
     	          						<th style="width:10%">Harga Unit</th>
     	          						<th style="width:10%">Tagihan</th>
     	          						<th style="width:15%">Cara Bayar</th>
     	          						<th style="width:10%">Status</th>
     	          					</tr>
     	          				</thead>
     	          				<tbody>
     	          					<?php
                                 $isApv=null;
                                 $isApv =($this->input->get("m")=='apv')?$nontunai_apv:$nontunai;
     	          						if(isset($isApv)){
     	          							$n=0;
     	          							if($isApv->totaldata > 0){
                                       $tr ="";

     	          								foreach ($isApv->message as $key => $value) {
     	          									$n++;
     	          									?>
     	          										<tr id="nt-<?php echo $value->NO_TRANS;?>">
     	          											<td class='text-center table-nowarp'><?php echo $n;?></td>
     	          											<td class="text-center table-nowarp">
                                                <?php if($judul){?>
                                                   <a data-toggle="modal" data-target="#<?php echo $value->NO_REFF;?>" data-backdrop="static"><i class="fa fa-cogs"></i></a>
                                                <?php }?>
     	          												<a class="hidden" hrer="#"><i class="fa fa-eye"></i></a>

     	          											</td>
     	          											<td class="text-center table-nowarp"><?php echo $value->NO_REFF;?></td>
     	          											<td class="text-center table-nowarp"><?php echo $value->TGL_TRANS;?></td>
     	          											<td class="td-overflow"><?php echo $value->NAMA_BPKB;?></td>
     	          											<td class="text-center table-nowarp"><?php echo $value->KD_ITEM;?></td>
     	          											<td class="text-right table-nowarp"><?php echo number_format($value->HARGA_OTR,0);?></td>
     	          											<td class="text-right table-nowarp"><?php echo number_format($value->SISA_TAGIHAN,0);?></td>
     	          											<td class="text-center table-nowarp"><?php echo $value->CARA_BAYAR;?></td>
     	          											<td class=" table-nowarp"><?php echo($value->APV_PIUTANG =='0')?'Open':'Approval';?></td>
     	          										</tr>
     	          									<?php
     	          								}
     	          							}
     	          						}
     	          					?>
     	          				</tbody>
     	          			</table>
                 		</div>
                  </fieldset>
            	</div>
               <!-- tagihan kredit ke leasing -->
               <div id="tabs-2" class="tab-pane <?php echo ($tabaktife=="2")? "active":"";?>" role="tabpanel">
                  <fieldset>
                  <div class="table-responsive h250">
                    <table class="table table-hover table-bordered table-stripped" id="lsh">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>#</th>
                          <th>No.SPK</th>
                          <th>Tgl. SPK</th>
                          <th>Fin</th>
                          <th>Harga</th>
                          <th>UangMuka</th>
                          <th>Tagihan</th>
                          <!-- <th>Sts</th> -->
                          <th>Uraian Transaksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                           $n=0;
                           if(isset($leasing)){
                              if($leasing->totaldata >0){
                                 foreach ($leasing->message as $key => $value) {
                                    $n++;
                                    $tampil = ($value->STATUS_PIUTANG >0)?"":"hidden";
                                    $tampil =($value->REPRINT==1)?"":$tampil;
                                    $tampil =($value->REPRINT==2)?"hidden":$tampil;
                                    $gatampil = ($value->STATUS_PIUTANG >0)?"hidden":"";
                                    $gatampil =($value->REPRINT >1)?"":$gatampil;
                                    $popover ="";
                                    $popover .="<div class='row'><table class='table table-stripped'><tr><td>Uang Muka Murni</td><td>:</td><td class='text-right'>".number_format($value->JML_DIBAYAR,0)."</td></tr>";
                                    $popover .="<tr><td>Total Subdisi</td><td>:</td><td class='text-right'>".number_format($value->SUBSIDI,0)."</td></tr>";
                                    $popover .="<tr><td class='text-right'>AHM</td><td>:</td><td class='text-right'>".number_format($value->SK_AHM,0)."</td></tr>";
                                    $popover .="<tr><td class='text-right'>Main Dealer</td><td>:</td><td class='text-right'>".number_format($value->SK_MD,0)."</td></tr>";
                                    $popover .="<tr><td class='text-right'>Dealer</td><td>:</td><td class='text-right'>".number_format($value->SK_SD,0)."</td></tr>";
                                    $popover .="<tr><td class='text-right'>Finance</td><td>:</td><td class='text-right'>".number_format($value->SK_FINANCE,0)."</td></tr>";
                                    $popover .="<tr class='total'><td>Total Uang Muka</td><td>:</td><td class='text-right'>".number_format(($value->JML_DIBAYAR+$value->SUBSIDI),0)."</td></tr></table></div>";
                                    
                                    $lunas = ($value->STATUS_PIUTANG >=2)?"Tagihan Lunas" :"Menunggu Pembayaran dari ".$value->KD_FINCOY;
                                       $lunas .='<hr>';
                                       $lunas .=($value->REPRINT==1)?'<i class="fa fa-info-circle text-success"></i> Menunggu request re print di approve':
                                       'Click icon <span class="fa fa-stack fa-xs"><i class="fa fa-print fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span> for request reprint';
                                       
                                    if($modeApv){
                                       if($value->REPRINT==1){
                                          ?>
                                          <tr id="<?php echo $value->NO_TRANS."-".$value->KD_FINCOY;?>" <?php echo ($value->SUBSIDI >0)?'data-toggle="popover" data-title="Detail Uang Muka" data-content="'.$popover.'"':'';?>>
                                          <td class='text-center'><?php echo $n;?></td>
                                          <td class='text-center table-nowarp'>
                                             <a data-toggle='popover' data-title='Approval Re Print' data-content='<?php echo $value->ALASAN_REPRINT;?>' onclick="__apvreprint('<?php echo $value->NO_TRANS.'/'.$value->KD_FINCOY;?>','<?php echo $value->KD_PIUTANG;?>','0');"><i class='fa fa-check'></i></a>
                                             <a title='Un Approve re print' onclick="__apvreprint('<?php echo $value->NO_TRANS.'/'.$value->KD_FINCOY;?>','<?php echo $value->KD_PIUTANG;?>','1');"><i class='fa fa-close'></i></a>
                                          </td>
                                          <td class='text-center table-nowarp'><?php echo $value->NO_TRANS;?></td>
                                          <td class='text-center table-nowarp'><?php echo tglFromSql($value->TGL_TRANS);?></td>
                                          <td class='text-center table-nowarp'><?php echo $value->KD_FINCOY;?></td>
                                          <td class="text-right table-nowarp"><?php echo number_format($value->HARGA_OTR,0);?></td>
                                          <td class="text-right table-nowarp"><?php echo number_format(($value->JML_DIBAYAR+$value->SUBSIDI),0);?> <?php echo ($value->SUBSIDI >0)?"<sup><i class='fa fa-info-circle' style='color:red'></i>":"&nbsp;";?></td>
                                          <td class="text-right table-nowarp"><?php echo number_format($value->SISA_TAGIHAN,0);?></td>
                                          <!-- <td class="table-nowarp"><?php echo "";?></td> -->
                                          <td class="td-overflow-100" title="<?php echo $value->URAIAN_TRANSAKSI;?>"><?php echo $value->URAIAN_TRANSAKSI;?></td>
                                       </tr>
                                       <?php
                                       }

                                    }else{
                                       ?>
                                       <tr id="<?php echo $value->NO_TRANS;?>" <?php echo ($value->SUBSIDI >0)?'data-toggle="popover" data-title="Detail Uang Muka" data-content="'.$popover.'"':'';?>>
                                          <td class='text-center'><?php echo $n;?></td>
                                          <td class='text-center table-nowarp'>
                                             <span id="lsh-<?php echo $value->NO_TRANS.'-'.$value->KD_FINCOY;?>" data-toggle='popover' onclick='__reprint("<?php echo $value->NO_TRANS."/".$value->KD_FINCOY;?>","<?php echo $value->KD_PIUTANG;?>");' data-title='Status Tagihan' data-content='<i class="fa fa-info-circle"></i> <?php echo $lunas;?>' class="fa-stack fa-xs <?php echo $tampil;?>">
                                                   <i class="fa fa-print fa-stack-1x"></i>
                                                   <i id="xlsh-<?php echo $value->NO_TRANS.'-'.$value->KD_FINCOY;?>" class="fa fa-ban fa-stack-2x <?php echo ((int)$value->REPRINT==1)?'text-success':'text-danger';?>"></i>
                                                </span>
                                             <a class="<?php echo $gatampil;?>" id="modal-button" title="Print Tagihan" onclick='addForm("<?php echo base_url('report/tagihan_lsg_print/').$value->NO_TRANS;?>")'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i></a>
                                          </td>
                                          <td class='text-center table-nowarp'><?php echo $value->NO_TRANS;?></td>
                                          <td class='text-center table-nowarp'><?php echo tglFromSql($value->TGL_TRANS);?></td>
                                          <td class='text-center table-nowarp'><?php echo $value->KD_FINCOY;?></td>
                                          <td class="text-right table-nowarp"><?php echo number_format($value->HARGA_OTR,0);?></td>
                                          <td class="text-right table-nowarp"><?php echo number_format(($value->JML_DIBAYAR+$value->SUBSIDI),0);?> <?php echo ($value->SUBSIDI >0)?"<sup><i class='fa fa-info-circle' style='color:red'></i>":"&nbsp;";?></td>
                                          <td class="text-right table-nowarp"><?php echo number_format($value->SISA_TAGIHAN,0);?></td>
                                          <!-- <td class="table-nowarp"><?php echo "";?></td> -->
                                          <td class="td-overflow-100" title="<?php echo $value->URAIAN_TRANSAKSI;?>"><?php echo $value->URAIAN_TRANSAKSI;?></td>
                                       </tr>
                                       <?php
                                    }
                                 }
                              }
                           }
                        ?>
                      </tbody>
                    </table>
                  </fieldset>
               </div>
               <!-- tagihan sales kupon -->
               <div id="tabs-3" class="tab-pane <?php echo ($tabaktife=="3")? "active":"";?>" role="tabpanel">
                  <fieldset>
                  <div class="table-responsive h250">
                     <table class="table table-stripped table-bordered">
                        <thead>
                           <tr>
                              <th style="width:5%">No.</th>
                              <th style="width:5%">#</th>
                              <th style="width:10%">Tgl.Trans</th>
                              <th style="width:8%">Fin</th>
                              <th>Uraian Transaksi</th>
                              <th style="width:15%">Nama Customer</th>
                              <th style="width:10%">Jumlah</th>
                              <th style="width:10%">No.Trans</th>
                              <th style="width:5%">Sts</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              $n=0;$no_spk="";
                              if(isset($kupon)){
                                 if($kupon->totaldata >0){
                                    foreach ($kupon->message as $key => $value) {
                                       $tampil = ($value->STATUS_PIUTANG >0)?"":"hidden";
                                       $gatampil = ($value->STATUS_PIUTANG >0)?"hidden":"";
                                       $Lunas = ($value->STATUS_PIUTANG >=2)?"Tagihan Lunas" :"Menunggu Pembayaran";
                                       $n++;
                                       $batas =($n>1 && $no_spk != $value->NO_TRANS)?'top-border':'';
                                       if($modeApv){

                                       }else{
                                       ?>
                                          <tr class='<?php echo $batas;?>' id="<?php echo "p_".$value->KD_SALESKUPON;?>">
                                             <td class='text-center'><?php echo $n;?></td>
                                             <td class='text-center table-nowarp'>
                                                <span data-toggle='popover' data-content='Menunggu Pembayaran' class="fa-stack fa-xs <?php echo $tampil;?>">
                                                   <i class="fa fa-print fa-stack-1x"></i>
                                                   <i class="fa fa-ban fa-stack-1x text-danger"></i>
                                                </span>
                                                <a class="<?php echo $gatampil;?>" id="modal-button" title="Print Tagihan" onclick='addForm("<?php echo base_url('report/tagihan_lsg_print/').$value->NO_TRANS;?>/kupon/<?php echo $value->KD_SALESKUPON;?>")'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i></a>
                                             </td>
                                             <td class='text-center table-nowarp'><?php echo tglFromSql($value->TGL_TRANS);?></td>
                                             <td class='text-center table-nowarp'><?php echo $value->KD_FINCOY;?></td>
                                             <td class='td-overflow-100' title="<?php echo $value->URAIAN_TRANSAKSI;?>"><?php echo $value->URAIAN_TRANSAKSI;?></td>
                                             <td class='td-overflow' title="<?php echo $value->NAMA_CUSTOMER;?>"><?php echo $value->NAMA_CUSTOMER;?></td>
                                             <td class='text-right table-nowarp'><?php echo number_format($value->JML_TAGIHAN,0);?></td>
                                             <td class='text-center table-nowarp'><?php echo $value->NO_TRANS;?></td>
                                             <td>&nbsp;</td>
                                          </tr>
                                       <?php 
                                       $no_spk= $value->NO_TRANS; 
                                       }  
                                    }
                                 }
                              }
                           ?>
                        </tbody>
                     </table>
                  </div>
                  </fieldset>
               </div>
               <!-- tagihan sales program -->
               <div id="tabs-4" class="tab-pane <?php echo ($tabaktife=="4")? "active":"";?>" role="tabpanel">
                  <fieldset>
                  <div class="table-responsive h250">
                     <table class="table table-stripped table-bordered">
                        <thead>
                           <tr>
                              <th style="width:5%">No.</th>
                              <th style="width:5%">#</th>
                              <th style="width:10%">No.Trans</th>
                              <th style="width:10%">Tgl.Trans</th>
                              <th style="width:8%">Tagih Ke</th>
                              <th>Uraian Transaksi</th>
                              <th style="width:10%">Jumlah</th>
                              <th style="width:15%">Nama Customer</th>
                              <th style="width:5%">Sts</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              $n=0;$no_spk="";
                              if(isset($program)){
                                 if($program->totaldata >0){
                                    foreach ($program->message as $key => $value) {
                                       $n++;
                                       $tampil = ($value->STATUS_PIUTANG >0)?"":"hidden";
                                       $tampil =($value->REPRINT==1)?"":$tampil;
                                       $tampil =($value->REPRINT==2)?"hidden":$tampil;
                                       $gatampil = ($value->STATUS_PIUTANG >0)?"hidden":"";
                                       $gatampil =($value->REPRINT >1)?"":$gatampil;
                                       $lunas = ($value->STATUS_PIUTANG >=2)?"Tagihan Lunas" :"Menunggu Pembayaran dari ".$value->TAGIHANKE;
                                       $lunas .='<hr>';
                                       $lunas .=($value->REPRINT==1)?'<i class="fa fa-info-circle text-success"></i> Menunggu request re print di approve':
                                       'Click icon <span class="fa fa-stack fa-xs"><i class="fa fa-print fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span> for request reprint';
                                       $batas =($n>1 && $no_spk != $value->NO_TRANS)?'top-border':'';
                                       if($modeApv){
                                          if((int)$value->REPRINT==1){
                                             ?>
                                             <tr class='<?php echo $batas;?>' id="<?php echo $value->NO_TRANS."-".$value->TAGIHANKE;?>">
                                                <td class='text-center'><?php echo $n;?></td>
                                                <td class='text-center table-nowarp'>
                                                   <a data-toggle='popover' data-title='Approval Re Print' data-content='<?php echo $value->ALASAN_REPRINT;?>' onclick="__apvreprint('<?php echo $value->NO_TRANS.'/'.$value->TAGIHANKE;?>','<?php echo $value->KD_PIUTANG;?>','0');"><i class='fa fa-check'></i></a>
                                                   <a title='Un Approve re print' onclick="__apvreprint('<?php echo $value->NO_TRANS.'/'.$value->TAGIHANKE;?>','<?php echo $value->KD_PIUTANG;?>','1');"><i class='fa fa-close'></i></a>
                                                </td>
                                                <td class='text-center table-nowarp'><?php echo $value->NO_TRANS;?></td>
                                                <td class='text-center table-nowarp'><?php echo tglFromSql($value->TGL_TRANS);?></td>
                                                <td class='table-nowarp'><?php echo $value->TAGIHANKE;?></td>
                                                <td class='td-overflow-100' title="<?php echo $value->URAIAN_TRANSAKSI;?>"><?php echo $value->URAIAN_TRANSAKSI;?></td>
                                                <td class='text-right table-nowarp'><?php echo number_format($value->JML_TAGIHAN,0);?></td>
                                                <td class='td-overflow' title="<?php echo $value->NAMA_CUSTOMER;?>"><?php echo $value->NAMA_CUSTOMER;?></td>
                                                <td>&nbsp;</td>
                                             </tr>
                                          <?php
                                          }
                                       }else{
                                       ?>
                                          <tr class='<?php echo $batas;?>'id="<?php echo $value->NO_TRANS."_".$value->TAGIHANKE;?>">
                                             <td class='text-center'><?php echo $n;?></td>
                                             <td class='text-center table-nowarp'>
                                                <span id="inf-<?php echo $value->NO_TRANS.'-'.$value->TAGIHANKE;?>" data-toggle='popover' onclick='__reprint("<?php echo $value->NO_TRANS."/".$value->TAGIHANKE;?>","<?php echo $value->KD_PIUTANG;?>");' data-title='Status Tagihan' data-content='<i class="fa fa-info-circle"></i> <?php echo $lunas;?>' class="fa-stack fa-xs <?php echo $tampil;?>">
                                                   <i class="fa fa-print fa-stack-1x"></i>
                                                   <i id="x-<?php echo $value->NO_TRANS.'-'.$value->TAGIHANKE;?>" class="fa fa-ban fa-stack-2x <?php echo ((int)$value->REPRINT==1)?'text-success':'text-danger';?>"></i>
                                                </span>
                                                <a class="<?php echo $gatampil;?>" id="modal-button" title="Print Tagihan" onclick='addForm("<?php echo base_url('report/tagihan_lsg_print/').$value->NO_TRANS;?>/program/<?php echo $value->TAGIHANKE;?>")'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i></a>
                                             </td>
                                             <td class='text-center table-nowarp'><?php echo $value->NO_TRANS;?></td>
                                             <td class='text-center table-nowarp'><?php echo tglFromSql($value->TGL_TRANS);?></td>
                                             <td class='table-nowarp'><?php echo $value->TAGIHANKE;?></td>
                                             <td class='td-overflow-100' title="<?php echo $value->URAIAN_TRANSAKSI;?>"><?php echo $value->URAIAN_TRANSAKSI;?></td>
                                             <td class='text-right table-nowarp'><?php echo number_format($value->JML_TAGIHAN,0);?></td>
                                             <td class='td-overflow' title="<?php echo $value->NAMA_CUSTOMER;?>"><?php echo $value->NAMA_CUSTOMER;?></td>
                                             <td>&nbsp;</td>
                                          </tr>
                                       <?php
                                          $no_spk=$value->NO_TRANS;
                                       }
                                    }
                                 }
                              }
                           ?>
                        </tbody>
                     </table>
                  </div>
                  </fieldset>
               </div>
               <!-- tagihan join promo -->
               <div id="tabs-5" class="tab-pane <?php echo ($tabaktife=="5")? "active":"";?>" role="tabpanel">
                  <fieldset>
                  <div class="table-responsive h250">
                    <table class="table table-stripped table-bordered">
                        <thead>
                           <tr>
                              <th style="width:5%">No.</th>
                              <th style="width:5%">#</th>
                              <th style="width:10%">No.Trans</th>
                              <th style="width:10%">Tgl.Trans</th>
                              <th style="width:8%">Fin</th>
                              <th>Uraian Transaksi</th>
                              <th style="width:10%">Jumlah</th>
                              <!-- <th style="width:15%">Nama Customer</th> -->
                              <th style="width:5%">Sts</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              $n=0;$no_spk="";
                              if(isset($promo)){
                                 if($promo->totaldata >0){
                                    foreach ($promo->message as $key => $value) {
                                       $tampil = ($value->STATUS_PIUTANG >0)?"":"hidden";
                                       $gatampil = ($value->STATUS_PIUTANG >0)?"hidden":"";
                                       $n++;
                                       $batas =($n>1 && $no_spk != $value->NO_TRANS)?'top-border':'';
                                       ?>
                                          <tr class='<?php echo $batas;?>' id="<?php echo $value->NO_TRANS."_".$value->KD_FINCOY;?>">
                                             <td class='text-center'><?php echo $n;?></td>
                                             <td class='text-center table-nowarp'>
                                                <span data-toggle='popover' data-content='Menunggu Pembayaran' class="fa-stack fa-xs <?php echo $tampil;?>">
                                                   <i class="fa fa-print fa-stack-1x"></i>
                                                   <i class="fa fa-ban fa-stack-2x text-danger"></i>
                                                </span>
                                                <a class="<?php echo $gatampil;?>" id="modal-button" title="Print Tagihan" onclick='addForm("<?php echo base_url('report/tagihan_lsg_print/').$value->NO_TRANS;?>/promo/<?php echo $value->KD_FINCOY;?>")'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i></a>
                                             </td>
                                             <td class='text-center table-nowarp'><?php echo $value->NO_TRANS;?></td>
                                             <td class='text-center table-nowarp'><?php echo tglFromSql($value->TGL_TRANS);?></td>
                                             <td class='table-nowarp'><?php echo $value->KD_FINCOY;?></td>
                                             <td class='td-overflow-100' title="<?php echo $value->URAIAN_TRANSAKSI;?>"><?php echo $value->URAIAN_TRANSAKSI;?></td>
                                             <td class='text-right table-nowarp'><?php echo number_format($value->TOTAL_TAGIHAN,0);?></td>
                                             <td>&nbsp;</td>
                                          </tr>
                                       <?php
                                       $no_spk=$value->NO_TRANS;
                                    }
                                 }
                              }
                           ?>
                        </tbody>
                     </table>
                  </div>
                  </fieldset>
               </div>
               <!-- tagihan lainnya -->
               <div id="tabs-6" class="tab-pane <?php echo ($tabaktife=="6")? "active":"";?>" role="tabpanel">
                  <fieldset>
                     <div class="table-responsive h250">
                    
                     </div>
                  </fieldset>
               </div>
            </div>
      	</div>
	   </div>
  	</div>
  	<div class="footer">
      <?php 
      if(isset($nontunai_apv)){
         if($nontunai_apv->totaldata>0){
            foreach ($nontunai_apv->message as $key => $value) {
            ?>
               <div class="modal fade xxd" id="<?php echo $value->NO_REFF;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $value->NO_REFF;?>" data-backdrop="static">
                  <div class="modal-dialog" role="document">
                     <div class="modal-content">
                        <form id="addForm-<?php echo $value->NO_REFF;?>" class="bucket-form" action="" method="post">
                           <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h5 class="modal-title" id="myModalLabel-<?php echo $value->NO_REFF;?>"><i class="fa fa-list-ul"></i> Approval Pembayaran Non Tunai</h5>
                           </div>    
                           <div class="modal-body" style="overflow-y: auto!important;">
                              <table class="table table-hover table-striped table-bordered">
                                 <tr class="total"><td>Nama Customer </td><td><?php echo $value->NAMA_BPKB;?></td></tr>
                                 <tr><td colspan="2"><?php echo $value->ALAMAT_BPKB." ".$value->NAMA_KELURAHAN."<br>Kec. ".$value->NAMA_KECAMATAN;?></td></tr>
                                 <tr class='total'>
                                    <td class="table-nowarp">NO. TRANS</td>
                                    <td class="table-nowarp"><?php echo $value->NO_TRANS;?></td>
                                 </tr>
                                 <tr>
                                    <td class="table-nowarp">Tanggal</td>
                                    <td class="table-nowarp"><?php echo tglFromSql($value->TGL_TRANS);?></td>
                                 </tr>
                                 <tr>
                                    <td class="table-nowarp">NO. SPK</td>
                                    <td class="table-nowarp"><?php echo $value->NO_REFF;?></td>
                                 </tr>
                                 <tr>
                                    <td class="table-nowarp">Keterangan</td>
                                    <td class="table-nowarp"><?php echo str_replace("]","]<br>",$value->URAIAN_PIUTANG);?></td>
                                 </tr>
                                 <tr>
                                    <td class="table-nowarp">Harga OTR</td>
                                    <td class="table-nowarp"><?php echo number_format($value->HARGA_OTR,0);?></td>
                                 </tr>
                                 
                                 <tr>
                                    <td class="table-nowarp">Uang Muka [Cash]</td>
                                    <td class="table-nowarp"><?php echo number_format(($value->HARGA_OTR-$value->TOTAL_TAGIHAN),0);?></td>
                                 </tr>
                                 <tr>
                                    <td class="table-nowarp">Jumlah Bayar [<?php echo $value->CARA_BAYAR;?>]</td>
                                    <td class="table-nowarp">
                                       <span><?php echo number_format($value->TOTAL_TAGIHAN,0);?></span>
                                       <span class='pull-right'><?php echo 'Rencana Bayar :'.tglFromSql($value->TGL_TEMPO);?></span>
                                    </td>
                                 </tr>
                              </table>
                           </div>
                           <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
                              <button id="submit-btn_<?php echo $value->NO_TRANS;?>:-1" data-dismiss="modal" type="button" class="btn btn-default submit-btn hidden"><i class="fa fa-trash"></i> Not Approved</button>
                              <button id="submit-btn_<?php echo $value->NO_TRANS;?>:1" data-dismiss="modal" type="button" class="btn btn-danger submit-btn"><i class="fa fa-save"></i> Approved</button>
                           </div>
                        </form>                        
                     </div>
                  </div>
               </div>
            <?php
            }
         }
      }
      ?>
      
  	<?php echo loading_proses();?>
</section>
<script type="text/javascript" scr="<?php echo base_url("assets/js/external/tagihan.js?v=").date('YmdHis');?>"></script>
<script type="text/javascript">
   $(document).ready(function(e){
      $('table#lsh tbody tr[data-toggle="popover"]').popover({
         placement: 'top', 
         trigger: 'hover', 
         html: true
      })
      $('span.fa-stack[data-toggle="popover"').popover({
         placement: 'top', 
         trigger: 'hover', 
         html: true
      })
      $('td > a[data-toggle="popover"').popover({
         placement: 'top', 
         trigger: 'hover', 
         html: true
      })
      $("ul > li > a").on('click',function(){
        var tabId=$(this).attr('href');
        console.log(tabId);
        switch(tabId){
          case "#tabs-6":$("#tabaktif").val('6');break;
          case "#tabs-2":$("#tabaktif").val('2');break;
          case "#tabs-3":$("#tabaktif").val('3');break;
          case "#tabs-4":$("#tabaktif").val('4');break;
          case "#tabs-5":$("#tabaktif").val('5');break;
          default:
              $("#tabaktif").val('1');break;
        }
      })
      $('a.rpt').click(function(){
         var id= $(this).atr('id');
         alert(id);
         __reprint(id);
      })
      $('.modal .submit-btn').on('click',function(){
         var id =$(this).attr('id').split('_');
         //console.log(id);
         if(id.length >1){
            var ids = id[1].split(':');
            var notrans=ids[0];
            var sts = ids[1];
            p_approve(notrans,sts);
         }
      })
   })

   function p_approve(notrans,statuse){
      $.getJSON("<?php echo base_url('report/tagihan_apv');?>",{'no_trans':notrans,'statuse':statuse},function(result){
        //console.log(result);
        if(result.status){
            $('table > tbody > tr#nt-'+notrans).addClass("hidden");
        }
      })
   }
   function __apvreprint(id,kdp,apv){
      var alasan="";
      var apro ="2";
      var idx=id.replace('/','-');
      var ntrans=id.split('/');
      if(apv==1){
         alasan = prompt("Masukan Alasan tidak di Approve");
         if(!alasan){ return false};
         apro="-1";
      }
      var kd_piutang="";
      switch(ntrans[1]){
         case 'AHM':
         case 'MD':
         case 'FIN':
         case 'DLR':
            kd_piutang = kdp +'-'+ntrans[1];
            break;
         default:
            kd_piutang = kdp;
         break;
      }
      $.ajax({
         type:'GET',
         url :"<?php echo base_url('angsuran/reprinttagihan/');?>"+apro,
         data:{
           'no_trans':ntrans[0],
           'kd_piutang':kd_piutang,
           'alasan':alasan
         },
         success:function(result){
            $('tr#'+idx).remove();
         }
      })
   }
   function __reprint(id,kdp){
      var idx=id.replace('/','-');
      var ntrans=id.split('/');
      var apv="<?php echo $this->input->get('m');?>"
      if($('#x-'+idx).hasClass('text-success')){ return false;}
      var alasan=prompt("Masukan alasan Re Print tagihan ini");
      var kd_piutang="";
      switch(ntrans[1]){
         case 'AHM':
         case 'MD':
         case 'FIN':
         case 'DLR':
            kd_piutang = kdp +'-'+ntrans[1];
            break;
         default:
            kd_piutang = kdp;
         break;
      }
      if(alasan){
         //ajax update
         $.ajax({
            type:'GET',
            url :"<?php echo base_url('angsuran/reprinttagihan');?>",
            data:{
              'no_trans':ntrans[0],
              'kd_piutang':kd_piutang,
              'alasan':alasan
            },
            success:function(result){
               $('#x-'+idx).removeClass('text-danger').addClass('text-success');
               $('#inf-'+idx).attr('data-content','Reprint menunggu di approve');
               $('#xlsh-'+idx).removeClass('text-danger').addClass('text-success');
               $('#lsh-'+idx).attr('data-content','Reprint menunggu di approve');
            }
         })
      }
      $('span.fa-stack[data-toggle="popover"').popover('hide');
   }  
               
     
</script>