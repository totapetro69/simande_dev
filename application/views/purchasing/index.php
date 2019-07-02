<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defTahun = ($this->input->get("thn"))?$this->input->get("thn"):date("Y");
$defBulan = ($this->input->get("bln"))?$this->input->get("bln"):date("m");
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
        <div class="bar-nav pull-right">  
         <a class="btn btn-default <?php echo $status_c;?>" href="<?php echo base_url('purchasing/add_po'); ?>" role="button">
            <i class="fa fa-file-o fa-fw"></i> Input PO</a>
            <a class="btn btn-default hidden" href="#" role="button">
                <i class="fa fa-download fa-fw"></i> Download
            </a>
        </div>
	</div>
	<div class="col-lg-12 padding-left-right-10">
		<div class="panel margin-bottom-10">
        	<div class="panel-heading">
                <i class="fa fa-search"></i> Purchase Order
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: ;">
            	<form id="filterForm" action="<?php echo base_url('purchasing/PO_list') ?>" class="bucket-form" method="get">
            		<div id="ajax-url" url="<?php echo base_url('purchasing/po_typeahead'); ?>"></div>
                    <div class="row">
                        <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class='form-group'>
                                <label>Periode Bulan</label>
                                <select class="form-control" name="bln" id="bln">
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
                                    <option value="">--Pilih Tahun--</option>
                                    <?php
                                        if(isset($tahun)){
                                            if($tahun->totaldata >0){
                                                foreach ($tahun->message as $key => $value) {
                                                    $pilih=($defTahun==$value->TAHUN_KIRIM)?'selected':'';
                                                    echo "<option value='".$value->TAHUN_KIRIM."' ".$pilih.">".$value->TAHUN_KIRIM."</option>";
                                                }
                                            }else{
                                                echo "<option value='".$defTahun."' selected>".$defTahun."</option>";
                                            }
                                        }else{
                                            echo "<option value='".$defTahun."' selected>".$defTahun."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                    		<div class="form-group">
                                <label>Field Cari</label>
                                <input type="text" id="keyword" autocomplete="off" name="keyword" class="form-control" placeholder="Nomor PO" >
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
                            <th></th>
                            <th>No.PO</th>
                            <th>Tanggal PO</th>
							<th>Tanggal Selesai PO</th>
                            <th>Jenis PO</th>
                            <th>Kode Dealer</th>
                            <th>Nama Dealer</th>
                            <th>Bulan/Tahun</th>
                            <!-- <th>Periode</th> -->
                            <th>Status PO</th>
                            <th>Keterangan</th>
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
                                   
                                //define status po
                                $status="";
                                switch ($row->STATUS_PO) {
                                    case 0: $status="Draft"; break;
                                    case 1: 
                                        $status=($row->APPROVAL_PO)?"Submitted":"Returned by MD";break;
                                    case 2: $status="Processed By MD"; break;
                                    case 3: $status="Completed";break;
                                    case -1: $status="Rejected By MD"; break;
                                    default:
                                        $status="Draft";
                                        break;
                                }
                        ?>
                        <tr>
                            <td class="table-nowarp"><?php echo ($i+1); ?></td>
                            <td class="table-nowarp">
                                <?php 
                                        $hiden=($row->STATUS_PO>0)?'':'hidden';
                                        $hiden= (isBolehAkses('p'))?$hiden:'hidden';
                                        $hidens=($row->STATUS_PO>=0)?'':'hidden';
                                        $hidens= (isBolehAkses('c'))?$hidens:'hidden';
                                    ?>
                                <a id="modal-button" class="<?php echo $hiden;?>" title="Print PO"  onclick='addForm("<?php echo base_url('purchasing/cetak_po?n='.urlencode(base64_encode($row->NO_PO)).''); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                                    <i class="fa fa-print text-primary text-active"></i>
                                </a>
                                <a class="active <?php echo $hidens;?>"   href="<?php echo base_url('purchasing/add_po?n='.urlencode(base64_encode($row->NO_PO)).''); ?>" role="button">
                                <?php if($status=='Draft'||$status =='Returned by MD'):?>
                                    <i class="fa fa-edit text-success text-active" title="Edit PO"></i>
                                <?php else: ?>
                                    <i class="fa fa-file-text text-active" title="Lihat detail PO"></i>
                                <?php endif ?>
                                </a>
                                    <?php 
                                        $hiden=($row->STATUS_PO<2)?'':'hidden';
                                        $hiden= (isBolehAkses('e'))?$hiden:'hidden';
                                    ?>
                                <a id='x_<?php echo $row->ID;?>' class='delete-btn <?php echo $hiden;?>' url='<?php echo base_url('purchasing/po_delete?n='.urlencode(base64_encode($row->NO_PO)).'');?>'>
                                  <i data-toggle="tooltip" data-placement="left" title="Hapus PO" class="fa fa-trash text-danger text"></i>
                               </a></td>
                            <td class="table-nowarp"><?php echo $row->NO_PO;?></td>
                            <td class="table-nowarp"><?php echo tglFromSql($row->TGL_PO);?></td>
                            <td class="table-nowarp"><?php 
							if($row->STATUS_PO < 3){
								echo "-";
							} else {
								echo tglFromSql($row->TGL_SELESAI_PO);
							}
							?></td>
                            <td class="table-nowarp"><?php echo $row->KD_JENISPO;?></td>
                            <td class="table-nowarp"><?php echo $row->KD_DEALER;?></td>
                            <td class="table-nowarp"><?php echo $row->NAMA_DEALER;?></td>
                            <td class="table-nowarp"><?php echo $row->BULAN_KIRIM."/".$row->TAHUN_KIRIM;?></td>
                            <!-- <td class="table-nowarp"><?php echo ($row->JENIS_PO!='F')?$row->PERIODE_PO:"1 (".tglFromSql($row->TGL_AWALPO)." sd ".tglFromSql($row->TGL_AKHIRPO).")";?></td> -->
                            <td class="table-nowarp"><?php echo $status;?></td>
                            <td class="table-nowarp"><?php echo $row->APPROVAL_POBY;?></td>
                            
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
</section>