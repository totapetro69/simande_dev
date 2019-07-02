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
	<div class="breadcrumb margin-bottom-10">
     <?php echo breadcrumb();?>
 </div>
    
	<div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                <i class="fa fa-search"></i> HISTORY HARGA
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('inventori/history_harga') ?>" class="bucket-form" method="get">

                    <!-- <div id="ajax-url" url="<?php echo base_url('umsl/suratjalan_typeahead'); ?>"></div> -->

                    <div class="form-group">
                        <label>Field Cari</label>
                        <input type="text" id="keyword" name="keyword" class="form-control" autocomplete="false" placeholder="Cari Berdasarkan Kode Item, Nama Item, Nama Wilayah" >
                    </div>

                </form>

            </div>

        </div>

    </div>
    <div class="col-lg-12 padding-left-right-10">
    	<div class="panel panel-default">
    		<div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th></th>
                            <th colspan="2">Kode Item</th>
							<th colspan="7">Nama Item</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th></th>
							<th>Aksi</th>
                            <th>Wilayah</th>
                            <th>Tanggal</th>
                            <th>Harga Customer</th>
							<th>Harga Dealer</th>
                            <th>Harga Beli</th>
                            <th>BBN</th>
							<th>Harga OTR</th>
							<th>Kategori</th>
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
                                    <td class="text-bold"><?php echo $no; ?></td>
                                    <td class="table-nowarp"></td>
                                    <td class="table-nowarp" colspan="2"><?php echo $row->KD_ITEM;?></td>
                                    <td class="table-nowarp" colspan="7"><?php echo $row->NAMA_ITEM;?></td>
                                </tr>
                                <?php
                                    if($listd){
                                        if(is_array($listd[$i])){
                                            for($x=0;$x < count($listd[$i][$row->KD_ITEM]);$x++){
                                                ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo ($listd[$i][$row->KD_ITEM][$x]["STATUS_HARGA"]==1)? "<i class='fa fa-info text-success' title='harga yng berlaku saat ini'></i>":"";?></td>
                                                        <td class='text-right'><?php echo ($x+1);?></td>
														<td class="table-nowarp">
															<a id="modal-button" onclick='addForm("<?php echo base_url('inventori/detail_history_harga/'.$row->ID); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
															  <i data-toggle="tooltip" data-placement="left" title="Detail" class="fa fa-file text-success text-active"></i>
															</a>
														</td>
                                                        <td class='text-center'><?php echo $listd[$i][$row->KD_ITEM][$x]["ALIAS_WILAYAH"];?></td>
                                                        <td class='text-center'><?php echo tglFromSql($listd[$i][$row->KD_ITEM][$x]["TGL_UPDATE"]);?></td>
                                                        <td class='text-right'><?php echo number_format($listd[$i][$row->KD_ITEM][$x]["HARGA_DEALER"]);?></td>
														<td class='text-right'><?php echo number_format($listd[$i][$row->KD_ITEM][$x]["HARGA"]);?></td>
                                                        <td class='text-right'><?php echo number_format($listd[$i][$row->KD_ITEM][$x]["HARGA_DEALERD"]);?></td>
                                                        <td class='text-right'><?php echo number_format($listd[$i][$row->KD_ITEM][$x]["BBN"]);?></td>
                                                        <td class='text-right'><?php echo number_format($listd[$i][$row->KD_ITEM][$x]["HARGA_OTR"]);?></td>
                                                        <td><?php echo $listd[$i][$row->KD_ITEM][$x]["KD_CATEGORY"];?></td>
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
                        if($listh){ ?>
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