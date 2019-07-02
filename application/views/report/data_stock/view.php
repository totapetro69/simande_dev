<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
 
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$pilih = $this->input->get('pilih');
$defaultDealer=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
 
        <div class="bar-nav pull-right ">
            <a class="btn btn-default <?php echo $status_p ?>" id="modal-button" onclick='addForm("<?php echo base_url('laporan/data_stock/1?pilih=' . $this->input->get("pilih") . '&keyword=' . $this->input->get("keyword")); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Data Stock" ></i>Print Report
            </a> 
 
        </div>
    </div>
 
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class="fa fa-list-ul"></i> Laporan Data Stock Unit
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
 
            <div class="panel-body panel-body-border" style="display: block;">
 
                <form id="filterForm" action="<?php echo base_url('laporan/data_stock') ?>" class="bucket-form" method="get">
                    <div class="row">
                        <div class="col-xs-6 col-md-4 col-sm-4">
                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select name="kd_dealer" id="kd_dealer" class="form-control">
                                    <option value="0">--Pilih Dealer--</option>
                                    <?php
                                    if (isset($dealer)) {
                                        if (($dealer->totaldata >0)) {
                                            foreach ($dealer->message as $key => $value) {
                                                $select = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-2 col-sm-2">
                            <div class="form-group">
                                <label>Group By</label>
                                <select name="pilih" class="form-control">
                                    <option value="0" <?php echo ($pilih == 0 ? "selected" : ""); ?>>Semua</option>
                                    <option value="1" <?php echo ($pilih == 1 ? "selected" : ""); ?>>Gudang</option>
                                    <option value="2" <?php echo ($pilih == 2 ? "selected" : ""); ?>>Stock Status</option>
                                    <option value="3" <?php echo ($pilih == 3 ? "selected" : ""); ?>>Tipe Motor</option> 
                                </select>
                            </div>
                        </div>
                    
                        <div class="col-xs-5 col-md-5 col-sm-5">
                            <div class="form-group">
                                <label>Search</label>
                                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan kata kunci sesuai group by yang dipilih" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1 col-sm-1">
                            <br>
                            <button id="submit-btn" type="submit" class="btn btn-primary pull-right"><i class='fa fa-search'></i> OK</button>
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
                            <th style="width: 5%">No.</th>
                            <th style="width: 8%">Kode Item</th>
                            <th>Nama Item</th>
                            <th style="width: 6%">Jumlah</th> 
                            <th style="width: 12%">No Rangka</th>
                            <th style="width: 12%">No Mesin</th>
                            <th style="width: 8%">Gudang</th>
                            <th style="width: 8%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        echo $html;
                        ?>
                    </tbody>
                </table>
            </div>
            <footer class="panel-footer">
                <div class="row">
 
                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo ((int)$totaldata==0) ? "" : "<i>Total Data " . $totaldata . " items</i>"; ?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo ($pagination)?$pagination:''; ?>
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
            console.log(id);
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
    /*function loadData(id, value, select) {

        var param = $('#' + id + '').attr('title');
        $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
        var urls = "<?php echo base_url(); ?>laporan/" + param;
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
    }*/
</script>

