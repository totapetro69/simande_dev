 <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
  
  $status_c = (isBolehAkses('c') ? '' : 'remove-button' ); 
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
  ?>

<section class="wrapper">
	<div class="breadcrumb">
		<?php echo breadcrumb();?>

        <div class="bar-nav pull-right ">
         <a id="modal-button" class="btn btn-warning <?php echo $status_c?>" onclick='addForm("<?php echo base_url('inventori/add_sjkeluar'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-plus"></i> tambah
            </a>
        </div>
	</div>
    
	<div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                <i class="fa fa-search"></i> surat jalan keluar
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('inventori/sjkeluar') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('inventori/sjkeluar_typeahead'); ?>"></div>

                    <div class="form-group">
                        <label>Field Cari</label>
                        <input type="text" id="keyword" name="keyword" class="form-control" placeholder="cari berdasarkan nomor surat,nama pengirim,no mobil,nama sopir,nama penerima" >
                    </div>

            </div>

        </div>

    </div>
    <div class="col-lg-12 padding-left-right-10">
    	<div class="panel panel-default">
    		<div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th style="width:45px;">No</th>
                            <th style="width:45px;">Aksi</th>
                            <th>No Surat</th>
                            <th>Tanggal Surat Jalan</th>
                            <th>Kode Main Dealer</th>
                            <th>Kode Dealer</th>
                            <th>Kode Gudang</th>
                            <th>No Reff</th>
                            <th>Kode Customer</th>
                            <th>Alamat Pengiriman</th>
                            <th>Tanggal Kirim</th>
                            <th>Nama Pengirim</th>
                            <th>No Mobil</th>
                            <th>Nama Sopir</th>
                            <th>Nama Penerima</th>
                            <th>Tgl Terima</th>
                            <th>Status_sj</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php
                        if (is_array($list->message)) {

                            //for($i=0;$i < count($list["message"]);$i++) 
                            $no = $this->input->get('page');
                            foreach ($list->message as $row) {
                                $no ++;
                                # code...
                                $i = 0;
                                ?> 
                                <tr>
                                    <td><?php echo  $no; ?></td>
                                    <td class="table-nowarp">
                                        
                                        <a class="active <?php echo $status_e?>" ui-toggle-class="" onclick='addForm("<?php echo base_url('inventori/edit_sjkeluar/' . $row->NO_SURATJALAN . ''); ?>");' role="button" data-toggle="modal" data-target="#myModalLg">
                                            <i class="fa fa-edit text-success text-active"></i>
                                        </a>
                                        &nbsp;
                                        <a class="active <?php echo $status_e?>" ui-toggle-class="" onclick='addForm("<?php echo base_url('inventori/du'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg">
                                            <i class="fa fa-upload"></i>
                                        </a>
                                        &nbsp;
                                        <a class="active <?php echo $status_e?>" ui-toggle-class="" onclick='addForm("<?php echo base_url('inventori/du'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg">
                                            <i class="fa fa-external-link fa-fw"></i>
                                        </a>
                                        &nbsp;
                                        <a class="active <?php echo $status_e?>" ui-toggle-class="" onclick='addForm("<?php echo base_url('inventori/co'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg">
                                            <i class="fa fa-shopping-cart fa-fw"></i>
                                        </a>
                                        
                                    </td>
                                    <td><?php echo $row->NO_SURATJALAN;?></td>
                                    <td><?php echo $row->TGL_SURATJALAN;?></td>
                                    <td><?php echo $row->KD_MAINDEALER;?></td>
                                    <td><?php echo $row->KD_DEALER;?></td>
                                    <td><?php echo $row->KD_GUDANG;?></td>
                                    <td><?php echo $row->NO_REFF;?></td>
                                    <td><?php echo $row->KD_CUSTOMER;?></td>
                                    <td><?php echo $row->ALAMAT_KIRIM;?></td>
                                    <td><?php echo $row->TGL_KIRIM;?></td>
                                    <td><?php echo $row->NAMA_PENGIRIM;?></td>
                                    <td><?php echo $row->NO_MOBIL;?></td>
                                    <td><?php echo $row->NAMA_SOPIR;?></td>
                                    <td><?php echo $row->NAMA_PENERIMA;?></td>
                                    <td><?php echo $row->TGL_TERIMA;?></td>
                                    <td><?php echo $row->STATUS_SJ;?></td>
                                    <td><?php echo $row->KETERANGAN;?></td>
                                </tr>
                                <?php
                                $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                        <td colspan="11"><b><?php echo ($list->message); ?></b></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                    </tbody>
                </table>
            </div>
            <footer class="panel-footer">
                <div class="row">

                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo ($list->totaldata == '') ? "" : "<i>Total Data " . $list->totaldata . " items</i>" ?>
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