<?php
if (!isBolehAkses()) {
    //redirect(base_url() . 'auth/error_auth');
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
$tabaktife = $this->input->get("t");
$tampil = ($this->input->get("bln"))?'':'hidden';
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
				<i class="fa fa-list-ul"></i> List Tagihan Menunggu Pembayaran
				<span class="tools pull-right"><a class="fa fa-chevron-down" href="javascript:;"></a></span>
			</div>
			<div class="panel-body panel-body-border panel-body-10">
				<form id="filterForm" method="GET" action="<?php echo base_url("report/view_ar"); ?>">
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
                  <div class="col-xs-6 col-md-3 col-sm-3">
                     <div class="form-group">
                        <br>
                        <input type="hidden" id="tabaktif" name="t" value="<?php echo $tabaktife;?>">
                        <button type="submit" class="btn btn-info"><i class='fa fa-search'></i> Preview</button>
                        <button type="button" onclick="__reset2all();" class="btn btn-info <?php echo $tampil;?>"><i class='fa fa-cog'></i> Reset</button>
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
	    					<li role="presentation" <?php echo ($tabaktife=="" || $tabaktife=="1")? " class='active tbs'":" tbs";?>>
                    	   <a href="#tabs-1" aria-controls="nontunai" role="tab" data-toggle="tab"><i class="fa fa-credit-card fa-fw"></i> Penjualan Non Tunai </a>
                  	</li>
                  	<li role="presentation" <?php echo ($tabaktife=="2")? " class='active tbs'":" tbs";?>>
                    	   <a href="#tabs-2" aria-controls="kredit" role="tab" data-toggle="tab"><i class="fa fa-university fa-fw"></i> Tagihan Leasing </a>
                  	</li>
                     <li role="presentation" <?php echo ($tabaktife=="3")? " class='active tbs'":" tbs";?>>
                        <a href="#tabs-3" aria-controls="kupon" role="tab" data-toggle="tab"><i class="fa fa-envelope fa-fw"></i> Tagihan Kupon </a>
                     </li>
                     <li role="presentation" <?php echo ($tabaktife=="4")? " class='active tbs'":" tbs";?>>
                        <a href="#tabs-4" aria-controls="program" role="tab" data-toggle="tab"><i class="fa fa-gift fa-fw"></i> Tagihan Program </a>
                     </li>
                  	<li role="presentation" <?php echo ($tabaktife=="5")? " class='active tbs'":" tbs";?>>
                    	   <a href="#tabs-5" aria-controls="joinpromo" role="tab" data-toggle="tab"><i class="fa fa-puzzle-piece fa-fw"></i> Join Promo</a>
                  	</li>
                  	<li role="presentation" <?php echo ($tabaktife=="6")? " class='active tbs'":" tbs";?>>
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
     	          			<table class="table table-hover table-bordered table-stripped padding-top-5 font-small" id="nnt">
     	          				<thead>
     	          					<tr>
     	          						<th style="width:5%">No</th>
     	          						<th style="width:5%">#</th>
     	          						<th style="width:10%">No. SPK</th>
     	          						<th style="width:8%">Tgl SPK</th>
     	          						<th style="width:20%">Customer</th>
     	          						<th style="width:10%">Tipe Motor</th>
     	          						<th style="width:10%">Harga Unit</th>
     	          						<th style="width:10%">Jml Dibayar</th>
     	          						<th style="width:10%">Sisa Tagihan</th>
     	          						<th style="width:15%">Cara Bayar</th>
     	          					</tr>
     	          				</thead>
     	          				<tbody>
     	          					<?php
                                 $nontunai_apv=null;
     	          						if(isset($nontunai)){
     	          							$n=0;$nontunai_apv=$nontunai;
     	          							if($nontunai->totaldata > 0){
     	          								foreach ($nontunai->message as $key => $value) {
                                          $tampil = ($value->STATUS_PIUTANG >=2 && (double)$value->SISA_TAGIHAN ==0)?"":"hidden";
                                          $gatampil = ($value->STATUS_PIUTANG >=2 && (double)$value->SISA_TAGIHAN ==0)?"hidden":"";
     	          									$n++;
     	          									?>
     	          										<tr>
     	          											<td class='text-center table-nowarp'><?php echo $n;?></td>
     	          											<td class="text-center table-nowarp">
                                                      <span class='fa-stack <?php echo $gatampil;?>' data-toggle='popover' data-content=' Bayar Tagihan'>
                                                         <!-- <a id="modal-button" onclick='addForm("<?php echo base_url('report/tagihan_bayar/').$value->NO_TRANS;?>")'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-edit"></i></a> -->
                                                         <a role="button" class="xc" data-toggle="modal" data-target="#<?php echo $value->NO_REFF;?>" data-backdrop="static"><i class="fa fa-cogs"></i></a>
                                                      </span>
     	          												   <span data-toggle='popover' data-content='Lunas' class="<?php echo $tampil;?>">
                                                         <i class="fa fa-check-circle-o text-success"></i>
                                                      </span>
     	          											</td>
     	          											<td class="text-center table-nowarp"><?php echo $value->NO_REFF;?></td>
     	          											<td class="text-center table-nowarp"><?php echo $value->TGL_TRANS;?></td>
     	          											<td class="td-overflow"><?php echo $value->NAMA_BPKB;?></td>
     	          											<td class="text-center table-nowarp"><?php echo $value->KD_ITEM;?></td>
     	          											<td class="text-right table-nowarp"><?php echo number_format($value->HARGA_OTR,0);?></td>
     	          											<td class="text-right table-nowarp"><?php echo number_format($value->JUMLAH_DIBAYAR,0);?></td>
     	          											<td class="text-right table-nowarp"><?php echo number_format($value->SISA_TAGIHAN,0);?></td>
     	          											<td class="text-center table-nowarp"><?php echo $value->CARA_BAYAR;?></td>
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
                          <th>Harga Unit</th>
                          <th>UangMuka</th>
                          <th>Jml Dibayar</th>
                          <th>Sisa Tagihan</th>
                          <th>Uraian Transaksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                           $n=0;
                           //var_dump($leasing);exit();
                           if(isset($leasing)){
                              if($leasing->totaldata >0){
                                 foreach ($leasing->message as $key => $value) {
                                    $n++;
                                    
                                    $tampil = ($value->STATUS_PIUTANG >=2 && (double)$value->SISA_TAGIHAN==0)?"":"hidden";
                                    $gatampil = ($value->STATUS_PIUTANG >=2 && (double)$value->SISA_TAGIHAN==0)?"hidden":"";
                                    $popover ="";
                                    $popover .="<div class='row'><table class='table table-stripped'><tr><td>Uang Muka Murni</td><td>:</td><td class='text-right'>".number_format($value->JML_DIBAYAR,0)."</td></tr>";
                                    $popover .="<tr><td>Total Subdisi</td><td>:</td><td class='text-right'>".number_format($value->SUBSIDI,0)."</td></tr>";
                                    $popover .="<tr><td class='text-right'>AHM</td><td>:</td><td class='text-right'>".number_format($value->SK_AHM,0)."</td></tr>";
                                    $popover .="<tr><td class='text-right'>Main Dealer</td><td>:</td><td class='text-right'>".number_format($value->SK_MD,0)."</td></tr>";
                                    $popover .="<tr><td class='text-right'>Dealer</td><td>:</td><td class='text-right'>".number_format($value->SK_SD,0)."</td></tr>";
                                    $popover .="<tr><td class='text-right'>Finance</td><td>:</td><td class='text-right'>".number_format($value->SK_FINANCE,0)."</td></tr>";
                                    $popover .="<tr class='total'><td>Total Uang Muka</td><td>:</td><td class='text-right'>".number_format(($value->JML_DIBAYAR+$value->SUBSIDI),0)."</td></tr></table></div>";
                                    ?>
                                    <tr id="<?php echo $value->NO_TRANS;?>" <?php echo ($value->SUBSIDI >0)?'data-toggle="popover" data-title="Detail Uang Muka" data-content="'.$popover.'"':'';?>>
                                       <td class='text-center'><?php echo $n;?></td>
                                       <td class='text-center table-nowarp'>
                                          <span data-toggle='popover' id="ga-<?php echo $value->NO_TRANS;?>" data-content='Lunas' class="fa-stack <?php echo $tampil;?>">
                                             <i class="fa fa-check-circle-o text-success"></i>
                                          </span>
                                          <span class='fa-stack <?php echo $gatampil;?>' id="tampil-<?php echo $value->NO_TRANS;?>" data-toggle='popover' data-content=' Bayar Tagihan'>
                                            <a id="modal-button" onclick='addForm("<?php echo base_url('report/tagihan_bayar/').$value->NO_TRANS;?>")'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-edit"></i></a>
                                          </span>
                                       </td>
                                       <td class='text-center table-nowarp'><?php echo $value->NO_TRANS;?></td>
                                       <td class='text-center table-nowarp'><?php echo tglFromSql($value->TGL_TRANS);?></td>
                                       <td class='text-center table-nowarp'><?php echo $value->KD_FINCOY;?></td>
                                       <td class="text-right table-nowarp"><?php echo number_format($value->HARGA_OTR,0);?></td>
                                       <td class="text-right table-nowarp"><?php echo number_format(($value->JML_DIBAYAR+$value->SUBSIDI),0);?> <?php echo ($value->SUBSIDI >0)?"<sup><i class='fa fa-info-circle' style='color:red'></i>":"&nbsp;";?></td>
                                       <td class="table-nowarp text-right "><?php echo number_format($value->JUMLAH_DIBAYAR,0);?></td>
                                       <td class="text-right table-nowarp"><?php echo number_format($value->SISA_TAGIHAN,0);?></td>
                                       <td class="td-overflow-100" title="<?php echo $value->URAIAN_TRANSAKSI;?>"><?php echo $value->URAIAN_TRANSAKSI;?></td>
                                    </tr>
                                    <?php
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
                                       $n++;
                                       $batas =($n>1 && $no_spk != $value->NO_TRANS)?'top-border':'';
                                       ?>
                                          <tr class='<?php echo $batas;?>'>
                                             <td class='text-center'><?php echo $n;?></td>
                                             <td class='text-center table-nowarp'>
                                                <a id="modal-button" title="Print Tagihan" onclick='addForm("<?php echo base_url('report/tagihan_lsg_print/').$value->NO_TRANS;?>/kupon/<?php echo $value->KD_SALESKUPON;?>")'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i></a>
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
                                       $batas =($n>1 && $no_spk != $value->NO_TRANS)?'top-border':'';

                                       $tampil = ($value->STATUS_PIUTANG >=2 && (double)$value->SISA_TAGIHAN==0)?"":"hidden";
                                       $gatampil = ($value->STATUS_PIUTANG >=2 && (double)$value->SISA_TAGIHAN==0)?"hidden":"";
                                       ?>
                                          <tr class='<?php echo $batas;?>'>
                                             <td class='text-center'><?php echo $n;?></td>
                                             <td class='text-center table-nowarp'>
                                                <span data-toggle='popover' id="ga-<?php echo $value->NO_TRANS;?>" data-content='Lunas' class="fa-stack <?php echo $tampil;?>">
                                                   <i class="fa fa-check-circle-o text-success"></i>
                                                </span>
                                                <span class='fa-stack <?php echo $gatampil;?>' id="tampil-<?php echo $value->NO_TRANS;?>" data-toggle='popover' data-content=' Bayar Tagihan'>
                                                  <a id="modal-button" onclick='addForm("<?php echo base_url('report/tagihan_bayar/').$value->NO_TRANS.'/program/'.$value->TAGIHANKE;?>")'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-edit"></i></a>
                                                </span>
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
                                       $n++;
                                       $batas =($n>1 && $no_spk != $value->NO_TRANS)?'top-border':'';
                                       ?>
                                          <tr class='<?php echo $batas;?>'>
                                             <td class='text-center'><?php echo $n;?></td>
                                             <td class='text-center table-nowarp'>
                                                <a id="modal-button" title="Print Tagihan" onclick='addForm("<?php echo base_url('report/tagihan_lsg_print/').$value->NO_TRANS;?>/promo/<?php echo $value->KD_FINCOY;?>")'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i></a>
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
      $jumlah=0;
      if(isset($nontunai_apv)){
         if($nontunai_apv->totaldata>0){
            foreach ($nontunai_apv->message as $key => $value) {
              ?>
                  <!-- <div id="<?php echo $value->NO_REFF;?>" class="hidden"> -->
                     <div class="modal fade xxd" id="<?php echo $value->NO_REFF;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                           <div class="modal-content">
                              <form id="addForms-<?php echo $value->NO_REFF;?>" class="bucket-form" action="" method="post">
                                 <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h5 class="modal-title" id="myModalLabel"><i class="fa fa-list-ul"></i> Pelunasan Pembayaran Non Tunai</h5>
                                 </div>    
                                 <div class="modal-body" style="overflow-y: auto!important;">
                                    <table class="table table-hover table-striped table-bordered">
                                       <tr class="total"><td>Nama Customer </td><td><?php echo $value->NAMA_BPKB;?></td></tr>
                                       <tr><td colspan="2"><?php echo $value->ALAMAT_BPKB." ".$value->NAMA_KELURAHAN."<br>Kec. ".$value->NAMA_KECAMATAN;?></td></tr>
                                       <tr class='total'>
                                          <td class="table-nowarp">NO. TRANS</td>
                                          <td class="table-nowarp"><?php echo $value->NO_TRANS;?>
                                             <input type="hidden" id="no_trans-<?php echo $value->NO_REFF;?>" value="<?php echo $value->NO_TRANS;?>">
                                          </td>
                                       </tr>
                                       <tr>
                                          <td class="table-nowarp">Tanggal</td>
                                          <td class="table-nowarp"><?php echo tglFromSql($value->TGL_TRANS);?>
                                             <input type="hidden" id="tgl_trans-<?php echo $value->NO_REFF;?>" value="<?php echo tglFromSql($value->TGL_TRANS);?>">
                                             <input type="hidden" id="kd_dealer-<?php echo $value->NO_REFF;?>" value="<?php echo $value->KD_DEALER;?>">
                                             <input type="hidden" id="kd_piutang-<?php echo $value->NO_REFF;?>" value="<?php echo $value->KD_PIUTANG;?>">
                                          </td>
                                       </tr>
                                       <tr>
                                          <td class="table-nowarp">NO. SPK</td>
                                          <td class="table-nowarp"><?php echo $value->NO_REFF;?></td>
                                       </tr>
                                       <tr>
                                          <td class="table-nowarp">Keterangan</td>
                                          <td class="table-nowarp"><span id="keterangan-<?php echo $value->NO_REFF;?>"><?php echo str_replace("]","]<br>",$value->URAIAN_PIUTANG);?></span></td>
                                       </tr>
                                       <?php $jumlah =($value->SISA_TAGIHAN);?>
                                       <tr>
                                          <td class="table-nowarp">Jumlah Tagihan [<?php echo $value->CARA_BAYAR;?>]</td>
                                          <td class="table-nowarp">
                                             <span id="jml_tghn-<?php echo $value->NO_REFF;?>"><?php echo number_format($value->SISA_TAGIHAN,0);?></span>
                                             <span class='pull-right'><?php echo 'Rencana Bayar :'.tglFromSql($value->TGL_TEMPO);?></span>
                                             <input type="hidden" id="jatuh_tempo-<?php echo $value->NO_REFF;?>" value="<?php echo tglFromSql($value->TGL_TEMPO);?>">
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>Reff. Penerimaan Bank <span id="ldg-<?php echo $value->NO_REFF;?>" class="hidden" style="color:red"><i class="fa fa-spinner fa-spin"></i></span></td>
                                          <td>
                                             <div class="form-group">
                                                <input type="text" id="no_reff_bank-<?php echo $value->NO_REFF;?>" class="form-control" placeholder="pilih penerimaan bank">
                                             </div>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>Jumlah</td>
                                          <td>
                                             <span>
                                                <input type="text" id="jml_bank-<?php echo $value->NO_REFF;?>" class="form-control" placeholder="jumlah" data-mask='#,##0' data-mask-reverse='true' readonly="true">
                                             </span>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>Jumlah Di Bayar</td>
                                          <td>
                                             <span>
                                                <input type="text" id="jml_bayar-<?php echo $value->NO_REFF;?>" value="" class="form-control" placeholder="jumlah" data-mask='#,##0' data-mask-reverse='true'>
                                             </span>
                                          </td>
                                       </tr>
                                    </table>
                                 </div>
                                 <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
                                    <button id="submit-btn_<?php echo $value->NO_TRANS;?>:1" onclick="__simpanBayar('<?php echo $value->NO_TRANS;?>','<?php echo $value->NO_REFF;?>');" type="button" class="btn btn-danger submit-btn-<?php echo $value->NO_REFF;?> disabled-action"><i class="fa fa-save xd"></i> Simpan</button>
                                 </div>                        
                           </div>
                        </div>
                     </div>
                  <!-- </div> -->
               <!-- </div> -->
               <?php
            }
         }
      }
      ?>
  	</div>
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
      $('#nnt a.xc').click(function(){
         var idModal = $(this).data('target');
         console.log(idModal);
         $('#ldg'+idModal.replace("#","-")).removeClass("hidden");
         __loadTerimaBank(idModal.replace("#","-"));

      })
      $('.modal').on('shown.bs.modal', function () {
            $('#no_reff_bank').focus();
            //__loadTerimaBank();
      })
      $('.modal').on('hidden.bs.modal', function () {
            window.location.replace('<?php echo base_url('report/view_ar?t=');?>'+$("#tabaktif").val());
      })
      var jenis='program';
      $('#myModalLg').on("hidden.bs.modal",function(){
        var tab="2";
        switch(jenis){
          case "kupon":tab="3";break;
          case "program":tab="4";break;
          case "promo":tab="5";break;
          default: tab="2";break;
        }
          //window.location.href="<?php echo base_url('report/view_ar?t=');?>"+tab;
        })
   })
   function __reset2all(){
      document.location.href="<?php echo base_url('report/view_ar');?>";
   }
   function __loadTerimaBank(id){
      var datax=[];
      $('#ldg'+id).removeClass("hidden");
      $.getJSON("<?php echo base_url("angsuran/terimabank_list/1");?>",function(result){
         if(result.status){
            $.each(result.message,function(e,d){
               datax.push({
                  'NO_TRANS':d.NO_TRANS,
                  'KETERANGAN': d.KETERANGAN,
                  'TANGGAL': d.TANGGAL,
                  'JUMLAH':parseFloat(d.SISA_SALDO).toLocaleString(),
                  'text'   : d.NO_TRANS +' - '+d.KETERANGAN
               });
            })
            $('#ldg'+id).addClass("hidden");
         }else{
            $('#ldg'+id).addClass("hidden");
         }
         $('#no_reff_bank'+id).inputpicker({
            data : datax,
            fields: ['NO_TRANS','JUMLAH','KETERANGAN','TANGGAL'],
            fieldValue :"NO_TRANS",
            fieldText : 'text',
            filterOpen: false,
            headShow: true
         }).on("change", function(e) {
            e.preventDefault();
            var dx = datax.findIndex(obj => obj['NO_TRANS'] === $(this).val());
            if (dx > -1) {
               var jml_bayar = $("#jml_tghn"+id).html().replace(/,/g,'');
               var jml_dbyar = datax[dx]["JUMLAH"].replace(/,/g,'');
               $('#jml_bank'+id).val(parseFloat(jml_dbyar).toLocaleString());
               var kurang=(parseFloat(jml_bayar) > parseFloat(jml_dbyar));
               console.log(kurang + '='+jml_bayar +"> "+jml_dbyar);
               if(kurang){
                  var dbyr=(parseFloat(jml_dbyar).toLocaleString())
                  //$('#jmb').html(jml_dbyar);
                  $("#jml_bayar"+id).val(dbyr);
               }else{
                  $("#jml_bayar"+id).val(parseFloat(jml_bayar).toLocaleString());
               }
               $('.submit-btn'+id).removeClass("disabled-action");
            }
         })
      })
   }
   function __simpanBayar(notrans,reff){
      $("i.xd").addClass("fa fa-spinner fa-spin").removeClass("fa-save");
      $('#loadpage').removeClass("hidden");
      datax={
         'kd_maindealer':"<?php echo $this->session->userdata('user_id');?>",
         'kd_dealer':$('#kd_dealer-'+reff).val(),
         'no_trans' :reff,
         'tgl_bayar':$('#tgl_trans-'+reff).val(),
         'jumlah_bayar': $("#jml_bayar-"+reff).val(),
         'reff_bayar' : $('#no_reff_bank-'+reff).val(),
         'keterangan' : $('#keterangan-'+reff).html(),
         'sisa_bayar' : $("#jml_bayar-"+reff).val(),
         'rencana_bayar':$('#jatuh_tempo-'+reff).val(),
         'no_kwitansi' :notrans,
         'kd_piutang': $('#kd_piutang-'+reff).val(),
      }
      $.ajax({
         type :'POST',
         url : "<?php echo base_url('report/piutang_bayar/1');?>",
         data: datax,
         dataType :'json',
         //async : false,
         success : function(result){
            //console_log(result);
            if(result.status){
               //update trans_piutang status=2 ( di bayar)
               $("i.xd").removeClass("fa fa-spinner fa-spin").addClass("fa-save")
               $('.modal#'+reff).modal('hide');
               $('#loadpage').addClass("hidden");
            }
         }
      })
   }
</script>