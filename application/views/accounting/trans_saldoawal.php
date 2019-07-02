<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$kd_akun="";$nama_akun="";$saldo_awal="";$id="";

if($this->input->get('edit')){
    // if(isset($edit)){
      if($edit->totaldata > 0){
        foreach ($edit->message as $key => $value) {
          $kd_akun = $value->KD_AKUN;
          $nama_akun = $value->NAMA_AKUN;
          $saldo_awal = $value->SALDO_AWAL;
        }
      }
    // }
  }

  if($this->input->get('edit')){
    // if(isset($edit)){
      if($edit->totaldata > 0){
        foreach ($edit->message as $key => $value) {
          $id=$value->ID;
          $kd_akun = $value->KD_AKUN;
          $nama_akun = $value->NAMA_AKUN;
          $saldo_awal = $value->SALDO_AWAL;
        }
      }
    // }
  }
?>
<section class="wrapper">
  
  <div class="breadcrumb margin-bottom-10">

    <?php echo breadcrumb();?>

    <div class="bar-nav pull-right ">
    </div>

  </div>

<div class="col-lg-12 padding-left-right-10">

  <div class="panel margin-bottom-10">

    <div class="panel-heading">
      Saldo Awal Perkiraan
      <span class="tools pull-right">
        <a class="fa fa-chevron-up" href="javascript:;"></a>
      </span>
    </div>

    <div class="panel-body panel-body-border" style="display: up;">

      <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('finance/add_saldoawal_simpan'); ?>">

        <div class="row">
          
          <input type="hidden" id="id" name="id" class="form-control" value="<?php echo $id;?>" disabled>

          <div class="col-xs-12 col-sm-2">
            <div class="form-group">
              <label>Dealer</label>
              <select name="kd_dealer" id="kd_dealer" class="form-control" disabled="disabled" required="true">
                <option value="">- Pilih Dealer -</option>
                  <?php
                    foreach ($dealer->message as $key => $group) :
                      if ($KD_DEALER != ''):
                        $default = ($KD_DEALER == $group->KD_DEALER) ? " selected" : " ";
                        else:
                          $default = ($this->session->userdata("kd_dealer") == $group->KD_DEALER) ? " selected" : '';
                        endif;
                        ?>
                        <option value="<?php echo $group->KD_DEALER; ?>" <?php echo $default; ?> ><?php echo $group->NAMA_DEALER; ?></option>
                      <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="col-xs-12 col-sm-2">
            <div class="form-group">
              <label>Kode Akun</label>
              <?php if($this->input->get('edit')):?>
              <input id="kd_akun_edit" name="kd_akun_edit" class="form-control" value="<?php echo $kd_akun;?>" disabled>
              <?php else:?>
              <input id="kd_akun" name="kd_akun" class="form-control" value="<?php echo $kd_akun;?>" required="true">
              <?php endif;?>
            </div>
          </div>

          <div class="col-xs-12 col-sm-4">
            <div class="form-group">
              <label>Nama Akun</label>
              <input type="text" id="nama_akun" name="nama_akun" class="form-control" readonly="true" value="<?php echo $nama_akun;?>">
            </div>
          </div>

          <div class="col-xs-12 col-sm-2">
            <div class="form-group">
              <label>Saldo Awal</label>
              <input type="text" name="saldo_awal" id="saldo_awal" class="form-control" placeholder="Masukkan saldo" value="<?php echo $saldo_awal;?>">
            </div>
          </div>

          <div class="col-xs-12 col-sm-2">
            <div class="form-group">
              <label>             </label>
              <a id="submit-btn" onclick="addData();" class="btn btn-primary">Simpan</a>
            </div>
          </div>

        </div>
      </form>
    </div>
  </div>
</div>

<div class="col-lg-12 padding-left-right-10">

  <div class="panel panel-default">
      <!-- <div class="panel-heading">
        Responsive Table
      </div> -->

      <div class="table-responsive">
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th style="width:40px;">No.</th>
              <th style="width:45px;">Aksi</th>
              <th>Kode Akun</th>
              <th>Nama Akun</th>
              <th class='text-right'>Saldo Awal</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = $this->input->get('page');
            if($list):
              if(is_array($list->message) || is_object($list->message)):
                foreach($list->message as $key=>$row): 
                  $no ++;
                  ?>

                  <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' ';?>" >
                    <td><?php echo $no;?></td>
                    <td class="table-nowarp">
                      <a href="<?php echo base_url('finance/saldoawal?edit='.$row->ID); ?>"class="<?php echo $status_v?>">
                        <i data-toggle="tooltip" data-placement="left" title="Edit" class="fa fa-edit text-success text-active"></i>
                      </a>
                      <?php 
                      if($row->ROW_STATUS == 0){ 
                       ?>
                       <a id="delete-btn<?php echo $no;?>" class="delete-btn <?php echo $status_e?>" url="<?php echo base_url('finance/delete_saldoawal/'.$row->ID); ?>">
                        <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                      </a>
                      <?php
                    }
                    ?>
                  </td>
                  <td class="table-nowarp"><?php echo $row->KD_AKUN;?></td>
                  <td class="table-nowarp"><?php echo $row->NAMA_AKUN;?></td>
                  <td class='text-right'><?php echo number_format($row->SALDO_AWAL);?></td>
                </tr>

                <?php 
              endforeach;
            else:
              ?>
              <tr>
                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                <td colspan="8"><b><?php echo ($list->message); ?></b></td>
              </tr>
              <?php
            endif;
          else:

            belumAdaData(9);

          endif;
          ?>
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

<script type="text/javascript">
 var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
   
    $(document).ready(function(){
        $('#kd_trans').keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();
            }
        });

        $('#nama_trans').keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();
            }
        });

        $('#ar').keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();
            }
        });

      $.getJSON(http+"/cashier/kodeakun",function(result){
      //console.log(result);
        $('#kd_akun').html('');
        var datax=[];
        $.each(result,function(index,d){
          datax.push({
            'value':d.KD_AKUN,
            'text':d.NAMA_AKUN,
            'KD AKUN':d.KD_AKUN,
            'NAMA AKUN':d.NAMA_AKUN
          })
        })
        $('#kd_akun').val('');
        $('#kd_akun').inputpicker({
          data:datax,
          fields:['KD AKUN','NAMA AKUN'],
          headShow:true,
          fieldText:'value',
          filterOpen:true
        }).change(function(e){
      e.preventDefault();
      var dx=datax.findIndex(obj => obj['value'] === $(this).val());
      $('#jml').focus().select();
      if(dx>-1){
        $('#nama_akun').val(datax[dx]['NAMA AKUN']);

      }
    })
      });

    })


</script>