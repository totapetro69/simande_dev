<?php
	if (!isBolehAkses()) {redirect(base_url() . 'auth/error_auth');}
	$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  	$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  	$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  	$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  	$defaultDealer=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
	$default="";$KD_DEALER="";$no_po="";$periode="";$bulan="";
	$tahun="";$jenispo="";$tglpo="";$tglselesaipo="";$approval=0;$po_id=0;$alamat='';$kab_prov='';$tlp='';$status_po=0;
	if(base64_decode(urldecode($this->input->get("n")))){
		if(isset($poheader)){
			if($poheader->totaldata >0){
				foreach ($poheader->message as $key => $value) {
					$KD_DEALER 	= $value->KD_DEALER;
					$kd_md		= $value->KD_MAINDEALER;
					$no_po 		= $value->NO_PO;
					$periode 	= ($value->KD_JENISPO!='F' )?($value->PERIODE_PO):$value->PERIODE_PO." (".tglfromSql($value->TGL_AWALPO)." sd ".tglfromSql($value->TGL_AKHIRPO)." )";
					$bulan 		= $value->BULAN_KIRIM;
					$tahun 		= $value->TAHUN_KIRIM;
					$jenispo 	= $value->KD_JENISPO;
					$tglpo 		= TglFromSql($value->TGL_PO);
					//$tglselesaipo 		= TglFromSql($value->TGL_SELESAI_PO);
					$approval	= ($value->APPROVAL_PO)?$value->APPROVAL_PO:"0";
					$po_id 		= $value->ID;
					$status_po 		= $value->STATUS_PO;
					$defaultDealer = $value->KD_DEALER;
					$alamat 	= $value->ALAMAT." ".$value->NAMA_KABUPATEN.", ".$value->NAMA_PROPINSI."&#10;".$value->TLP.'/'.$value->TLP2;
				}
				
			}
		}
	}
	if($status_po == 0){
		$status_po_text = "Draft";
	} else if ($status_po == -1){
		$status_po_text = "Rejected by MD";
	} else if ($status_po == 1){
		if ($approval == 1){
			$status_po_text = "Submitted";
		} else {
			$status_po_text = "Returned by MD";
		}
	} else if($status_po == 2){
		$status_po_text = "Processed by MD";
	} else {
		$status_po_text = "unknow";
	}
	$cetak=(!$no_po)?' disabled-action':'';
	$cetak=(isBolehAkses("p"))? $cetak:'disabled-action';
	$downldh=((int)$approval >0)?'':'disabled-action';
	$mode = $this->input->get('b');
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
        <div class="bar-nav pull-right ">
        	<a class="btn btn-default" href="#" role="button" onclick="add_new();">
                <i class="fa fa-file-o fa-fw"></i> Baru
            </a>
        	<a class="btn btn-default <?php echo $status_c;?>" id="submit-btns" href="#" role="button" onclick="simpan_po();"  <?php echo (int)$approval > 0? 'disabled="true"':'';?>>
                <i class="fa fa-save fa-fw"></i> Simpan
            </a>
        	<a id="modal-button" class="btn btn-default <?php echo $cetak;?> <?php echo $status_p;?>" onclick='addForm("<?php echo base_url('purchasing/cetak_po?n='.(($this->input->get('n'))).''); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-print fa-fw"></i> Cetak
            </a>
            <a type="button" class="<?php echo $downldh ;?> btn btn-default <?php echo $cetak;?> <?php echo $status_p;?>" href="<?php echo base_url('purchasing/createfile_udpo?n='.$this->input->get('n').'');?>" >
                    <i class="fa fa-download fa-fw"></i> Download file .UDPO  <!-- <span class="caret"></span> -->
                </a>
         	<a class="btn btn-default" href='<?php echo base_url('purchasing/PO_list'); ?>' role="button" data-toggle="modal" data-backdrop="static">
                <i class="fa fa-table fa-fw"></i> List PO
            </a>
        </div>
	</div>
	<div class="col-lg-12 padding-left-right-10">
    	<div class="panel margin-bottom-5">
    		<div class="panel-heading">
                <i class="fa fa-list fa-fw"></i> PO Header <?php //echo $approval;?>
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
                <input type='hidden' id="apv_sts" value="<?php echo $approval;?>">
            </div>
            <div class="panel-body panel-body-border">
				 <form id="po_form" method="post" action="">
				    <div class="row">
				        <div class="padding-left-right-10">
				        	<div class="row">
			        			<div class="col-xs-6 col-sm-4 col-md-4">
			        				<div class="form-group">
										<!-- <?php var_dump($dealer); ?> -->
			        					<label>Dealer</label>
			        					<select name="kd_dealer" id="kd_dealer" class="form-control" required="true">
								          <option value="">- Pilih Dealer -</option>
								          <?php foreach ($dealer->message as $key => $group) { 
								          		$pilih =($defaultDealer == $group->KD_DEALER)?' selected':'';
								          	?>
								            <option value="<?php echo $group->KD_DEALER;?>" <?php echo $pilih;?>><?php echo $group->NAMA_DEALER;?></option>
								          <?php 
								      		} ?>
								        </select>
								    </div>
								</div>
								<div class="col-xs-6 col-md-2 col-sm-2">
			        				<div class="form-group">
			        					<label>Tanggal PO</label>
			        					<div class="input-group input-append date" id="date">
						                    <input type="text" class="form-control" id="tgl_po" required="true" name="tgl_po" value="<?php echo ($tglpo)?$tglpo: date("d/m/Y");?>" <?php echo ($tglpo)?"disabled":"";?> placeholder="dd/mm/yyyy" />
						                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
						                </div>
						            </div>
						        </div>
								<!--div class="col-xs-6 col-md-2 col-sm-2">
			        				<div class="form-group">
			        					<label>Tanggal Selesai PO</label>
			        					<div class="input-group input-append date" id="datenext">
						                    <input type="text" class="form-control" id="tgl_selesai_po" required="true" name="tgl_selesai_po" value="<?php //echo ($tglselesaipo)?$tglselesaipo: date("d/m/Y");?>" <?php //echo ($tglselesaipo)?"disabled":"";?> placeholder="dd/mm/yyyy" />
						                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
						                </div>
						            </div>
								</div--->
						        <div class="col-xs-6 col-md-3 col-sm-3">
						        	<div class="form-group">
			        					<label>No. PO</label>
			        					<input id="no_po" type="text" name="no_po" class="form-control" readonly="true" value="<?php echo $no_po;?>" placeholder="No PO" >
			        				</div>
					        	</div>
					        </div>
							<div class="row">
								<div class="col-xs-6 col-sm-3 col-md-3">
			        				<div class="form-group">
			        					<label>Detail Ship to :</label>
			        					<textarea id='dtl_ship' class="form-control" disabled><?php echo $alamat ?></textarea>
								    </div>
								</div>
								<div class="col-xs-6 col-md-3 col-sm-3">
					            	<div class="form-group">
				        				<label>Periode</label>
				        				<?php
				        					$tglAwal= ($this->input->post('bulan_kirim'))? '01/'.$this->input->post('bulan_kirim').'/'.$this->input->post('tahun_kirim'):'01/'.date("m/Y");
				        					$tglAkhir=($this->input->post('bulan_kirim'))?cal_days_in_month(CAL_GREGORIAN,$this->input->post('bulan_kirim'),$this->input->post('tahun_kirim')).'/'.$this->input->post('bulan_kirim').'/'.$this->input->post('tahun_kirim'):cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')).'/'.date('m/Y');
				        					$periode=($this->input->get('p')=='A')?(int)($periode)+1:($no_po!='')?$periode:'1 ('.$tglAwal.' sd '.$tglAkhir.')';?>
				        				<input type="text" id="periode_po" name="periode_po" class="form-control" readonly="true"
				        				value="<?php echo $periode;?>">
				        				<!--  ($periode!='')?$periode: '1 (01/'.date('m/Y').' sd '.cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')).'/'.date('m/Y').')';?>"> -->
				        			</div>
				        		</div>
								<div class="col-xs-6 col-md-2 col-sm-2">
				        			<div class="form-group">
			        					<label>Jenis PO </label>
			        					<select class="form-control" id="jenis_po" name="jenis_po"  <?php echo ($jenispo!='')? 'disabled="true"':'';?>>
			        						<option value ="F" <?php echo ($jenispo=='F' ||$this->input->get('p')=='F')?"selected":"";?>>REGULAR</option>
			        						<option value ="A" <?php echo ($jenispo=='A' ||$this->input->get('p')=='A')?"selected":"";?>>Additional</option>
			        					</select>
			        				</div>
				        		</div>
								<div class="col-xs-6 col-sm-4 col-md-4">
			        				<div class="form-group">
					            		<label>Bulan Tahun</label>
					            		<div class="form-inline">
						            		<select class="form-control " id="bulan_kirim" name="bulan_kirim" <?php echo ($bulan!='')? 'disabled="true"':'';?>>
						            			<option>- Pilih Bulan -</option>
						            			<?php for($n=1; $n <=12;$n++){
						            				
						            				$aktif=($bulan==$n)?"selected":"";
						            				$aktif=(date("m")==$n && $bulan=='')?"selected":$aktif;
						            				echo "<option value=".$n." $aktif>".nBulan($n)."</option>";
						            			}
						            			?>
						            		</select>
						            		<select class="form-control" id="tahun_kirim" name="tahun_kirim"  <?php echo ($tahun!='')? 'disabled="true"':'';?>>
						            			<option>- Pilih Tahun -</option>
						            			<?php if($tahun>0){ echo "<option value='".$tahun."' selected>".$tahun."</option>"; }?>
						            			<option value="<?php echo date("Y");?>"<?php echo ($tahun>0)?"":" selected";?>><?php echo date("Y");?></option>
						            			<option value="<?php echo date("Y")+1;?>"><?php echo date("Y")+1;?></option>
						            		</select>
					            		</div>
					            	</div>
					            </div>
							</div>
					        <div class="row">
								<div class="col-xs-6 col-md-3 col-sm-3">
				        			<div class='form-group'>
										<label>&nbsp;</label>
										<span class="form-control">
										<span class="fa fa-info"> Status PO : <?php echo $status_po_text?></span>
										</span>
									</div>
				        		</div>
				        	</div>
				    	</div>
				    </div>
				    <div class="clear-fix"></div>
				    <div class="row"><?php $hidden=($approval>0)? "hidden":"";?>
				    	<div style="z-index:9999" class="padding-left-right-10 <?php echo  $hidden;?>" id="adds">
				    	<!-- <form id="podetail" method="post" action=""> -->
					    	<table class="table table-bordered">
					        	<thead>
					        		<!-- <tr class="no-hover"><th colspan="7" ><i class="fa fa-list fa-fw"></i> List Detail PO</th></tr> -->
					        		<tr>
								        <!-- <th>#</th> -->
								        <th style="width:20%">Kode Item</th>
								        <th style="width:40%">Nama Item</th>
								        <th style="width:10%">Qty PO</th>
								        <th style="width:10%">Tentative 1</th>
								        <th style="width:10%">Tentative 2</th>

								        <th style="width:10%">&nbsp;</th>
							    	</tr>
						        </thead>
						        <tbody>
					        		<tr id="forminput" class="info">
					        			<!-- <td>&nbsp;</td> -->
					        			<!-- dropdown motor -->
					        			<td><input type="text" id="kd_item" name="kd_item" class="form-control" required="true"></td>
					        			<td><input class="form-control" type="text" id="nama_item" name="nama_item"></td>
					        			<td><input class="form-control" type="text" id="fix" name="fix" data-trigger="focus" data-toggle="popover"></td>
					        			<td><input class="form-control" type="text" id="ten1" name="ten1" data-trigger="focus" data-toggle="popover"></td>
					        			<td><input class="form-control" type="text" id="ten2" name="ten2" data-trigger="focus" data-toggle="popover"></td>
					        			<td align="center" valign="middle">
					        				<input type="hidden" id='idpo' name='idpo' value='<?php echo $po_id;?>'>
					        				<input type="hidden" id='podetailid' name='podetailid' value='0'>
					        				<a role="button" id="tambah" class="btn btn-default" onclick="add_item();"><i class="fa fa-plus-circle fa-fw"></i></a>
					        			</td>
					        		</tr>
					        	</tbody>
					        </table>
					    <!-- </form> -->
					    </div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
		<div class="col-lg-12 padding-left-right-10">
			<!-- <div id="listpods" style="height: '300px';overflow: auto;"> -->
		 	<div class="panel panel-default" >
		 		<div class='table-responsive h350' style="overflow-x: hidden!important;"><!--style="max-height: 400px;overflow: auto;">-->
		 		<table class="table table-bordered table-hover table-stripped" id="listdetail">
		 			<thead>
		 				<tr class="no-hover"><th colspan="8" ><i class="fa fa-list fa-fw"></i> List PO Detail</th></tr>
		 				<tr>
		 					<th style="width:5% !important">#</th>
		 					<th style="width:8% !important">&nbsp;</th>
		 					<th style="width:10% !important">Kode Item</th>
		 					<th style="width:40% !important">Nama Item</th>
		 					<th style="width:10% !important text-align: center !important;">Qty PO</th>
		 					<th style="width:8% !important text-align: center !important;">Tentative 1</th>
		 					<th style="width:8% !important text-align: center !important;">Tentative 2</th>
		 					<th style="width:8% !important">&nbsp;</th>
		 				</tr>
		 			</thead>
		 			<tbody>
		 				<?php
		 					if(base64_decode(urldecode($this->input->get("n")))){	 						
		 						$i=0;
		 						$hidden_class=((int)$approval>0)?'disabled-action':'';
		 						
		 						if(isset($detail)){
		 							if($detail->totaldata>0){
				 						foreach ($detail->message as $key => $value) {			 							
				 							echo "<tr id='l_".$value->ID."'>
						                            <td class='text-center table-nowarp'>".($i+1)."</td>
						                            <td class='text-center table-nowarp'>
							                            <a class='edit-btn hidden' role='button' onclick=\"editItem('".$i."','".$value->ID."');\"><i data-toggle=\"tooltip\" data-placement=\"left\" title=\"Edit Item\" class=\"fa fa-edit text-success text-active\"></i></a>";
													if($status_po<1||$approval<1){
														
													echo"
							                            <a id='x_".$value->ID."' title=\"hapus\" class='$hidden_class' onclick=\"hapusItemData('".($value->ID)."');\"><i class=\"fa fa-trash text-danger text\"></i></a>";
													}
												echo"
						                             </td>
						                            <td class='table-nowarp'>".$value->KD_TYPEMOTOR."-".$value->KD_WARNA."</td>
						                            <td class='td-overflow-50' title='".$value->NAMA_ITEM."'>".$value->NAMA_ITEM."</td>
						                            <td class='text-right'>".number_format($value->FIX_QTY,0)."</td>
						                            <td class='text-right'>".number_format($value->T1_QTY,0)."</td>
						                            <td class='text-right'>".number_format($value->T2_QTY,0)."</td>
						                            <td class='text-center'>&nbsp;</td>
						                            <td class='text-center hidden'>".$value->ID."</td>
						                            <td class='text-center hidden'>".$po_id."</td>
						                        </tr>";
						                      $param[]=array(
				                                'kd_item'   => $value->KD_TYPEMOTOR."-".$value->KD_WARNA,
				                                'nama_item' => $value->NAMA_ITEM,
				                                'fix_qty'   => number_format($value->FIX_QTY,0),
				                                't1_qty'    => number_format($value->T1_QTY,0),
				                                't2_qty'    => number_format($value->T2_QTY,0),
				                                'idpo'		=> $value->ID,
				                                'id'		=> $value->PODETAILID
				                            );
						                    $i++;
				 						}
			 							//$this->session->set_userdata('podetail',$param);
			 						}
			 					}

		 					}
		 				?></tbody>
		 			<tfoot></tfoot>
		 		</table>
		 		<?php //echo print_r($this->session->userdata('podetail'));?>
				<input type="hidden" id="hps" value="">
				</div>
		 	</div> 
		 	<?php echo loading_proses();?>  			
		 <!-- </div> -->
	</div><!-- end div table responsive -->
