<?php
	if (!isBolehAkses()) {redirect(base_url() . 'auth/error_auth');}
	$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  	$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  	$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  	$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  	$defaultDealer=$dealerpilih;
	$defaultTahun=$tahunpilih;
	$defaultMotor=$motorpilih;
	$default="";$KD_DEALER="";$no_po="";$periode="";$bulan="";
	$tahun="";$jenispo="";$tglpo="";$approval=0;$po_id=0;
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
        <div class="bar-nav pull-right ">
        	
        </div>
	</div>
	<div class="col-lg-12 padding-left-right-10">
    	<div class="panel margin-bottom-5">
    		<div class="panel-heading">
                <i class="fa fa-list fa-fw"></i> Setup Quantity PO Percentage <?php //echo $approval;?>
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
                <input type='hidden' id="apv_sts" value="<?php echo $approval;?>">
            </div>
            <div class="panel-body panel-body-border">
				<form id="po_form" method="post" action="quantity_po">
				    <div class="row">
				        <div class="padding-left-right-10">
				        	<div class="row">
			        			<div class="col-xs-4 col-sm-3 col-md-3">
			        				<div class="form-group">
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
					            <div class="col-xs-4 col-sm-3 col-md-3">
			        				<div class="form-group">
										<label>Motor</label>
										<input type="text" id="kd_typemotor" name="kd_typemotor" class="form-control" required="true" />	
								    </div>
								</div>
								
								<div class="col-xs-4 col-sm-2 col-md-2">
			        				<div class="form-group">
					            		<label>Jenis PO</label>
					            		<div class="form-inline">
						            		<select class="form-control" id="jenis_po" name="jenis_po" required="true">
						            			<option value = "">- Pilih Jenis PO -</option>
												<option value="FIX" <?php echo ($this->input->post("jenis_po")!="FIX")?"":" selected";?>>FIX</option>
												<option value="T1" <?php echo ($this->input->post("jenis_po")!="T1")?"":" selected";?>>TENTATIVE 1</option>
						            		</select>
					            		</div>
					            	</div>
					            </div>
								<div class="col-xs-4 col-sm-2 col-md-2">
			        				<div class="form-group">
					            		<label>Tahun</label>
					            		<div class="form-inline">
						            		<select class="form-control" id="tahun" name="tahun" required="true">
						            			<option value="">- Pilih Tahun -</option>
												<?php 
													for($i = date("Y"); $i > (date("Y")-5); $i--){
												?>
														<option value="<?php echo $i;?>"<?php echo ($i!=$defaultTahun)?"":" selected";?>><?php echo $i;?></option>
												<?php
													}
												?>
						            		</select>
					            		</div>
					            	</div>
					            </div>
								<br />
								<button id="submit-btn" class="btn btn-primary"><i class="fa fa-search"></i> Show</button>
				        	</div>
				    	</div>
				    </div>
				</form>
			</div>
		</div>
	</div>
	<?php
		if($defaultMotor!=''){
	?>
	<div class="col-lg-12 padding-left-right-10">
		<!-- <div id="listpods" style="height: '300px';overflow: auto;"> -->
		<div class="panel panel-default" >
			<div class='table-responsive h350' style="overflow-x: hidden!important;"><!--style="max-height: 400px;overflow: auto;">-->
			<table class="table table-bordered table-hover table-stripped" id="listdetail">
				<thead>
					<tr class="no-hover"><th colspan="8" ><i class="fa fa-list fa-fw"></i>  Quantity PO Percentage</th></tr>
					<tr>
						<th style="width:4% !important">No.</th>
						<th style="width:6% !important">Aksi</th>
						<th style="width:10% !important">Kode Item</th>
						<th style="width:20% !important">Nama Item</th>
						<th style="width:10% !important">JENIS PO</th>
						<th style="width:5% !important text-align: center !important;">% MINIMUM</th>
						<th style="width:5% !important text-align: center !important;">% MAXIMUM</th>
						<th style="width:30% !important">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if(isset($list)){
							$i = 0;
							if($list->totaldata>0){
								foreach ($list->message as $key => $value) {			 							
									echo "<tr id='l_".$value->ID."'>
											<td class='text-center table-nowarp'>".++$i."</td>
											<td class='text-center table-nowarp'>
												<a id='e_".$value->ID."' class='edit-btn' onclick=\"editItemData('".($value->ID)."');\"><i data-toggle=\"tooltip\" data-placement=\"left\" title=\"Simpan Perubahan\" class=\"fa fa-save text-success text-active\"></i></a>
												<a id='x_".$value->ID."' title=\"Hapus\" onclick=\"hapusItemData('".($value->ID)."');\"><i class=\"fa fa-trash text-danger text\"></i></a>
											 </td>
											<td class='table-nowarp'>".$value->KD_TYPEMOTOR."</td>
											<td class='table-nowarp'>";
											foreach ($kodetipemotor->message as $key => $namamotor) { 
												if($namamotor->KD_ITEM == $value->KD_TYPEMOTOR){ echo $namamotor->NAMA_ITEM; }
											}
									echo"</td>
											<td class='text-center'>".$value->JENIS_PO."</td>
											<td class='text-center'><input id='min_".$value->ID."' class='form-control' value='".(($value->MIN_QTY)*100)."' /></td>
											<td class='text-center'><input id='max_".$value->ID."'class='form-control' value='".(($value->MAX_QTY)*100)."' /></td>
											<td class='text-center'>&nbsp;</td>
											<td class='text-center hidden'>".$value->ID."</td>
										</tr>";
								}
								//$this->session->set_userdata('podetail',$param);
							} else {
								echo "<tr'>
									<td class='text-center table-nowarp'>".++$i."</td>
									<td class='text-center table-nowarp'>
										<a onclick=\"simpanItemData();\" class='save-btn' role='button' ><i data-toggle=\"tooltip\" data-placement=\"left\" title=\"Simpan Data\" class=\"fa fa-save text-success text-active\"></i></a>
									 </td>
									<td class='table-nowarp'>".$this->input->post("kd_typemotor")."</td>
									<input type='hidden' id = 'form-kd_typemotor' value = '".$this->input->post("kd_typemotor")."'>
									<input type='hidden' id = 'form-kd_dealer' value = '".$this->input->post("kd_dealer")."'>
									<input type='hidden' id = 'form-jenis_po'  value = '".$this->input->post("jenis_po")."'>
									<input type='hidden' id = 'form-tahun'  value = '".$this->input->post("tahun")."'>
									<td class='table-nowarp'>";
									foreach ($kodetipemotor->message as $key => $namamotor) { 
										if($namamotor->KD_ITEM == $this->input->post("kd_typemotor")){ echo $namamotor->NAMA_ITEM; }
									}
								echo"</td>
									<td class='text-center'>".$this->input->post("jenis_po")."</td>
									<td class='text-center'><input id='form-min' class='form-control' value='Not Set' /></td>
									<td class='text-center'><input id='form-max' class='form-control' value='Not Set' /></td>
									<td class='text-center'>&nbsp;</td>
								</tr>";
							}
						} 
					?></tbody>
				<tfoot></tfoot>
			</table>
			</div>
		</div> 
		<?php echo loading_proses();?>  			
	
	</div>
	<?php 
		}
	?>
