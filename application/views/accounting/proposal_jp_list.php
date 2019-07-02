<?php
  if (!isBolehAkses()) { redirect(base_url() . 'auth/error_auth');}
  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $usergroup=$this->session->userdata("kd_group");
  $defaultDealer = ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
  $bulanIni =($this->input->get("bln"))?$this->input->get("bln"):date('m');
  $TahunIni =($this->input->get("thn"))?$this->input->get("thn"):date('Y');
  $applist =($this->input->get('a')=='y')?true:false;
  $status_c =($applist)?'hidden':$status_c;
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>

        <div class="bar-nav pull-right ">
            <a id="modal-button" class="btn btn-default <?php echo $status_c;?>" href="<?php echo base_url('stock_opname/proposal_jp'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Add Proposal 
            </a>
            <a id="modal-button-1" class="btn btn-default <?php echo ($applist)?'':'hidden';?>" href="<?php echo base_url('stock_opname/proposal_jplist'); ?>" role="button">
                <i class="fa fa-list-ul fa-fw"></i> Proposal List
            </a>
            <!-- <a id="modal-button-1" class="btn btn-default" href="<?php echo base_url('cashier/laporan_lkh'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Laporan Kas Harian
            </a> -->

        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class='fa fa-list-ul'></i> List Proposal Join Promo <?php echo ($applist)?'Need Approval':'';?>
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
            	<form id="frmCriteria" action="" class="bucket-form" method="get">
                    <div class="row">
            			<div class="col-xs-6 col-md-3 col-sm-3">
                            <div class="form-group">
                				<label>Nama Dealer</label>
                				<select name="kd_dealer" id="kd_dealer" class="form-control">
                					<option value="">--Pilih Dealer--</option>
                					<?php
            							if($dealer){
            								if(is_array($dealer->message)){
            									foreach ($dealer->message as $key => $value) {
            										$select=($this->session->userdata('kd_dealer')==$value->KD_DEALER)?"selected":"";
                                                    $select=($defaultDealer==$value->KD_DEALER)?"selected":$select;
            										echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
            									}
            								}
            							}
            						?>
                				</select>
                            </div>
            			</div>
                        <div class="col-xs-6 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Periode Bulan</label>
                                <select class="form-control" name="bln" id="bln">
                                    <option value="">--Pilih Bulan--</option>
                                    <?php
                                        for($i=0;$i < 13; $i++){
                                            $pilih =($bulanIni==$i)?'selected':'';
                                            echo "<option value='".$i."' ".$pilih.">".nBulan($i)."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-2 col-sm-2">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select class="form-control" name="thn" id="thn">
                                    <option value="">--Pilih Tahun--</option>
                                    <?php
                                        if(isset($tahun)){
                                            if($tahun->totaldata >0){
                                                foreach ($tahun->message as $key => $value) {
                                                   $pilih =($TahunIni==$value->TAHUN)?'selected':'';
                                            echo "<option value='".$value->TAHUN."' ".$pilih.">".$value->TAHUN."</option>";
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class='text-center'>No.</th>
                            <th class='text-center'>#</th>
                            <th class='text-center'>Judul Proposal</th>
                            <th class='text-center'>Area Kegiatan</th>
                            <th class='text-center'>Tanggal Kegiatan</th>
                            <th class='text-center'>Tujuan</th>
                            <th class='text-center'>Target Audiens</th>
                            <th class='text-center'>Target Sales</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $n=0;
                            if(isset($list)){
                                if($list->totaldata >0){
                                    foreach ($list->message as $key => $value) {
                                        $apv_level=$value->STATUS_JOINPROMO;
                                        $jns='PROJP';
                                        $apv=isApproval($jns);
                                        $n++;
                                        $status_e =($value->STATUS_JOINPROMO >0 || $value->STATUS_JOINPROMO <0)?'disabled-action':$status_e;
                                        $no_trans = urlencode(base64_encode($value->NO_TRANS));
                                        if($applist){
                                            if(($apv-1)==$apv_level){
                                                ?>
                                                    <tr>
                                                        <td class='text-center table-nowarp'><?php echo $n;?></td>
                                                        <td>
                                                            <a href="<?php echo base_url('stock_opname/proposal_jp?n=').$no_trans.'&a=y&v='.$apv;?>" class="<?php echo $status_v;?>" title="view detail proposal for edit or print"><i class='fa fa-cogs'></i></a>
                                                            <!-- <a id="h_<?php echo $value->NO_TRANS;?>" onclick="__hapus_jp('<?php echo $value->NO_TRANS;?>');" class="<?php echo $status_e;?>" title="hapus proposal"><i class="fa fa-trash"></i></a> -->
                                                        </td>
                                                        <td class='table-nowarp'><?php echo $value->KEGIATAN_JOINPROMO;?></td>
                                                        <td class='table-nowarp'><?php echo $value->AREA_JOINPROMO;?></td>
                                                        <td class='table-nowarp'><?php echo $value->TGL_JOINPROMO;?></td>
                                                        <td class='td-overflow' title="<?php echo $value->TUJUAN_JOINPROMO;?>"><?php echo $value->TUJUAN_JOINPROMO;?></td>
                                                        <td class='table-nowarp text-center'><?php echo $value->TARGET_AUDIENS;?></td>
                                                        <td class='table-nowarp text-center'><?php echo $value->TARGET_SALES;?></td>
                                                        <td class='table-nowarp text-center'><?php echo ($value->STATUS_JOINPROMO>0)?'Apv':($value->STATUS_JOINPROMO>0)?'Open':'Not';?></td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <td colspan="5">&nbsp;</td>
                                                        <td colspan="4">&nbsp;</td>
                                                    </tr>
                                                <?
                                            }
                                        }else{

                                        ?>
                                            <tr>
                                                <td class='text-center table-nowarp'><?php echo $n;?></td>
                                                <td>
                                                    <a href="<?php echo base_url('stock_opname/proposal_jp?n=').$no_trans;?>" class="<?php echo $status_v;?>" title="view detail proposal for edit or print"><i class='fa fa-edit'></i></a>
                                                    <a id="h_<?php echo $value->NO_TRANS;?>" onclick="__hapus_jp('<?php echo $value->NO_TRANS;?>');" class="<?php echo $status_e;?>" title="hapus proposal"><i class="fa fa-trash"></i></a>
                                                </td>
                                                <td class='table-nowarp'><?php echo $value->KEGIATAN_JOINPROMO;?></td>
                                                <td class='table-nowarp'><?php echo $value->AREA_JOINPROMO;?></td>
                                                <td class='table-nowarp'><?php echo $value->TGL_JOINPROMO;?></td>
                                                <td class='td-overflow' title="<?php echo $value->TUJUAN_JOINPROMO;?>"><?php echo $value->TUJUAN_JOINPROMO;?></td>
                                                <td class='table-nowarp text-center'><?php echo $value->TARGET_AUDIENS;?></td>
                                                <td class='table-nowarp text-center'><?php echo $value->TARGET_SALES;?></td>
                                                <td class='table-nowarp text-center'><?php echo ($value->STATUS_JOINPROMO>0)?'Apv':($value->STATUS_JOINPROMO>0)?'Open':'Not';?></td>
                                            </tr>
                                        <?php
                                        }
                                    }
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <footer class="panel-footer">
            <div class="row">
                 <div class="col-sm-5">
                    <small class="text-muted inline m-t-sm m-b-sm"> 
                        <?php echo isset($list) ? ($list->totaldata == '' ? "" : "<i>Total Data " . $list->totaldata . " items</i>") : '' ?>
                    </small>
                </div>
                <div class="col-sm-7 text-right text-center-xs">                
                    <?php echo $pagination; ?>
                </div>
            </div>
        </footer>
    </div>
    <?php echo loading_proses();?>
</section>
<script type="text/javascript">
    $(document).ready(function(){

    })

    function __hapus_jp(no_trans){
        if(confirm('Yakin Proposal ini akan di hapus?')){
            $("#h_"+no_trans).html("<i class='fa fa-spinner fa-spin red'></i>");
            $.getJSON("<?php echo base_url();?>stock_opname/proposal_del",{'n':no_trans},function(result){
                if(result.status){
                    document.location.reload();
                }
            })
        }
    }

</script>