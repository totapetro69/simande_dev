 <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 

$KD_DEALER = ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$KD_MAINDEALER = '';
// $app_level=0;

switch ($reff) {
    case 1:
        $jenis = 'STNK';
        break;
    case 2:
        $jenis = 'BPKB';
        break;
}

/*
if(isset($approval)){
  if($approval->totaldata >0){
    foreach ($approval->message as $key => $value) {
      $app_level  = $value->APP_LEVEL;
      $app_doc  = $value->KD_DOC;
    }
  }
}*/
// $disabled =($app_level >0)?"":"disabled-action";

?>
<section class="wrapper">


<!-- <div style="margin-left:20px;margin-top:50px;"> -->
<!-- </div>  -->

  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>

    <div class="bar-nav pull-right ">
      <a class="btn btn-info" role="button" href="<?php echo base_url('stnk/add_pengurusan/').$jenis;?>"><i class="fa fa-file-o"> Add Pengurusan</i></a>

    </div>
    <!-- </li> -->
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          <i class="fa fa-list fa-fw"></i> List <?php echo $jenis;?>
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
          </span>
      </div>

      <div class="panel-body panel-body-border" style="display: show;">

        <form id="filterForm" action="<?php echo base_url('stnk/list_data/'.$jenis) ?>" class="bucket-form" method="get">


          <input type="hidden" id="tgl_trans" name="tgl_trans" value="<?php echo date('d/m/Y'); ?>">
          <input type="hidden" id="kd_maindealer" name="kd_maindealer" value="<?php echo $KD_MAINDEALER; ?>">

          <!-- <div id="pengurus-url" url="<?php echo base_url('stnk/pengurus_typeahead');?>"></div> -->

          <div class="row">


            <div class="col-xs-12 col-sm-2">
                    
              <div class="form-group">
                  <label>Dealer</label>
                  <select name="kd_dealer" id="kd_dealer" class="form-control" required="true">
                    <?php
                             
                        if (isset($dealer)) {
                            if ($dealer->totaldata > 0) {
                                foreach ($dealer->message as $key => $value) {
                                    $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                                    $select = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $select;
                                    echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                                }
                            }
                        }
                    ?>
                  </select>
              </div>

            </div>
            <div class="col-xs-12 col-sm-4">
                    
              <div class="form-group">
                  <label>Wilayah</label>
                  <select name="kd_kota" id="kd_kota" class="form-control" required="true">
                    <option value="">- Pilih Wilayah -</option>
                    <?php foreach ($kabupaten->message as $key => $group) : 
                        $default=($this->input->get('kd_kota')==$group->KD_KABUPATEN)?" selected":" ";
                    ?>
                      <option value="<?php echo $group->KD_KABUPATEN;?>" <?php echo $default;?> ><?php echo $group->NAMA_KABUPATEN;?></option>
                    <?php endforeach; ?>
                  </select>
              </div>

            </div>

            <div class="col-xs-12 col-sm-2">

              <div class="form-group">

                <label class="control-label" for="date">Periode Awal</label>
                <div class="input-group input-append date">
                    <input class="form-control" id="tgl_awal" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tgl_awal')?$this->input->get('tgl_awal'):date('d/m/Y', strtotime('-30 days')); ?>" type="text"/>
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>

              </div>

            </div>

            <div class="col-xs-12 col-sm-2">

              <div class="form-group">

                <label class="control-label" for="date">Periode Akhir</label>
                <div class="input-group input-append date">
                    <input class="form-control" id="tgl_akhir" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tgl_akhir')?$this->input->get('tgl_akhir'):date('d/m/Y'); ?>" type="text"/>
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>

                </div>

              </div>

            </div>

          </div>

        </form>

      </div>
      
    </div>

  </div>

  <div class="col-lg-12 padding-left-right-10">

    <div class="panel panel-default">       
      <div class="table-responsive">
        <table id="list_data" class="table table-striped table-bordered">
        <thead>
        <tr class="no-hover"><th colspan="10" ><i class="fa fa-list fa-fw"></i> List Pengajuan</th></tr>
        <tr class="no-hover">
          <th rowspan="2" style="width:45px; vertical-align: middle;">No</th>
          <th colspan="4" >No Pajak</th>
          <th colspan="5" >Pengurus</th>
        </tr>

        <tr>
        <th>Aksi</th>
        <th class="<?php echo $jenis == 'BPKB'?'':'hide';?>">Pengajuan Pinjaman</th>
        <th>Tipe Motor</th>
        <th>NO RANGKA</th>
        <th>NO MESIN</th>
        <th>NAMA PEMILIK</th>
        <th>ALAMAT PEMILIK</th>
        <th>STATUS</th>
        <th>Total Biaya</th>
        <!-- <th class="table-nowarp">Terima</th> -->
        <!-- <th class="table-nowarp">Penyerahan</th> -->
        </tr>
        </thead>
        <tbody>
          <?php echo $list_detail; ?>
        </tbody>
        </table>
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

    </div>
  </div>

