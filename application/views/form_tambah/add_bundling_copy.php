<?php
	
	$default="";$KD_DEALER="";$no_po="";$periode="";$bulan="";
	$tahun="";$jenispo="";$tglpo="";$approval=0;
	if(base64_decode(urldecode($this->input->get("n")))){
		foreach ($poheader->message as $key => $value) {
			$KD_DEALER 	= $value->KD_DEALER;
			$kd_md		= $value->KD_MAINDEALER;
			$no_po 		= $value->NO_PO;
			$periode 	= ($value->KD_JENISPO!='F' )?($value->PERIODE_PO):$value->PERIODE_PO." (".tglfromSql($value->TGL_AWALPO)." sd ".tglfromSql($value->TGL_AKHIRPO)." )";
			$bulan 		= $value->BULAN_KIRIM;
			$tahun 		= $value->TAHUN_KIRIM;
			$jenispo 	= $value->KD_JENISPO;
			$tglpo 		= tglfromSql($value->TGL_PO);
			$approval	= ($value->APPROVAL_PO>0)?$value->APPROVAL_PO:0;
		}
	}
	$cetak=(!$no_po)?' disabled':'';
	$cetak=(isBolehAkses("p"))? $cetak:'disabled';
?>

<section class="wrapper">
	<div class="breadcrumb">
		<div id="bc1" class="myBreadcrumb">
            <a href="javascript:void(0);"><i class="fa fa-home fa-2x"></i></a>
            <!-- <div>...</div> -->
            <a href="javascript:void(0);"><div>Motor</div></a>
            <a href="<?php echo base_url('purchasing/PO_list'); ?>"><div>Bundling</div></a>
            <a href="javascript:void(0);" class="active"><div>Tambah</div></a>
        </div>
        <div class="bar-nav pull-right ">
        	<a class="btn btn-default" id="submit-btns" href="#" role="button" onclick="simpan_po();" >
                <i class="fa fa-save fa-fw"></i> Simpan
            </a>
        </div>
	</div>
	<div class="col-lg-12 padding-left-right-10">
    	<div class="panel margin-bottom-5">
    		<div class="panel-heading">
                <i class="fa fa-list fa-fw"></i> Bundling Header
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border">
				 <form id="po_form" method="post" action="">
				    <div class="row">
				        <div class="padding-left-right-10">
				        	<div class="row">
				        			<div class="col-xs-6 col-sm-6 col-md-6">
										<?php $disable= ($KD_DEALER!='' || $this->session->userdata("kd_dealer")!='')? "readonly='true'":'';?>
				        				<div class="form-group">
				        					<label>Dealer</label>
				        					<select name="kd_dealer" id="kd_dealer" class="form-control" disabled="disabled" required="true">
									          <option value="">- Pilih Dealer -</option>
									          <?php foreach ($dealer->message as $key => $group) { 
									          	if($KD_DEALER!=''):
										          	$default=($KD_DEALER==$group->KD_DEALER)?" selected":" ";
										        else:
										          	$default=($this->session->userdata("kd_dealer")==$group->KD_DEALER)?" selected":'';
									          	endif
									          	?>
									            <option value="<?php echo $group->KD_DEALER;?>"<?php echo $default;?> ><?php echo $group->NAMA_DEALER;?></option>
									          <?php } ?>
									        </select>
									    </div>
										<div class="col-xs-6 col-sm-6 col-md-6">
											<div class="form-group">
											   <label>Tanggal Mulai</label>
											   <div class="input-group input-append date" id="date">
												   <input type="text" class="form-control" id="start_date" name="start_date" value="" placeholder="dd/mm/yyyy" />
												   <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
											   </div>
											</div>
										</div>
										<div class="col-xs-6 col-sm-6 col-md-6">
											<div class="form-group">
											   <label>Tanggal Selesai</label>
											   <div class="input-group input-append date" id="date">
												   <input type="text" class="form-control" id="end_date" name="end_date" value="" placeholder="dd/mm/yyyy" />
												   <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
											   </div>
										   </div>
									   </div>
				        			</div>
				        			<div class="col-xs-6 col-sm-6 col-md-6">
				        				<div class="form-group">
											<label>Kode</label>
											<input id="kd_bundling" type="text" name="kd_bundling" class="form-control" placeholder="Masukkan kode diskon" >
										</div>
										<div class="form-group">
											<label>Nama</label>
											<input id="nama_bundling" type="text" name="nama_bundling" class="form-control" placeholder="Masukkan kode diskon" >
										</div>
				        			</div>
				        	</div>
				    	</div>
				    </div>
					<div class="row">
								<div style="z-index:9999" class="padding-left-right-10" id="adds_motor">
									<table class="table table-bordered">
										<thead>
											<tr style="color:#fff">
												<th>#</th>
												<th>Kode Motor</th>
												<th>Nama</th>
												<th>Jumlah</th>
												<th>&nbsp;</th>
											</tr>
										</thead>
										<tbody>
											<tr id="forminput" class="info">
											<td>&nbsp;</td>
												<td>
													<div class="dropdown">
														<div class="input-group input-append">
															<input class="form-control" type="text" name="kd_item" id="kd_item" aria-haspopup="true" aria-expanded="true" placeholder="Type Motor">
															<div class="input-group-btn">
																<button class="btn btn-default dropdown-toggle" type="button" id="kd_items" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
																	<span id='cls' class="caret"></span>
																</button>
																  <ul class="dropdown-menu multi-column" aria-labelledby="kd_items">
																	<li class="">
																		<table id="list" name="list" class="table table-bordered table-hover">
																			<tbody>
																			<?php foreach ($motor->message as $key => $group) : ?>
																				<tr onclick="dropdown_item('<?php echo $group->KD_ITEM;?>','<?php echo $group->NAMA_ITEM;?>');">
																					<td style="white-space: nowrap;"><?php echo $group->KD_ITEM;?></td>
																					<td style="white-space: nowrap;"><?php echo $group->NAMA_TYPEMOTOR;?></td>
																					<td style="white-space: nowrap;"><?php echo $group->NAMA_ITEM;?></td>
																					<td style="white-space: nowrap;"><?php echo $group->KET_WARNA;?></td>
																				</tr>
																			<?php endforeach;?>
																			</tbody>
																		</table>
																	</li>
																  </ul>
															</div><!-- end div input btn -->
														</div><!-- end div form group -->
													</div><!-- end div dropdow -->
												</td>
												<td><input class="form-control" type="text" id="nama_item" name="nama_item"></td>
												<td><input class="form-control" type="text" id="jml_item" name="jml_item" data-trigger="focus" data-toggle="popover"></td>
												<td align="center" valign="middle">
													<input type="hidden" id='idpo' name='idpo' value='0'>
													<a role="button" id="tambah" class="btn btn-default" onclick="add_item();"><i class="fa fa-plus-circle fa-fw"></i></a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
						</div>
						<div class="row">
								<div style="z-index:9999" class="padding-left-right-10" id="adds_aksesoris">
									<table class="table table-bordered">
										<thead>
											<tr style="color:#fff">
												<th>#</th>
												<th>Kode Aksesoris</th>
												<th>Nama</th>
												<th>Jumlah</th>
												<th>&nbsp;</th>
											</tr>
										</thead>
										<tbody>
											<tr id="forminput" class="info">
											<td>&nbsp;</td>
												<td>
													<div class="dropdown">
														<div class="input-group input-append">
															<input class="form-control" type="text" name="kd_aksesoris" id="kd_aksesoris" aria-haspopup="true" aria-expanded="true" placeholder="Aksesoris">
															<div class="input-group-btn">
																<button class="btn btn-default dropdown-toggle" type="button" id="kd_aksesoriss" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
																	<span id='cls' class="caret"></span>
																</button>
																  <ul class="dropdown-menu multi-column" aria-labelledby="kd_aksesoriss">
																	<li class="">
																		<table id="list" name="list" class="table table-bordered table-hover">
																			<tbody>
																			<?php foreach ($aksesoris->message as $key => $group) : ?>
																				<tr onclick="dropdown_aksesoris('<?php echo $group->KD_AKSESORIS;?>','<?php echo $group->NAMA_AKSESORIS;?>');">
																					<td style="white-space: nowrap;"><?php echo $group->KD_AKSESORIS;?></td>
																					<td style="white-space: nowrap;"><?php echo $group->NAMA_AKSESORIS;?></td>
																				</tr>
																			<?php endforeach;?>
																			</tbody>
																		</table>
																	</li>
																  </ul>
															</div><!-- end div input btn -->
														</div><!-- end div form group -->
													</div><!-- end div dropdow -->
												</td>
												<td><input class="form-control" type="text" id="nama_aksesoris" name="nama_aksesoris"></td>
												<td><input class="form-control" type="text" id="jml_aksesoris" name="jml_aksesoris" data-trigger="focus" data-toggle="popover"></td>
												<td align="center" valign="middle">
													<input type="hidden" id='idpo' name='idpo' value='0'>
													<a role="button" id="tambah" class="btn btn-default" onclick="add_aksesoris();"><i class="fa fa-plus-circle fa-fw"></i></a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
						</div>
						<div class="row">
								<div style="z-index:9999" class="padding-left-right-10" id="adds_apparel">
									<table class="table table-bordered">
										<thead>
											<tr style="color:#fff">
												<th>#</th>
												<th>Kode Apparel</th>
												<th>Nama</th>
												<th>Jumlah</th>
												<th>&nbsp;</th>
											</tr>
										</thead>
										<tbody>
											<tr id="forminput" class="info">
											<td>&nbsp;</td>
												<td>
													<div class="dropdown">
														<div class="input-group input-append">
															<input class="form-control" type="text" name="kd_item" id="kd_item" aria-haspopup="true" aria-expanded="true" placeholder="Type Motor">
															<div class="input-group-btn">
																<button class="btn btn-default dropdown-toggle" type="button" id="kd_items" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
																	<span id='cls' class="caret"></span>
																</button>
																  <ul class="dropdown-menu multi-column" aria-labelledby="kd_items">
																	<li class="">
																		<table id="list" name="list" class="table table-bordered table-hover">
																			<tbody>
																			<?php foreach ($motor->message as $key => $group) : ?>
																				<tr onclick="dropdown_item('<?php echo $group->KD_ITEM;?>','<?php echo $group->NAMA_ITEM;?>');">
																					<td style="white-space: nowrap;"><?php echo $group->KD_ITEM;?></td>
																					<td style="white-space: nowrap;"><?php echo $group->NAMA_TYPEMOTOR;?></td>
																					<td style="white-space: nowrap;"><?php echo $group->NAMA_ITEM;?></td>
																					<td style="white-space: nowrap;"><?php echo $group->KET_WARNA;?></td>
																				</tr>
																			<?php endforeach;?>
																			</tbody>
																		</table>
																	</li>
																  </ul>
															</div><!-- end div input btn -->
														</div><!-- end div form group -->
													</div><!-- end div dropdow -->
												</td>
												<td><input class="form-control" type="text" id="nama_item" name="nama_item"></td>
												<td><input class="form-control" type="text" id="jml_item" name="jml_item" data-trigger="focus" data-toggle="popover"></td>
												<td align="center" valign="middle">
													<input type="hidden" id='idpo' name='idpo' value='0'>
													<a role="button" id="tambah" class="btn btn-default" onclick="add_apparel();"><i class="fa fa-plus-circle fa-fw"></i></a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
						</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- DETAIL MOTOR -->
	<div class="col-lg-12 padding-left-right-10">
    	<div class="panel panel-default" >
				<div class='table-responsive'><!--style="max-height: 400px;overflow: auto;">-->
					<table class="table table-bordered table-hover" id="listdetail">
									<thead>
										<tr>
											<th>#</th>
											<th>&nbsp;</th>
											<th class="col-sm-2">Kode Motor</th>
											<th class="col-sm-4">Nama</th>
											<th class="col-sm-1">Jumlah</th>
										</tr>
									</thead>
									<tbody>
										<?php
											if(base64_decode(urldecode($this->input->get("n")))){
												
												$i=0;$hidden_class=($approval>0)?'hidden':'';
												if($detail){
													foreach ($detail->message as $key => $value) {
														
														echo "<tr>
																<td>".($i+1)."</td>
																<td>
																<a class='edit-btn $hidden_class' role='button' onclick=\"editItem('".$i."','".$value->ID."');\"><i data-toggle=\"tooltip\" data-placement=\"left\" title=\"Edit Item\" class=\"fa fa-edit text-success text-active\"></i></a>
																<a id='x_".$value->ID."' class='delete-btn $hidden_class' url='".base_url('purchasing/podetail_delete?n='.$this->input->get("n").'&id='.base64_encode($value->ID).'')."'>
																<i data-toggle=\"tooltip\" data-placement=\"left\" title=\"hapus\" class=\"fa fa-trash text-danger text\"></i></a>
																	</td>
																<td>".$value->KD_TYPEMOTOR."-".$value->KD_WARNA."</td>
																<td>".$value->NAMA_ITEM."</td>
																<td>".number_format($value->JUMLAH,0)."</td>
															</tr>";
														  $i++;
														  $param[]=array(
															'kd_item'   => $value->KD_TYPEMOTOR."-".$value->KD_WARNA,
															'nama_item' => $value->NAMA_ITEM,
															'jml_item'   => $value->JUMLAH,
															'idpo'		=> $value->ID
														);
													}
													$this->session->set_userdata('podetail',$param);
												}

											}
										?>
									</tbody>
									<tfoot></tfoot>
					</table>
					<br>
					<table class="table table-bordered table-hover" id="listdetail_aksesoris">
									<thead>
										<tr>
											<th>#</th>
											<th>&nbsp;</th>
											<th class="col-sm-2">Kode Aksesoris</th>
											<th class="col-sm-4">Nama</th>
											<th class="col-sm-1">Jumlah</th>
										</tr>
									</thead>
									<tbody>
										<?php
											if(base64_decode(urldecode($this->input->get("n")))){
												
												$i=0;$hidden_class=($approval>0)?'hidden':'';
												if($detail){
													foreach ($detail->message as $key => $value) {
														
														echo "<tr>
																<td>".($i+1)."</td>
																<td>
																<a class='edit-btn $hidden_class' role='button' onclick=\"editItem('".$i."','".$value->ID."');\"><i data-toggle=\"tooltip\" data-placement=\"left\" title=\"Edit Item\" class=\"fa fa-edit text-success text-active\"></i></a>
																<a id='x_".$value->ID."' class='delete-btn $hidden_class' url='".base_url('purchasing/podetail_delete?n='.$this->input->get("n").'&id='.base64_encode($value->ID).'')."'>
																<i data-toggle=\"tooltip\" data-placement=\"left\" title=\"hapus\" class=\"fa fa-trash text-danger text\"></i></a>
																	</td>
																<td>".$value->KD_AKSESORIS."</td>
																<td>".$value->NAMA_AKSESORIS."</td>
																<td>".number_format($value->JUMLAH,0)."</td>
															</tr>";
														  $i++;
														  $param[]=array(
															'kd_item'   => $value->KD_TYPEMOTOR."-".$value->KD_WARNA,
															'nama_item' => $value->NAMA_ITEM,
															'jml_item'   => $value->JUMLAH,
															'idpo'		=> $value->ID
														);
													}
													$this->session->set_userdata('podetail',$param);
												}

											}
										?>
									</tbody>
									<tfoot></tfoot>
								</table>
								<br>
								<table class="table table-bordered table-hover" id="listdetail_apparel">
									<thead>
										<tr>
											<th>#</th>
											<th>&nbsp;</th>
											<th class="col-sm-2">Kode Apparel</th>
											<th class="col-sm-4">Nama</th>
											<th class="col-sm-1">Jumlah</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
									<tfoot></tfoot>
								</table>
				</div>			
			</div>
	</div>
