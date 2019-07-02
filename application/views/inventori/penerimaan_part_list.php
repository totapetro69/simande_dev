<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
  
  $status_c = (isBolehAkses('c') ? '' : 'remove-button' ); 
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
  $defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
  $dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('first day of this month'));
  $sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y");
  
  ?>
 <style type="text/css">
    table {
   
    font-size: 13px;
   
</style>
  <section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
		<div class="bar-nav pull-right">
			<a class="btn btn-default" id="baru" role="button" href="<?php echo base_url("inventori/penerimaanpart");?>"><i class="fa fa-file-o fa-fw"></i> Input Penerimaan</a>
			
        </div>
    </div>

    <div class="col-lg-12 padding-left-right-5">
        <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-list-ul fa-fw"></i> List Penerimaan Sparepart
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border panel-body-8" style="display: block;">
            	<form id="filterForm" method="get" action="<?php echo base_url("inventori/listpenerimaan_sp");?>">
            		<div class="col-xs-12 col-md-5 col-sm-5">
            			<label>Nama Dealer</label>
                            <select class="form-control " id="kd_dealer" name="kd_dealer">
                                <option value="">--Pilih Dealer--</option>
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
                    <div class="col-xs-12 col-md-3 col-sm-3">
                    	<label>Periode dari Tanggal</label>
                    	<div class="input-group input-append date" id="date">
                            <input class="form-control" id="dari_tanggal" name="dari_tanggal" value="<?php echo $dari_tanggal;?>">
                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3 col-sm-3">
                    	<label>Sampai Tanggal</label>
                    	<div class="input-group input-append date" id="date">
                            <input class="form-control" id="sampai_tanggal" name="sampai_tanggal" value="<?php echo $sampai_tanggal;?>">
                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-1 col-md-1">
                    	<label style="color: white">Preview</label>
                    	<button class="btn btn-default" type="submit">Preview</button>
                    </div>
            	</form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-5">
        <div class="panel panel-default">

            <div class="table-responsive">
            	<table class="table table-bordered table-striped">
            		<thead>
            			<tr>
                            <th>&nbsp;</th>
            				
            				<th>Tanggal</th>
            				<th>No. Transaksi</th>
                            <th colspan='2'>No. Faktur</th>                         
                            <th colspan='2'>Tgl. Faktur</th>                            
            				<th colspan='2'>Jatuh Tempo</th>            				
            				<th colspan='2'>No.PO</th>
            				
            			</tr>
            			<tr clas="thead-alias-tr">
            				<th>&nbsp;</th>
            				<th>No.</th>
            				<th>Part Number</th>
            				<th>Part Deskripsi</th>

                            <th>Jumlah</th>                         
                            <th>Jumlah RFS</th> 
                            <th>Rak Bin</th>                        
            				<th>Jumlah NRFS</th>  
                            <th>Rak Bin NRFS</th>          				
            				<th>Jenis PO</th>                           
                            <th>Harga Beli</th>
            			</tr>
            		</thead>
            		<tbody>
            			<?php
                        $xd=$this->input->get('page');
                        if(!$no_data){
            				if($list){

            					for($i=0; $i < count($list);$i++){
                                    $xd++;
            						echo "<tr class='total'>
            								<td>
                                                <a href='".base_url("inventori/penerimaanpart/?v=y&t=").base64_encode($list[$i]["no_trans"])."&d=".$list[$i]["kd_dealer"]."' class='fa fa-edit' role='button'>
                                                </a> ";
                                                if(!isset($list[$i]["detail"][0]["partno"])){
                                                   echo "<a onclick=\"__hpusTrans('".$list[$i]["no_trans"]."')\" title='hapus data kosong'><i class='fa fa-trash'></i></a>"; 
                                                }
                                    echo "<span class='pull-right'><sup>".$xd."</sup></span>";           
                                    echo "</td>
            								<td class='text-center'>".tglFromSql($list[$i]["tgl_trans"])."</td>
            								<td>".$list[$i]["no_trans"]."</td>
                                            <td colspan='2'>".$list[$i]["no_sj"]."</td>
                                            <td colspan='2'>".$list[$i]["tgl_sj"]."</td>
            								<td colspan='2'>".$list[$i]["jth_tmp"]."</td>
            								
            								<td colspan='2'>".$list[$i]["no_po"]."</td>
            								
            							  </tr>";
                                            for($n=0;$n < (count($list[$i]["detail"]));$n++){
                                                if(isset($list[$i]["detail"][($n)]["partno"])){
                                                    echo "<tr>
                                                            <td>&nbsp;</td>
                                                            <td class='text-right'>".($n+1)."</td>
                                                            <td>".$list[$i]["detail"][($n)]["partno"]."</td>
                                                            <td>".$list[$i]["detail"][($n)]["partdes"]."</td>
                                                            <td class='text-right'>".$list[$i]["detail"][($n)]["qty"]."</td>
                                                            <td class='text-right'>".$list[$i]["detail"][($n)]["qty_rfs"]."</td>
                                                             <td class='text-center'>".strtoupper($list[$i]["detail"][($n)]["rakbin"])."</td>
                                                            <td class='text-right'>".$list[$i]["detail"][($n)]["qty_nrfs"]."</td>
                                                             <td class='text-center'>".strtoupper($list[$i]["detail"][($n)]["rakbin_nrfs"])."</td>
                                                            
                                                            <td>".$list[$i]["detail"][($n)]["kdtrans"]."</td>
                                                           
                                                           
                                                            <td>".number_format($list[$i]["detail"][($n)]["price"], 0)."</td>
                                                          </tr>";
                                                }
                                            }
        					    }
            				}else{
            					echo belumAdaData(8);
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
                            <?php echo ($hasil) ? ($hasil->totaldata == '' ? "" : "<i>Total Data " . $hasil->totaldata . " items</i>") : '' ?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo ($pagination)?$pagination:""; ?>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <?php echo loading_proses();?>
  </section>

  <script type="text/javascript">
    $(document).ready(function(){

    })
    function __hpusTrans(no_trans){
        if(confirm("Yakin transaksi ini akan dihapus")){
            $('#loadpage').removeClass("hidden");
            $.ajax({
                type :'get',
                url : "<?php echo base_url('inventori/deletepenerimaan/');?>"+no_trans,
                data:{},
                dataType : 'json',
                success:function(result){
                     result_message(result);
                }
            })
        }
    }
  </script>