

<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <a href="<?php echo base_url('part/hargabeli_md'); ?>" class="btn btn-default $status_v">
                <i class="fa fa-list"></i> List Harga Beli Ke MD
            </a>

        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                History Perubahan Master Harga Beli MD
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: show;">

                <table class="table table-striped b-t b-light">
                    <tr>
                        <td>Kode Part</td>
                        <td>: <?php echo $list->message[0]->PART_NUMBER; ?></td>
                    </tr>
                    
                    <tr>
                        <td>Part Deskripsi</td>
                        <td>: <?php echo $list->message[0]->PART_DESKRIPSI; ?></td>
                    </tr>

                </table>

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
                            <th>Kode Part</th>
                            <th>Part Deskripsi</th>
                            <th>Harga Beli</th>
                            <th>Dealer</th>
                            <th>Tanggal</th>
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

                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $row->PART_NUMBER; ?></td>
                                        <td><?php echo $row->PART_DESKRIPSI; ?></td>
                                        <td class="text-right"><?php echo number_format($row->PRICE, 0); ?></td>
                                        <td><?php echo $row->NAMA_DEALER; ?></td>
                                        <td><?php echo $row->CREATED_TIME; ?></td>
                                        <td><?php echo $row->ROW_STATUS == 0 ? 'Aktif' : 'Tidak Aktif'; ?></td>
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