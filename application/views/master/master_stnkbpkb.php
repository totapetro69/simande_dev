<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right ">
            <a id="modal-button" class="btn btn-info <?php echo  $status_c ?>" onclick='addForm("<?php echo base_url('dealer/add_stnk_bpkb'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-file-o fa-fw"></i> Add Master Biaya
            </a>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class='fa fa-list'></i> Master Biaya STNK BPKB
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: blok;">
                <form id="filterForm" action="<?php echo base_url('dealer/stnk_bpkb') ?>" class="bucket-form" method="get">
                    <div id="ajax-url" url="<?php echo base_url('dealer/stnk_bpkb_typeahead'); ?>"></div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Dealer</label>
                                <select class="form-control" id="kd_dealerx" name="kd_dealer">
                                    <option value="">--Pilih Dealer--</option>
                                    <?php
                                        if(isset($dealer)){
                                            if(($dealer->totaldata >0)){
                                                foreach ($dealer->message as $key => $value) {
                                                    $select="";
                                                    $select=($defaultDealer==$value->KD_DEALER)?'selected':$select;
                                                    echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER." -<b>".$value->KD_DEALERAHM."</b> [".$value->KD_JENISDEALER."]</option>";
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-2 col-md-2">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select id="tahun" name="tahun" class="form-control">
                                    <option value=''>--Pilih Tahun--</option>
                                    <?php
                                    //var_dump($thndata);
                                        if($thndata){
                                            if($thndata->totaldata>0){
                                                foreach ($thndata->message as $key => $value) {
                                                    $pilih=(date("Y")==$value->TAHUN)?'selected':'';
                                                    $pilih=($this->input->get("tahun")== $value->TAHUN)?"selected":$pilih;
                                                    echo "<option value='".$value->TAHUN."' $pilih >".$value->TAHUN."</option>";
                                                }
                                            }else{
                                                echo "<option value='".date("Y")."' selected>".date("Y")."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-5 col-sm-5 col-md-5">
                            <div class="form-group">
                                <label>Search</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan kode tipe motor" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <br>
                                <a class="btn btn-default" role="button" href="<?php echo base_url("dealer/stnk_bpkb");?>">Reset filter</a>
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
                <table class="table table-striped b-t b-light" id="lsh">
                    <thead>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th style="width:45px;">Aksi</th>
                            <th>Kode Dealer</th>
                             <th>Kabupaten</th>
                            <th>Tipe Motor</th>
                            <th>Tahun</th>
                            <th>BBNKB</th>
                            <th>PKB</th>
                            <th>SWDKLLJ</th>
                            <th>Total STNK</th>
                            <th>STCK</th>
                            <th>Plat Asli</th>
                            <th>Admin Samsat</th>
                            <th>BPKB</th>
                            <th>Pengurusan Tambahan</th>
                            <th>Total BPKB</th>
                            <th>SS</th>
                            <th>Banpen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $x=0;
                        if(isset($needApv)){
                            if($needApv->totaldata>0){
                                ?>
                                    <tr class="warning"><td>&nbsp;</td>
                                        <td colspan="17"><b>Master Biaya yang perlu Di Approve</b></td>
                                    </tr>
                                <?php
                                
                                foreach ($needApv->message as $key => $row) {
                                    //info detail secara popover
                                    $tr="";
                                    $tr .="<div class='row'><table class='table table-hover table-striped'>
                                            <tr class='subtotal'>
                                                <td class='table-nowarp text-center'>Dealer</td>
                                                <td class='table-nowarp text-center'>Kabupaten</td>
                                                <td class='table-nowarp text-center'>Tahun</td>
                                                <td class='table-nowarp text-center'>Tipe Motor</td>
                                            </tr>
                                            <tr><td class='text-center'>".$row->KD_DEALER."</td>
                                                <td class='text-center'>".NamaWilayah("Kabupaten",$row->KD_KABUPATEN)."</td>
                                                <td class='text-center'>".$row->TAHUN."</td>
                                                <td class='text-center'>".$row->KD_TIPEMOTOR."</td>
                                            </tr>
                                            <tr class='total'>
                                                <td colspan='2' class='text-center' style='border-right:2px solid grey !important'>Biaya BPKB</td>
                                                <td colspan='2' class='text-center'>Biaya STNK</td>
                                            </tr>
                                            <tr><td class='text-right'>Admin Samsat :</td>
                                                <td class='text-right' style='padding-right:5px; border-right:2px solid grey !important'>".number_format($row->ADMIN_SAMSAT,0)."</td>
                                                <td class='text-right'>BBNKB :</td>
                                                <td class='text-right' style='padding-right:5px;'>".number_format($row->ADMIN_SAMSAT,0)."</td>
                                            </tr>
                                            <tr><td class='text-right'>BPKB :</td>
                                                <td class='text-right' style='padding-right:5px; border-right:2px solid grey !important'>".number_format($row->BPKB,0)."</td>
                                                <td class='text-right'>PKB :</td>
                                                <td class='text-right' style='padding-right:5px;'>".number_format($row->PKB,0)."</td>
                                            </tr>
                                            <tr><td class='text-right'>PLat Asli :</td>
                                                <td class='text-right' style='padding-right:5px; border-right:2px solid grey !important'>".number_format($row->PLAT_ASLI,0)."</td>
                                                <td class='text-right'>SWDKLLJ :</td>
                                                <td class='text-right' style='padding-right:5px;'>".number_format($row->SWDKLLJ,0)."</td>
                                            </tr>
                                            <tr>
                                                <td class='text-right'>Tambahan :</td>
                                                <td class='text-right' style='padding-right:5px; border-right:2px solid grey !important'>".number_format($row->PLAT_ASLI,0)."</td>
                                                <td colspan='2'>&nbsp;</td>
                                            </tr>
                                            <tr class='total'>
                                                <td class='text-right'>Total BPKB :</td>
                                                <td class='text-right' style='border-right:2px solid grey !important'>".number_format($row->TOTAL_BPKB,0)."</td>
                                                <td class='text-right'>Total STNK :</td>
                                                <td class='text-right'>".number_format($row->TOTAL_STNK,0)."</td>
                                            </tr>
                                            <tr class='top-border'><td class='text-right'> BANPEN:</td>
                                                <td class='text-right' style='padding-right:5px; border-right:2px solid grey !important'>".number_format($row->BANPEN,0)."</td>
                                                <td class='text-right'>SS :</td>
                                                <td class='text-right' style='padding-right:5px;'>".number_format($row->SS,0)."</td>
                                            </tr>
                                            <tr class='success'><td class='text-right'>Created By :</td>
                                                <td class='text-right' style='padding-right:5px; border-right:2px solid grey !important'>".$row->CREATED_BY."</td>
                                                <td class='text-right'>Date :</td>
                                                <td class='text-right' style='padding-right:5px;'>".TglFromSql($row->CREATED_TIME)."</td>
                                            </tr>
                                            </table></div>";
                                    $x++;
                                    ?>
                                    <tr id="<?php echo  $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" 
                                        >
                                        <td class='text-center'><?php echo  $x; ?></td>
                                        <td class="table-nowarp text-center">
                                            <a href="<?php echo base_url('dealer/delete_stnk_bpkb/' . $row->ID); ?>" role="button">
                                                <i data-toggle="tooltip" data-placement="left" title="No Approved" class="fa fa-trash text-success text-active"></i>
                                            </a>
                                            <?php
                                            if((int)isApproval('SBPKB')>0){?>
                                                <a id="modal-button" onclick='addForm("<?php echo base_url('dealer/add_stnk_bpkb/' . $row->ID . '/' . $row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo  $status_v ?>">
                                                    <i data-toggle="tooltip" data-placement="left" title="Approved" class="fa fa-cogs text-success text-active"></i>
                                                </a>
                                            <?php }?>
                                            <a data-toggle="popover" data-title="Detail Biaya Pengurusan" data-content="<?php echo $tr;?>">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                        <td><?php echo $row->KD_DEALER; ?></td>
                                        <td class='table-nowarp'><?php echo NamaWilayah("Kabupaten",$row->KD_KABUPATEN); ?></td>
                                        <td class="text-center"><?php echo $row->KD_TIPEMOTOR; ?></td>
                                        <td class="text-center"><?php echo $row->TAHUN; ?></td>
                                        <td class="text-right"><?php echo number_format($row->BBNKB,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->PKB,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->SWDKLLJ,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->TOTAL_STNK,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->STCK,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->PLAT_ASLI,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->ADMIN_SAMSAT,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->BPKB,0); ?></td>
                                        <td class="text-right"><?php if($row->PENGURUSAN_TAMBAHAN != null){ echo number_format($row->PENGURUSAN_TAMBAHAN,0);}else{echo $row->PENGURUSAN_TAMBAHAN;} ?></td>
                                        <td class="text-right"><?php echo number_format($row->TOTAL_BPKB,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->SS,0); ?></td>
                                        <td class="text-right"><?php if($row->BANPEN != null){echo number_format($row->BANPEN,0); }else{echo $row->BANPEN;}?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                    <tr class="success top-border"><td>&nbsp;</td>
                                        <td colspan="17"> List Master Biaya yang Sudah Di Approve</td>
                                    </tr>
                                <?
                            }
                        }
                        $no = $this->input->get('page');
                        if (isset($list)):
                            if ($list->totaldata>0):
                                foreach ($list->message as $key => $row):
                                    $no ++;
                                    $tr2="";
                                    $tr2 .="<div class='row'><table class='table table-hover table-striped'>
                                            <tr class='subtotal'>
                                                <td class='table-nowarp text-center'>Dealer</td>
                                                <td class='table-nowarp text-center'>Kabupaten</td>
                                                <td class='table-nowarp text-center'>Tahun</td>
                                                <td class='table-nowarp text-center'>Tipe Motor</td>
                                            </tr>
                                            <tr><td class='text-center'>".$row->KD_DEALER."</td>
                                                <td class='text-center'>".NamaWilayah("Kabupaten",$row->KD_KABUPATEN)."</td>
                                                <td class='text-center'>".$row->TAHUN."</td>
                                                <td class='text-center'>".$row->KD_TIPEMOTOR."</td>
                                            </tr>
                                            <tr class='total'>
                                                <td colspan='2' class='text-center' style='border-right:2px solid grey !important'>Biaya BPKB</td>
                                                <td colspan='2' class='text-center'>Biaya STNK</td>
                                            </tr>
                                            <tr><td class='text-right'>Admin Samsat :</td>
                                                <td class='text-right' style='padding-right:5px; border-right:2px solid grey !important'>".number_format($row->ADMIN_SAMSAT,0)."</td>
                                                <td class='text-right'>BBNKB :</td>
                                                <td class='text-right' style='padding-right:5px;'>".number_format($row->ADMIN_SAMSAT,0)."</td>
                                            </tr>
                                            <tr><td class='text-right'>BPKB :</td>
                                                <td class='text-right' style='padding-right:5px; border-right:2px solid grey !important'>".number_format($row->BPKB,0)."</td>
                                                <td class='text-right'>PKB :</td>
                                                <td class='text-right' style='padding-right:5px;'>".number_format($row->PKB,0)."</td>
                                            </tr>
                                            <tr><td class='text-right'>PLat Asli :</td>
                                                <td class='text-right' style='padding-right:5px; border-right:2px solid grey !important'>".number_format($row->PLAT_ASLI,0)."</td>
                                                <td class='text-right'>SWDKLLJ :</td>
                                                <td class='text-right' style='padding-right:5px;'>".number_format($row->SWDKLLJ,0)."</td>
                                            </tr>
                                            <tr>
                                                <td class='text-right'>Tambahan :</td>
                                                <td class='text-right' style='padding-right:5px; border-right:2px solid grey !important'>".number_format($row->PLAT_ASLI,0)."</td>
                                                <td colspan='2'>&nbsp;</td>
                                            </tr>
                                            <tr class='total'>
                                                <td class='text-right'>Total BPKB :</td>
                                                <td class='text-right' style='border-right:2px solid grey !important'>".number_format($row->TOTAL_BPKB,0)."</td>
                                                <td class='text-right'>Total STNK :</td>
                                                <td class='text-right'>".number_format($row->TOTAL_STNK,0)."</td>
                                            </tr>
                                            <tr class='top-border'><td class='text-right'> BANPEN:</td>
                                                <td class='text-right' style='padding-right:5px; border-right:2px solid grey !important'>".number_format($row->BANPEN,0)."</td>
                                                <td class='text-right'>SS :</td>
                                                <td class='text-right' style='padding-right:5px;'>".number_format($row->SS,0)."</td>
                                            </tr>
                                            <tr class='success'><td class='text-right'>Created By :</td>
                                                <td class='text-right' style='padding-right:5px; border-right:2px solid grey !important'>".$row->CREATED_BY."</td>
                                                <td class='text-right'>Date :</td>
                                                <td class='text-right' style='padding-right:5px;'>".TglFromSql($row->CREATED_TIME)."</td>
                                            </tr>
                                            <tr class='info'><td class='text-right'>Approved By :</td>
                                                <td class='text-right' style='padding-right:5px; border-right:2px solid grey !important'>".$row->LASTMODIFIED_BY."</td>
                                                <td class='text-right'>Date :</td>
                                                <td class='text-right' style='padding-right:5px;'>".TglFromSql($row->LASTMODIFIED_TIME)."</td>
                                            </tr>
                                            </table></div>";
                                    ?>
                                    <tr id="<?php echo  $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td class='text-center'><?php echo  $no; ?></td>
                                        <td class="table-nowarp text-center">
                                            <a href="<?php echo base_url('dealer/history_stnk_bpkb/' . $row->ID); ?>" role="button">
                                                <i data-toggle="tooltip" data-placement="left" title="History Perubahan" class="fa fa-file-o text-success text-active"></i>
                                            </a>
                                            <a id="modal-button" onclick='addForm("<?php echo base_url('dealer/add_stnk_bpkb/' . $row->ID); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo  $status_v ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Ubah" class="fa fa-edit text-success text-active"></i>
                                            </a>
                                            <a data-toggle="popover" data-title="Detail Biaya Pengurusan" data-content="<?php echo $tr2;?>">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                        <td><?php echo $row->KD_DEALER; ?></td>
                                        <td class='table-nowarp'><?php echo NamaWilayah('Kabupaten',$row->KD_KABUPATEN); ?></td>
                                        <td class="text-center"><?php echo $row->KD_TIPEMOTOR; ?></td>
                                        <td class="text-center"><?php echo $row->TAHUN; ?></td>
                                        <td class="text-right"><?php echo number_format($row->BBNKB,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->PKB,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->SWDKLLJ,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->TOTAL_STNK,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->STCK,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->PLAT_ASLI,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->ADMIN_SAMSAT,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->BPKB,0); ?></td>
                                        <td class="text-right"><?php if($row->PENGURUSAN_TAMBAHAN != null){ echo number_format($row->PENGURUSAN_TAMBAHAN,0);}else{echo $row->PENGURUSAN_TAMBAHAN;} ?></td>
                                        <td class="text-right"><?php echo number_format($row->TOTAL_BPKB,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->SS,0); ?></td>
                                        <td class="text-right"><?php if($row->BANPEN != null){echo number_format($row->BANPEN,0); }else{echo $row->BANPEN;}?></td>
                                    </tr>
                                    <?php
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
<script type="text/javascript">
   $(document).ready(function(e){
      $('table#lsh tbody tr[data-toggle="popover"]').popover({
         placement: 'auto', 
         trigger: 'hover', 
         html: true
      })
      $('a[data-toggle="popover"').popover({
         placement: 'auto left', 
         trigger: 'hover', 
         html: true,
         container:'body',
         template: '<div class="popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>'
      })
   })
</script>