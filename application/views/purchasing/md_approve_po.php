<script src="<?php echo base_url('assets/dist/print.min.js');?>"></script>
<script type="text/javascript">
   function printKw() {
      printJS('printarea','html');
      $('#keluar').click();
    }
	
	function rejectPO(){
		var id = $('#no_po').val();
		console.log(id);
		if(confirm("Reject PO ini?")){
	  		$.ajax({
	  			url :'<?php echo base_url("purchasing/reject_po");?>',
	  			type :'GET',
	  			dataType:'json',
	  			data :{'id':id},
	  			success : function(result){
	  				document.location.href="<?php echo base_url('purchasing/po_received');?>";
	  			}
	  		})
	  	}
	}
	function returnPO(){
		var id = $('#no_po').val();
		console.log(id);
		if(confirm("Kembalikan PO ini?")){
	  		$.ajax({
	  			url :'<?php echo base_url("purchasing/return_po");?>',
	  			type :'GET',
	  			dataType:'json',
	  			data :{'id':id},
	  			success : function(result){
	  				document.location.href="<?php echo base_url('purchasing/po_received');?>";
	  			}
	  		})
	  	}
	}
	function processPO(){
		var id = $('#no_po').val();
		console.log(id);
		if(confirm("Proses PO ini?")){
	  		$.ajax({
	  			url :'<?php echo base_url("purchasing/process_po");?>',
	  			type :'GET',
	  			dataType:'json',
	  			data :{'id':id},
	  			success : function(result){
	  				document.location.href="<?php echo base_url('purchasing/po_received');?>";
	  			}
	  		})
	  	}
	}
</script>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Print Preview PO</h4>
</div>
<div class="modal-body">
    <div class="row" id="printarea">
        <div class="col-lg-12 padding-left-right-10">
            <div class="row margin-bottom-10">
                <?php
                    $default="";$KD_DEALER="";
                        if(base64_decode(urldecode($this->input->get("n")))){
                            foreach ($poheader->message as $key => $value) {
                                $KD_DEALER  = $value->KD_DEALER;
                                $namadealer = $value->NAMA_DEALER;
                                $no_po      = $value->NO_PO;
								echo'<input type=hidden id = "no_po" value = '.$value->NO_PO.' />';
                                $periode    = ($value->KD_JENISPO!='F')?$value->PERIODE_PO:$value->PERIODE_PO." (".tglfromSql($value->TGL_AWALPO)." sd ".tglfromSql($value->TGL_AKHIRPO)." )";
                                $bulan      = $value->BULAN_KIRIM;
                                $tahun      = $value->TAHUN_KIRIM;
                                $jenispo    = $value->KD_JENISPO;
                                $tglpo      = tglfromSql($value->TGL_PO);
                                $approval   = ($value->APPROVAL_PO)?$value->APPROVAL_PO:"0";
								$status_po	= $value->STATUS_PO;
                            }
                        }
                                ?>
            	<CENTER><b>PURCHASE ORDER (PO) DETAIL</b></CENTER>
            	<br>
                <div class="col-sm-12">
    				<table width="100%">
                    	
                    	<tr>
                    		<td><label>Dealer</label></td>
                    		<td>:</td>
                         	<td>&nbsp;<?php echo strtoupper($namadealer);?></td>
                            <td>&nbsp;</td>
                            <td><label>Nomor PO</label></td>
                            <td>:</td>
                            <td>&nbsp;<?php echo $no_po;?></td>
                        </tr>
                        <tr>
                        	<td><label>Kode Dealer</label></td>
                        	<td>:</td>
                        	<td>&nbsp;<?php echo strtoupper($KD_DEALER);?></td>
                            <td>&nbsp;</td>
                            <td><label>Tanggal PO</label></td>
                            <td>:</td>
                            <td>&nbsp;<?php echo $tglpo;?></td>
                        </tr>
                        <tr>
                        	<td><label>Bulan/Tahun</label></td>
                        	<td>:</td>
                        	<td>&nbsp;<?php echo $bulan."/".$tahun;?></td>
                            <td>&nbsp;</td>
                            <td><label>Jenis PO</label></td>
                            <td>:</td>
                            <td>&nbsp;<?php echo $jenispo;?></td>
                        </tr>
                        <tr>
                        	<td><label>Periode</label></td>
                        	<td>:</td>
                        	<td>&nbsp;<?php echo $periode;?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
		<br /><br /><br />
        <div class="col-lg-12 padding-left-right-10">
        		<!-- <div class="panel panel-default"> -->
                    <div class="table-responsive h350">
                        <table class="table table-striped b-t b-light">
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
                            if(base64_decode(urldecode($this->input->get("n")))){
                                echo "<tbody>";
                                $i=0;
                                if($detail){
                                foreach ($detail->message as $key => $value) {
                                    
                                    echo "<tr>
                                            <td>".($i+1)."</td>
                                            <td>".$value->KD_TYPEMOTOR."-".$value->KD_WARNA."</td>
                                            <td>".$value->NAMA_ITEM."</td>
                                            <td class='text-center'>".number_format($value->FIX_QTY,0)."</td>
                                            <td class='text-center'>".number_format($value->T1_QTY,0)."</td>
                                            <td class='text-center'>".number_format($value->T2_QTY,0)."</td>
                                        </tr>";
                                      $i++;
                                }
                                }else{
                                    echo "<tr><td colspan='6'>Nomor po tidak ada isi nya</td></tr>";
                                }
                                echo "</tbody>";
                            }
                        ?>
                        </table>
                    </div>
                <!-- </div> -->
        </div>
    </div>
</div>
<div class="modal-footer">
    <button id="keluar" type="button" class="btn btn-default" data-dismiss="modal"><i class='fa fa-close fa-fw'></i> Keluar</button>
	<?php 
		if($status_po == 1 && $approval > 0){
	?>
    <button type="button" class="btn btn-success" onclick="js:processPO()"><i class="fa fa-check fa-fw"></i> Process</button>
	<button type="button" class="btn btn-warning" onclick="returnPO()"><i class="fa fa-warning fa-fw"></i> Return</button>
	<button type="button" class="btn btn-danger" onclick="rejectPO()"><i class="fa fa-ban fa-fw"></i> Reject</button>
	<?php
		}
	?>
	<button type="button" class="btn btn-default" onclick="js:printKw()"><i class="fa fa-print fa-fw"></i> Cetak Ke Printer</button>
	
    <div class="btn-group">
		<a type="button" href="<?php echo base_url('purchasing/createfile_udpo?n='.$this->input->get('n').'');?>" class="btn btn-default <?php echo ((int)$approval>0)?'':'disabled-action';?>">
			<i class="fa fa-download fa-fw"></i> Download file .UDPO  <!-- <span class="caret"></span> -->
		</a>
		<ul class="dropdown-menu">
			<li><a href="#">.UDPO</a></li>
			<!-- <li><a href="#">Excel</a></li> -->
		</ul>
	</div>
</div>
?>