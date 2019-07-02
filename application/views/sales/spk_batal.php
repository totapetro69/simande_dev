<?php if(!isBolehAkses()){ redirect(base_url() . 'auth/error_auth');}
	$defaultDealer=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
	$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
	$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
    $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
	$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
	$no_spk=$this->input->get("no_spk");
    $keterangan="";
    if(isset($ket)){
        if($ket->totaldata >0){
            $keterangan = $ket->message[0]->KETERANGAN;
        }
    }
    if(isset($spkdtl)){
        if($spkdtl->totaldata >0){
            $defaultDealer=$spkdtl->message[0]->KD_DEALER;
        }
    }
    $hidden = ($this->input->get("f"))?"hidden":"";
    $show   = ($this->input->get("f"))?"":"hidden";
    $disbled = ($this->input->get("f"))?"disabled-action":"";
?>
<div class="wrapper">
	<div class="breadcrumb margin-bottom-10">
        <div id="bc1" class="myBreadcrumb">
            <a href="javascript:void(0);"><i class="fa fa-home fa-2x"></i></a>
            <a href="javascript:void(0);"><div>Sales</div></a>
            <a href="javascript:void(0);" class="active"><div>SPK</div></a>
            <a href="javascript:void(0);" class="active"><div>Batal SPK</div></a>
        </div>

        <div class="bar-nav pull-right ">

            <a href="<?php echo base_url("spk/batal_spk");?>" role="button"class="btn btn-default <?php echo $hidden;?>"><i class="fa fa-file-o"></i> Baru </a>
            <a class="btn btn-info <?php echo $status_c." ". $hidden;?>" role="button" id="modal-buttonx"><i class="fa fa-cogs fa-fw"></i> Process Batal SPK</a>
            <a class="btn btn-info <?php echo $status_c." ". $show;?>" role="button" id="approve_batal"><i class="fa fa-file-o fa-fw"></i> Approved Pembatalan SPK</a>
            <a class="btn btn-warning <?php echo $status_c." ". $show;?>" role="button" id="notapprove_batal"><i class="fa fa-trash fa-fw"></i> Not Approved</a>
            <a id="btn_list" class="btn btn-default <?php echo $status_v." ". $show;?>" role="button" href="<?php echo base_url("cashier/approval_ds");?>"><i class="fa fa-list-ul fa-fw"></i> List Approval</a>
        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">
    	<div class="panel-heading">
                <i class='fa fa-list-ul'></i> History SPK
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
        <div class="panel margin-bottom-10">
        	<div class="panel-body panel-body-border" style="display: block;">
	        	<form method="get" id="frm1" action ="<?php echo base_url("spk/batal_spk");?>" class="<?php echo $disbled;?>">
	        		<div class='row'>
		        		<div class="col-xs-12 col-sm-4 col-md-4">
		        			<div class="form-group">
		        				<label>Dealer</label>
		        				<select id="kd_dealer" name="kd_dealer" class="form-control">
		        					<opton value="">--Pilih Dealer--</opton>
		        					<?php 
		        						if(isset($dealer)){
		        							if($dealer->totaldata >0){
		        								foreach ($dealer->message as $key => $value) {
		        									$pilih =($defaultDealer==$value->KD_DEALER)?"selected":"";
		        									echo "<option value='".$value->KD_DEALER."' ".$pilih.">".$value->NAMA_DEALER."</option>";
		        								}
		        							}
		        						}
		        					?>
		        				</select>
		        			</div>
		        		</div>
		        		<div class="col-xs-12 col-sm-3 col-md-3">
		        			<div class="form-group">
		        				<label>No. SPK</label>
		        				<input type="text" id="no_spk" name="no_spk" class="form-control" value='<?php echo $no_spk;?>' placeholder="NO SPK" required="true">
		        			</div>
		        		</div>
                        <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                                <label>Alasan Pembatalan</label>
                                <input type="text" id="alasan" name="alasan" placeholder="Alasan Pembatalan" class="form-control" value='<?php echo $keterangan;?>' required="true">
                            </div>
                        </div>
		        		<div class="col-xs-3 col-sm-1 col-md-1 hidden">
		        			<div class="form-group">
		        				<br>
		        				<button type="submit" class="btn btn-default">OK</button>
		        			</div>
		        		</div>
		        	</div>
		        </form>
		    </div>
	    </div>
	</div>
	<div class="col-lg-12 padding-left-right-10">
        <div class="table-responsive h350">
        	<table class="table table-bordered table-hover table-striped">
        		<thead>
        			<tr>
        				<th>NO</th>
        				<th>Keterangan</th>
        				<th>NO. Document</th>
        				<th>Tanggal Document</th>
        				<th>Nama Customer</th>
        				<th>Nama Sales</th>
        			</tr>
        		</thead>
        		<tbody>
        			<?php
        				$n=0;
        				$status_spk="0";
        				if(isset($spkdtl)){
        					if($spkdtl->totaldata >0){
        						foreach ($spkdtl->message as $key => $value) {
        							$n++;
        							?>
        								<tr>
        									<td class='text-center'><?php echo $n;?></td>
        									<td class='table-nowarp'><?php echo $value->KETERANGAN;?></td>
        									<td class='table-nowarp'><?php echo $value->DOC_NO;?></td>
        									<td class='table-nowarp'><?php echo TglFromSql($value->TGL_DOC);?></td>
        									<td class='table-nowarp'><?php echo $value->NAMA_CUSTOMER;?></td>
        									<td class='table-nowarp'><?php echo $value->NAMA_SALES;?></td>
        									<td class='hidden'><?php echo $value->DOC_ID;?></td>
                                            <td class='hidden'><?php echo $value->KD_DEALER;?></td>
        								</tr>
        							<?php
                                    if($value->NOM=="1"){
                                        $status_spk =$value->STATUS_SPK;
                                    }                                   
        						}
        					}
        				}
        			?>
        		</tbody>
        	</table>
        </div>
    </div>
    <?php echo loading_proses();?>
