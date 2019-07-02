<?php
if (!isBolehAkses()) {  redirect(base_url() . 'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer =($this->session->userdata("kd_dealer"));
$status_n = ($this->session->userdata("nama_group")=="Root")?"":"disabled='disabled'";
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right ">
            <div class="btn-group">
                <a class="btn btn-default <?php echo $status_c;?>"  role="button" href="<?php echo base_url('customer/add_list_appointment'); ?>");'>
                    <i class="fa fa-file-o fa-fw"></i> Input Appointment
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class='fa fa-list-ul'></i> List Appointment
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
                <form id="filterForm" action="<?php echo base_url('customer/list_appointment') ?>" class="bucket-form">
                    <div id="ajax-url" url="<?php echo base_url('customer/list_appointment_typeahead'); ?>"></div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Nama Dealer</label>
                               <select class="form-control " id="kd_dealer" name="kd_dealer" <?php echo $status_n;?>>
                                    <option value="0">--Pilih Dealer--</option>
                                    <?php
                                    if ($dealer) {
                                        if (is_array($dealer->message)) {
                                            foreach ($dealer->message as $key => $value) {
                                                $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                $aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
                                                echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                            }
                                        }
                                    }
                                    ?> 
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="date">Tanggal Janji Dari </label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d/m/Y', strtotime('0 day')); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group hidden">
                                <label>Include</label>
                                <select id="pm" name="pm" class="form-control">
                                    <option value="">Janji Hari Ini Saja</option>
                                    <option value="3"> 3 Hari Ke Depan</option>
                                    <option value="5"> 5 Hari Ke Depan</option>
                                    <option value="3P"> 3 Hari Sebelum</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group hidden">
                                <label>Filter By</label>
                                <select id="f" name="f" class="form-control">
                                    <option value="">&nbsp;</option>
                                    <option value="bd"> Belum Datang</option>
                                    <option value="sd"> Sudah Datang</option>
                                    <option value="td"> Tidak Datang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
                             <div class="form-group">
                                <label>Search By </label>
                                <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Nama customer" autocomplete="off">
                            </div>                                                                                             
                        </div>
                    </div>
                   <!--  <div class="row">
                        
                    </div> -->

                </form>

            </div>

        </div> 

    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel panel-default">
            <div class="table-responsive h350">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Aksi</th>
                            <th>Tanggal Janji</th>
                            <th>Jenis Appointment</th>
                            <th>Dihubungi Via</th>
                            <th>Nama Customer</th>
                            <th>No. HP</th>
                            <th>Nama Sales</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if (isset($list)) {
                            $no = $this->input->get('page');
                            if ($list->totaldata>0) {
                                foreach ($list->message as $key => $value) {
                                    $datang ="";
                                    $tlgJanji =date_create($value->TANGGAL_JANJI);
                                    $datang =date_diff(date_create(date('Ymd')),$tlgJanji,false);
                                    $datang1 = ($datang->invert)?-1*$datang->days:$datang->days;
                                    $sts =(strlen($value->GUEST_NO)>4)?"Datang":"";
                                    $sts =($sts=="" && (int)$datang1<0)?"Tdk Datang":$sts;
                                    $no++;
                                    $sts=($sts=='Datang')?"<abbr title ='Datang Tanggal: ".tglFromSql($value->TGL_DATANG).",\nDiterima Oleh : ".$value->SALES_PENERIMA."'>Datang</abbr>":$sts;
                                    ?>

                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp">
                                            <a id="modal-button"  class="<?= $status_v ?>"  href='<?php echo base_url() . "customer/add_list_appointment?n=" . urlencode(base64_encode($value->NO_TRANS)); ?>')" role="button" >
                                                <i data-toggle="tooltip" data-placement="left" title="Ubah Data customer" class="fa fa-edit fa-fw"></i>
                                            </a>
                                            <a id="delete-btn" class="delete-btn <?php echo ($sts)?'disabled-action':'';?>" url="<?php echo base_url('customer/delete_list_appointment/' . $value->ID); ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Hapus data list appointment:<?php echo $value->NO_TRANS; ?>" class="fa fa-trash text-danger text"></i>
                                            </a>
                                        </td>
                                        <!-- <td><?php echo $value->GUEST_NO; ?></td> -->
                                        <td class="table-nowarp"><?php echo tglFromSql($value->TANGGAL_JANJI); ?></td>
                                        <td class="td-overflow" title="<?php echo $value->JENIS_APPOINTMENT; ?>"><?php echo $value->JENIS_APPOINTMENT; ?></td>
                                        <td class="table-nowarp"><?php echo $value->HUBUNGI_VIA; ?></td>
                                        <td class="table-nowarp"><?php echo str_replace("\'","'",$value->NAMA_CUSTOMER); ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_HP; ?></td>
                                        <td><?php echo $value->NAMA_SALES; ?></td>
                                        <td class="table-nowarp"><?php echo $sts?></td>
                                    </tr>
                                    <?php
                                }
                            }else{
                              belumAdaData(9);  
                            }
                        }else{
                            belumAdaData(9);
                        }
                        ?>
                    </tbody>
                    
                </table>
                
            </div>
            
        </div>
        
        <div class="panel-footer"> 
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
            
        </div>
        
    </div>
<?php echo loading_proses();?>
</section>