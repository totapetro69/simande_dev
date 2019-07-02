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

            

        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                Approval Master STNK BPKB
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('dealer/stnk_bpkb_approval') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('dealer/stnk_bpkb_backup_typeahead'); ?>"></div>

                    <div class="row">

                        <div class="col-xs-12 col-sm-12">

                            <div class="form-group">
                                <label>Approval Master STNK BPKB</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan kode tipe motor" autocomplete="off">
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
                            <th style="width:40px;">No.</th>
                            <th style="width:45px;">Aksi</th>
                            <th>Kode Dealer</th>
                            <th>Tipe Motor</th>
                            <th>Kabupaten</th>
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
                            <th>Approval</th>
                            <th>Edit By</th>
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
                                        <td class="table-nowarp">
                                            <?php 
                    if($row->STATUS_APPROVE == 0){ 
                      ?>
                                            <a id="modal-button" onclick='addForm("<?php echo base_url('dealer/edit_stnk_bpkb_approval/' . $row->ID . '/' . $row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo  $status_v ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Approval" class="fa fa-hand-o-up text-success text-active"></i>
                                            </a>
                                            <?php
                    }
                    ?>
                                        </td>
                                        <td><?php echo $row->KD_DEALER; ?></td>
                                        <td><?php echo $row->KD_TIPEMOTOR; ?></td>
                                        <td><?php echo $row->NAMA_KABUPATEN; ?></td>
                                        <td><?php echo $row->TAHUN; ?></td>
                                        <td><?php echo number_format($row->BBNKB,0); ?></td>
                                        <td><?php echo number_format($row->PKB,0); ?></td>
                                        <td><?php echo number_format($row->SWDKLLJ,0); ?></td>
                                        <td><?php echo number_format($row->TOTAL_STNK,0); ?></td>
                                        <td><?php echo number_format($row->STCK,0); ?></td>
                                        <td><?php echo number_format($row->PLAT_ASLI,0); ?></td>
                                        <td><?php echo number_format($row->ADMIN_SAMSAT,0); ?></td>
                                        <td><?php echo number_format($row->BPKB,0); ?></td>
                                        <td><?php if($row->PENGURUSAN_TAMBAHAN != null){ echo number_format($row->PENGURUSAN_TAMBAHAN,0);}else{echo $row->PENGURUSAN_TAMBAHAN;} ?></td>
                                        <td><?php echo number_format($row->TOTAL_BPKB,0); ?></td>
                                        <td><?php echo number_format($row->SS,0); ?></td>
                                        <td><?php if($row->BANPEN != null){echo number_format($row->BANPEN,0); }else{echo $row->BANPEN;}?></td>
                                        <td><?php echo $row->NAMA; ?></td>
                                        <td><?php echo $row->CREATED_BY; ?></td>
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