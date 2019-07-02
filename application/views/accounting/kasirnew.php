<?php
  	if (!isBolehAkses()) {
      	redirect(base_url() . 'auth/error_auth');
  	}

  	$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  	$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  	$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  	$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  	$defaultDealer=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
  	$no_trans="";$tgl_trans="";$noref="";$tgl_ref="";
  	$saldo_awal=(isset($saldoAwal))?$saldoAwal:0;
  	$saldo_awal=(isset($saldoAkhir))?$saldoAkhir:$saldoAwal;

  	$tp_transaksi=$this->input->get('tp');$jenis_transaksi="";$no_trans=base64_decode(urldecode($this->input->get("n")));
  	$kd_akun="";$nama_akun="";$no_reff="";$ket_reff="";$carabayar="";$posting="";$jml_bayar=0;
  	$lkh_status=($this->input->get('lk'))?$this->input->get('lk'):0;;
  	$pic_reff_jp="";
  	/**
  	 * load header transaksi
  	 */
  	if(isset($trans)){
	  	if($trans){
	  		if($trans->totaldata > 0){
	  			foreach ($trans->message as $key => $value) {
	  				$tp_transaksi = $value->TYPE_TRANS;
	  				$jenis_transaksi = $value->JENIS_TRANS;
	  				$tgl_trans = ($value->TGL_TRANS);
	  				$no_reff = $value->NO_REFF;
	  				$ket_reff = $value->KET_REFF;
	  				$posting = $value->POSTING_STATUS;
	  			}
	  		}
	  	}
	  	$nama_pengurus="";
	  	/**
	  	 * Load transaksi detail
	  	 */
	  	if($transd){
	  		if($transd->totaldata >0){
	  			foreach ($transd->message as $key => $value) {
	  				$kd_akun = $value->KD_ACCOUNT;
	  				$nama_akun =explode(":", $value->KETERANGAN)[0];
	  				$jml_bayar +=($value->JUMLAH * $value->HARGA);
	  				$lkh_status = ($value->LKH)?$value->LKH:0;
	  			}
	  		}
	  	}
  	}
  	$status_posting=($posting=="1")?"disabled-action":"";
  	$print_nota ='hidden';
  	$print_kwitansi='hidden';
  	/**
  	 * Jenis kwitansi yang di gunakan
  	 * dalam bentuk nota atau dalam format kwitansi biasa
  	 */
  	switch ($jenis_transaksi) {
  		case 'Penjualan Sparepart':
  		case "Pengeluaran Barang":
		case "Penjualan Apparel":
		case "Penjualan Aksesoris":
		case "Service":
  			$print_nota =($no_trans)?'':'disabled-action';
  			$print_kwitansi='hidden';
  		break;
  		default:
  			$print_nota ='hidden';
  			$print_kwitansi='';
  		break;
  	}
	$cb="";$no_rekening="";$no_cek="";$nama_bank="";$tgl_jthtempo="";$no_kwitansi="";
	$print_status=($this->input->get('rpn'))?$this->input->get('rpn'):0;
	/**
	 * load data cara pembayaran yang dilakukan
	 */
	if(isset($cbayare)){
		if($cbayare){
			if($cbayare->totaldata>0){
				foreach ($cbayare->message as $key => $value) {
					$cb=$value->CARA_BAYAR;
					$no_rekening = $value->NO_REKENING;
					$no_cek = $value->NO_CHEQUE;
					$nama_bank = $value->NAMA_BANK;
					$tgl_jthtempo = tglFromSql($value->JTH_TEMPO);
					$no_kwitansi = $value->NO_KWITANSI;
					$print_status = $value->STATUS_PRINT;
					
				}
			}
		}
	}
	$printkw=($print_status==0 && $no_trans!='')?" ":"disabled-action";
	$printkw=($print_status==0 && $no_trans=='')?" ":$printkw;
	$panelKw=($no_trans=='')?'disabled-action':"";
	/**
	 * Load data approval status untuk transaksi pengeluaran
	 * yang perlu dilakukan approval
	 * @var string
	 */
	$voucher_status="";$sts_pst=0;$sts_lkh=0;$no_apv="";$no_url="";
	if(isset($appv)){
		if($appv->totaldata>0){
			foreach ($appv->message as $key => $value) {
				$voucher_status=$value->VOUCHER_NO;
				$sts_pst=$value->POSTING_STATUS;
				$sts_lkh =($value->LKH) ?$value->LKH:0;
				$no_apv  = $value->NO_TRANS;
				$no_url =urlencode(base64_encode($value->NO_TRANS));
			}
		}
	}
	$hidden_panel=($no_trans)?"":"disabled-action";
	$yangApproval=($this->session->userdata("status_cabang")=='Y')?"DS":"KSP";
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
		<div class="bar-nav pull-right">
			<a class="btn btn-default modal-button" id="opn" role="button" title="Open Transaksi" url="<?php echo base_url("cashier/open_trans");?>" data-toggle="modal" data-target="#myModalDf" data-backdrop="static"><i class='fa fa-bookmark'></i><span class='hidden-xs hidden-sm'> Open Transaksi</span></a>
			<a class="btn btn-default" id="baru" role="button" title="Baru"><i class='fa fa-file-o'></i><span class='hidden-xs hidden-sm'> Baru</span></a>
			<a id="smp" class="btn btn-default disabled-action <?php echo $printkw;?> <?php echo ($no_trans!='')?'disabled-action':"";?>" role="button" onclick="__simpan_transaksi();" title="Simpan"><i class='fa fa-save'></i><span class='hidden-xs hidden-sm'> Simpan</span></a>
			<a class="btn btn-default <?php echo $printkw;?> <?php echo ($posting=="1" || $no_trans=='')?'disabled-action':"";?>" role="button" onclick="__cancel_transaksi('<?php echo $no_trans;?>','1');" title="Cancel"><i class='fa fa-trash'></i><span class='hidden-xs hidden-sm'> Cancel</span></a>
			<a class="btn btn-default" id="lsted" role="button" href="<?php echo base_url('cashier/listkasir');?>" title="List Transaksi"><i class='fa fa-list-ul'></i><span class='hidden-xs hidden-sm'> List Transaksi</span></a>
			<a class="btn btn-default modal-button" id="cls" role="button" title="Close Transaksi" url="<?php echo base_url("cashier/close_trans");?>" data-toggle="modal" data-target="#myModalDf" data-backdrop="static"><i class='fa fa-close'></i><span class='hidden-xs hidden-sm'> Close Transaksi</span></a>
		</div>
	</div>
	<!-- <fieldset class="all"> -->
		<div class="col-lg-12  padding-left-right-10" style="overflow: auto;" id="ctn">
    		<form id="frmKasir"  method="post" class="">
				<div class="col-md-9 col-sm-9 col-xs-12 ">
					<div class="row"><!-- row 1 -->
						<div class="panel margin-bottom-10">
							<div class="panel-heading panel-custom">
								<div class="row">
									<div class="col-xs-4 col-sm-4 col-md-4">
										<h4 class="panel-title" style="padding-top: 10px;">
											<i class='fa fa-cart'></i> Cashier 
										</h4>
									</div>
									<div class="col-xs-6 col-sm-4 col-md-4">
										<label  title='<?php echo gitversion();?>'>Saldo Kas : <?php echo number_format($saldo_awal,0);?> </label>
										<input type="hidden" readonly="true" id="saldo_awal" name="saldo_awal" value="<?php echo $saldo_awal;?>">
							        </div>
							        <div class="col-sm-4 col-xs-hidden <?php echo $hidden_panel;?><?php echo $status_p;?>">
							        	<div class="btn-group pull-right btn-group-sm" role="group" style="margin-top: 6px;padding-right: 10px">
							        		<a class="btn btn-info btn-group" role="group"  onclick="__reprint();"><i class='fa fa-print fa-fw'></i> Re Print</a>
							        		<a class="btn btn-default btn-group" role="group" onclick="__regigster()"><i class='fa fa-registered'></i> Register</a>
							        	</div> 
							        </div> 
							        <div class="col-xs-2 col-sm-1 col-md-1 hidden">
										<span class="tools pull-right" style="padding-right: 5px">
							              <a class="fa fa-chevron-down" href="javascript:;"></a>
							            </span>
							        </div>
						        </div>
						    </div>
					    	<div class="panel-body panel-body-border">
						    	<div class="col-xs-12 col-sm-8 col-md-8">
						    		<div class="col-xs-12 col-sm-5 col-md-5">
							    		<div class="form-group">
							    			<label>Tipe Transaksi</label>
							    			<select id="tp_transaksi" name="tp_transaksi" class="form-control <?php echo ($tp_transaksi)?'disabled-action':'';?>">
							    				<option>--Pilih Tipe Transaksi--</option>
							    				<option value='Penerimaan' <?php echo ($tp_transaksi=="Penerimaan")?"selected":"";?>>Penerimaan</option>
							    				<option value='Pengeluaran' <?php echo ($tp_transaksi=="Pengeluaran")?"selected":"";?>>Pengeluaran</option>
							    			</select>
							    		</div>
							    	</div>
							    	<div class="col-xs-12 col-sm-7 col-md-7">
							    		<div class="form-group">
							    			<label>Jenis Transaksi<span id="ldg_0"></span></label>
							    			<select id="jenis_transaksi" name="jenis_transaksi" class="form-control <?php echo ($tp_transaksi && $no_trans)?'disabled-action':'';?>">
							    				<option>--Pilih Jenis Transaksi--</option>
							    				<option class="C hidden" value='Penjualan Unit'<?php echo ($jenis_transaksi=="Penjualan Unit")?"selected":"";?>>Penjualan Unit</option>
							    				<option class="C hidden" value='Penjualan Sparepart'<?php echo ($jenis_transaksi=="Penjualan Sparepart")?"selected":"";?>>Penjualan Sparepart</option>
							    				<option class="C hidden" value='Penjualan Apparel'<?php echo ($jenis_transaksi=="Penjualan Apparel")?"selected":"";?>>Penjualan Apparel</option>
							    				<option class="C hidden" value='Penjualan Aksesoris'<?php echo ($jenis_transaksi=="Penjualan Aksesoris")?"selected":"";?>>Penjualan Aksesoris</option>
							    				<option class="C hidden" value='Penerimaan Umum'<?php echo ($jenis_transaksi=="Penerimaan Umum")?"selected":"";?>>Penerimaan Umum</option>
							    				<option class="C hidden" value='Titipan Uang'<?php echo ($jenis_transaksi=="Titipan Uang")?"selected":"";?>>Titipan Uang</option>
							    				<option class="D hidden" value='Pengeluaran Umum'<?php echo ($jenis_transaksi=="Pengeluaran Umum")?"selected":"";?>>Pengeluaran Umum</option>
							    				<option class="D hidden" value='Pengeluaran Barang'<?php echo ($jenis_transaksi=="Pengeluaran Barang")?"selected":"";?>>Pengeluaran Barang</option>
							    				<option class="D hidden" value='Biaya Hadiah'<?php echo ($jenis_transaksi=="Biaya Hadiah")?"selected":"";?>>Biaya Hadiah</option>
							    				<option class="D hidden" value='Fee Penjualan'<?php echo ($jenis_transaksi=="Fee Penjualan")?"selected":"";?>>Fee Penjualan</option>
							    				<!-- pilihan untuk penerimaan barang -->
							    				<option class='D E hidden' value='Penerimaan Barang' <?php echo ($jenis_transaksi=="Penerimaan Barang")?"selected":"";?>>Penerimaan Barang</option>
							    				<option class="C hidden" value='Pengembalian Pinjaman'<?php echo ($jenis_transaksi=="Pengembalian Pinjaman")?"selected":"";?>>Pengembalian Pinjaman Peng. STNK/BPKB</option>
							    				<option class="C hidden" value='Balik Sementara'<?php echo ($jenis_transaksi=="Balik Sementara")?"selected":"";?>>Pengembalian Pinjaman Sementara</option><!-- Service -->
							    				<option class='D E hidden' value='Service' <?php echo ($jenis_transaksi=="Service")?"selected":"";?>>Service</option>
							    				<option class="D hidden" value='Pinjaman'<?php echo ($jenis_transaksi=="Pinjaman")?"selected":"";?>>Pinjaman Pengurusan BPKB/STNK</option>
							    				<option class="D hidden" value='Nilai SS'<?php echo ($jenis_transaksi=="Nilai SS")?"selected":"";?>>Pembayaran SS</option>
							    				<option class="D hidden" value='Pinjaman Sementara'<?php echo ($jenis_transaksi=="Pinjaman Sementara")?"selected":"";?>>Pinjaman Sementara</option>
							    			</select>
							    		</div>
							    	</div>
						    		<div class="col-xs-12 col-sm-5 col-md-5">
						    			<div class="form-group" >
						    				<label id="kda">Kode Akun</label>
						    				<input id="kd_akun" name="kd_akun" class="form-control" value="<?php echo $kd_akun;?>">
						    			</div>
						    		</div>
						    		<div class="col-xs-12 col-sm-7 col-md-7">
						    			<label>Deskripsi Akun</label>
						    			<input type="text" id="nama_akun" name="nama_akun" class="form-control" readonly="true" value="<?php echo $nama_akun;?>">
						    		</div>
						    	</div>
						    	<div class="col-xs-12 col-sm-4 col-md-4">
						    		<div class="col-xs-12 col-sm-12 col-md-12">
						    			<div class="form-group">
						    				<label>No. Transaksi</label>
						    				<input type="text" name="no_trans" id="no_trans" placeholder="No Transaksi Auto Generate" class="form-control" readonly="true" value="<?php echo $no_trans;?>">
						    			</div>
						    		</div>
						    		<div class="col-xs-12 col-sm-12 col-md-12">
						    			<div class="form-group">
						    				<label>Tanggal Transaksi</label>
						    				<div class="input-group append-group ">
				        						<input type="text" class="form-control" id="tgl_trans" name="tgl_trans" value="<?php echo($tgl_trans=='')? date("d/m/Y"):tglFromSql($tgl_trans);?>">
				        						 <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span> </span>
				        					</div>
				        				</div>
						    		</div>
						    	</div>
						    </div>
						</div>
					</div><!-- end row 1 -->
					<div class="row">
						<?php
							//otomatis tampilkan field penjualan unit jika variable $jenis_transaksi=='Penjualan Unit'
							$class=($jenis_transaksi=="Penjualan Unit")?"":"class='hidden'";
							$disabled="";//($jenis_transaksi=="Penjualan Unit")?"":"disabled-action";
						?>
						<!-- penjualan unit -->
						<fieldset id="unit" class='<?php echo ($jenis_transaksi=="Penjualan Unit")?"":"hidden";?> <?php echo $status_posting;?>' >
							<div class="panel margin-bottom-10">
								<div class="panel-body panel-body-border-top">
									<!-- <fieldset id="Penjualan_Unit" class="<?php echo $disabled;?>"> -->
										<!-- <form> -->
											<div class="col-xs-12 col-md-3 col-sm-3">
												<div class="form-group">
													<label>No. Reff <span style="color:red" id="lgd"></span></label>
													<input type="text" name="no_reff" id="no_reff" class="form-control" value="<?php echo $no_reff;?>">
													<input type="hidden" name="source" id="source" value="TRANS_SPK.NO_SPK">
												</div>
											</div>
											<div class="col-xs-12 col-md-6 col-sm-6">
												<div class="form-group">
													<label>Nama Customer</label>
													<input type="text" name="ket_reff" id="ket_reff" class="form-control" value="<?php echo $ket_reff;?>">
												</div>
											</div>
											<div class="col-xs-12 col-md-3 col-sm-3">
												<div class="form-group">
													<label>Cara Pembayaran</label>
													<input type="text" name="carabayar" id="carabayar" class="form-control" value="<?php echo $carabayar;?>">
												</div>
											</div>
										<!-- </form> -->
									<!-- </fieldset> -->
								</div>
									<div class="">
										<table class="table tablex table-striped table-bordered" id="united">
											<thead>
												<tr>
													<th>No.</th>
													<th class="col-md-6">Uraian Transaksi</th>
													<th class="col-md-1">Jumlah</th>
													<th class="col-md-2">Harga</th>
													<th class="col-md-2">Total Harga</th>
												</tr>
											</thead>
											<tbody>
												<?php
												 if(isset($transd)){
												 	if($transd){ $n=0;$totalx=0;
												 		if($transd->totaldata>0){ 
												 			foreach ($transd->message as $key => $value) {
												 				?>
																<tr class="disabled-action">
																	<td class='text-center'><?php echo ($n+1);?></td>
																	<td><textarea rows='4' name="uraian_1" id="uraian_12" class='on-grid form-control' ><?php echo $value->URAIAN_TRANSAKSI;?></textarea></td>
																	<td><input type='text' name="jml_1" id="jml_12" class='on-grid form-control text-center' value='<?php echo (double)$value->JUMLAH;?>'/></td>
																	<td><input type='text' name="harga_1" id="harga_12" class='on-grid form-control text-right' value='<?php echo (double)$value->HARGA;?>' data-mask='#,##0' data-mask-reverse='true'/></td>
																	<td><input type='text' name="total_1" id="total_12" class='on-grid form-control text-right' value='<?php echo ((double)$value->HARGA * (double)$value->JUMLAH);?>' data-mask='#,##0' data-mask-reverse='true'/></td>
																</tr>
												 				<?php
												 				$totalx +=((double)$value->HARGA * (double)$value->JUMLAH);
												 				//var_dump($totalx);
												 			}
												 			?>
												 				<tr><td colspan="5">
												 					<!-- untuk informasi sales program -->
												 					<table class='table sp_info' id="info_sp">
												 						<tbody>
												 						</tbody>
												 						<tfoot></tfoot>
												 					</table>
												 					<table class='table sp_info' id="info_kk">
												 						<tbody>
												 						</tbody>
												 					</table>
												 				</td></tr>
																<tr class="total warning">
																	<td colspan="3" class="text-right" style="padding-right: 10px"> <b>Jumlah Total</b></td>
																	<td>&nbsp;</td>
																	<td class="text-right"><?php echo number_format($totalx,0);?></td>
																</tr>
																<tr><td colspan="5" class='text-right' style="padding-right: 10px"><em><?php echo terbilang($totalx);?><em></td></tr>
												 			<?php
												 		}
												 	}
												 }else{
												?>
													<tr class="hidden" id="ttp">
														<td class='text-center'>&nbsp;</td>
														<td class="table-nowarp"><input type="text" class="on-grid form-control disabled-action" id="uraian_ttp" name="uraian_ttp"></td>
														<td class='text-center'>1</td>
														<td class="text-right"><input type="text" class="on-grid form-control disabled-action text-right" id="jml_ttp" name="jml_ttp"></td>
														<td class="text-right"><input type="text" class="on-grid form-control disabled-action text-right" id="t_jml_ttp"></td>
													</tr>
													<tr>
														<td class='text-center'>1</td>
														<td><textarea rows='3' name="uraian_1" id="uraian_1" class='on-grid form-control'></textarea></td>
														<td><input type='text' name="jml_1" id="jml_1" class='on-grid form-control text-center'/></td>
														<td class='text-center'>
															<input type='text' name="harga_1" id="harga_1" class='on-grid form-control text-right' data-placement='bottom' data-toggle='popover' data-trigger='focus'/>
															
														</td>
														<td valign="top">
															<input type='text' name="total_1" id="total_1" class='on-grid form-control text-right' data-mask='#,##0' data-mask-reverse='true'/>
															<input type='text' value="0" name="total_2" id="total_2" class='on-grid form-control text-right' data-mask='#,##0' data-mask-reverse='true'/>
														</td>
													</tr>
													<tr><td colspan="5"><input type="hidden" id="harga_awal" value=''>
														<table class='table sp_info' id="info_spx"><tbody></tbody><tfoot></tfoot></table>
														<!-- <table class='table sp_info' id="info_kkx"><tbody></tbody></table> -->
													</td></tr>
													<tr class="total warning">
														<td colspan="3" class="text-right" style="padding-right: 10px"> <b>Jumlah Total</b></td>
														<td>&nbsp;</td>
														<td class="text-right"><span id="totalbayar"></span></td>
													</tr>
													<tr><td colspan="5" class='text-right' style="padding-right: 10px"><em><span id="terbilang"></span><em></td></tr>
												<?php } ?>		
											</tbody>
										</table>
										<!-- <button id="bayar" type="button" class="btn btn-primary pull-right"><i class='fa fa-shopping-bag'></i> Bayar</button> -->
									</div>
								<!-- </div> -->
							</div>
						</fieldset>
						<fieldset id="barang" class='<?php echo ($jenis_transaksi=="Penerimaan Barang")?"":"hidden";?> <?php echo $status_posting;?>'>
							<div class="panel margin-bottom-10">
								<div class="panel-body panel-body-border-top">
									<span class="refbarang">
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label>No. Reff/No Bukti Pembelian</label>
												<input type="text" name="no_reff_b" id="no_reff_b" class="form-control" value='<?php echo $no_reff;?>'>
											</div>
										</div>
										<div class="col-xs-12 col-md-8 col-sm-8">
											<div class="form-group">
												<label>Keterangan</label>
												<input type="text" name="ket_reff_b" id="ket_reff_b" class="form-control" value="<?php echo $ket_reff;?>">
											</div>
										</div>
									</span>
									<div class="col-xs-12 col-md-6 col-sm-6">
										<div class="form-group">
											<label>Nama Barang</label>
											<input type="text" name="nama_barang" id="nama_barang" class="form-control">
											<input type="hidden" name="kd_barang" id="kd_barang" class="form-control">
										</div>
									</div>
									<div class="col-xs-12 col-md-1 col-sm-1">
										<div class="form-group">
											<label>Jumlah</label>
											<input type="text" name="jumlah_b" id="jumlah_b" class="form-control" data-mask="#,##0" data-mask-reverse="true">
										</div>
									</div>
									<div class="col-xs-12 col-md-2 col-sm-2">
										<div class="form-group">
											<label>Harga</label>
											<input type="text" name="price_b" id="price_b" class="form-control" data-mask="#,##0" data-mask-reverse="true">
										</div>
									</div>
									<div class="col-xs-12 col-md-3 col-sm-3">
										<div class="form-group">
											<label>Total Harga</label>
											<div class="input-group">
												<input type="text" name="tprice_b" id="tprice_b" class="form-control" data-mask="#,##0" data-mask-reverse="true">
												<span class="input-group-btn">
													<button class="btn btn-default" onclick="__addItemBarang();" type="button" id="btn-add"><i class="fa fa-plus"></i></button>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="">
									<table class="table table-bordered table-hover table-striped" id="lst_barang">
										<thead>
											<tr>
												<th>No.</th><th>&nbsp;</th>
												<th class="col-md-6">Uraian Transaksi</th>
												<th class="col-md-1">Jumlah</th>
												<th class="col-md-2">Harga</th>
												<th class="col-md-2">Total Harga</th>
											</tr>
										</thead>
										<tbody>
										<?php
											if(isset($transd)){ $n=0;
												if($transd->totaldata>0){
													foreach ($transd->message as $key => $value) {
														
														?>
														<tr>
															<td class="text-center"><?php echo ($n+1);?></td>
															<td class="text-center <?php $status_posting;?>" ><a onclick="__hapus_item_b('<?php echo $value->ID;?>','<?php echo $n;?>')" role="button"><i class="fa fa-trash"></i></a></td>
															<td><?php echo $value->URAIAN_TRANSAKSI;?></td>
															<td class="text-right"><?php echo number_format($value->JUMLAH,0);?></td>
															<td class="text-right"><?php echo number_format($value->HARGA,0);?></td>
															<td class="text-right"><?php echo number_format(($value->JUMLAH * $value->HARGA),0);?></td>
														</tr>
														<?php
														$n++;
													}
												}
											}
										?>
										</tbody>
									</table>
							</div>
						</fieldset>
						<?php
							$uraian_u="";$jumlah_u="0";
							$umum="";$label="";
							if($jenis_transaksi=="Penerimaan Umum" ||
							   $jenis_transaksi=="Pengeluaran Umum"){
								$umum="";
								$label="Diberikan Kepada";
							}else{
								$umum="hidden";
								$label="No.Reff";
							}
							$ket_reff =($jenis_transaksi=="Pengeluaran Umum")?$this->session->userdata("nama_dealer"):$ket_reff;
							$nonaktif=($jenis_transaksi=="Pengeluaran Umum")?"disabled-action":"";
							if(isset($transd)){
									if($transd->totaldata>0){
										foreach ($transd->message as $key => $value) {
											//$ket_reff 	= $value->KET_REFF;
											//$no_reff 	= $value->NO_REFF;
											$uraian_u	= $value->URAIAN_TRANSAKSI;
											$jumlah_u	= $value->HARGA;
										}
									}
								}
						?>
						<fieldset id="umum" class='<?php echo $umum;?> <?php echo $status_posting;?>'>
							<div class="panel margin-bottom-10">
								<div class="panel-body panel-body-border-top">
									<div class="row">
										<div class="col-xs-6 col-md-6 col-sm-6">
											<div class="form-group">
												<label>Telah Terima Dari</label>
												<input type="text" name="ket_reff_u" id="ket_reff_u" value="<?php echo $ket_reff;?>" class="form-control <?php echo $nonaktif;?>">
											</div>
											<input type="hidden" id="nama_dealer" value="<?php echo NamaDealer($defaultDealer);?>">
										</div>
										<div class="col-xs-6 col-md-5 col-sm-5">
											<div class="form-group">
												<label><span  id="nf"><?php echo $label;?></span></label>
												<input type="text" name="no_reff_u" id="no_reff_u" value="<?php echo $no_reff;?>" class="form-control">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-md-8 col-sm-8">
											<div class="form-group">
												<label>Uraian Transaksi</label>
												<textarea id="uraian_u" name="uraian_u" class="form-control"><?php echo $uraian_u;?></textarea>
											</div>
										</div>
										<div class="col-xs-6 col-md-4 col-sm-4 hidden" id="btlspk">
											<div class="form-group">
												<label><input type="checkbox" id="SPKBT" name="spkbt"> Pembatalan SPK <span id="ldgspk" style='color:red'></span></label>
												<input type="text" id="no_spk_batal" name="no_spk_batal" class="form-control">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label>Jumlah</label>
												<div class="input-group">
													<input type="text" name="jumlah_u" id="jumlah_u" value="<?php echo number_format($jumlah_u,0);?>" data-mask="#,##0" data-mask-reverse="true" class="form-control text-right">
													<span class="input-group-addon"><input type="checkbox" class="hidden" id="ppn_u" name="ppn_u" style="cursor: pointer;"></span>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-md-8 col-sm-8">
											<div class="form-group">
												<br>
												<label style="font-style: italic;" id="terbilang_u"></label>
												<input type="hidden" name="no_kwt_lama" id="no_kwt_lama" value="">
											</div>
										</div>
									</div>
								</div>
							</div>
						</fieldset>
						<fieldset id="sparepart" class='<?php echo 
							($jenis_transaksi=="Penjualan Sparepart"||
							$jenis_transaksi=="Penjualan Aksesoris" ||
							$jenis_transaksi=="Penjualan Apparel" ||
							$jenis_transaksi=="Service")?"":"hidden";?> <?php echo $status_posting;?>'>
							<div class="panel margin-bottom-10">
								<div class="panel-body panel-body-border-top">
									<div class="row">
										<div class="col-xs-12 col-md-3 col-sm-3 no-margin-r">
											<div class="form-group">
												<label>No.Reff <span id="pkb"></span> <span id="ldgsp"></span></label> &nbsp;
												<input type="text" name="no_reff_sp" id="no_reff_sp" class="form-control" placeholder="Sales Order">
											</div>
										</div>
										<div class="col-xs-12 col-md-6 col-sm-6 no-margin-r">
											<div class="form-group">
												<label>Keterangan <span id="lbr"></span></label>
												<input type="text" name="ket_reff_sp" id="ket_reff_sp" class="form-control" placeholder="Nama Customer, instansi dll">
											</div>
										</div>
										<div class="col-xs-12 col-md-3 col-sm-3 no-margin-l">
											<div class="form-group">
												<label>Total Dibayar</label>
												<input type="text" name="jml_bayar" id="jml_bayar" class="form-control text-right" style="font-size: 16pt" readonly="true" data-mask="#,##0" data-mask-reverse="true" value="<?php echo $jml_bayar;?>">
											</div>
										</div>
									</div>
									<div class="row spsp <?php echo ($jenis_transaksi=="Service" ||$jenis_transaksi=="Penjualan Sparepart")?"hidden":"";?>" id="sp_item">
										<div class="col-xs-12 col-md-5 col-md-5 no-margin-r">
											<div class="form-group">
												<label>Nama Barang</label> &nbsp;
												<span>
													<input type="checkbox" id="only_stock" name="only_stock" style="cursor: pointer;" checked="true"> Only Ready Stock
												</span>
												<input type="text" id="nama_sp" name="nama_sp" class="form-control">
												<input type="hidden" name="part_number" id="part_number" class="form-control">
											</div>
										</div>
										<div class="col-xs-12 col-md-1 col-md-1 no-margin-l no-margin-r">
											<div class="form-group">
												<label>Jumlah</label>
												<input type="text" id="jumlah_sp" name="jumlah_sp" data-mask="#,##0" data-mask-reverse="true" class="form-control text-right">
											</div>
										</div>
										<div class="col-xs-12 col-md-3 col-md-3 no-margin-l no-margin-r">
											<div class="form-group">
												<label>Harga Satuan</label>
												<input type="text" name="harga_sp" id="harga_sp" data-mask="#,##0" data-mask-reverse="true" class="form-control text-right">
											</div>
										</div>
										<div class="col-xs-12 col-md-3 col-md-3 no-margin-l">
											<div class="form-group">
												<label>Total Harga</label>
												<div class="input-group">
													<input type="text" name="total_harga_sp" id="total_harga_sp" readonly="true" data-mask="#.##0" data-mask-reverse="true" class="form-control text-right">
													<span class="input-group-btn">
														<button class="btn btn-primary disabled-action" onclick="__addItemSP();" type="button" id="btn-add-sp"><i class="fa fa-plus"></i></button>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="table-responsive h250">
									<table class="table table-bordered table-hover table-striped" id="lst_sp">
										<thead>
											<tr>
												<th>No.</th><th>&nbsp;</th>
												<th class="col-md-6">Uraian Transaksi</th>
												<th class="col-md-1">Jumlah</th>
												<th class="col-md-2">Harga</th>
												<th class="col-md-2">Total Harga</th>
												<th class="hidden">PartNumber</th>
											</tr>
										</thead>
										<tbody>
											<!-- list sparepart -->
											<?php
												$n=0;
												if(isset($transd)){
													if($transd->totaldata>0){
														foreach ($transd->message as $key => $value) {
															$ket=explode(":", $value->KETERANGAN);
															?>
															<tr id="n_<?php echo $n;?>">
																<td class='text-center'><?php echo ($n+1);?></td>
																<td class="text-center <?php $status_posting;?>" ><a onclick="__hapus_item_b('<?php echo $value->ID;?>','<?php echo $n;?>')" role="button"><i class="fa fa-trash"></i></a></td>
																<td class="td-overflow-50" title="<?php echo $value->URAIAN_TRANSAKSI;?>"><?php echo $value->URAIAN_TRANSAKSI;?></td>
																<td class="text-right"><?php echo number_format($value->JUMLAH,0);?></td>
																<td class="text-right"><?php echo number_format($value->HARGA,0);?></td>
																<td class="text-right"><?php echo number_format(($value->JUMLAH * $value->HARGA),0);?></td>
																<td class="hidden"><?php echo (count($ket)>1)?$ket[1]:"";?></td>
															</tr>
															<?php
															$n++;
														}
													}
												}
											?>
										</tbody>
										<tfoot class="hidden">
											<tr class="subtotal">
												<td>&nbsp;</td><td>&nbsp;</td>
												<td id="service_reff"></td>
												<td class="text-right" id="jml_total"></td>
												<td class="text-right" id="harga_total"></td>
												<td class="text-right" id="grand_total"></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</fieldset>
						<fieldset id="fee" class='<?php echo ($jenis_transaksi=="Fee Penjualan")?"":"hidden";?> <?php echo $status_posting;?>'>
							<div class="panel margin-bottom-10">
								<div class="panel-body panel-body-border-top">
									<div class="row ajeg">
										<div class="col-xs-12 col-sm-12 col-md-3 no-margin-r">
											<div class="form-group">
												<label>Jenis Fee</label>
												<select id="jenis_fee" name="jenis_fee" class="form-control">
													<option value="">--Pilih Fee--</option>
													<option value="FS">Fee Sales</option>
													<option value="MK">Fee Makelar</option>
													<option value="GCS">Fee GC Swasta</option>
													<option value="GCD">Fee Dinas</option>
												</select>
											</div>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-6 no-margin-r no-margin-l">
											<div class="form-group">
												<label>Penerima Fee</label>
												<input type="text" id="nama_penerima" name="nama_penerima" class="form-control">
											</div>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-3 no-margin-l">
											<div class="form-group">
												<label>Total Fee</label>
												<input type="text" id="total_fee" name="total_fee" style="font-size: 16pt" class="form-control" readonly="true" data-mask="#,##0" data-mask-reverse="true">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-6 col-md-3 no-margin-r">
											<div class="form-group">
												<label>No.Reff (SPK)</label>
												<input type="text" id="no_reff_fe" name="no_reff_fe" class="form-control" placeholder="Nomor SPK">
											</div>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-6 no-margin-r no-margin-l">
											<div class="form-group">
												<label>Keterangan</label>
												<input type="text" id="ket_reff_fe" name="ket_reff_fe" class="form-control" readonly="true">
											</div>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-3 no-margin-l">
											<div class="form-group">
												<label>Jumlah Fee</label>
												<div class="input-group">
													<input type="text" id="jumlah_fe" name="jumlah_fe" class="form-control text-right" data-mask="#,##0" data-mask-reverse="true">
													<span class="input-group-btn">
														<button class='btn btn-primary disabled-action' id="addFee" role="button"><i class='fa fa-plus'></i></button>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="">
									<table class="table table-bordered table-hover table-striped" id="list_fee">
										<thead>
											<tr>
												<th>No.</th><th>&nbsp;</th>
												<th class="col-md-6">Uraian Transaksi</th>
												<th class="col-md-1">Jumlah</th>
												<th class="col-md-2">Harga</th>
												<th class="col-md-2">Total Harga</th>
												<th class="hidden">Penerima</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if(isset($transd)){ $n=0;
												if($transd->totaldata>0){
													foreach ($transd->message as $key => $value) {
														
														?>
														<tr>
															<td class="text-center"><?php echo ($n+1);?></td>
															<td class="text-center <?php $status_posting;?>" ><a onclick="__hapus_item_b('<?php echo $value->ID;?>','<?php echo $n;?>')" role="button"><i class="fa fa-trash"></i></a></td>
															<td><?php echo $value->URAIAN_TRANSAKSI;?></td>
															<td class="text-right"><?php echo number_format($value->JUMLAH,0);?></td>
															<td class="text-right"><?php echo number_format($value->HARGA,0);?></td>
															<td class="text-right"><?php echo number_format(($value->JUMLAH * $value->HARGA),0);?></td>
														</tr>
														<?php
														$n++;
													}
												}
											}
										?>
										</tbody>
									</table>
								</div>
							</div>
						</fieldset>
						<fieldset id="pengembalian" class='<?php echo ($jenis_transaksi=="Pengembalian Pinjaman")?"":"hidden";?> <?php echo $status_posting;?>'>
							<div class="panel margin-bottom-10">
								<div class="panel-body panel-body-border-top">
									<div class="row">
										<div class="col-xs-12 col-md-3 col-sm-6">
											<div class="form-group">
												<label>Nama Pengurus<span id="ldg_1"></span></label>
												<input type="text" class="form-control" id="ket_reff_pb" name="ket_reff_pb" placeholder="Nama pengurusan" value='<?php echo $ket_reff;?>'>
											</div>
										</div>
										<div class="col-xs-12 col-md-3 col-sm-6 no-margin-r no-margin-l">
											<div class="form-group">
												<label>No. Pengajuan<span id="ldg_2"></span></label>
												<input type="text" class="form-control" id="no_reff_pb" name="no_reff_pb" placeholder="Nomor pengajuan" value='<?php echo $no_reff;?>'>
												<input type="hidden" name="stnk_idb" id="stnk_idb" value="">
											</div>
										</div>
										<div class="col-xs-6 col-md-2 col-sm-2 no-margin-r no-margin-l">
											<div class="form-group">
												<label>Jenis Document</label>
												<input type="text" class="form-control disabled-action" id="jenis_reff_pb" name="jenis_reff_pb" placeholder="Jenis Document">
												<input type="hidden" id="jml_pengajuanb" name="jml_pengajuanb">
												<input type="hidden" id="uraian_pb" name="uraian_pb">
											</div>
										</div>
										<div class="col-xs-6 col-md-2 col-sm-2 no-margin-l no-margin-r">
											<div class="form-group">
												<label>Total Pinjaman</label>
												<!-- <div class="input-group"> -->
													<input type="text" class="form-control text-right disabled-action" id="jumlah_pb" name="jumlah_pb" placeholder="Total Pengajuan" data-mask="#,##0" data-mask-reverse="true"  style="font-size: 16pt" >
													<span class="input-group-btn hidden">
														<button class='btn btn-primary disabled-action' id="addPinjamanb"><i class='fa fa-plus'></i></button>
													</span>
												<!-- </div> -->
											</div>
										</div>
										<div class="col-xs-6 col-md-2 col-sm-2 no-margin-l">
											<div class="form-group">
												<label>Total Kembali</label>
												<input type="text" class="form-control text-right disabled-action" id="jumlah_pbp" name="jumlah_pbp" placeholder="Total Pengajuan" data-mask="#,##0" data-mask-reverse="true"  style="font-size: 16pt" >

											</div>
										</div>
									</div>
									<div class="row hidden" id="bpkb_checkb">
										<div class="col-xs-12 col-md-11 col-sm-11">
											<span id="jtrans"></span>
											<ul class="list-inline pull-right" id="lst_bpkbb"></ul>
											<input type="hidden" id="list_pilihanb" name="list_pilihanb" value="">
										</div>
									</div>
								</div>
								<div id="tbl-ctn" class="">
									<table class="table table-bordered table-hover table-stripped <?php echo ($no_trans)?"":'hidden';?>" id="list_pinjamanb">
										<thead>
											<tr>
												<th>No.</th><th>&nbsp;</th>
												<th class="col-md-6">Uraian Transaksi</th>
												<th class="col-md-1">Jumlah</th>
												<th class="col-md-2">Harga</th>
												<th class="col-md-2">Total Harga</th>
												<th class="hidden">Penerima</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if(isset($transd)){ $n=0;
												if($transd->totaldata>0){
													foreach ($transd->message as $key => $value) {
														
														?>
														<tr>
															<td class="text-center"><?php echo ($n+1);?></td>
															<td class="text-center <?php $status_posting;?>" ><a onclick="__hapus_item_b('<?php echo $value->ID;?>','<?php echo $n;?>')" role="button"><i class="fa fa-trash"></i></a></td>
															<td><?php echo $value->URAIAN_TRANSAKSI;?></td>
															<td class="text-right"><?php echo number_format($value->JUMLAH,0);?></td>
															<td class="text-right"><?php echo number_format($value->HARGA,0);?></td>
															<td class="text-right"><?php echo number_format(($value->JUMLAH * $value->HARGA),0);?></td>
															<td class="hidden"><?php echo $value->KETERANGAN;?></td>
														</tr>
														<?php
														$n++;
													}
												}
											}
										?>
										</tbody>
									</table>
								<!-- </div> -->
								<!-- <div class="table-responsive"> -->
									<table class="table table-bordered table-hover table-stripped <?php echo ($no_trans)?'hidden':'';?>" id="list_pinjamanlb">
										<thead>
											<tr>
												<th>No</th>
												<th>No Mesin</th>
												<th>Kode</th>
												<th>Customer</th>
												<th>BBNKB</th>
												<th>PKB</th>
												<th>SWDKLLJ</th>
												<th>#</th>
											</tr>
										</thead>
										<tbody>
											
										</tbody>
									</table>
								</div>

							</div>						
						</fieldset>
						<fieldset id="pinjaman" class='<?php echo ($jenis_transaksi=="Pinjaman")?"":"hidden";?> <?php echo $status_posting;?>'>
							<div class="panel margin-bottom-10">
								<div class="panel-body panel-body-border-top">
									<div class="row">
										<div class="col-xs-12 col-md-3 col-sm-6">
											<div class="form-group">
												<label>Nama Pengurus</label>
												<input type="text" class="form-control" id="ket_reff_p" name="ket_reff_p" placeholder="Nama pengurusan" value='<?php echo $ket_reff;?>'>
											</div>
										</div>
										<div class="col-xs-12 col-md-3 col-sm-6 no-margin-r no-margin-l">
											<div class="form-group">
												<label>No. Pengajuan</label>
												<input type="text" class="form-control" id="no_reff_p" name="no_reff_p" placeholder="Nomor pengajuan" value='<?php echo $no_reff;?>'>
												<input type="hidden" name="stnk_id" id="stnk_id" value="">
											</div>
										</div>
										<div class="col-xs-12 col-md-3 col-sm-6 no-margin-r no-margin-l">
											<div class="form-group">
												<label>Jenis Document</label>
												<input type="text" class="form-control disabled-action" id="jenis_reff_p" name="jenis_reff_p" placeholder="Jenis Document">
												<input type="hidden" id="jml_pengajuan" name="jml_pengajuan">
												<input type="hidden" id="uraian_p" name="uraian_p">
											</div>
										</div>
										<div class="col-xs-12 col-md-3 col-sm-6 no-margin-l">
											<div class="form-group">
												<label>Total Pinjaman</label>
												<div class="input-group">
													<input type="text" class="form-control text-right disabled-action" id="jumlah_p" name="jumlah_p" placeholder="Total Pengajuan" data-mask="#,##0" data-mask-reverse="true"  style="font-size: 16pt" >
													<span class="input-group-btn hidden">
														<button class='btn btn-primary disabled-action' id="addPinjaman"><i class='fa fa-plus'></i></button>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="row hidden" id="bpkb_check">
										<div class="col-xs-12 col-md-6 col-sm-6">
											<ul class="list-inline pull-right" id="lst_bpkb">
												<!-- <li><input type="checkbox" name="lst_p" id="bpkb" value="B"> BPKB</li>
												<li><input type="checkbox" name="lst_p" id="plat" value="B"> BPKB</li>
												<li><input type="checkbox" name="lst_p" id="adm" value="B"> BPKB</li>
												<li><input type="checkbox" name="lst_p" id="adm" value="B"> BPKB</li> -->
											</ul>
											<input type="hidden" id="list_pilihan" name="list_pilihan" value="">
										</div>
									</div>
								</div>
								<div class="">
									<table class="table table-bordered table-hover table-striped" id="list_pinjaman">
										<thead>
											<tr>
												<th>No.</th><th>&nbsp;</th>
												<th class="col-md-6">Uraian Transaksi</th>
												<th class="col-md-1">Jumlah</th>
												<th class="col-md-2">Harga</th>
												<th class="col-md-2">Total Harga</th>
												<th class="hidden">Penerima</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if(isset($transd)){ $n=0;
												if($transd->totaldata>0){
													foreach ($transd->message as $key => $value) {
														
														?>
														<tr>
															<td class="text-center"><?php echo ($n+1);?></td>
															<td class="text-center <?php $status_posting;?>" ><a onclick="__hapus_item_b('<?php echo $value->ID;?>','<?php echo $n;?>')" role="button"><i class="fa fa-trash"></i></a></td>
															<td><?php echo $value->URAIAN_TRANSAKSI;?></td>
															<td class="text-right"><?php echo number_format($value->JUMLAH,0);?></td>
															<td class="text-right"><?php echo number_format($value->HARGA,0);?></td>
															<td class="text-right"><?php echo number_format(($value->JUMLAH * $value->HARGA),0);?></td>
															<td class="hidden"><?php echo $value->KETERANGAN;?></td>
														</tr>
														<?php
														$n++;
													}
												}
											}
										?>
										</tbody>
									</table>
									<div class="table-responsive h250 list_pinjamanl hidden">
										<table class="table table-bordered table-hover table-striped <?php echo ($no_trans)?'hidden':'hidden';?>" id="list_pinjamanl">
											<thead>
												<tr>
													<th>No</th>
													<th>No Mesin</th>
													<th>Kode</th>
													<th>Customer</th>
													<th>BBNKB</th>
													<th>PKB</th>
													<th>SWDKLLJ</th>
													<th>#</th>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</fieldset>
						<fieldset id="keluarbarang" class='<?php echo ($jenis_transaksi=="Pengeluaran Barang")?"":"hidden";?> <?php echo $status_posting;?>'>
							<div class="panel margin-bottom-10">
								<div class="panel-body panel-body-border-top">
									<div class="row">
										<div class="col-xs-12 col-md-6 col-sm-6">
											<div class="form-group">
												<label>Nama Barang</label>
												<input type="text" name="nama_barang_p" id="nama_barang_p" class="form-control">
												<input type="hidden" name="kd_barang_p" id="kd_barang_p" class="form-control">
											</div>
										</div>
										<div class="col-xs-12 col-md-1 col-sm-1 no-margin-r no-margin-l">
											<div class="form-group">
												<label>Jumlah</label>
												<input type="text" name="jumlah_b_p" id="jumlah_b_p" class="form-control" data-mask="#,##0" data-mask-reverse="true">
											</div>
										</div>
										<div class="col-xs-12 col-md-2 col-sm-2 no-margin-r no-margin-l">
											<div class="form-group">
												<label>Harga</label>
												<input type="text" name="price_b_p" id="price_b_p" class="form-control" data-mask="#,##0" data-mask-reverse="true">
											</div>
										</div>
										<div class="col-xs-12 col-md-3 col-sm-3 no-margin-l">
											<div class="form-group">
												<label>Total Harga</label>
												<div class="input-group">
													<input type="text" name="tprice_b_p" id="tprice_b_p" class="form-control" data-mask="#,##0" data-mask-reverse="true">
													<span class="input-group-btn">
														<button class="btn btn-default" onclick="__addItemBarang_p();" type="button" id="btn-add_p"><i class="fa fa-plus"></i></button>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</fieldset>
						<fieldset id="loadss" class='<?php echo ($jenis_transaksi=="Nilai SS")?"":"hidden";?> <?php echo $status_posting;?>'>
							<div class="panel margin-bottom-10">
								<div class="panel-body panel-body-border-top">
									<div class="row">
										<div class="col-xs-12 col-md-3 col-sm-3">
											<div class="form-group">
												<label>Nama Pengurus</label>
												<input type='text' name="nama_pengurus" id="nama_pengurus" class="form-control">
											</div>
										</div>
										<div class="col-xs-12 col-md-6 col-sm-6">
											<div class="form-group">
												<label>Uraian</label>
												<input type='text' name='uraian_ss' id="uraian_ss" class="form-control">
											</div>
										</div>
										<div class="col-xs-12 col-md-3 col-sm-3">
											<div class="form-group">
												<label>Jumlah</label>
												<input type="text" name="jumlah_ss" id="jumlah_ss" class="form-control">
											</div>
										</div>
									</div>
								</div>
								<div class="panel-footer">
									<div class="row">
										<i class="fa fa-list"></i> Detail Pengajuan
										<div class="table-responsive h150">
											<table class="table table-bordered table-striped table-hover" id="lst_ss">
												<thead>
													<tr>
														<th>NO</th>
														<th>No.SPK</th>
														<th>Unit</th>
														<th>No.Mesin</th>
														<th>Jumlah</th>
														<th>Nama Customer</th>
														<th>Alamat</th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</fieldset>
						<fieldset id="titipuang" class='<?php echo ($jenis_transaksi=="Titipan Uang")?"":"hidden";?> <?php echo $status_posting;?>'>
							<div class="panel margin-bottom-10">
								<div class="panel-body panel-body-border-top">
									<div class="col-xs-12 col-md-3 col-sm-3">
										<div class="form-group">
											<label>No. Reff <span id="ldg_tipu"></span></label>
											<input type="text" name="no_reff_tp" id="no_reff_tp" class="form-control" value="<?php echo $no_reff;?>">
											<input type="hidden" name="source_tp" id="source_tp" value="TRANS_SPK.NO_SPK">
										</div>
									</div>
									<div class="col-xs-12 col-md-6 col-sm-6">
										<div class="form-group">
											<label>Nama Customer</label>
											<input type="text" name="ket_reff_tp" id="ket_reff_tp" class="form-control" value="<?php echo $ket_reff;?>">
										</div>
									</div>
									<div class="col-xs-12 col-md-3 col-sm-3">
										<div class="form-group">
											<label>Cara Pembayaran</label>
											<input type="text" name="carabayar_tp" id="carabayar_tp" class="form-control" value="<?php echo $carabayar;?>">
										</div>
									</div>
								</div>
							</div>
							<div class="">
								<!-- <div class="table-responsive h150"> -->
									<table class="table table-bordered table-striped" id="lst_ttp">
										<thead>
											<tr>
												<th>No.</th>
												<th class="col-md-6">Uraian Transaksi</th>
												<th class="col-md-1">Jumlah</th>
												<th class="col-md-2">Harga</th>
												<th class="col-md-2">Total Harga</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class='text-center'><input type='hidden' name='no_trans_tp' id="no_trans_tp"></td>
												<td><textarea name="uraian_titipan" rows="2" id="uraian_titipan" value="" class="form-control on-grid"></textarea></td>
												<td><input type="text" name="jumlah_titipan" id="jumlah_titipan" value="1" class="form-control on-grid"></td>
												<td><input type="text" name="harga_titipan" id="harga_titipan" value="0" class="form-control on-grid text-right"></td>
												<td><input type="text" name="t_harga_titipan" id="t_harga_titipan" value="0" class="form-control on-grid disabled-action text-right"></td>
											</tr>
										</tbody>
										<tfoot>
											
										</tfoot>
										<tr>
											<td colspan="5"><table class='table sp_info' id="info_spux"><tbody></tbody><tfoot></tfoot></table></td>
										</tr>
									</table>
								<!-- </div> -->
							</div>
						</fieldset>
						<!-- pinjaman sementara -->
						<fieldset  id="pjmsmtr" class='<?php echo ($jenis_transaksi=="Pinjaman Sementara")?"":"hidden";?> <?php echo $status_posting;?>'>
							<div class="panel margin-bottom-10">
								<div class="panel-body panel-body-border-top">
									<div class="col-xs-12 col-md-3 col-sm-3">
										<div class="form-group">
											<label>PIC <span id="kldg" style="color: red"></span></label>
											<input type="text" name="pic_reff_jp" id="pic_reff_jp" class="form-control" value="<?php echo $pic_reff_jp;?>" requeired>
										</div>
									</div>
									<div class="col-xs-12 col-md-3 col-sm-3">
										<div class="form-group">
											<label>Proposal No <span id="pldg" style="color: red"></span></label>
											<input type="text" name="no_reff_jp" id="no_reff_jp" class="form-control" value="<?php echo $no_reff;?>">
											<input type="hidden" name="source_jp" id="source_jp" value="TRANS_JOINPROMO.NO_TRANS">
										</div>
									</div>
									<div class="col-xs-12 col-md-6 col-sm-6">
										<div class="form-group">
											<label>Jenis Kegiatan</label>
											<input type="text" name="ket_reff_jp" id="ket_reff_jp" class="form-control" value="<?php echo $ket_reff;?>">
											<input type="hidden" name="ket_reff_jpn" id="ket_reff_jpn" class="form-control" value="<?php echo $ket_reff;?>">
										</div>
									</div>
								</div>
							</div>
							<div class="">
								<!-- <div class="table-responsive h150"> -->
								<table class="table table-bordered table-striped" id="lst_ttp">
									<thead>
										<tr>
											<th>No.</th>
											<th class="col-md-6">Uraian Transaksi</th>
											<th class="col-md-1">Jumlah</th>
											<th class="col-md-2">Harga</th>
											<th class="col-md-2">Total Harga</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class='text-center'>1</td>
											<td class=''><textarea  id="ket_joinpromo" class="on-grid" rows="2"></textarea></td>
											<td class='text-center'>1 Unit</td>
											<td>
												<input type='text'  class='text-right' style="padding-right: 8px" id="jml_joinpromo" name="jml_joinpromo" class="on-grid">
											</td>
											<td class='text-right' style="padding-right: 8px" id="total_jp"></td>
										</tr>
									</tbody>
								</table>
							</div>
						</fieldset>
						<!-- end of pinjaman sementara -->
					</div>
				</div>
				<!-- panel cara pembayaran -->
				<div class="col-xs-12 col-sm-3 col-md-3">
					<div class="panel margin-bottom-10 <?php echo $status_posting;?>">
						<div class="panel-heading">
							
						</div>
						<div class="panel-body panel-body-border-top">
							<div class="form-group">
			    				<label>Cara Pembayaran</label>
			    			</div>
			    			<div class="form-group">
			    				<label class="radio-inline"><input type="radio" <?php echo ($cb=="Cash")?"checked='true'":"";?>" name="cbayar" id="cbayar1" style="cursor: pointer;" value="Cash"> Cash</label>
			    				<label class="radio-inline"><input type="radio" <?php echo ($cb=="Cheque")?"checked='true'":"";?>" name="cbayar" id="cbayar2" style="cursor: pointer;" value="Cheque"> Cheque</label>
			    				<label class="radio-inline"><input type="radio" <?php echo ($cb=="KU")?"checked='true'":"";?>" name="cbayar" id="cbayar3" style="cursor: pointer;" value="KU"> KU</label>
			    			</div>
			    			<fieldset id="noncash" <?php echo ($cb=="Cash")?"class='disabled-action'":"";?>>
				    			<div class="form-group">
				    				<label>Nama Bank</label>
				    				<input type="text" name="nama_bank" id="nama_bank" autocomplete="off"  class="form-control" placeholder="Nama Bank penerbit" value="<?php echo $nama_bank;?>">
				    			</div>
				    			<div class="form-group">
				    				<label>No. Rekening</label>
				    				<input type="text" id="no_rekening" name="no_rekening" placeholder="Nomor Rekening" class="form-control" value="<?php echo $no_rekening;?>">
				    			</div>
				    			<div class="form-group">
				    				<label>No. Cheque / BG</label>
				    				<input type="text" name="no_cek"  id="no_cek" class="form-control" placeholder="Nomor Cheque atau Billyet" value="<?php echo $no_cek;?>">
				    			</div>
				    			<div class="form-group">
				    				<label>Tgl Jatuh Tempo</label>
				    				<div class='input-group append-group datetimez'>
				    					<input type="text" name="tgl_jthtempo" id="tgl_jthtempo" value="<?php echo $tgl_jthtempo;?>"   class="form-control" placeholder="dd/mm/yyyy" clearifnotmatch="true">
				    					<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span> </span>
				    				</div>
				    			</div>
			    			</fieldset>
			    		</div>
			    	</div>
			    	<div class="panel margin-bottom-10">
			    		<?php
			    			$no_kwt="disabled-action"; $no_kwitansi="0";
			    			$setup_nomor="disabled-action";
			    			if(isset($kwt) && $no_trans!=''){
			    				if($kwt->totaldata>0){
			    					foreach ($kwt->message as $key => $value) {
			    						$no_kwitansi = str_pad(($value->LAST_DOCNO),6,"0",STR_PAD_LEFT);
			    					}
			    				}else{
			    					$setup_nomor="";
			    					$no_kwt ="disabled-action";
			    				}
			    			}
			    		?>
			    		<fieldset id="kwt" class="<?php echo ($no_kwitansi)?'disabled-action':'';?> <?php echo $status_c;?>">
				    		<div class="panel-body panel-body-border-top">
					    		<div class="form-group">
					    			<!-- <label>No. Kwitansi</label> -->
					    			<!-- <div class="input-group"> -->
					    				<a class='btn btn-info modal-button btn-sm' title="setup Nomorator kwitansi" id="modal-button-2" url="<?php echo base_url('cashier/kwitansi_setup/');?>" role="button" data-toggle="modal" data-target="#myModalDf" data-backdrop="static"><i class="fa fa-cog fa-fw"></i> Setup Nomorator Kwitansi</a>
						            	<!-- </span> -->
						            <!-- </div> -->
					    		</div>
					    		
					    	</div>
				    	</fieldset>
			    	</div>
				</div> 
				<!-- end of panel cara pembayaran -->
				<input type="hidden" id="reopen_status" name="reopen_status">   	
			</form>
		</div>
	<!-- </fieldset> -->
	<?php echo loading_proses();?>
	<!-- modal proses printing kwitansi dan confirmasi -->
	<div class="modalx" id="print_kwts">
		<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
		<div class="modal-contentxx">
			<div class="panel-body panel-body-border-top">
				<div class="row">
	    			<div class="col-md-6 col-xs-12 col-sm-6">
		    			<div class="form-group">
			    			<label>No. Kwitansi</label>
			    			<div class="input-group">
			    				<input type="text" name="no_kwitansi" id="no_kwitansi" placeholder="Nomor Kwitansi yng digunakan" class="form-control <?php echo $printkw;?>" value="<?php echo $no_kwitansi;?>">

				            </div>
				        </div>
		    		</div>
			        <div class="col-md-6 col-xs-12 col-sm-6">
			    		<div class="form-group "><br>
			    			<button class="pull-right btn btn-default" id="piutang" onclick="add_piutang($no_trans);">Masukan Ke Piutang</button>	    			
			    		</div>
		            </div>
	    		</div>
	    		<a class="btn btn-primary <?php echo $print_kwitansi;?>" id="modal-button" 
	    			onclick='addForm("<?php echo base_url('cashier/kwitansi_print/'.urlencode(base64_encode($no_trans))); ?>"+"/"+$("#no_kwitansi").val());'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class='fa fa-print'></i> Print Kwitansi</a>
	    		<a class="btn btn-primary <?php echo $status_posting." ".$print_nota;?>" 
	    			id="modal-button-1" onclick='addForm("<?php echo base_url('cashier/kwitansi_nota/'.urlencode(base64_encode($no_trans))); ?>"+"/"+$("#no_kwitansi").val());'  
	    			role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class='fa fa-print'></i> Print Nota
	    		</a>
	    		
	    		<a class="btn btn-primary disabled-action modal-button" id="modal-button-3" url="<?php echo base_url('cashier/kwitansi_register/'.urlencode(base64_encode($no_trans))); ?>" role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-registered"></i> Print Register</a>
	    		<a class="btn btn-danger" id="modal-button-5" onclick="__cancel_transaksi('<?php echo $no_trans;?>','1');" title="Cancel transaksi"><i class="fa fa-trash"></i> Cancel</a>
				<input type="hidden" id="sts_lkh" name="sts_lkh" value="<?php echo $lkh_status;?>">
				<a class="btn btn-danger hidden" id="modal-button-4" onclick="__batal();" title="Batal Reprint"><i class="fa fa-trash"></i> Cancel</a>
	    	</div>
	    </div>
	</div>
	<!-- end of proses pinting kwitansi -->
	<!-- list transaksi untuk posting data / proses register hidden mode  -->
	<input type='hidden' id='min_value' value="">
