<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : ($this->session->userdata("kd_dealer"));
//var_dump($list);exit;
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>

    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                <i class="fa fa-search"></i> List PO Submitted
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border">
                <form id="filterForm" action="<?php echo base_url('purchasing/monitoringpopart') ?>" class="bucket-form" method="get">
                    <div id="ajax-url" url="<?php echo base_url('purchasing/posp_typeahead'); ?>"></div>
                    <div class="row">
                        <div class="col-xs-4 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Dealer</label>
                                <select name="kd_dealer" id="kd_dealer" class="form-control">
                                    <option value="">- ALL -</option>
                                    <?php
                                    foreach ($dealer->message as $key => $group) {
                                        $pilih = ($defaultDealer == $group->KD_DEALER) ? ' selected' : '';
                                        ?>
                                        <option value="<?php echo $group->KD_DEALER; ?>" <?php echo $pilih; ?>><?php echo $group->NAMA_DEALER; ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div> 	
                        <div class="col-xs-6 col-md-2 col-sm-2">
                            <div class='form-group'>
                                <label>Periode Bulan</label>
                                <select class="form-control" name="bulan" id="bulan">
                                    <option value ="">-Pilih Bulan-</option>
                                    <option value ="1">Januari</option>
                                    <option value ="2">Februari</option>
                                    <option value ="3">Maret</option>
                                    <option value ="4">April</option>
                                    <option value ="5">Mei</option>
                                    <option value ="6">Juni</option>
                                    <option value ="7">Juli</option>
                                    <option value ="8">Agustus</option>
                                    <option value ="9">September</option>
                                    <option value ="10">Oktober</option>
                                    <option value ="11">November</option>
                                    <option value ="12">Desember</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-2 col-sm-2">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select class="form-control" name="tahun" id="tahun">
                                    <option value="">- Pilih Tahun -</option>
                                    <?php
                                    for ($a = 2019; $a > 1970; $a--) {
                                        echo "<option value = '$a'>$a</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-2 col-md-2">
                            <div class="form-group">
                                <label>Jenis PO</label>
                                <select name="jenis_po" id="jenis_po" class="form-control">
                                    <option value="">- All -</option>								  
                                    <option value="Reguler" >REGULAR</option>
                                    <option value="Hotline" >HOTLINE</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Field Cari</label>
                                <input type="text" id="keyword" autocomplete="off" name="keyword" class="form-control" placeholder="Nomor PO" >
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
                            <th>No.</th>
                            <th>No. PO</th>
                            <th>PO Periode</th>
                            <th>Unit Qty.</th>
                            <th>Tipe PO</th>
                            <th>Tanggal PO</th>
                            <th>Status PO</th>
                            <th>Fullfilment Rate</th>
                            <th>Tanggal Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if (isset($list)) {
                            if ($list->totaldata > 0) {
                                foreach ($list->message as $key => $value) {
                                    $tanggalClosed = "Not yet";
                                    $poOpen = "none";
                                    $poSubmitted = "none";
                                    $poProcessed = "none";
                                    $poClosed = "none";
                                    $poRejected = "none";

                                    if ($value->APPROVAL == 0) {
                                        $poOpen = "blue";
                                    }

                                    if ($value->APPROVAL == 1) {
                                        $poOpen = "blue";
                                        $poSubmitted = "blue";
                                    }

                                    if ($value->APPROVAL == 2) {
                                        $poOpen = "blue";
                                        $poSubmitted = "blue";
                                        $poProcessed = "blue";
                                    }

                                    if ($value->APPROVAL == 3) {
                                        $poOpen = "blue";
                                        $poSubmitted = "blue";
                                        $poProcessed = "blue";
                                        $poClosed = "blue";
                                        $tanggalClosed = $value->APPROVAL_DATE;
                                    }

                                    if ($value->APPROVAL == -1) {
                                        $poRejected = "red";
                                    }


                                    $no++;
                                    ?>
                                    <tr>
                                        <td class="table-nowarp"><?php echo $no; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_PO; ?></td>
                                        <td class="table-nowarp"><?php echo date("F Y", strtotime($value->TGL_PO)); ?></td>
                                        <td class="table-nowarp"><?php echo intval($value->QTY_ORDER); ?></td>
                                        <td class="table-nowarp"><?php echo $value->JENIS_PO; ?></td>
                                        <td class="table-nowarp"><?php echo tglfromSql($value->TGL_PO); ?></td>
                                        <td class="table-nowarp">
                                            <span class = "badge" style = "background-color:<?php echo $poOpen; ?>">OPEN</span>
                                            <span class = "badge" style = "background-color:<?php echo $poSubmitted; ?>">SUBMITTED</span>
                                            <span class = "badge" style = "background-color:<?php echo $poProcessed; ?>">PROCESSED</span>
                                            <span class = "badge" style = "background-color:<?php echo $poClosed; ?>">CLOSED</span>
                                            <span class = "badge" style="background-color: <?php echo $poRejected; ?>">REJECTED</span>
                                        </td>
                                        <td class="table-nowarp">
                                            <?php
                                            if ($value->QTY_FULLFILMENT > 0) {
                                                echo intval(($value->QTY_FULLFILMENT / $value->QTY_ORDER) * 100) . "%";
                                            } else {
                                                echo "-";
                                            }
                                            ?>
                                        </td>
                                        <td class="table-nowarp">
                                            <?php echo tglFromSql($tanggalClosed); ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                belumAdaData();
                            }
                        } else {
                            belumAdaData();
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <footer class="panel-footer">
                <div class="row">

                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 

                            <?php echo ($list != NULL) ? ($list->totaldata == '') ? "" : "<i>Total Data " . $list->totaldata . " items</i>" : "" ?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo $pagination; ?>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script type="text/javascript">

        $(document).ready(function (e) {
            var jenis_po = getParameterByName('jenis_po');
            var bulan = getParameterByName('bulan');
            var tahun = getParameterByName('tahun');
            $("select[name='jenis_po']").val(jenis_po);
            $("select[name='bulan']").val(bulan);
            $("select[name='tahun']").val(tahun);
            var keyword = getParameterByName('keyword');
            if (keyword != '') {
                $("select[name='jenis_po']").val('');
                $("select[name='bulan']").val('');
                $("select[name='tahun']").val('');
            }

        });

        function getParameterByName(name) {
            var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
            return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
        }
    </script>
</section>