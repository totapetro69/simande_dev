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
  $dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('First day of previous month'));
  $sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y",strtotime('first day of next month'));
?>
<section class="wrapper">


    <div class="breadcrumb margin-bottom-10">
        <div id="bc1" class="myBreadcrumb">
            <a href="javascript:void(0);"><i class="fa fa-home fa-2x"></i></a>
            <a href="javascript:void(0);"><div>Sales</div></a>
            <a href="javascript:void(0);" class="active"><div>SPK</div></a>
            <a href="javascript:void(0);" class="active"><div>List SPK</div></a>
        </div>

        <div class="bar-nav pull-right ">

            <a id="modal-button" class="btn btn-default <?php echo $status_c;?>" href="<?php echo base_url('spk/add_spk'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Tambah SPK Baru
            </a>
        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"> <i class="fa fa-list-ul"></i> List Surat Pesanan Kendaraan
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="filterForm" action="<?php echo base_url('spk/spk') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('spk/spk_typeahead'); ?>"></div>

                    <div class="row">

                        <div class="col-xs-12 col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nama Dealer</label>
                                    <select class="form-control " id="kd_dealer" name="kd_dealer" <?php echo $status_n;?>>
                                        <option value="">--Pilih Dealer--</option>
                                        <?php
                                        if ($dealer) {
                                            if (is_array($dealer->message)) {
                                                foreach ($dealer->message as $key => $value) {
                                                    $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                    $aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
                                                    echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                                }
                                            }
                                        }
                                        ?> 
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Dari Tanggal</label>
                                        <div class="input-group input-append date" id="date">
                                            <input class="form-control" id="dari_tanggal" name="dari_tanggal" value="<?php echo $dari_tanggal;?>">
                                            <span></span><span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Sampai Tanggal</label>
                                        <div class="input-group input-append date" id="date">
                                            <input class="form-control" id="sampai_tanggal" name="sampai_tanggal" value="<?php echo $sampai_tanggal;?>">
                                            <span></span><span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Cari SPK</label>
                                    <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan nomor spk atau nama customer" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <?php //print_r($this->session->userdata());?>
                                <br>
                                    <button type="submit" class="btn btn-default pull-right"> Preview</button>
                                
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
                            <th>Aksi</th>
                            <th>Nomor SPK</th>
                            <th>Tgl SPK</th>
                            <th>Nama Customer</th>
                            <th>Type Motor</th>
                            <th>Tipe Penjualan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if ($list):
                            if ($list->totaldata >0):
                                foreach ($list->message as $key => $row):
                                    $no ++;
                                    ?>

                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp">
                                            <a href="<?php echo base_url('spk/add_spk?id=' . $row->ID); ?>" role="button" class="<?php echo $status_v;?>">
                                                <i data-toggle="tooltip" data-placement="left" title="View detail / edit" class="fa fa-edit text-success text-active"></i>
                                            </a>
                                            <?php 
                                                if($row->LEASINGID > 0 ){
                                                    if($row->KD_FINCOY !='CSH'){
                                            ?>
                                            <a href="<?php echo base_url('spk/add_spk?tab=2&l=1&id='. $row->ID); ?>" role="button" class="<?php echo $status_e;?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Approve Leasing <?php echo $row->KD_FINCOY;?>" class="fa fa-cog text-success"></i>
                                            </a>
                                            <?php } 
                                                }
                                                if((int)$row->STATUS_SPK==0) {?>
                                            <a id="delete-btn<?php echo $no; ?>" class="delete-btn <?php echo $status_e;?>" url="<?php echo base_url('spk/delete_spk/' . $row->NO_SPK); ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                                            </a>
                                            <?php }?>
                                        </td>
                                        <td class="table-nowarp"><?php echo $row->NO_SPK; ?></td>
                                        <td class="table-nowarp"><?php echo tglFromSql($row->TGL_SPK); ?></td>
                                        <td class="table-nowarp"><?php echo str_replace("\'","'",$row->NAMA_CUSTOMER); ?></td>
                                        <td class="table-nowarp" title="<?php echo ($row->KD_TYPEMOTOR)?'Harga OTR :'.number_format(($row->HARGA_OTR+$row->DISKON),0).',Subsidi :'.number_format($row->DISKON,0):'';?>"><?php echo($row->KD_TYPEMOTOR)? $row->KD_TYPEMOTOR ."-".$row->KD_WARNA :""; ?></td>
                                        <td class="table-nowarp"><?php echo $row->TYPE_PENJUALAN; ?>
                                            <?php if($row->TYPE_PENJUALAN=="CREDIT" && (int)$row->STATUS_SPK >=0){
                                                echo " by :".$row->KD_FINCOY." [".$row->HASIL."]";
                                            }else{
                                                if((int)$row->STATUS_SPK <0){
                                                    echo "- <b>Di Batalkan</b>";
                                                }
                                            }   
                                            ?>
                                        </td>
                                        <td class="table-nowarp"><?php echo status_spk($row->STATUS_SPK);?></td>
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
                            echo belumAdaData(8);
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