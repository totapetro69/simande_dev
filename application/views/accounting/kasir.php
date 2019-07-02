<?php
  if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

	$nama_customer="";$nomor_transaksi="";
	$kd_customer="";$tgl_spk="";$no_spk="";
	$datamotor=array();$type_spk="";$deal_status="";
	$jenis_bayar=$this->input->get("jenis_pembayaran");
	//jika no_ref di click
	if($this->input->get("no_ref")){
		if($spk){
			if($spk->totaldata>0){
				foreach ($spk->message as $key => $value) {
					$nama_customer=$value->NAMA_CUSTOMER;
					$kd_customer=$value->KD_CUSTOMER;
					$tgl_spk=tglFromSql($value->TGL_SPK);
					$no_spk=$value->NO_SPK;
					$type_spk=$value->TYPE_PENJUALAN;
					$deal_status= $value->STATUS;
				}
			/*print_r($spk);*/
			}
		}
	}

	$no_trans="";$tgl_trans="";$noref="";$tgl_ref="";
	//ambil data setelah load simpan/print kwitansi
	if($kwt){
		if($kwt->totaldata>0){
			foreach ($kwt->message as $key => $value) {
				$no_trans 		= $value->NO_TRANS;
				$tgl_trans 		= $value->TGL_TRANS;
				$jenis_bayar 	= $value->JENIS_TRANS;
				$noref 			= $value->NO_REFF;
				$tgl_ref 		= $value->TGL_REFF;
				$kd_customer 	= $value->KD_CUSTOMER;
				$nama_customer 	= $value->NAMA_CUSTOMER;
				$nomor_transaksi= $value->NOMOR;
			}
		}
	}
	$nomorkwitansi=$this->input->get("notrans");
	$awal="";$akhir="";
	if($no_trans=='' && $nomorkwitansi==''){
		if($nomork){
			if(is_array($nomork->message)){
				foreach ($nomork->message as $key => $value) {
					$nomorkwitansi=str_pad(($value->LAST_DOCNO+1), 8,'0',STR_PAD_LEFT);
					$awal=str_pad($value->FROM_DOCNO,  8,'0',STR_PAD_LEFT);
					$akhir=str_pad($value->TO_DOCNO,  8,'0',STR_PAD_LEFT);
				}
			}
		}
	}else{
		$nomorkwitansi=($no_trans=='')?$nomorkwitansi:$no_trans;
	}
	switch ($type_spk) {
		case 'CASH':
		case 'Tunai':
			$jenis_bayar="Tunai";
			break;
		case "CREDIT":
		case "Kredit":
			$jenis_bayar="Uang Muka";
			break;
		default:
			$jenis_bayar="";
			break;
	}
	switch ($deal_status) {
		case 'Deal Indent':
		case 'Pending':
			$jenis_bayar="Titipan";
			break;
		default:
			$jenis_bayar=$jenis_bayar;
			break;
	}

