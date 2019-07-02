<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defTahun = ($this->input->get("thn"))?$this->input->get("thn"):date("Y");
$defBulan = ($this->input->get("bln"))?$this->input->get("bln"):date("m");
$defaultDealer = ($this->input->get("kd_dealer"));
$defApproval = ($this->input->get("APPROVAL"));

?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
        
	</div>
	<div class="col-lg-12 padding-left-right-10">
		<div class="panel margin-bottom-10">
        	<div class="panel-heading">
                <i class="fa fa-search"></i> List Monitoring Order NRFS
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: ;">
            	<form id="filterForm" action="<?php echo base_url('umsl/monitoring_nrfs') ?>" class="bucket-form" method="get">
            		<div id="ajax-url" url="<?php echo base_url('purchasing/po_typeahead'); ?>"></div>
                    <div class="row">
						<div class="col-xs-4 col-sm-3 col-md-3">
							<div class="form-group">
								<label>Dealer</label>
								<select name="kd_dealer" id="kd_dealer" class="form-control">
								  <option value="">- ALL -</option>
								  <?php foreach ($dealer->message as $key => $group) { 
										$pilih =($defaultDealer == $group->KD_DEALER)?' selected':'';
									?>
									<option value="<?php echo $group->KD_DEALER;?>" <?php echo $pilih;?>><?php echo $group->NAMA_DEALER;?></option>
								  <?php 
									} ?>
								</select>
							</div>
						</div> 	
                        <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class='form-group'>
                                <label>Periode Bulan</label>
                                <select class="form-control" name="bln" id="bln">
                                    <option value="">- Pilih Bulan -</option>
                                    <?php 
                                        for($n=1;$n<13; $n++){
                                            $pilih=($defBulan==$n)?'selected':'';
                                            echo "<option value='".$n."' ".$pilih.">".nBulan($n)."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-2 col-sm-2">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select class="form-control" name="thn" id="thn">
                                    <option value="">- Pilih Tahun -</option>
                                    <?php
                                        foreach ($tahun->message as $key => $value) {                                            
                                            echo "<option value='".$value->TAHUN."' ".$pilih.">".$value->TAHUN."</option>";
                                        }                                            
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                    		<div class="form-group">
                                <label>Field Cari</label>
                                <input type="text" id="keyword" autocomplete="off" name="keyword" class="form-control" placeholder="Nomor PO" >
                            </div>
                        </div>
                    </div>
					<div class="row">
						<div class="col-xs-4 col-sm-3 col-md-3">
							<div class="form-group">
								<label>Jenis PO</label>
								<input type="text" value="NRFS" name="jenis_po" readonly="" class="form-control">
							</div>
						</div> 	
                        <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class='form-group'>
                                <label>Approval PO</label>
                                <select class="form-control" name="APPROVAL" id="APPROVAL">
									<option value="">- All -</option>								  
									<option value="-1" <?= $defApproval == -1 ? 'selected' : '' ; ?> >Rejected</option>
									<option value="nol" <?= $defApproval == 'nol' ? 'selected' : '' ; ?> >Draft</option>
									<option value="1" <?= $defApproval == 1 ? 'selected' : '' ; ?> >Submitted</option>	
									<option value="3" <?= $defApproval == 3 ? 'selected' : '' ; ?> >Closed</option>
                                    <option value="2" <?= $defApproval == 2 ? 'selected' : '' ; ?> >Canceled</option>
									
                                </select>
                            </div>
                        </div>                        
                    </div>
                </form>
            </div>
        </div>       
    </div>
    <div class="col-lg-12 padding-left-right-10">
    	<div class="panel panel-default">
    		<div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No.PO</th>
                            <th>PO Periode</th>
							<th>Unit Qty.</th>
                            <th>Tipe PO</th>
                            <th>Tanggal PO</th>
                            <th>Status PO</th>                            

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        if($list===NULL){ ?>
                        <tr>
                            <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                            <td colspan="11"><b><i class='fa fa-exclamation fa-fw'></i> Ada masalah koneksi. Silahkan hubungi IT</b></td>
                        </tr>
                        <?php goto end;
                        } 
                            $i=0;
                            if(is_array($list->message)){                                

                                foreach ($list->message as $row) {                                    
                                   
                                $draft     = "";
                                $submitted = "";
                                $closed    = "";
                                $canceled  = "";
                                $rejected  = "";

                                if ($row->ROW_STATUS == 0) {                                                                    

                                    if ($row->APPROVAL == 0) {
                                    
                                        $draft = "badge-primary";

                                    } else if ($row->APPROVAL == 1) {
                                    
                                        $submitted = "badge-primary";

                                    } else if ($row->APPROVAL == 3) {
                                    
                                        $closed = "badge-primary";

                                    } else if ($row->APPROVAL == -1) {
                                                                        
                                        $rejected = "badge-danger";

                                    }

                                } else {

                                    $canceled = "badge-danger";

                                }

                               ?>

                        <tr>
                            <td class="table-nowarp"><?php echo ($i+1); ?></td>
                            <td class="table-nowarp"><?php echo $row->NO_PO;?></td>
                            <td class="table-nowarp"><?php echo date("F, Y", strtotime($row->TGL_PO));?></td>
                            <td class="table-nowarp"><?php echo $row->QTY;?></td>
                            <td class="table-nowarp"><?php echo $row->JENIS_PO;?></td>                        
                            <td class="table-nowarp"><?php echo date("d-m-Y", strtotime($row->TGL_PO));?></td>
                            <td class="table-nowarp">
                                <span class="badge <?php echo $draft ?>">DRAFT</span>
                                <span class="badge <?php echo $submitted ?>">SUBMITTED</span>                                
                                <span class="badge <?php echo $closed ?>">CLOSED</span>
                                <span class="badge <?php echo $canceled ?>">CANCELED</span>
                                <span class="badge <?php echo $rejected ?>">REJECTED</span>
                            </td>
                            
                        </tr>

                        <?php

                        $i++;

                                 }

                            }else{


                        ?>
                          <tr>
                            <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                            <td colspan="9"><b><?php echo ($list->message);?></b></td>
                        </tr>
                        <?php 
                        end:
                         }
                         ?>
                    </tbody>
                </table>
            </div>
            <footer class="panel-footer">
                <div class="row">

                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 

                            <?php echo ($list!=NULL)? ($list->totaldata=='')?"":"<i>Total Data ". $list->totaldata ." items</i>":""?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                         <?php echo $pagination;?>
                    </div>
                </div>
            </footer>
        </div>
    </div>
<script type="text/javascript">
	
	$(document).ready(function(e){

		var approval = getParameterByName('APPROVAL');
		$("select[name='APPROVAL']").val(approval);
        var bulan = getParameterByName('bln');
        $("select[name='bln']").val(bulan);
        var tahun = getParameterByName('thn');
        $("select[name='thn']").val(tahun);		
		var keyword = getParameterByName('keyword');
		if(keyword!=''){
			$("select[name='APPROVAL']").val('')
			$("select[name='bln']").val('')
			$("select[name='thn']").val('')			
		}
		
	});
	
	function getParameterByName(name) {
		var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
		return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
	}
</script>
</section>