<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$bukanCabang=($this->session->userdata("status_cabang")!='Y')?"":"hidden";
?>

<section class="wrapper">


    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">
            <?php 
                $url= ($this->input->get('kd_dealer'))? 
                base_url()."dealer/add_salesman?kd_dealer=".$this->input->get('kd_dealer'): base_url('dealer/add_salesman');
            ?>
            <a id="modal-button" class="btn btn-primary <?php echo $status_c; ?>" onclick='addForm("<?php echo $url;?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-download"></i> Update Data
            </a>
            <!-- <a onclick='addForm("<?php echo base_url('dealer/edit_salesman/');?>")' class="btn btn-default <?php echo $bukanCabang;?>" role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-file-o"></i> Add Salesman Baru</a> -->
        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                <i class='fa fa-list-ul'></i> Salesman 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: none;">
                <form id="filterForm" action="<?php echo base_url('dealer/salesman') ?>" class="bucket-form" method="get">
                    <div id="ajax-url" url="<?php echo base_url('dealer/salesman_typeahead'); ?>"></div>
                    <div class="row">
                        <div class="col-xs-3 col-sm-3">
                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select class="form-control" id="kd_dealer" name="kd_dealer">
                                    <option value="">--Pilih Dealer--</option>
                                    <?php
                                        if($dealer){
                                            if(is_array($dealer->message)){
                                                foreach ($dealer->message as $key => $value) {
                                                    $select=($this->session->userdata('kd_dealer')==$value->KD_DEALER)?"selected":"";
                                                    $select=($this->input->get("kd_dealer")==$value->KD_DEALER)?"selected":$select;
                                                    echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-5 col-sm-5">
                            <div class="form-group">
                                <label>Kode atau Nama Salesman</label>
                                 <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan kode atau nama Salesman" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select id="row_status" name="row_status" class="form-control">
                                    <option value="A" <?php echo ($this->input->get('row_status') == "A" ? "selected" : ""); ?>>Aktif</option>
                                    <option value="X" <?php echo ($this->input->get('row_status') == "X" ? "selected" : ""); ?>>Tidak Aktif</option>
                                    <option value="" <?php echo ($this->input->get('row_status') == "" ? "selected" : ""); ?>>Semua</option>
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
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th>&nbsp;</th>
                            <th>Kode Salesman</th>
                            <th>Kode Honda</th>
                            <th>NIK</th>
                            <th>Nama Salesman</th>
                            <th>Dealer</th>
							<th>Group Sales</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if ($list):
                            if (is_array($list->message) || is_object($list->message)):
                                foreach ($list->message as $key => $row):
                                    $no ++;
                                    ?>
                                    <tr id="<?php echo  $this->session->flashdata('tr-active') == $row->KD_SALES ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo  $no; ?></td>
                                        <td class="text-center table-nowarp">
                                            <a onclick='addForm("<?php echo base_url('dealer/edit_salesman/' . $row->KD_SALES.'/'.$row->KD_DEALER); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-edit"></i></a>
                                        </td>
                                        <td class="table-nowarp"><?php echo $row->KD_SALES; ?></td>
                                        <td class="table-nowarp"><?php echo $row->KD_HSALES; ?></td>
                                        <td class="table-nowarp"><?php echo $row->NIK; ?></td>
                                        <td class="table-nowarp"><?php echo $row->NAMA_SALES; ?></td>
                                        <td class="table-nowarp"><?php echo $row->NAMA_DEALER; ?></td>
										<td class="table-nowarp"><?php echo $row->GROUP_SALES; ?></td>
                                        <td class="table-nowarp"><?php echo $row->KD_JABATAN ." - ".$row->PERSONAL_JABATAN; ?></td>
                                        <td class="table-nowarp"><?php echo $row->STATUS_SALES; ?></td>
                                    </tr>

                                    <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="10"><b><?php echo ($list->message); ?></b></td>
                                </tr>
                            <?php
                            endif;
                        else:
                            echo belumAdaData(10);
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total Data " . $list->totaldata . " items</i>") : '' ?>
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
<script type="text/javascript">
    $(document).ready(function(){
        $('#nmd').html($('#kd_dealer option:selected').text())
    })
</script>