<?php
  if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $usergroup=$this->session->userdata("kd_group");

?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>

        <div class="bar-nav pull-right ">
            <a id="modal-button" class="btn btn-default" href="<?php echo base_url('cashier/kasirnew'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Transaksi Baru
            </a>
            <a id="modal-button-1" class="btn btn-default" href="<?php echo base_url('cashier/seleksi_lkh'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Seleksi Transaksi
            </a>
            <a id="modal-button-1" class="btn btn-default" href="<?php echo base_url('cashier/laporan_lkh'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Laporan Kas Harian
            </a>

        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                List Transaksi Kasir
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
            	<form id="frmCriteria" action="<?php echo base_url('cashier/listkasir') ?>" class="bucket-form" method="get">
            		<div id="ajax-url" url="<?php echo base_url('cashier/tm_typeahead'); ?>"></div>
            		<div class="row">
            			<div class="col-sm-6">
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
            			<div class="col-sm-6">
            				<div class="col-sm-6">
                                <div class="form-group">
                					<label>Periode dari Tanggal</label>
                					<div class="input-group append-group date">
    	        						<input type="text" class="form-control" id="tgl_trans_aw" name="tgl_trans_aw" value="<?php echo ($this->input->get("tgl_trans_aw"))?$this->input->get("tgl_trans_aw"):date("d/m/Y",strtotime("-1 days"));?>">
    	        						<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span> </span>
    	        					</div>
                                </div>
	        				</div>
	        				<div class="col-sm-6">
                                <div class="form-group">
                					<label>Sampai Tanggal</label>
                					<div class="input-group append-group date">
    	        						<input type="text" class="form-control" id="tgl_trans_ak" name="tgl_trans_ak" value="<?php echo ($this->input->get("tgl_trans_ak"))?$this->input->get("tgl_trans_ak"):date("d/m/Y");?>"">
    	        						<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span> </span>
    	        					</div>
                                </div>
	        				</div>
	        			</div>
            		</div>
            		<div class='row'>
            			<div class="col-sm-6">
                            <div class="form-group">
                				<label>Find by</label>
                				<input class="form-control" type="text" id="keyword" name="keyword" placeholder="cari berdasarkan No Trans, nama customer atau no spk">
                            </div>
            			</div>
            			<div class="col-sm-6">
                            <div class="pull-right" style="padding-right: 20px;padding-top: 20px">
                               
            				    <button type="submit" class="btn btn-primary"><i class="fa fa-view fa-fw"></i> Preview</button>
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
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                    	<tr>
                    		<th>No.</th>
                    		<th></th>
                    		<th>No Transaksi</th>
                    		<th>Tgl Transaksi</th>
                    		<th>Uraian Transaksi</th>
                    		<th>Jumlah</th>
                    		<th>Dealer</th>
                    		<th>KD Account</th>
                    	</tr>
                    </thead>
                    <tbody>
                    	<?php
                    	 $n=$this->input->get('page');
                    		if($list){
                    			if(is_array($list->message)){
                    				foreach ($list->message as $key => $value) {
                    					$n++;
                                        $url=base_url('cashier/kasirnew/?n='.urlencode(base64_encode($value->NO_TRANS))."&x=".rand());
                                        $no_lkh=($value->LKH>0)?"":"info";
                    					echo "
                    					<tr class='$no_lkh'>
                    						<td class='text-center'>$n</td>
                    						<td class='text-center'>
                    							<a href='".$url."' class='".$status_v."'>
                    								<i data-toggle='tooltip' data-placement='left' title='view detail' class='fa fa-edit text-success text'></i>
                          						</a>
                    						</td>
                    						<td class='text-center table-nowarp'>".($value->NO_TRANS)."</td>
                    						<td class='text-center'>".tglfromSql($value->TGL_TRANS)."</td>
                    						<td>".$value->JENIS_TRANS." - ".$value->URAIAN_TRANSAKSI."</td>
                    						<td class='text-right'>".number_format($value->HARGA,0)."</td>
                    						<td class='text-center'>".($value->KD_DEALER)."</td>
                    						<td class='text-center' >".($value->KD_ACCOUNT)."</td>
                    					";
                    				}
                    			}
                    		}
                    	?>
                    </tbody>
                </table>
            </div>
        </div>
        <footer class="panel-footer">
            <div class="row">
                 <div class="col-sm-5">
                    <small class="text-muted inline m-t-sm m-b-sm"> 
                        <?php echo isset($list) ? ($list->totaldata == '' ? "" : "<i>Total Data " . $list->totaldata . " items</i>") : '' ?>
                    </small>
                </div>
                <div class="col-sm-7 text-right text-center-xs">                
                    <?php echo $pagination; ?>
                </div>
            </div>
        </footer>
    </div>
    <?php echo loading_proses();?>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        $('#frmCriteria').submit(function(){
            $('#loadpage').removeClass("hidden");
        })
    })

</script>