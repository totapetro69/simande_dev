<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>
<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">
            <a id="modal-button" class="btn btn-default hidden <?php echo  $status_c ?>" onclick='addForm("<?php echo base_url('dealer/add_stnk_bpkb'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-file-o fa-fw"></i> Tambah Baru
            </a>
            <a href="<?php echo base_url('dealer/stnk_bpkb');?>" class="btn btn-default"><i class='fa fa-list-ul'></i> List Master Biaya</a>

        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                History Perubahan Master STNK BPKB
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: show;">

                <table class="table table-striped b-t b-light">
                    <tr>
                        <td class='table-nowarp'>Kode Dealer</td>
                        <td class='table-nowarp'>: <?php echo $list_detail->message[0]->KD_DEALER; ?></td>
                        <td class='col-sm-1'>&nbsp;</td>
                        <td class='table-nowarp'>Tipe Motor</td>
                        <td class='table-nowarp'>: <?php echo $list_detail->message[0]->KD_TIPEMOTOR; ?></td>
                        <td class='col-sm-2'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class='table-nowarp'>Kabupaten</td>
                        <td class='table-nowarp'>: <?php echo $list_detail->message[0]->NAMA_KABUPATEN; ?></td>
                        <td class='col-sm-1'>&nbsp;</td>
                        <td class='hidden'>Tahun</td>
                        <td class='hidden'>: <?php echo $list_detail->message[0]->TAHUN; ?></td>
                        <td class='col-sm-1'>&nbsp;</td>
                    </tr>
                    
                </table>

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
                            <th>Tanggal</th>
                            <th>BBNKB</th>
                            <th>PKB</th>
                            <th>SWDKLLJ</th>
                            <th style="border-right: 1px dotted">Total STNK</th>
                            <th>STCK</th>
                            <th>Plat Asli</th>
                            <th>Admin Samsat</th>
                            <th>BPKB</th>
                            <th>Pengurusan Tambahan</th>
                            <th style="border-right: 1px dotted">Total BPKB</th>
                            <th>SS</th>
                            <th>Banpen</th>
                            <th>Submit By</th>
                            <th>Approval</th>
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

                                    <tr id="<?php echo  $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo  $no; ?></td>
                                        <td><?php echo /*($row->CREATED_TIME)? tglFromSql($row->CREATED_TIME):*/$row->TAHUN;  ?></td>
                                        <td class="text-right"><?php echo number_format($row->BBNKB,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->PKB,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->SWDKLLJ,0); ?></td>
                                        <td style="border-right: 1px dotted"><?php echo number_format($row->TOTAL_STNK,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->STCK,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->PLAT_ASLI,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->ADMIN_SAMSAT,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->BPKB,0); ?></td>
                                       <td class="text-right"><?php if($row->PENGURUSAN_TAMBAHAN != null){ echo number_format($row->PENGURUSAN_TAMBAHAN,0);}else{echo $row->PENGURUSAN_TAMBAHAN;} ?></td>
                                        <td class="text-right" style="border-right: 1px dotted"><?php echo number_format($row->TOTAL_BPKB,0); ?></td>
                                        <td class="text-right"><?php echo number_format($row->SS,0); ?></td>
                                        <td class="text-right"><?php if($row->BANPEN != null){echo number_format($row->BANPEN,0); }else{echo $row->BANPEN;}?></td>
                                        <td><?php echo $row->CREATED_BY; ?></td>
                                        <td><?php echo $row->NAMA; ?></td>
                                        <td><?php if($row->STATUS_APPROVE == 0){
                                            echo "Submitted";
                                        }elseif($row->STATUS_APPROVE == 1){
                                            echo "Approved";
                                        }else{
                                            echo "Rejected";
                                        } ?></td>
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

                <!-- <div class="row">

                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total Data " . $list->totaldata . " items</i>") : '' ?>
                        </small>
                    </div>

                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo $pagination; ?>
                    </div>

                </div> -->

            </footer>

        </div>

    </div>

</section>