</section><!-- end div class wrapper -->
<!--
 /**
 * Javascript for add_po process
 * created on : 28-10-2017
 */-->
<script type="text/javascript">
	
	$(document).ready(function(e){
		
		$('#form-max')
			.focusout(function(){
			})
			.ForceNumericOnly();
			
		$('#form-min')
		.focusout(function(){
				
		})
		.ForceNumericOnly();
		
		$('#ten2').focusout(
			function(){
				
			})
		.ForceNumericOnly();
		__datamotor();

	});
	function hapusItemData(id){
	  	if(confirm("Yakin item ini akan dihapus?")){
	  		$('#loadpage').removeClass("hidden");
	  		$.ajax({
	  			url :'<?php echo base_url("setup/quantity_po_delete");?>',
	  			type :'GET',
	  			dataType:'json',
	  			data :{'id':id},
	  			success : function(result){
	  				$('#l_'+id).remove();
	  				alert("Data Berhasil Dihapus");
					$('#loadpage').addClass("hidden");
	  			}
	  		})
	  	}
	}
	
	function editItemData(id){
		var min = (document.getElementById('min_'+id).value)/100;
		var max = (document.getElementById('max_'+id).value)/100;
	  	if(confirm("Yakin Menyimpan Perubahan?")){
	  		$('#loadpage').removeClass("hidden");
	  		$.ajax({
	  			url :'<?php echo base_url("setup/quantity_po_update");?>',
	  			type :'GET',
	  			dataType:'json',
	  			data :{'id':id,'min':min,'max':max},
	  			success : function(result){
	  				alert("Perubahan Berhasil Disimpan");
					$('#loadpage').addClass("hidden");
	  			}
	  		})
	  	}
	}
	
	function simpanItemData(){
		var kd_dealer = document.getElementById('form-kd_dealer').value;
		var tahun = document.getElementById('form-tahun').value;
		var kd_typemotor = document.getElementById('form-kd_typemotor').value;
		var jenis_po = document.getElementById('form-jenis_po').value;
		var min = document.getElementById('form-min').value;
		var max = document.getElementById('form-max').value;
		console.log(min);
		if(min == 'Not Set' || min == ''){
			alert("Persentase Minimum Wajib Diisi");
			return false;
		}
		if(max == 'Not Set' || max == ''){
			alert("Persentase Maksimum Wajib Diisi");
			return false;
		}
		
	  	if(confirm("Yakin Menyimpan Data?")){
	  		$('#loadpage').removeClass("hidden");
	  		$.ajax({
	  			url :'<?php echo base_url("setup/quantity_po_insert");?>',
	  			type :'POST',
	  			dataType:'json',
	  			data :{'kd_dealer':kd_dealer,'tahun':tahun,'kd_typemotor':kd_typemotor,'jenis_po':jenis_po,'min':min/100,'max':max/100},
	  			success : function(result){
					alert("Data Berhasil Disimpan");
					$('#loadpage').addClass("hidden");
					window.location.replace('quantity_po');
	  			}
	  		})
	  	}
	}
	function __datamotor(){
		$("#kd_typemotor #cls").html("<i class='fa fa-refresh fa-spin fa-fw'></i>");
		var datax=[];
		$.getJSON("<?php echo base_url('motor/tipe_motor/1/1');?>",{"aktif":"1"},function(result){
			//console.log(result);
			if(result.totaldata >0){
				$.each(result.message,function(e,d){
					datax.push({
						'KD_TYPEMOTOR'	: d.KD_ITEM,
						'DESKRIPSI': d.NAMA_PASAR,
						'WARNA'	:d.KET_WARNA
					})
				})
				//console.log(datax);
				$('#kd_typemotor').inputpicker({
					data : datax,
					fields :['KD_TYPEMOTOR','DESKRIPSI','WARNA'],
					fieldText : 'KD_TYPEMOTOR',
					fieldValue: 'KD_TYPEMOTOR',
					filterOpen: true,
				    headShow:true,
				}).on("change",function(e){
					e.preventDefault();
					var dx=datax.findIndex(obj => obj['KD_TYPEMOTOR'] === $(this).val());
					$("#nama_item").val(datax[dx]["DESKRIPSI"]);
					$('#fix').focus();
				})
			}
		})
	}
	function __getdata(){
		var kw = $('#kd_typemotor').val();
		$("#kd_typemotor #cls").html("<i class='fa fa-refresh fa-spin fa-fw'></i>");
		$.ajax({
			url:'<?php echo base_url("purchasing/listmotor");?>',
			type:"POST",
			dataType: "html",
			data:{'keyword':kw},
			success:function(result){
				$('#list tbody').html('');
				$("table#list tbody").append(result);
				$("#kd_typemotor #cls").html("");
				$("#kd_items").click();
			}

		});
		return false;
	}
	function dropdown_item(kd_item,nama_item){
    	
	    $("#kd_typemotor").val(kd_item);
	    $("#nama_item").val(nama_item);
	    $("#fix").focus();
	}
</script>