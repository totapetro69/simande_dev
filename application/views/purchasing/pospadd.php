<?php
if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $status_n = ($this->session->userdata("nama_group")=="Root")?"":"disabled='disabled'";
  $pilih=$this->input->get('pilih');
  $defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
  $dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('First day of previous month'));
  $sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y",strtotime('first day of next month'));
  $tgl_trans="";
  $no_mesin=$this->input->get("keyword");
  $no_po=($this->input->get("n"))?base64_decode($this->input->get("n")):"";
  $jenis_order="";$tgl_po="";$nama_konsumen="";$kd_konsumen="";$no_telp="";$alamat_konsumen="";
  $kota_konsumen="";$vor="";$jrs="";$bulan="";$tahun="";$kd_typemotor="";$tahun_motor="";$approval=0;
  $salesOrder=base64_decode(urldecode($this->input->get("so")));
  $jenis_order=($salesOrder)?'Hotline':"";$keterangan="";
  if(isset($po)){
  	if((int)$po->totaldata>0){
  		foreach ($po->message as $key => $value) {
  			$no_po 			= $value->NO_PO;
  			$jenis_order	= $value->JENIS_PO;
  			$tgl_trans		= $value->TGL_PO;
  			$bulan 			= $value->BULAN;
  			$tahun 			= $value->TAHUN;
  			$nama_konsumen	= $value->NAMA_KONSUMEN;
  			$no_telp 		= $value->NO_TELP;
  			$alamat_konsumen= $value->ALAMAT_KONSUMEN;
  			$kota_konsumen 	= $value->KOTA_KONSUMEN;
  			$vor 			= $value->VOR;
  			$jrs 			= $value->JSR;
  			$kd_typemotor	= $value->TYPE_MOTOR;
  			$tahun_motor	= $value->TAHUN_MOTOR;
  			$approval		= ($value->APPROVAL)?$value->APPROVAL:"";
  			$salesOrder		= $value->REFF_NO;
  			$keterangan 	= $value->KETERANGAN;
  		}
  	}
  }
  $modeEdit=($no_po!='')?"disabled-action":"";
  $SudahDiApprove=($approval)?"disabled-action":"";
  $jikaSalesorder=($salesOrder)?"disabled-action":"";
  // data dari salesOrder
  $datacustomer=null; $kd_customer="";
  if(isset($soh)){
  	if($soh->totaldata>0){
  		foreach ($soh->message as $key => $value) {
  			$kd_customer 	= $value->KD_CUSTOMER;
  			$vor 			= $value->VOR;
  			$jrs 			= $value->JR;
  			$kd_typemotor	= $value->KD_TYPEMOTOR;
  			$tahun_motor	= $value->TAHUN_MOTOR;
  		}
  	}
  }

 $datacustomer=(isset($suc))?$suc:null;//infoCustomer($kd_customer,true);
 //var_dump($datacustomer);
 //$nama_konsumen="";$no_telp="";$alamat_konsumen="";$kode_pos="";
  if($datacustomer){
  	if($datacustomer->totaldata>0){
  		foreach ($datacustomer->message as $key => $value) {
  			$nama_konsumen	= $value->NAMA_CUSTOMER;
  			$nama_konsumen 	.= ($value->NO_POLISI)?" [".strtoupper($value->NO_POLISI)."]":"";
  			$no_telp 		= $value->NO_HP;
  			$alamat_konsumen= strtoupper($value->ALAMAT_SURAT." ".$value->NAMA_DESA.", ".$value->NAMA_KECAMATAN) ;
  			$kota_konsumen 	= $value->NAMA_KABUPATEN;
  			$kode_pos		= $value->KODE_POS;
  			$tahun_motor 	= $value->TAHUN_MOTOR;
  		}
  	}
  }
  $app_level="";
  $app_doc="";
  if(isset($approvale)){
		if($approvale->totaldata >0){
			foreach ($approvale->message as $key => $value) {
				$app_level 	= $value->APP_LEVEL;
				$app_doc 	= $value->KD_DOC;
			}
		}
	}
	$disabled =($app_level >0)?"":"disabled-action";
	$statusnya=($approval=="-1")?"<small style='color:red'> <i class='fa fa-info-circle'></i> Status Rejected : ".$keterangan."</small>":"";
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
		<div class="bar-nav pull-right">
			<a class="btn btn-default" role="button" id="baru"><i class='fa fa-file-o'></i> Baru</a>
			<a class="btn btn-default <?php echo $SudahDiApprove;?>" role="button" id="simpan" onclick="__simpan_po();">
				<?php if($no_po==""):?><i class='fa fa-save'></i> Simpan <?php else: ?><i class='fa fa-save'></i> Update <?php endif;?></a>
			<?php if($no_po!="" && $approval==0):?><a class="btn btn-default <?php echo $disabled;?>" role="button" id="approval" onclick="__approvalPO('1')"><i class='fa fa-pencil-square-o'></i> Submit PO</a><?php endif;?>
			<?php if($no_po!="" && $approval>0):?><a class="btn btn-default <?php echo $disabled;?>" role="button" id="approval" onclick="__approvalPO('2')"><i class='fa fa-close'></i> Reject PO</a><?php endif;?>
			
			<a class="btn btn-default" role="button" href="<?php echo base_url("purchasing/posp_list");?>"><i class='fa fa-list-ul'></i> List PO</a>
		</div>
	</div>
	<?php //echo $soh->totaldata;?>
	<div class="col-lg-12 padding-left-right-10">
		<div class="panel margin-bottom-10">
			<div class="panel-heading panel-custom">
				<h4 class="panel-title" style="padding-top: 10px"><i class='fa fa-file-o'></i> Input PO Sparepart <span id="statuse" style="color: red"><?php echo $statusnya;?></span></h4>
				<span class="tools pull-right">
	              <!-- <a class="fa fa-chevron-down" href="javascript:;"></a> -->
	              
	            </span>
	        </div>
	        <div class="panel-body panel-body-border">
	        	<form id="frmpoheader" method="post">
	        	<div class="col-xs-12 col-sm-6 col-md-6">
		        	<div class="col-xs-12 col-sm-6 col-md-6">
		        		<div class="form-group">
		        			<label>Dealer</label>
		        			<select class="form-control  <?php echo $SudahDiApprove." ".$modeEdit;?>" id="kd_dealer" name="kd_dealer" required="true">
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
		        	<div class="col-xs-12 col-sm-6 col-md-6">
		        		<div class="form-group">
		        			<label>Jenis Order</label>
		        			<select class="form-control <?php echo $SudahDiApprove." ".$modeEdit;?>" id="jenis_order" name="jenis_order" required="true">
		        				<option>--Pilih Jenis Order</option>
		        				<option value='Reguler' <?php echo ($jenis_order=="Reguler" || $jenis_order=="")?"selected":"";?>>Reguler</option>
		        				<option class='<?php echo ($salesOrder)?'':'hidden';?>' value='Hotline' <?php echo ($jenis_order=="Hotline")?"selected":"";?>>Hotline</option>
		        				<option value='Fix' <?php echo ($jenis_order=="Fix")?"selected":"";?>>Fix</option>
		        				<option value='Canvasing' <?php echo ($jenis_order=="Canvasing")?"selected":"";?>>Canvasing</option>
		        				<option value='NRFS' <?php echo ($jenis_order=="NRFS")?"selected":"";?>>Urgent (NRFS)</option>
		        			</select>
		        		</div>
		        	</div>
		        	
		        	<div class="col-xs-12 col-sm-6 col-md-6">
		        		<div class="form-group">
		        			<label>Tanggal PO</label>
		        			<div class="input-group append-group date">
	    						<input type="text" class="form-control <?php echo $SudahDiApprove;?>" id="tgl_po" name="tgl_po" value="<?php echo($tgl_trans=='')? date("d/m/Y"):tglFromSql($tgl_trans);?>" required="true">
	    						 <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span> </span>
	    					</div>
		        		</div>
		        	</div>
		        	<div class="col-xs-12 col-sm-6 col-md-6">
		        		<div class="form-group">
		        			<label>No. PO</label>
		        			<span class="pull-right">
		        				<input type="checkbox" name="fso" id="fso" style="cursor: pointer;" class=" <?php echo $SudahDiApprove;?>">
		        				<label>Load <abbr title="Suggested Order : List barang yang di rekomedasikan untuk di beli berdasarkan perhitungan sistem">Suggested Order</abbr></label></span>
		        			<input type="text" name="no_po" id="no_po" value='<?php echo $no_po;?>' class="form-control" placeholder="Auto generate PO number" readonly="true">
		        		</div>
		        	</div>
		        	<div class="col-xs-12 col-md-3 col-sm-3">
		        		<div class="form-group">
		        			<label>Periode Bulan</label>
		        			<select id="bulan_kirim" name="bulan_kirim" class="form-control <?php echo $SudahDiApprove;?>">
		        				<?php
		        					for($i=0;$i<=12;$i++){
		        						$pilih =(date('m')==$i)?"selected":"";
		        						echo "<option value='$i' $pilih >".nBulan($i)."</option>";
		        					}
		        				?>
		        			</select>
		        		</div>
		        	</div>
		        	<div class="col-xs-12 col-md-3 col-sm-3">
		        		<div class="form-group">
		        			<label>Tahun</label>
		        			<select id="tahun_kirim" name="tahun_kirim" class="form-control <?php echo $SudahDiApprove;?>">
		        				<option value='<?php echo date('Y')+1;?>'><?php echo date('Y')+1;?></option>
		        				<option value='<?php echo date('Y');?>' selected="true"><?php echo date('Y');?></option>
		        				<option value='<?php echo date('Y')-1;?>'><?php echo date('Y')-1;?></option>
		        			</select>
		        		</div>
		        	</div>
		        	<div class="col-xs-12 col-md-6 col-sm-6">
		        		<div class="form-group">
		        			<label>No. Reff</label>
		        			<input type="text" class="form-control" id="no_reff" name="no_reff" placeholder="No sales order" value="<?php echo $salesOrder;?>">
		        		</div>
		        	</div>		        	
		        	<div class="separator" role="divider"></div>
		        	<div class="col-xs-12 col-md-8 col-sm-8">
		        		<div class="form-group">
		        			<label>Part Number <span id="fd"></span></label>
		        			<input type="text" name="part_number" id="part_number" class="form-control typeahead <?php echo $jikaSalesorder.' '. $SudahDiApprove;?>" placeholder="Input Part Number atau nama part">
		        		</div>
		        	</div>
		        	<div class="col-xs-12 col-md-4 col-sm-4">
		        		<div class="form-group">
		        			<label>Jumlah</label>
		        			<div class="input-group">
			        			<input type="text" name="jml_order" id="jml_order" class="form-control <?php echo $jikaSalesorder.' '.$SudahDiApprove;?>" data-mask="000000" placeholder="Jumla PO">
			        			<span class="input-group-btn " id="appd" title="Add Part">
				            	 	<button id="btn-simpan" class='btn btn-default disabled-action' type='button' onclick="add_item();"><i class="fa fa-plus fa-fw"></i></button>
				            	</span>
				            </div>
		        		</div>
		        	</div>
		        	<div class="col-xs-12 col-md-8 col-sm-8">
		        		<div class="form-group">
		        			<label>Part Deskripsi</label>
		        			<input type="text" name="nama_part" id="nama_part" class="form-control disabled-action" placeholder="Deskripsi part">

		        		</div>
		        	</div>
		        	<div class="col-xs-12 col-md-4 col-sm-4">
		        		<div class="form-group">
		        			<label>Harga/Pcs</label>
		        			<div class="input-group">
		        				<input type="text" name="harga" id="harga" value="0" class="form-control disabled-action">
		        				<span class="input-group-addon"><input type="checkbox" id="ppne" name="ppne" checked="true" title="Hitung PPN"> PPN</span>
		        			</div>
		        		</div>
		        	</div>
	        	</div>
    			<div class="col-xs-12 col-md-6 col-sm-6">
		        	<fieldset class='xx disabled-action'>
		        		<div class="col-xs-12 col-md-8 col-sm-8">
	        				<div class="form-group">
	        					<label>Nama Konsumen</label>
	        					<input type="text" name="nama_konsumen" id="nama_konsumen" class="form-control <?php echo $SudahDiApprove;?>" placeholder="Nama Konsumen / autocomplete existing consumen" value='<?php echo $nama_konsumen;?>'>
	        				</div>
	        			</div>
	        			<div class="col-xs-12 col-sm-4 col-md-4">
	        				<div class="form-group">
	        					<label>No. Telp</label>
	        					<input type="text" name="no_telp" id="no_telp" class="form-control <?php echo $SudahDiApprove;?>" placeholder="No telp konsumen" value='<?php echo $no_telp;?>'>
	        				</div>
	        			</div>
	        			<div class="col-xs-12 col-md-12 col-sm-12">
	        				<div class="form-group">
	        					<label>Alamat</label>
	        					<textarea class="form-control <?php echo $SudahDiApprove;?>" name="alamat_konsumen" id="alamat_konsumen" placeholder="Alamat Jl, Kelurahan, kecamatan"><?php echo $alamat_konsumen;?></textarea>
	        				</div>
	        			</div>
        				<div class="col-xs-12 col-md-8 col-sm-8">
	        				<div class="form-group">
	        					<label>Kota</label>
	        					<input type="text"  name="kota_konsumen" id="kota_konsumen" placeholder="kota dan propinsi" class="form-control <?php echo $SudahDiApprove;?>" value='<?php echo $kota_konsumen;?>'>
	        				</div>
	        			</div>
	        			<div class="col-xs-12 col-md-4 col-sm-4">
	        				<div class="form-group">
	        					<label>Kode Pos</label>
	        					<input type="text" name="kd_pos" id="kd_pos" value='<?php echo $kode_pos;?>' class="form-control <?php echo $SudahDiApprove;?>" data-mask="00000">
	        				</div>
	        			</div>
	        			<div class="col-xs-12 col-sm-6 col-md-6">
	        				<div class="form-group">
	        					<label>Type Motor</label>
	        					<input type="text"  name="kd_typemotor" value='<?php echo $kd_typemotor;?>' id="kd_typemotor" class="form-control <?php echo $SudahDiApprove;?>" placeholder="Kode Type Motor" maxlength="3">
	        				</div>
	        			</div>
	        			<div class="col-xs-12 col-sm-6 col-md-6">
	        				<div class="form-group">
	        					<label>Motor Tahun</label>
	        					<input type="text" name="thn_motor" id="thn_motor"  value='<?php echo $tahun_motor;?>' class="form-control <?php echo $SudahDiApprove;?>" placeholder="Tahun Perakitan" data-mask="0000" >
	        				</div>
	        			</div>
	        			<div class="col-xs-12 col-sm-6 col-md-6">
	        				<div class="form-group">
	        					<label>Vehicle Of The Road 
	        						<abbr title='VOR Pilih Y apabila selama motor diperbaiki motor tersebut menginap di bengkel/Tidak bisa di Gunakan. Pilih N jika motor tidak menginap dan bisa digunakan sementara oleh konsumen'>(VOR)</abbr>
	        					</label>
	        					<select class="form-control <?php echo $SudahDiApprove;?>" id="vor" name="vor">
	        						<option value='N' <?php echo ($vor=="N")? "selected":"";?>>N</option>
	        						<option value='Y' <?php echo ($vor=="Y")? "selected":"";?>>Y</option>
	        					</select>
	        				</div>
	        			</div>
	        			<div class="col-xs-12 col-md-6 col-sm-6">
	        				<div class="form-group">
	        					<label>Job Return Service <abbr title="Pilih Y Jika Terkait Job Return">(JR)</abbr></label>
	        					<select class="form-control <?php echo $SudahDiApprove;?>" id="jr" name="jr">
	        						<option value='N' <?php echo ($jrs=="N")? "selected":"";?>>N</option>
	        						<option value='Y' <?php echo ($jrs=="Y")? "selected":"";?>>Y</option>
	        					</select>
	        				</div>
	        			</div>
	        		</fieldset>
	        	</div>
	        	</form>
	    	</div>
	    </div>
	</div>
	<div class="col-lg-12 padding-left-right-10">
		<div class="panel panel-default">
			<div class="table-responsive h250">
				<table class="table table-bordered table-striped" id="listpo">
					<thead>
						<tr>
							<th>No.</th>
							<th>&nbsp;</th>
							<th class="col-sm-2">Part Number</th>
							<th class="col-sm-5">Part Deskription<span class="pull-right" style="color: red" id="lgl"></span></th>
							<th>Jumlah</th>
							<th>Harga/Pcs</th>
							<th>PPN</th>
							<th>Total Harga</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody class="<?php echo $SudahDiApprove;?>">
						<?php
						// var_dump($podetail);
						 if(isset($podetail)){
						 	if($podetail){
						 		if($podetail->totaldata>0){
						 			$n=0;
						 			foreach ($podetail->message as $key => $value) {
						 				echo "<tr>
												<td class='text-center' valign='middle'>".($n+1)."</td>
												<td class='text-center' valign='middle'><a onclick=\"hapusID('" . $value->ID . "','".$n."');\"><i class='fa fa-trash'></i></a><span id='ld_".$n."'></span></td>
												<td class='text-center' valign='middle'>".$value->PART_NUMBER."</td>
												<td valign='middle'>".$value->PART_DESKRIPSI."</td>
												<td class='text-right'><input type='text' class='on-grid form-control' id='jml_p".$n."' name='jml_p".$n."' value='".$value->JUMLAH."'/></td>
												<td valign='middle' class='text-right'>".number_format($value->HARGA,2)."</td>
												<td valign='middle' class='text-right'>".number_format($value->PPN,2)."</td>
												<td valign='middle' class='text-right'>".number_format(($value->HARGA * $value->JUMLAH),2)."</td>
												<td>&nbsp;</td>
											</tr>
						 					 ";
						 					 $n++;
						 			}
						 		}
						 	}
						 }
						 // Dari salesOrder
						 if(isset($sod)){
						 	if($sod){
						 		if($sod->totaldata>0){
						 			$n=0;
						 			foreach ($sod->message as $key => $value) {
						 				echo "<tr>
												<td class='text-center' valign='middle'>".($n+1)."</td>
												<td class='text-center' valign='middle'><a class='disabled-action' onclick=\"hapusID('" . $value->ID . "','".$n."');\"><i class='fa fa-trash'></i></a><span id='ld_".$n."'></span></td>
												<td class='text-center' valign='middle'>".$value->PART_NUMBER."</td>
												<td valign='middle'>".$value->PART_DESKRIPSI."</td>
												<td class='text-right'><input type='text' class='on-grid form-control' id='jml_p".$n."' name='jml_p".$n."' value='".$value->JUMLAH_ORDER."'/></td>
												<td valign='middle' class='text-right'>".number_format($value->HARGA_JUAL,2)."</td>
												<td valign='middle' class='text-right'>".number_format(($value->HARGA_JUAL-($value->HARGA_JUAL/1.1)),0)."</td>
												<td valign='middle' class='text-right'>".number_format(($value->HARGA_JUAL * $value->JUMLAH_ORDER),2)."</td>
											</tr>
						 					 ";
						 					 $n++;
						 			}
						 		}
						 	}
						 }
						?>

					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php echo loading_proses();?>
