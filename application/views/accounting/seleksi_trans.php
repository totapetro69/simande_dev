<?php
//echo isBolehAkses();
  if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $usergroup=$this->session->userdata("kd_group");
  $mode=($this->input->get("t"))?"":"hidden";
  $jt=$this->input->get("jenis_trans");
  $defaultDealer=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>

        <div class="bar-nav pull-right ">
            <a id="modal-button" class="btn btn-default" href="<?php echo base_url('cashier/kasirnew'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Transaksi Baru
            </a>
            <a id="modal-button-1" class="btn btn-default <?php echo $mode;?>" href="<?php echo base_url('cashier/seleksi_lkh'); ?>" role="button">
                <i class="fa fa-list-alt fa-fw"></i> Seleksi Transaksi
            </a>
            <a id="modal-button-1" class="btn btn-default" href="<?php echo base_url('cashier/laporan_lkh'); ?>" role="button">
                <i class="fa fa-list-alt fa-fw"></i> Laporan Kas Harian
            </a>
            <a id="modal-button-1" class="btn btn-default" href="<?php echo base_url('cashier/listkasir'); ?>" role="button">
                <i class="fa fa-list-ul fa-fw"></i> List Transaksi
            </a>
        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                List Transaksi Kasir
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
            	<form id="frmCriteria" action="<?php echo base_url('cashier/seleksi_lkh') ?>" class="bucket-form" method="get">
            		<!-- <div id="ajax-url" url="<?php echo base_url('cashier/tm_typeahead'); ?>"></div> -->
            		<div class="row">
            			<div class="col-sm-4 col-md-3 col-xs-12">
                            <div class="form-group">
                				<label>Nama Dealer</label>
                				<select name="kd_dealer" id="kd_dealer" class="form-control">
                					<option value="">--Pilih Dealer--</option>
                					<?php
            							if($dealer){
            								if(is_array($dealer->message)){
            									foreach ($dealer->message as $key => $value) {
            										$select=($defaultDealer==$value->KD_DEALER)?"selected":'';
            										echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
            									}
            								}
            							}
            						?>
                				</select>
                            </div>
            			</div>
        				<div class="col-sm-3 col-xs-6 col-md-3">
                            <div class="form-group">
            					<label>Tipe Transaksi</label>
            					<select id="jenis_trans" name="jenis_trans" class="form-control">
                                    <option value="">--Pilih Jenis Transaksi--</option>
                                    <option value="Penerimaan" <?php echo ($jt=="Penerimaan")?"selected":"";?>>Penerimaan</option>
                                    <option value="Pengeluaran" <?php echo ($jt=="Pengeluaran")?"selected":"";?>>Pengeluaran</option>
                                </select>
                            </div>
        				</div>
                        <div class="col-sm-3 col-xs-6 col-md-2">
                            <div class="form-group">
                                <label>Tanggal Transaksi</label>
                                <div class="input-group append-group date">
                                    <input type="text" class="form-control" id="tgl_trans_aw" name="tgl_trans_aw" value="<?php echo ($this->input->get("tgl_trans_aw"))?$this->input->get("tgl_trans_aw"):date("d/m/Y");?>">
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span> </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-6 col-md-2">
                            <div class="form-group">
                                <label>Status Posting</label>
                                <select id="pstatus" name="pstatus" class="form-control">
                                    <option value="all" <?php echo ($this->input->get('pstatus') == 'all' ? "selected" : ""); ?>>--Pilih Status--</option>
                                    <option value="1" <?php echo ($this->input->get('pstatus') == '1' ? "selected" : ""); ?>>Sudah</option>
                                    <option value="0" <?php echo ($this->input->get('pstatus') == '0' || !$this->input->get('pstatus') ? "selected" : ""); ?>>Belum</option>
  
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-1 col-xs-3 col-md-1">
                            <br>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search fa-fw"></i> Preview</button>
                        </div>
            		</div>
            		
            	</form>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="table-responsive h350">
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th style="width:4%">No.</th>
                        <th style="width:4%"></th>
                        <th style="width:10%; white-space: nowrap;">No. Transaksi</th>
                        <th style="width:5%; white-space: nowrap;">Tanggal</th>
                        <th style="width:10%; white-space: nowrap;">NO.Reff</th>
                        <th style="width:5%; white-space: nowrap;">Cara Bayar</th>
                        <th style="width:20%" colspan="4">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sudahApprove=0;
                        $list=$listlkh;
                        if(isset($list)){ $n=$this->input->get("page");
                            if($list->totaldata > 0){
                                foreach ($list->message as $key => $value) {
                                    $n++;$jml_detail=0;
                                    if(isset($listd[$value->NO_TRANS])){ 
                                        $jml_detail=$listd[$value->NO_TRANS]->totaldata;
                                    }
                                    $url=base_url('cashier/kasirnew/?n='.urlencode(base64_encode($value->NO_TRANS))."&x=".rand());
                                    if($jml_detail==0){ continue ;}
                                    $sudahApprove=$value->POSTING_STATUS;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $n;?></td>
                                        <td class="text-center">
                                            <a onclick="__showDetail('m_<?php echo $value->ID;?>');" title="Seleksi transaksi"><i class="fa fa-ioxhost"></i></a>

                                        </td>
                                        <td style="white-space: nowrap;"><?php echo $value->NO_TRANS;?></td>
                                        <td style="white-space: nowrap;" class="text-center"><?php echo tglFromSql($value->TGL_TRANS,"/");?></td>
                                        <td style="white-space: nowrap;" class="text-left"><?php echo $value->NO_REFF?></td>
                                        <td><?php echo $value->CARA_BAYAR;?> <span class='pull-right'>[<abbr title='O : open status P: Posting ( closed)'><?php echo ($sudahApprove==0)?'O':'P';?>]</abbr></span></td>
                                        <td colspan="4"><span>
                                            <?php 
                                                if($value->CARA_BAYAR !='Cash'){
                                                    ?>
                                                    <ul class="list-inline">
                                                        <li><?php echo "Nama BanK : ".$value->NAMA_BANK ;?></li>
                                                        <li><?php echo "No.Rek : ".$value->NO_REKENING;?></li>
                                                        <li><?php echo "No.Cek : ".$value->NO_CHEQUE;?></li>
                                                        <li><?php echo "Jatuh Tempo: ".tglFromSql($value->JTH_TEMPO);?></li>
                                                    </ul>

                                            <?php
                                                
                                            }
                                            
                                            ?></span>   
                                        </td>
                                    </tr>
                                    <tr class="m_<?php echo $value->ID;?> hidden">
                                        <td colspan="10"> Detail Transaksi : <?php echo $value->TYPE_TRANS;?></td>
                                    </tr>
                                    <tr class="m_<?php echo $value->ID;?> hidden info ">
                                        <td>No</td>
                                        <td>
                                            <a class="<?php echo ((int)$sudahApprove==1)?'disabled-action':'';?>" href="<?php echo $url;?>" title="Posting transaksi"><i class="fa fa-cogs"></i></a>
                                                <!-- onclick="__posting('<?php echo $value->NO_TRANS;?>','<?php echo $value->ID;?>');" -->
                                        </td>
                                        <td colspan="3">Uraian Transaksi </td>
                                        <td>Jumlah</td>
                                        <td style="white-space: nowrap;">Harga</td>
                                        <td style="white-space: nowrap;">Total Harga</td>
                                        <!-- <td>Status</td> -->
                                        <td colspan="3">Kode Akun</td>
                                    </tr>
                                    <?
                                    $saldo_awal=(isset($saldo))?$saldo->message[0]->SALDO_AWAL:0;$total_trans=0;$pose;
                                    if(isset($listd[$value->NO_TRANS])){ $x=0;
                                        if($listd[$value->NO_TRANS]->totaldata >0){
                                            foreach ($listd[$value->NO_TRANS]->message as $key => $val) {
                                                $x++; $total_trans =$val->JUMLAH * $val->HARGA;
                                                $pose=($val->POS_AKUN!='')? $val->POS_AKUN:$val->DEFAULT_AC;
                                                ?><tr id="r_<?php echo $value->ID."_".$x;?>" class="m_<?php echo $value->ID;?> hidden pos_<?php echo $value->ID;?>">
                                                    <td class="hidden"><?php echo $val->ID;?></td>
                                                    <td class="hidden"><?php echo($val->POS_AKUN!='')? $val->POS_AKUN:$val->DEFAULT_AC;?></td>

                                                    <td class='text-right'><?php echo $x;?></td>
                                                    <td style="white-space: nowrap;">
                                                        <a class='hidden' onclick="__edit('<?php echo $val->ID;?>');" title="edit transaksi"><i class='fa fa-edit'></i></a>
                                                        <a class="hidden <?php echo ((int)$sudahApprove==1)?'disabled-action':'';?>" onclick="__delete('<?php echo $val->ID;?>');" title="Hapus transaksi"><i class='fa fa-trash'></i></a>
                                                    </td>
                                                    <td colspan="3"><?php echo $value->JENIS_TRANS;?>&nbsp;<?php echo $val->URAIAN_TRANSAKSI;?></td>
                                                    <td class='text-right'><?php echo number_format($val->JUMLAH,0);?></td>
                                                    <td class='text-right'><?php echo number_format($val->HARGA,0);?></td>
                                                    <td class='text-right'><?php echo number_format(($val->JUMLAH * $val->HARGA),0);?></td>
                                                    <!-- <td></td> -->
                                                    <td colspan="3" title="<?php echo $val->NAMA_AKUN;?>"><?php echo trim($val->KD_ACCOUNT);?>
                                                        [<?php echo($val->POS_AKUN!='')? $val->POS_AKUN:$val->DEFAULT_AC;?>]</td>

                                                </tr>
                                                <?php
                                                
                                                $total_trans=(trim($pose)==='K')?(-1*$total_trans):$total_trans;
                                            }
                                        }
                                    }
                                    ?>
                                    <tr class="m_<?php echo $value->ID;?> hidden"><td colspan="10">&nbsp;
                                        <input type="hidden" id="saldo_awal" value="<?php echo $saldo_awal;?>">
                                        <input type="hidden" id="saldo_akhir_<?php echo $value->ID;?>" value="<?php echo ($saldo_awal+$total_trans);?>">
                                    </td></tr>
                                    <?php
                                }
                            }
                        }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
    <footer class="panel-footer">
          <div class="row">

              <div class="col-sm-5">
                  <small class="text-muted inline m-t-sm m-b-sm"> 
                      <?php echo ($list)? ($list->totaldata==''?"":"<i>Total Data ". $list->totaldata ." items</i>") : '' ?>
                      
                  </small>
              </div>
              <div class="col-sm-7 text-right text-center-xs">                
                   <?php echo $pagination;?>
              </div>
          </div>
      </footer>
