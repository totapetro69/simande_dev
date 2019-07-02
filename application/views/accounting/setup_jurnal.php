<?php 
	if (!isBolehAkses()) { redirect(base_url() . 'auth/error_auth');  }
	$jtrans=$this->input->get("j");
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        <div class="bar-nav pull-right ">
        	<a class="btn btn-default" id="modal-button" onclick='addForm("<?php echo base_url("finance/jno_add");?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
	          <i class="fa fa-file-o fa-fw"></i> Add Setup
	      </a>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                List Setup Jurnal Otomatis
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
            	<form id="filterForm" action="<?php echo base_url('finance/setup_jno') ?>" class="bucket-form" method="get">
            		<div class="row">
            			<div class="col-sm-4 col-md-3 col-xs-12">
                            <div class="form-group">
                            	<label>Jenis Transaksi</label>
                            	<select name="j" class="form-control">
                            		<option value="">--Pilih Jenis Transaksi--</option>
                            		<?php
                            			if(isset($trans)){
                            				if($trans->totaldata > 0){
                            					foreach ($trans->message as $key => $value) {
                            						$select =($jtrans==$value->KD_TRANSAKSI)?"selected":"";
                            						echo "<option value='".$value->KD_TRANSAKSI."' ".$select.">".strtoupper($value->NAMA_TRANS)."</option>";
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
	                    	<th>No. Perkiraan</th>
	                    	<th>Nama Perkiraan</th>
	                    	<th>Posisi</th>
	                    	<th>Keterangan</th>
	                    </tr>
	                </thead>
	                <tbody>
	                	<?php
	                		$x=0;
	                		$n=($this->input->get("page"))?$this->input->get("page"):0;
	                		if(isset($trans)){
	                			if($trans->totaldata > 0){
	                				foreach ($trans->message as $key => $val) {
	                					$x++;$nn=0;
	                					$tampil=($jtrans && $jtrans!=$val->KD_TRANSAKSI)?"hidden":"";
	                					?>
	                						<tr class="info <?php echo $tampil;?>">
	                							<td class="text-left"><?php echo $x;?><span id="ls_<?php echo $val->KD_TRANSAKSI;?>" class="pull-right hidden"><i style="color:red" class="fa fa-spinner fa-spin"></i></span></td>
	                							<td class="table-nowarp text-center">
	                								<a onclick='addForm("<?php echo base_url('finance/jno_add/').$val->KD_TRANSAKSI;?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"  title="Edit"><i class="fa fa-edit"></i></a>
	                								<a onclick="__hapus_jno('<?php echo $val->KD_TRANSAKSI;?>')" href="#" title="Hapus"><i class="fa fa-trash"></i></a>
	                							</td>
	                							<td colspan="4"><b><?php echo $val->NAMA_TRANS ." [ ".$val->KD_TRANSAKSI." ]";?></b></td>
	                						
	                					<?php 
				                		if(isset($list)){
				                			if($list->totaldata >0){ $d="";
				                				foreach ($list->message as $key => $value) {
				                					if($value->KD_TRANSAKSI==$val->KD_TRANSAKSI){
				                						$nn++;
				                						$d="";//($d != $value->TYPE_AKUN && $d!='')?"style='border-top:2px solid yellow !important'":'';
					                					?>
					                						<tr <?php echo $d;?>>
					                							<td class="text-center"><?php echo $nn;?></td>
					                							<td class="table-nowarp text-center"><?php //echo $value->KD_TRANSAKSI."--".$val->KD_TRANSAKSI;?></td>
					                							<td class="table-nowarp"><?php echo ($value->TYPE_AKUN=='K')?str_repeat("&nbsp;", 5):"";?><?php echo $value->KD_AKUN;?></td>
					                							<td class="td-overflow-50"><?php echo ($value->TYPE_AKUN=='K')?str_repeat("&nbsp;", 5):"";?><?php echo $value->NAMA_AKUN;// get_perkiraan($value->NO_AKUN);?></td>
					                							<td class="text-center"><abbr title="<?php echo ($value->TYPE_AKUN=='D')?'Debet':'Kredit';?>"><?php echo $value->TYPE_AKUN;?></abbr></td>
					                							<td>&nbsp;</td>
					                						</tr>
					                					<?php
					                					$d=$value->TYPE_AKUN;
					                				}
				                				}
				                			}
				                		}
				                		?>
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
	                        <?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total Data " . $list->totaldata . " items</i>") : '' ?>
	                    </small>
	                </div>
	                <div class="col-sm-7 text-right text-center-xs">                
	                    <?php //echo $pagination; ?>
	                </div>
	            </div>
	        </footer>
	    </div>
    </div>
</section>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/setup_jurnal_oto.js?v=").date('YmdHis');?>"></script>