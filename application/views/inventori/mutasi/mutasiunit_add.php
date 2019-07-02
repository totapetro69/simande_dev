<?php
$defaultDealer = $this->session->userdata("kd_dealer");
$kd_gudang_asal="";
$kd_gudang_tujuan="";
$kd_dealer_tujuan="";
$no_rangka=""; $tgl_mutasi=date("d/m/Y");
$keterangan="";
$deskripsi_unit="";
$kelengkapan="";
$no_trans=(isset($ntrans))?$ntrans:"";
$jenis_mutasi="";
if(isset($list)){
  if($list->totaldata>0){
    foreach ($list->message as $key => $value) {
      $defaultDealer = $value->KD_DEALER;
      $kd_gudang_asal = $value->KD_GUDANG_ASAL;
      $kd_gudang_tujuan = $value->KD_GUDANG_TUJUAN;
      $kd_dealer_tujuan = $value->KD_DEALER_TUJUAN;
      //$no_rangka = $value->PART_NUMBER;
      $tgl_mutasi = tglFromSql($value->TGL_TRANS);
      $no_trans = $value->NO_TRANS;
     // $keterangan = $value->KETERANGAN;
      //$kelengkapan = $value->KSU;
      $jenis_mutasi = $value->JENIS_TRANS;
    }
  }
}
$disabled_action=($no_trans)?"disabled-action":"";
?>

