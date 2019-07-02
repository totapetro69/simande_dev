<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        <div class="bar-nav pull-right ">  
         <a id="modal-button" class="btn btn-warning" onclick='addForm("<?php echo base_url('Purchasing/add_sj'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-plus"></i> tambah
            </a>
            
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                <i class="fa fa-search"></i> Surat
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('purchasing/suratjalan') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('purchasing/suratjalan_typeahead'); ?>"></div>

                    <div class="form-group">
                        <label>Field Cari</label>
                        <input type="text" id="keyword" name="keyword" class="form-control" placeholder="cari berdasarkan kode nomor surat jalan" >
                    </div>

                </form>

            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">
        <form class="bucket-form" method="get">
            <div class="row">
            	<CENTER><b>SURAT JALAN</b></CENTER>
            	<br>
				 <div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<table width="90%" cellspacing="3" cellpadding="2">
                        	
                        	<tr>
                        		<td><label>NO SURAT JALAN</label></td>
                        		<td>:</td>
                             	<td>001/08/23</td>
                            </tr>
                            <tr>
                            	<td><label>TANGGAL SHIPPING</label></td>
                            	<td>:</td>
                            	<td>23/10/2017</td>
                            </tr>
                            <tr>
                            	<td><label>KODE DEALER</label></td>
                            	<td>:</td>
                            	<td>T10</td>
                            </tr>
    
                        </table>
                    </div>
				</div>

                <div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<table width="60%" cellspacing="3" cellpadding="2">
                        	<tr>
                        		<td><label>KODE CABANG MOTOR</label></td>
                             	<td>:</td>
                             	<td>PO-T10</td>
                            </tr>
                            
                    	</table>
                    </div>
				</div>
			</div>
		</form>
	</div>

    <div class="col-lg-12 padding-left-right-10">
		<div class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th style="width:45px;">No</th>
                            <th style="width:45px;">Aksi</th>
                            <th>Tanggal Shipping</th>
                            <th>Kode Dealer</th>
                            <th>Kode Cabang Dealer</th>
                            <th>Kode Tipe Motor</th>
                            <th>Kode Warna</th>
                            <th>No Rangka</th>
                            <th>No Mesin</th>
                            <th>Tahun Perakitan</th>
                            <th>No Referensi</th>
                            <th>Expedisi</th>
                            <th>No Pol Truk</th>
                            <th>No Faktur</th>
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
                                        <a class="active" ui-toggle-class="" onclick='addForm("<?php echo base_url('setup/edit_sj?no_surat_jalan=' . $row->NO_SURAT_JALAN . ''); ?>");' role="button" data-toggle="modal" data-target="#myModalLg">
                                            <i class="fa fa-edit text-success text-active"></i>
                                        </a>
                                    </td>
                            <td>23/10/2017</td>
                            <td>T10</td>
                            <td>PO-T10</td>
                            <td>4g4</td>
                            <td>2</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
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