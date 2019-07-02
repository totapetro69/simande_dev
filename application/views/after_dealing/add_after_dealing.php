<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$kd_lokasi = ($this->input->get("kd_lokasidealer"))?$this->input->get("kd_lokasidealer"):$this->session->userdata("kd_lokasidealer");

// $no_trans = base64_decode(urldecode($this->input->get("u")));
$no_trans = $this->input->get("u");
$tgl_trans = date('d/m/Y');
$kd_customer="";
$nama_customer="";
$no_spk = "";
$no_hp="";
$no_rangka="";
$no_mesin="";

if(isset($list)){
    if($list->totaldata >0){
        foreach ($list->message as $key => $value) {
            $no_trans           = $value->NO_TRANS;
            $kd_customer        = $value->KD_CUSTOMER;
            $no_spk             = $value->NO_SPK;
            $no_rangka          = $value->NO_RANGKA;
            $no_mesin           = $value->NO_MESIN;
            $tgl_trans          = TglFromSql($value->TGL_TRANS);
            $nama_customer      = $value->NAMA_CUSTOMER;
            $no_hp              = $value->NO_HP;
        }
    }
}

$lock = ($no_trans)?'disabled-action':'';

?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right ">

            <!-- <div class="btn-group"> -->
                <a class="btn btn-default" href="<?php echo base_url('after_dealing/add_after_dealing'); ?>">
                  <i class="fa fa-file-o fa-fw"></i> Add Activity
                </a>
                <a id="submit-btn" type="button" class="btn btn-default submit-btn <?php echo $status_c; ?>">  
                    <i class="fa fa-save fa-fw"></i> Simpan
                </a>

                <a class="btn btn-default" href="<?php echo base_url('after_dealing/list_activity_afterdealing'); ?>" role="button">
                    <i class="fa fa-table fa-fw"></i> List Penerimaan
                </a>
            <!-- </div> -->

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class='fa fa-list-ul'></i> Add Activity After Dealing
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">

            <form class="bucket-form" id="addAfterdealingform" method="post" action="<?php echo base_url("after_dealing/simpan_after_dealing"); ?>" autocomplete="off">

            <input type="hidden" name="tgl_trans" id="tgl_trans" value="<?php echo $tgl_trans;?>" class="form-control">
            <input type="hidden" name="kd_customer" id="kd_customer" value="<?php echo $kd_customer;?>" required>
            <input type="hidden" name="no_rangka" id="no_rangka" value="<?php echo $no_rangka;?>" required>
            <input type="hidden" name="no_mesin" id="no_mesin" value="<?php echo $no_mesin;?>" required>


            <div class="row">
                <div class="col-xs-6 col-md-2 col-sm-2">
                    <div class="form-group">
                        <label>Nama Dealer</label>
                        <select class="form-control <?php echo $lock;?>" id="kd_dealer" name="kd_dealer">
                            
                            <?php
                            if (isset($dealer)) {
                                if ($dealer->totaldata > 0) {
                                    foreach ($dealer->message as $key => $value) {
                                        $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                                        $select = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $select;
                                        echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-3 col-sm-3">
                    <div class="form-group">
                        <label>No SPK</label>
                        <input type="text" name="no_spk" id="no_spk" value="<?php echo $no_spk;?>" class="form-control" placeholder="KD Customer" <?php echo $no_trans?'readonly':'';?>>
                    </div>
                </div>

                <div class="col-xs-6 col-md-3 col-sm-3">
                    <div class="form-group">
                        <label>Nama Customer <span class="load_spk"></span></label>
                        <input type="text" name="nama_customer" id="nama_customer" class="form-control" placeholder="Nama Customer" value="<?php echo $nama_customer;?>" required>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2 col-sm-2">
                    <div class="form-group">
                        <label>No HP <span class="load_spk"></span></label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="No HP" value="<?php echo $no_hp;?>" required>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2 col-sm-2">
                    <div class="form-group">
                        <label>No Trans</label>
                        <input type="text" name="no_trans" id="no_trans" class="form-control" placeholder="No Trans" value="<?php echo $no_trans;?>" readonly>
                    </div>
                </div>
            </div>

            </form>

            <hr>

            <div class="row">

            <form id="activityForm" autocomplete="off">

                <input type="hidden" name="detail_id" id="detail_id" value="0" class="form-control">
                <input type="hidden" name="status_aktivitas" id="status_aktivitas" value="0" class="form-control">

                <div class="col-xs-6 col-md-3 col-sm-3">
                    <div class="form-group">
                        <label>Kategori</label>
                        <select class="form-control option_form" id="tipe_aktivitas" name="tipe_aktivitas" required>
                            <option disabled selected>-- Pilih Aktivitas --</option>

                            <?php 
                            if(isset($activity) && is_array($activity->message)):
                            foreach ($activity->message as $key => $value): 
                            // if($value->STATUS_ACTIVITY == 0):
                            ?>
                            <option value="<?php echo $value->TIPE_AKTIVITAS;?>"><?php echo $value->NAMA_AKTIVITAS;?></option>
                            <?php 
                            // endif;
                            endforeach;
                            endif;
                            ?>

                            <option value="LAINNYA">LAINNYA</option>
                            
                            <!-- <option value="INDENT">Notif Motor Tersedia</option>
                            <option value="MOTOR">Notif Motor Dikirim</option>
                            <option value="BPKB">Notif BPKB Dikirim</option>
                            <option value="PLAT">Notif Nomor Polisi Dikirim</option>
                            <option value="SRUT">Notif SRUT Dikirim</option> -->
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-5 col-sm-5">
                    <div class="form-group">
                        <label>Nama Aktivitas</label>
                        <input type="text" name="nama_aktivitas" id="nama_aktivitas" class="form-control" placeholder="Nama Aktivitas" required>
                    </div>
                </div>

                <div class="col-xs-6 col-md-4 col-sm-4">
                    <div class="form-group">
                        <label>Sales Person</label>
                        <input type="text" id="kd_sales" name="kd_sales" class="form-control" placeholder="Sales Person" required>
                    </div>
                </div>


                <div class="col-xs-12 col-md-12 col-sm-12">
                    <div class="form-group">
                        <label>Deskripsi Aktivitas</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Deskripsi Aktivitas"></textarea>
                    </div>
                </div>



                <div class="col-xs-6 col-md-5 col-sm-5">
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <div class="input-group input-append date" id="">
                            <input class="form-control" id="waktu_mulai" name="waktu_mulai" placeholder="DD/MM/YYYY" type="text" required/>
                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>

                <div class="col-xs-6 col-md-5 col-sm-5">
                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <div class="input-group input-append date" id="">
                            <input class="form-control" id="waktu_selesai" name="waktu_selesai" placeholder="DD/MM/YYYY" type="text" required/>
                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>

            </form>

                <div class="col-xs-6 col-md-2 col-sm-2">
                    <div class="form-group">
                        <br>
                        <button class="btn btn-primary <?php echo $status_c;?>" onclick="__addItem();" type="button" id="btn-add-sp"><i class="fa fa-plus"></i> Tambah Aktivitas</button>
                    </div>
                </div>
            </div>


            </div>
        </div>
    </div>



    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class='fa fa-list-ul'></i> List Activity After Dealing
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">

            <div class="row card-task">

                <?php
                if(!empty($detail) && is_array($detail->message)): 
                foreach ($detail->message as $key => $value):
                    switch ($value->STATUS_AKTIVITAS) {
                        case 0:
                            $button_aktivitas = '<button type="button" class="btn btn-danger btn-xs btn-update btn-add-card disabled-action">Not Started</button>';
                            break;
                        
                        case 1:
                            $button_aktivitas = '<button type="button" class="btn btn-primary btn-xs btn-update btn-add-card disabled-action">In Progress</button>';
                            break;
                        
                        default:
                            $button_aktivitas = '<button type="button" class="btn btn-success btn-xs btn-update btn-add-card disabled-action">Completed</button>';
                            break;
                    }
                ?>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 card-form">
                      <div class="thumbnail">
                          <div class="caption">
                            <div class='col-lg-12'>
                                <span class="glyphicon glyphicon-credit-card"></span>
                                <a href="#" id="hapus-<?php echo $value->DETAIL_ID ;?>" data-id="<?php echo $value->DETAIL_ID ;?>" class="fa fa-trash pull-right text-primary hapus2-item <?php echo $status_e?>"></a>
                                <a href="#" id="edit-<?php echo $value->DETAIL_ID ;?>" data-id="<?php echo $value->DETAIL_ID ;?>" class="fa fa-edit pull-right text-primary edit2-item <?php echo $status_e?>"></a>
                                <a href="<?php echo base_url('after_dealing/cetak_card/'.$value->DETAIL_ID);?>" target="_blank" id="print-<?php echo $value->DETAIL_ID ;?>" data-id="<?php echo $value->DETAIL_ID ;?>" class="fa fa-print pull-right text-primary print-item <?php echo $status_p?>"></a>

                            </div>
                            <div class='col-lg-12 well well-add-card'>

                                <input type="hidden" name="detail_id" value="<?php echo $value->DETAIL_ID ;?>" class="detail_id_old_<?php echo $value->DETAIL_ID;?>">
                                <input type="hidden" name="kd_sales" value="<?php echo $value->KD_SALES ;?>" class="kd_sales_old_<?php echo $value->DETAIL_ID;?>">
                                <input type="hidden" name="nama_aktivitas" value="<?php echo $value->NAMA_AKTIVITAS ;?>" class="nama_aktivitas_old_<?php echo $value->DETAIL_ID;?>">
                                <input type="hidden" name="tipe_aktivitas" value="<?php echo $value->TIPE_AKTIVITAS ;?>" class="tipe_aktivitas_old_<?php echo $value->DETAIL_ID;?>">
                                <input type="hidden" name="status_aktivitas" value="<?php echo $value->STATUS_AKTIVITAS ;?>" class="status_aktivitas_old_<?php echo $value->DETAIL_ID;?>">
                                <input type="hidden" name="waktu_mulai" value="<?php echo TglFromSql($value->WAKTU_MULAI) ;?>" class="waktu_mulai_old_<?php echo $value->DETAIL_ID;?>">
                                <input type="hidden" name="waktu_selesai" value="<?php echo TglFromSql($value->WAKTU_SELESAI) ;?>" class="waktu_selesai_old_<?php echo $value->DETAIL_ID;?>">
                                <input type="hidden" name="deskripsi" value="<?php echo $value->DESKRIPSI ;?>" class="deskripsi_old_<?php echo $value->DETAIL_ID;?>">
                                <input type="hidden" name="keterangan" value="<?php echo $value->KETERANGAN ;?>" class="keterangan_old_<?php echo $value->DETAIL_ID;?>">

                                <h4><?php echo $value->NAMA_CUSTOMER ;?></h4>
                            </div>
                            <div class='col-lg-12'>
                                <p><?php echo $value->NAMA_AKTIVITAS ;?></p>
                                <p class="text-muted">No HP : <?php echo $value->NO_HP ;?></p>
                                <p class="text-muted">Tanggal aktivitas : <?php echo TglFromSql($value->WAKTU_MULAI).' - '.TglFromSql($value->WAKTU_SELESAI) ;?></p>
                            </div>
                            
                            <?php echo $button_aktivitas;?>
                            <span class='glyphicon glyphicon-exclamation-sign text-warning pull-right icon-style' data-toggle="tooltip" data-placement="left" title="<?php echo $value->DESKRIPSI;?>"></span>
                        </div>
                      </div>
                    </div>

                <?php
                endforeach;
                endif;
                ?>
<!-- 
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                  <div class="thumbnail">
                      <div class="caption">
                        <div class='col-lg-12'>
                            <span class="glyphicon glyphicon-credit-card"></span>
                            <span class="fa fa-trash pull-right text-primary"></span>
                            <span class="fa fa-edit pull-right text-primary"></span>
                        </div>
                        <div class='col-lg-12 well well-add-card'>
                            <h4>Nama Customer</h4>
                        </div>
                        <div class='col-lg-12'>
                            <p>Nama Aktivitas</p>
                            <p class="text-muted">No HP : XXXX</p>
                            <p class="text-muted">Tanggal aktivitas : 12-08</p>
                        </div>
                        <button type="button" class="btn btn-danger btn-xs btn-update btn-add-card disabled-action">Not Started</button>
                        <span class='glyphicon glyphicon-exclamation-sign text-warning pull-right icon-style'></span>
                    </div>
                  </div>
                </div> -->

<!-- 
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                  <div class="thumbnail">
                      <div class="caption">
                        <div class='col-lg-12'>
                            <span class="glyphicon glyphicon-credit-card"></span>
                            <span class="fa fa-trash pull-right text-primary"></span>
                            <span class="fa fa-edit pull-right text-primary"></span>
                        </div>
                        <div class='col-lg-12 well well-add-card'>
                            <h4>Nama Customer</h4>
                        </div>
                        <div class='col-lg-12'>
                            <p>Nama Aktivitas</p>
                            <p class="text-muted">No HP : XXXX</p>
                            <p class="text-muted">Tanggal aktivitas : 12-08</p>
                        </div>
                        <button type="button" class="btn btn-primary btn-xs btn-update btn-add-card">In Progress</button>
                        <span class='glyphicon glyphicon-exclamation-sign text-warning pull-right icon-style'></span>
                    </div>
                  </div>
                </div>


                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                  <div class="thumbnail">
                      <div class="caption">
                        <div class='col-lg-12'>
                            <span class="glyphicon glyphicon-credit-card"></span>
                            <span class="fa fa-trash pull-right text-primary"></span>
                            <span class="fa fa-edit pull-right text-primary"></span>
                        </div>
                        <div class='col-lg-12 well well-add-card'>
                            <h4>Nama Customer</h4>
                        </div>
                        <div class='col-lg-12'>
                            <p>Nama Aktivitas</p>
                            <p class="text-muted">No HP : XXXX</p>
                            <p class="text-muted">Tanggal aktivitas : 12-08</p>
                        </div>
                        <button type="button" class="btn btn-success btn-xs btn-update btn-add-card">Completed</button>
                        <span class='glyphicon glyphicon-exclamation-sign text-warning pull-right icon-style'></span>
                    </div>
                  </div>
                </div> -->

            </div>
            </div>
        </div>
    </div>

    <?php echo loading_proses(); ?>

</section>

<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];

var urutanData = 0;
var activity = [];

$(document).ready(function () {
        var date = new Date();
        date.setDate(date.getDate());


        $('.date').datepicker({
          format: 'dd/mm/yyyy',
          startDate: date,
          autoclose: true
        });


        sales_people();
        list_afterdealing();

        $('#date,#datex, #date_fu, #date_fu1, #date_fu2').datepicker({
            format: 'dd/mm/yyyy',
            daysOfWeekHighlighted: "0",
            autoclose: true,
            todayHighlight: true
        });
        $('.qurency').mask('000.000.000.000.000', {reverse: true});
        $('#no_polisi').mask('AZ-0001-AAZ',{'translation': {
          A: {pattern: /[A-Za-z]/},
          Z: {pattern: /[A-Za-z]/,optional:true},
          0: {pattern: /[0-9]/},
          1: {pattern: /[0-9]/,optional:true}
        }})
        $("#submit-btn").on('click', function (event) {
            var formId = '#addAfterdealingform';
            var btnId = '#' + this.id;

            // alert(urutanData);
            $('#loadpage').removeClass("hidden");
            $('.qurency').unmask();

            $(formId).valid();

            if (jQuery(formId).valid()) {
                event.preventDefault();

                storeData(formId, btnId);

            } else {

                $('#loadpage').addClass("hidden");

            }
        });



        $('#kategori, #jenis_kpb').on('change', function () {
          $('#kd_part').val("");
          $('#qty').val("");
          $('#harga_sp').val("");
          __getBarangSP();
        });


        $("#kd_part").on("change",function(e){
          
            var kd_kategori = $('#kategori').val();
            var data_number= $.trim($(this).val());
            var url = http+"/pkb/part_jasa/"+kd_kategori+"/true";

            $(".detail-loading").html("<i class='fa fa-spinner fa-spin'></i>");
            // var url = (kd_kategori == 'Part' ? http+"/sparepart/hargapart/true":http+"/pkb/hargajasa");
            $.getJSON(url,{"data_number":data_number},function(result){
              console.log(result);
              $.each(result,function(e,d){
                var harga_jual=0;
                // var cek_oli = $(".data-OLI").length;
                var jenis_kpb = $("#kd_tipepkb").val();

                harga_jual = d.DATA_HARGA;

                $('#kategori_item').val($("#kategori").val());
                $('#part_desc').val(d.DATA_DESKRIPSI);
                $('#qty').val("1");
                $('#harga_sp').attr('min',parseFloat(harga_jual));     
                $('#harga_sp').val(parseFloat(harga_jual));     
                
                $(".detail-loading").html("");

              })
            })

        });


        $('.card-task').on('click', '.hapus2-item', function(){
          var detailId = $(this).data('id');
          if(detailId != '')
          {
            $.getJSON(http+'/after_dealing/delete_afterdealing_detail',{id:detailId}, function(data, status) {
                if (data.status == true) {
                  $("#hapus-"+detailId).parents('.card-form').remove();
                }
            });
          }
        });


        $('.card-task').on('click', '.edit2-item', function(){
            var detailId = $(this).data('id');

            var detail_id = $('.detail_id_old_'+detailId).val();
            var kd_sales = $('.kd_sales_old_'+detailId).val();
            var nama_aktivitas = $('.nama_aktivitas_old_'+detailId).val();
            var tipe_aktivitas = $('.tipe_aktivitas_old_'+detailId).val();
            var status_aktivitas = $('.status_aktivitas_old_'+detailId).val();
            var waktu_mulai = $('.waktu_mulai_old_'+detailId).val();
            var waktu_selesai = $('.waktu_selesai_old_'+detailId).val();
            var deskripsi = $('.deskripsi_old_'+detailId).val();
            var keterangan = $('.keterangan_old_'+detailId).val();

            $('#detail_id').val(detail_id);
            $('#kd_sales').val(kd_sales);
            $('#nama_aktivitas').val(nama_aktivitas);
            $('#tipe_aktivitas').val(tipe_aktivitas);
            $('#status_aktivitas').val(status_aktivitas);
            $('#waktu_mulai').val(waktu_mulai);
            $('#waktu_selesai').val(waktu_selesai);
            $('#deskripsi').val(deskripsi);
            $('#keterangan').val(keterangan);

            // console.log(data.message);
            $("#edit-"+detailId).parents('.card-form').remove();
            sales_people();
            // urutanData--;
        });


        $('.card-task').on('click', '.hapus-item', function(){
            $(this).parents('.card-form').remove();
            urutanData--;
        });

        $('.card-task').on('click', '.edit-item', function(){
            var detailId = $(this).data('id');
            // alert(detailId);

            var detail_id = $('.detail_id_'+detailId).val();
            var kd_sales = $('.kd_sales_'+detailId).val();
            var nama_aktivitas = $('.nama_aktivitas_'+detailId).val();
            var tipe_aktivitas = $('.tipe_aktivitas_'+detailId).val();
            var status_aktivitas = $('.status_aktivitas_'+detailId).val();
            var waktu_mulai = $('.waktu_mulai_'+detailId).val();
            var waktu_selesai = $('.waktu_selesai_'+detailId).val();
            var deskripsi = $('.deskripsi_'+detailId).val();
            var keterangan = $('.keterangan_'+detailId).val();

            $('#detail_id').val(detail_id);
            $('#kd_sales').val(kd_sales);
            $('#nama_aktivitas').val(nama_aktivitas);
            $('#tipe_aktivitas').val(tipe_aktivitas);
            $('#status_aktivitas').val(status_aktivitas);
            $('#waktu_mulai').val(waktu_mulai);
            $('#waktu_selesai').val(waktu_selesai);
            $('#deskripsi').val(deskripsi);
            $('#keterangan').val(keterangan);

            // console.log(data.message);
            $("#"+detailId).parents('.card-form').remove();
            // urutanData--;

            $(this).parents('.card-form').remove();
            urutanData--;
            sales_people();
        });

        $('#kd_propinsi').change(function(){
            $('#kd_kabupaten').val('');
            $('#kd_kecamatan').val('');
            $('#kd_kelurahan').val('');

            kd_kabupaten();
            kd_kecamatan();
            kd_kelurahan();
        });

        $('#tipe_aktivitas').change(function(){
            var value = $(this).val();

            checkActivity(value);
        })
    })



    function generateStock()
    {
        $(".detail-loading").html("<i class='fa fa-spinner fa-spin'></i>");
        $(".hddetail-loading").html("<i class='fa fa-spinner fa-spin'></i>");
        
        $.getJSON(http+"/pkb/parts4gen",function(result, status){
            if(status == 'success'){
                $('#form-additem').removeClass('disabled-action');
                $(".detail-loading").html("");
                $(".hddetail-loading").html("");
            }
            else{
                generateStock();
            }
        });
    }

    function __getMotor()
    {
        var url = http+"/pkb/tipe_motor";
        var kd_typemotor = $("#kd_typemotor").val();

        $('#kd_typemotor').inputpicker({
          url:url,
          urlParam:{"kd_item":kd_typemotor},
          fields:['KD_TYPEMOTOR','NAMA_PASAR', 'KET_WARNA'],
          fieldText:'NAMA_PASAR',
          fieldValue:'KD_ITEM',
          filterOpen: true,
          headShow:true,
          pagination: true,
          pageMode: '',
          pageField: 'p',
          pageLimitField: 'per_page',
          limit: 15,
          pageCurrent: 1,
          urlDelay:2
        })
        .on("change",function(){
            __getBarangSP();
        })
    }


    function __getBarangSP(){
      var kd_kategori = $('#kategori').val();
      var lokasi_dealer = $('#kd_lokasidealer').val();
      var item = $("#kd_typemotor").val();
      // console.log(item);
      var kd_item = 'null';

      $("#kd_part").val('');
      if(item != undefined && item != ''){
        var split = item.split("-");
        kd_item = split[0];
        var url_kategori = http+"/pkb/part_jasa/"+kd_kategori+"?kd_typemotor="+kd_item+"&lokasi_dealer="+lokasi_dealer;
        $('#kd_part').inputpicker({
          url:url_kategori,
          fields:['DATA_NUMBER','DATA_DESKRIPSI'],
          fieldText:'DATA_DESKRIPSI',
          fieldValue:'DATA_NUMBER',
          filterOpen: true,
          headShow:true,
          pagination: true,
          pageMode: '',
          pageField: 'p',
          pageLimitField: 'per_page',
          limit: 15,
          pageCurrent: 1,
          urlDelay:2
        })
      }
    }

    function __addItem(){

        var formId = '#activityForm';
        // $('.qurency').unmask();

        $(formId).valid();

        if (jQuery(formId).valid()) {
            event.preventDefault();
            
            __storeItem()

            // storeData(formId, btnId);

        }
    }


    function __storeItem()
    {
        var detail_id = $('#detail_id').val();
        var nama_customer = $('#nama_customer').val();
        var no_hp = $('#no_hp').val();
        var kd_sales = $('#kd_sales').val();
        var nama_aktivitas = $('#nama_aktivitas').val();
        var tipe_aktivitas = $('#tipe_aktivitas').val();
        var status_aktivitas = $('#status_aktivitas').val();
        var waktu_mulai = $('#waktu_mulai').val();
        var waktu_selesai = $('#waktu_selesai').val();
        var deskripsi = $('#deskripsi').val();
        var keterangan = $('#keterangan').val();

        var html = '';
        html += '<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 card-form">';
        html += '<div class="thumbnail">';
        html += '<div class="caption">';
        html += '<div class="col-lg-12">';
        html += '<span class="glyphicon glyphicon-credit-card"></span>';

        html += '<a href="#" class="fa fa-trash pull-right text-primary hapus-item"></a>';
        html += '<a href="#" data-id="'+urutanData+'" class="fa fa-edit pull-right text-primary edit-item"></a>';
        // html += '<a class="fa fa-trash pull-right text-primary hapus-item"></a>';
        html += '</div>';
        html += '<div class="col-lg-12 well well-add-card">';

        html += '<input type="hidden" name="detail_id" value="'+detail_id+'" class="detail_id_'+urutanData+'">';
        html += '<input type="hidden" name="kd_sales" value="'+kd_sales+'" class="kd_sales_'+urutanData+'">';
        html += '<input type="hidden" name="nama_aktivitas" value="'+nama_aktivitas+'" class="nama_aktivitas_'+urutanData+'">';
        html += '<input type="hidden" name="tipe_aktivitas" value="'+tipe_aktivitas+'" class="tipe_aktivitas_'+urutanData+'">';
        html += '<input type="hidden" name="status_aktivitas" value="'+status_aktivitas+'" class="status_aktivitas_'+urutanData+'">';
        html += '<input type="hidden" name="waktu_mulai" value="'+waktu_mulai+'" class="waktu_mulai_'+urutanData+'">';
        html += '<input type="hidden" name="waktu_selesai" value="'+waktu_selesai+'" class="waktu_selesai_'+urutanData+'">';
        html += '<input type="hidden" name="deskripsi" value="'+deskripsi+'" class="deskripsi_'+urutanData+'">';
        html += '<input type="hidden" name="keterangan" value="'+keterangan+'" class="keterangan_'+urutanData+'">';

        html += '<h4>'+nama_customer+'</h4>';
        html += '</div>';
        html += '<div class="col-lg-12">';
        html += '<p>'+nama_aktivitas+'</p>';
        html += '<p class="text-muted">No HP : '+no_hp+'</p>';
        html += '<p class="text-muted">Tanggal aktivitas : '+waktu_mulai+' - '+waktu_selesai+'</p>';
        html += '</div>';
        if(status_aktivitas == 0){
            html += '<button type="button" class="btn btn-danger btn-xs btn-update btn-add-card disabled-action">Not Started</button>';
        }
        else if(status_aktivitas == 1){
            html += '<button type="button" class="btn btn-primary btn-xs btn-update btn-add-card disabled-action">In Progress</button>';
        }
        else{
            html += '<button type="button" class="btn btn-success btn-xs btn-update btn-add-card disabled-action">Completed</button>';
        }

        html += '<span class="glyphicon glyphicon-exclamation-sign text-warning pull-right icon-style" data-toggle="tooltip" data-placement="left" title="'+deskripsi+'"></span>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        $('.card-task').prepend(html);


        $('#detail_id').val('');
        $('#kd_sales').val('');
        $('#nama_aktivitas').val('');
        $('#tipe_aktivitas').val('');
        $('#status_aktivitas').val('');
        $('#waktu_mulai').val('');
        $('#waktu_selesai').val('');
        $('#deskripsi').val('');
        $('#keterangan').val('');
        sales_people();


        urutanData++;
    }


    function list_afterdealing()
    {
        var no_spk = $('#no_spk').val();
        var url = "<?php echo base_url('after_dealing/get_list_afterdealing');?>";

        $('#no_spk').inputpicker({
          url:url,
          urlParam:{"no_spk":no_spk},
          fields:['NO_SPK', 'KD_CUSTOMER','NAMA_CUSTOMER'],
          fieldText:'NO_SPK',
          fieldValue:'NO_SPK',
          filterOpen: true,
          headShow:true,
          pagination: true,
          pageMode: '',
          pageField: 'p',
          pageLimitField: 'per_page',
          limit: 10,
          pageCurrent: 1,
          urlDelay:1
        }).on('change', function(){
            var no_spk = $(this).val();
            
            $(".load_spk").html("<i class='fa fa-spinner fa-spin'></i>");

            var url_detail = "<?php echo base_url('after_dealing/get_list_afterdealing/true/true');?>";
            $.getJSON(url_detail, {'no_spk':no_spk}, function(result){

                $('#kd_customer').val(result.list.message[0].KD_CUSTOMER);
                $('#nama_customer').val(result.list.message[0].NAMA_CUSTOMER);
                $('#no_hp').val(result.list.message[0].NO_HP);
                $('#no_rangka').val(result.list.message[0].NO_RANGKA);
                $('#no_mesin').val(result.list.message[0].NO_MESIN);
                $('#no_trans').val(result.list.message[0].NO_TRANS);
                $(".load_spk").html("");
                
                $('#detail_id').val('');
                $('#kd_sales').val('');
                $('#nama_aktivitas').val('');
                $('#tipe_aktivitas').val('');
                $('#status_aktivitas').val('');
                $('#waktu_mulai').val('');
                $('#waktu_selesai').val('');
                $('#deskripsi').val('');
                $('#keterangan').val('');

                $(".card-task").html(result.card);


                activity = [];
                var option = '<option disabled selected>-- Pilih Aktivitas --</option>';

                $.each(result.activity.message,function(e,d){
                    activity.push({
                        'TIPE_AKTIVITAS'    : d.TIPE_AKTIVITAS,
                        'STATUS_ACTIVITY'   : d.STATUS_ACTIVITY,
                        'NAMA_AKTIVITAS'    : d.NAMA_AKTIVITAS
                    })

                    // if(d.STATUS_ACTIVITY == 0){
                        option += '<option value="'+d.TIPE_AKTIVITAS+'">'+d.NAMA_AKTIVITAS+'</option>';
                    // }

                })
                
                option += '<option value="LAINNYA">LAINNYA</option>';
                
                $(".option_form").html(option);

                // checkActivity();
            });

        })
    }


    function checkActivity(value)
    {
        if(value == 'INDENT'){
            $('#nama_aktivitas').val('NOTIFIKASI KEDATANGAN MOTOR');
        }
        else if(value == 'BPKB')
        {
            $('#nama_aktivitas').val('NOTIFIKASI PROSES PENGIRIMAN BPKB');
        }
        else if(value == 'SRUT')
        {
            $('#nama_aktivitas').val('NOTIFIKASI PROSES PENGIRIMAN SRUT');
        }
        else if(value == 'PLAT')
        {
            $('#nama_aktivitas').val('NOTIFIKASI PROSES PENGIRIMAN PLAT');
        }
        else{

            $('#nama_aktivitas').val('');
        }
    }


    function __checkActivity(value)
    {
        if(value != 'LAINNYA'){
            var dx=activity.findIndex(obj => obj['TIPE_AKTIVITAS'] == value);
            // console.log(activity);
            // console.log('array :'+dx);
            if(dx>-1){
                $('#nama_aktivitas').val(activity[dx]['NAMA_AKTIVITAS']);
            }
        }
        else{

            $('#nama_aktivitas').val('');
        }
    }

    function sales_people()
    {
        var url = "<?php echo base_url('after_dealing/get_salespeople');?>";

        $('#kd_sales').inputpicker({
          url:url,
          fields:['NIK','NAMA'],
          fieldText:'NAMA',
          fieldValue:'NIK',
          headShow:true,
          filterOpen: true,
          urlDelay:1
        })
    }
    function __data()
    {
        var dataxx=[];
        for(iz=0; iz<urutanData; iz++){
            dataxx.push({
                'detail_id' : $(".detail_id_"+iz).val(),
                'kd_sales' : $(".kd_sales_"+iz).val(),
                'nama_aktivitas' : $(".nama_aktivitas_"+iz).val(),
                'tipe_aktivitas' : $(".tipe_aktivitas_"+iz).val(),
                'status_aktivitas' : $(".status_aktivitas_"+iz).val(),
                'waktu_mulai' : $(".waktu_mulai_"+iz).val(),
                'waktu_selesai' : $(".waktu_selesai_"+iz).val(),
                'deskripsi' : $(".deskripsi_"+iz).val(),
                'keterangan' : $(".keterangan_"+iz).val(),
            })
        }
        return dataxx;
    }

    function storeData(formId, btnId){
        // alert(formId);
        var data_form=__data();
        var defaultBtn = $(btnId).html();

        $(btnId).addClass("disabled");
        $(btnId).html("<i class='fa fa-spinner fa-spin'></i> Loading");
        $(".alert-message").fadeIn();

        // $(formId + " select").removeAttr("disabled");
        // $(formId + " select").removeClass("disabled-action");
        var formData = $(formId).serialize();
        var act = $(formId).attr('action');

        // console.log(data_form);

        $.ajax({
            url: act,
            type: 'POST',
            data: formData+"&detail="+JSON.stringify(data_form),
            dataType: "json",
            success: function (result) {

                if (result.status == true) {

                    $('.success').animate({
                        top: "0"
                    }, 500);
                    $('.success').html(result.message);


                    if (result.location != null) {
                        setTimeout(function () {
                            location.replace(result.location)
                        }, 1000);
                    } else {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                } else {

                    $('.error').animate({
                        top: "0"
                    }, 500);
                    $('.error').html(result.message);

                    setTimeout(function () {
                        hideAllMessages();
                        $(btnId).removeClass("disabled");
                        $(btnId).html(defaultBtn);
                        $('#loadpage').addClass("hidden");
                    }, 2000);


                }
            }

        });

        return false;
    }
    function __loadBooking(){
        var datax=[];
        $('#load-form').html("<i class='fa fa-spinner fa-spin'></i>");
        $.getJSON("<?php echo base_url('reminder_booking/service_booking/true');?>",function(result){
            console.log(result);
            if(result.status){
                $.each(result.message,function(e,d){
                    datax.push({
                        'NO_POLISI':d.NO_POLISI.toUpperCase(),
                        'NAMA_CUSTOMER' :$.ucwords(d.NAMA_CUSTOMER),
                        'TGL_BOOKING':d.TGL_TRANS.toLocaleString(),
                        'NO_TRANS'  :d.NO_TRANS
                    })
                })
                if(datax){
                    $('#no_polisi').inputpicker({
                        data:datax,
                        fields :['NO_POLISI','NAMA_CUSTOMER','TGL_BOOKING'],
                        fieldText :'NO_POLISI',
                        fieldValue :'NO_POLISI',
                        filterOpen: true,
                        headerShow:true
                    }).on('change',function(e){
                        var dx=datax.findIndex(obj => obj['NO_POLISI'] == $(this).val());
                        __loadDetailCustomer($(this).val());
                    })
                }
                $('#load-form').html("");
            }
        })
    }
    function __loadDetailCustomer(id){
        $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");
        $.getJSON("<?php echo base_url('customer_service/get_datacsa/true');?>",{'n':id},function(result){
            var alamat_lengkap="";
            if(result.status){
                $.each(result.message,function(e,d){
                    alamat_lengkap +=d.ALAMAT;
                    // alamat_lengkap +=d.NAMA_DESA +" "+d.NAMA_KECAMATAN+", "
                    // alamat_lengkap +=d.NAMA_KABUPATEN +" "+d.NAMA_PROPINSI+" "+d.KODE_POS;
                    $('#no_stnk').val('');
                    $('#no_rangka').val(d.NO_RANGKA);
                    $('#no_mesin').val(d.NO_MESIN);
                    $('#nama_pemilik').val(d.NAMA_PEMILIK);
                    $('#no_hp').val(d.NO_HP);
                    $('#alamat').val(alamat_lengkap);
                    $('#kd_customer').val(d.KD_CUSTOMER);
                    $('#kd_typemotor').val(d.KD_TYPEMOTOR);
                })
                $(".load-form").html("");
            }else{
                $(".load-form").html("");
            }
        })
    }
    function __getLokasiDealer(kd_dealer){
        var option ="<option value=''>--Pilih Lokasi--</option>";
        $.getJSON("<?php echo base_url('dealer/lokasi_dealer/true/true');?>",{'kd_dealer':kd_dealer},function(result){
            if(result){
                $.each(result,function(e,d){
                    option +="<option value='"+d.KD_LOKASI+"'>"+d.NAMA_LOKASI+"</option>";
                })
            }
            $('#kd_lokasidealer').html(option);
        })
    }
</script>