</section>
<script type="text/javascript">
	
	$(document).ready(function(e){
		var date = new Date();
		__check_po('w');
	
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

		$("#kd_item")
			.keypress(function(e){ if(e.keyCode == 13){$(this).focusout();}})
			.focusout(function(){__getdata();
		});
		$("#kd_aksesoris")
			.keypress(function(e){ if(e.keyCode == 13){$(this).focusout();}})
			.focusout(function(){__getdata_aksesoris();
		});
		$("#kd_apparel")
			.keypress(function(e){ if(e.keyCode == 13){$(this).focusout();}})
			.focusout(function(){__getdata_apparel();
		});
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
		$('#ten2')
		.focusout(function(){
				
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
		$('#tambah').keypress(function(e){
			if(e.keyCode==13){
				//add_item();
			}
		});
		//unsetSession(-1);
	});

	/**
	 * menampilkan data list motor berdasarkan inputan di kolm kditem
	 * @return {[type]} [description]
	 */
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
	function __getdata_aksesoris(){
		var kw = $('#kd_aksesoris').val();
		$("#kd_items #cls").html("<i class='fa fa-refresh fa-spin fa-fw'></i>");
				$.ajax({
					url:'<?php echo base_url("master/aksesoris");?>',
					type:"POST",
					dataType: "html",
					data:{'keyword':kw},
					success:function(result){
						$('#list_aksesoris tbody').html('');
						$("table#list_aksesoris tbody").append(result);
						$("#kd_aksesoriss #cls").html("");
						$("#kd_aksesoriss").click();
					}

				});
				return false;
	}
	function __getdata_apparel(){
		var kw = $('#kd_apparel').val();
		$("#kd_items #cls").html("<i class='fa fa-refresh fa-spin fa-fw'></i>");
				$.ajax({
					url:'<?php echo base_url("master/apparel");?>',
					type:"POST",
					dataType: "html",
					data:{'keyword':kw},
					success:function(result){
						$('#list_apparel tbody').html('');
						$("table#list_apparel tbody").append(result);
						$("#kd_apparels #cls").html("");
						$("#kd_apparels").click();
					}

				});
				return false;
	}
	/**
	 * mengisi kolom kditem dan nama item di form podetail
	 * @param  {[type]} kd_item   [description]
	 * @param  {[type]} nama_item [description]
	 * @return {[type]}           [description]
	 */
	function dropdown_item(kd_item,nama_item){
    	
	    $("#kd_item").val(kd_item);
	    $("#nama_item").val(nama_item);
	    $("#jml_item").focus();
	}
	/**
	 * mengisi kolom kditem dan nama item di form podetail
	 * @param  {[type]} kd_item   [description]
	 * @param  {[type]} nama_item [description]
	 * @return {[type]}           [description]
	 */
	function dropdown_aksesoris(kd_aksesoris,nama_aksesoris){
    	
	    $("#kd_aksesoris").val(kd_aksesoris);
	    $("#nama_aksesoris").val(nama_aksesoris);
	    $("#jml_aksesoris").focus();
	}
	/**
	 * mengisi kolom kditem dan nama item di form podetail
	 * @param  {[type]} kd_item   [description]
	 * @param  {[type]} nama_item [description]
	 * @return {[type]}           [description]
	 */
	function dropdown_apparel(kd_apparel,nama_apparel){
    	
	    $("#kd_apparel").val(kd_apparel);
	    $("#nama_apparel").val(nama_apparel);
	    $("#jml_apparel").focus();
	}

	/**
	 * menyimpan sementara tipe motor yang dibuatkan po
	 * ke dalam session sebelum dilakukan proses simpan
	 */
	function add_item(){
		if(itemExists($('#kd_item').val())==false){return;};
		var data ={
			'kd_item'	:$('#kd_item').val(),
			'nama_item'	:$('#nama_item').val(),
			'jml_item'	:$('#jml_item').val(),
			'idx'		:$('#hps').val(),
			'idpo'		:$('#idpo').val()
		};
			$.ajax({
					url:'<?php echo base_url("purchasing/podetail_listtmp");?>',
					type:"POST",
					dataType: "html",
					data:data,
					success:function(result){
						//if($('#kd_item').val()==''){
							$("table#listdetail tbody").html('');
							$('#listdetail tbody').html(result);
						/*}else{
							$("table#listdetail tfoot").html('');
							$('#listdetail tfoot').html(result);
						}*/
						
						$('#kd_item').val('');
						$('#nama_item').val('');
						$('#jml_item').val('');
						$('#hps').val('');
						$('#idpo').val('0')
						var scroll = $(window).scrollTop();
						$(window).scrollTop(scroll);
						$('#listpod').scrollTop($('#listpod').height());
						/*$('#adds').stick_in_parent({
							offset_top:100
						});*/
						if(result){
							//$('#bulan_kirim').attr("disabled","disabled");
							//$('#tahun_kirim').attr("disabled","disabled");
						}
						
					}

				});
	}
	/**
	 * menyimpan sementara aksesoris yang dibuatkan po
	 * ke dalam session sebelum dilakukan proses simpan
	 */
	function add_aksesoris(){
		if(itemExists($('#kd_aksesoris').val())==false){return;};
		var data ={
			'kd_aksesoris'	:$('#kd_aksesoris').val(),
			'nama_aksesoris'	:$('#nama_aksesoris').val(),
			'jml_aksesoris'	:$('#jml_aksesoris').val(),
			'status_bundling'	:'aksesoris',
			'idx'		:$('#hps').val(),
			'kd_bundling'		:$('#idpo').val()
		};
			$.ajax({
					url:'<?php echo base_url("motor/bundlingdetail_listtmp");?>',
					type:"POST",
					dataType: "html",
					data:data,
					success:function(result){
						//if($('#kd_item').val()==''){
							$("table#listdetail_aksesoris tbody").html('');
							$('#listdetail_aksesoris tbody').html(result);
						/*}else{
							$("table#listdetail tfoot").html('');
							$('#listdetail tfoot').html(result);
						}*/
						
						$('#kd_aksesoris').val('');
						$('#nama_aksesoris').val('');
						$('#jml_aksesoris').val('');
						$('#hps').val('');
						$('#idpo').val('0')
						var scroll = $(window).scrollTop();
						$(window).scrollTop(scroll);
						$('#listpod').scrollTop($('#listpod').height());
						/*$('#adds').stick_in_parent({
							offset_top:100
						});*/
						if(result){
							//$('#bulan_kirim').attr("disabled","disabled");
							//$('#tahun_kirim').attr("disabled","disabled");
						}
						
					}

				});
	}
	/**
	 * menyimpan sementara apparel yang dibuatkan po
	 * ke dalam session sebelum dilakukan proses simpan
	 */
	function add_apparel(){
		if(itemExists($('#kd_apparel').val())==false){return;};
		var data ={
			'type_bundling'	:$('#kd_apparel').val(),
			'status_budling'	:'apparel',
			'jumlah'	:$('jml_apparel').val(),
			'idx'		:$('#hps').val(),
			'kd_bundling'		:$('#idpo').val()
		};
			$.ajax({
					url:'<?php echo base_url("motor/bundlingdetail_listtmp");?>',
					type:"POST",
					dataType: "html",
					data:data,
					success:function(result){
						//if($('#kd_item').val()==''){
							$("table#listdetail tbody").html('');
							$('#listdetail tbody').html(result);
						/*}else{
							$("table#listdetail tfoot").html('');
							$('#listdetail tfoot').html(result);
						}*/
						
						$('#kd_apparel').val('');
						$('#nama_apparel').val('');
						$('#jml_apparel').val('');
						$('#hps').val('');
						$('#idpo').val('0')
						var scroll = $(window).scrollTop();
						$(window).scrollTop(scroll);
						$('#listpod').scrollTop($('#listpod').height());
						/*$('#adds').stick_in_parent({
							offset_top:100
						});*/
						if(result){
							//$('#bulan_kirim').attr("disabled","disabled");
							//$('#tahun_kirim').attr("disabled","disabled");
						}
						
					}

				});
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
	function itemExists_aksesoris(item){
		var MyRows = $('table#listdetail_aksesoris').find('tbody').find('tr');
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
	function itemExists(item){
		var MyRows = $('table#listdetail_apparel').find('tbody').find('tr');
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
		//$('#po_form :option').removeAttr("disabled");
		$.ajax({
			url:'<?php echo base_url("motor/simpan_bundling");?>',
			type:"POST",
			data: $('#po_form').serialize(),
			success:function(result){

				//alert(result);
				var d=$.parseJSON(result);
				if(d.status==true){
					document.location.href="<?php echo base_url('motor/add_bundling?n=');?>"+d.nodoc;
					$('#kd_dealer').attr('disabled','disabled');
					$("#submit-btns").html("Simpan");
				}else{
					alert(d.message);
					$('#kd_dealer').attr('disabled','disabled');
					$("#submit-btns").html("Simpan");
					$('#po_form :input').attr("disabled",'disabled');
					return false;
				}
			}

		});/**/
	}
	/**
	 * reload page and clear form to default
	 * clear session podetail
	 */
	function add_new(){
		window.location.href="<?php echo base_url('motor/add_bundling?b=y');?>";
	}
	
	function unsetSession(id){
		var data={'idx' : id};
		$.ajax({
			url:'<?php echo base_url("motor/podetail_listtmp");?>',
			type:"POST",
			dataType: "html",
			data:data,
			success:function(result){
				$('#listdetail tbody').html(result);
			}

		});
		
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
					$('#kd_item').val(d.KD_ITEM),
					$('#nama_item').val(d.NAMA_ITEM),
					$('#fix').val(d.FIX_QTY),
					$('#ten1').val(d.T1_QTY),
					$('#ten2').val(d.T2_QTY),
					$('#idpo').val(d.ID)
				});
			  }
			})
		}else{
			
			$('#kd_item').val($('tr#l_'+id).children('td:nth-child(3)').text());
			$('#nama_item').val($('tr#l_'+id).children('td:nth-child(4)').text());
			$('#fix').val($('tr#l_'+id).children('td:nth-child(5)').text());
			$('#ten1').val($('tr#l_'+id).children('td:nth-child(6)').text());
			$('#ten2').val($('tr#l_'+id).children('td:nth-child(7)').text());
		}
		/**/
		
	}
</script>