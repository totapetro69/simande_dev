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

            <a class="btn btn-default <?php echo $status_p ?>" id="modal-button" onclick='addForm("<?php echo base_url('report/report_ksu_print?tgl_awal=' . $this->input->get("tgl_awal") . '&tgl_akhir=' . $this->input->get("tgl_akhir")); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Penerimaan" ></i> Cetak
            </a>    

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                Dealer
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="penerimaanForm" action="<?php echo base_url('report/report_ksu') ?>" class="bucket-form" method="get">

                    <div class="row">

                        <div class="col-xs-12 col-sm-4 col-md-4">

                            <div class="form-group">
                                <label>Dealer</label>
                                <select name="kd_dealer" id="kd_dealer" class="form-control"  required="true">
                                    <option value="">- Pilih Dealer -</option>
                                    <?php
                                    foreach ($dealer->message as $key => $group) :
                                        if ($KD_DEALER != ''):
                                            $default = ($KD_DEALER == $group->KD_DEALER) ? " selected" : " ";
                                        else:
                                            $default = ($this->session->userdata("kd_dealer") == $group->KD_DEALER) ? " selected" : '';
                                        endif;
                                        ?>
                                        <option value="<?php echo $group->KD_DEALER; ?>" <?php echo $default; ?> ><?php echo $group->NAMA_DEALER; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-3">
 
                            <div class="form-group">
 
                                <label class="control-label" for="date">Periode Awal</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
 
                            </div>
 
                        </div>
 
                        <div class="col-xs-12 col-sm-3 col-md-3">
 
                            <div class="form-group">
 
                                <label class="control-label" for="date">Periode Akhir</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
 
                            </div>
 
                        </div>
                        
                        <div class="col-xs-12 col-sm-2 col-md-2">

                            <div class="form-group">

                                <br>
                                <button id="submit-btn" onclick="" class="btn btn-primary"><i class="fa fa-search"></i> Preview</button>

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
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
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
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
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

    $(document).ready(function (e) {
        
    });
    

    var min = new Date().getFullYear(),
            max = min - 2,
            select = document.getElementById('tahun');

    for (var i = min; i >= max; i--) {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        select.appendChild(opt);
    } 

    $('#bulan').change(function () {
        var year = $('#tahun').val();
        var month = $(this).val();
        var day = 1;

        var bulan = new Date(year, month, day);
        var bulan2 = bulan.getMonth() + 1;//new Date(bulan).setMonth(bulan.getMonth()+1);
        var bulan3 = (bulan.getMonth() + 1) + 1;
//        var lastday = bulan3.getDate()-1;
        
        var date = new Date(bulan).setMonth(bulan.getMonth() + 1);
        var year2 = bulan.getFullYear();
        var year3 = bulan.getFullYear();
        if (bulan3 == 13) {
            bulan3 = 1;
            year3 = year3 + 1;
        }

    });

    $('#tahun').change(function () {
        var year = $(this).val();
        var month = $('#bulan').val();
        var day = 1;
        var bulan = new Date(year, month, day);
        var bulan2 = bulan.getMonth() + 1;//new Date(bulan).setMonth(bulan.getMonth()+1)   

    });
</script>
