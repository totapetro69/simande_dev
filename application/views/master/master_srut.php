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
            <a id="modal-button" class="btn btn-primary <?php echo  $status_c ?>" onclick='addForm("<?php echo base_url('motor/add_srut'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-download"></i> Update Data
            </a>
        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                SRUT
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('motor/srut') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('motor/srut_typeahead'); ?>"></div>

                    <div class="form-group">
                        <label>SRUT</label>
                        <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukan No Terima atau No Mesin atau No Rangka" autocomplete="off">
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
                            <th>No Terima Dealer</th>
                            <th>Tgl Terima</th>        
                            <th>No Mesin</th>
                            <th>No Rangka</th>
                            <th>No SUT</th>
                            <th>No SRUT</th>
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
                                        <td class="table-nowarp"><?php echo $row->NO_TERIMA_DEALER; ?></td>
                                        <td class="table-nowarp"><?php echo $row->TGL_TERIMA; ?></td>
                                        <td class="table-nowarp"><?php echo $row->NO_MESIN; ?></td>
                                        <td class="table-nowarp"><?php echo $row->NO_RANGKA; ?></td>
                                        <td class="table-nowarp"><?php echo $row->NO_SUT; ?></td>
                                        <td class="table-nowarp"><?php echo $row->NO_SRUT; ?></td>
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
                            echo belumAdaData(7);
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