?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
		<div class="bar-nav pull-right">
			<!-- <div class="btn-group"> -->
				<a class="btn btn-default hidden" id="opn" onclick="__OpenTrans('o');"><i class='fa fa-open'></i> Open Trans</a>
				<a class="btn btn-default" id="baru"><i class='fa fa-file-o'></i> Baru</a>
				<?php
					if($no_trans==''):
				?>
				<a class="btn btn-default" id="modal-button" onclick="__simpan();" role="button"  data-backdrop="static"><i class='fa fa-print'></i> Simpan</a>
				<?php else: ?>
				<a class="btn btn-default" id="modal-button" onclick='addForm("<?php echo base_url('cashier/kwitansi_print/'.urlencode(base64_encode($nomor_transaksi)).'/?'.rand()); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class='fa fa-print'></i> Print Kwitansi</a>
				<?php endif ?>
				<a class="btn btn-default " id="lsted" role="button" href="<?php echo base_url('cashier/listkasir');?>" ><i class='fa fa-list-o'></i> List Transaksi</a>
				<a class="btn btn-default hidden" id="cls" role="button" onclick="__closedTrans('c');"><i class='fa fa-close'></i> Close Trans</a>
			<!-- </div> -->
		</div>
	</div>
	<!-- <fieldset id="frmj" <?php echo ($no_trans!='')?" disabled":"";?>> -->
		<div class="col-xs-12 padding-left-right-10">
			<fieldset id="frmj" <?php echo ($no_trans!='')?" disabled":"";?>>
			    <div class="panel margin-bottom-10">
			      	<div class="panel-heading panel-custom">
			      		<div class="row">
				      		<div class="col-sm-2">
					            <h4 class="panel-title pull-left" style="padding-top: 10px;">
					              <i class='fa fa-file-o fa-fw'></i> Kasier 
					            </h4>
					        </div>
					        <div class="col-sm-8">
					          	<form id="frmH" class="form-inline" method="post">
					          		
	 					        </form>
					        </div>
					        <div class="col-sm-2">
					            <span class="tools pull-right">
					              <a class="fa fa-chevron-down" href="javascript:;"></a>
					            </span>
					        </div>
				        </div>
			        </div>
			        <div class="panel-body panel-body border">
			        	<form id="frmKasir" method="get" action="<?php echo base_url("cashier/kasir");?>">
			        		<input type="hidden" id="notrans" name="notrans" value="<?php echo $no_trans;?>">
			        		<input type="hidden" id="saldoawal" name="saldoawal" value="<?php echo $saldoAwal;?>">
			        		<input type="hidden" id="opendate" name="opendate" value="">
			        		<input type="hidden" id="closedate" name="closedate" value="">
			        		<div class="row">
			        			<div class="col-xs-12 col-md-3 col-sm-3">
			        				<div class="form-group">
					          			<label>Nomor Kwitansi</label>
							            <div class="input-group">
							            	<input type="text" class="form-control text-bold" required="true" id="nomorKwt" name="nomorKwt" value="<?php echo $nomorkwitansi;?>" required="true">
							            	 <span class="input-group-btn" id="appd" title="Setup Nomorator Kwitansi">
							            	 	<button class='btn btn-default' type='button'><i class="fa fa-cog fa-fw"></i></button>
							            	 </span>
							            </div>
							        </div>
							    </div>
						        <div class="col-xs-12 col-md-3 col-sm-3">
						        	<div class="form-group">
						        		<label>Nomor Transaksi</label>
						        		<input type='text' class='form-control text-bold' id="nomor_t" name='nomor_t' value='<?php echo $nomor_transaksi;?>' aria-describedby="addon" readonly="true">
						        	</div>
						        </div>
			        			<div class="col-xs-12 col-md-3 col-sm-3">
			        				<div class="form-group">
			        					<label>Nama Dealer</label>
			        					<select class="form-control" id="kd_dealerx" name="kd_dealer" disabled="disabled" required="true">
			        						<option value="">--Pilih Dealer--</option>
			        						<?php
			        							if($dealer){
			        								if(is_array($dealer->message)){
			        									foreach ($dealer->message as $key => $value) {
			        										$select=($this->session->userdata('kd_dealer')==$value->KD_DEALER)?"selected":"";
			        										echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
			        									}
			        								}
			        							}
			        						?>
			        					</select>
			        				</div>
			        			</div>
			        			<div class="col-xs-12 col-md-3 col-sm-3">
			        				<div class="form-group">
			        					<label>Tanggal<?php echo $type_spk;?></label>
			        					<div class="input-group append-group date">
			        						<input type="text" class="form-control" id="tgl_trans" name="tgl_trans" value="<?php echo($tgl_trans=='')? date("d/m/Y"):tglFromSql($tgl_trans);?>">
			        						 <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span> </span>
			        					</div>
			        				</div>
			        			</div>
							</div>
			        		<div class="row">
			        			<div class="hidden">
			        				<a id="modal-button" class="btn btn-primary <?php echo $status_c ?>" onclick='addForm("<?php echo base_url('chasier/add_titipan'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
						                <i class="fa fa-download"></i> Update Data
						            </a>
						        </div>
			        			<div class="col-xs-12 col-md-3 col-sm-3">
			        				<div class="form-group">
			        					<label>Jenis Transaksi <?php echo $jenis_bayar;?></label>
			        					<select class="form-control" id="jenis_pembayaran" name="jenis_pembayaran">
			        						<option>--Tentukan Jenis Transaksi--</option>
			        						<option value="Uang Muka-100.11800" <?php echo ($jenis_bayar=="Uang Muka")?"selected":"";?> >Pembayaran Uang Muka Motor</option>
			        						<option value="Tunai-100.11900" <?php echo ($jenis_bayar=="Tunai")?"selected":"";?>>Pembayaran Tunai Motor</option>
			        						<option value="Titipan-100.12000" <?php echo ($jenis_bayar=="Titipan")?"selected":"";?>>Titipan Uang</option>
			        						<option value="Pinjaman-100.12000" <?php echo ($jenis_bayar=="Titipan")?"selected":"";?>>Pinjaman</option>
			        					</select>
			        				</div>
			        			</div>
			        		<!-- </div>
			        		<div class="row"> -->
			        			<div class="col-xs-12 col-md-3 col-sm-3">
			        				<div class="form-group">
			        					<label>No. Reff</label>
			        					<select class="form-control" id="no_ref" name="no_ref" required="required">
			        						<option value="">--Pilih No. SPK--</option>
			        						<?php
			        							$nosp=($noref=='')?$this->input->get('no_ref'):$noref;
			        							if($ddspk){
			        								if(is_array($ddspk->message)){
			        									foreach ($ddspk->message as $key => $value) {
			        									  $select=($nosp==$value->SPKID)? " selected":"";
			        									  $no_spk=($nosp==$value->SPKID)?$value->NO_SPK:$no_spk;
			        										echo "<option value='".$value->SPKID."' ".$select." title='".$value->NAMA_CUSTOMER."-".$value->ALAMAT_SURAT."'>".$value->NO_SPK."</option>";
			        									}
			        								}
			        							}
			        						?>
			        					</select>
			        				</div>
			        			</div>
			        			<div class="col-xs-12 col-md-3 col-sm-3">
			        				<div class="form-group">
			        					<label>Nama Customer</label>
			        					<input type="text" class="form-control" readonly="readonly" id="nama_customer" name="nama_customer" value="<?php echo $nama_customer;?>">
			        					<input type="hidden" id="kd_customer" name="kd_customer" value="<?php echo $kd_customer;?>">
			        					<input type="hidden" id="source" name="source" value="TRANS_SPK.ID">
			        				</div>
			        			</div>
			        			<div class="col-xs-12 col-md-3 col-sm-3">
			        				<div class="form-group">
			        					<label>Tanggal SPK</label>
			        					<div class="input-group append-group date">
			        						<input type="text" class="form-control" readonly="readonly" id="tgl_spk" name="tgl_spk" value="<?php echo ($tgl_ref=='')? $tgl_spk:$tgl_ref;?>">
			        						 <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span> </span>
			        					</div>
			        				</div>
			        			</div>
			        		</div>
			        	</form>
			        </div>
			    </div>
		    </fieldset>
		</div>
		<div class="clearfix"></div>
	    <div class="col-xs-12 padding-left-right-10">
	    	<div class="panel panel-defaulet">
		    	<div class="table-responsive h250">
		    		<form id="frmList" method="post" action="">
		    			<table class="table table-striped table-bordered">
		    				<thead>
		    					<tr>
			    					<th style="width: 4%">#</th>
			    					<!-- <th style="width: 4%">&nbsp;</th> -->
			    					<th style="width: 45%">Uraian Transaksi</th>
			    					<th style="width: 8%">Jumlah</th>
			    					<th style="width: 12%">Harga</th>
			    					<th style="width: 12%">Total Harga</th>
			    				</tr>
		    				</thead>
		    				<tbody>
		    					<?php
		    					$uraianbayar="";$ketbayar="";$jumlah=0;$uang=0;$jmluang=0;
		    					$total=0;$n=0;
		    						//echo urlencode(base64_encode('00000001'));
		    						if($motor){
		    							if($motor->totaldata>0){
		    								// print_r($motor);exit();
		    								foreach ($motor->message as $key => $value) {
		    									//$n++;
		    									$uraianbayar="Pembayaran $jenis_bayar SPK No : $no_spk\n";
		    									$ketbayar .="- ".terbilang($value->JUMLAH)." Unit Motor [ $value->KD_ITEM ] $value->NAMA_ITEM\n";
		    									$jumlah +=$value->JUMLAH;
		    									switch ($jenis_bayar) {
		    										case 'Uang Muka':
		    										$uang=number_format(($value->UANG_MUKA),0);
				    									$jmluang=number_format(($value->JUMLAH*$value->HARGA_OTR),0);
		    											break;
		    										case 'Tunai':
		    										$uang=number_format(($value->HARGA_OTR),0);
		    											$jmluang=$uang;
		    											break;
		    										default:
		    											$uang="";
		    											$jmluang="";
		    											break;
		    									}
		    									// ($type_spk!="CREDIT")?$uang:$jmluang;
		    									$total +=(str_replace(',','',$jmluang));

		    									echo "<tr>
							    						<td>1</td>
							    						<td><textarea class='on-grid' id='ket_$n' name='ket_$n'>$uraianbayar$ketbayar</textarea></td>
							    						<td><input type='text' id='jml_$n' name='jml_$n' class='on-grid text-right input-number' value='".$jumlah."'></td>
							    						<td class='padding-unset'><input type='text' class='on-grid text-right input-number' id='uangmuka_$n' name='uangmuka_$n' value='".$uang."'></td>
							    						<td class='text-right' style='padding-right:5px'><span id='tot_".($n)."' name='tot_".($n)."'>".$jmluang."</span></td>
							    					</tr>";
					    					$n++;
		    									
		    								}

		    							}
		    						}
		    						$disabled_action="";		
									if($no_trans!=''){
										$disabled_action="disabled-action";
										if($kwtdtl){
											$n=0;
											if(is_array($kwtdtl->message)){
												foreach ($kwtdtl->message as $key => $value) {
													$uraianbayar = $value->URAIAN_TRANSAKSI;
													$jumlah += $value->JUMLAH;
													$uang=number_format(($value->HARGA),0);
			    									$jmluang=number_format(($value->JUMLAH*$value->HARGA),0);
			    									$jmluang=($jenis_bayar!="Tunai")?$uang:$jmluang;
			    									$total +=(str_replace(',','',$jmluang));

			    									echo "<tr>
								    						<td>".($n+1)."</td>
								    						<td><textarea class='on-grid $disabled_action' id='ket_$n' name='ket_$n'>$uraianbayar$ketbayar</textarea></td>
								    						<td><input type='text' id='jml_$n' name='jml_$n' class='on-grid text-right input-number $disabled_action' value='".$jumlah."'></td>
								    						<td class='padding-unset'><input type='text' class='on-grid text-right input-number $disabled_action' id='uangmuka_$n' name='uangmuka_$n' value='".$uang."'></td>
								    						<td class='text-right' style='padding-right:5px'><span id='tot_".($n)."' name='tot_".($n)."'>".$jmluang."</span></td>
								    					</tr>";	
							    					$n++;
												}
											}
										}
									}
									$jumlah=($jenis_bayar!="Tunai")?"1":$jumlah;
									
		    						for($x=0;$x<=(4-$n);$x++){
		    							echo "<tr>
		    									<td id='n_".($x+$n)."'>&nbsp;</td><td><input type='text' class='on-grid $disabled_action' id='ket_".($x+$n)."' name='ket_".($x+$n)."'></td>
		    									<td><input type='text' class='on-grid text-right input-number $disabled_action' id='jml_".($x+$n)."' name='jml_".($x+$n)."'></td>
		    									<td><input type='text' class='on-grid text-right input-number $disabled_action' id='uangmuka_".($x+$n)."' name='uangmuka_".($x+$n)."'></td>
		    									<td class='text-right' style='padding-right:5px'><span id='tot_".($x+$n)."' name='tot_".($x+$n)."'></span></td>
		    								  </tr>";
		    						}
		    					?>
		    					
		    				</tbody> 
		    				<tfoot>
		    					<tr class="success ">
		    						<td colspan="4" class='text-right'><b> Total</b></td>
		    						<td class='text-right' style='padding-right:5px'  id="grandtotal"><b><?php echo ($total>0)?number_format($total,0):"";?></b></td>
		    					</tr>
		    					<tr class="infor">
		    						<td colspan="5" class='text-right text-bold text-italic' id="terbilang" style='padding-right:5px; font-weight: bold; font-variant: italic'><em><?php echo ($total>0)? terbilang($total)." Rupiah":"";?></em></td>
		    					</tr>
		    				</tfoot>
		    			</table>
		    		</form>
		    	</div>
		    </div>
	    </div>
    <!-- </fieldset> -->
    <?php echo loading_proses();?>
</section>
<script type="text/javascript">
	window.onload=function(){
		if($('#no_ref').val()!=''){
			$('#uangmuka_0').focus().select();
		}
	}
</script>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/kasir.js");?>"></script>