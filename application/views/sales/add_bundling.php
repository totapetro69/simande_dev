<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$status_ce = (isBolehAkses('c') || isBolehAkses('e'))? '' : 'disabled-action' ; 

$kd_dealer=$this->session->userdata("kd_dealer");
$start_date=date("d/m/Y");$end_date=date('d/m/Y',strtotime('Last day of next month'));
$kd_bundling=$this->input->get('kd_bundling');$nama_bundling="";$status_bundling="0";
$kd_typemotor="";$kd_warna="";$nama_typemotor="";$ket_warna="";$sparepart=array();$apparel=array();$aksesoris=array();
if($bundling){
	if($bundling->totaldata>0){
		foreach ($bundling->message as $key => $value) {
			$kd_dealer=$value->KD_DEALER;
			$start_date=tglFromSql($value->START_DATE);
			$end_date=tglFromSql($value->END_DATE);
			$kd_bundling=$value->KD_BUNDLING;
			$nama_bundling=$value->NAMA_BUNDLING;
			$kd_typemotor=$value->KD_TYPEMOTOR;
			$kd_warna=$value->KD_WARNA;
			$sparepart[$value->GROUP_BUNDLING][]=array(
						'kd_item' =>$value->KD_ITEM,
						'nama_item'=>$value->NAMA_ITEM,
						'group_bundling'=>$value->GROUP_BUNDLING,
						'jumlah' => $value->JUMLAH,
						'keterangan'=>$value->KETERANGAN,
						'detailid' =>$value->DETAILID
					);
			
			$status_bundling=$value->STATUS_BUNDLING;
		}
	}
	
}
$statusbnd=($status_bundling=="0")?"":" disabled-action";
$statusbndls=($status_bundling=="0")?"":" hidden";
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
		<div class="bar-nav pull-right">
			<a class="btn btn-default" role="button" href="<?php echo base_url("motor/add_bundling");?>"><i class="fa fa-file-o fa-fw"></i> Baru</a>
			<a class="btn btn-default <?php echo $statusbnd ." ".$status_ce;?> " role="button" id="btn-simpan"><i class="fa fa-save fa-fw"></i> Simpan</a>
			<a class="btn btn-default" role="button" href="<?php echo base_url("motor/bundling");?>"><i class="fa fa-list fa-fw"></i> List Bundling</a>
		</div>
	</div>
	<div class="col-xs-12 padding-left-right-10">
		<fieldset <?php echo ($status_bundling=="0")?"":"disabled";?>>
			<form id="formBundlinge" method="post" action="<?php echo base_url('motor/add_bundling_simpan');?>">
				<div class="panel margin-bottom-10">
					<div class="panel-heading">
						<i class="fa fa-cog"></i> Bundling Program
						<span class="pull-right">
			              <a class="fa fa-chevron-down" href="javascript:;"></a>
			            </span>
			        </div>
			        <div class="panel-body panel-body-border">
			        	<div class="row">
			        		<div class="col-sm-6">
			        			<div class="col-xs-12">
				        			<div class="form-group">
				        				<label>Nama Dealer <?php //echo $kd_dealer;?></label>
				        					<select class="form-control" id="kd_dealer" name="kd_dealer" disabled="disabled">
				        						<option value="0">--Pilih Dealer--</option>
				        						<?php
				        							if($dealer){
				        								if(is_array($dealer->message)){
				        									foreach ($dealer->message as $key => $value) {
				        										$select=($kd_dealer==$value->KD_DEALER)?"selected":"";
				        										echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
				        									}
				        								}
				        							}
				        						?>
				        					</select>
				        			</div>
				        		</div>
				        		<?php //echo var_dump($bundling);?>
			        			<div class="col-sm-4">
				        			<div class="form-group">
				        				<label>Kode Bundling</label>
				        				<input type="text" id="kd_bundling" name="kd_bundling" class="form-control text-upper" placeholder="Autogenerate" readonly="readonly" value="<?php echo $kd_bundling;?>" >
				        			</div>
				        		</div>
				        		<div class="col-sm-8">
				        			<div class="form-group">
				        				<label>Nama Bundling</label>
				        				<input type="text" id="nama_bundling" name="nama_bundling" placeholder="Nama Bundling" class="form-control" required value="<?php echo $nama_bundling;?>">
				        			</div>
				        		</div>
			        		</div>
			        		<div class="col-sm-6">
			        			<div class="row">
			        				<div class="col-sm-6">
			        					<div class="form-group">
			        						<label>Tanggal Mulai</label>
			        						<div class="input-group input-append date" id="date">
				        						<input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo $start_date;?>" placeholder="dd/mm/yyyy" required="required" />
				   								<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
				   							</div>
			   							</div>
			   						</div>
			   						<div class="col-sm-6">
			        					<div class="form-group">
			        						<label>Tanggal Selesai</label>
			        						<div class="input-group input-append date" id="datex">
				        						<input type="text" class="form-control" id="end_date" name="end_date" value="<?php echo $end_date;?>" placeholder="dd/mm/yyyy" required="required" />
				   								<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
				   							</div>
			   							</div>
			   						</div>
			   						<div class="col-sm-6">
			   							<div class="form-group">
						        			<label>Type Motor</label>
						        			<?php echo DropDownMotor(true,$kd_typemotor);?>
						        		</div>
						        	</div>
						        	<div class="col-sm-6">
			   							<div class="form-group">
						        			<label>Warna Motor</label>
						        			<?php echo DropDownWarnaMotor($kd_warna);?>
						        		</div>
						        	</div>
			   					</div>
			   				</div>
			        	</div>
			        </div>	
			    </div>
			    <div class="panel margin-bottom-10">
			    	<div class="panel-heading">
			    		<i class="fa fa-list"></i> Item Bundling
			    		<span class="tools pull-right">
			              <a class="fa fa-chevron-down" href="javascript:;"></a>
			            </span>
			    	</div>
			    	<div class="panel-body panel-body-border">
		    			<table class="table table-stripped table-bordered tablex" id="bundling_sp">
		    				<thead>
		    					<tr class="thead-alias-tr">
		    						<th colspan="6"><i class="fa fa-list"></i> Bundling Sparepart</th>
		    					</tr>
		    					<tr>
		    						<th style="width:5%">#</th>
		    						<th style="width:12%">Kode Sparepart</th>
		    						<th style="width:28%">Nama Sparepart</th>
		    						<th style="width:8%">Jumlah</th>
		    						<th>Keterangan</th>
		    						<th style="width:5%"></th>
		    					</tr>
		    				</thead>
		    				<tbody>
		    					<tr class="thead-alias-tr <?php echo $statusbndls;?>" id="lst_Sparepart">
		    						<td></td>
		    						<td><input type="text" id="kd_sparepart" name="kd_sparepart" readonly="readonly" placeholder="Kode Sparepart" class="form-control on-grid"></td>
		    						<td><input type="text" id="nama_sparepart" name="nama_sparepart" placeholder="Nama Sparepart" class="form-control on-grid"></td>
		    						<td><input type="text" id="jumlah_sparepart" name="jumlah_sparepart" placeholder="Jumlah Sparepart" class="form-control on-grid text-right" value="1"></td>
		    						<td><input type="text" id="ket_bundling" name="ket_bundling" placeholder="Keterangan Bundling" class="form-control on-grid"></td>
		    						<td class="text-center"><a id="sp" class="btn btn-default btn-sm disabled-action" onclick="add_item('sp');"><i class="fa fa-plus fa-fw text-primary"></i></a></td>
		    					</tr>
		    					<?php 
		    						if($sparepart){
		    							if(isset($sparepart["Sparepart"])){
			    							for($n=0;$n< count($sparepart["Sparepart"]);$n++){
			    								echo "<tr>
			    									<td class='text-center'>".($n+1)."</td>
			    									<td class='text-center'>".$sparepart["Sparepart"][$n]["kd_item"]."</td>
			    									<td>".$sparepart["Sparepart"][$n]["nama_item"]."</td>
			    									<td class='text-right'>".$sparepart["Sparepart"][$n]["jumlah"]."</td>
			    									<td>".$sparepart["Sparepart"][$n]["keterangan"]."</td>
			    									<td class='text-center $statusbnd $status_e'><a onclick=\"_hapus('".$sparepart["Sparepart"][$n]["detailid"]."','".$kd_bundling."');\"><i class='fa fa-trash'></i></a></td>
			    								</tr>";
			    							}
			    						}
		    						}
		    					?>
		    				</tbody>
		    			</table>
		    			<hr>
		    			<div class="clearfix"></div>
				    	<table class="table table-stripped table-bordered tablex" id="bundling_apparel">
				    		<thead>
				    			<tr class="thead-alias-tr">
		    						<th colspan="6"><i class="fa fa-list"></i> Bundling Apparel</th>
		    					</tr>
		    					<tr>
		    						<th style="width:5%">#</th>
		    						<th style="width:12%">Kode Apparel</th>
		    						<th style="width:28%">Nama Apparel</th>
		    						<th style="width:8%">Jumlah</th>
		    						<th>Keterangan</th>
		    						<th style="width:5%"></th>
		    					</tr>
		    				</thead>
		    				<tbody>
		    					<tr class="thead-alias-tr <?php echo $statusbndls;?>" id="lst_Apparel">
		    						<td></td>
		    						<td><input type="text" id="kd_aparel" name="kd_aparel" readonly="readonly" placeholder="Kode Apparel" class="form-control on-grid"></td>
		    						<td><input type="text" id="nama_aparel" name="nama_aparel" placeholder="Nama Apparel" class="form-control on-grid"></td>
		    						<td><input type="text" id="jumlah_aparel" name="jumlah_aparel" placeholder="Jumlah Apparel" class="form-control on-grid text-right" value="1"></td>
		    						<td><input type="text" id="ket_aparel" name="ket_aparel" placeholder="Keterangan Bundling" class="form-control on-grid"></td>
		    						<td class="text-center"><a id="ap" class="btn btn-default btn-sm disabled-action" onclick="add_item('ap');"><i class="fa fa-plus fa-fw text-primary"></i></a></td>
		    					</tr>
		    					<?php 
		    						if($sparepart){
		    							if(isset($sparepart["Apparel"])){
			    							for($n=0;$n< count($sparepart["Apparel"]);$n++){
			    								echo "<tr>
			    									<td class='text-center'>".($n+1)."</td>
			    									<td class='text-center'>".$sparepart["Apparel"][$n]["kd_item"]."</td>
			    									<td>".$sparepart["Apparel"][$n]["nama_item"]."</td>
			    									<td class='text-right'>".$sparepart["Apparel"][$n]["jumlah"]."</td>
			    									<td>".$sparepart["Apparel"][$n]["keterangan"]."</td>
			    									<td class='text-center $statusbnd $status_e'><a onclick=\"_hapus('".$sparepart["Apparel"][$n]["detailid"]."','".$kd_bundling."');\"><i class='fa fa-trash'></i></a></td>
			    								</tr>";
			    							}
			    						}
		    						}
		    					?>
		    				</tbody>
				    	</table>
				    	<table class="table table-stripped table-bordered tablex" id="bundling_aksesoris">
				    		<thead>
				    			<tr class="thead-alias-tr">
		    						<th colspan="6"><i class="fa fa-list"></i> Bundling Aksesoris</th>
		    					</tr>
		    					<tr>
		    						<th style="width:5%">#</th>
		    						<th style="width:12%">Kode Aksesoris</th>
		    						<th style="width:28%">Nama Aksesoris</th>
		    						<th style="width:8%">Jumlah</th>
		    						<th>Keterangan</th>
		    						<th style="width:5%"></th>
		    					</tr>
		    					
		    				</thead>
		    				<tbody>
		    					<tr class="thead-alias-tr <?php echo $statusbndls;?>" id="lst_Aksesoris">
		    						<td></td>
		    						<td><input type="text" id="kd_aksesoris" name="kd_aksesoris" readonly="readonly" placeholder="Kode Aksesoris" class="form-control on-grid"></td>
		    						<td><input type="text" id="nama_aksesoris" name="nama_aksesoris" placeholder="Nama Aksesoris" class="form-control on-grid"></td>
		    						<td><input type="text" id="jumlah_aksesoris" name="jumlah_aksesoris" placeholder="Jumlah Aksesoris" class="form-control on-grid text-right" value="1"></td>
		    						<td><input type="text" id="ket_aksesoris" name="ket_aksesoris" placeholder="Keterangan Bundling" class="form-control on-grid"></td>
		    						<td class="text-center"><a id="hd" class="btn btn-default btn-sm disabled-action" onclick="add_item('hd');"><i class="fa fa-plus fa-fw text-primary"></i></a></td>
		    					</tr>
		    					<?php 
		    						if($sparepart){
		    							if(isset($sparepart["Aksesoris"])){
			    							for($n=0;$n< count($sparepart["Aksesoris"]);$n++){
			    								echo "<tr>
			    									<td class='text-center'>".($n+1)."</td>
			    									<td class='text-center'>".$sparepart["Aksesoris"][$n]["kd_item"]."</td>
			    									<td>".$sparepart["Aksesoris"][$n]["nama_item"]."</td>
			    									<td class='text-right'>".$sparepart["Aksesoris"][$n]["jumlah"]."</td>
			    									<td>".$sparepart["Aksesoris"][$n]["keterangan"]."</td>
			    									<td class='text-center $statusbnd $status_e'><a onclick=\"_hapus('".$sparepart["Aksesoris"][$n]["detailid"]."','".$kd_bundling."');\"><i class='fa fa-trash'></i></a></td>
			    								</tr>";
			    							}
			    						}
		    						}
		    					?>
		    				</tbody>
				    	</table>
			    	</div>
			    </div>
			</form>
		</fieldset>
	</div>
<?php echo loading_proses();?>
</section>
<script type="text/javascript" src="<?php echo base_url("assets/js/handlebars.js");?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/add_bundling.js");?>"></script>
