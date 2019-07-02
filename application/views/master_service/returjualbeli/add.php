<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
 
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer =($this->input->get("kd_dealer"))? $this->input->get("kd_dealer"):($this->session->userdata("kd_dealer"));
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
$no_trans = base64_decode($this->input->get("t"));
$defLokasi =($this->input->get("kd_lokasidealer"))?$this->input->get("kd_lokasidealer"):"";
$tgl_trans = date('d/m/Y');
$jenis_retur="";$no_reff="";$tgl_ref="";
$panel_part="disabled-action";
if(isset($hdr)){
    if($hdr->totaldata >0){
        foreach ($hdr->message as $key => $value) {
            $defaultDealer  = $value->KD_DEALER;
            $no_trans       = $value->NO_TRANS;
            $tgl_trans      = TglFromSql($value->TGL_TRANS);
            $defLokasi      = $value->KD_LOKASIDEALER;
            $jenis_retur    = $value ->JENIS_RETUR;
            $no_reff        = $value->NO_REFF;
            $tgl_ref        = TglFromSql($value->TGL_REFF);
        }
        $panel_part="";
    }
}
?>
<section class="wrapper">
    <form class="bucket-form" id="addFormz" method="post" action="<?php echo base_url("retur/simpan_jualbeli"); ?>" autocomplete="off">
        <div class="breadcrumb margin-bottom-10">
            <?php echo breadcrumb(); ?>
            <div class="bar-nav pull-right ">
                <div class="btn-group">
                    <a id="baru" type="button" class="btn btn-default baru ">
                        <i class="fa fa-file-o fa-fw"></i> Retur Baru
                    </a>
                    <a role="button" href="<?php echo base_url("retur/jualbeli"); ?>" class="btn btn-default <?php echo $status_v; ?>"><i class="fa fa-list-ul"></i> List Retur</a>
                </div>
            </div>
        </div>
        <div class="col-lg-12 padding-left-right-10">
            <div class="panel margin-bottom-10">
                <div class="panel-heading"><i class='fa fa-list-ul'></i> Form Retur</div>
                <div class="panel-body panel-body-border" style="display: block;">
                        <div class="row">
                            <div class="col-xs-6 col-md-3 col-sm-3">                            
                                <div class="form-group">
                                    <label>Nama Dealer</label>
                                    <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer">
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
                            <div class="col-xs-6 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <label>Lokasi Dealer</label>
                                    <select class="form-control" id="kd_lokasidealer" name="kd_lokasidealer" required="true">
                                        <option value="0">--Pilih Lokasi Dealer--</option>
                                         <?php
                                            if (isset($lokasidealer)) {
                                              if ($lokasidealer->totaldata >0) {
                                                foreach ($lokasidealer->message as $key => $value) {
                                                  $aktif = ($defLokasi == $value->KD_LOKASI) ? "selected" : '';
                                                  echo "<option value='" . $value->KD_LOKASI . "' " . $aktif . ">[".$value->KD_LOKASI."] ". strtoupper($value->NAMA_LOKASI)."</option>";
                                                }
                                              }
                                            }
                                        ?>  
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <label>Tanggal Trans</label>
                                    <div class="input-group input-append date" id="date">
                                        <input class="form-control" id="tgl_trans" name="tgl_trans" placeholder="DD/MM/YYYY" value="<?php echo $tgl_trans; ?>" type="text"/>
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <label>No. Transaksi</label>
                                    <input type="text" class="form-control disabled-action" name="no_trans" value="<?php echo $no_trans;?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label>Jenis Retur</label>
                                    <select name="jenis_retur" id="jenis_retur" class="form-control" required="true">
                                        <!-- <option value="">- Pilih Jenis Retur -</option> -->
                                        <option value="Pembelian" <?php echo ($jenis_retur=='Pembelian')?'selected':'';?>>Pembelian</option>
                                        <option value="Penjualan" <?php echo ($jenis_retur=='Penjualan')?'selected':'';?>>Penjualan</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label>Doc. Reff<span id="ldg"></span></label> 
                                    <div id="picking_reff">
                                        <input type="text" id="no_reff" name="no_reff" value="<?php echo $no_reff;?>" class="form-control <?php echo ($no_reff)?'disabled-action':'';?>" placeholder="Masukkan no reff then enter" required>
                                    </div>
                                </div>
                                    
                            </div>
                            
                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label>Tanggal Reff <span class="load-form"></span></label>
                                    <input type="text" value="<?php echo $tgl_ref;?>" class="form-control disabled-action" name="tgl_ref" id="tgl_ref">
                                </div>
                            </div>
                        </div>
                        <div class="row <?php echo $panel_part;?>" id="part_panel">
                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label>Part Number</label>
                                    <input type="text" name="part_number" id="part_number" class="form-control">
                                    <input type="hidden" name="part_deskripsi" id="part_deskripsi" class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label>Qty <span class="jrt"></span></label>
                                    <input type="text" name="qty_asal" id="qty_asal" class="form-control disabled-action" placeholder="Qty">
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label>Qty Retur</label>
                                    <input type="text" name="qty" id="qty" class="form-control text-right" placeholder="Qty">
                                    <input type="hidden" name="harga" id="harga">
                                    <input type="hidden" name="diskon" id="diskon">
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-5 col-md-5">
                                <div class="form-group">
                                    <label>Keterangan Retur</label>
                                    <div class="input-group">
                                        <input type="text" name="Keterangan" id="Keterangan" class="form-control" required="true" placeholder="Keterangan return"> 
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary <?php echo $status_c;?>" onclick="__addItem();" type="button" id="btn-add-sp"><i class="fa fa-plus"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </form> 
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel panel-default">
            <div class="table-responsive">
                <table id="pkb_list" class="table table-bordered table-hover b-t b-light">
                    <thead>
                        <tr class="no-hover"><th colspan="8"><i class="fa fa-list fa-fw"></i>List Retur <span class="jrt"></span></th></tr>
                            <tr>
                                <th>No</th>
                                <th>&nbsp;</th>
                                <th>Part Number</th>
                                <th>Part Deksripsi</th>
                                <th>Qty Retur</th>
                                <th>Harga</th>
                                <th>Total Harga</th>
                                <th>Keterangan</th>
                            </tr> 
                        </tr>             
                    </thead> 
                    <tbody>
                        <?php
                          //var_dump($dtl);
                            $n=0;
                            if(isset($dtl)){
                                if($dtl->totaldata>0){
                                    foreach ($dtl->message as $key => $value) {
                                        $n++;
                                        ?>
                                        <tr>
                                            <td class='text-center'><?php echo $n;?></td>
                                            <td class='table-nowarp text-center'>
                                                <a href="<?php echo base_url();?>retur/delete_jualbeli_dtl/<?php echo $value->ID;?>"><i class="fa fa-trash"></i></a>
                                            </td>
                                            <td class="table-nowarp"><?php echo $value->PART_NUMBER;?></td>
                                            <td class="td-overflow-50" title="<?php echo $value->PART_DESKRIPSI;?>"><?php echo $value->PART_DESKRIPSI;?></td>
                                            <td class="table-nowarp text-right"><?php echo number_format($value->JUMLAH,0);?></td>
                                            <td class="table-nowarp text-right"><?php echo number_format($value->HARGA,0);?></td>
                                            <td class="table-nowarp text-right"><?php echo number_format(($value->JUMLAH*$value->HARGA),0);?></td>
                                            <td class="td-overflow-50"><?php echo $value->KETERANGAN;?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                        ?>
                    </tbody>                  
                </table>
            </div>            
        </div>
    </div>
 
    <?php echo loading_proses(); ?>
</section>
<script type="text/javascript">
   var path = window.location.pathname.split('/');
   var http = window.location.origin + '/' + path[1];
 
    $(document).ready(function () {
 
 
        $('#baru').click(function () {
            document.location.href="<?php echo base_url('retur/add_jualbeli');?>";
        })
        
        var date = new Date();
        date.setDate(date.getDate());
 
        $('#date').datepicker({
            format: 'dd/mm/yyyy',
            daysOfWeekHighlighted: "0",
            autoclose: true,
            todayHighlight: true
        });

        $("#submit-btn").on('click', function (event) {
            var formId = '#' + $(this).closest('form').attr('id');
            var btnId = '#' + this.id;
            $('#loadpage').removeClass("hidden");
            $('.qurency').unmask();
 
            $(formId).valid();
 
            if (jQuery(formId).valid()) {
                // Do something
                event.preventDefault();
 
                storeData(formId, btnId);
 
            } else {
 
                $('#loadpage').addClass("hidden");
 
            }
        });
        $('#jenis_retur').change(function(){
            $(".jtr").html($(this).val());
        })
        $('#no_reff')
        .on('keypress',function(e){
            if(e.which==13){
                //__getPartNumber();
                $('#part_number').focus();
            }
        })
        .on('focusout',function(){
            if($(this).val().length >3){
                __getPartNumber();
            }
            
        })
        $('#qty').on('keypress',function(e){
            if(e.which==13){
                $('#Keterangan').focus();
            }
        }).on('focusout',function(){
            var asal=parseFloat($('#qty_asal').val().replace(/,/g,''));
            var rtr =parseFloat($('#qty').val());
            if(rtr > asal){
                alert("Qty Retur tidak boleh melebih Qty Asal");
                $('#qty').val('0').focus().select();
                //return;
            }
        })
        var pnr="<?php echo $panel_part;?>";
        if(pnr==''){
            __getPartNumber();
        }
    })
 
    function __getPartNumber(){
        var jenis_retur=$('#jenis_retur').val();
        var url=(jenis_retur=='Penjualan')? "/retur/penjualan":"/retur/pembelian";
        $('#ldg').html("<i class='fa fa-spinner fa-spin' style='color:red'></i>");
        var datax=[];
        $.getJSON(http+url,{'n':$('#no_reff').val()},function(result){
            if(result.length>0){
                $.each(result,function(e,d){
                    datax.push({
                        'PartNumber':d.PART_NUMBER,
                        'Deskripsi':d.PART_DESKRIPSI,
                        'Jumlah':d.JUMLAH,
                        'Harga' :d.HARGA,
                        'Diskon':d.DISKON,
                        'TglReff':d.TGL_TRANS
                    })
                })
                $('#part_number').focus();
                $('#part_panel').removeClass('disabled-action');
                $('#ldg').html("");
            }else{
                alert('Data tidak di temukan');
                //$('#part_number').inputpicker('destroy');
                $('#part_panel').addClass('disabled-action');
                $('#ldg').html("");
                return;
            }
            
            $('#part_number').inputpicker({
                data:datax,
                fields :['PartNumber','Deskripsi','Jumlah'],
                fieldText:'Deskripsi',
                fieldValue:'PartNumber',
                filterOpen: true,
                headShow:true,
            }).change(function(e){
                var dx=datax.findIndex(obj => obj['PartNumber'] === $(this).val());
                $('#part_deskripsi').val(datax[dx]['Deskripsi']);
                $('#qty_asal').val(datax[dx]['Jumlah']);
                $('#harga').val(datax[dx]["Harga"]);
                $('#diskon').val(datax[dx]['Diskon']);
                $('#qty').focus().select();
                $('#tgl_ref').val(datax[dx]["TglReff"]);
            }).select(function(){
                $('#qty').focus().select();
            })
        })
    }
 
    function __addItem() {
        var formId="#addFormz";
        if(!$('#addFormz').valid()){ return false;}
        $('#loadpage').removeClass("hidden");
        $(formId + " select").removeAttr("disabled");
        $(formId + " select").removeClass("disabled-action");
        var formData = $(formId).serialize();
        var act = $(formId).attr('action');
 
        $.ajax({
            url: act,
            type: 'POST',
            data: formData,
            dataType: "json",
            success: function (result) {
 
                if (result.status == true) {
                    $('.success').animate({ top: "0"}, 500);
                    $('.success').html(result.message);
                    if (result.location != null) {
                        setTimeout(function () { 
                            location.replace(result.location)
                        }, 1500);
                    } else {
                        setTimeout(function () { 
                            location.reload();
                        }, 1500);
                    }
                } else {
 
                    $('.error').animate({top: "0"}, 500);
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
 
</script>