<?php
	if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
	$defaultDealer = ($this->input->get('kd_dealer'))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
	$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
	$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
	$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
	$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
	$user_id="";
	$user_name="";
	if(isset($list)){
		if($list->totaldata > 0){
			foreach ($list->message as $key => $value) {
				$user_id = $value->USER_ID;
				$user_name = $value->USER_NAME;
			}
		}
	}
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  	<h4 class="modal-title"><i class='fa fa-cogs'></i> Setup Document Approval</h4>
</div>
<div class="modal-body">
	<form id="addForm" class="bucket-form" action="<?php echo base_url("user/apv_docs_simpan");?>" method="post">
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6">
				<div class="form-group">
					<label>User ID</label>
					<input type="text" class="form-control disabled-action" name="user_id" value='<?php echo $user_id;?>'>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-sm-6">
				<div class='form-group'>
					<label>User Name</label>
					<input type="text" class="form-control disabled-action" name="user_name" value='<?php echo $user_name;?>'>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="table-responsive h350">
				<table class="table table-hover table-borderd table-stripped" id="lsd">
					<thead>
						<tr>
							<th>No</th>
							<th>#</th>
							<th>Kode </th>
							<th>Nama Document</th>
							<th>Apv Level</th>
							<th style="width:20%">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$n=0;
							if(isset($lstdoc)){
								if($lstdoc->totaldata>0){
									foreach ($lstdoc->message as $key => $value) {
										$n++;
										$level= $value->APV_LEVEL;
										?>
											<tr>
												<td class='text-center table-nowarp'><?php echo $n;;?></td>
												<td class='text-center'>
													<input type='checkbox' id="chk_<?php echo $n;?>" name='<?php echo $value->KD_MODUL;?>' value='<?php echo $value->KD_MODUL;?>' class='<?php echo $status_c;?>' <?php echo ((int)$level>0)?"checked='checked'":"";?>>
												</td>
												<td class='table-nowarp'><?php echo $value->KD_MODUL;?></td>
												<td class='table-nowarp'><?php echo $value->NAMA_MODUL;?></td>
												<td class='text-center'>
													<select id="c_<?php echo $n;?>" name="c_<?php echo $value->KD_MODUL;?>" class='form-control'>
														<?php
															for($i=1;$i<= $value->LEVEL_APV;$i++){
																$pilih=((int)$level==$i)?'selected':'';
																echo "<option value='".$i."' ".$pilih.">".$i."</option>";
															}
														?>
													</select>
												</td>
												<td>&nbsp;</td>
											</tr>
										<?
									}
								}
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
  <button id="submit-btne" class="btn btn-danger <?php echo $status_e?>"><i class='fa fa-save'></i> Simpan</button>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#submit-btne').click(function(){
			__simpan_apv();
		})
	})

	function __simpan_apv(){
		var defaultBtn = $("#submit-btne").html();
		var act = $("#addForm").attr('action');
		var data=[];
		var jml = $('#lsd > tbody > tr').length;
		for(i=0; i<jml;i++){
			if($('#chk_'+i).is(":checked")){
				data.push({
					'kd_doc' : $('#chk_'+i).val(),
					'lvl'	 : $('#c_'+i).val()
				})
			}
			
		}
		console.log(data);
		$("#submit-btne").html("<i class='fa fa-spinner fa-spin'></i> Process...");
		$.ajax({
	    url:act,
	    type:"POST",
	    dataType: "json",
	    data:$("#addForm").serialize()+'&detail='+JSON.stringify(data),
	    success:function(result){

	      if (result.status == true) 
	      {
	       
	        $('.success').animate({ top: "0" }, 500).fadeIn();
	        $('.success').html(result.message);

	        setTimeout(function(){
	            location.reload();
	        }, 2000);
	      }else{

	        $('.error').animate({ top: "0" }, 500).fadeIn();
	        $('.error').html(result.message);

	        setTimeout(function () {
	            hideAllMessages();
	            $("#submit-btne").removeClass("disabled");
	            $("#submit-btne").html(defaultBtn);
	        }, 4000);
	        
	      }

	    }
	  });
	}
</script>