</section>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/kasirnew.js?v=").date('YmdHis');?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/kasir.js?v=").date('YmdHis');?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/kasirpinjaman.js?v=").date('YmdHis');?>"></script>
<script type="text/javascript">
	var path = window.location.pathname.split('/');
	var http = window.location.origin + '/' + path[1];
	var no_transx="<?php echo $no_trans;?>";
	$(document).ready(function(){
		var sts_prnt="<?php echo $print_status;?>";
		var lkh ="<?php echo $lkh_status;?>";
		var tp_trans="<?php echo $this->input->get("tp");?>"
		__getMinimalValue('<?php echo $jenis_transaksi;?>');
		__nomorator_kwt();//check penomoran kwitansi
		var saldoawal="<?php echo $saldo_awal;?>";
		// check saldo awal di setiap transaksi
			if(parseFloat(saldoawal)<1 && no_transx=='' && tp_trans=='Pengeluaran' ){
				alert("Saldo Awal Tidak Mencukupi untuk melakukan transaksi hari ini\nSilahkan Tambahkan dulu Saldo Awal nya");
				$("#smp").addClass("disabled-action");
			}else{
				//$("#smp").removeClass("disabled-action");
			}
		
		
		if((parseInt(sts_prnt)==0 || parseInt(lkh)==0)&& no_transx!=''){
			$('.modalx').show();
			if(sts_prnt==0){
				$('#modal-button').removeClass("disabled-action");
				$('#modal-button-3').addClass("disabled-action");
				$('#modal-button-1').removeClass("disabled-action");
				$('#modal-button-5').removeClass("disabled-action").removeClass("hidden");
				$('#modal-button-4').addClass("disabled-action").addClass("hidden");
				//__nomorator_kwt();
			}
			if(parseInt(sts_prnt)>=1 && lkh==0){
				$('#modal-button-3').removeClass("disabled-action");
				$('#modal-button').addClass("disabled-action");
				$('#modal-button-1').addClass("disabled-action");
				$('#modal-button-5').removeClass("disabled-action").removeClass("hidden");
				$('#modal-button-4').addClass("disabled-action").addClass("hidden");
			}
		}

		

		var date = new Date();
    	date.setDate(date.getDate());
		$('.datetimez').datetimepicker({
            format: 'DD/MM/YYYY',
            minDate: date
	    });
		//console.log(__getMinimal('Pengeluaran Umum'))
	})

	function __getMinimalValue(jenis_trans){
	$.getJSON(http+"/cashier/minimal_value",{'jt':jenis_trans},function(result){
		console.log(result)
		if(result){
			if(parseFloat(result)>0){
				$('#min_value').val(result);
				__checkStatusAproval(no_transx,result)
			}
		}
		
		//__checkSudahDiApprove(result)
	})
}
	/*window.onafterprint = function() {
   		console.log('afterprint')
        window.location.reload(true);
    };*/
	function __printKW(){
		$('.modalx').hide();		
	}
	/**
	 * Cechk min pengeluaran yang memerlukan approval
	 * dibuat config karena ada perbedaan minimal pengeluaran yang perlu di aproval 
	 * @param      {string}  no_transx  No transx
	 */
	function __checkStatusAproval(no_transx,minVal){
		var no_transx="<?php echo $no_trans;?>";
		var sts_vch="<?php echo $voucher_status;?>";
		var jenist="<?php echo $jenis_transaksi;?>";
		var sts_pos="<?php echo ($sts_pst)?$sts_pst:'0';?>";
		var sts_lkh="<?php echo $sts_lkh;?>";
		var jml=$('#jumlah_u').val().replace(/,/g,'');
		switch(jenist){
			case 'Pengeluaran Umum' :
			case 'Biaya Hadiah':

			console.log(sts_vch.length+':'+parseInt(sts_pos))
			if(parseFloat(jml)>parseFloat(minVal) && (sts_vch.length ==0) && no_transx!=''){
				if(confirm("Transaksi melebihi "+parseFloat(minVal).toLocaleString()+" harus melalui approval <?php echo $yangApproval;?>dahulu\nApakah Transaksi akan dilanjutkan?\nTekan OK untuk Lanjut Cancel untuk membatalkan transaksi")){
					__Simpan_Approval(no_transx);
				}else{
					__dibatalkan();
				}
			}else{
				__checkSudahDiApprove(minVal);
			}
			break;
			default:
				__checkSudahDiApprove(minVal);
			break;
		}
		
	}
	/**
	 * check pengeluaran yang perlu di approv
	 * jika sudah di approve konfirmasi apakah akan di lakukan proses selanjutnya
	 */
	function __checkSudahDiApprove(minVal){
		var sts_vch="<?php echo $voucher_status;?>";
		var sts_pos="<?php echo ($sts_pst)?$sts_pst:'0';?>";
		var sts_lkh="<?php echo ($sts_lkh)?$sts_lkh:'0';?>";
		var no_transx="<?php echo $no_trans;?>";
		var jenist="<?php echo $jenis_transaksi;?>";
		var p="<?php echo $this->input->get('p');?>"
		var jml=$('#jumlah_u').val().replace(/,/g,'');
		console.log(sts_vch.length+':'+parseInt(sts_pos)+":"+parseInt(sts_lkh))
		if((sts_vch.length) >4 && (parseInt(sts_pos)==0 || parseInt(sts_lkh)==0) && no_transx==''/* && p==''*/){
			if (confirm("Ada transaksi yng sudah di Approve\nApakah mau di proses No. Trans : <?php echo $no_apv;?> ini?")){
				document.location.href=http+"/cashier/kasirnew?n=<?php echo $no_url;?>";
			}
		}else{
			console.log(jenist+"=="+jml+"="+minVal);
			if(sts_vch.length==1 && (jenist=='Pengeluaran Umum' || jenist=='Biaya Hadiah') && parseFloat(jml)>parseFloat(minVal)){
				alert('Transaksi ini belum di Approve, Tidak bisa di lanjutkan\nHubungi <?php echo $yangApproval;?> untuk Approval');
				$('#baru').click();
			}
		}
	}
	function __dibatalkan(){
		$('.modalx').show();
		$('#modal-button').addClass("disabled-action");
		$('#modal-button-3').addClass("disabled-action");
		$('#modal-button-1').addClass("disabled-action");
		$('#modal-button-5').removeClass("disabled-action").removeClass("hidden");
		$('#modal-button-4').addClass("disabled-action").addClass("hidden");
	}
	function __reprint(){
		$('.modalx').show();
		$('#modal-button').removeClass("disabled-action");
		$('#modal-button-3').addClass("disabled-action");
		$('#modal-button-1').addClass("disabled-action");
		$('#modal-button-5').addClass("disabled-action").addClass("hidden");
		$('#modal-button-4').removeClass("disabled-action").removeClass("hidden");
	}
	function __regigster(){
		$('.modalx').show();
		$('#modal-button').addClass("disabled-action");
		$('#modal-button-3').removeClass("disabled-action");
		$('#modal-button-5').addClass("disabled-action").addClass("hidden");
		$('#modal-button-4').removeClass("disabled-action").removeClass("hidden");;
	}
	function __batal(){
		$('.modalx').hide();
	}
</script>