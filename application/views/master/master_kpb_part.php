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
            <a id="modal-button" class="btn btn-primary <?php echo  $status_c ?>" onclick='addForm("<?php echo base_url('sparepart/add_kpb_part'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-download"></i> Update Data
            </a>
        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                KPB No Part
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('sparepart/kpb_part') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('sparepart/kpb_part_typeahead'); ?>"></div>

                    <div class="form-group">
                        <label>KPB No Part</label>
                        <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukan No Mesin" autocomplete="off">
                    </div>

                </form>

            </div>

        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel panel-default">
            <!-- <div class="panel-heading">
              Responsive Table
            </div> -->

            <div class="table-responsive">

                <table class="table table-striped b-t b-light">

                    <thead>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th>No Mesin</th>
                            <th>Motor KPB</th>        
                            <th>No Part Oli</th>
                            <th>No Part Oli2</th>
                            <th>Isi Oli</th>
                            <th>Harga Oli</th>
                            <th>No Part Oli1</th>
                            <th>No Part Oli2</th>
                            <th>Isi Oli2</th>
                            <th>Harga Oli2</th>
                            <th>Nominal Jasa</th>
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
                                        <td class="table-nowarp"><?php echo $row->NO_MESIN; ?></td>
                                        <td class="table-nowarp"><?php echo $row->MOTOR_KPB; ?></td>
                                        <td class="table-nowarp"><?php echo $row->NO_PART_OLI_1A; ?></td>
                                        <td class="table-nowarp"><?php echo $row->NO_PART_OLI_1B; ?></td>
                                        <td class="table-nowarp"><?php echo $row->ISI_OLI_1; ?></td>
                                        <td class="table-nowarp"><?php echo number_format($row->HARGA_OLI_1); ?></td>
                                        <td class="table-nowarp"><?php echo $row->NO_PART_OLI_2A; ?></td>
                                        <td class="table-nowarp"><?php echo $row->NO_PART_OLI_2B; ?></td>
                                        <td class="table-nowarp"><?php echo $row->ISI_OLI_2; ?></td>
                                        <td class="table-nowarp"><?php echo number_format($row->HARGA_OLI_2); ?></td>
                                        <td class="table-nowarp"><?php echo number_format($row->NOMINAL_JASA); ?></td>
                                    </tr>

                                    <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="12"><b><?php echo ($list->message); ?></b></td>
                                </tr>
                            <?php
                            endif;
                        else:
                            echo belumAdaData(12);
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