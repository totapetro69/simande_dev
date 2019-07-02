<?php
  if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }
 
  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $status_n = ($this->session->userdata("nama_group")=="Root")?"":"disabled='disabled'";
 
  $defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");

  $tahun=($this->input->get("tahun"))?$this->input->get("tahun"):date("Y");
  $bulan=($this->input->get("bulan"))?$this->input->get("bulan"):date("m");
  $jenisRetur = ($this->input->get("jenis_retur")) ? $this->input->get("jenis_retur") : ""; 
  
  /*$dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('First day of previous month'));
  $sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y",strtotime('first day of next month'));*/
?>
<section class="wrapper">
     <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right ">
            <a id="modal-button" class="btn btn-default <?php echo $status_c;?>" href="<?php echo base_url('retur/add_jualbeli'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Add Retur
            </a>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                List Retur Penjualan & Pembelian
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
                <form id="filterForm" action="<?php echo base_url('retur/jualbeli') ?>" class="bucket-form" method="get">
                    <div id="ajax-url" url="<?php //echo base_url('retur/jualbeli_typeahead'); ?>"></div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select name="kd_dealer" class="form-control">
                                    <option value="0">--Pilih Dealer--</option>
                                    <?php
                                    if ($dealer) {
                                        if (is_array($dealer->message)) {
                                            foreach ($dealer->message as $key => $value) {
                                                $select = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Jenis Retur</label>
                                <select class="form-control" id="jenis_retur" name="jenis_retur">
                                    <option value="">--Pilih Jenis Retur--</option>
                                    <option value='Pembelian'<?php echo ($jenisRetur == 'Pembelian') ? " selected" : ""; ?>>Pembelian</option>
                                    <option value='Penjualan'<?php echo ($jenisRetur == 'Penjualan') ? " selected" : ""; ?>>Penjualan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-2 col-sm-2">
                            <div class="form-group">
                                <label>Periode Bulan</label>
                                <select id="bulan" name="bulan" class="form-control">
                                    <option value="">--Pilih Bulan--</option>
                                    <?php 
                                        for($i=1;$i<=12; $i++){
                                            $pilih=($bulan==$i)?"selected":"";
                                            echo "<option value='".$i."' ".$pilih.">".nBulan($i)."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-2 col-sm-2">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select id="tahun" name="tahun" class="form-control">
                                    <option value="">--Pilih Tahun--</option>
                                    <?php 
                                        if(isset($tahun)){
                                            if($tahun->totaldata>0){
                                                foreach ($tahun->message as $key => $value) {
                                                    $pilih=(date("Y")==$value->TAHUN)?"selected":"";
                                                    $pilih=($this->input->get("tahun")==$value->TAHUN)?"selected":$pilih;
                                                    echo "<option value='".$value->TAHUN."' $pilih>".$value->TAHUN."</option>";
                                                }
                                            }else{
                                                echo "<option value='".date("Y")."' selected>".date("Y")."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 hidden">
                            <div class="form-group">
                                <label>Cari</label>
                                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan Nomor Transaksi atau Jenis Retur" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-md-2 hidden">
                            <div class="form-group">
                                <br>
                                <button id="submit-btn" onclick="addData();" class="btn btn-primary pull-right"><i class='fa fa-search'></i> Preview</button>
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
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>&nbsp;</th>
                            <th>Dealer</th>
                            <th>Lokasi Dealer</th>
                            <th>No.Transaksi</th>
                            <th>Tanggal</th>
                            <th>Jenis Retur</th>
                            <th>No. Reff</th>
                            <th>Tanggal Reff</th>
                            <th>Keterangan</th>
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
 
                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp">
                                            <a id="modal-button" href="<?php echo base_url('retur/add_jualbeli?n='.$row->NO_TRANS); ?>");' role="button" class="<?php echo $status_v?>">
                                                <i data-toggle="tooltip" data-placement="left" title="edit" class="fa fa-edit text-success text-active"></i>
                                          </a>
                                        </td>
                                        <td class="table-nowarp"><?php echo NamaDealer($row->KD_DEALER); ?></td>
                                        <td class="table-nowarp"><?php echo $row->KD_LOKASIDEALER; ?></td>
                                        <td class="table-nowarp"><?php echo $row->NO_TRANS; ?></td>
                                        <td class="table-nowarp"><?php echo tglfromSql($row->TGL_TRANS);?></td>
                                        <td class="table-nowarp"><?php echo $row->JENIS_RETUR; ?></td>
                                        <td class="table-nowarp"><?php echo $row->NO_REFF; ?></td>
                                        <td class="table-nowarp"><?php echo tglfromSql($row->TGL_REFF); ?></td>
                                        <td class="table-nowarp"><?php echo $row->KETERANGAN; ?></td>
                                    </tr>
 
                                   
                                <?php 
                              endforeach;
                            else:
                              ?>
                              <tr>
                                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                <td colspan="8"><b><?php echo ($list->message); ?></b></td>
                              </tr>
                              <?php
                            endif;
                          else:
                            ?>
                            <tr>
                              <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                              <td colspan="8"><b>ada error, harap hubungi bagian IT</b></td>
                            </tr>
                            <?php
                          endif;
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
                        <div class="col-sm-7 text-right text-center-xs">                
                         <?php echo $pagination;?>
                    </div>
                </div>
            </footer>

        </div>
    </div>
</section> 