<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right">
             <a id="modal-button" class="btn btn-default" onclick='addForm("<?php echo base_url('Setup/add_minimal'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-file-o fa-fw"></i> Baru
            </a>

        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                Setup Minimal Value
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('Setup/minimal') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('Setup/minimal_typeahead'); ?>"></div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-8">
                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select class="form-control" id="kd_dealer" name="kd_dealer">
                                    <option value="0">--Pilih Dealer--</option>
                                    <?php
                                    if ($dealer) {
                                      if (($dealer->totaldata > 0)) {
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
                        <div class="col-xs-12 col-sm-4">

                            <div class="form-group">
                                <label>Status</label>
                                <select id="row_status" name="row_status" class="form-control">
                                    <option value="0" <?php echo ($this->input->get('row_status') == 0 ? "selected" : ""); ?>>Aktif</option>
                                    <option value="-1" <?php echo ($this->input->get('row_status') == -1 ? "selected" : ""); ?>>Tidak Aktif</option>
                                    <option value="-2" <?php echo ($this->input->get('row_status') == -2 ? "selected" : ""); ?>>Semua</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12 padding-left-right-10">
    <div class="panel panel-default">
        <div class="table-responsive h250">
            
            <table class="table table-striped b-t b-light">
                <thead>
                    <tr>
                        <th style="width:40px;">No.</th>
                        <th style="width:45px;">Aksi</th>
                        <th>Dealer</th>
                        <th>Kode</th>
                        <th>Nama Transaksi</th>
                        <th>Min Value</th>
                        <!-- <th>Max Value</th> -->
                        <th>Keterangan</th>
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
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td class="table-nowarp">
                                            <a id="modal-button" onclick='addForm("<?php echo base_url('setup/edit_minimal/'.$row->ID.'/'.$row->ROW_STATUS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                                                <i data-toggle="tooltip" data-placement="left" title="edit" class="fa fa-edit text-success text-active"></i>
                                            </a>
                                            <?php 
                                            if($row->ROW_STATUS == 0){ 
                                            ?>
                                            <a id="delete-btn<?php echo $no;?>" class="delete-btn" url="<?php echo base_url('setup/delete_minimal/'.$row->ID); ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="hapus" class="fa fa-trash text-danger text"></i>
                                            </a>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    <td><?php echo $row->KD_DEALER ?></td>
                                    <td><?php echo $row->KD_TRANS ?></td>
                                    <td><?php echo $row->NAMA_TRANS ?></td>
                                    <td><?php echo number_format($row->MIN_VALUE,0); ?></td>
                                    <!-- <td><?php echo number_format($row->MAX_VALUE,0);?></td> -->
                                    <td><?php echo $row->KETERANGAN;?></td>
                                    <td><?php echo  $row->ROW_STATUS == 0 ? 'Aktif' : 'Tidak Aktif'; ?></td>
                                </tr>

                                <?php
                            endforeach;
                        else:
                            ?>
                            <tr>
                                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                <td colspan="6"><b><?php echo ($list->message); ?></b></td>
                            </tr>
                        <?php
                        endif;
                    else:
                        ?>
                        <tr>
                            <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                            <td colspan="6"><b>Ada error, harap hubungi bagian IT</b></td>
                        </tr>
                    <?php
                    endif;
                    ?>
                </tbody>
            </table>
        </div>

        <footer class="panel-footer">
            <div class="row">

                
            </div>
        </footer>
    </div>
</div>
<div id="printarea" style="height: 0.5px; overflow: hidden;width: 100% !important">
    <table style="width:100%; border-collapse: collapse;" border="0">
        <tr>
            <td style="width:100%; padding: 5px">
                <table style="width:100%; border-collapse: collapse;">
                    <tr>
                        <td style="width:10%;" valign="top"><h4><?php echo $namadealer;?></h4></td>
                        <td style="width:40%" align="center" valign="middle"><h4>LAPORAN BACK ORDER</h4></td>
                        <td style="width:15%; white-space: nowrap;" valign="top">Tanggal Cetak </td>
                        <td style="width:15%; white-space: nowrap;" valign="top">: <?php echo date('d/m/Y');?></td>
                    </tr>
                    <tr><td>&nbsp;</td><td align="center" valign="middle"><?php switch ($this->input->get("tp")) {
                                case '0':
                                   echo "Out Standing PO Main Dealer";
                                    break;
                                case '1':
                                   echo "Out Standing SO Dealer";
                                    break;
                                
                                default:
                                    # code...
                                    break;
                            }
                            ?></td>
                        <td style="width:15%; white-space: nowrap;" valign="top">Periode Laporan </td>
                        <td style="width:15%; white-space: nowrap;" valign="top">: <?php echo $periodelap;?></td>
                    </tr>
                    <tr><td colspan="4">&nbsp;</td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td valign="top" style="padding: 5px;">
                <table style="width:100%; border-collapse: collapse;" border="1">
            <thead>
                <tr>
                    <th style="width:40px;">No.</th>
                    <th>Part Number</th>
                    <th>Part Deskripsi</th>
                    <th>Jumlah Order</th>
                    <th>Jumlah Supply</th>
                    <th>Out Standing</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 0;
                if (isset($list)):
                    if ($list->totaldata):
                        foreach ($list->message as $key => $row):
                            $no ++;
                            ?>

                            <tr>
                                <td align="center"><?php echo $no; ?></td>
                                <td><?php echo $row->PART_NUMBER ?></td>
                                <td style="white-space: nowrap;"><?php echo $row->PART_DESKRIPSI ?></td>
                                <td align="right" style="padding-right: 5px"><?php echo number_format($row->JUMLAH_ORDER,0); ?></td>
                                <td align="right" style="padding-right: 5px"><?php echo number_format($row->JUMLAH_SUPPLY,0);?></td>
                                <td align="right" style="padding-right: 5px"><?php echo number_format($row->SISA,0);?></td>
                            </tr>

                            <?php
                        endforeach;
                    else:
                        ?>
                        <tr>
                            <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                            <td colspan="6"><b><?php echo ($list->message); ?></b></td>
                        </tr>
                    <?php
                    endif;
                else:
                    ?>
                    <tr>
                        <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                        <td colspan="6"><b>Ada error, harap hubungi bagian IT</b></td>
                    </tr>
                <?php
                endif;
                ?>
            </tbody>
        </table>
            </td>
        </tr>
    </table>
</div>
</section>
<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
    
    function printKw() {
        printJS({ printable: 'printarea', type: 'html', honorColor: true });
    }
</script>   