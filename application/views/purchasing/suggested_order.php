<?php
     $dari_tgl =($this->input->get("tgl_trans"))?$this->input->get("tgl_trans"):date("d/m/Y",strtotime("-1 Days"));
     $no_trx=$this->input->get("n");
     $disable=($no_trx)?"":"disabled-action";
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
        <div class="bar-nav pull-right">
            <a href="<?php echo base_url("purchasing/posp_suggest");?>" class="btn btn-default"><i class="fa fa-file-o"></i> Baru</a>
            <a onclick="simpan();" class="btn btn-default"><i class="fa fa-save"></i><?php echo ($no_trx)?' Update Suggested Order':' Simpan Suggested Order';?>  </a>
            <a onclick="printKw();" class="btn btn-default <?php echo $disable;?>"><i class="fa fa-print"></i> Print </a>
        </div>
	</div>
        <div class="col-lg-12 padding-left-right-10" style="display: block;">
        	<div class="panel margin-bottom-5">
	    		<div class="panel-heading">
	                <i class="fa fa-list fa-fw"></i> Form Suggested Order
	                <span class="tools pull-right">
	                    <a class="fa fa-chevron-down" href="javascript:;"></a>
	                </span>
	            </div>
                <div class="panel-body panel-body-border">
                    <form id="frmAdd" method="get" action="<?php echo base_url("purchasing/posp_suggest");?>">
                        <div class="row">
                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label>Dealer</label>
                                    <select id="kd_dealer" name="kd_dealer" class="form-control">
                                        <option value="">--Pilih Dealer--</option>
                                        <?php
                                            if(isset($dealer)){
                                                if($dealer->totaldata >0){
                                                    foreach ($dealer->message as $key => $value) {
                                                        $select=($this->session->userdata('kd_dealer')==$value->KD_DEALER)?"selected":"";
                                                        $select=($this->input->get("kd_dealer")==$value->KD_DEALER)?"selected":$select;
                                                        echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
                                                    }
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <div class="input-group input-append date">
                                        <input type="text" id="tgl_trans" name="tgl_trans" class="form-control" value="<?php echo $dari_tgl;?>">
                                         <span class="input-group-addon"><i class='glyphicon glyphicon-calendar'></i></span>
                                    </div>
                                    <input type="hidden" name="sgs" value='SGS'>
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-1 col-md-1">
                                <div class="form-group">
                                    <br>
                                    <button type="submit" class='btn btn-info'><i class="fa fa-search"></i> Preview</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
	        </div>
        </div>
        <div class="col-lg-12 padding-left-right-10">
        		<div class="panel panel-default">
                    <div class="table-responsive h350" id="printarea">
                        <table class="table table-striped table-bordered table-hover" id="lst_sgs">
                            <thead>
                                <tr>
                                    <th rowspan="2">Part No.</th>
                                    <th rowspan="2">Deskripsi</th>
                                    <th colspan="6">Qty Sales / Week</th>
                                    <th rowspan="2">Avg</th>
                                    <th rowspan="2">Rank</th>
                                    <th rowspan="2">SIM</th>
                                    <th rowspan="2">Stock</th>
                                    <th rowspan="2">PO TO MD</th>
                                    <th rowspan="2">Sgs Ord</th>
                                    <th rowspan="2">Adj Sgs Ord</th>
                                </tr>
                                <tr>
                                	<th>WK1</th>
                                	<th>WK2</th>
                                	<th>WK3</th>
                                	<th>WK4</th>
                                	<th>WK5</th>
                                	<th>WK6</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($list)){ $n=0;
                                        if($list->totaldata>0){
                                            foreach ($list->message as $key => $value) {
                                                $n++;
                                                ?>
                                                <tr>
                                                    <td class='table-nowarp'><?php echo $value->PART_NUMBER;?></td>
                                                    <td class="td-overflow-50" title="<?php echo $value->PART_DESKRIPSI;?>"><?php echo $value->PART_DESKRIPSI;?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($value->WK1,0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($value->WK2,0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($value->WK3,0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($value->WK4,0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($value->WK5,0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($value->WK6,0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($value->AVG_SALES,0);?></td>
                                                    <td class='text-center table-nowarp'><?php echo ($value->RANK_PARTS);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($value->QTY_SIMPARTS,0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($value->STOCKED,0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo number_format($value->QTY_PO,0);?></td>
                                                    <td class='text-right table-nowarp'><?php echo((double)$value->SGS_ORDER <0)?'0': number_format($value->SGS_ORDER,0);?></td>
                                                    <td class='text-center table-nowarp'>
                                                        <?php $adj=(isset($value->ADJ_SGSORDER))?$value->ADJ_SGSORDER:$value->SGS_ORDER;
                                                              $adj = ((double)$adj <0 )?"0":(double)$adj;
                                                        ?>
                                                        <input type='text' id='a_<?php echo ($n-1);?>' name='a_<?php echo ($n-1);?>' value="<?php echo number_format($adj,0);?>" class="form-control on-grid text-right">
                                                    </td>
                                                    <td class="hidden"><?php echo $value->AK_QTY;?></td>
                                                    <td class="hidden"><?php echo $value->TOTAL_SALES;?></td>
                                                    <td class="hidden"><?php echo $value->PROSEN;?></td>
                                                    <td class="hidden"><?php echo $value->USK;?></td>
                                                    <td class="hidden"><?php echo $value->USKD;?></td>
                                                </tr>
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

                        </div>
                    </footer>
                </div>
        </div>
    <?php echo loading_proses();?>
</section><!-- end div class wrapper -->
<!-- <script type="text/javascript" src="<?php echo base_url('assets/dist/print.min.js');?>"></script> -->
<script type="text/javascript" src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script type="text/javascript">
    var path = window.location.pathname.split('/');
    var http = window.location.origin + '/' + path[1];
    function simpan(){
        $('#loadpage').removeClass("hidden");
        var jmlrow=$('#lst_sgs > tbody > tr').length;
        var no_trans='SG'+$('#kd_dealer').val()+"<?php echo tglToSql($dari_tgl);?>";
        for(i=0 ; i < jmlrow ; i++){
            var datax=[];
            datax.push({
                'kd_dealer' : $('#kd_dealer').val(),
                'tgl_trans' : $('#tgl_trans').val(),
                'kd_maindealer': '<?php echo $this->session->userdata('kd_maindealer');?>',
                'no_trans': no_trans,
                'part_number' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(0)').text(),
                'part_deskripsi' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(1)').text(),
                'wk1' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(2)').text(),
                'wk2' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(3)').text(),
                'wk3' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(4)').text(),
                'wk4' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(5)').text(),
                'wk5' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(6)').text(),
                'wk6' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(7)').text(),
                'avg_sales' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(8)').text(),
                'rank_parts' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(9)').text(),
                'qty_simparts' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(10)').text(),
                'stocked' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(11)').text(),
                'qty_po' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(12)').text(),
                'sgs_order' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(13)').text(),
                'adj_sgsorder':$('#a_'+i).val(),
                'ak_qty' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(15)').text(),
                'total_sales' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(16)').text(),
                'prosen' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(17)').text(),
                'usk' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(18)').text(),
                'uskd' : $('#lst_sgs > tbody >tr:eq('+i+')>td:eq(19)').text()
            })
            $.ajax({
                type :'POST',
                ajaxI:i,
                url :"<?php echo base_url('purchasing/simpan_suggest');?>",
                data :{'data':JSON.stringify(datax)},
                dataType:'json',
                success:function(result){
                    i = this.ajaxI;
                    if(result){

                        if(i==(parseInt(jmlrow)-1)){
                            $('.success').animate({ top: "0"}, 500);
                            $('.success').html('Data berhasil di simpan').fadeIn();
                            setTimeout(function() {
                                document.location.href="?n="+no_trans; 
                            }, 2000);
                            
                        }
                            $('#loadpage > p').html("<h3>"+ (i+1)+'/'+jmlrow+' completed...</h3>').attr('style',"color:#0615F7");
                            //$('#loadpage > p').addClass("info");
                    }
                    
                }
           })
        }
    }
    function printKw() {
        $('#loadpage').removeClass("hidden");
        $.getJSON(http+"/purchasing/posp_suggest/false/true",{'n':"<?php echo $this->input->get("n");?>"},function(result){
            if(result.length>0){
                printJS({
                    printable: result, 
                    properties: ['PART_NUMBER','PART_DESKRIPSI','WK1','WK2','WK3','WK4','WK5','WK6','AVG_SALES','RANK_PARTS','QTY_SIMPARTS','STOCKED','QTY_PO','SGS_ORDER','ADJ_SGSORDER'],
                    type: 'json'
                })
                $('#loadpage').addClass("hidden");
            }
        })
        //printJS('printarea','html');
    }
</script>