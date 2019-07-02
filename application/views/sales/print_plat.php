<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}


$status_c = (isBolehAkses('c') ? '' : 'remove-button' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>
<style type="text/css">
#desc {
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 20px;
    width: 100%;
}
.project {
    /* float: left; */
    text-align: left;
    display: table;
    width: 100%;
}
.project div {
    display: table-row;
}

.project .title {
    color: #5D6975;
    width: 90px;
}

.project span {
    text-align: left;
    /* width: 100px; */
    /* margin-right: 15px; */
    padding: 2px 0;
    display: table-cell;
    /* font-size: 0.8em; */
}

.project .content {
    width: 150px;
}

.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    border-bottom: 0px solid #e9e9e9 ! important;
}
.panel-footer {
background-color: #fff;
border: none;
}

</style>


<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Daftar Plat</h4>
</div>

<div class="modal-body">

  <div  id="printarea">


    <?php 
    if($list && (is_array($list->message) || is_object($list->message))):
    foreach ($list->message as $key => $list_row): ?>
        <table id="list_data" class="table">
          <tr>
            <td colspan="2"><h3><strong>DATA PENGURUSAN PLAT</strong></h3></td>
            <td colspan="2"><?php echo $list_row->NAMA_DEALER;?></td>
          </tr>

          <tr><td colspan="4">&nbsp;</td></tr></tr>

          <tr>
            <td colspan="4" valign="top">
                <div class="project">
                  <div><span class="title">Nomor</span><span class="content"> <?php echo $list_row->NO_TRANS;?></span></div>
                  <div><span class="title">Tanggal Pengajuan Plat</span><span class="content"> <?php echo tglfromSql($list_row->TGL_STNK);?></span></div>
                  <div><span class="title">Wilayah</span><span class="content"> <?php echo $list_row->NAMA_KABUPATEN;?></span></div>
                </div>
            </td>
          </tr>

          <tr><td colspan="4">&nbsp;</td></tr>

            <!-- <thead> -->
            <tr style="border-bottom: 1px solid;">
            <th style="width:45px; vertical-align: middle;">No</th>
            <th>Nama</th>
            <th>No Mesin</th>
            <th>No Plat</th>
            </tr>
            <!-- </thead> -->
            <!-- <tbody> -->

            <?php 
            $no = 1;
                if($detail && (is_array($detail->message) || is_object($detail->message))): 
                foreach ($detail->message as $key => $value):
                    if($value->STNK_ID == $list_row->ID):
            ?>
            <tr style="border-bottom: 1px solid;">
                <td><?php echo $no;?></td>
                <td><?php echo $value->NAMA_PEMILIK;?></td>
                <td><?php echo $value->NO_MESIN;?></td>
                <td></td>
            </tr>

            <?php $no++; endif; endforeach; endif;?>

            <!-- </tbody> -->


          <tr><td colspan="5">&nbsp;</td></tr>
          
          <tr>
            <td colspan="4"><?php echo tglfromSql($list_row->TGL_PENGAJUANPLAT);?></td>
            <!-- <td colspan="4"><?php echo date('d/m/Y H:i:s'); ?></td> -->
          </tr>

          <tr><td colspan="5">&nbsp;</td></tr>

          <tr>
            <td colspan="5">
                <div class="project">
                  <div>
                    <span class="title" style="text-align: center;">Pengurus,</span> 
                    <span class="title" style="text-align: center;">Samsat,</span> 
                  </div>
                </div>
            </td>
          </tr>
          <tr>
            <td colspan="5">
                <div class="project">
                  <div>
                    <span class="content" style="text-align: center; padding-top: 100px;"><u><?php echo $list_row->NAMA_PENGURUS;?></u></span>
                    <span class="content" style="text-align: center; padding-top: 100px;"><u></u></span>
                  </div>
                </div>
            </td>
          </tr>

        </table>

    <?php endforeach;
    else: ?>

    <h5 class="modal-title" id="myModalLabel"><i class="fa fa-info-circle fa-fw"></i> Belum ada data / data tidak ditemukan</h5>
    <?php endif;?>

  </div>

  <footer class="panel-footer">

      <div class="row">

          <div class="col-sm-5">
              <small class="text-muted inline m-t-sm m-b-sm"> 
                  <?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total Data " . $list->totaldata . " items</i>") : '' ?>
              </small>
          </div>

          <div class="col-sm-7 text-right text-center-xs">                
              <?php echo $pagination; ?>
          </div>

      </div>

  </footer>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button type="button" onclick="printSj();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>
</div>

<script src="<?php echo base_url('assets/dist/print.min.js');?>"></script>
<script type="text/javascript">
   function printSj() {
      printJS('printarea','html');
       $('#keluar').click();
    }

  $(document).ready(function(){

    var date = new Date();
    date.setDate(date.getDate());

  /*
    $('.datetime').datetimepicker({
      format:'hh:mm:ss',
      pickDate: false,
      // pickTime: false,
      autoclose: true
    });*/
      $(".modal-body .pagination li a").click(function(e){
        e.preventDefault();

        var url = $(this).attr('href');

        var modalId = $(this).parents('.modal').attr('id');



        $.getJSON(url, function(data, status) {

          // console.log(data);

            $("#"+modalId).find(".modal-content").html(data);


        });

        // $(this).removeAttr('href');
        // alert(modalId);
      });
    
      $('.date').datepicker({
          format: 'dd/mm/yyyy',
          endDate: date,
          autoclose: true
      });

  });

</script>