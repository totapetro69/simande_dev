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
            <!-- <a href="<?php echo base_url("pengeluaran/add_pengeluaran");?>" role="button" class="btn btn-default">
                <i class="fa fa-file"></i> Add Indent
            </a> -->
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                <i class="fa fa-list-ul fa-fw"></i> List Indent
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: none;">
                <form id="filterForm" action="<?php echo base_url('sales_order/list_indent') ?>" class="bucket-form" method="get">
                    <div id="ajax-url" url="<?php echo base_url('sales_order/indent_typeahead'); ?>"></div>
                    <div class="row">
                      <div class="col-xs-12 col-sm-2">
                        <div class="form-group">
                            <label>Dealer</label>
                            <select name="kd_dealer" id="kd_dealer" class="form-control" required="true">
                             
                                <?php
                                if (isset($dealer)) {
                                    if ($dealer->totaldata > 0) {
                                        foreach ($dealer->message as $key => $value) {
                                            $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                                            $select = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $select;
                                            echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                                        }
                                    }
                                }
                                ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <label>Field Cari</label>
                            <input type="text" id="keyword" name="keyword" class="form-control" value="<?php echo $this->input->get('keyword'); ?>" placeholder="cari berdasarkan nomor SPK,kode customer,kode item"  autocomplete="off">
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-3">
                        <div class="form-group">
                          <label class="control-label" for="date">Periode Awal</label>
                          <div class="input-group input-append date">
                              <input class="form-control" id="tgl_awal" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tgl_awal')?$this->input->get('tgl_awal'):date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-3">
                        <div class="form-group">
                          <label class="control-label" for="date">Periode Akhir</label>
                          <div class="input-group input-append date">
                              <input class="form-control" id="tgl_akhir" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tgl_akhir')?$this->input->get('tgl_akhir'):date('d/m/Y'); ?>" type="text"/>
                              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                          </div>
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
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Indent</th>
                            <th>No SPK</th>
                            <th>Kode Item</th>
                            <th>Deskripsi</th>
                            <th>Nama Customer</th>
                            <th>KTP</th>
                            <th>No HP</th>
                            <th>Jenis Pembayaran</th>
                            <th>Sales People</th>
                            <th>Status Indent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (isset($list) && ($list->totaldata >0)){
                                $no = $this->input->get('page');
                                foreach ($list->message as $row){
                                    $no ++;
                                    switch ($row->STATUS_INDENT) {
                                        case 1:
                                            $status = 'PO Created';
                                            break;
                                        case 2:
                                            $status = 'Unit Ready';
                                            break;
                                        case 3:
                                            $status = 'Close';
                                            break;
                                        default:
                                            $status = 'Requested';
                                            break;
                                    }
                                    ?> 
                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' ';?>" >
                                        <td class="table-nowarp"><?php echo  $no; ?></td>
                                        <td class='table-nowarp'><?php echo $row->NO_TRANS;?></td>
                                        <td class='table-nowarp'><?php echo $row->NO_SPK;?></td>
                                        <td class="table-nowarp"><?php echo $row->KD_ITEM;?></td>
                                        <td class="table-nowarp"><?php echo $row->NAMA_ITEM;?></td>
                                        <td class="table-nowarp"><?php echo $row->NAMA_CUSTOMER;?></td>
                                        <td class="table-nowarp"><?php echo $row->NO_KTP;?></td>
                                        <td class="table-nowarp"><?php echo $row->NO_HP;?></td>
                                        <td class="table-nowarp"><?php echo $row->TYPE_PENJUALAN;?></td>
                                        <td class="table-nowarp"><?php echo $row->NAMA_SALES;?></td>
                                        <td class="table-nowarp"><?php echo $status;?></td>
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