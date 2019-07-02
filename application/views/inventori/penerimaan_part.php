<?php
if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $status_ce = (isBolehAkses('c') || isBolehAkses('e'))? '' : 'disabled-action' ; 
  //print_r($sjm);exit();
  $status_n = ($this->session->userdata("nama_group")=="Root")?"":"disabled='disabled'";
  $pilih=$this->input->get('pilih');
  $defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
  $dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y");
  $sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y",strtotime('first day of next month'));
  $no_trans=base64_decode($this->input->get("t"));$expedisi="";$no_polisi="";$nama_driver=""; $status_rcv="";
  $no_sjmasuk=base64_decode($this->input->get('n'));$tgl_sjmasuk="";$no_po="";$jatuh_tempo="";$kddlr="";
  $nfakturShow=($this->input->get('t'))?"disabled-action":"";$nama_dealer="";$no_data=false; $yngpunya="";$infone="";
   if($sjm){
    	if(!($sjm["no_data"])){
    		$no_sjmasuk = $sjm["no_sj"];
    		$no_po 		= $sjm["no_po"];
    		$tgl_sjmasuk= tglFromSql($sjm["tgl_sj"]);
    		$jatuh_tempo= tglFromSql($sjm["jth_tmp"]);
    		$kddlr =	$sjm["kd_dealer"];
        $nama_dealer = $sjm["nama_dealer"];
    		$dari_tanggal 	= (isset($sjm["tgl_trans"]))? tglFromSql($sjm["tgl_trans"]):$dari_tanggal;
    		$expedisi 	= (isset($sjm["nama_expedisi"]))? $sjm["nama_expedisi"]:"";
    		$no_polisi 	= (isset($sjm["no_polisi"]))? $sjm["no_polisi"]:"";
    		$nama_driver= (isset($sjm["nama_driver"]))? $sjm["nama_driver"]:"";
    		$status_rcv = (isset($sjm["status_rcv"]))? $sjm["status_rcv"] :"";
        $defaultDealer=$sjm["kd_dealer"];
        $no_data=$sjm["no_data"];
        $infone =(isset($sjm["info"]))?$sjm["info"]:"";
    	}else{
        $no_data=$sjm["no_data"];
        $infone =$sjm["info"];
        $yngpunya= (isset($sjm["dealerpunya"]))?$sjm["dealerpunya"]:"";
      }
    }
  // }
//echo $this->session->userdata("kd_dealer")."==".$defaultDealer; exit();
 $editable =((int)$status_rcv > 0)?'disabled-action':"disabled-action";
 $pemilik=($this->session->userdata("kd_dealer")===$defaultDealer)?"":'disabled-action';
 
