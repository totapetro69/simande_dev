<section class="wrapper">
	<div class="breadcrumb">
		<div id="bc1" class="myBreadcrumb">
            <a href="javascript:void(0);"><i class="fa fa-home fa-2x"></i></a>
            <!-- <div>...</div> -->
            <a href="javascript:void(0);"><div>General</div></a>
            <a href="javascript:void(0);" class="active"><div>List Master Penomoran</div></a>
        </div>
        <div class="bar-nav pull-right ">  
         <a id="modal-button" class="btn btn-warning" onclick='addForm("<?php echo base_url('setup/add_data'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-plus"></i> tambah
            </a>
            
        </div>
	</div>
    
	<div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                <i class="fa fa-search"></i> List Penomoran Dokumen
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('setup/setup_docno') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('setup/setup_docno_typeahead'); ?>"></div>

                    <div class="form-group">
                        <label>Field Cari</label>
                        <input type="text" id="keyword" name="keyword" class="form-control" placeholder="cari berdasarkan kode nomor dokumen, nama nomor dokumen atau kode dealer" >
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
                            <th style="width:45px;">No.</th>
                            <th style="width:45px;">Aksi</th>
                            <th>Kode Nomor Dokumen</th>
                            <th>Nama Nomor Dokumen</th>
                            <th>Kode Dealer</th>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Urutan</th>
                            <th>Reset</th>
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
                                    <td><?php echo $no; ?></td>
                                    <td class="table-nowarp">
                                        <a class="active" ui-toggle-class="" onclick='addForm("<?php echo base_url('setup/edit_data?kd_docno=' . $row->KD_DOCNO . ''); ?>");' role="button" data-toggle="modal" data-target="#myModalLg">
                                            <i class="fa fa-edit text-success text-active"></i>
                                        </a>
                                    </td>
                                    <td><?php echo $row->KD_DOCNO;?></td>
                                    <td><?php echo $row->NAMA_DOCNO;?></td>
                                    <td><?php echo $row->KD_DEALER;?></td>
                                    <td><?php echo $row->TAHUN_DOCNO;?></td>
                                    <td><?php echo $row->BULAN_DOCNO;?></td>
                                    <td>
                                        <?php
                                          for ($i= 1; $i <= 1000; $i++) 
                                             {
                                                echo "<li>$i</li>";
                                             }
                                          ?>    
                                    </td>
                                    <td><?php echo $row->RESET_DOCNO;?></td>
                            
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