</section><!-- end div class wrapper -->
<!--
 /**
 * Javascript for add_po process
 * created on : 28-10-2017
 */-->
<script type="text/javascript">
	
	$(document).ready(function(e){
		var date = new Date();
		date.setDate(date.getDate());

		// $('#datenext').datepicker({
			// format: 'dd/mm/yyyy',
			// daysOfWeekHighlighted: "0",
			// autoclose: true,
			// todayHighlight: true,
			// startDate:date
		// });
		
		var appv="0";
			appv ="<?php echo $approval;?>";
		
		if(parseInt(appv)==0){
			
			
				__check_po('w');
			
		}
		
		$('.footer').hide();
		var header = $("#adds");
		  $(window).scroll(function() {    
		    var scroll = $(window).scrollTop();
		       if (scroll >= 235) {
		          //header.addClass("fixed");
		          $(window).scrollTop(235)
		          //$('#listpod').attr({'height':100,'overflow':'auto'})
		        } else {
		          //header.removeClass("fixed");
		          //$('#listpod').removeAttr('style');
		        }
		});

		/*$("#kd_item")
			.keypress(function(e){ if(e.keyCode == 13){$(this).focusout();}})
			.focusout(function(){__getdata();
		});*/
		$('#fix')
			.focusout(function(){

			})
			.ForceNumericOnly()
			/*.popover({
			placement:'top',
			html:true,
			title:'<i class=\'fa fa-info-circle fa-fw\'></i> Informasi',
			content:'Informasi demand and supply untuk po bulan ini'
		});*/
		$('#ten1')
		.focusout(function(){
				
		})
		.ForceNumericOnly()
		/*.popover({
			placement:'top',
			html:true,
			title:'<i class=\'fa fa-info-circle fa-fw\'></i> Informasi',
			content:'Informasi demand and supply untuk po 1 bulan kedepan'
		});*/
		$('#ten2').focusout(
			function(){
				
			})
		.ForceNumericOnly()
		/*.popover({
			placement:'top',
			html:true,
			title:'<i class=\'fa fa-info-circle fa-fw\'></i> Informasi',
			content:'Informasi demand and supply untuk po 2 bulan kedepan'
		});*/
		if($('#kd_dealer').val()==''){$('#adds').hide();}
		//disable cetak button jika kolom no po tidak di isi
		if($('#no_po').val()=='No PO'||$('#no_po').val()==''){
			//$("#modal-button").attr('disabled','disabled')
		}
		
		//on scroll
		var po_lalu="<?php echo $this->input->get('b');?>";
		if(po_lalu=='y'){
			__check_po_bulanlalu(true);
		}

		$('#bulan_kirim').change(function(e){
			var d= new Date();
			var awal='01/'+('00'+$('#bulan_kirim').val()).slice(-2)+"/"+$('#tahun_kirim').val();
			var akhir=new Date($('#tahun_kirim').val(),$('#bulan_kirim').val(),0);
				akhir=akhir.getDate()+"/"+('00'+$('#bulan_kirim').val()).slice(-2)+"/"+$('#tahun_kirim').val();
			$('#periode_po').val('1 ('+awal+' sd '+ akhir+')');
			if($('#jenis_po').val()!='F'){
				$('#periode_po').val('1');
			}
			//__check_po_bulanlalu();
			if(parseInt(appv)==0){
				__check_po('e');
			}
		})
		$('#tahun_kirim').change(function(){
			$('#bulan_kirim').change();
		})
		$('#jenis_po').change(function(){
			//$('#bulan_kirim').change();
			document.location.href="?p="+$('#jenis_po').val();
		})
		$('#kd_dealer').change(function(){
			if($(this).val()==''){
				$('#adds').hide();
			}else{
				$('#adds').show();
				$('#bulan_kirim').change();
			}
			__detailshipto();
		})
		$('#tambah').keypress(function(e){
			if(e.keyCode==13){
				//add_item();
			}
		});
		__datamotor();
		__detailshipto();
	});

	function __detailshipto(){
		var dlr = $("#kd_dealer :selected").val();
		//$("#dtl_ship").val("OK");
		$.ajax({
			url:'<?php echo base_url("purchasing/detail_po_ship_to");?>',
			type:"POST",
			dataType: "html",
			data:{'kd_dealer':dlr},
			success:function(result){
				var d=$.parseJSON(result);
				// console.log(d);
				$("#dtl_ship").val(d[0].ALAMAT+", "+d[0].NAMA_KABUPATEN+" "+d[0].NAMA_PROPINSI+"\n"+d[0].TLP+"/"+d[0].TLP2);
			}

		});
		return false;
		
	}	
	
	function __datamotor(){
		$("#kd_item #cls").html("<i class='fa fa-refresh fa-spin fa-fw'></i>");
		var datax=[];
		$.getJSON("<?php echo base_url('motor/tipe_motor/1/1');?>",{"aktif":"1"},function(result){
			//console.log(result);
			if(result.totaldata >0){
				$.each(result.message,function(e,d){
					datax.push({
						'KD_ITEM'	: d.KD_ITEM,
						'DESKRIPSI': d.NAMA_PASAR,
						'WARNA'	:d.KET_WARNA
					})
				})
				//console.log(datax);
				$('#kd_item').inputpicker({
					data : datax,
					fields :['KD_ITEM','DESKRIPSI','WARNA'],
					fieldText : 'KD_ITEM',
					fieldValue: 'KD_ITEM',
					filterOpen: true,
				    headShow:true,
				}).on("change",function(e){
					e.preventDefault();
					var dx=datax.findIndex(obj => obj['KD_ITEM'] === $(this).val());
					$("#nama_item").val(datax[dx]["DESKRIPSI"]);
					$('#fix').focus();
				})
			}
		})
	}
	function __getdata(){
		var kw = $('#kd_item').val();
		$("#kd_items #cls").html("<i class='fa fa-refresh fa-spin fa-fw'></i>");
		$.ajax({
			url:'<?php echo base_url("purchasing/listmotor");?>',
			type:"POST",
			dataType: "html",
			data:{'keyword':kw},
			success:function(result){
				$('#list tbody').html('');
				$("table#list tbody").append(result);
				$("#kd_items #cls").html("");
				$("#kd_items").click();
			}

		});
		return false;
	}
	
	function dropdown_item(kd_item,nama_item){
    	
	    $("#kd_item").val(kd_item);
	    $("#nama_item").val(nama_item);
	    $("#fix").focus();
	}
	
	function cekMinMax(kd_item, kd_dealer, jenis){
		var res = new Array();
		$.ajax({
			async:false,
			url :'<?php echo base_url("purchasing/cek_minmaxpo");?>',
			type :'get',
			dataType:'json',
			data :{'kd_dealer':kd_dealer,'item':kd_item,'jenis_po':jenis},
			success : function(result){
				//console.log(result);
				if(result == "Belum ada data / data tidak di temukan"){
					res[0] = Number('.10');
					res[1] = Number('.15');
				} else {
					res[0] = result[0].MIN_QTY;
					res[1] = result[0].MAX_QTY;
				}
			}
		})
		return res;
	}
	
	function getLastPOItem(kd_item, kd_dealer, bulan_kirim, tahun_kirim){
		var res = new Array();
		//console.log(kd_item+', '+kd_dealer+', '+bulan_kirim+', '+tahun_kirim);
		$.ajax({
			async:false,
			url :'<?php echo base_url("purchasing/lastpoitemquantity");?>',
			type :'get',
			dataType:'json',
			data :{'kd_dealer':kd_dealer,'item':kd_item,'bulan_kirim':bulan_kirim,'tahun_kirim':tahun_kirim},
			success : function(result){
				//console.log(result);
				if(result == "Belum ada data / data tidak di temukan"){
					res[0] = 0;
					res[1] = 0;
				} else {
					//console.log(result[0].T1_QTY+', '+result[0].T2_QTY);
					res[0] = result[0].T1_QTY;
					res[1] = result[0].T2_QTY;
				}
			}
		})
		return res;
	}
	
	function add_item(){
		var kd_item = $('#kd_item').val();
		var fix_min;
		var fix_max;
		if(
			kd_item == '' ||
			$('#fix').val()=='' ||
			$('#ten1').val()=='' ||
			$('#ten2').val()==''
			){
			alert("Data harus lengkap!");
			return false;
		}
		var dealer = '<?php echo $defaultDealer?>';
		if(itemExists($('#kd_item').val())==false){return;};
		//ambil persentase minimum maksimum
		var minmaxfix = cekMinMax(kd_item,dealer,'FIX');
		var minmaxtent = cekMinMax(kd_item,dealer,'T1');
		//ambil nilai tentative 1 dan 2 dari order item sebelumnya
		var month = $('#bulan_kirim').val() -1;
		var year = $('#tahun_kirim').val();
		if(month == 1){
			year = year -1;
			month = 12;
		}
		var lastPoItem = getLastPOItem(kd_item, dealer, month, year);
		//console.log(lastPoItem);
		var minFix = Number(lastPoItem[0])-(minmaxfix[0]*lastPoItem[0]);
		var maxFix = Number(lastPoItem[0]) + (minmaxfix[1]*lastPoItem[0]);
		var minTent = Number(lastPoItem[1])-(minmaxtent[0]*lastPoItem[1]);
		var maxTent = Number(lastPoItem[1])+(minmaxtent[1]*lastPoItem[1]);
		if(minFix != 0 && maxFix != 0 && minTent != 0 && maxTent != 0){
			console.log($('#fix').val()+','+minFix);
			console.log($('#fix').val()+','+maxFix);
			if($('#fix').val() < minFix){
				alert("Nilai Fix tidak boleh kurang dari "+minmaxfix[0]*100+"% Tentative 1 order item sebelumnya");
				return false;
			}
			if($('#fix').val() > maxFix){
				alert("Nilai Fix tidak boleh lebih dari "+minmaxfix[1]*100+"% Tentative 1 order item sebelumnya");
				return false;
			}
			if($('#ten1').val() < minTent){
				alert("Nilai Tentative 1 tidak boleh kurang dari "+minmaxtent[0]*100+"% Tentative 2 order item sebelumnya");
				return false;
			}
			if($('#ten1').val() > maxTent){
				alert("Nilai Tentative 1  tidak boleh lebih dari "+minmaxtent[1]*100+"% Tentative 2 order item sebelumnya");
				return false;
			}
		}
		//console.log("lolos");
		//return;
		
		var datax =""; var tr="";
		var row=$('#listdetail > tbody > tr').length;
		tr +="<tr><td class='text-center'>"+(row+1)+"</td>";
		tr +="<td class='text-center'><a onclick=\"__hapusBaris('"+row+"');\" title='deleted item'><i class='fa fa-trash'></i></a></td>";
		tr +="<td class='text-center'>"+$('#kd_item').val()+"</td>";
		tr +="<td>"+$('#nama_item').val()+"</td>";
		tr +="<td class='text-center'>"+$('#fix').val()+"</td>";
		tr +="<td class='text-center'>"+$('#ten1').val()+"</td>";
		tr +="<td class='text-center'>"+$('#ten2').val()+"</td>";
		tr +="<td class='text-center'>&nbsp;</td><td class='hidden'>0</td><td class='hidden'>"+$('#idpo').val()+"</td></tr>";
		$('#listdetail tbody').append(tr)
		
		$('#kd_item').text('');
		$('#nama_item').val('');
		$('#fix').val('');
		$('#ten1').val('');
		$('#ten2').val('');
		$('#hps').val('');
		$('#idpo').val('0');
		$('#podetailid').val('0');
		var scroll = $(window).scrollTop();
		$(window).scrollTop(scroll);
		$('#listpod').scrollTop($('#listpod').height());
		$('#d_kd_item').removeClass('disabled-action');
					
	}
	function itemExists(item){
		var MyRows = $('table#listdetail').find('tbody').find('tr');
		 if (MyRows.length>0 && $('#hps').val()==''){
		 	for (var i = 0; i < MyRows.length; i++) {
				var MyIndexValue = $(MyRows[i]).find('td:eq(2)').html();
				if(MyIndexValue==item){
					alert(item+' Sudah ada di list');
					return false;
				}
			}
		 }
			
	}
	/**
	 * [simpan_po description]
	 * @return {[type]} [description]
	 */
	function simpan_po(){
		$('#kd_dealer').removeAttr('disabled');
		$("#submit-btns").html("<span class='fa fa-spinner fa-spin'></span> Simpan");
		$('#po_form :input').removeAttr("disabled");
		var detail=__detailData();
		$('#loadpage').removeClass("hidden");
		//console.log($('#po_form').serialize());
		$.ajax({
			url:'<?php echo base_url("purchasing/simpan_po");?>',
			type:"POST",
			data: $('#po_form').serialize()+"&d="+JSON.stringify(detail),
			success:function(result){
				//console.log(result);
				var d=$.parseJSON(result);
				if(d.status==true){
					document.location.href="<?php echo base_url('purchasing/add_po?n=');?>"+d.nodoc;
					$('#kd_dealer').attr('disabled','disabled');
					$("#submit-btns").html("Simpan");
					$('#loadpage').addClass("hidden");
					//console.log("A");
				}else{
					alert(d.message);
					$('#kd_dealer').attr('disabled','disabled');
					$("#submit-btns").html("Simpan");
					$('#po_form :input').attr("disabled",'disabled');
					$('#loadpage').addClass("hidden");
					console.log("B");
					return false;
				}
			}

		});/**/
	}
	function __detailData(){
		var datax=[];
		var jmlrow=0;
		jmlrow =$('#listdetail > tbody > tr').length;
		for(i=0;i<jmlrow;i++){
			var exist=0;
				exist=parseInt($('#listdetail > tbody > tr:eq('+i+') > td:eq(8)').text());
			if(isNaN(exist) || exist ==0){
				datax.push({
					'kd_item'	:$('#listdetail > tbody > tr:eq('+i+') > td:eq(2)').text(),
					'nama_item'	:$('#listdetail > tbody > tr:eq('+i+') > td:eq(3)').text(),
					'fix_qty'	:$('#listdetail > tbody > tr:eq('+i+') > td:eq(4)').text(),
					't1_qty'	:$('#listdetail > tbody > tr:eq('+i+') > td:eq(5)').text(),
					't2_qty'	:$('#listdetail > tbody > tr:eq('+i+') > td:eq(6)').text(),
					'id'		:$('#listdetail > tbody > tr:eq('+i+') > td:eq(8)').text(),
					'idpo'		:$('#listdetail > tbody > tr:eq('+i+') > td:eq(9)').text(),
				});
			}
		}
		//console.log('sudah ada'+exist);
		return datax;
	}
	function add_new(){
		window.location.href="<?php echo base_url('purchasing/add_po?b=y');?>";
	}
	function __check_po(e){
		var data={
			kd_dealer : $('#kd_dealer').val(),
			bulan_kirim: $('#bulan_kirim').val(),
			tahun_kirim: $('#tahun_kirim').val(),
			jenis_po : $('#jenis_po').val()
		}
		$.ajax({
			url:'<?php echo base_url("purchasing/po_exists");?>',
			type:"POST",
			//dataType: "json",
			data:data,
			success:function(result){
				var rst=result.split(':');
				//console.log(rst[0]+'='+e);
				if(parseInt(rst[0])>0){
					if(e=='e'){
						//po sudah pernah dibuat
						if(parseInt(rst[1]) > 0){
							//po sudah di approve
							alert("Periode  : "+$('#bulan_kirim').val()+"/"+$('#tahun_kirim').val()+"\n"+
						  	"Jenis PO : "+ $('#jenis_po option:selected').text()+"\nSudah pernah di buat");
							$('#adds').hide();
							__check_po_bulanlalu(true);
						}else{
							//po sudah perndah di buat dan blm di approve 
							//tampilkan data yang bulan skrang
							__check_po_bulanlalu(true);
						}
					}else{

						if($('#no_po').val()=='No PO'||$('#no_po').val()==''){
							$('#adds').show();
						}else{
							$('#adds').show();
						}
						if($('#jenis_po').val()=='A'){
							$('#periode_po').val(parseInt(result)+1);
						}else{
							//saat load form
							//po pernah di buat dan jenis po adalh F
							//tampilkan po bulan lalu
							__check_po_bulanlalu(true);
						}
					}
					

				}else{
					if($('#kd_dealer').val()!=''){
						//jika po blm pernah dibuat load data po bulan lalu
						__check_po_bulanlalu();
					}
					$('#adds').show();
				}
			}

		});
		return false;
	}
	function __check_po_bulanlalu(skr){
		$('#loadpage').removeClass("hidden");
		var bulanlalu=$('#bulan_kirim').val();
		var tahunlalu=$('#tahun_kirim').val();
		if(!skr){
			if((bulanlalu-1)==0){
				bulanlalu=12;tahunlalu=(tahunlalu-1);
			}else{
				bulanlalu=(bulanlalu-1);
				tahunlalu=tahunlalu;
			}
		}
		var data={
			kd_dealer : $('#kd_dealer').val(),
			bulan_kirim: bulanlalu,
			tahun_kirim: tahunlalu,
			jenis_po : $('#jenis_po').val()
		}
		var blnskr=(skr)?"/true":"";
		$.ajax({
			url:'<?php echo base_url("purchasing/pobulanlalu");?>'+blnskr,
			type:"POST",
			dataType:"html",
			data:data,
			success:function(result){
					if(skr){
						__loadPOHeader(data);
					}
					//add_item();
					
					$('#loadpage').addClass("hidden");
				
			}
		})
	}
	function __loadPOHeader(datax){
		var no_po = '';
		$.ajax({
			url:'<?php echo base_url("purchasing/po_exists/true");?>',
			type:"POST",
			dataType:"json",
			data:datax,
			success:function(result){
				//console.log(result);
				if(result){
					//console.log(result);
					$.each(result,function(e,d){
						if(parseInt(d.STATUS_PO) < 0){
							return;
						} 
						$('#no_po').val(d.NO_PO);
						no_po = d.NO_PO;
						$('#tgl_po').val(convertDate(d.TGL_PO));//.toLocaleString());
						//$('#tgl_selesai_po').val(convertDate(d.TGL_SELESAI_PO));
						$('#periode_po').val(d.PERIODE_PO +"("+convertDate(d.TGL_AWALPO)+" sd "+convertDate(d.TGL_AKHIRPO)+")");
						$('#jenis_po').val(d.KD_JENISPO).select();
						$('#bulan_kirim').val(d.BULAN_KIRIM).select();
						$('#tahun_kirim').val(d.TAHUN_KIRIM).select();
						$('#idpo').val(d.ID);
						$('#dtl_ship').val(d.ALAMAT+' '+d.NAMA_KABUPATEN+', '+d.NAMA_PROPINSI+'\n'+d.TLP+' / '+d.TLP2);
						$('#tgl_po').prop('disabled',true);
						//$('#tgl_selesai_po').prop('disabled',true);
					})
					var mode="<?php echo $mode?$mode:$this->input->get('n');?>";
					console.log(no_po);
					if(mode=='y'){
						document.location.href="<?php echo base_url('purchasing/add_po?n=');?>"+$.base64.encode($('#no_po').val())
					}
				}
				
			}
		})
	}
	function unsetSession(id){
		var data={'idx' : id};
		$.ajax({
			url:'<?php echo base_url("purchasing/podetail_listtmp");?>',
			type:"POST",
			dataType: "html",
			data:data,
			success:function(result){
				$('#listdetail tbody').html(result);
			}

		});
		
	}
	function __hapusBaris(id){
		if(confirm('Yakin data ini akan dihapus?')){
			$('#listdetail > tbody > tr:eq('+id+')').remove();
		}	
	}
	function hapusItemData(id){
		//console.log(id);
	  	if(confirm("Yakin item ini akan dihapus?")){
	  		$('#loadpage').removeClass("hidden");
	  		$.ajax({
	  			url :'<?php echo base_url("purchasing/podetail_delete");?>',
	  			type :'GET',
	  			dataType:'json',
	  			data :{'id':id},
	  			success : function(result){
	  				//unsetSession(id);
					$('#l_'+id).remove();
	  				$('#loadpage').addClass("hidden");
	  			}
	  		})
	  	}
	}
	function editItem(id,idpo){
		var data={'id':id,'id_po':idpo}
		$('#hps').val(id);
		if(idpo>0){
			$.ajax({
			url:'<?php echo base_url("purchasing/podetailid");?>',
			type:"POST",
			dataType:"json",
			data:data,
			success:function(result){
				$.each(result,function(index,d){
					$('#d_kd_item').removeClass('disabled-action');
					$('#kd_item').val(d.KD_ITEM).select();
					$('#nama_item').val(d.NAMA_ITEM);
					$('#fix').val(d.FIX_QTY.replace('.00','')).focus().select();
					$('#ten1').val(d.T1_QTY.replace('.00',''));
					$('#ten2').val(d.T2_QTY.replace('.00',''));
					$('#idpo').val(d.ID_PO);
					$('#podetailid').val(d.ID);
				});
			  }
			})
		}else{
			
			$('#d_kd_item').addClass('disabled-action');
			$('#kd_item').val($('tr#l_'+id).children('td:nth-child(3)').text()).select();
			$('#nama_item').val($('tr#l_'+id).children('td:nth-child(4)').text()).addClass('disabled-action');
			$('#fix').val($('tr#l_'+id).children('td:nth-child(5)').text()).focus().select();
			$('#ten1').val($('tr#l_'+id).children('td:nth-child(6)').text());
			$('#ten2').val($('tr#l_'+id).children('td:nth-child(7)').text());
		}
		
	}
	function __loadDetail(poid){
		$.getJSON("",{'id':poid},function(result){
			var row=0;
			/*if(result.totaldata){
				$.each(result.message,function(e,d)){
					tr +="<tr><td class='text-center'>"+(row+1)+"</td>";
					tr +="<td class='text-center'><a onclick=\"__hapusBaris('"+row+"');\" title='deleted item'><i class='fa fa-trash'></i></a></td>";
					tr +="<td class='text-center'>"+e.KD_ITEM+"</td>";
					tr +="<td>"+e.+"</td>";
					tr +="<td class='text-center'>"+$('#fix').val()+"</td>";
					tr +="<td class='text-center'>"+$('#ten1').val()+"</td>";
					tr +="<td class='text-center'>"+$('#ten2').val()+"</td>";
					tr +="<td class='text-center'>&nbsp;</td><td class='hidden'>0</td><td class='hidden'>"+$('#idpo').val()+"</td></tr>";
					row ++;
				}
				$('#listdetail tbody').append(tr)
			}*/
		})
	}
</script>