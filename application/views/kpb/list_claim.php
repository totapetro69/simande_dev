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

            <a id="kpb-btn" class="btn btn-default <?php echo $status_p;?>" role="button">
                <i class="fa fa-download fa-fw"></i> Download File .SDKPB
            </a>

        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                <i class="fa fa-list fa-fw"></i> List Claim
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('kpb/list_claim') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('kpb/kpbvalidasi_typeahead?status_kpb=1'); ?>"></div>

                    <div class="row">

                        <div class="col-xs-12 col-sm-12">

                            <div class="form-group">
                                <label>Pencarian</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Cari berdasarkan nomor mesin" autocomplete="off" value="<?php echo $this->input->get('keyword');?>">
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
                <table id="pkb_list" class="table table-striped table-bordered">
                    <thead>
                        <tr class="no-hover"><th colspan="15" ><i class="fa fa-list fa-fw"></i> Data Claim</th></tr>
                        <tr>
                            <th rowspan="2" style="width:40px;">No.</th>
                            <th rowspan="2" style="width:45px;"><input id="kpb_all" class="kpb_all" name="kpb_all" value="1" type="checkbox"></th>
                            <th colspan="11">No. Claim</th>
                        </tr>
                        <tr>
                            <th>Kode Main Dealer</th>
                            <th>Kode Dealer</th>
                            <th>No. PKB</th>
                            <th>No. Mesin</th>
                            <th>No. Rangka</th>
                            <th>Tanggal Beli</th>
                            <th>Sequence</th>
                            <th>KM Service</th>
                            <th>Tanggal Service</th>
                            <th>Motor Luar</th>
                            <th>Buku Baru</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if ($list):
                            if (is_array($list->message) || is_object($list->message)):
                                foreach ($list->message as $key => $header) :
                                $no ++;
                        ?>

                                    <tr class="info bold">
                                        <td><?php echo  $no; ?></td>
                                        <td><input class="kpb_checked kpb_checked<?php echo $header->NO_KPB;?>" name="no_kpb" value="<?php echo $header->NO_KPB;?>" type="checkbox"></td>
                                        <td colspan="11"><?php echo  $header->NO_KPB; ?></td>
                                    </tr>

                        <?php
                                foreach ($detail->message as $key => $row):
                                if($header->NO_KPB == $row->NO_KPB):
                                    ?>

                                    <tr id="<?php echo  $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td></td>
                                        <td></td>
                                        <!-- <td class="table-nowarp"> -->
                                        <!-- </td> -->
                                        <td><?php echo  $row->KD_MAINDEALER; ?></td>
                                        <td><?php echo  $row->KD_DEALER; ?></td>
                                        <td><?php echo  $row->NO_PKB; ?></td>
                                        <td><?php echo  $row->KD_MESIN.$row->NO_MESIN; ?></td>
                                        <td><?php echo  $row->NO_RANGKA; ?></td>
                                        <td><?php echo  $row->TGL_BELI; ?></td>
                                        <td><?php echo  $row->SEQUENCE; ?></td>
                                        <td><?php echo  $row->KM_SERVICE; ?></td>
                                        <td><?php echo  $row->TGL_SERVICE; ?></td>
                                        <td><?php echo  $row->MOTOR_LUAR == '*'? 'True':'False'; ?></td>
                                        <td><?php echo  $row->BUKU_BARU == ''? 'False':'True'; ?></td>
                                    </tr>

                                    <?php
                                endif;
                                endforeach;

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

<script type="text/javascript" src="<?php echo base_url("assets/js/external/kpb.js");?>"></script>
