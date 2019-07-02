<section class="wrapper">
 
 
    <div class="breadcrumb margin-bottom-10">
        <div id="bc1" class="myBreadcrumb">
            <a href="javascript:void(0);"><i class="fa fa-home fa-2x"></i></a>
            <!-- <div>...</div> -->
            <a href="javascript:void(0);"><div>Perusahaan</div></a>
            <a href="javascript:void(0);" class="active"><div>Kecamatan</div></a>
        </div>
 
        <div class="bar-nav pull-right ">  
            <a id="modal-button" class="btn btn-primary" onclick='addForm("<?php echo base_url('company/add_kecamatan'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-download"></i> Update Data
            </a>
        </div>
    </div>
 
 
    <div class="col-lg-12 padding-left-right-10">
 
        <div class="panel margin-bottom-10">
 
            <div class="panel-heading">
                 <i class='fa fa-list-ul fa-fw'></i> List Kecamatan
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
 
            <div class="panel-body panel-body-border" style="display: none;">
 
                <form id="filterForm" action="<?php echo base_url('company/kecamatan') ?>" class="bucket-form" method="get">
 
                    <div id="ajax-url" url="<?php echo base_url('company/kecamatan_typeahead'); ?>"></div>
 
                    <div class="form-group">
                        <label>Field Pencarian</label>
                        <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan Kode kecamatan atau Nama kecamatan" autocomplete="off">
                    </div>
 
                </form>
 
            </div>
 
        </div>
 
    </div>
 
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel panel-default">
            <!-- <div class="panel-heading">
              Responsive Table
            </div> -->
 
            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th style="width:45px;">No.</th>
                            <th style="width:45px;">Aksi</th>
                            <th>Kode Kabupaten</th>
                            <th>Kode Kecamatan</th>
                            <th>Nama Kecamatan</th>
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
                                        <a class="active" id="modal-button" ui-toggle-class="" onclick='addForm("<?php echo base_url('company/edit_kecamatan?kd_kecamatan=' . $row->KD_KECAMATAN . ''); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                                            <i class="fa fa-edit text-success text-active"></i>
                                        </a>
                                        <a class="hidden">
                                            <i class="fa fa-trash text-danger text"></i>
                                        </a>
                                    </td>
                                    <td class="table-nowarp"><?php echo $row->KD_KABUPATEN; ?></td>
                                    <td class="table-nowarp"><?php echo $row->KD_KECAMATAN; ?></td>
                                    <td class="table-nowarp"><?php echo $row->NAMA_KECAMATAN; ?></td>
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