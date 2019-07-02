<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$jps=($this->input->get('jps'))?$this->input->get('jps'):'PK014';
$sts=($this->input->get('sts'))?$this->input->get('sts'):'0';
$thn=($this->input->get("thn"))?$this->input->get("thn"):date('Y');
$bln=($this->input->get("bln"))?$this->input->get("bln"):date('m');
$mode="";$title="";
$model=$this->input->get("m");
switch ($model) {
    case 'apv':
        $mode="hidden";
        $sts='0';
        $title='Approval Piutang';
        break;
    
    default:
        $title='List Piutang';
        break;
}
$apv=isApproval('PIUNT');
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right">
            <a class="btn btn-default <?php //echo $status_e;?> <?php echo ($model=='apv')?'':'hidden';?> <?php echo ($apv=='0')?'disabled-action':'';?>" onclick="__approved();" role='button'><i class='fa fa-cogs'></i> Approve</a>
            <a class="btn btn-default hidden <?php echo $status_p ?>" id="modal-button" onclick='addForm("<?php echo base_url('report/tagihan_print?tanggal=' . $this->input->get("tanggal") . '&kd_dealer' . $this->input->get("kd_dealer")); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Tagihan" ></i> Cetak
            </a>
        </div>

    </div>

    <div class="col-lg-12 padding-left-right-5 ">

        <div class="panel margin-bottom-5">

            <div class="panel-heading">
                <i class="fa fa-list-ul fa-fw" title="<?php echo $apv;?>"></i> <?php echo $title;?> <label class='badge hidden'><?php echo $apv;?></label>
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border panel-body-10 <?php echo $mode;?>" style="display: block;">

                <form id="filterForms" method="GET" action="<?php echo base_url("report/tagihan"); ?>">

                    <div class="row">

                        <div class="col-xs-6 col-md-2 col-sm-2">
                            
                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select class="form-control " id="kd_dealer" name="kd_dealer">
                                    <option value="0">--Pilih Dealer--</option>
                                    <?php
                                    if (isset($dealer)){
                                        if (($dealer->totaldata >0)) {
                                            foreach ($dealer->message as $key => $value) {
                                                $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                            }
                                        }
                                    }
                                    ?> 
                                </select>
                            </div>
                            
                        </div>
                        <div class="col-xs-6 col-sm-2 col-md-2">
                            <div class="form-group">
                                <label>Jenis Piutang</label>
                                <select id="jps" name="jps" class="form-control">
                                    <option value="PK014" <?php echo ($jps=='PK014')?'selected':'';?>>Piutang Unit</option>
                                    <option value="PKULS" <?php echo ($jps=='PKULS')?'selected':'';?>>Piutang Leasing</option>
                                    <option value="PPRJP" <?php echo ($jps=='PPRJP')?'selected':'';?>>Piutang Join Promo</option>
                                    <option value="PPOTH" <?php echo ($jps=='PPOTH')?'selected':'';?>>Piutang Lain - Lain</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-2 col-sm-2">
                            <div class="form-group">
                                <label>Status Piutang</label>
                                <select class='form-control' name="sts">
                                    <option value='2' <?php echo ($sts=='2')?'selected':'';?>>--All Status--</option>
                                    <option value='0' <?php echo ($sts=='0')?'selected':'';?>>Open</option>
                                    <option value='1' <?php echo ($sts=='1')?'selected':'';?>>Close</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-2 col-sm-2">
                            <div class="form-group">
                                <label>Periode Bulan</label>
                                <select class="form-control" name="bln">
                                    <option value=''>--Pilih Bulan--</option>
                                    <?php
                                        for($i=1; $i<=12; $i++){
                                            $pilih=($bln==$i)?'selected':'';
                                            echo "<option value='".$i."' ".$pilih.">".nBulan($i)."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-2 col-sm-2">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select class="form-control" name="thn">
                                    <option value=''>--Pilih Tahun--</option>
                                    <?php
                                        if(isset($tahun)){
                                            if($tahun->totaldata >0){
                                                foreach ($tahun->message as $key => $value) {
                                                    $pilih=($thn==$value->TAHUN)?'selected':'';
                                                    echo "<option value='".$value->TAHUN."' ".$pilih.">".$value->TAHUN."</option>";
                                                }
                                            }else{
                                                echo "<option value='".date('Y')."' selected='true'>".date('Y')."</option>";
                                            }
                                        }else{
                                            echo "<option value='".date('Y')."' selected='true'>".date('Y')."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-2 col-sm-2">
                            <div class="form-group pull-right">
                                <br>
                                <button id="submit-btn" onclick="addData();" class="btn btn-info" ><i class='fa fa-search'></i> Preview</button>
                            </div>
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-5 ">

        <div class="panel panel-default">
            <div class="table-responsive h350">
                <table class="table table-hover table-striped table-bordered" id="lst_apv">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>&nbsp;</th>
                            <th>No. LKH</th>
                            <th>Tanggal LKH</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no=0;$apv_sts="0";$statuse="";                       
                        if (isset($list)) {
                            if (($list->totaldata)) {
                                foreach ($list->message as $key => $value) {                                   
                                    $apv_sts="0";//($value->APV_PIUTANG)?$value->APV_PIUTANG:"0";
                                    $default=($value->KD_PIUTANG=='PK014')?'':'hidden';
                                    switch($apv_sts){ case '0':$statuse="Open";break;default: $statuse='Approved';break;}
                                    ?>
                                    <tr class='<?php echo $value->KD_PIUTANG." ".$default;?>'>
                                        <td class="table-nowarp"><?php echo ($no+1); ?></td>
                                        <td class="table-nowarp">
                                            <input type="checkbox" id="chk_<?php echo $no;?>" name="chk_<?php echo $no;?>" value="<?php echo $value->NO_TRANS;?>" class='<?php echo ((int)$apv >0)?'':'disabled-action';?> <?php echo ($apv_sts=='0' && ($model=='apv'))?'':'hidden';?> <?php echo ($model=='apv')?'':'hidden';?>'>
                                            <a class="<?php echo $status_v;?>" href="javascript:;"><i class='fa fa-edit'></i></a>
                                            <a class="hidden <?php echo ($apv_sts=='0')?$status_e:'hidden';?> <?php echo $status_e;?>" href=""><i class="fa fa-trash"></i></a>
                                            <a class='<?php echo $status_v;?>' title='Print Tagihan'><i class='fa fa-print'></i></a>
                                        </td>
                                        <td class="table-nowarp"><?php echo $value->NO_TRANS;?></td>
                                        <td class="table-nowarp"><?php echo tglFromSql($value->TGL_TRANS);?></td>
                                        <td class="table-nowarp text-right"><?php echo number_format($value->JUMLAH_PIUTANG,0);?></td>
                                        <td class="td-overflow-100" title="<?php echo $value->URAIAN_PIUTANG;?>"><?php echo $value->URAIAN_PIUTANG;?></td>
                                        <td><?php echo $statuse;?></td>
                                    </tr>
                                    <?php
                                    $no++;
                                }
                            } else {
                                belumAdaData(6);
                            }
                        } else {
                            belumAdaData(6);
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo (isset($totaldata)) ? ($totaldata == '0' ? "" : "<i>Total Data " . $totaldata . " items</i>") : '' ?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo isset($pagination) ? $pagination : ""; ?>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <?php echo loading_proses();?>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        $('#jps').on('change',function(e){
            var j=$(this).val();
            console.log(j);
            $('table#lst_apv tbody tr:not(.'+j+')').addClass('hidden');
            $('table#lst_apv tbody tr.'+j).removeClass('hidden');
        })
    })
    function __approved(){
        var jmlbaris=$('#lst_apv > tbody > tr').length;
        var datax=[];
        for(i=0; i < jmlbaris; i++){
            if($('#chk_'+i).is(":checked")){
                datax.push({
                    'no_trans' : $('#chk_'+i).val(),
                    'apv'   :"<?php echo $apv;?>"
                });
            }
        }
        console.log(datax);
        if(datax.length=="0"){ alert('Tidak ada data yang akan di Approved');return false;}
        $('#loadpage').removeClass("hidden");
        $.ajax({
            type : 'POST',
            url : "<?php echo base_url('report/tagihan_approval');?>",
            data : {'data':JSON.stringify(datax)},
            dataType :'json',
            success: function(result){
                if(result){
                    //document.location.reload();
                }
                $('#loadpage').addClass("hidden");
            }
        })
    }
</script>