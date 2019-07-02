<?php
  if (!isBolehAkses()) {redirect(base_url() . 'auth/error_auth'); }
  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $pilih=$this->input->get('pilih');
  $defaultDealer=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class="fa fa-list-ul"></i> Stok Unit Motor
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
                <form action="<?php echo base_url('inventori/stock_unit') ?>" class="bucket-form" method="get">
                    <div class="row">
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Dealer</label>
                                    <select name="kd_dealer" id="kd_dealer" class="form-control">
                                 <option value="0">--Pilih Dealer--</option>
                                    <?php
                                        if(isset($dealer)){
                                            if($dealer->totaldata >0){
                                                foreach ($dealer->message as $key => $value) {
                                                    $select = ($defaultDealer==$value->KD_DEALER)?"selected":"";
                                                    echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4 col-xs-6">
                            <div class="form-group">
                                <label>Gudang <span id="l_gudang"></span></label>
                                <select class="form-control" id="kd_gudang" name="kd_gudang" title="gudang">
                                    <option value="0">--Pilih Gudang--</option>
                                    <?php
                                    //var_dump($gudang);exit();
                                    if (isset($gudang)) {
                                          if ($gudang->totaldata >0) {
                                              foreach ($gudang->message as $key => $value) {
                                                $select=($value->DEFAULTS==1)?"selected":"";
                                                $select=($this->input->get("kd_gudang")== $value->KD_GUDANG)?"selected":"";
                                                if(strtoupper($value->JENIS_GUDANG)=='UNIT'){
                                                    echo "<option value='" . $value->KD_GUDANG . "' ".$select.">" . $value->NAMA_GUDANG ."[".strtoupper($value->KD_GUDANG)."]</option>";
                                                }
                                              }
                                          }
                                      }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-4 hidden">
                            <div class="form-group">
                                <label>Group By</label>
                                <select name="pilih" class="form-control">
                                    <option value="0" <?php echo ($pilih == "0" ? "selected" : ""); ?>>Semua</option>
                                    <option value="1" <?php echo ($pilih == "1" ? "selected" : ""); ?>>Segmen Motor</option>
                                    <option value="2" <?php echo ($pilih == "2" ? "selected" : ""); ?>>Tipe Motor</option>
                                    <option value="3" <?php echo ($pilih == "3" ? "selected" : ""); ?>>Series Motor</option>
                                    <option value="4" <?php echo ($pilih == "4" ? "selected" : ""); ?>>Group Motor</option>
                                    <option value="5" <?php echo ($pilih == "5" ? "selected" : ""); ?>>Kategori Motor</option>
                                    <!-- <option value="6" <?php echo ($pilih == 6 ? "selected" : ""); ?>>Status Stock</option> -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Kata Kunci</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan kata kunci sesuai group by yang dipilih" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-1 col-sm-3 col-md-3">
                            <br>
                            <button id="submit-btn" onclick="addData();" class="btn btn-primary pull-right"><i class='fa fa-search'></i> Preview</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-striped table-borderd">
                    <thead>
                        <tr>
                            <th style="width:5%">No.</th>
                            <th style="width:8%">Kode</th>
                            <th style="">Nama Item</th>
                            <th style="width:11%">No. Mesin</th>
                            <th style="width:12%">Gudang</th>
                            <th style="width:4%">Stk Awal</th>
                            <th style="width:4%">Sales</th>
                            <th style="width:4%">Stk Akhir </th>
                            <th style="width:8%">Tgl Terima</th>
                            <!-- <th style="width:8%">Tgl IN Dealer</th> -->
                            <!-- <th>Nama Dealer</th> -->
                        </tr>
                    </thead>
                    <tbody>
                       <?php
                            $n=($this->input->get("page"))?$this->input->get("page"):"0";
                            if(isset($list)){
                                if($list->totaldata >0){
                                    foreach ($list->message as $key => $value) {
                                        $n++;
                                        ?>
                                            <tr>
                                                <td class='text-center'><?php echo $n;?></td>
                                                <td class="text-center table-nowarp"><?php echo $value->KD_ITEM;?></td>
                                                <td class="td-overflow-50" title="<?php echo $value->NAMA_ITEM;?>"><?php echo $value->NAMA_ITEM;?></td>
                                                <td class="text-center"><?php echo $value->NO_MESIN;?></td>
                                                <td class="table-nowarp" title="<?php echo NamaGudang($value->KD_GUDANG,$value->KD_DEALER);?>"><?php echo ($value->KD_GUDANG);?></td>
                                                <td class="text-center"><?php echo ((int)$value->BELI>0)?$value->BELI:$value->SA;?></td>
                                                <td class="text-center"><?php echo $value->SP;?></td>
                                                <td class="text-center"><?php echo $value->STOCK_AKHIR?></td>
                                                <td class="text-center"><?php echo TglFromSql($value->TGL_TERIMA);?></td>
                                                <!-- <td class=""><?php echo $value->NAMA_ITEM;?></td> -->
                                        <?php
                                    }
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo (isset($totaldata )) ? "" : "<i>Total Data " . $totaldata . " items</i>"; ?>
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
    $(document).ready(function () {
        var date = new Date();
        date.setDate(date.getDate());
        $('#date').datepicker({
            format: 'dd/mm/yyyy',
            daysOfWeekHighlighted: "0",
            autoclose: true,
            setDate: date
        });
        var chk="<?php echo $this->input->get("onlystock");?>"
        if(chk){
            $('#onlystock').attr("checked",true);
        }else{
            $('#onlystock').removeAttr("checked");
        }
        $('#kd_dealer').change();
        /*pilihan propinsi*/
        $('#kd_dealer').on('change', function () {
            loadData('kd_gudang', $('#kd_dealer').val(), '0')
        })
        $("tr[id^='l_']").on('click',function(){
            var id=$(this).attr('id');
            if($('tr.'+id).hasClass('hidden')){
                $('tr.'+id).removeClass('hidden');
            }else{
                $('tr.'+id).addClass('hidden');
            }
        })
    });
    function __getcustomerdetail() {
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('inventori/stock_detail'); ?>",
            dataType: 'json',
            data: {'kd_dealer': $('#kd_dealer').val()},
            success: function (result) {
                if (result.status == false) {
                    $('#kd_dealer').val('0').select();
                    loadData('kd_gudang', '0', '0');
                    //return;
                }
                $.each(result, function (index, d) {
                    $('#kd_dealer').val(d.KD_DEALER).select();
                    loadData('kd_gudang', d.KD_DEALER, d.KD_GUDANG);
                })
            }/*,
             fail:function(jqXHR, textStatus, errorThrown){
             $('#l_alamat').html(""); 
             $('#kd_propinsi').val('0').select();
             loadData('kd_kabupaten','0','0');
             loadData('kd_kecamatan','0','0');
             loadData('kd_desa','0','0');
             }*/
        })
    }
    function loadData(id, value, select) {
        var param = $('#' + id + '').attr('title');
        $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
        var urls = "<?php echo base_url(); ?>inventori/" + param;
        var datax = {"kd_dealer": value};
        $.ajax({
            type: 'GET',
            url: urls,
            data: datax,
            typeData: 'html',
            success: function (result) {
                $('#' + id + '').empty();
                $('#' + id + '').append(result);
                $('#' + id + '').val(select).select();
                $('#l_' + param + '').html('');
            }
        });
    }
</script>