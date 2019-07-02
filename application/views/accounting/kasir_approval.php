<?php
if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $usergroup=$this->session->userdata("nama_group");
  $mode=($this->input->get("t"))?"":"hidden";
  $no_promise=($usergroup!=='Root')?"disabled-action":"";
  $defaultDealer=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
  $app_level = isApproval("KS250");
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>

        <div class="bar-nav pull-right ">
            
        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                APPROVAL PENGELUARAN KAS
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
            	<div class="col-xs-12 col-sm-4 col-md-4">
            		<div class="form-group">
            			<label>Nama Dealer</label>
            			<select name="kd_dealer" id="kd_dealer" class="form-control">
	    					<option value="">--Pilih Dealer--</option>
	    					<?php
								if($dealer){
									if(is_array($dealer->message)){
										foreach ($dealer->message as $key => $value) {
											$select="";//($this->session->userdata('kd_dealer')==$value->KD_DEALER)?"selected":"";
	                                        $select=($defaultDealer==$value->KD_DEALER)?"selected":$select;
											echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
										}
									}
								}
							?>
	    				</select>
	    			</div>
	    		</div>
	    		<div class="pull-right">
	    			<br>
	    			<button class="btn btn-info disabled-action " type="button" id="aprv"><i class="fa fa-cog"></i> Approved</button>
	    			<button class="btn btn-info disabled-action " type="button" id="unaprv"><i class="fa fa-cog"></i> Un Approved</button>
	    		</div>
	    	</div>
	    </div>
	</div>
	<div class="clearfix"></div>
    <div class="col-lg-12 padding-left-right-10">
    	<div class="table-responsive">
    		<?php 
    			$no_promise =((int)$app_level>0)?'':'disabled-action';
    			$no_promise =($status_c)?"disabled-action":$no_promise;
    			?>
    		<table class="table table-striped table-hover table-bordered" id="lst_app">
                <thead>
                    <tr>
                        <th style="width: 5%">No.</th>
                        <th style="width: 3%" class="text-center">
                        	<input class="<?php echo $no_promise;?>" type="checkbox" id="chk_all" title="Appove All" style="cursor: pointer;">
                        </th>
                        <th style="width: 15%">No.Trans</th>
                        <th style="width: 8%">Tanggal</th>
                        <th style="width: 25%">Keterangan</th>
                        <th style="width: 12%">Penerima</th>
                        <th style="width: 5%">Jumlah</th>
                        <th style="width: 10%">Harga</th>
                        <th style="width: 10%">Min Value</th>
                    </tr>
                </thead>
                <tbody>
                	<?php 
                	// var_dump($transd);
                	$n=0;
                	if(isset($transd)){
                		if($transd->totaldata>0){
                			$total=0;
                			//print_r($transd->message);exit();
                			foreach ($transd->message as $key => $value) {
                				$jmlKeluar = ((double)$value->HARGA*(double)$value->JUMLAH);
                				if(/*( $jmlKeluar >= (double)$value->MIN_VALUE) &&*/ (double)$value->MIN_VALUE > 0){
                					$tampil=($defaultDealer==$value->KD_DEALER)?'':'hidden';
	                				?>
	                					<tr class='<?php echo $value->KD_DEALER." ".$tampil;?>'>
	                						<td class='text-center'><?php echo ($n+1);?></td>
	                						<td class='text-center'><input class="<?php echo $value->KD_DEALER." ".$no_promise;?>" type="checkbox" name="chk_<?php echo $value->ID;?>" id="chk_<?php echo $n;?>" style="cursor: pointer;"></td>
	                						<td class='text-left table-nowarp'><?php echo $value->NO_TRANS;?></td>
	                						<td class='text-center table-nowarp'><?php echo $value->TGL_TRANS;?></td>
	                						<td class='' title="<?php echo $value->URAIAN_TRANSAKSI;?>"><?php echo $value->URAIAN_TRANSAKSI;?></td>
	                						<td class=''><?php echo strtoupper($value->NO_REFF);?></td>
	                						<td class='text-right table-nowarp'><?php echo number_format($value->JUMLAH,0);?></td>
	                						<td class='text-right table-nowarp'><?php echo number_format($value->HARGA,0);?></td>
	                						<td class='text-right table-nowarp'><?php echo number_format($value->MIN_VALUE,0);?></td>
	                					</tr>
	                					<!-- <tr class='<?php echo $value->KD_DEALER." ".$tampil;?>'><td colspan="9"><?php echo $jmlKeluar;?></td></tr> -->
	                				<?
	                				$n++;
	                				if($tampil){
		                				$total +=$value->HARGA;
		                			}
		                			$jmlKeluar=0;
	                			}
                			}
                		}
                	}
                	?>
                </tbody>
                <tfoot>
                	<tr class="total hidden">
                		<td class='text-right' colspan="6"><em>Total</em></td>
                		<td>&nbsp;</td>
                		<td class='text-right'><?php echo number_format($total,0);?></td>
                	</tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php echo loading_proses();?>
