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
            <a href="<?php echo base_url("pengeluaran/add_pengeluaran");?>" role="button" class="btn btn-default">
                <i class="fa fa-file"></i> Add Delivery Unit
            </a>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                <i class="fa fa-list-ul fa-fw"></i> List Delivery Unit
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: none;">
                <form id="filterForm" action="<?php echo base_url('pengeluaran/pengeluaran') ?>" class="bucket-form" method="get">
                    <div id="ajax-url" url="<?php echo base_url('pengeluaran/sjkeluar_typeahead'); ?>"></div>
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
                            <input type="text" id="keyword" name="keyword" class="form-control" value="<?php echo $this->input->get('keyword'); ?>" placeholder="cari berdasarkan nomor surat,nama pengirim,no mobil,nama sopir,nama penerima"  autocomplete="off">
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
                            <th>Aksi</th>
                            <th>No Surat</th>
                            <th>Tgl SJ</th>
                            <th>Kode Customer</th>
                            <th>Nama Penerima</th>
                            <th>Alamat Kirim</th>
                            <th>Nama Sopir</th>
                            <th>Tgl Terima</th>
                            <th>Status SJ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($list)){
                            if (($list->totaldata >0)){
                                $no = $this->input->get('page');
                                foreach ($list->message as $row){
                                    $no ++;
                                    $redeliver = ($row->STATUS_SJ == 'process' || $this->session->userdata('nama_group') == 'Root' ? $status_e:'disabled-action');
                                    $nosj = urlencode(base64_encode($row->NO_SURATJALAN));
                                    ?> 
                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' ';?>" >
                                        <td class="table-nowarp"><?php echo  $no; ?></td>
                                        <td class="table-nowarp">
                                            <a class="active <?php echo $status_e;?>" href="<?php echo base_url('pengeluaran/add_pengeluaran?n='.$nosj);?>"><i class="fa fa-edit"></i></a>

                                            <a class="active <?php echo $status_p?>" id="modal-button" onclick='addForm("<?php echo base_url('pengeluaran/sj_keluar/'.$row->NO_SURATJALAN); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                                                <i class='fa fa-print' data-toggle="tooltip" data-placement="left" title="Print SJ Keluar" ></i>
                                            </a>
                                            
                                            <?php if($row->TYPE_PENJUALAN == 'CREDIT'): ?>
                                            <a class="<?php echo $status_p?>" href="<?php echo base_url('pengeluaran/penyerahan_bpkb/'.$row->NO_SURATJALAN); ?>" target="_blank">
                                                <i class='fa fa-file' data-toggle="tooltip" data-placement="left" title="Penyerahan BPKB" ></i>
                                            </a>
                                            <?php endif;?>
                                            
                                            <a class="<?php echo $status_p?>" href="<?php echo base_url('pengeluaran/surat_kuasa/'.$row->NO_SURATJALAN); ?>" target="_blank" hidden>
                                                <i class='fa fa-file' data-toggle="tooltip" data-placement="left" title="Surat Kuasa" ></i>
                                            </a>

                                            <a href="<?php echo base_url('pengeluaran/cetak_barcode/'.$row->NO_SURATJALAN); ?>" target="_blank" class="<?php echo $status_p?>">
                                              <i data-toggle="tooltip" data-placement="left" title="Cetak Barcode" class="fa fa-barcode text-warning text"></i>
                                            </a>

                                            <a href="<?php echo base_url('pengeluaran/cetak_barcode2/'.$row->NO_SURATJALAN); ?>" target="_blank" class="<?php echo $status_p?>">
                                              <i data-toggle="tooltip" data-placement="left" title="Cetak Barcode 2" class="fa fa-barcode text-warning text"></i>
                                            </a>

                                            <a id="modal-button" class="active <?php echo $redeliver?>" onclick='addForm("<?php echo base_url('pengeluaran/delivery_unit/'.$row->NO_SURATJALAN); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                                                <i class="fa fa-truck fa-fw" data-toggle="tooltip" data-placement="left" title="Delivery Unit" ></i>
                                            </a>
                                            <?php if($row->STATUS_SJ != 'process'):?>
                                            <a href="<?php echo base_url($row->BUKTI_TERIMA);?>" target="blank">
                                                <i class="fa fa-list-alt fa-fw" data-toggle="tooltip" data-placement="left" title="Bukti Terima" ></i>
                                            </a>
                                            <?php endif;?>
                                            <?php $status_sj = ($row->STATUS_SJ != 'process'? 'disabled-action':$status_e);?>
                                            <a id="delete-btn<?php echo $no;?>" class="delete-btn <?php echo $status_sj?>" url="<?php echo base_url('pengeluaran/delete_sjkeluar/'.$row->NO_SURATJALAN); ?>">
                                              <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                                            </a>
                                        </td>
                                        <td class='table-nowarp'><?php echo $row->NO_SURATJALAN;?></td>
                                        <td class="table-nowarp"><?php echo TglFromSql($row->TGL_SURATJALAN);?></td>
                                        <td class="table-nowarp"><?php echo $row->KD_CUSTOMER;?></td>
                                        <td class="td-overflow" title="<?php echo $row->NAMA_PENERIMA;?>"><?php echo str_replace("\'","'",$row->NAMA_PENERIMA);?></td>
                                        <td class="td-overflow-50" title="<?php echo $row->ALAMAT_KIRIM;?>"><?php echo $row->ALAMAT_KIRIM;?></td>
                                        <td class="table-nowarp"><?php echo $row->NAMA_SOPIR;?></td>
                                        <td class="table-nowarp"><?php echo(substr($row->TGL_TERIMA,0,4)=='1900')?'': TglFromSql($row->TGL_TERIMA);?></td>
                                        <td class="table-nowarp"><?php echo $row->STATUS_SJ;?></td>
                                    </tr>
                                    <?php
                                }
                            }else{
                            ?>
                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="17"><b><?php echo ($list->message); ?></b></td>
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