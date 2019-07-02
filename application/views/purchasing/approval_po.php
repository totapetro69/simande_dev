<?php
	if (!isBolehAkses()) {redirect(base_url() . 'auth/error_auth');}
	$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  	$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  	$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  	$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

	$nopopilihan=base64_decode(urldecode($this->input->get('d')));
	$default="";$KD_DEALER="";$app_level=0;$no_po="";
	$namadealer="";
	$periode="";
	$bulan="";
	$tahun="";
	$jenispo="";
	$tglpo="";
	$dibuatoleh="";
	$tgl_selesaipo = "";
	if(isset($poheader)){
		if($poheader->status==TRUE){
			foreach ($poheader->message as $key => $value) {
				if($nopopilihan==$value->ID){
					$KD_DEALER  = $value->KD_DEALER;
					$namadealer = $value->NAMA_DEALER;
					$no_po      = $value->NO_PO;
					$periode    = ($value->KD_JENISPO!='F')?$value->PERIODE_PO:$value->PERIODE_PO." (".tglfromSql($value->TGL_AWALPO)." sd ".tglfromSql($value->TGL_AKHIRPO)." )";
					$bulan      = $value->BULAN_KIRIM;
					$tahun      = $value->TAHUN_KIRIM;
					$jenispo    = $value->KD_JENISPO;
					$tglpo      = tglfromSql($value->TGL_PO);
					if($value->STATUS_PO < 3){
						$tgl_selesaipo = "-";
					} else {
						$tgl_selesaipo = tglfromSql($value->TGL_SELESAI_PO);
					}//$approvale   = $value->APPROVAL_PO;
					$dibuatoleh = $value->USER_NAME;
					
				}else if($nopopilihan==''){
					$KD_DEALER  = $value->KD_DEALER;
					$namadealer = $value->NAMA_DEALER;
					$no_po      = $value->NO_PO;
					$periode    = ($value->KD_JENISPO!='F')?$value->PERIODE_PO:$value->PERIODE_PO." (".tglfromSql($value->TGL_AWALPO)." sd ".tglfromSql($value->TGL_AKHIRPO)." )";
					$bulan      = $value->BULAN_KIRIM;
					$tahun      = $value->TAHUN_KIRIM;
					$jenispo    = $value->KD_JENISPO;
					$tglpo      = tglfromSql($value->TGL_PO);
					if($value->STATUS_PO < 3){
						$tgl_selesaipo = "-";
					} else {
						$tgl_selesaipo      = tglfromSql($value->TGL_SELESAI_PO);
					}//$approvale   = $value->APPROVAL_PO;
					$dibuatoleh = $value->USER_NAME;
					break;
				}
				
			}
		}
		
	}

	
	if(isset($approval)){
		if($approval->totaldata >0){
			foreach ($approval->message as $key => $value) {
				$app_level 	= $value->APP_LEVEL;
				$app_doc 	= $value->KD_DOC;
			}
		}
	}
	$disabled =($app_level >0)?"":"disabled-action";
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
        <div class="bar-nav pull-right ">
        	<a class="btn btn-default <?php echo ($no_po=='')?' disabled':'';?><?php echo $disabled;?>" href="<?php echo base_url('purchasing/approval_po?a='.urlencode(base64_encode($no_po)).'&l='.base64_encode($app_level).''); ?>" role="button"><i class="fa fa-pencil-square-o fa-fw"></i> Approve</a>
         	<a class="btn btn-default <?php echo $status_c;?>" href='<?php echo base_url('purchasing/PO_list'); ?>' role="button" data-toggle="modal" data-backdrop="static">
                <i class="fa fa-table fa-fw"></i> List PO
            </a>
        </div>
	</div><!-- 
	<div class="col-lg-12 padding-left-right-10">
    	<div class="row"> -->
        <div class="col-lg-12 padding-left-right-10">
        	<div class="panel margin-bottom-5">
	    		<div class="panel-heading">
	                <i class="fa fa-list fa-fw"></i> NOPO : <?php echo strtoupper($no_po);?>
	                <span class="tools pull-right">
	                    <a class="fa fa-chevron-down" href="javascript:;"></a>
	                </span>
	            </div>
	            <div class="panel-body panel-body-border">
	                <form class="bucket-form" method="get">
	                    <div class="row">
	                    	<!-- <CENTER><b>PURCHASE ORDER(PO)</b></CENTER>
	                    	<br> -->
	        				 <div class="col-xs-5 col-sm-5 col-md-5">
	        					<div class="form-group">
	        						<table width="100%">
	                                	
	                                	<tr>
	                                		<td style="width:35%"><label>Dealer</label></td>
	                                		<td>:</td>
	                                     	<td>&nbsp;<?php echo strtoupper($namadealer);?></td>
	                                    </tr>
	                                    <tr>
	                                    	<td><label>Kode Dealer</label></td>
	                                    	<td>:</td>
	                                    	<td>&nbsp;<?php echo strtoupper($KD_DEALER);?></td>
	                                    </tr>
	                                    <tr>
	                                    	<td><label>Bulan/Tahun</label></td>
	                                    	<td>:</td>
	                                    	<td>&nbsp;<?php echo $bulan."/".$tahun;?></td>
	                                    </tr>
	                                    <tr>
	                                    	<td><label>Periode</label></td>
	                                    	<td>:</td>
	                                    	<td>&nbsp;<?php echo $periode;?></td>
	                                    </tr>
										<tr>
	                                    	<td><label>Tanggal PO</label></td>
	                                    	<td>:</td>
	                                    	<td>&nbsp;<?php echo $tglpo;?></td>
	                                    </tr>
										<tr>
	                                    	<td><label>Tanggal Selesai PO</label></td>
	                                    	<td>:</td>
	                                    	<td>&nbsp;<?php echo $tgl_selesaipo;?></td>
	                                    </tr>
	                                </table>
	                            </div>
	        				</div>

	                        <div class="col-xs-7 col-sm-7 col-md-7">
	        					<div class="form-group">
	        						<table width="80%">
	                                	<tr>
	                                		<td style="width:30%"><label>Nomor PO</label></td>
	                                     	<td>:</td>
	                                     	<td>&nbsp;
	                                     	<?php 
	                                     	if($poheader){
	                                     		if(is_array($poheader->message)){
	                                     			if(count($poheader->message)>1){

	                                     				echo "<form id='frm' method='post'>
	                                     					 <div class='form-group'>
	                                     					 <select id='poid' name='poid' class='form-control'>";
	                                     					 foreach ($poheader->message as $key => $value) {
	                                     					 	$selected=(base64_decode(urldecode($this->input->get('d')))==$value->ID)?'selected':'';
	                                     					 	echo "<option value='".urlencode(base64_encode($value->ID))."' ".$selected.">".$value->NO_PO."</option>";
	                                     					 }
	                                     				echo "</select></form>";
	                                     					 	
	                                     			}else{
	                                     				echo $no_po;
	                                     			}
	                                     		}
	                                     	}
	                                     	?>
	                                     	</td>
	                                    </tr>
	                                    <tr>
	                                    	<td><label>Jenis PO</label></td>
	                                    	<td>:</td>
	                                    	<td>&nbsp;<?php echo $jenispo;?></td>
	                                    </tr>
	                                    <tr>
	                                    	<td><label>Dibuat Oleh</label></td>
	                                    	<td>:</td>
	                                    	<td>&nbsp;<?php echo $dibuatoleh;?></td>
	                                    </tr>
	                            	</table>
	                            </div>
	        				</div>
	        			</div>
	        		</form>
	        	</div>
	        </div>
        </div>
        <div class="col-lg-12 padding-left-right-10">
        		<div class="panel panel-default" style="max-height: 450px;overflow: auto;">
                    <!-- <div class="table-responsive"> -->
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width:45px;">No.</th>
                                    <th>Kode Item</th>
                                    <th>Keterangan</th>
                                    <th>Qty</th>
                                    <th>Qty N+1</th>
                                    <th>Qty N+2</th>
                                </tr>
                            </thead>
                            <?php
                            if(isset($detail)){
                            	if($detail->totaldata >0){
	                                echo "<tbody>";
	                                $i=0;
	                                foreach ($detail->message as $key => $value) {
	                                    
	                                    echo "<tr>
	                                            <td>".($i+1)."</td>
	                                            <td>".$value->KD_TYPEMOTOR."-".$value->KD_WARNA."</td>
	                                            <td class='table-nowarp'>".$value->NAMA_ITEM."</td>
	                                            <td class='text-center'>".number_format($value->FIX_QTY,0)."</td>
	                                            <td class='text-center'>".number_format($value->T1_QTY,0)."</td>
	                                            <td class='text-center'>".number_format($value->T2_QTY,0)."</td>
	                                        
	                                        </tr>";
	                                      $i++;
	                                }
	                                echo "</tbody>";
	                            }
                            }?>
                        </table>
                    <!-- </div> -->
                    <footer class="panel-footer">
                        <div class="row">

                        </div>
                    </footer>
                </div>
        </div>
    <!-- </div>
	</div> --><!-- end div table responsive -->
</section><!-- end div class wrapper -->

<script type="text/javascript">
	
	$(document).ready(function(){
		$('#poid').change(function(){
			var id=$('#poid').val();
			document.location.href="<?php echo base_url('purchasing/approval_po?d=');?>"+(id);
		})
	});
	/**
	 * [simpan_po description]
	 * @return {[type]} [description]
	 */
	function aprove_po(url){

		$.ajax({
			url:url,
			type:"POST",
			data: $('#po_form').serialize(),
			success:function(result){
				//alert(result);
				var d=$.parseJSON(result);
				if(d.status==true){
					document.location.href="<?php echo base_url('purchasing/add_po?n=');?>"+d.nodoc;
				}else{
					return false;
				}
			}
		});
	}
	
</script>