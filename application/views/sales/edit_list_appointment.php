<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$status_ce= (isBolehAkses('c') || isBolehAkses('e'))? '' : ' disabled-action';

$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$no_trans
?>

<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right ">
             <a class="btn btn-default <?php echo $status_c;?>" href="<?php echo base_url('customer/add_list_appointment');?>"><i class="fa fa-file-o fa-fw"></i> Tambah Appointment </a>
                <a id="submit-btn" type="button" onclick="addData();" class="btn btn-default submit-btn <?php echo $status_ce;?>"><i class="fa fa-save fa-fw"></i> Update</a>
                <a href="<?php echo base_url('customer/list_appointment');?>" class="btn btn-default $status_v"><i class="fa fa-list"></i> List Appointment</a>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class='fa fa-users'></i> List Appointment <?php echo $list->message[0]->NO_TRANS;?>
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>

                </span>
            </div>
            <div class="panel-body panel-body-border">
                <form class="bucket-form" id="addForm" method="post" action="<?php echo base_url('customer/update_list_appointment/'. $list->message[0]->NO_TRANS); ?>">
                    <!-- baris ke 1 -->
                    <div class="row">
                        <!-- nama customer -->
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nama Dealer</label>
                                    <select class="form-control" id="kd_dealer" name="kd_dealer">
                                        <option value="0">--Pilih Dealer--</option>
                                        <?php
                                        if ($dealer) {
                                            if (is_array($dealer->message)) {
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

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>No. Trans</label>
                                    <input type="text" name="no_trans" id="no_trans" placeholder="AUTO GENERATE" readonly class="form-control" value="<?php echo $list->message[0]->NO_TRANS; ?>">
                                </div>
                            </div>

                        </div>      
                    </div>
                    <?php //var_dump($customer);?>
                    <!-- baris ke 2 -->
                    <div class="row">    
                        <!-- alamat -->
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div id="ajax-url-customer" url="<?php echo base_url('customer/customer_autocomplete/'.$defaultDealer.'/'); ?>"></div>
                                    <label>Nama Customer </label>
                                    <div class="input-group">
                                        <input type="text" name="nama_customer" value="<?php echo $list->message[0]->NAMA_CUSTOMER; ?>" id="nama_customer" class="form-control" placeholder="Masukkan Nama Customer" autocomplete="off" required >
                                        <span class="input-group-btn disabled-action">
                                            <button type="button" id="modal-button-3" class='btn btn-info' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                    <input type="hidden" name="kd_customer" value='<?php echo $list->message[0]->KD_CUSTOMER; ?>'>
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Jenis Appointment</label>
                                    <select name="jenis_appointment" id="jenis_appointment" class="form-control">
                                        <option value="">--Pilih Jenis Appointment--</option>
                                        <option value="Riding Test" <?php echo($list->message[0]->JENIS_APPOINTMENT=="Riding Test")?"selected":"";?>>Riding Test</option>
                                        <option value="Pembelian Unit" <?php echo($list->message[0]->JENIS_APPOINTMENT=="Pembelian Unit")?"selected":"";?>>Pembelian Unit</option>
                                        <option value="Ambil BPKB" <?php echo($list->message[0]->JENIS_APPOINTMENT=="Ambil BPKB")?"selected":"";?>>Ambil BPKB</option>
                                        <option value="Dll" <?php echo($list->message[0]->JENIS_APPOINTMENT=="Dll")?"selected":"";?>>Dan Lain-Lain</option>
                                    </select>
                                </div>      
                            </div>

                        </div>
                    </div>

                    <!--Baris Ke-3 -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Alamat <span id="l_alamat"></span></label>
                                    <textarea type="text" rows="4"  name="alamat" id="alamat" class="form-control" placeholder="Masukkan Nama Alamat" required="required"><?php echo  $list->message[0]->ALAMAT; ?></textarea>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Via Penghubung</label>
                                    <select class="form-control" id="nama_metode" name="nama_metode" required>
                                        <option value="" >- Pilih  -</option>
                                        <?php
                                        if ($metodefus):
                                            foreach ($metodefus->message as $key => $metodefu) :
                                                ?>
                                                <option value="<?php echo  $metodefu->NAMA_METODE; ?>" <?php echo ($metodefu->NAMA_METODE == $list->message[0]->HUBUNGI_VIA ? "selected" : "");?>><?php echo  $metodefu->NAMA_METODE; ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nama Sales</label>
                                    <select class="form-control" name="kd_sales" id="kd_sales">
                                        <option value="" >- Pilih Sales -</option>
                                        <?php if($sales && (is_array($sales->message) || is_object($sales->message))): foreach ($sales->message as $key => $value) : ?>
                                         <option value="<?php echo $value->KD_SALES;?>" <?php echo ($value->KD_SALES == $list->message[0]->KD_SALES ? "selected" : "");?>><?php echo $value->NAMA_SALES;?></option>
                                     <?php endforeach; endif;?>
                                 </select>
                             </div>
                         </div>
                     </div>
                 </div>

                     <!--Baris ke-4-->
                     <!-- propinsi -->
                     <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="col-sm-3">
                             <div class="form-group">
                                <label>Propinsi</label>
                                <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi">
                                    <option value="0">--Pilih Propinsi--</option>
                                    <?php
                                    if ($propinsi) {
                                        if (is_array($propinsi->message)) {
                                            foreach ($propinsi->message as $key => $value) {
                                                $select=($list->message[0]->KD_PROPINSI == $value->KD_PROPINSI)?"selected":"";
                                                echo "<option value='" . $value->KD_PROPINSI . "' ".$select.">" . $value->NAMA_PROPINSI . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Kabupaten <span id="l_kabupaten"></span></label>
                                <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten">
                                    <option value="0">--Pilih Kabupaten--</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                           <div class="form-group">
                            <label>Tanggal Janji</label>
                            <div class="input-group input-append date" id="date">
                                <input type="text" class="form-control" id="tanggal_janji" name="tanggal_janji" value="<?php echo ($list->message[0]->TANGGAL_JANJI!='')?tglfromSql($list->message[0]->TANGGAL_JANJI): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="col-sm-3">
                     <div class="form-group">
                        <label>Kecamatan <span id="l_kecamatan"></span></label>
                        <select class="form-control" id="kd_kecamatan" name="kd_kecamatan" title="kecamatan">
                            <option value="0">--Pilih Kecamatan-- </option>
                        </select>
                    </div> 
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Kelurahan <span id="l_desa"></span></label>
                        <select class="form-control" id="kd_desa" name="kd_desa" title="desa">
                            <option value="0">--Pilih Kelurahan--</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Jam Janji</label>
                        <div class="input-group input-append datetime-mulai" id="datetime">
                            <input class="form-control" id="jam_janji" name="jam_janji" placeholder="HH:MM" value="<?php echo $list->message[0]->JAM_JANJI; ?>" type="text"/>
                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-time"></span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>No. Telphone/HP</label>
                        <input type="text" name="hp_customer" id="hp_customer" class="form-control" placeholder="Masukkan nomor telpon atau HP" value="<?php echo  $list->message[0]->NO_HP; ?>">
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                       <label>Keterangan</label>
                       <textarea class="form-control" id="keterangan" name="keterangan"><?php echo  $list->message[0]->KETERANGAN; ?></textarea>
                   </div>
               </div>
           </div>
       </form>
   </div>
</div>
</div>
</section>

<script type="text/javascript">
    $(document).ready(function(){

        loadData('kd_kabupaten', $('#kd_propinsi').val(), "<?php echo $list->message[0]->KD_KABUPATEN;?>");
        loadData('kd_kecamatan', $(this).val(), "<?php echo $list->message[0]->KD_KECAMATAN;?>");
        loadData('kd_desa', $(this).val(), "<?php echo $list->message[0]->KD_DESA;?>")
        /*pilihan propinsi*/
        $('#kd_propinsi').on('change', function () {
            loadData('kd_kabupaten', $('#kd_propinsi').val(), "<?php echo $list->message[0]->KD_KABUPATEN;?>")
        })
        $('#kd_kabupaten').on('change', function () {
            loadData('kd_kecamatan', $(this).val(), "<?php echo $list->message[0]->KD_KECAMATAN;?>")
        })
        $('#kd_kecamatan').on('change', function () {
            loadData('kd_desa', $(this).val(), "<?php echo $list->message[0]->KD_DESA;?>")
        })

        $("#submit-btn").on('click',function(event){
            var formId = '#'+$(this).closest('form').attr('id');
            var btnId = '#'+this.id;
            $('#loadpage').removeClass("hidden");

            $(formId).validate({
                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                errorElement: 'span',
                errorClass: 'help-block',
                errorPlacement: function(error, element) {
                    if(element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
            if (jQuery(formId).valid()) {
                // Do something
                event.preventDefault();

                addValid(formId, btnId);

            }else{
                $('#loadpage').addClass("hidden");
                $(window).scrollTop($('.form-group').hasClass('has-error').offset().top);
            }
        });

        var date = new Date();
        date.setDate(date.getDate());

        $('.datetime-mulai').datetimepicker({
            format: 'LT',
        locale: 'ru'
        });
    })

    function loadData(id, value, select) {

        var param = $('#' + id + '').attr('title');
        $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
        var urls = "<?php echo base_url(); ?>master_service/" + param;
        var datax = {"kd": value};
        $('#' + id + '').attr('disabled','disabled');
        $.ajax({
            type: 'POST',
            url: urls,
            data: datax,
            typeData: 'html',
            success: function (result) {
                $('#' + id + '').html('');
                $('#' + id + '').html(result);
                $('#' + id + '').val(select).select();
                $('#l_' + param + '').html('');
                $('#' + id + '').removeAttr('disabled');
            }
        });
/*
        var date = new Date();
        date.setDate(date.getDate());*/

        
    }
</script>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/appointment.js");?>"></script>