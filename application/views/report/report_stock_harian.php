<?php //if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
  
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$dari_tanggal=($this->input->get("tgl"))?$this->input->get("tgl"):date("d/m/Y");
$sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y");
$periodelap=$dari_tanggal;
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
		<div class="bar-nav pull-right">
			<a class="btn btn-default" onclick='printKw();'><i class="fa fa-print"></i> Print Report</a>
		</div>

    </div>

    <div class="col-lg-12 padding-left-right-5 ">
        <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-list-ul fa-fw"></i> Report Stock Harian Sparepart
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
        
	        <div class="panel-body panel-body-border panel-body-10" style="display: block;">
	        	<form id="filterForms" method="GET" action="<?php echo base_url("report/stockharian_part");?>">
	        		<div class="row">
		        		<div class="col-xs-6 col-md-3 col-sm-3">
		        			<div class="form-group">
			        			<label>Nama Dealer</label>
		                        <select class="form-control" id="kd_dealer" name="kd_dealer">
		                            <option value="">--Pilih Dealer--</option>
		                            <?php
                                    $namadealer=NamaDealer($defaultDealer);
		                            if (isset($dealer)) {
		                                if ($dealer->totaldata>0) {
		                                    foreach ($dealer->message as $key => $value) {
		                                        $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                                $namadealer=($defaultDealer == $value->KD_DEALER)?NamaDealer($value->KD_DEALER):$namadealer;
		                                    }
		                                }
		                            }
		                            ?> 
		                        </select>
		                    </div>
		                </div>
		                <div class="col-xs-6 col-md-3 col-sm-3">
		                	<div class="form-group">
			                	<label>Periode Tanggal</label>
			                	<div class="input-group input-append date" id="date">
			                        <input class="form-control" id="tgl" name="tgl" value="<?php echo $dari_tanggal;?>">
			                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
			                </div>
		                </div>
                        <?php $filter=$this->input->get("filter");?>
                        <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class="form-group">
                            	<label>Filter By</label>
                            	<select id="filter" name="filter" class="form-control">
                            		<option value="TS" <?php echo ($filter=='')?'selected':'';?>>Total Stock</option>
                            		<option value="GD" <?php echo ($filter=='GD')?'selected':'';?>>Gudang</option>
                            		<option value="SIM" <?php echo ($filter=='SIM')?'selected':'';?>>SIM PART</option>
                                        <option value="SG" <?php echo ($filter=='SG')?'selected':'';?>>Sales Group (Semua)</option>
                                        <option value="SGP" <?php echo ($filter=='SGP')?'selected':'';?>>Sales Group (Part)</option>
                                        <option value="SGO" <?php echo ($filter=='SGO')?'selected':'';?>>Sales Group (Oli)</option>
                            	</select>
                            </div>
                        </div>
		                <div class="col-xs-6 col-md-3 col-sm-3">
		                	<div class="form-group">
		                		<br>
		                		<button type="button" class="btn btn-info" id="rld"><i class="fa fa-search"></i> Preview</button>
                                <button type="button" class="btn btn-default hidden" ><i class="fa fa-cogs"></i> Reload Data</button>
		                	</div>
		                </div>
		            </div>
	        	</form>
	        </div>
	    </div>
    </div>
    <div class="col-lg-12 padding-left-right-5 ">
    	<div class="panel panel-default">
    		<div class="table-responsive">
    			<table class="table table-hover table-striped table-bordered"  id="lsh">
    				<thead>
    					<tr>
    						<th>No</th>
    						<th>Part Number</th>
    						<th>Deskripsi</th>
    						<th>Qty Stock</th>
    						<th>Harga Jual (Rp)</th>
    						<th>Harga Pokok (Rp)</th>
    						<th>Ammount by Harga Pokok (Rp)</th>
    						<th>Type Motor</th>
    					</tr>
    				</thead>
    				<tbody>
    					<?php
    					$n=0;
                        switch($filter){
                            case "GD":
                                if(isset($lokasigd)){
                                    if($lokasigd->totaldata >0){
                                        foreach ($lokasigd->message as $key => $value) {
                                            $n++;
                                            ?>
                                                <tr class="info">
                                                    <td class='text-center'><?php echo $n;?></td>
                                                    <td colspan="2"><?php echo $value->KD_LOKASI;?> <i class="fa fa-arrow-right"></i> <?php echo $value->KD_GUDANG;?></td>
                                                    <td colspan="5">&nbsp;</td>
                                                </tr>
                                            <?php
                                            if(isset($inlokasi)){ 
                                                $x=($this->input->get('page'))?$this->input->get('page'):0;
                                                if(isset($inlokasi[$value->KD_GUDANG])){
                                                    if($inlokasi[$value->KD_GUDANG]->totaldata>0){
                                                        foreach ($inlokasi[$value->KD_GUDANG]->message as $key => $val) {
                                                            $x++;
                                                        ?>  
                                                            <tr>
                                                                <td class='text-right'><?php echo $x;?></td>
                                                                <td class='table-nowarp'><?php echo $val->PART_NUMBER;?></td>
                                                                <td class='table-nowarp'><?php echo $val->PART_DESKRIPSI;?></td>
                                                                <td class='text-right'><?php echo number_format($val->JUMLAH_SAK);?></td>
                                                                <td class='text-right'><?php echo number_format($val->HARGA_JUAL);?></td>
                                                                <td class='text-right'><?php echo number_format($val->HARGA_BELI);?></td>
                                                                <td class='text-right'><?php echo number_format(($val->JUMLAH_SAK *$val->HARGA_BELI));?></td>
                                                                <td class='table-nowarp'><?php echo $val->TYPE_MOTOR;?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                                
                                            <?php
                                        }
                                    }
                                }
                                break;
                            case "SIM":
                            $n=($this->input->get('page'))?$this->input->get('page'):0;
                                if(isset($list)){
                                    if($list->totaldata>0){
                                        foreach ($list->message as $key => $value) {
                                            
                                            $sim=isSIMParts($value->PART_NUMBER,$value->KD_DEALER);
                                                $n++;
                                                ?>
                                                <tr>
                                                    <td class='text-center'><?php echo $n;?></td>
                                                    <td class='table-nowarp'><?php echo $value->PART_NUMBER;?></td>
                                                    <td class='table-nowarp'><?php echo $value->PART_DESKRIPSI;?><?php echo ($sim)?'<abbr title="Standart Item Minimun Qty"><span class="warning pull-right">&nbsp;'. isSIMParts($value->PART_NUMBER,$value->KD_DEALER).'&nbsp;</span></abbr>':'';"";?> </td>
                                                    <td class='text-right'><?php echo number_format($value->JUMLAH_SAK);?></td>
                                                    <td class='text-right'><?php echo number_format($value->HARGA_JUAL);?></td>
                                                    <td class='text-right'><?php echo number_format($value->HARGA_BELI);?></td>
                                                    <td class='text-right'><?php echo number_format(($value->JUMLAH_SAK *$value->HARGA_BELI));?></td>
                                                    <td class='table-nowarp'><?php echo $value->TYPE_MOTOR;?></td>
                                                </tr>
                                                <?php
                                            //}
                                        }
                                    }
                                }
                                break;
                            case "SG":
                                if(isset($lokasigd)){
                                    if($lokasigd->totaldata >0){
                                        foreach ($lokasigd->message as $key => $value) {
                                            $n++;
                                            ?>
                                                <tr class="info">
                                                    <td class='text-center'><?php echo $n;?></td>
                                                    <td colspan="2"><?php echo $value->KD_GROUPSALES;?> </td>
                                                    <td colspan="5">&nbsp;</td>
                                                </tr>
                                            <?php
                                            if(isset($inlokasi)){ $x=($this->input->get('page'))?$this->input->get('page'):0;
                                                if(isset($inlokasi[$value->KD_GROUPSALES])){
                                                    if($inlokasi[$value->KD_GROUPSALES]->totaldata>0){
                                                        foreach ($inlokasi[$value->KD_GROUPSALES]->message as $key => $val) {
                                                            $x++;
                                                        ?>  
                                                            <tr>
                                                                <td class='text-right'><?php echo $x;?></td>
                                                                <td class='table-nowarp'><?php echo $val->PART_NUMBER;?></td>
                                                                <td class='table-nowarp'><?php echo $val->PART_DESKRIPSI;?></td>
                                                                <td class='text-right'><?php echo number_format($val->JUMLAH_SAK);?></td>
                                                                <td class='text-right'><?php echo number_format($val->HARGA_JUAL);?></td>
                                                                <td class='text-right'><?php echo number_format($val->HARGA_BELI);?></td>
                                                                <td class='text-right'><?php echo number_format(($val->JUMLAH_SAK *$val->HARGA_BELI));?></td>
                                                                <td class='table-nowarp'><?php echo $val->TYPE_MOTOR;?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                                
                                            <?php
                                        }
                                    }
                                }
                            break;
                            case "SGO":
                                if(isset($lokasigd)){
                                    if($lokasigd->totaldata >0){
                                        foreach ($lokasigd->message as $key => $value) {
                                            $n++;
                                            ?>
                                                <tr class="info">
                                                    <td class='text-center'><?php echo $n;?></td>
                                                    <td colspan="2"><?php echo $value->KD_GROUPSALES;?> </td>
                                                    <td colspan="5">&nbsp;</td>
                                                </tr>
                                            <?php
                                            if(isset($inlokasi)){ $x=($this->input->get('page'))?$this->input->get('page'):0;
                                                if(isset($inlokasi[$value->KD_GROUPSALES])){
                                                    if($inlokasi[$value->KD_GROUPSALES]->totaldata>0){
                                                        foreach ($inlokasi[$value->KD_GROUPSALES]->message as $key => $val) {
                                                            $x++;
                                                        ?>  
                                                            <tr>
                                                                <td class='text-right'><?php echo $x;?></td>
                                                                <td class='table-nowarp'><?php echo $val->PART_NUMBER;?></td>
                                                                <td class='table-nowarp'><?php echo $val->PART_DESKRIPSI;?></td>
                                                                <td class='text-right'><?php echo number_format($val->JUMLAH_SAK);?></td>
                                                                <td class='text-right'><?php echo number_format($val->HARGA_JUAL);?></td>
                                                                <td class='text-right'><?php echo number_format($val->HARGA_BELI);?></td>
                                                                <td class='text-right'><?php echo number_format(($val->JUMLAH_SAK *$val->HARGA_BELI));?></td>
                                                                <td class='table-nowarp'><?php echo $val->TYPE_MOTOR;?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                                
                                            <?php
                                        }
                                    }
                                }
                            break;
                            case "SGP":
                                if(isset($lokasigd)){
                                    if($lokasigd->totaldata >0){
                                        foreach ($lokasigd->message as $key => $value) {
                                            $n++;
                                            ?>
                                                <tr class="info">
                                                    <td class='text-center'><?php echo $n;?></td>
                                                    <td colspan="2"><?php echo $value->KD_GROUPSALES;?> </td>
                                                    <td colspan="5">&nbsp;</td>
                                                </tr>
                                            <?php
                                            if(isset($inlokasi)){ $x=($this->input->get('page'))?$this->input->get('page'):0;
                                                if(isset($inlokasi[$value->KD_GROUPSALES])){
                                                    if($inlokasi[$value->KD_GROUPSALES]->totaldata>0){
                                                        foreach ($inlokasi[$value->KD_GROUPSALES]->message as $key => $val) {
                                                            $x++;
                                                        ?>  
                                                            <tr>
                                                                <td class='text-right'><?php echo $x;?></td>
                                                                <td class='table-nowarp'><?php echo $val->PART_NUMBER;?></td>
                                                                <td class='table-nowarp'><?php echo $val->PART_DESKRIPSI;?></td>
                                                                <td class='text-right'><?php echo number_format($val->JUMLAH_SAK);?></td>
                                                                <td class='text-right'><?php echo number_format($val->HARGA_JUAL);?></td>
                                                                <td class='text-right'><?php echo number_format($val->HARGA_BELI);?></td>
                                                                <td class='text-right'><?php echo number_format(($val->JUMLAH_SAK *$val->HARGA_BELI));?></td>
                                                                <td class='table-nowarp'><?php echo $val->TYPE_MOTOR;?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                                
                                            <?php
                                        }
                                    }
                                }
                            break;
                            default:
                                if(isset($list)){
                                    $n=($this->input->get('page'))?$this->input->get('page'):0;
        							if($list->totaldata>0){
        								foreach ($list->message as $key => $value) {
        									$n++;
        									?>
        									<tr>
        										<td class='text-center'><?php echo $n;?></td>
        										<td class="table-nowarp"><?php echo $value->PART_NUMBER;?></td>
        										<td class="table-nowarp"><?php echo $value->PART_DESKRIPSI;?></td>
        										<td class='text-right'><?php echo number_format($value->JUMLAH_SAK);?></td>
        										<td class='text-right'><?php echo number_format($value->HARGA_JUAL);?></td>
        										<td class='text-right'><?php echo number_format($value->HARGA_BELI);?></td>
        										<td class='text-right'><?php echo number_format(($value->JUMLAH_SAK *$value->HARGA_BELI));?></td>
        										<td class='table-nowarp'><?php echo $value->TYPE_MOTOR;?></td>
        									</tr>
        									<?php
        								}
        							}else{
                                        echo belumAdaData(8);
                                    }
        						}
                                break;
                        }
    					?>
    				</tbody>
    			</table>
    		</div>
    		<footer class="panel-footer">
                <div class="row">
                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo ($filter=='SIM')?"<span class='warning'>&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp; Standart Item Minimum Quantity":"";?>&nbsp;
                            <?php echo (isset($totaldata)) ? ($totaldata == '0' ? "" : "<i>Total Data " . $totaldata . " items</i>") : '' ?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo isset($pagination)?$pagination:""; ?>
                    </div>
                </div>
            </footer>
    	</div>
    </div>
    
    <div id="printarea" style="height: 0.5px; overflow: hidden; width:100%">
        <table style="width:100%; border-collapse: collapse;">
            <tr>
                <td style="padding-right: 5px">
                    <table style="width:100%; border-collapse: collapse;">
                        <tr>
                            <td style="width:10%;" valign="top"><h4><?php echo $namadealer;?></h4></td>
                            <td style="width:40%" align="center" valign="middle"><h4>LAPORAN STOCK HARIAN</h4></td>

                            <td style="width:15%; white-space: nowrap;" valign="top">Tanggal Cetak </td>
                            <td style="width:15%; white-space: nowrap;" valign="top">: <?php echo date('d/m/Y');?></td>
                        </tr>
                        <tr><td></td><td align="center" valign="middle">
                                <?php switch ($this->input->get("filter")) {
                                    case 'GD':
                                       echo "Group by Gudang";
                                        break;
                                    case 'SIM':
                                       echo "Standart Item Minimum";
                                        break;
                                    case 'SG':
                                       echo "Group By Sales Group";
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                                ?>
                            </td>
                                <td style="width:15%; white-space: nowrap;" valign="top">Periode </td>
                            <td style="width:15%; white-space: nowrap;" valign="top">: <?php echo $periodelap;?></td>
                        </tr>
                        <tr><td colspan="4">&nbsp;</td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width:100%">
                    <table style="border-collapse: collapse; width: 100%" border="1">
                        <thead>
                            <tr>
                                <th style="width:4%">No</th>
                                <th style="width:8%">Part Number</th>
                                <th style="width:16%;">Deskripsi</th>
                                <th style="width:10%">Qty Stock</th>
                                <th style="width:10%">Harga Jual (Rp)</th>
                                <th style="width:10%">Harga Pokok (Rp)</th>
                                <th style="width:12%">Ammount by Harga Pokok (Rp)</th>
                                <th style="width:40%">Type Motor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $n=0;
                            switch($filter){
                                case "GD":
                                if(isset($lokasigd)){
                                    if($lokasigd->totaldata >0){
                                        foreach ($lokasigd->message as $key => $value) {
                                            $n++;
                                            ?>
                                                <tr class="info">
                                                    <td class='text-center'><?php echo $n;?></td>
                                                    <td colspan="2"><?php echo $value->KD_LOKASI;?> <i class="fa fa-arrow-right"></i> <?php echo $value->KD_GUDANG;?></td>
                                                    <td colspan="5">&nbsp;</td>
                                                </tr>
                                            <?php
                                            if(isset($inlokasi)){ $x=0;
                                                if(isset($inlokasi[$value->KD_GUDANG])){
                                                    if($inlokasi[$value->KD_GUDANG]->totaldata>0){
                                                        foreach ($inlokasi[$value->KD_GUDANG]->message as $key => $val) {
                                                            $x++;
                                                        ?>  
                                                            <tr>
                                                                <td align='right' style='padding-right:5px'><?php echo $x;?></td>
                                                                <td style="white-space: nowrap;"><?php echo $val->PART_NUMBER;?></td>
                                                                <td style="white-space: nowrap;"><?php echo $val->PART_DESKRIPSI;?></td>
                                                                <td align='right' style='padding-right:5px'><?php echo number_format($val->JUMLAH_SAK);?></td>
                                                                <td align='right' style='padding-right:5px'><?php echo number_format($val->HARGA_JUAL);?></td>
                                                                <td align='right' style='padding-right:5px'><?php echo number_format($val->HARGA_BELI);?></td>
                                                                <td align='right' style='padding-right:5px'><?php echo number_format(($val->JUMLAH_SAK *$val->HARGA_BELI));?></td>
                                                                <td><?php echo $val->TYPE_MOTOR;?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                                
                                            <?php
                                        }
                                    }
                                }
                                break;
                                case "SIM":
                                if(isset($list)){
                                    if($list->totaldata>0){
                                        foreach ($list->message as $key => $value) {
                                            $sim=isSIMParts($value->PART_NUMBER,$value->KD_DEALER);
                                                $n++;
                                                
                                                ?>
                                                <tr>
                                                    <td align="center"><?php echo $n;?></td>
                                                    <td style="white-space: nowrap;"><?php echo $value->PART_NUMBER;?></td>
                                                    <td style="white-space: nowrap;"><?php echo $value->PART_DESKRIPSI;?><?php echo ($sim)?'<abbr title="Standart Item Minimun Qty"><span class="warning pull-right">&nbsp;'. isSIMParts($value->PART_NUMBER,$value->KD_DEALER).'&nbsp;</span></abbr>':'';"";?></td>
                                                    <td align='right' style='padding-right:5px'><?php echo number_format($value->JUMLAH_SAK);?></td>
                                                    <td align='right' style='padding-right:5px'><?php echo number_format($value->HARGA_JUAL);?></td>
                                                    <td align='right' style='padding-right:5px'><?php echo number_format($value->HARGA_BELI);?></td>
                                                    <td align='right' style='padding-right:5px'><?php echo number_format(($value->JUMLAH_SAK *$value->HARGA_BELI));?></td>
                                                    <td><?php echo $value->TYPE_MOTOR;?></td>
                                                </tr>
                                                <?php
                                            //}
                                        }
                                    }
                                }
                                break;
                                case "SG":
                                if(isset($lokasigd)){
                                    if($lokasigd->totaldata >0){
                                        foreach ($lokasigd->message as $key => $value) {
                                            $n++;
                                            ?>
                                                <tr class="info">
                                                    <td align="center"><?php echo $n;?></td>
                                                    <td colspan="2"><?php echo $value->KD_GROUPSALES;?> </td>
                                                    <td colspan="5">&nbsp;</td>
                                                </tr>
                                            <?php
                                            if(isset($inlokasi)){ $x=0;
                                                if(isset($inlokasi[$value->KD_GROUPSALES])){
                                                    if($inlokasi[$value->KD_GROUPSALES]->totaldata>0){
                                                        foreach ($inlokasi[$value->KD_GROUPSALES]->message as $key => $val) {
                                                            $x++;
                                                        ?>  
                                                            <tr>
                                                                <td align='right' style='padding-right:5px'><?php echo $x;?></td>
                                                                <td style="white-space: nowrap;"><?php echo $val->PART_NUMBER;?></td>
                                                                <td style="white-space: nowrap;"><?php echo $val->PART_DESKRIPSI;?></td>
                                                                <td align='right' style='padding-right:5px'><?php echo number_format($val->JUMLAH_SAK);?></td>
                                                                <td align='right' style='padding-right:5px'><?php echo number_format($val->HARGA_JUAL);?></td>
                                                                <td align='right' style='padding-right:5px'><?php echo number_format($val->HARGA_BELI);?></td>
                                                                <td align='right' style='padding-right:5px'><?php echo number_format(($val->JUMLAH_SAK *$val->HARGA_BELI));?></td>
                                                                <td><?php echo $val->TYPE_MOTOR;?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                                
                                            <?php
                                        }
                                    }
                                }
                                break;
                                default:

                                if(isset($list)){
                                    if($list->totaldata>0){
                                        foreach ($list->message as $key => $value) {
                                            $n++;
                                            ?>
                                            <tr>
                                                <td align="center"><?php echo $n;?></td>
                                                <td style="white-space: nowrap;"><?php echo $value->PART_NUMBER;?></td>
                                                <td style="white-space: nowrap;"><?php echo $value->PART_DESKRIPSI;?></td>
                                                <td align='right' style='padding-right:5px'><?php echo number_format($value->JUMLAH_SAK);?></td>
                                                <td align='right' style='padding-right:5px'><?php echo number_format($value->HARGA_JUAL);?></td>
                                                <td align='right' style='padding-right:5px'><?php echo number_format($value->HARGA_BELI);?></td>
                                                <td align='right' style='padding-right:5px'><?php echo number_format(($value->JUMLAH_SAK *$value->HARGA_BELI));?></td>
                                                <td><?php echo $value->TYPE_MOTOR;?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                                break;
                            }
                            ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</section>
<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>

<script type="text/javascript">
    var path = window.location.pathname.split('/');
    var http = window.location.origin + '/' + path[1];
    $(document).ready(function(){
        $('#filter').on("change",function(){
            $('#filterForms').submit();
        })
        var filter="<?php echo $this->input->get("filter");?>"
        if(!filter){
            __generate_stock();
        }
        $('#tgl').datepicker({
            onClose: function(d,i){
                $(this).change();
            }
        })
        $('#tgl').change(function(e){
            e.preventDefault();
            //__generate_stock();
        })
        $('#kd_dealer').on('change',function(){
            if($(this).val()){
                __generate_stock();
                return;
            }
            
        })
        $('#rld').click(function(){
            __generate_stock();
        })
    })
    function __reload(){
        __generate_stock();
    }
    function printKw() {
        printJS({ printable: 'printarea', type: 'html', honorColor: true });
        //$('#keluar').click();
    }
    function __generate_stock(){
      $('#lsh tbody').html("<tr><td>&nbsp;</td><td colspan='7'><i class='fa fa-spinner fa-spin'></i> generate stock process , please wait...</td></tr>");
      /*$.getJSON(http+"/part/parts4gen",{'d':'1', 's':'d','tgl': $('#tgl').val()
        },function(result){*/
         $('#filterForms').submit();
      // });
   }
</script>