?>
<style type="text/css">
    table {
   
    font-size: 13px;
   
</style>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
		<div class="bar-nav pull-right">
			<a class="btn btn-default" id="baru" role="button" href="<?php echo base_url("inventori/penerimaanpart");?>"><i class="fa fa-file-o fa-fw"></i> Baru</a>
			<a class="btn btn-default <?php echo $status_ce;?> <?php echo $pemilik ." ".$nfakturShow;?> " role="button" id="btn-simpan">
				<?php if($this->input->get('t')){?><i class="fa fa-save fa-fw"></i> Update</a><?php }else{?><i class="fa fa-save fa-fw"></i> Simpan</a><?php }?>
			<a class="btn btn-default" role="button" href="<?php echo base_url("inventori/listpenerimaan_sp");?>"><i class="fa fa-list fa-fw"></i> List Penerimaan Part</a>
		</div>

    </div>

    <div class="col-lg-12 padding-left-right-5">
      <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-cog fa-fw"></i> Penerimaan Sparepart 
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border panel-body-8" style="display: block;">
            	<form action="" class="bucket-form" method="get" id="frmSJ">
                <div class="col-xs-12 col-sm-9 col-md-9">
                  <div class="row">
                		<div class="col-xs-6 col-md-4 col-sm-4">
                			<div class="form-group">
                				<label>Nama Dealer</label>
                          <select class="form-control  <?php echo $nfakturShow;?>" id="kd_dealer" name="kd_dealer">
                              <option value="">--Pilih Dealer--</option>
                              <?php
                              if ($dealer) {
                                  if (is_array($dealer->message)) {
                                      foreach ($dealer->message as $key => $value) {
                                          $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                          //$aktif = ($kddlr == $value->KD_DEALER) ? "selected" : $aktif;
                                          echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                      }
                                  }
                              }
                              ?> 
                          </select>
                      </div>         				
                    </div>
                    <div class="col-xs-6 col-md-4 col-sm-4">
	            				<div class="form-group">
	            					<label>No. Faktur</label>
	            					<div class="input-group input-append">
	            						<input type="text" name="no_sjmasuk" id="no_sjmasuk" style="text-transform: uppercase;" class='form-control <?php echo $nfakturShow;?>' required="true" value="<?php echo $no_sjmasuk;?>" placeholde="No Faktur di SJ Masuk" title="Tekan enter setelah isi no faktur dengan lengkap atau klik button samping nya untuk load data nya">
	            						<span  onclick="addSJ();" class="input-group-addon add-on sj <?php echo $nfakturShow;?>" role="button"><span class="fa fa-cog <?php echo $nfakturShow;?>" title="Upload Surat Jalan"></span></span>
	            					</div>
	            				</div>
	            			</div>
                    <div class="col-xs-6 col-md-4 col-sm-4">
                      <div class="form-group">
                        <label>Tanggal</label>
                        <div class="input-group input-append date" id="date">
                            <input class="form-control <?php echo $nfakturShow;?>" id="tgl_sj" name="tgl_sj" value="<?php echo $dari_tanggal;?>">
                            <span class="input-group-addon add-on <?php echo $nfakturShow;?>"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                      <div class="col-xs-6 col-md-3 col-sm-3">
                        <div class="form-group">
                          <label>Nomor Transaksi</label>
                          <input type="text" class="form-control" id="no_trans" autocomplete="off" name="no_trans" placeholder="Generate Auto" readonly="true" value="<?php echo $no_trans;?>">
                        </div>
                      </div>
  	            			<div class="col-xs-6 col-md-3 col-sm-3">
  	            				<div class="form-group">
  	            					<label>Tanggal Faktur</label>
                            <div class="input-group input-append date">
    	            					  <input type="text" name="tgl_faktur" id="tgl_faktur"  class='form-control <?php echo $nfakturShow;?>' value="<?php echo $tgl_sjmasuk;?>">
                              <span class="input-group-addon add-on <?php echo $nfakturShow;?>"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
  	            				</div>
  	            			</div>
  	            			<div class="col-xs-6 col-md-3 col-sm-3">
  	            				<div class="form-group">
  	            					<label>Jatuh Tempo</label>
                          <div class="input-group input-append date">
  	            					  <input type="text" name="jatuh_tempo" id="jatuh_tempo"  class='form-control <?php echo $nfakturShow;?>' value="<?php echo $jatuh_tempo;?>">
                            <span class="input-group-addon add-on <?php echo $nfakturShow;?>"><span class="glyphicon glyphicon-calendar"></span></span>
                          </div>
  	            				</div>
  	            			</div>
  	            			<div class="col-xs-6 col-sm-3 col-md-3">
  	            				<div class="form-group">
  	            					<label>No.PO</label>
                          <input type="text" name="no_po" id="no_po" value="<?php echo $no_po;?>"  class='form-control <?php echo $nfakturShow;?>'>
  	            				</div>
  	            			</div>
	            		</div>
  	            		<div class="row">
                      
  	            			<fieldset id="inputane" disabled="true">
    	            			<div class="col-xs-6 col-sm-3 col-sm-3">
                					<label>Part Number <span id="fd"></span></label>
                					<input type="text" id="kd_sparepart" name="kd_sparepart" placeholder="Number Part" class="form-control">
                				</div>
                				<div class="col-xs-6 col-sm-5 col-sm-5">
                					<label>Deskripsi</label>
                					<input type="text" id="nama_sparepart" name="nama_sparepart" placeholder="Nama Sparepart" class="form-control" readonly="true">
                				</div>
                        <div class="col-xs-6 col-sm-2 col-sm-2">
                          <label>Harga</label>
                          <input type="text" id="harga_sp" name="harga_sp" placeholder="Harga Beli" class="form-control">
                        </div>
                         
                				<div class="col-xs-6 col-sm-2 col-sm-2">
                					<label>Quantity</label>
                					<div class="input-group input-append">
    	            					<input type="text" id="jumlah_sparepart" name="jumlah_sparepart" placeholder="Jumlah" class="form-control">
    	            					<span class="input-group-addon add-on"><span id="sp" role="button" class="disabled-action fa fa-plus fa-fw" onclick="add_item('sp');"></span></span>
    	            				  <input type="hidden" id="total_harga" name="total_harga" value=""> 
                          </div>
                				</div>
              				</fieldset>
  	            		</div>
	            	</div>
                
	            	<div class="col-xs-12 col-md-3 col-sm-3">
	            		<div class="form-group">
	            			<label>Expedisi</label>
	            			<input type="text" id="nama_expedisi" name="nama_expedisi" class="form-control" placeholder="Nama Expedisi" style="text-transform: uppercase;" value="<?php echo $expedisi;?>">
	            		</div>
	            		<div class="form-group">
	            			<label>No. Polisi</label>
	            			<input type="text" name="no_polisi" id="no_polisi" class="form-control text-upper" style="text-transform: uppercase;" data-mask="AZ-0000-BZZ" placeholder="AB-1234-XX" value="<?php echo $no_polisi;?>">
	            		</div>
	            		<div class="form-group">
	            			<label>Nama Driver</label>
	            			<input type="text" id="nama_driver" name="nama_driver" class="form-control" placeholder="Nama Sopir expedisi" value="<?php echo $nama_driver;?>">
	            		</div>
	            	</div>
		        </form>
		     </div>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
    	<div class="panel panel-default">
    		<div class="table-responsive h250">
    			<table class="table table-bordered table-striped" id="bundling_sp">
    				<thead>
    					<tr>
    						<th style="width:3%">No.</th>
    						<th>&nbsp;</th>
    						<th style="width:10%">Part Number</th>
    						<th style="width:30%">Description</th>
                <th style="width:7%">Harga Beli</th>
                <th style="width:5%">Qty Terima</th>
                
                <th style="width:7%">Total Harga</th>
                <th style="width:12%">Rak Default RFS</th>    						
                <th style="width:7%">Qty RFS</th>
                <th style="width:12%">Rak Default NRFS</th>
                <th style="width:12%">Qty NRFS</th>
    					</tr>
    				</thead>
    				<tbody>
    					<?php
    						$x=0;
                if(isset($sjm)){
    							if(empty($sjm["no_po"])){
    								echo belumAdaData(6);
    							}else{
    								for($i=0;$i < count($sjm["detail"]);$i++){
    									$iconHpus="";$pemilik="";$qty_rfs = $sjm["detail"][$i]['qty']; $qty_nrfs=0;  $status_part =1;
    									if($this->input->get('t')){
                        $qty_rfs = $sjm["detail"][$i]['qty_rfs'];
                        $qty_nrfs = $sjm["detail"][$i]['qty_nrfs'];
                        $status_part = $sjm["detail"][$i]['status_part'];
                        $pemilik="disabled-action";
    										$iconHpus ="<a class='fa fa-trash fa-fv ".$pemilik."' role='button' onclick=\"__hapusID('".$sjm["detail"][$i]["detailid"]."');\" title='hapus item'></a>"; 
    									}
    									$x++;
                      $price=((double)$sjm["detail"][$i]['netprice']>0)?((double)$sjm["detail"][$i]['netprice']/$sjm["detail"][$i]['qty']):"0";
                      $binPart=PartDefaultBin($sjm["detail"][$i]['partno']);
                      $binPart2="";
    									echo "<tr>
    										  <td class='text-center 0'>".$x."</td>
    										  <td class='text-center 1'>".$iconHpus."<span id='lg_".$sjm["detail"][$i]["detailid"]."'></span></td>
    										  <td class='table-nowarp 2'>".$sjm["detail"][$i]['partno']."</td>
    										  <td class='td-collapse-50 3' title='".$sjm["detail"][$i]['partdes']."'>".$sjm["detail"][$i]['partdes']."</td>
                          <td class='text-right 4'><input type='text' id='hrg_".$i."' name='hrg_".$i."' class='on-grid text-right disabled-action ".$pemilik."' value='".number_format($price,0)."'/>
                          <td class='table-nowarp 5'><input type='text' id='jml_".$i."' name='jml_".$i."' class='on-grid text-right ".$pemilik."' value='".number_format($sjm["detail"][$i]['qty'],0)."'/></td>
                        
                          <td class='text-right 6'>".number_format(($sjm["detail"][$i]['netprice']),0)."</td>";
                          $option="";$terpilih=""; $option2="";$terpilih2="";
                          if($gudang){
                            if($gudang->totaldata>0){
                              foreach ($gudang->message as $key => $value) {
                                if($binPart==''){
                                  $terpilih=($value->RAK_DEFAULT=="1")?"selected":"";
                                  if($value->RAK_DEFAULT=='1'){
                                    $option .="<option value='".strtoupper($value->KD_LOKASI).":".$value->KD_GUDANG."' ".$terpilih.">".strtoupper($value->KD_LOKASI) ." - ".$value->KD_GUDANG."</option>";
                                    break;
                                  }else{
                                    $option .="<option value='".strtoupper($value->KD_LOKASI).":".$value->KD_GUDANG."' ".$terpilih.">".strtoupper($value->KD_LOKASI) ." - ".$value->KD_GUDANG."</option>";                                   
                                  }
                                }else{
                                  $terpilih =($binPart == (strtoupper($value->KD_LOKASI).":".$value->KD_GUDANG))?"selected":"";
                                  if($binPart==(strtoupper($value->KD_LOKASI).":".$value->KD_GUDANG)){
                                    $option .="<option value='".strtoupper($value->KD_LOKASI).":".$value->KD_GUDANG."' ".$terpilih.">".strtoupper($value->KD_LOKASI) ." - ".$value->KD_GUDANG."</option>";
                                    break;
                                  }
                                }
                              }
                              foreach ($gudang->message as $key => $value) {

                                if($binPart2==''){
                                  $terpilih2=($value->DEFAULTS1=="1")?"selected":"";
                                  if($value->DEFAULTS1=='1'){
                                    $option2 .="<option value='".strtoupper($value->KD_LOKASI).":".$value->KD_GUDANG."' ".$terpilih2.">".strtoupper($value->KD_LOKASI) ." - ".$value->KD_GUDANG."</option>";
                                    break;
                                  }else{
                                    $option2 .="<option value='".strtoupper($value->KD_LOKASI).":".$value->KD_GUDANG."' ".$terpilih2.">".strtoupper($value->KD_LOKASI) ." - ".$value->KD_GUDANG."</option>";                                   
                                  }
                                }else{
                                  $terpilih2 =($binPart2 == (strtoupper($value->KD_LOKASI).":".$value->KD_GUDANG))?"selected":"";
                                  if($binPart2==(strtoupper($value->KD_LOKASI).":".$value->KD_GUDANG)){
                                    $option2 .="<option value='".strtoupper($value->KD_LOKASI).":".$value->KD_GUDANG."' ".$terpilih2.">".strtoupper($value->KD_LOKASI) ." - ".$value->KD_GUDANG."</option>";
                                    break;
                                  }
                                }
                             
                              }
                            }
                          }
    									echo "<td class='table-nowarp 7'><select id='rak_".$i."' name='rak_".$i."' class='on-grid ".$editable." ".$pemilik."'>".$option."</select>
                         <input id='bindefault' type='hidden' value='".$binPart."'/>
                          </td>
    										  <td class='hidden price 8'>".$sjm["detail"][$i]['price']."</td>
    										  <td class='hidden diskon 9'>".$sjm["detail"][$i]['diskon']."</td>
    										  <td class='hidden ppn 10'>".$sjm["detail"][$i]['ppn']."</td>
    										  <td class='hidden net 11'>".$sjm["detail"][$i]['netprice']."</td>
    										  <td class='hidden kdtrans 12'>".$sjm["detail"][$i]['kdtrans']."</td>
                         
                          <td class='table-nowarp 13'><input type='text' id='jml_rfs".$i."' name='jml_rfs".$i."' class='form-control text-right disabled-action' value='".number_format($qty_rfs,0)."'/></td>
                           <td class='table-nowarp 14'><select id='rakNRFS_".$i."' name='rakNRFS_".$i."' class='on-grid ".$editable." ".$pemilik."'>".$option2."</select>
                          <input id='bindefault' type='hidden' value='".$binPart2."'/>
                          </td>
                          <td class='table-nowarp 15'><input type='text' id='jml_nrfs".$i."' onkeyup='jml_nrfs(".$i.")' name='jml_nrfs".$i."' class='form-control text-right' value='".number_format($qty_nrfs,0)."'/></td>
    										  </tr>";
                        $binPart="";
                        $binPart2="";
    								}
    							}
    						}
    					?>
    				</tbody>
    			</table>
    		</div>
    	</div>
    </div>
    <?php echo loading_proses();?>
</section>
<script>    
      function jml_nrfs(i){
          var jml = $('#jml_'+i).val();
         
          var jml_nrfs = $('#jml_nrfs'+i).val();
          $('#jml_rfs'+i).val(jml-jml_nrfs);
      }
      function rfs(i){
        var jm = $('#status_part'+i).val();
        
          if (jm == '1' ) { 
          $('#jml_rfs'+i).addClass('disabled-action');     
            
          }else {
            $('#jml_rfs'+i).removeClass('disabled-action');    
          }
      }    

	$(document).ready(function(){
		var sjdlr="<?php echo isset($yngpunya)? $yngpunya:$kddlr;?>";
		var dfd ="<?php echo $defaultDealer;?>";
    var view="<?php echo $this->input->get('v');?>";
    var view2="<?php echo $this->input->get('t');?>";
    var nodata="<?php echo ($no_data);?>";
    var infone="<?php echo ($infone);?>";
    if(!view){
  		
    }
    if(nodata && !view){
      if(infone.substring(0,5)=='Item '){
        $('.info').animate({ top: "0"}, 2000);
        $('.info h3').html('Nomor Surat Jalan <?php echo strtoupper($no_sjmasuk);?>');
        $('.info p').html('Sudah di received semua');
        $('.info').fadeIn();
        setTimeout(function() {
            document.location.href="<?php echo base_url()."inventori/penerimaanpart";?>";
        }, 5000);
      }else if(infone.substring(0,5)=="Nomor"){
          if(confirm('nomor sj :<?php echo $no_sjmasuk;?> tidak di terdaftar!\nApakah akan dilanjut dengan proses secara manual?\nError message:'+infone+'\nTekan Ok untuk proses secara manual, Cancel untuk membatalkan')){
    				$('#inputane').attr('disabled',false);
    				$('#kd_sparepart').focus().select();
    				$("#bundling_sp >tbody").empty();
    			}else{
    				window.location.href="<?php echo base_url("inventori/penerimaanpart");?>"
    			}
      }else if(infone.substring(0,5)=='Surat'){
        $('.error').animate({ top: "25%"}, 1000).addClass('text-center');
        $('.error h3').html(infone);
        $('.error p').html('Surat jalan ini tidak bisa di proses lanjut disini');
        $('.error').fadeIn();
          
        setTimeout(function() {
            document.location.href="<?php echo base_url()."inventori/penerimaanpart";?>";
        }, 5000); 
      }else{
        if(sjdlr!= dfd && sjdlr!=''){
          alert('nomor sj :<?php echo strtoupper($no_sjmasuk);?> bukan punya dealer <?php echo  $defaultDealer;?>\nSilahkan cek kembali!');
          document.location.href="<?php echo base_url();?>inventori/penerimaanpart";
        }
      }
    }
	})

</script>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/penerimaan_part.js");?>"></script>
