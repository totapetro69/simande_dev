<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
//$defaultDealer = ($this->session->userdata("kd_dealer"));
//$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right ">
            <div class="btn-group">
                <a class="btn btn-default <?php echo $status_c; ?>"  role="button" href='<?php echo base_url('part/add_picking_part'); ?>' >
                    <i class="fa fa-file-o fa-fw"></i> Input Picking Part
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                Picking Part
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: none;">
              <form id="filterForm" action="<?php echo base_url('part/picking_part') ?>" class="bucket-form" method="get">
                <div id="ajax-url" url="<?php echo base_url('part/picking_part_typeahead'); ?>"></div>
                  <div class="row">
                    <div class="col-xs-12 col-sm-8">
                      <div class="form-group">
                        <label>Picking Part</label>
                        <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan No. Trans atau Nama Konsumen" autocomplete="off">
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                      <div class="form-group">
                        <label>Status</label>
                        <select id="row_status" name="row_status" class="form-control">
                          <option value="0" <?php echo ($this->input->get('row_status') == 0 ? "selected" : ""); ?>>Aktif</option>
                          <option value="-1" <?php echo ($this->input->get('row_status') == -1 ? "selected" : ""); ?>>Tidak Aktif</option>
                          <option value="-2" <?php echo ($this->input->get('row_status') == -2 ? "selected" : ""); ?>>Semua</option>
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
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width:40px;">No.</th>
                            <th rowspan="2" style="width:45px;">Aksi</th>
                            <th colspan="1">No. Trans</th>
                            <th colspan="1">No. Referensi</th>
                            <th colspan="2">Tanggal Transaksi</th>
                        </tr>
                        <tr>
                            <th>Part Number</th>
                            <!-- <th>Deskripsi</th> -->
                            <th>Jumlah</th>
                            <!-- <th>Total Harga</th>
                            <th>Harga Jual</th> -->
                            <th>Rakbin</th>
                            <th>Gudang</th>
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
                                    <tr  class="info bold">
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp">
                                            <a class="active <?php echo $status_p?>" id="modal-button" onclick='addForm("<?php echo base_url('part/cetak_picking_part?u='.$row->NO_TRANS); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                                                <i class='fa fa-print' data-toggle="tooltip" data-placement="left" title="Print Picking Part" ></i>
                                            </a>
                                        </td>
                                        <td colspan="1"><?php echo $row->NO_TRANS; ?></td>
                                        <td colspan="1"><?php echo $row->NO_REFF; ?></td>
                                        <td colspan="2"><?php echo tglfromSql($row->TGL_TRANS); ?></td>
                                    </tr>
                          <?php
                            if(isset($detail)){
                              if($detail->totaldata >0){

                              foreach ($detail->message as $key => $row_detail){
                              if($row_detail->NO_TRANS == $row->NO_TRANS){
                            ?>
                                    <tr  id="<?php echo $this->session->flashdata('tr-active') == $row_detail->ID ? 'tr-active' : ' '; ?>" >
                                        <td>&nbsp;</td>
                                        <td class="table-nowarp text-right">
                                            <?php
                                            if ($row_detail->PICKING_REFF <= 1) {
                                                ?>
                                                <a id="delete-btn<?php echo $no; ?>" class="delete-btn" url="<?php echo base_url('part/delete_part_pickint_detail/' .$row_detail->ID.'/'.$row_detail->JENIS_REFF.'/'.$row_detail->ID_REFF); ?>">
                                                    <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $row_detail->PART_NUMBER; ?></td>
                                        <td><?php echo $row_detail->JUMLAH; ?></td>
                                        <td class="text-right hidden"><?php echo number_format($row_detail->PRICE,0); ?></td>
                                        <td class="text-right hidden"><?php echo number_format($row_detail->HARGA_JUAL,0); ?></td>
                                        <td><?php echo $row_detail->KD_RAKBIN; ?></td>
                                        <td><?php echo $row_detail->KD_GUDANG; ?></td>
                                    </tr>
                          <?php
                                  }
                                }
                              }
                            }
                            endforeach;
                            else:
                          ?>
                          <tr>
                            <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                            <td colspan="40"><b><?php echo ($list->message); ?></b></td>
                          </tr>
                            <?php
                            endif;
                              else:
                                echo belumAdaData(40);
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