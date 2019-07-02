<?php
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
$kd_kota="";$status_dealer='T';
$disabled_action="";$no_edited="";
$kd_tipe="";$bbnkb="0";$pkb="0";$swdkllj="0";$tstnk="0";$stck="0";$bpkb="0";$plat_asli="0";$ss="0";$banpen="0";
$admin_samsat="0";$pengurusan_tambahan="0";$tahun=date("Y");$id="0";
//$wilayah_samsat="";$dinas="";
$kd_propinsi="";$kabupaten="0";
if(isset($list)){
    if($list->totaldata>0){
        foreach ($list->message as $key => $value) {
            $kd_tipe = strtoupper($value->KD_TIPEMOTOR);
            $tahun = $value->TAHUN;
            $bbnkb = number_format($value->BBNKB,0);
            $pkb    = number_format($value->PKB,0);
            $swdkllj = number_format($value->SWDKLLJ,0);
            $stck   = number_format($value->STCK,0);
            $tstnk  = number_format($value->TOTAL_STNK,0);
            $bpkb   = number_format($value->BPKB,0);
            $ss     = number_format($value->SS,0);
            $banpen = number_format($value->BANPEN,0);
            $admin_samsat = number_format($value->ADMIN_SAMSAT,0);
            $pengurusan_tambahan = number_format($value->PENGURUSAN_TAMBAHAN,0);
            $plat_asli = number_format($value->PLAT_ASLI,0);
            //$wilayah_samsat = $value->WILAYAH_SAMSAT;
            //$dinas = $value->TIPE_CUSTOMER;
            $id        = $value->ID;
            $kd_propinsi  = $value->KD_PROPINSI;
            $kabupaten = $value->KD_KABUPATEN;
            $defaultDealer = $value->KD_DEALER;
        }
    }
    $disabled_action="disabled-action";
}
$no_edited=($judul=='Approve')?'disabled-action':'';
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><?php echo $judul;?> Master STNK BPKB</h4>
</div>
<div class="modal-body">
    <form id="addForm" class="bucket-form" action="<?php echo base_url('dealer/add_stnk_bpkb_simpan'); ?>" method="post">
        <div class="row">
            <div class="col-xs-6 col-md-4 col-sm-4">
                <div class="form-group">
                    <label>Dealer </label>
                    <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer">
                        <option value="0">--Pilih Dealer--</option>
                        <option value="ALL" <?php echo ($defaultDealer=='ALL')?'selected':'';?>>All Dealer</option>
                        <?php
                        if ($dealer) {
                            if (($dealer->totaldata >0)) {
                                foreach ($dealer->message as $key => $value) {
                                    $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                    $kd_kota=($defaultDealer == $value->KD_DEALER) ? $value->KD_KABUPATEN:$kabupaten;
                                    $status_dealer=($defaultDealer == $value->KD_DEALER) ?$value->KD_JENISDEALER:'T';
                                    echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                }
                            }
                        }
                        ?> 
                    </select>
                    <input type="hidden" id="id" name="id" value="<?php echo $id;?>">
                </div>
            </div>
            <!-- propinsi -->
            <div class="col-xs-6 col-md-4 col-sm-4">
                <div class="form-group">
                    <label>Propinsi</label>
                    <select class="form-control disabled-action" name="kd_propinsi" id="kd_propinsi" title="propinsi" required>
                        <!-- <option value="0">--Pilih Propinsi--</option> -->
                        <?php
                        if (isset($propinsi)) {
                            if (($propinsi->totaldata >0)) {
                                foreach ($propinsi->message as $key => $value) {
                                    //$pilih=($propinsi == $value->KD_PROPINSI)?'selected':'';
                                    echo "<option value='" . $value->KD_PROPINSI . "'>" . $value->NAMA_PROPINSI . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <!-- kabupaten -->
            <div class="col-xs-6 col-md-4 col-sm-4">
                <div class="form-group">
                    <label>Kabupaten <span id="l_kabupaten"></span></label>
                    <select class="form-control <?php echo $disabled_action;?>" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten" required>
                        <option value="0">--Pilih Kabupaten--</option>
                        <?php
                            if(isset($wilayah)){
                                if($wilayah->totaldata >0){
                                    foreach ($wilayah->message as $key => $value) {
                                        $pilih =($kabupaten == $value->KD_KABUPATEN)?'selected':'';
                                      echo "<option value='" . $value->KD_KABUPATEN . "' ".$pilih.">" . $value->NAMA_KABUPATEN . "</option>";  
                                    }
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-4 col-sm 4">
                <div class="form-group">
                    <label>Kode Item</label>
                    <!-- <select name="kd_motor" id="kd_motor" class="form-control <?php echo $disabled_action;?>">
                      <option value="">- Pilih Item -</option>
                    </select> -->
                    <input type="text" id="kd_motor" name="kd_motor" class="form-control" placeholder="Tipe Motor" value="<?php echo $kd_tipe;?>">
                </div>
            </div>
            <div class="col-xs-6 col-md-2 col-sm-3">
                <div class="form-group">
                 <label>Tahun</label>
                   <input type="text" class="form-control <?php echo $disabled_action;?>" id="start_date" name="start_date" value="<?php echo $tahun;?>" placeholder="yyyy" />
               </div>
            </div>
            <div class="col-xs-6 col-md-2 col-sm-3">
                <div class="form-group">
                    <label>Copy Dari Tahun</label>
                    <select class="form-control <?php echo $disabled_action;?>" id="data_tahun" name="data_tahun">
                    </select>
                </div>
            </div>
        </div>
        <hr style="margin-bottom: 5px !important; margin-top: 5px !important">
        <div class="row">
            <div class="col-xs-6 col-sm-3 col-md-3">
                <div class="form-group">
                    <label>BBNKB</label>
                    <input id="bbnkb" type="text" name="bbnkb" class="form-control <?php echo $no_edited;?> xx" value="<?php echo $bbnkb;?>">
                </div>
            </div>
            <div class="col-xs-6 col-sm-3 col-md-3">
                <div class="form-group">
                    <label>PKB</label>
                    <input id="pkb" type="text" name="pkb" class="form-control <?php echo $no_edited;?> xx" value="<?php echo $pkb;?>">
                </div>
            </div>
            <div class="col-xs-6 col-sm-3 col-md-3">
                <div class="form-group">
                    <label>SWDKLLJ</label>
                    <input id="swdkllj" type="text" name="swdkllj" class="form-control <?php echo $no_edited;?> xx" value="<?php echo $swdkllj;?>">
                </div>
            </div>
            <div class="col-xs-6 col-sm-3 col-md-3 hidden">
                <div class="form-group">
                    <label>Total STNK</label>
                    <input id="tstnk" type="text" name="tstnk" class="form-control <?php echo $no_edited;?> xx" value="<?php echo $tstnk;?>">
                </div>
            </div>
            <!-- <div class="col-xs-6 col-md-3 col-sm-3 no-margin-l">
                <div class="form-group">
                    <br>
                    <label><input id="notApv" type="checkbox" name="notApv" style="cursor: pointer;"> Tanpa Approval DS
                    </label>
                </div>
            </div> -->
        </div>
        <div class="row hidden">
            <div class="col-xs-6 col-sm-3 col-md-3">
                <div class="form-group">
                    <label>BBNKB</label>
                    <input id="bbnkb" type="text" name="bbnkb_a" class="form-control xx" value="<?php echo $bbnkb;?>">
                </div>
            </div>
            <div class="col-xs-6 col-sm-3 col-md-3">
                <div class="form-group">
                    <label>PKB</label>
                    <input id="pkb" type="text" name="pkb_a" class="form-control xx" value="<?php echo $pkb;?>">
                </div>
            </div>
            <div class="col-xs-6 col-sm-3 col-md-3">
                <div class="form-group">
                    <label>SWDKLLJ</label>
                    <input id="swdkllj" type="text" name="swdkllj_a" class="form-control xx" value="<?php echo $swdkllj;?>">
                </div>
            </div>
            <div class="col-xs-6 col-sm-3 col-md-3 hidden">
                <div class="form-group">
                    <label>Total STNK</label>
                    <input id="tstnk" type="text" name="tstnk_a" class="form-control xx" value="<?php echo $tstnk;?>">
                </div>
            </div>
        </div>
        <hr style="margin-bottom: 5px !important; margin-top: 5px !important">
        <div class="row">
            <div class="col-xs-6 col-md-3 col-sm-3">
                <div class="form-group">
                    <label>STCK</label>
                    <input id="stck" type="text" name="stck" class="form-control xx" value="<?php echo $stck;?>">
                </div>
            </div>
            <div class="col-xs-6 col-md-3 col-sm-3">
                <div class="form-group">
                    <label>PLAT ASLI</label>
                    <input id="plat_asli" type="text" name="plat_asli" class="form-control xx" value="<?php echo $plat_asli;?>">
                </div>
            </div>
            <div class="col-xs-6 col-md-3 col-sm-3">
                <div class="form-group">
                    <label>ADMIN SAMSAT</label>
                    <input id="admin_samsat" type="text" name="admin_samsat" class="form-control xx" value="<?php echo $admin_samsat;?>">
                </div>
            </div>
            <div class="col-xs-6 col-md-3 col-sm-3">
                <div class="form-group">
                    <label>BPKB</label>
                    <input id="bpkb" type="text" name="bpkb" class="form-control xx" value="<?php echo $bpkb;?>">
                </div>
            </div>
        </div>
        <hr style="margin-bottom: 5px !important; margin-top: 5px !important">
        <div class="row">
            <div class="col-xs-6 col-md-3 col-sm-3">
                <div class="form-group">
                    <label>PENGURUSAN TAMBAHAN</label>
                    <input id="pengurusan_tambahan" type="text" name="pengurusan_tambahan" class="form-control xx" value="<?php echo $pengurusan_tambahan;?>">
                </div>
            </div>
            <div class="col-xs-6 col-md-3 col-sm-3">
                <div class="form-group">
                    <label>SS</label>
                    <input id="ss" type="text" name="ss" class="form-control xx" value="<?php echo $ss;?>">
                </div>
            </div>
            <div class="col-xs-6 col-md-3 col-sm-3">
                <div class="form-group">
                    <label>BANPEN</label>
                    <input id="banpen" type="text" name="banpen" class="form-control xx" value="<?php echo $banpen;?>">
                </div>
            </div>
            <div class="col-xs-6 col-md-3 col-sm-3 no-margin-l">
                <div class="form-group">
                    <br>
                    <label><input id="updAll" type="checkbox" name="updAll" style="cursor: pointer;"> Update ke Semua Tipe Motor
                    </label>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class='fa fa-close'></i> Batal</button>
    <a href="<?php echo base_url("dealer/delete_stnk_bpkb/").$id;?>" role="button" id="not_aprove" class="btn btn-default <?php echo ($judul=='Approve')?"":"hidden";?>"><i class="fa fa-trash"></i> Not Approved</a>
    <button id="submit-btn"  class="btn btn-danger <?php echo ($judul=='Approve')?'':"disabled-action";?>">
        <i class="fa fa-save"></i> <?php echo ($judul=='Approve')?"Approved":"Simpan";?>
    </button>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#datepicker").datepicker( {
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years"
        });
        var judule="<?php echo $judul;?>"
        $('#addForm input').on('change',function(){
            if(judule=='Edit'){
                $('#submit-btn').removeClass("disabled-action");
            }
        })
        //loadData('kd_kabupaten', $('#kd_propinsi').val(), '<?php echo $kd_kota;?>')
        /*pilihan propinsi*/
        $('#kd_propinsi').on('change', function () {
            //loadData('kd_kabupaten', $('#kd_propinsi').val(), '<?php echo $kd_kota;?>')
        })
        $('#kd_kabupaten').change(function(){
            $('#kd_motor option.'+$(this).val()).removeClass("hidden")
            $('#kd_motor option:not(.'+$(this).val()+',.xx)').addClass("hidden");
            $('#submit-btn').removeClass("disabled-action");
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
            addData();
            
        });
        $('#bbnkb').ForceNumericOnly().mask('#,##0',{'reverse':true}).addClass('text-right')
        $('#pkb').ForceNumericOnly().mask('#,##0',{'reverse':true}).addClass('text-right')
        $('#swdkllj').ForceNumericOnly().mask('#,##0',{'reverse':true}).addClass('text-right')
        $('#stck').ForceNumericOnly().mask('#,##0',{'reverse':true}).addClass('text-right')
        $('#plat_asli').ForceNumericOnly().mask('#,##0',{'reverse':true}).addClass('text-right')
        $('#bpkb').ForceNumericOnly().mask('#,##0',{'reverse':true}).addClass('text-right')
        $('#pengurusan_tambahan').ForceNumericOnly().mask('#,##0',{'reverse':true}).addClass('text-right')
        $('#admin_samsat').ForceNumericOnly().mask('#,##0',{'reverse':true}).addClass('text-right')
        $('#ss').ForceNumericOnly().mask('#,##0',{'reverse':true}).addClass('text-right')
        $('#banpen').ForceNumericOnly().mask('#,##0',{'reverse':true}).addClass('text-right')
        $('#data_tahun').change(function(){
            __checkdataAllDealer($(this).val(),$('#kd_motor').val())
        })
        __getmotors();
    })
    function loadData(id, value, select) {
        var param = $('#' + id + '').attr('title');
        $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
        var urls = "<?php echo base_url(); ?>dealer/" + param;
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
                console.log('pilih samsat'+select);
                if(select){
                    $('#kd_motor option.'+select).removeClass("hidden")
                }else{
                    $('#kd_motor option').addveClass("hidden")
                }
            }
        });
    }
    function __checkdataAllDealer(tahune,tpm){
        var dealer  = "<?php echo ($status_dealer=='Y')?'ALL':$defaultDealer;?>";
        var tahun   = (tahune=='')?$('#start_date').val():tahune;
        var kd_kota = $('#kd_kabupaten').val();
        var tipe_m  = tpm;// $('#kd_motor').val();
        $.getJSON("<?php echo base_url('dealer/get_stnk_bpkb');?>/true",
            {'kd_dealer':dealer,'tahun':tahun,'kd_kabupaten':kd_kota,'kd_tipemotor':tipe_m},
            function(result){
            //console.log(result);
            if(result.length>0){
                $.each(result,function(e,d){
                    $('#bbnkb').val(parseFloat(d.BBNKB).toLocaleString())
                    $('#pkb').val(parseFloat(d.PKB).toLocaleString())
                    $('#swdkllj').val(parseFloat(d.SWDKLLJ).toLocaleString())
                    $('#stck').val(parseFloat(d.STCK).toLocaleString())
                    $('#plat_asli').val(parseFloat(d.PLAT_ASLI).toLocaleString())
                    $('#bpkb').val(parseFloat(d.BPKB).toLocaleString())
                    $('#pengurusan_tambahan').val(parseFloat(d.PENGURUSAN_TAMBAHAN).toLocaleString())
                    $('#admin_samsat').val(parseFloat(d.ADMIN_SAMSAT).toLocaleString())
                    $('#ss').val(parseFloat(d.SS).toLocaleString())
                    $('#banpen').val(parseFloat(d.BANPEN).toLocaleString());
                    $('#id').val(d.ID)
                })
            }else{
                $(".xx").val("0");
            }
        })
    }
    function __getTahunData(tpm){
        var dealer  = "<?php echo ($status_dealer=='Y')?'ALL':$defaultDealer;?>";
        var kd_kota = $('#kd_kabupaten').val();
        var tipe_m  = tpm;//$('#kd_motor').val();
        var optione ="";
            optione +="<option value=''>--Pilih Tahun--</option>";
        $('#data_tahun').html(optione);
        $.getJSON("<?php echo base_url('dealer/get_stnk_bpkb');?>/true/true",
            {'kd_dealer':dealer,'kd_kabupaten':kd_kota,'kd_tipemotor':tipe_m},
            function(result){
                if(result.length>0){
                    $.each(result,function(e,d){
                        $('#data_tahun').append("<option value='"+d.TAHUN+"'>"+d.TAHUN+"</option>");
                    })
                }
                //console.log('jmldata :'+result.length);
        });
    }
    function __getmotors(){
        $.getJSON("<?php echo base_url();?>motor/tipe_motor/1",{'otm':1},function(result){
            var datax=[];
            if(result.length>0){
                $.each(result,function(e,d){
                    datax.push({
                        'kd_tipemotor': d.KD_TYPEMOTOR,
                        'nama_tpm' : d.NAMA_TYPEMOTOR,
                        'description':d.NAMA_PASAR,
                        'text':d.KD_TYPEMOTOR+" - "+d.NAMA_PASAR,
                        'value':d.KD_TYPEMOTOR
                    })
                })
            }
            console.log(datax);
            $('#kd_motor').inputpicker({
                data:datax,
                fields:['kd_tipemotor','nama_tpm','description'],
                fieldValue:'kd_tipemotor',
                fieldText:'text',
                filterOpen:true
            }).change(function(){
                var dx=datax.findIndex(obj => obj['value'] === $(this).val());
                __checkdataAllDealer('',datax[dx]['value']);
                __getTahunData(datax[dx]['value']);
            })
        })
    }
</script>
