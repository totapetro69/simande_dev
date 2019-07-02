<?php
if (!isBolehAkses()) {     redirect(base_url() . 'auth/error_auth');  }
	$status_c =(isBolehAkses('c'))?'':'disabled-action';
	$status_e =(isBolehAkses('e'))?'':'disabled-action';
	$status_v =(isBolehAkses('v'))?'':'disabled-action';
	$defaultDealer=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
	$hidden=($this->input->get("kd_dealer"))?"hidden":"";
	$dDealer=array();$nx=0;
	if(isset($jdlr)){
		if($jdlr->totaldata >0){
			foreach ($jdlr->message as $key => $value) {
				$dDealer[$value->KD_DEALER]=$value->JML;
			}
			//$dDealer["K4D"]=array_count_values(array_column($list->message, 'KD_DEALER'))['K4D'];
		}
	}
	//var_dump($jdlr);
	/*print_r($dDealer);
	exit();*/
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        <div class="bar-nav pull-right ">          
        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class="fa fa-list-ul"></i> APPROVAL DOCUMENT 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
            	<!-- <form id='filterForm' method="get" action="<?php echo base_url("cashier/approval_ds");?>"> -->
	            	<div class="col-xs-12 col-sm-4 col-md-4">
	            		<div class="form-group">
	            			<label>Nama Dealer</label>
	            			<select name="kd_dealer" id="kd_dealer" class="form-control">
		    					<option value="">--Pilih Dealer--</option>
		    					<?php
									if($dealer){
										if(is_array($dealer->message)){
											foreach ($dealer->message as $key => $value) {
												$len =(strlen($value->KD_DEALER) >=3)?"":"&nbsp;";
												$select=($defaultDealer==$value->KD_DEALER)?"selected":"";
												$jml="";//(isset($dDealer[$value->KD_DEALER]))? " (".$dDealer[$value->KD_DEALER].")":"";
												echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER." ".$jml."</option>";
											}
										}
									}
								?>
		    				</select>
		    			</div>
		    			<?php //var_dump($apv);;?>
		    		</div>
		    	<!-- </form> -->
	    		<div class="pull-right">
	    			<br>
	    			<button class="btn btn-info disabled-action" type="button" id="aprv"><i class="fa fa-cog"></i> Approved</button>
	    			<button class="btn btn-default disabled-action hidden" type="button" id="unaprv"><i class="fa fa-trash"></i> Un Approved</button>
	    		</div>
	    	</div>
	    </div>
	</div>
	<div class="clearfix"></div>
	<div class="col-lg-12 padding-left-right-10">
		<div class="panel panel-default">
			<div class="table-responsive h350">
				<?php /*$userAppv=isApproval('SPKBT');
						print_r($userAppv);*/;?>
				<table class="table table-bordered table-hover table-striped" id="lst_app">
					<thead>
						<tr>
							<th>No</th>
							<th><input type="checkbox" id="chk_all" class="<?php echo $status_c;?>" title="Check All"></th>
							<th>Jenis Document</th>
							<th class="<?php echo $hidden;?>">Dealer</th>
							<th>No Document</th>
							<th>Tgl Document</th>
							<th>Keterangan</th>
							<th></th>
							<th>Request By</th>
						</tr>
					</thead>
					<tbody>
						<?php $n=0;

						if(isset($list)){
							if($list->totaldata > 0){
								foreach ($list->message as $key => $value) {
									$apv_level=0;
									$jns=explode(" ", $value->URAIAN);
									$apv=isApproval($value->KD_DOC);
									//$nexAPV = isApproval($value->KD_DOC,((int)$apv+1),null,"USER_NAME");
									$jmlAprover = CountApvDoc($value->KD_DOC);
									$yngDimunculkan=($jns[0]!='MUTASI')?((int)$apv-(int)$jmlAprover)-1:((int)$apv-(int)$jmlAprover);//+$value->STATUS_SPK);
									$jenis=($jns[0]!='MUTASI')?$jns[0]:$value->KD_DOC;
									//echo $apv.":".$jmlAprover.":".$yngDimunculkan;
									if($yngDimunculkan===(int)$value->STATUS_SPK){
										$tampil = ($defaultDealer==$value->KD_DEALER)?'':"hidden";
										$n++;
									?>
										<tr class='<?php echo $value->KD_DEALER." ".$tampil;?>'>
											<td class="text-center"><?php echo $n;//.":" .$yngDimunculkan;?></td>
											<td class='text-center table-nowarp'>
												<input type="checkbox" class='chk <?php echo $status_c;?>' id="ap_<?php echo $value->ID."_".$jenis."_".$apv."_".$value->NO_SPK."_".$value->KD_DEALER."_".$jmlAprover;?>" style="cursor: pointer;" title="<?php echo $value->URAIAN;?>" name="nchk[]">
												<a href="<?php echo base_url().$value->URL_LINK;?>"><i class="fa fa-edit"></i></a>
												<!-- <a href="<?php echo base_url().$value->URL_LINK;?>&d=y"><i class="fa fa-trash"></i></a> -->
											</td>
											<td class="text-left table-nowarp"><?php echo $value->URAIAN;?></td>
											<td class="<?php echo $hidden;?> text-center"><?php echo $value->KD_DEALER;?></td>
											<td class='text-left table-nowarp'><?php echo $value->NO_SPK;?></td>
											<td class='text-left'><?php echo TglFromSql($value->TGL_SPK);?></td>
											<td class='td-overflow-50'><?php echo $value->KD_ITEM." ".$value->NAMA_ITEM;?></td>
											<td class='table-nowarp'><?php echo $value->CHANEL;?> <sup><?php echo $apv;?></sup></td>
											<td class='table-nowarp' title="<?php echo $value->STATUS_SPK;?>"><?php echo ((int)$value->STATUS_SPK ==0)?$value->USER_NAME:$value->REQUEST_BY;?></td>
											<td class="hidden"><?php echo $apv_level;?></td>
										</tr>
									<?php
									}
								}
							}else{
								echo BelumAdaData(8);
							}
						}else{
							echo BelumAdaData(8);
						}

							?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php echo loading_proses();?>
