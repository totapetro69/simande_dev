<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>
<section class="wrapper">
	<div class="breadcrumb  margin-bottom-10">
		<?php echo breadcrumb();?>
        <div class="bar-nav pull-right ">  
         <a id="modal-button" class="btn btn-default <?php echo $status_c;?>" onclick='addForm("<?php echo base_url('umsl/addsjf'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-bookmark-o"></i> Update UMSL
            </a>
            
        </div>
	</div>
    
	<div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                <i class="fa fa-search"></i> UNIT MOTOR SHIPING LIST
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('umsl/listsj') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('umsl/suratjalan_typeahead'); ?>"></div>

                    <div class="form-group">
                        <label>Field Cari</label>
                        <input type="text" autocomplete="false" id="keyword" name="keyword" class="form-control" placeholder="cari berdasarkan nomor surat" >
                    </div>

                </form>

            </div>

        </div>

    </div>
    <div class="col-lg-12 padding-left-right-10">
    	<div class="panel panel-default">
    		<div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th></th>
                            <th>Tanggal</th>
                            <th>KD Dealer</th>
                            <th>No.SuratJalan</th>
                            <th>No.DO</th>
                            <th>No.Faktur</th>
                            <th>No.PO</th>
                            <th>No.POMD</th>
                            <th>Nama Tagihan</th>
                            <th>Keterangan</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th></th>
                            <th>KodeItem</th>
                            <th colspan="3">Nama Item</th>
                            <th>No.Rangka</th>
                            <th>No.Mesin</th>
                            <th colspan="3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php
                        if($listh){
                            if (is_array($listh->message)) {
                            $no = $this->input->get('page');
                             $i = 0;
                            foreach ($listh->message as $row) {
                                $no ++;
                                # code...
                               
                                ?> 
                                <tr class="info bold">
                                    <td class="text-bold"><?php echo  $no; ?></td>
                                    <td class="table-nowarp">
                                        <a class="active hidden" ui-toggle-class="" onclick='addForm("<?php echo base_url('umsl/edit_sj?no_umsl=' . $row->NO_SJMASUK . ''); ?>");' role="button" data-toggle="modal" data-target="#myModalLg">
                                            <i class="fa fa-edit text-success text-active"></i>
                                        </a>
                                        <a class="active hidden" ui-toggle-class="" onclick='addForm("<?php echo base_url('umsl/cetak_suratjalan'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg">
                                            <i class="fa fa-print fa-fw"></i>
                                        </a>
                                    </td>
                                    <td class="table-nowarp"><?php echo TglFromSql($row->TGL_SJMASUK);?></td>
                                    <td class="table-nowarp"><?php echo $row->KD_DEALER;?></td>
                                    <td class="table-nowarp"><?php echo $row->NO_SJMASUK;?></td>
                                    <td class="table-nowarp"><?php echo $row->NO_REFF;?></td>
                                    <td class="table-nowarp"><?php echo $row->NO_FAKTUR;?></td>
                                    <td class="table-nowarp"><?php echo $row->NO_PO;?></td>
                                    <td class="table-nowarp"><?php echo $row->NO_POMD;?></td>
                                    <td class="table-nowarp"><?php echo $row->NAMA_TAGIHAN?></td>
                                    <td class="table-nowarp"><?php echo $row->EXPEDISI ." ".$row->NOPOL;?></td>
                                </tr>
                                <?php
                                    if($listd){
                                        if(is_array($listd[$i])){
                                            for($x=0;$x < count($listd[$i][$row->NO_SJMASUK]);$x++){
                                                ?>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td class='text-right'><?php echo ($x+1);?></td>
                                                        <td><?php echo $listd[$i][$row->NO_SJMASUK][$x]["KD_ITEM"];?></td>
                                                        <td colspan="3"><?php echo $listd[$i][$row->NO_SJMASUK][$x]["NAMA_ITEM"];?></td>
                                                        <td><?php echo $listd[$i][$row->NO_SJMASUK][$x]["NO_RANGKA"];?></td>
                                                        <td><?php echo $listd[$i][$row->NO_SJMASUK][$x]["NO_MESIN"];?></td>
                                                        <td><?php echo $listd[$i][$row->NO_SJMASUK][$x]["STATUS_SJ"];?></td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>

                                                    </tr>
                                                <?php
                                            }

                                        }
                                    }
                                $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                        <td colspan="11"><b><?php echo ($listh->message); ?></b></td>
                                    </tr>
                                    <?php
                                }
                            }else{ 
                                belumAdaData(11);
                            }
                                ?>
                    </tbody>
                </table>
            </div>
           
            <footer class="panel-footer">
                <div class="row">
                     <?php 
                        if($list){ ?>
                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo ($listh->totaldata == '') ? "" : "<i>Total Data " . $listh->totaldata . " items</i>" ?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo $pagination; ?>
                    </div>
                     <?php } ?>
                </div>
            </footer>
           
        </div>
    </div>
</section>