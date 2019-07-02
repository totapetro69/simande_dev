<?php
if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $status_n = ($this->session->userdata("nama_group")=="Root")?"":"disabled='disabled'";
  $pilih=$this->input->get('pilih');
  $defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
  $dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('First day of previous month'));
  $sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y",strtotime('first day of next month'));
  $no_mesin=$this->input->get("keyword");
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
    </div>

    <div class="padding-left-right-10">
        <div class="row">
            <div class="col-xs-12 col-md-12 col-sm-12">
                <div class="panel margin-bottom-10">
                    <div class="panel-heading">
                       <i class="fa fa-cog fa-fw"></i> Stok Movement Part
                        <span class="tools pull-right">
                            <a class="fa fa-chevron-up" href="javascript:;"></a>
                        </span>
                    </div>
                    <div class="panel-body panel-body-border" style="display: block;">
                    	<form action="<?php echo base_url('inventori/stockoverview') ?>" method="get" >
                            <div class="row col-md-6 col-sm-12 col-xs-12">
                            <!-- dealer -->
                                <div class="col-md-6 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Nama Dealer</label>
                                        <select class="form-control" id="kd_dealer" name="kd_dealer" <?php echo $status_n;?>>
                                            <option value="">--Pilih Dealer--</option>
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
                                <!-- part number-->
                                <div class="col-md-6 col-xs-12 col-sm-12">
                                    <div class="form-group">
                    					<label>Part Number</label>
                                        <input type="text" class="form-control" id="part_number" autocomplete="off" name="part_number" placeholder="Input full part number" required="requeired" value="<?php echo $no_mesin;?>">
                                    </div>
                				</div>
                                <!-- part deskripsi -->
                                <div class="col-md-6 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Part Description</label>
                                        <input type="text" id="part_name" name="part_name" class="form-control disabled-action">
                                    </div>
                                </div>
                                <!-- part superseed -->
                                <div class="col-md-6 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="part_super">Part Superseed</label>
                                        <input type="text" id="part_super" name="part_super" class="form-control disabled-action">
                                    </div>
                                </div>
                                <!-- HET dan SIM -->
                                <div class="col-xs-6 col-md-6 col-md-6">
                                    <div class="form-group">
                                        <label for="het">HET</label>
                                        <input type="text" id="het" name="het" class="form-control disabled-action">
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-6 col-md-6">
                                    <div class="form-group">
                                        <label>SIM Parts</label>
                                        <input type="text" id="sim_part" name="sim_part" class="form-control disabled-action">
                                    </div>
                                </div>
                                <!-- part status -->
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                    <!-- <label for="part_super">Part Status</label> -->
                                        <table class="table table-striped table-bordered" id="pstatus">
                                            <thead>
                                                <tr><th>Status</th><th>Source</th><th>Current</th></tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- stock status -->
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-condensed" id="lststk">
                                            <thead>
                                                <tr><th colspan="4">Stock Status</th></tr>
                                                <tr class="text-center">
                                                    <th style="width:40%">Lokasi Gudang</th>
                                                    <th style="width:20%">On Hand</th>
                                                    <th style="width:20%">Blocked</th>
                                                    <th style="width:20%">In Transit</th></tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                        <span class="hidden ldg"><i class="fa fa-spinner fa-spin"></i></span>
                                    </div>
                                </div>
                            </div>
        		        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
<script type="text/javascript">
    var path = window.location.pathname.split('/');
    var http = window.location.origin + '/' + path[1];
    $(document).ready(function(){
        $('#part_number').inputpicker({
            url : http+"/part/part/true",
            urlParam :{"keyword":$(this).val()},
            urlDelay:1,
            fields:['PART_NUMBER','PART_DESKRIPSI','HET'],
            fieldText:'PART_NUMBER',
            fieldValue:'PART_NUMBER',
            //filterField:'PART_NUMBER',
            pagination: false,
            pageMode: '',
            pageField: 'p',
            pageLimitField: 'per_page',
            limit: 5,
            pageCurrent: 1,
            headShow:true
        }).on("change",function(){
            $.getJSON(http+"/part/part/true/true",{'p':$(this).val()},function(result){
                if(result.length>0){
                    $.each(result,function(e,d){
                        $('#part_name').val(d.PART_DESKRIPSI);
                        $('#part_super').val(d.PART_SUPERSEED);
                        $('#het').val(parseFloat(d.HET).toLocaleString());
                        $('#pstatus > tbody').html("<tr><td class='text-center'>"+d.PART_STATUS_N+"</td><td class='text-center'>"+d.PART_SOURCE_N+"</td><td class='text-center'>"+d.PART_CURRENT_N+"</td></tr>");
                        __getSIMPart(d.PART_NUMBER);
                        __getStocked(d.PART_NUMBER);
                    })
                }
            })
        })
    })

/**
 * js function
 */
function __getSIMPart(part_number){
    $.getJSON(http+"/part/sim_part/true",{'kd_dealer':$('#kd_dealer').val(),'p':part_number},function(result){
        if(result.length > 0){
            $.each(result,function(e,d){
                $("#sim_part").val(d.JUMLAH_STANDARITEM_MIN);
            })
        }
    })
}
function __getStocked(part_number){
    $('span.ldg').removeClass('hidden');
    $.getJSON(http+"/part/stockoverview",{'kd_dealer':$('#kd_dealer').val(),'part_number':part_number},function(result){
        if(result.length > 0){
            $("#lststk > tbody").html(result);
            $('span.ldg').addClass('hidden');
        }
    });
}
</script>