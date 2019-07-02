<?php
if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $status_n = ($this->session->userdata("nama_group")=="Root")?"":"disabled='disabled'";
  $pilih=$this->input->get('pilih');
  $defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
  $dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('First day of previous month'));
  $sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y",strtotime('first day of next month'));
  $no_mesin=$this->input->get("keyword");
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
               <i class="fa fa-cog fa-fw"></i> Stok Movement Unit
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">
            	<form action="<?php echo base_url('inventori/stockoverview') ?>" class="bucket-form" method="get">
	            		<div id="ajax-url" url="<?php echo base_url('inventori/stock_unit_typeahead'); ?>"></div>
		            	<div class="row">
		            		<div class="col-xs-12 col-md-6 col-sm-6">
		            			<div class="form-group">
		            				<label>Nama Dealer</label>
	                                <select class="form-control " id="kd_dealer" name="kd_dealer" <?php echo $status_n;?>>
	                                    <option value="">--Pilih Dealer--</option>
	                                    <?php
	                                    if ($dealer) {
	                                        if (is_array($dealer->message)) {
	                                            foreach ($dealer->message as $key => $value) {
	                                                $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
	                                                $aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
	                                                echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
	                                            }
	                                        }
	                                    }
	                                    ?> 
	                                </select>
	                            </div>
		            		</div>
		            		
	            			<div class="col-xs-12 col-sm-4 col-md-4">
	            				<div class="form-group">
	            					<label>No. Rangka</label>
	            					<input type="text" class="form-control" id="keyword" autocomplete="off" name="keyword" placeholder="Input no rangka" required="requeired" value="<?php echo $no_mesin;?>">
	            				</div>
	            			</div>
	            			<!-- <div class="col-xs-12 col-sm-6 col-md-6">
	            			</div> -->
	            			<div class="col-xs-12 col-sm-2 col-md-2 pull-right">
	            				<div class="form-group">
	            					<label></label>
	            					<button class="btn btn-default form-control" type="submit"><i class='fa fa-search'></i> Preview </button>
	            				</div>
	            			</div>
	            		</div>
		            </div>
		        </form>
            </div>
        </div>
        <div class="col-lg-12 padding-left-right-10">
        	<div class="panel panel-default">
        		<?php
        			$kd_item="";$nama_item="";$no_mesin="";$no_rangka="";$tahun="";
        			if($motor){
        				if($motor->totaldata>0){
        					foreach ($motor->message as $key => $value) {
        						$kd_item 	= $value->KD_ITEM;
        						$nama_item 	= $value->NAMA_ITEM;
        						$no_mesin 	= $value->NO_MESIN;
        						$no_rangka 	= $value->NO_RANGKA;
        						$tahun 		= $value->THN_PERAKITAN;
        					}
        				}
        			}
        		?>
        		<table class="table table-striped">
        			<tr>
    					<td class="col-md-2">Kode Item</td>
    					<td>: <b><?php echo $kd_item;?></b></td>
    				</tr>
    				<tr>
    					<td>Nama Item</td>
    					<td>: <b><?php echo $nama_item;?></b></td>
    				</tr>
    				<tr>
    					<td>Tahun Produksi</td>
    					<td>: <b><?php echo $tahun;?></b></td>
    				</tr>
    				<tr>
    					<td>No. Rangka - No Mesin</td>
    					<td>: <b><?php echo $no_rangka ." - ". $no_mesin;?></b></td>
    				</tr>
        		</table>
        		<div class="table-responvive">
        			<table class="table table-bordered table-striped">
        				<thead>
        					<tr>
        						<th class='text-center'>No Transaksi</th>
        						<th class='text-center'>Tanggal</th>
        						<th class='text-center'>Jenis Transaksi</th>
        						<th class='text-center'>Jumlah</th>
        						<th class='text-center'>Lokasi</th>
        						<th class='text-center'>Keterangan</th>
        					</tr>
        				</thead>
        				<tbody>
        					<?php
        					$total=0;
        					 if($receive){
        					 	if($receive->totaldata>0){
        					 		foreach ($receive->message as $key => $value) {
        					 			echo "<tr>
        					 				  <td class='text-center'>".$value->NO_TRANS."</td>
        					 				  <td class='text-center'>".tglFromSql($value->TGL_TRANS)."</td>
        					 				  <td>".$value->KETERANGAN."</td>
        					 				  <td class='text-center jml'>".number_format($value->JUMLAH,0)."</td>
        					 				  <td>".$value->KD_GUDANG."</td>
        					 				  <td></td>
        					 				  </tr>";
                                        switch(substr($value->NO_TRANS,0,2)){
                                            case 'RM':$total +=$value->JUMLAH;break;
                                            case 'MU': if($value->KETERANGAN=='Mutasi Antar Dealer'){
                                                $total -=$value->JUMLAH;
                                            }
                                            break;
                                            case 'PK': $total -=$value->JUMLAH; break;
                                        }
        					 			
        					 		}
        					 	}else{
        					 		echo belumAdaData(5);
        					 	}
        					 }else{
        					 	echo  belumAdaData(5);
        					 }
        					 
        					?>
        				</tbody>
        				<tfoot>
        					<tr class="success">
	        						<td colspan="3" class='text-right padding-left-10'><em><b>Stock Akhir</b></em></td>
	        						<td class='text-center'><em><b><?php echo $total;?></b></em></td>
	        						<td colspan="2">&nbsp;</td>
	        					</tr>

        				</tfoot>
        			</table>
        		</div>
        	</div>
        </div>
    </div>
</section>
