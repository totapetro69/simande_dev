<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
  
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('first day of this month'));
$sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y");
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
		<div class="bar-nav pull-right">
			<a id="modal-button" class="btn btn-default" onclick='addForm("<?php echo base_url('part/mutasipart_add'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-file-o"></i> Input Mutasi
            </a>
		</div>

    </div>

    <div class="col-lg-12 padding-left-right-5">
        <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-list-ul fa-fw"></i> List Mutasi Unit
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
        
	        <div class="panel-body panel-body-border panel-body-10" style="display: block;">
	        	<form id="filterForm" method="GET" action="<?php echo base_url("part/mutasipart_list");?>">
	        		<div class="row">
		        		<div class="col-xs-6 col-md-2 col-sm-2">
		        			<div class="form-group">
			        			<label>Nama Dealer</label>
		                        <select class="form-control" id="kd_dealer" name="kd_dealer">
		                            <option value="">--Pilih Dealer--</option>
		                            <?php
		                            if (isset($dealer)) {
		                                if ($dealer->totaldata>0) {
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
                        <div class="col-xs-8 col-md-4 col-sm-4">
                            <div class="form-group">
                                <label>Search Criteria</label>

                                <div id="ajax-url" url="<?php echo base_url('part/moving_typeahead/PART');?>"></div>

                                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="find by No. Part ,No Trans" autocomplete="off">

                            </div>
                        </div>
		                <div class="col-xs-6 col-md-3 col-sm-3">
		                	<div class="form-group">
			                	<label>Periode dari Tanggal</label>
			                	<div class="input-group input-append date" id="date">
			                        <input class="form-control" id="dari_tanggal" name="dari_tanggal" value="<?php echo $dari_tanggal;?>">
			                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
			                </div>
		                </div>
		                <div class="col-xs-6 col-md-3 col-sm-3">
		                	<div class="form-group">
			                	<label>Sampai Tanggal</label>
			                	<div class="input-group input-append date" id="date">
			                        <input class="form-control" id="sampai_tanggal" name="sampai_tanggal" value="<?php echo $sampai_tanggal;?>">
			                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
			                </div>
		                </div>
		            </div><!-- 
		            <div class="row">
		            	<div class="col-xs-4 col-sm-1 col-md-1">
		                	<label style="color: white">Preview</label>
		                	<button class="btn btn-info" type="submit"> <i class="fa fa-search"></i> Preview</button>
		                </div>
		            </div> -->
	        	</form>
	        </div>
	    </div>
    </div>
    <div class="col-lg-12 padding-left-right-5">
    	<div class="panel panel-default">
    		<div class="table-responsive">
    			<table class="table table-hover table-striped table-bordered">
    				<thead>
                        <tr>
                            <th rowspan="2">No</th>
    						<th rowspan="2">Aksi</th>
    						<th colspan="4">No. Trans</th>
    						<th colspan="5">Tanggal</th>
                        </tr>
                        <tr>
    						<th>No. Part</th>
    						<th>Keterangan</th>
    						<th>Jenis Mutasi</th>
    						<th>Lokasi Asal</th>
                            <th>Rakbin Asal</th>
                            <th>Lokasi Tujuan</th>
                            <th>Rakbin Tujuan</th>
                            <th>Jumlah</th>
    						<th>Status</th>
    					</tr>
    				</thead>
    				<tbody>
    					<?php 
                            $n = $this->input->get('page');
    						if(isset($header)):
    							if($header->totaldata>0):
                                    foreach ($header->message as $key => $headervalue):
                                    $n++;
                        ?>
                                        <tr class="info bold">
                                            <td><?php echo $n;?></td>
                                            <td>
                                                <a class="active <?php echo $status_p?>" id="modal-button" onclick='addForm("<?php echo base_url('part/print_slip?no_trans='.$headervalue->NO_TRANS); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                                                    <i class='fa fa-print' data-toggle="tooltip" data-placement="left" title="Print surat pengantar" ></i>
                                                </a>
                                            </td>
                                            <td colspan="4"><?php echo $headervalue->NO_TRANS;?></td>
                                            <td class='text-center' colspan="5"><?php echo tglFromSql($headervalue->TGL_TRANS);?></td>
                                        </tr>
                        <?php
        								foreach ($list->message as $key => $value):
                                            if($value->NO_TRANS == $headervalue->NO_TRANS):
        									?>
        										<tr>
        											<td colspan="2"></td>
        											<td class='text-center'><?php echo $value->PART_NUMBER;?></td>
        											<td><?php echo $value->KETERANGAN;?></td>
        											<td><?php echo $value->JENIS_TRANS;?></td>
        											<td><?php echo $value->KD_GUDANG_ASAL;?></td>
                                                    <td><?php echo $value->RAKBIN_ASAL;?></td>
                                                    <td><?php echo $value->KD_GUDANG_TUJUAN;?></td>
                                                    <td><?php echo $value->RAKBIN_TUJUAN;?></td>
                                                    <td><?php echo $value->JUMLAH;?></td>
        											<td><?php echo $value->STATUS_MUTASI;?></td>
        										</tr>
        									<?php
                                            endif;
                                        endforeach;

    								endforeach;
                                else:
    								belumAdaData(11);
    							endif;
    						else:
    							belumAdaData(11);
    						endif;
    					?>	
    				</tbody>
    				<tfoot>
    					
    				</tfoot>
    			</table>
    		</div>
    		<footer class="panel-footer">
                <div class="row">
                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo (isset($totaldata)) ? ($totaldata == '0' ? "" : "<i>Total Data " . $totaldata . " items</i>") : '' ?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo isset($pagination)?$pagination:""; ?>
                    </div>
                </div>
            </footer>
    	</div>
    </div>
    <?php echo loading_proses();?>
</section>