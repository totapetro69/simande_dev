<?php
    if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $mode="disabled-action";
  $no_trans=(($this->input->get("n")));
  $dari_tgl =($this->input->get("dtgl"))?$this->input->get("dtgl"):date("d/m/Y",strtotime("-5 Days"));
  $smp_tgl =($this->input->get("stgl"))?$this->input->get("stgl"):date("d/m/Y");
  $sosts =($this->input->get("sosts")!='')?$this->input->get("sosts"):"";
?>

<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>

        <div class="bar-nav pull-right ">
            <a id="modal-button" class="btn btn-default" href="<?php echo base_url('cashier/addsop'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> SO Baru
            </a>
            <!-- <a id="modal-button-1" class="btn btn-default <?php echo $mode;?>" href="<?php echo base_url('cashier/seleksi_lkh'); ?>" role="button">
                <i class="fa fa-list-alt fa-fw"></i> Seleksi Transaksi
            </a>
            <a id="modal-button-1" class="btn btn-default" href="<?php echo base_url('cashier/laporan_lkh'); ?>" role="button">
                <i class="fa fa-list-alt fa-fw"></i> Laporan Kas Harian
            </a> -->
            <a id="modal-button-1" class="btn btn-default disabled-action" href="<?php echo base_url('cashier/listsop'); ?>" role="button">
                <i class="fa fa-list-ul fa-fw"></i> List Sales Order
            </a>
        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class='fa fa-list'></i> List Sales Order
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
                <form method="get" action="<?php echo base_url("cashier/listsop");?>" id="frmKsr">
                    <div class="col-xs-12 col-md-3 col-sm-3">
                        <div class="form-group">
                        <label>Dealer</label>
                            <select id="kd_dealer" name="kd_dealer" class="form-control">
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
                    <div class="col-xs-12 col-md-5 col-sm-5">
                        <div class="col-xs-12 col-md-6 col-sm-6 no-margin-l">
                            <div class="form-group">
                                <label>Periode Tanggal</label>
                                <div class="input-group input-append date">
                                    <input type="text" id="dtgl" name="dtgl" class="form-control" value="<?php echo $dari_tgl;?>">
                                     <span class="input-group-addon"><i class='glyphicon glyphicon-calendar'></i></span>
                                 </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 col-sm-6 no-margin-r">
                            <div class="form-group">
                                <label>Sampai Dengan Tanggal</label>
                                <div class="input-group input-append date">
                                    <input type="text" id="stgl" name="stgl" class="form-control" value="<?php echo $smp_tgl;?>">
                                     <span class="input-group-addon"><i class='glyphicon glyphicon-calendar'></i></span>
                                 </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2 col-sm-2">
                        <label>SO Status</label>
                        <select class="form-control" id="sosts" name="sosts">
                            <option value="" <?php echo ($sosts=='')?"selected":"";?>>All</option>
                            <option value="0" <?php echo ($sosts=='0')?"selected":"";?>>Open</option>
                            <option value="1" <?php echo ($sosts=='1')?"selected":"";?>>Picking</option>
                            <option value="2" <?php echo ($sosts=='2')?"selected":"";?>>Di Bayar</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top:20px"><i class="fa fa-search"></i> Preview</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="table-resposive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No Transaksi</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Nama Customer</th>
                            <th colspan="5">Tipe SO</th>
                        </tr>
                        <tr>
                            <th>No</th>
                            <th>Part Number</th>
                            <th colspan="3">Deskripsi</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Diskon</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($list){ $tipe_so="";$booking_ke="";
                                if($list->totaldata>0){ $n=0;
                                    foreach ($list->message as $key => $value) {
                                        $n++;
                                        $booking_ke="";
                                        $tipe_so=($value->BOOKING_ORDER=="1")?"BOOKING":"";
                                        $booking_ke=($value->ORDER_TO=='T10')?"MAIN DEALER":$value->ORDER_TO;
                                        //$booking_ke=($value->ORDER_TO=='' && $value->BOOKING_ORDER=="1")?"AHM":;
                                        $sudahPO=($value->REFF_DOC!='' || $value->SO_STATUS >0)?'disabled-action':"";
                                        ?>
                                        <tr class='warning'>
                                            <td class="text-center"><?php echo $n;?><span id="<?php echo $value->NO_TRANS;?>" class="hidden"><i class="fa fa-spinner fa-spin"></i></td>
                                            <td class="text-left table-nowarp">
                                                <span class="pull-left" style="margin-right: 10px">
                                                    <a class="" href="<?php echo base_url('cashier/addsop?n=').urlencode(base64_encode($value->NO_TRANS));?>" title="edit data"><i class="fa fa-edit"></i></a>
                                                    <a class="<?php echo ($sosts!=0)?'disabled-action':'';?><?php echo $sudahPO;?> " onclick="__hapusH('<?php echo $value->NO_TRANS;?>');" title='Hapus transaksi'><i class="fa fa-trash"></i></a>
                                                </span>
                                                <?php echo $value->NO_TRANS;?></td>
                                            <td class="text-center table-nowarp"><?php echo tglFromSql($value->TGL_TRANS);?></td>
                                            <td class="text-center table-nowarp"><?php echo ($tipe_so=='')? $value->TYPE_CUSTOMER : $value->KD_CUS;?></td>
                                            <td class="table-nowarp"><?php echo $value->NAMA_CUSTOMER;?></td>
                                            <!-- <td colspan="2" class="text-center"><?php  ?></td> -->
                                            <td colspan="5" class="table-nowarp"><?php echo $tipe_so." ".$booking_ke;?>&nbsp;&nbsp;
                                                <?php echo ($value->BOOKING_ORDER=="1")? "<i class='fa fa-arrow-right'></i> No. PO Hotline : <a href=".base_url('purchasing/posp_add?n=').urlencode(base64_encode($value->REFF_DOC)).">".$value->REFF_DOC."</a>":"";?></td>
                                            <!-- <td><?php echo (isset($listd->totaldata)>0)?"":"Open";?></td> -->
                                        </tr>
                                        <?php
                                        if(isset($listd)){
                                            if($listd->totaldata>0){ $x=0; 
                                                foreach ($listd->message as $key => $val) {
                                                    if($val->NO_TRANS==$value->NO_TRANS){
                                                        $x++;
                                                        $status="Open";
                                                        $status =($val->PICKING_STATUS==1)?"Picking":$status;
                                                        $status =($val->BILL_REFF!='' && $val->PICKING_STATUS==2)?"Di Bayar":$status;
                                                        ?>
                                                        <tr >
                                                           <td class="text-right"><?php echo $x;?><span id="<?php echo $val->ID;?>" class="hidden"><i class="fa fa-spinner fa-spin"></i></td>
                                                            <td class="text-right"><span class="pull-left">
                                                                <a class="<?php echo $sudahPO;?>" onclick="__hapusItem('<?php echo $val->ID;?>');" title='hapus item'><i class="fa fa-trash"></i></a></span>
                                                                <?php echo $val->PART_NUMBER;?></td>
                                                            <td colspan="3"><?php echo $val->PART_DESKRIPSI;?></td>
                                                            <td class="text-right"><?php echo number_format($val->JUMLAH_ORDER,0);?></td>
                                                            <td class="text-right"><?php echo number_format($val->HARGA_JUAL,0);?></td>
                                                            <td class="text-right"><?php echo number_format($val->DISKON,0);?></td>
                                                            <td class="text-right"><?php echo number_format(($val->JUMLAH_ORDER * $val->HARGA_JUAL)- $val->DISKON,0)?></td>
                                                            <td class="text-center"><?php echo $status;?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel-footer">
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
        </div>
</section>
<script type="text/javascript">
    var path = window.location.pathname.split('/');
    var http = window.location.origin + '/' + path[1];
    function __hapusItem(id){
        if(confirm("Yakin item ini akan dihapus?")){
            $('#'+id).removeClass("hidden");
            $.ajax({
                type :'GET',
                url  : http+"/cashier/hapus_sod",
                data : {'id':id},
                dataType :'json',
                success:function(result){
                    result_message(result);
                }
            })
        }
        
    }
    function __hapusH(no_trans){
        if(confirm("Yakin transaksi ini akan dihapus?")){
            $('#'+no_trans).removeClass("hidden");
            $.ajax({
                type :'GET',
                url  : http+"/cashier/hapus_so",
                data : {'n':no_trans},
                dataType :'json',
                success:function(result){
                    $('#'+no_trans).addClass("hidden");
                    result_message(result);
                }
            })
        }
    }
</script>