<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
    <div class="bar-nav pull-right">
      <?php $asal_view=$this->input->get("f");?>
      <div class="<?php echo ($asal_view)?'hidden':'';?>">
        <a class="btn btn-primary" onclick="addNew();" role="button"><i class="fa fa-save"> Add Mutasi Baru</i></a>
        <a id="modal-button" class="btn btn-default" onclick='addForm("<?php echo base_url('motor/mutasiunit_print?n=').$no_trans.'&d='.$defaultDealer; ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
            <i class="fa fa-print"></i> Print SJ
        </a>
        <a class="btn btn-default" href="<?php echo base_url()."motor/mutasiunit_list";?>" role="button"><i class="fa fa-list-ol"> List Mutasi</i></a>
      </div>
      <div class="<?php echo ($asal_view)?"":"hidden";?>">
            <a class="btn btn-default" href="<?php echo base_url("spk/approval_spk/").$no_trans;?>/MTSAD"><i class="fa fa-cog"></i> Approve Mutasi</a>
            <a class="btn btn-default hidden" href="<?php echo base_url("spk/approval_spk/").$no_trans."/MTSAD/b";?>"><i class="fa fa-trash"></i> Batal Mutasi</a>
            <a class="btn btn-default" href="<?php echo base_url("cashier/approval_ds/");?>"><i class="fa fa-list-ul"></i> Approval List</a>
        </div>
    </div>

    </div>

    <div class="col-lg-12 padding-left-right-5 <?php echo ($asal_view)?'disabled-action':'';?>">
        <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-list-ul fa-fw"></i> List Mutasi Unit
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
        
          <div class="panel-body panel-body-border panel-body-10" style="display: <?php echo ($asal_view)?"none":"block";?>;">
            <form id="addForm" class="bucket-form" action="<?php echo base_url('motor/mutasiunit_simpan');?>" method="post">
              <div class="modal-body">
                <div class="row">
                  <input type="hidden" name="tipe_mutasi" value='UNIT'>
                  <!-- dealer -->
                  <div class="col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group">
                      <label>Dealer</label>
                      <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer">
                        <option value="0">--Pilih Dealer--</option>
                        <?php
                          if ($dealer) {
                            if (is_array($dealer->message)) {
                              foreach ($dealer->message as $key => $value) {
                                $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                $aktif = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $aktif;
                                echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                              }
                            }
                          }
                        ?> 
                      </select>
                    </div>
                  </div>
                  <!-- tanggal mutasi -->
                  <div class="col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group">
                      <label>Tanggal Mutasi</label>
                      <div class="input-group input-append date" id="datepicker">
                          <input class="form-control <?php echo $disabled_action;?>" name="tgl_mutasi" id="tgl_mutasi" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y'); ?>" type="text"/>
                          <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                      </div>
                    </div>
                  </div>
                  <!-- nomor transaksi -->
                  <div class="col-xs-12 col-md-4 col-sm-4">
                    <div class="form-group">
                      <label>No. Transaksi</label>
                      <input type="text" id="no_trans" name="no_trans" class="form-control disabled-action" value="<?php echo $no_trans;?>">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <!-- jenis mutasi -->
                  <div class="col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group">
                      <label>Jenis Mutasi</label>
                      <select onclick="clearInputFields('rangka')"  id="jenis_mutasi" name="jenis_mutasi" class="form-control <?php echo $disabled_action;?>">
                        <option value="">--Pilih Jenis Mutasi--</option>
                        <option value="Antar Gudang" <?php echo ($jenis_mutasi=='Antar Gudang')?'selected':'';?>>Antar Gudang</option>
                        <option value="Antar Dealer" <?php echo ($jenis_mutasi=='Antar Dealer')?'selected':'';?>>Antar Dealer</option>
                        <option value="Status Unit" <?php echo ($jenis_mutasi=='Status Unit')?'selected':'';?>>Status Unit</option>
                        <option value="Return" class="hidden">Return</option>
                      </select>
                    </div>
                  </div>
                  <!-- gudang asal -->
                  <div class="col-xs-12 col-md-4 col-sm-4">
                    <div class="form-group">
                      <label>Lokasi Asal</label>
                      <select id="kd_gudang" name="kd_gudang" class="form-control atg ">
                        <option value="">--Pilih Gudang--</option>
                        <?php 
                          if(isset($gudang)){
                            if($gudang->totaldata>0){
                              foreach ($gudang->message as $key => $value) {
                                $pilih=($kd_gudang_asal==$value->KD_GUDANG)?'selected':'';
                                echo "<option value='".$value->KD_GUDANG."' ".$pilih.">".$value->NAMA_GUDANG." [".$value->KD_GUDANG."] </option>";
                              }
                            }
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <!-- gudang tujuan -->
                  <div class="col-xs-12 col-md-4 col-sm-4">
                    <div class="form-group">
                      <label id="label_lokasi">Lokasi Tujuan</label>
                      <label id="label_status" class="hidden">Status Tujuan</label>
                      <select id="kd_gudang_tujuan" name="kd_gudang_tujuan" class="form-control atg ">
                        <option value="">--Pilih Gudang--</option>
                        <?php 
                          if(isset($gudang)){
                            if($gudang->totaldata>0){
                              foreach ($gudang->message as $key => $value) {
                                $pilih=($kd_gudang_tujuan==$value->KD_GUDANG)?'selected':'';
                                echo "<option value='".$value->KD_GUDANG."' class='".$value->KD_GUDANG." hidden' ".$pilih.">".$value->NAMA_GUDANG." [".$value->KD_GUDANG."] </option>";
                              }
                            }
                          }
                        ?>
                      </select>
                      <select id="kd_dealer_tujuan" name="kd_dealer_tujuan" class="form-control atd hidden ">
                        <option value="">--Pilih Dealer--</option>
                        <?php
                          if ($dealer) {
                            if (is_array($dealer->message)) {
                              foreach ($dealer->message as $key => $value) {
                                $aktif = ($kd_dealer_tujuan == $value->KD_DEALER) ? "selected" : "";
                                echo "<option value='" . $value->KD_DEALER . "' " . $aktif . " class='".$value->KD_DEALER." hidden'>" . $value->NAMA_DEALER . "</option>";
                              }
                            }
                          }
                        ?> 
                      </select>
                      <select id="kd_status_tujuan" name="kd_status_tujuan" class="form-control atd hidden ">
                        <option value="">--Pilih Status--</option>
                         <option value="NRFS" >NRFS</option>
                        <option value="RFS" >RFS</option>     
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row" id="rangka">
                  <!-- nomor rangka motor -->
                  <div class="col-xs-12 col-md-4 col-sm-4">
                    <div class="form-group">
                      <label>No. Mesin</label>
                      <input type="text" name="no_rangka" id="no_rangka" class="form-control <?php echo $disabled_action;?>" placeholder="Masukan nomor mesin kendaraan" value="<?php echo $no_rangka;?>" required>
                    </div>
                  </div>                  
                  <!-- deskripsi motor -->
                  <div class="col-xs-12 col-md-4 col-sm-4">
                    <div class="form-group">
                      <label>Deskripsi Unit</label>
                      <input type="text" name="deskripsi_unit" id="deskripsi_unit" class="form-control disabled-action" value="<?php echo $deskripsi_unit;?>">
                    </div>
                  </div>
                  <div class="col-xs-12 col-md-4 col-sm-4">
                    <div class="form-group">
                      <label>Kelengkapan</label>
                      <input type="text" name="kelengkapan" id="kelengkapan" class="form-control disabled-action" value="<?php //echo $kelengkapan;?>">
                    </div>
                  </div>                 
                </div>
                <div class="row">
                  <div class="col-xs-12 col-md-4 col-sm-4">
                    <div class="form-group">
                      <label>Keterangan</label>
                      <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Masukan keterangan mutasi" value="<?php echo $keterangan;?>" autocomplete="off">
                    </div>
                  </div>
                  <div class="col-xs-12 col-md-2 col-sm-2">
                    <div class="form-group">
                      <br>
                      <button id="submit-btn" type="submit" class="btn btn-info submit-btn"><i class="fa fa-plus"></i> Add Item</button>
                      <!-- <button id="btn-s" onclick="__addItem();" type="button" class="btn btn-primary <?php echo $disabled_action;?>"><i class="fa fa-plus"></i> Add Item</button> -->
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
      </div>
    </div>
    <div class="col-lg-12 padding-left-right-5">
      <div class="panel panel-default">
        <div class="table-responsive">
          <table class="table table-hover table-striped" id="lstMutasi">
            <thead>
              <tr>
                <th>#</th>
                <th>No. Trans</th>
                <th>Tanggal</th>
                <th>No. Rangka</th>
                <th>Keterangan</th>
                <th>Jenis</th>
                <th>Asal</th>
                <th>Tujuan</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $n=0;$approval=0;$status="";
                if(isset($list)){
                  if($list->totaldata>0){
                    foreach ($list->message as $key => $value) {
                      $n++;
                      $approval = ($value->APPROVAL_STATUS)?$value->APPROVAL_STATUS:$approval;
                      $disabled_action =($approval==0)?'':'disabled-action';
                      $status=($approval>0)?'Approve':'Open';
                      $status=($value->STATUS_MUTASI)?'Close':$status;
                      //$dsb=($value->STATUS_MUTASI)?'disabled-action':'';
                      ?>
                        <tr>
                          <td><?php //echo $n;?>
                            <span class="pull-right">
                              <a class="<?php echo ($asal_view)?'disabled-action':'';?> <?php echo $disabled_action;?>" onclick="__hapus('<?php echo $value->ID;?>');" id="l_<?php echo $value->ID;?>"><i class="fa fa-trash" ></i></a>
                            </span>
                          </td>
                          <td class='text-center table-nowarp'><?php echo $value->NO_TRANS;?></td>
                          <td class='text-center'><?php echo tglFromSql($value->TGL_TRANS);?></td>
                          <td class='text-center'><?php echo $value->PART_NUMBER;?></td>
                          <td class='td-overflow-50' title="<?php echo $value->KETERANGAN;?>"><?php echo $value->KETERANGAN;?></td>
                          <td class='table-nowarp'><?php echo $value->JENIS_TRANS;?></td>
                          <td class='table-nowarp'><?php echo $value->KD_GUDANG_ASAL;?></td>
                          <td class='table-nowarp'><?php echo($value->JENIS_TRANS=='Antar Gudang')? $value->KD_GUDANG_TUJUAN:$value->KD_DEALER_TUJUAN;?></td>
                          <td class='table-nowarp'><?php echo $status;?></td>
                        </tr>
                      <?php
                    }
                  }else{
                    belumAdaData(9);
                  }
                }else{
                  belumAdaData(9);
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
 </section>

<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
  $(document).ready(function(){
    var asal="<?php echo $kd_gudang_asal;?>"
    var jmut="<?php echo $jenis_mutasi;?>"
    var apv="<?php echo $approval;?>";
  
    $('#jenis_mutasi').change(function(){      
      if(asal=='' || jmut==''){
        $('#deskripsi_unit').val('');       
        $('#no_rangka').val('');       
        $('#kelengkapan').val('');
        $('#kd_gudang').val('').select();
        $('#kd_gudang_tujuan').val('').select();
        $('#kd_dealer_tujuan').val('').select();
        $('#kd_gudang').removeClass('disabled-action');
      }
     
      
    })
    if(parseInt(apv)>=0){
      $('#kd_gudang_tujuan').addClass("disabled-action");
      $('#kd_gudang').addClass('disabled-action');
      $('#kd_dealer_tujuan').addClass('disabled-action');
      $('#no_rangka').removeClass("disabled-action");
      if(parseInt(apv)>0){
        $('#submit-btn').addClass('disabled-action');
        $('#no_rangka').addClass("disabled-action");
      }
      __get_kendaraan();
    }
    if(asal!='' && jmut=='Antar Gudang'){
      $('#kd_gudang_tujuan option').addClass("hidden")
      $('#kd_gudang_tujuan').removeClass("hidden").attr('required',true);
      $("#kd_gudang_tujuan option:not(.<?php echo $kd_gudang_asal;?>)").removeClass("hidden");
      $('#kd_dealer_tujuan').addClass("hidden");
    }
    if(asal!='' && jmut=='Antar Dealer'){
      $('#kd_dealer_tujuan option').addClass('hidden')
      $('#kd_gudang_tujuan').addClass("hidden").removeAttr('required');
      $('#kd_gudang_tujuan option').addClass("hidden");
      $('#kd_dealer_tujuan').removeClass("hidden");
      $("#kd_dealer_tujuan option:not(.<?php echo $defaultDealer;?>)").removeClass('hidden')
    }

 
    $('#kd_gudang').on("change",function(){
      var jm =$("#jenis_mutasi").val()
     
        if (jm == 'Status Unit') {       
            
            $('#label_status').removeClass("hidden");
            $('#label_lokasi').addClass("hidden");
            $('#kd_status_tujuan').removeClass("hidden").val('').select();
            $('#kd_status_tujuan').attr('required',true);
            $('#kd_gudang_tujuan').addClass("hidden").val('').select();
            $('#kd_gudang_tujuan').removeAttr('required');   
            $('#kd_dealer_tujuan option').addClass('hidden')        
            $('#kd_dealer_tujuan').addClass("hidden");
            $('#kd_dealer_tujuan').addClass("disabled-action");         
           __get_kendaraan_nrfs()
            
        }else { 
            if(jm=='Antar Gudang'){
            $('#label_status').addClass("hidden");
            $('#label_lokasi').removeClass("hidden");
            $('#kd_gudang_tujuan option').addClass("hidden")
            $('#kd_gudang_tujuan').removeClass("disabled-action")
            $('#kd_gudang_tujuan').removeClass("hidden").val('').select();
            $('#kd_gudang_tujuan').attr('required',true)
            $("#kd_gudang_tujuan option:not(."+$(this).val()+")").removeClass("hidden");
            $('#kd_status_tujuan').addClass("hidden");
            $('#kd_dealer_tujuan').addClass("hidden");            
            $('#kd_dealer_tujuan option').addClass('hidden');
              __get_kendaraan();
            
          }else if(jm=='Antar Dealer'){
            $('#label_status').addClass("hidden");
            $('#label_lokasi').removeClass("hidden");
            $('#kd_dealer_tujuan option').addClass('hidden')
            $('#kd_gudang_tujuan').addClass("hidden").val('').select();
            $('#kd_gudang_tujuan').removeAttr('required');
            $('#kd_gudang_tujuan option').addClass("hidden");
            $('#kd_status_tujuan').addClass("hidden");
            $('#kd_dealer_tujuan').removeClass("hidden");
            $('#kd_dealer_tujuan').removeClass("disabled-action");
            $("#kd_dealer_tujuan option:not(.<?php echo $defaultDealer;?>)").removeClass('hidden');

            __get_kendaraan();
          }
         
        }
        
    })

     $('#jenis_mutasi').on("change",function(){
         
         
          $('#inputpicker-2').val('');
         
      })

      $('#kd_status_tujuan').on("change",function(){
        var jm = $("#jenis_mutasi").val()
        var tujuan = $("#kd_status_tujuan").val()
          if (jm == 'Status Unit' && tujuan != '') {            
            __get_kendaraan_nrfs()
          }
      })

  })
  function __get_kendaraan_nrfs(){    
    var datas=[];
    $.getJSON(http+"/motor/getNoRangkaNRFS",{'d':$('#kd_dealer').val(),'s':$("#kd_status_tujuan").val()},function(result){
      if(result.length>0){
        $.each(result,function(e,d){
          datas.push({
            'value' : d.NO_MESIN,
            'text'  : d.NO_MESIN,
            'Deskripsi':' ['+d.KD_ITEM+' ] '+d.NAMA_TYPEMOTOR+' '+d.NAMA_ITEM,
            'No.Mesin':d.NO_MESIN,
            'Kelengkapan':d.KSU
          })
        })
      }
    });
    $('#no_rangka').inputpicker({
        data : datas,
        fields :['No.Mesin','Deskripsi','Kelengkapan'],
        fieldText : 'text',
        fieldValue : 'value',
        filterOpen : false,
        headShow:true
      }).change(function(e){
        e.preventDefault();
         var dx=datas.findIndex(obj => obj['value'] === $(this).val());

         if(dx>-1){
          $('#deskripsi_unit').val(datas[dx]['Deskripsi']);
          $('#kelengkapan').val(datas[dx]['Kelengkapan']);
         }
      })
      
  }

  function __get_kendaraan(){    
    var datas=[];
    $.getJSON(http+"/motor/getNoRangka",{'d':$('#kd_dealer').val(),'g':$("#kd_gudang").val()},function(result){
      if(result.length>0){
        $.each(result,function(e,d){
          datas.push({
            'value' : d.NO_MESIN,
            'text'  : d.NO_MESIN,
            'Deskripsi':' ['+d.KD_ITEM+' ] '+d.NAMA_PASAR,
            'No.Mesin':d.NO_MESIN,
            'Kelengkapan':d.KSU
          })
        })
      }
    });
    $('#no_rangka').inputpicker({
        data : datas,
        fields :['No.Mesin','Deskripsi'],
        fieldText : 'text',
        fieldValue : 'value',
        filterOpen : false,
        headShow:true
      }).change(function(e){
        e.preventDefault();
         var dx=datas.findIndex(obj => obj['value'] === $(this).val());
         if(dx>-1){
          $('#deskripsi_unit').val(datas[dx]['Deskripsi']);
          $('#kelengkapan').val(datas[dx]['Kelengkapan']);
         }
      })
      
  }
  function __hapus(id){
    if(confirm('Yakin mutasi ini akan di hapus?')){
      $('#l_'+id).html("<i class='fa fa-spinner fa-spin'></i>");
      $.getJSON(http+"/motor/mutasiunit_del",{'id':id},function(result){
        location.reload();
      })
    }
  }
  function addNew(){
    document.location.href="<?php echo base_url("motor/mutasiunit_add");?>"
  }


  

</script>