<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}


$ROOT = ($this->session->userdata('nama_group')=='Root'?'':'disabled');

$status_c = (isBolehAkses('c') ? '' : 'remove-button' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>

<style type="text/css">
  .panel-footer {
    background-color: #fff;
    border: none;
  }


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

</style>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Print Surat Pengantar</h4>
</div>

<div class="modal-body">

  <div  id="printarea">
    

    <p>Banjarmasin, <?php echo date('d/m/Y'); ?></p>
    <p>Sp No. <?php echo $no_kpb;?></p>
    <p>Kepada Yth.</p>
    <p><strong>PT. TRIO MOTOR</strong></p>
    <p>MAIN DEALER HONDA KAL-SEL-TENG</p>
    <p>Jl. Perintis Kemerdekaan No. 45 AA</p>
    <p>Telp, 0511-3355400, 3355500</p>
    <p>Banjarmasin</p><br>

    <p>Dengan hormat,</p>
    <p>Bersama ini kami kirimkan tagihan Kartu Perawatan Berkala (KPB) yang telah dilaksnakan oleh AHASS.</p>
    <p>Adapun perincian penagihan sebagai berikut : </p>


   <table border='1' class="table-full table-border" style="width:750px;border-collapse: collapse;">
 
      
      <tr>
        <th style="text-align: center;width:8px;">Kode Mesin</th>
        <th style="text-align: center;width:8px;">KPB 1</th>
        <th style="text-align: center;width:8px;">KPB 2</th>
        <th style="text-align: center;width:8px;">KPB 3</th>
        <th style="text-align: center;width:8px;">KPB 4</th>
      </tr>


    <tbody>
      <?php
     
      
        if (($list->totaldata >0 )) {
          foreach ($list->message as $key => $value) {
          
            ?>
            <tr>
                <td style="text-align: center !important;width:5px!important;"><?php echo $value->KD_MESIN; ?></td>
                <td style="text-align: center !important;width:5px!important;"><?php echo $value->KPB1; ?></td>
                <td style="text-align: center !important;width:5px!important;"><?php echo $value->KPB2; ?></td>
                <td style="text-align: center !important;width:5px!important;"><?php echo $value->KPB3; ?></td>
                <td style="text-align: center !important;width:5px!important;"><?php echo $value->KPB4; ?></td>
            </tr>
           
            <?php
           
          }
        }
      //}
      ?>
    </tbody>
  
   
  </table>
     <p>Demikian pengajuan kami untuk penagihan KPB dan terima kasih atas perhatiannya.</p>
    <table style="width:90%">
        <td colspan="4">
            <div class="project">
              <div>
                <span class="content" style="text-align: left;"></span>
                <span class="content" style="text-align: left;"></span>
                <span class="content" style="text-align: left;"></span>
                <span class="content" style="text-align: left;">Banjarmasin, <?php echo date('d/m/Y');?></span>
              </div>
            </div>
        </td>
        
      </tr>
      <tr>
        <td colspan="4">
            <div class="project">
              <div>
                <span class="content" style="text-align: center; padding-top: 50px;"></span>
                <span class="content" style="text-align: center; padding-top: 50px;"></span>
                <span class="content" style="text-align: center; padding-top: 50px;"></span>
                <span class="content" style="text-align: center; padding-top: 50px;"><u><?php echo $this->session->userdata('user_name');?></u></span>
              </div>
            </div>
        </td>
      </tr>
      <tr>
        <td colspan="4">
            <div class="project">
              <div>
                 <span class="content" style="text-align: center; "></span>
                 <span class="content" style="text-align: center; "></span>
                 <span class="content" style="text-align: center; "></span>
                <span class="content" style="text-align: center;"><?php echo $this->session->userdata('nama_group');?></span>
              </div>
            </div>
        </td>
      </tr>
    </table>

  </div>


  <footer class="panel-footer">

     

  </footer>
      <!-- <input type="submit" name=""> -->

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