</section>
<script type="text/javascript">
    $(document).ready(function(){

    })
    function __showDetail(id){
        var punya=$('.'+id).hasClass('hidden');
        if($('.'+id).hasClass('hidden')){
            $('.'+id).removeClass("hidden");
        }else{
            $('.'+id).addClass("hidden");            
        }
       // $("tr[class^='m_'] :not(."+id+")").addClass("hidden");
    }
    function __posting(notran,id){
        var datax=[];
        if(confirm('Yakin transaki ini akan di posting?\nSetelah di Posting data sudah tidak bisa di edit / hapus lagi')){
            var salak=$('#saldo_akhir_'+id).val();
            if(salak < 0){ alert("Saldo saat ini tidak mencukupi"); return false;}
            var trow=$('tr.pos_'+id).length;
            for(i=0;i<trow;i++){
                datax.push({
                    'id':$("tr#r_"+id+"_"+(parseInt(i)+1)+" > td:eq(0)").text(),
                    'pos_akun':$("tr#r_"+id+"_"+(parseInt(i)+1)+" > td:eq(1)").text(),
                    'no_trans':notran
                })
            }
            
            $.ajax({
                type:'POST',
                url :"<?php echo base_url("cashier/posting_trans");?>",
                data : {'dt':JSON.stringify(datax)},
                dataType :'json',
                success:function(result){
                    if (result.status == true) {
                $('.success').animate({
                    top: "0"
                }, 500);
                $('.success').html(result.message).fadeIn();


                if (result.location != null) {
                    setTimeout(function() {
                        location.replace(result.location)
                    }, 2000);
                } else {
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            } else {

                $('.error').animate({
                    top: "0"
                }, 500);
                $('.error').html(result.message).fadeIn();

                setTimeout(function() {
                    hideAllMessages();
                    $('#loadpage').addClass("hidden");
                }, 4000);
            }
                }
            })
        }
    }
    function __edit(id){

    }
    function __delete(id){
        if(confirm("Yakin data ini akan di hapus?")){
            $.getJSON("<?php echo base_url("cashier/hapus_item");?>",{"id":id},function(result){
                window.location.reload();
            });
        }
    }
</script>