</section>
<script type="text/javascript">
	$(document).ready(function(){
		$('#kd_dealer').on("change",function(e){
			if($(this).val()){
				$('#lst_app >tbody tr.'+$(this).val()).removeClass("hidden");
				$('#lst_app >tbody tr:not(.'+$(this).val()+')').addClass("hidden");
			}else{
				$('#lst_app >tbody tr').removeClass("hidden");
			}			
			$("input[id^='ap_']").prop('checked',false);
		})
		$('#chk_all').click(function(e){
			if($(this).is(":checked")){
				$("input[type='checkbox']").attr("checked",true);
				$('button.btn').removeClass('disabled-action');
			}else{
				$("input[type='checkbox']").removeAttr("checked");
				$('button.btn').addClass('disabled-action');
			}
		})
		$(".chk").click(function(){
			if($('.chk:checked').length==$('.chk').length){
				console.log($('.chk').length);
				$('#chk_all').attr('checked','checked')
				$('button.btn').removeClass('disabled-action');
			}else if($('.chk:checked').length==0){
				$('#chk_all').removeAttr("checked");
				$('button.btn').addClass('disabled-action');
			}else{
				$('#chk_all').removeAttr("checked");
				$('button.btn').removeClass('disabled-action');
			}

		})
		$('#aprv').click(function(){
			__approval();
		})
	})
	function __approval(){
		$('#loadpage').removeClass("hidden");
		var chkArray = [];
		$('.chk:checked').each(function(){
			var id=$(this).attr('id').split('_');
			chkArray.push({
				'id':id[1],//id document
				'tp':id[2],//tipe document
				'level':id[3],//level approval -1
				'no_spk':id[4],//nospk
				'kd_dealer':id[5],//kode dealer
				'jmlAprover':id[6] //jumlah approval 2
			});
		})
		if(chkArray.length>0){
			$.post("<?php echo base_url();?>spk/approval_spk",{'d':JSON.stringify(chkArray)},function(result){
				 console.log(result);
				document.location.reload();
				$('#loadpage').removeClass("hidden");

			})
		}
	}
</script>