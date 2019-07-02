<?php
     $dari_tgl =($this->input->get("tgl_trans"))?$this->input->get("tgl_trans"):date("d/m/Y",strtotime("-1 Days"));
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
        <div class="bar-nav pull-right">
            <a href="#" class="btn btn-default disabled-action hidden"><i class="fa fa-save"></i> Simpan Suggested Order  </a>
            <a href="#" class="btn btn-default disabled-action"><i class="fa fa-print"></i> Print </a>
        </div>
	</div>
        <div class="col-lg-12 padding-left-right-10" style="display: block;">
        	<div class="panel margin-bottom-5">
	    		<div class="panel-heading">
	                <i class="fa fa-list fa-fw"></i> Rank Parts
	                <span class="tools pull-right">
	                    <a class="fa fa-chevron-down" href="javascript:;"></a>
	                </span>
	            </div>
                <div class="panel-body panel-body-border">
                    <form id="frmAdd" method="get" action="<?php echo base_url("purchasing/rankparts");?>">
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
                                    <input type="hidden" name="sgs" value='RP'>
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
                    <div class="table-responsive h350">
                        <table class="table table-striped table-bordered table-hover" style="width:70% !important">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width:5%">No.</th>
                                    <th rowspan="2" style="width:10%">Part No.</th>
                                    <th rowspan="2" style="width:25%">Deskripsi</th>
                                    <th rowspan="2" style="width:8%">Rata2 Qty Sales(6W)</th>
                                    <th colspan="2">Akumulasi</th>
                                    <th rowspan="2" style="width:10%">Rank Qty</th>
                                    <td rowspan="2">&nbsp;</td>
                                </tr>
                                <tr>
                                    <th style="width:8%">QTY</th>
                                    <th style="width:8%">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total=0;
                                    if(isset($list)){ $n=0; 
                                        if($list->totaldata>0){
                                            foreach ($list->message as $key => $value) {
                                                $n++;
                                                ?>
                                                <tr>
                                                    <td class='text-center'><?php echo $n;?></td>
                                                    <td class='table-nowarp'><?php echo $value->PART_NUMBER;?></td>
                                                    <td class="td-overflow-50" title="<?php echo $value->PART_DESKRIPSI;?>"><?php echo $value->PART_DESKRIPSI;?></td>
                                                    <td class='text-right'><?php echo number_format($value->AVG_SALES,0);?></td>
                                                    <td class='text-right'><?php echo number_format($value->AK_QTY,0);?></td>
                                                    <td class='text-right'><?php echo number_format($value->PROSEN,0);?></td>
                                                    <td class='text-center'><?php echo ($value->RANK_PARTS);?></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <?php
                                                $total +=$value->AVG_SALES;
                                            }
                                        }
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr class="success">
                                    <td colspan="3" class="text-right"><em>TOTAL</em>&nbsp;&nbsp;</td>
                                    <td class='text-right'><?php echo number_format($total,0);?></td>
                                    <td colspan="4">&nbsp;</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <footer class="panel-footer">
                        <div class="row">

                        </div>
                    </footer>
                </div>
        </div>
    <!-- </div>
	</div> --><!-- end div table responsive -->
</section>