</section>
<script type="text/javascript">
	$(document).ready(function(){
		var row= $('#lst_app > tbody >tr').length; var cheked=0;
		$('#chk_all').on('click',function(){
			var dlr=$('#kd_dealer').val();
			console.log(dlr);
			if($("input").hasClass(dlr)){
				$("input[id^='chk_']").prop('checked',this.checked);
				if(this.checked){
					$("#aprv").removeClass("disabled-action");
					$("#unaprv").removeClass("disabled-action");				
				}else{
					$("#aprv").addClass("disabled-action");
					$("#unaprv").addClass("disabled-action");
				}
			}
		})
		$('#kd_dealer').on("change",function(e){
			if($(this).val()){
				$('#lst_app >tbody tr.'+$(this).val()).removeClass("hidden");
				$('#lst_app >tbody tr:not(.'+$(this).val()+')').addClass("hidden");
			}else{
				$('#lst_app >tbody tr').removeClass("hidden");
			}			
			$("input[id^='chk_']").prop('checked',false);
		})
		$("input[id^='chk_']").on('click',function(){
			$(this).each(function(){
				if($(this).is(":checked")==true){
					cheked ++
				}else{
					cheked --
				}
			})
			if(cheked==0){
				$("#aprv").addClass("disabled-action");
				$("#unaprv").addClass("disabled-action");
			}else{
				$("#aprv").removeClass("disabled-action");
				$("#unaprv").removeClass("disabled-action");	
			}
		})

		$('#aprv').click(function(){
			var row= $('#lst_app > tbody > tr').length; 
			var data=[];
			for(i=0;i< row;i++){
				if($('#chk_'+i).is(":checked")){
					data.push({
					  'no_trans': $("#lst_app > tbody >tr:eq("+i+") > td:eq(2)").text(),
				      'voucher_no': "<?php echo $defaultDealer.date("Ym")."-".str_pad(mt_rand(),5,'0',STR_PAD_LEFT);?>"
					})
				}
			}
			$('#loadpage').removeClass("hidden");
			$.ajax({
				type :'POST',
				url :"<?php echo base_url();?>cashier/approve_trans",
				data:{'dt':JSON.stringify(data)},
				dataType:'json',
				success:function(result){
					if(result.status){
				    	$('.success').animate({ top: "0"}, 500);
			            $('.success').html('Approval berhasil').fadeIn();
			            
				        setTimeout(function() {
				            document.location.href="<?php echo base_url();?>cashier/kasirapp";
				        	$('#loadpage').addClass("hidden");
				            //jika data success . print kwitansi trus register
				           // __print_kwitansi();
				        }, 2000);    
					}else{
						$('.error').animate({ top: "0"}, 500);
			            $('.error').html('Data gagal di approve').fadeIn();
			            setTimeout(function() {
				            hideAllMessages();
				        }, 2000);
					}
				}
			})
		}) 
	})
</script>