</section>

<script type="text/javascript" src="<?php echo base_url("assets/js/external/stnk.js");?>"></script>
<script type="text/javascript">
$(document).ready(function(){
  $("#list_data").on('click','.cetak-btn',function(){
    var notrans = $(this).data('trans');
    var href = $(this).data('href');
    var url = "<?php echo base_url('stnk/update_statuscetak');?>";

    $.getJSON(url,{'no_trans':notrans,'status_cetak': 1}, function(data, status){
      console.log(data);
      if(data.status == true){
        window.open(href, '_blank');
        location.reload();

      }

    });

  });

  $("#list_data").on('click','.deletetrans-btn',function(){
    var key = $(this).data('key');

    var data = getData(key);

    var btnId = this.id;
    var defaultBtn = $(this).html();
    var url = "<?php echo base_url('stnk/deleteall_detail');?>";
    var result = confirm("Apakah anda yakin ingin menghapus data ini ?");


    if (result) {

        $(this).html("<i data-toggle='tooltip' data-placement='left' title='hapus' class='fa fa-spinner fa-spin text-danger text'></i>");
        $(".alert-message").fadeIn();


        $.ajax({
          url:url,
          type:"POST",
          dataType: "json",
          data:'detail='+JSON.stringify(data),
          success:function(result){

            if (result.status == true) 
            {
             
              $('.success').animate({ top: "0" }, 500);
              $('.success').html(result.message);

              if (result.location != null) {
                  setTimeout(function() {
                      location.replace(result.location)
                  }, 1000);
              }
            }else{

              $('.error').animate({ top: "0" }, 500);
              $('.error').html(result.message);

              setTimeout(function () {
                  hideAllMessages();
                  $("#" + btnId).html(defaultBtn);
              }, 4000);
              
            }
     
          }
        });
    }
    // alert(key);
  })

  $("#list_data").on('click','.printallow-btn',function(){

    var key = $(this).data('key');
    var btnId = this.id;
    var defaultBtn = $(this).html();
    var result = confirm("Apakah anda yakin ingin mengizinkan cetak data ini ?");
    var notrans = $(this).data('trans');
    var url = "<?php echo base_url('stnk/update_statuscetak');?>";

    if (result) {

        $(this).html("<i data-toggle='tooltip' data-placement='left' title='hapus' class='fa fa-spinner fa-spin text-danger text'></i>");
        $(".alert-message").fadeIn();


        $.getJSON(url,{'no_trans':notrans,'status_cetak': 0}, function(data, status){
          if(data.status == true){

            $('.success').animate({ top: "0" }, 500);
            $('.success').html(data.message);

            setTimeout(function () {
                $("#cetak-btn"+key).removeClass('disabled-action');

                hideAllMessages();
                $("#" + btnId).remove();
            }, 4000);

          }else{

            $('.error').animate({ top: "0" }, 500);
            $('.error').html(data.message);

            setTimeout(function () {
                hideAllMessages();
                $("#" + btnId).html(defaultBtn);
            }, 4000);
            
          }

        });
    }
    // alert(key);
  })

  $("#list_data").on('click','.req-btn', function(){
    var url = $(this).data('url');
    // var jenis_req = $(this).val();
    
    var data = {
      id : $(this).data('id'),
      ajukan : $(this).is(':checked')
    }
    // alert(url);

    $.ajax({
      url:url,
      type:"POST",
      dataType: "json",
      data:data,
      success:function(result){

        if (result.status == true) 
        {
         
          $('.success').animate({ top: "0" }, 500);
          $('.success').html(result.message).fadeIn();

          setTimeout(function () {
              hideAllMessages();
              $("#store-btn").removeClass("disabled");
              // $("#store-btn").html(defaultBtn);
          }, 4000);
        }else{

          $('.error').animate({ top: "0" }, 500);
          $('.error').html(result.message).fadeIn();

          setTimeout(function () {
              hideAllMessages();
              $("#store-btn").removeClass("disabled");
              // $("#store-btn").html(defaultBtn);
          }, 4000);
          
        }

      }
    });

  })

});

function getData(key){
  var data=[];

  var totalDetail = $(".id_"+key).length;

  if(totalDetail > 0)
  {
    for (i = 0; i < totalDetail; i++) {
      data.push({
        'id': $(".id_"+key+":eq(" + i + ")").val(),
        'row_status': $(".row_status_"+key+":eq(" + i + ")").val()
      });
    }
  }

  return data;

}
</script>