<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 

$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$start_date =($this->input->get("start_date"))?$this->input->get("start_date"):date("d/m/Y", strtotime("first day of this month"));
$end_date = ($this->input->get("end_date"))?$this->input->get("end_date"):date("d/m/Y");

?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('sales_event/create_event_simpan');?>" method="post">
  <section class="wrapper">

    <div class="breadcrumb margin-bottom-10">
      <?php echo breadcrumb();?>

      <div class="bar-nav pull-right ">
        <button id="submit-btn" type="submit" class="btn btn-default <?php echo  $status_e ?> submit-btn">Simpan</button>
      </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">
      <div class="panel margin-bottom-10">
        <div class="panel-heading">
          Detail Event
          <span class="tools pull-right">
            <a class="fa fa-chevron-up" href="javascript:;"></a>
          </span>
        </div>
        <div class="panel-body panel-body-border" style="display: show;">

          <table class="table table-striped b-t b-light">
            <tr>
              <td>Dealer</td>
              <td>: <?php echo $list->message[0]->KD_DEALER; ?> - <?php echo $list->message[0]->NAMA_DEALER; ?></td>
            </tr>
            <tr>
              <td>Event ID</td>
              <td>: <?php echo $list->message[0]->KD_EVENT; ?></td>
            </tr>
            <tr>
              <td>Nama Event</td>
              <td>: <?php echo $list->message[0]->NAMA_EVENT; ?></td>
            </tr>
            <tr>
              <td>Jenis Event</td>
              <td>: <?php echo $list->message[0]->NAMA_JENIS_EVENT; ?></td>
            </tr>
            <tr>
              <td>Start Date</td>
              <td>: <?php echo tglFromSql($list->message[0]->START_DATE); ?></td>
            </tr>
            <tr>
              <td>End Date</td>
              <td>: <?php echo tglFromSql($list->message[0]->END_DATE); ?></td>
            </tr>
            <tr>
              <td>Target Unit</td>
              <td>: <?php echo number_format($list->message[0]->TARGET_UNIT,0); ?></td>
            </tr>
            <tr>
              <td>Target Revenue</td>
              <td>: <?php echo number_format($list->message[0]->TARGET_REVENUE,0); ?></td>
            </tr>
            <tr>
              <td>Alamat</td>
              <td>: <?php echo $list->message[0]->ALAMAT_EVENT; ?></td>
            </tr>
            <tr>
              <td>Keterangan</td>
              <td>: <?php echo $list->message[0]->KETERANGAN_EVENT; ?></td>
            </tr>
            <tr>
              <td>Status Approval</td>
              <td>: <?php if($list->message[0]->APPROVAL_MD == 0){
                echo 'Waiting';
              }elseif($list->message[0]->APPROVAL_MD == 1){
                echo 'Approved';
              } else{
                echo 'Rejected';
              }
              ;?></td>
            </tr>


          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">
      <div class="panel margin-bottom-10">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#budget">Budget</a></li>
          <li><a href="#unit">Unit to Display</a></li>
        </ul>

        <div class="tab-content">
          <div id="budget" class="tab-pane fade in active">
            <div class="form-group">  
             <form name="add_name" id="add_name">
              <table class="table table-striped b-t b-light" id="budget_table">
                <tr>
                  <th>Kode</th>
                  <th>Nama Budget</th>
                  <th>Jumlah</th>
                  <th>Katerangan</th>
                </tr>
                <tr>
                  <td><input type="text" name="kode_budget[]" placeholder="Kode Budget" class="form-control name_list" /></td>
                  <td><input type="text" name="nama_budget[]" placeholder="Nama Budget" class="form-control name_list" /></td>
                  <td><input type="text" name="jumlah[]" placeholder="Jumlah" class="form-control name_list" /></td>
                  <td><input type="text" name="keterangan[]" placeholder="Keterangan" class="form-control name_list" /></td>
                  <td colspan="6"><input type="button" class="btn btn-primary" name="budget_add" value="Add" id="budget_add"></td>
                </tr>
              </table>
            </form>
          </div>
        </div>
        <div id="unit" class="tab-pane fade">
          <div class="form-group">  
             <form name="add_name" id="add_name">
              <table class="table table-striped b-t b-light" id="budget_table">
                <tr>
                  <th>Kode Item</th>
                  <th>Nama Item</th>
                  <th>Jumlah</th>
                  <th>Katerangan</th>
                </tr>
                <tr>
                  <td><input type="text" name="kode_budget[]" placeholder="Kode Budget" class="form-control name_list" /></td>
                  <td><input type="text" name="nama_budget[]" placeholder="Nama Budget" class="form-control name_list" /></td>
                  <td><input type="text" name="jumlah[]" placeholder="Jumlah" class="form-control name_list" /></td>
                  <td><input type="text" name="keterangan[]" placeholder="Keterangan" class="form-control name_list" /></td>
                  <td colspan="6"><input type="button" class="btn btn-primary" name="budget_add" value="Add" id="budget_add"></td>
                </tr>
              </table>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>

</section>
</form>

<script type="text/javascript">
  $(document).ready(function(){

    $('.selectpicker').selectpicker();

    var date = new Date();
    date.setDate(date.getDate());

    /*pilihan propinsi*/
    $('#kd_propinsi').on('change', function () {
      loadData('kd_kabupaten', $('#kd_propinsi').val(), '0')
    })
    $('#kd_kabupaten').on('change', function () {
      loadData('kd_kecamatan', $(this).val(), '0')
    })
    $('#kd_kecamatan').on('change', function () {
      loadData('kd_desa', $(this).val(), '0')
    })
    $('#baru').click(function(){
      document.location.reload();
    })

    var i=1;  
      $('#budget_add').click(function(){  
           i++;  
           $('#budget_table').append('<tr id="row'+i+'"><td><input type="text" name="kode_budget[]" placeholder="Kode Budget" class="form-control name_list" /></td><td><input type="text" name="nama_budget[]" placeholder="Nama Budget" class="form-control name_list" /></td><td><input type="text" name="jumlah[]" placeholder="Jumlah" class="form-control name_list" /></td><td><input type="text" name="keterangan[]" placeholder="Keterangan" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      }); 
      $('#submit').click(function(){            
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  

  });
  function loadData(id, value, select) {
    var param = $('#' + id + '').attr('title');
    $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
    var urls = "<?php echo base_url(); ?>customer/" + param;
    var datax = {"kd": value};
    $('#' + id + '').attr('disabled','disabled');
    $.ajax({
      type: 'GET',
      url: urls,
      data: datax,
      typeData: 'html',
      success: function (result) {
        $('#' + id + '').empty();
        $('#' + id + '').html(result);
        $('#' + id + '').val(select).select();
        $('#l_' + param + '').html('');
        $('#' + id + '').removeAttr('disabled');
      }
    });
  }


</script>