<?php 
	if (!isBolehAkses()) { redirect(base_url() . 'auth/error_auth');  }
	$bulan=($this->input->get("bulan"))?$this->input->get("bulan"):date("m");
	$tahun=($this->input->get("tahun"))?$this->input->get("tahun"):date("Y");
	$usergroup=$this->session->userdata("kd_group");
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        <div class="bar-nav pull-right ">
        	<a class="btn btn-default" id="modal-button" onclick='addForm("<?php echo base_url("finance/jurnal_new");?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
	          <i class="fa fa-file-o fa-fw"></i> Add Jurnal
	      </a>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                List Jurnal
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
            	<form id="filterForm" action="<?php echo base_url('finance/jurnal_list') ?>" class="bucket-form" method="get">
            		<div class="row">
            			<div class="col-sm-3 col-md-3 col-xs-12">
                            <div class="form-group">
                            	<label>Nama Dealer</label>
                				<select name="kd_dealer" id="kd_dealer" class="form-control" <?php echo($usergroup!=='0')?" disabled='disabled'":""?>">
                					<option value="">--Pilih Dealer--</option>
                					<?php
            							if($dealer){
            								if(is_array($dealer->message)){
            									foreach ($dealer->message as $key => $value) {
            										$select=($this->session->userdata('kd_dealer')==$value->KD_DEALER)?"selected":"";
                                                    $select=($this->input->get("kd_dealer")==$value->KD_DEALER)?"selected":$select;
            										echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
            									}
            								}
            							}
            						?>
                				</select>
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-3 col-xs-12">
                        	<div class="form-group">
                        		<label>Periode Bulan<?php //echo $bulan;?></label>
                        		<select name="bulan" class="form-control">
                        			<option value="">--Pilih Bulan</option>
                        			<?php
                        				for ($i=1; $i < 13; $i++){
                        					$select=($i==$bulan)?'selected':'';
                        					echo "<option value='".$i."' ".$select.">".nBulan($i)."</option>";
                        				}
                        			?>
                        		</select>
                        	</div>
                        </div>
                        <div class="col-sm-3 col-md-3 col-xs-12">
                        	<div class="form-group">
                        		<label>Tahun</label>
                        		<select name="tahun" class="form-control">
                        			<option value="">--Pilih Tahun</option>
                        			<?php
                        				if(isset($thn)){
                        					if($thn->totaldata >0){
                        						foreach ($thn->message as $key => $value) {
                        							$select=($tahun==$value->TAHUN)?"selected":"";
                        							echo "<option value='".$value->TAHUN."' ".$select.">".$value->TAHUN."</option>";
                        						}
                        					}
                        				}
                        			?>
                        		</select>
                        	</div>
                        </div>
                    </div>
            	</form>

            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12 padding-left-right-10">
    	<div class="panel panel-default">
	        <div class="table-responsive h350">
	            <table class="table table-striped table-hover table-bordered">
	                <thead>
	                    <tr>
	                    	<th>No.</th>
	                    	<td>&nbsp;</td>
	                    	<th>Tgl Jurnal</th>
	                    	<th>Nomor Jurnal</th>
	                    	<th>Keterangan Jurnal</th>
	                    	<th>Debet</th>
	                    	<th>Kredit</th>
	                    	<th>Balance</th>
	                    </tr>
	                </thead>
	                <tbody>
	                	<?php
	                	$n=($this->input->get("page"))?$this->input->get("page"):0;
	                		if(isset($trans)){
	                			if($trans->totaldata > 0){
	                				foreach ($trans->message as $key => $value) {
	                					$n++;
	                					$disabled=($value->CLOSING_STATUS==0)?"":"disabled-action";
	                					?>
	                						<tr>
	                							<td class="text-center table-nowarp"><?php echo $n;?></td>
	                							<td class="table-nowarp text-center">
	                								<a onclick='addForm("<?php echo base_url("finance/jurnal_new/").$value->NO_JURNAL;?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
	          											<i class="fa fa-edit"></i></a>
	                								<a onclick="__hapus_jurnal_h('<?php echo $value->NO_JURNAL;?>');" id="hps_<?php echo $value->NO_JURNAL;?>" class="<?php echo $disabled;?>"><i class="fa fa-trash"></i></a>
	                							</td>
	                							<td class='text-center table-nowarp'><?php echo TglFromSql($value->TGL_JURNAL);?></td>
	                							<td class='text-center table-nowarp'><?php echo $value->NO_JURNAL;?></td>
	                							<td class='tb-overflow-50' title="<?php echo $value->DESKRIPSI_JURNAL;?>"><?php echo $value->DESKRIPSI_JURNAL;?></td>
	                							<td class='text-right table-nowarp'><?php echo number_format($value->DEBET,0);?></td>
	                							<td class='text-right table-nowarp'><?php echo number_format($value->KREDIT,0);?></td>
	                							<td class='text-right table-nowarp'><?php echo number_format($value->BALANCE,0);?></td>
	                						</tr>
	                					<?php
	                				}
	                			}
	                		}
	                	?>
	                </tbody>
	            </table>
	        </div>
	        <footer class="panel-footer">
	            <div class="row">

	                <div class="col-sm-5">
	                    <small class="text-muted inline m-t-sm m-b-sm"> 
	                        <?php echo ($trans) ? ($trans->totaldata == '' ? "" : "<i>Total Data " . $trans->totaldata . " items</i>") : '' ?>
	                    </small>
	                </div>
	                <div class="col-sm-7 text-right text-center-xs">                
	                    <?php echo $pagination; ?>
	                </div>
	            </div>
	        </footer>
	    </div>
    </div>
</section>
<script type="text/javascript">
    var path = window.location.pathname.split('/');
    var http = window.location.origin + '/' + path[1];
	$(document).ready(function(){
		$('#myModalLg').on('hidden.bs.modal', function () {
	    	document.location.reload();
		})
	})
    function __hapus_jurnal_h(no_jurnal){
        if(confirm('Yakin akan hapus jurnal ini?')){
            $('#hps_'+no_jurnal).html("<i class='fa fa-spinner fa-spin' style='color:red'></i>");
            $.getJSON(http+"/finance/hapus_jurnal",{'no_jurnal':no_jurnal},function(result){
                document.location.reload();
            })
        }
    }
</script>