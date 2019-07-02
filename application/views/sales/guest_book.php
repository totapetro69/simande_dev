<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_delaer"))?$this->input->get("kd_delaer"):($this->session->userdata("kd_dealer"));
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
?>
<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <div class="btn-group">
                <a class="btn btn-default <?php echo $status_c; ?>"  role="button" href="<?php echo base_url('customer/add_guest_book'); ?>">  <!--onclick='addForm("< ?php echo base_url('motor/add_segmen_motor'); ?>");'-->
                    <i class="fa fa-file-o fa-fw"></i> Input Guest Book
                </a>
            </div>

            <div class="btn-group">
                <a role="button" href="<?php echo base_url("customer/guest_book_download"); ?>" class="btn btn-default <?php echo $status_v; ?>">
                    <i class="fa fa-arrow-circle-right"></i> Download File
                </a>
            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class='fa fa-list-ul'></i> Guest Book
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="filterForm" action="<?php echo base_url('customer/guest_book') ?>" class="bucket-form">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nama Dealer</label>
                                    <select class="form-control " id="kd_dealer" name="kd_dealer">>
                                        <option value="0">--Pilih Dealer--</option>
                                        <?php
                                        if (isset($dealer)) {
                                            if (($dealer->totaldata >0)) {
                                                foreach ($dealer->message as $key => $value) {
                                                    $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                    //$aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
                                                    echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                                }
                                            }
                                        }
                                        ?> 
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Field Pencarian</label>
                                    <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan Nama Customer, Nama Sales, Type Motor, Warna Motor, atau Alamat" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
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
                            <th>Nama Customer</th>
                            <th>Alamat</th>
                            <th>&nbsp;</th>
                            <th>No. Telp</th>
                            <th>Tgl. Berkunjung</th>
                            <th>Type Motor</th>
                            <th>Nama Sales</th>
                            <th>Satus Deal</th>
                            <th>Test Drive</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $status_deal="hidden";
                        if ($list) {
                            $no = 0;
                            if (is_array($list->message)) {
                                foreach ($list->message as $key => $value) {
                                    # code...
                                    $hpus=($value->HAS_SPK=='')?'':'hidden';
                                    $title="";$titlex="";
                                    $no++;
                                    $status_deal="hidden";
                                    switch($value->STATUS){
                                        case 'Deal':
                                        case 'Deal Indent':
                                        if($value->HAS_SPK==''){ 
                                            $status_deal="";
                                            $title="Click untuk proses SPK";
                                        }else{ 
                                            $status_deal="disabled-action";
                                            $title =" Sudah di buat spk No.SPK: ".$value->HAS_SPK;
                                        }
                                        break;
                                        default:
                                        $status_deal="hidden";
                                        break;
                                    }
                                    ?>

                                    <tr title="" id="<?php echo  $this->session->flashdata('tr-active') == $value->GUEST_NO ? 'tr-active' : ' '; ?>">
                                        <td class="table-nowarp text-center"><?php echo $no; ?></td>
                                        <td class="table-nowarp">
                                            <a id="modal-button"  class="<?php echo  $status_v ?>"  href='<?php echo base_url() . "customer/guestbook_edit?n=".urlencode(base64_encode( $value->GUEST_NO)) ;?>') role="button" >
                                                <i data-toggle="tooltip" data-placement="left" title="Ubah Data customer" class="fa fa-edit fa-fw"></i>
                                            </a>
                                            <a href="<?php echo base_url('customer/guestbook_detail/' .  $value->GUEST_NO); ?>" role="button" class="<?php echo  $status_v ?> hidden">
                                                <i data-toggle="tooltip" data-placement="left" title="Detail" class="fa fa-clipboard text-success text-active"></i>
                                            </a>
                                            <a id="delete-btn" class="delete-btn <?php echo $hpus;?> <?php echo  $status_e ?>" url="<?php echo base_url('customer/guestbook_delete/' . $value->GUEST_NO); ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Hapus data guest no:<?php echo $value->GUEST_NO; ?>" class="fa fa-trash text-danger text"></i>
                                            </a>
                                            <a class="<?php echo $status_deal;?>" role="button" href="<?php echo base_url()."spk/add_spk?g=".urlencode(base64_encode($value->GUEST_NO));?>" title="<?php echo $title;?>"><i class="fa fa-cogs"></i> </a>

                                        </td>
                                        <?php $alamat=$value->ALAMAT." ".$value->NAMA_DESA.", Kec. ".$value->NAMA_KECAMATAN." Kab. ".$value->NAMA_KABUPATEN;?>
                                        <?php $titlex = ($value->HAS_SPK)?"<a href='".base_url()."spk/add_spk?n=".urlencode(base64_encode($value->HAS_SPK))."' target='_blank'>".str_replace("\'","'",strtoupper($value->NAMA_CUSTOMER))."</a>":str_replace("\'","'",strtoupper($value->NAMA_CUSTOMER));?>
                                        <td class="table-nowarp" title="<?php echo $value->GUEST_NO;?>"><?php echo str_replace("\'","'",strtoupper($value->NAMA_CUSTOMER)); ?></td>
                                        <td class="td-overflow" title="<?php echo $alamat; ?>"><?php echo str_replace("\'","'",strtoupper($value->ALAMAT))." ".$value->NAMA_DESA; ?></td>
                                        <td class="td-overflow" title="<?php echo $alamat; ?>"><?php echo $value->NAMA_KECAMATAN." Kab. ".$value->NAMA_KABUPATEN; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NO_HP; ?></td>
                                        <td class="table-nowarp"><?php echo TglFromSql($value->TANGGAL); ?></td>
                                        <td class='table-nowarp'><?php echo $value->KD_ITEM; ?></td>
                                        <td class="table-nowarp"><?php echo $value->NAMA_SALES; ?></td>
                                        <td class="table-nowarp" title="<?php echo $value->KETERANGAN; ?>"><?php echo $value->STATUS; ?></td>
                                        <td class="table-nowarp"><?php echo $value->TEST_DRIVE; ?></td>
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
    <?php echo loading_proses(); ?>
</section>