</div>
<script language="javascript">
	$(document).ready(function(){
        $('#no_spk').on('focusout',function(){
            var sudahsubmit="<?php echo $this->input->get("no_spk");?>";
            if(!sudahsubmit){
                $('#frm1').submit();
            }
        });
        $('#modal-buttonx').click(function(){
            __simpan_batal();
        })
        $('#approve_batal').click(function(){
            if(confirm('Permintaan Pembatalan SPK ini akan di approval?')){
                $('#loadpage').removeClass("hidden");
                var datax=[];
                datax.push({
                    'id':'1',
                    'tp': 'PEMBATALAN',
                    'level': '2',
                    'no_spk' : $('#no_spk').val(),
                    'kd_dealer': $('#kd_dealer').val(),
                    'jmlAprover':"1"
                })
                $.ajax({
                    type :'POST',
                    url :"<?php echo base_url('spk/approval_spk');?>",
                    dataType :"json",
                    data: {'d':JSON.stringify(datax)},
                    success : function(result){
                        console.log(result);
                        document.location.href="<?php echo base_url('cashier/approval_ds');?>"
                    }
                })
            }
        })
        $('#notapprove_batal').click(function(){
            if(confirm('Permintaan Pembatalan SPK ini tidak akan di approval?')){
                $('#loadpage').removeClass("hidden");
                var datax=[];
                datax.push({
                    'no_spk' : $('#no_spk').val(),
                    'kd_dealer': $('#kd_dealer').val(),
                    'status'   :'-2'
                })
                $.ajax({
                    type : 'POST',
                    url :"<?php echo base_url('spk/unapv_batal_spk');?>",
                    data : {'d':JSON.stringify(datax)},
                    dataType : 'json',
                    success : function(result){
                        console.log(result);
                        document.location.href="<?php echo base_url('cashier/approval_ds');?>"
                    }
                })
            }
        })
	})
    function __simpan_batal(){
        if(!$('#frm1').valid()){ return false;}
        var datax="";
        $('#loadpage').removeClass("hidden");
        datax= {
            'no_spk' : $('#no_spk').val(),
            'alasan' : $('#alasan').val(),
            'status' :"<?php echo $status_spk;?>",
            'kd_dealer': "<?php echo $defaultDealer;?>",
            'jenis_doc':'SPK'
        };
        $.ajax({
            type :'POST',
            url :"<?php echo base_url("spk/batal_spk_prs");?>",
            data : $('#frm1').serialize()+"&status=<?php echo $status_spk;?>&jenis_doc=SPK",
            dataType: 'json',
            success : function(result){
                document.location.href ="<?php echo base_url("spk/batal_spk");?>";
            }
        })
    }
</script>