</section>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/pospadd.js");?>"></script>

<script type="text/javascript">
	$(document).ready(function(e){
		

	})
	

	$('#jenis_order').change(function(){ 
		var jenis_order = $('#jenis_order').val();
		if (jenis_order == 'NRFS' ) {
			
			__get_mutasi_nrfs()
		}  
     	
    })

    function __get_mutasi_nrfs(){    
    var datas=[];
    $.getJSON(http+"/purchasing/getMutasiNRFS",{'d':$('#kd_dealer').val()},function(result){
      if(result.length>0){
        $.each(result,function(e,d){
          datas.push({
            'value' : d.NO_TRANS,
            'text'  : d.NO_TRANS,
            'No.Trans': d.NO_TRANS,
            'kd_typemotor': d.KD_TYPEMOTOR,
            'nama_typemotor': d.NAMA_TYPEMOTOR,
            'Tahun Motor': d.THN_PERAKITAN
            
          })
        })
      }
    });
    $('#no_reff').inputpicker({
        data : datas,
        fields :['No.Trans'],
        fieldText : 'text',
        fieldValue : 'value',
        filterOpen : false,
        headShow:true
      }).change(function(e){
        e.preventDefault();
         var dx=datas.findIndex(obj => obj['value'] === $(this).val());
         
         if(dx>-1){
         	//alert(datas[dx]['kd_typemotor']);
          $('#kd_typemotor').val(datas[dx]['kd_typemotor']);
          $('#nama_typemotor').val(datas[dx]['nama_typemotor']);
          $('#thn_motor').val(datas[dx]['Tahun Motor']);
         }
      })
      
  }
</script>