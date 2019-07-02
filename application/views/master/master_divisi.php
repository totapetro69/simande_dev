<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        <div class="bar-nav pull-right ">
            <a id="modal-button" class="btn btn-default  <?php echo $status_c?> disabled-action" onclick='addForm("<?php echo base_url('company/add_divisi'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
              <i class="fa fa-file-o fa-fw"></i> Add Divisi Baru
            </a>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class="fa fa-list-ol"></i> Divisi
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: none;">
                <form id="filterForm" action="<?php echo base_url('company/divisi') ?>" class="bucket-form" method="get">
                    <div id="ajax-url" url="<?php echo base_url('company/divisi_typeahead');?>"></div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <div class="form-group">
                                <label>Kode atau Nama Divisi</label>
                                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan Kode atau Nama Divisi" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 hidden">
                            <div class="form-group">
                                <label>Status</label>
                                <select id="row_status" name="row_status" class="form-control">
                                    <option value="0" <?php echo ($this->input->get('row_status') == 0 ? "selected" : "");?>>Aktif</option>
                                    <option value="-1" <?php echo ($this->input->get('row_status') == -1 ? "selected" : "");?>>Tidak Aktif</option>
                                    <option value="-2" <?php echo ($this->input->get('row_status') == -2 ? "selected" : "");?>>Semua</option>
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
            <div class="table-responsive h350">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width:5%;">No.</th>
                            <th style="width:5%;">Aksi</th>
                            <th style="width:10%">Kode Divisi</th>
                            <th style="width:40%">Nama Divisi</th>
                            <th style="width:10%">Status</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = $this->input->get('page');
                    if(isset($list)){
                        if($list->totaldata >0){
                            foreach($list->message as $key=>$row){
                                $no ++;
                        ?>
                            <tr id="<?php echo $this->session->flashdata('tr-active') == $row->KD_DIVISI ? 'tr-active' : ' ';?>" >
                                <td><?php echo $no;?></td>
                                <td class="table-nowarp">&nbsp;</td>
                                <td class="table-nowarp"><?php echo $row->KD_DIVISI;?></td>
                                <td class="table-nowarp"><?php echo $row->NAMA_DIV;?></td>
                                <td class="table-nowarp"><?php echo $row->ROW_STATUS == 0 ? 'Aktif':'Tidak Aktif';?></td>
                                <td>&nbsp;</td>
                            </tr>
                        <?php 
                            }
                        }else{
                        ?>
                        <tr>
                            <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                            <td colspan="8"><b><?php echo ($list->message); ?></b></td>
                        </tr>
                    <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo ($list)? ($list->totaldata==''?"":"<i>Total Data ". $list->totaldata ." items</i>") : '' ?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs"> <?php echo $pagination;?></div>
                </div>
            </footer>
        </div>
    </div>
</section>