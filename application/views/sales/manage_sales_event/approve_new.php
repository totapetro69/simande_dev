<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>


<section class="wrapper">

  <div class="breadcrumb margin-bottom-10">
   <?php echo breadcrumb();?>

   <div class="bar-nav pull-right ">
    <a class="btn btn-default <?php echo $status_c?>" href="<?php echo base_url('sales_event/list_event');?>" role="button">
      <i class="fa fa-list-ul fa-fw"></i>List Event
    </a>
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
    <div class="panel-heading">
      People
      <span class="tools pull-right">
        <a class="fa fa-chevron-up" href="javascript:;"></a>
      </span>
    </div>
    <div class="panel-body panel-body-border" style="display: show;">

      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th style="width:40px;">No.</th>
            <th>Kode Sales</th>
            <th>Nama Sales</th>
            <th>Jabatan</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = $this->input->get('page');
          if($people):
            if(is_array($people->message) || is_object($people->message)):
              foreach($people->message as $key=>$row): 
                $no ++;
                ?>

                <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' ';?>" >
                  <td><?php echo $no;?></td>
                  <td><?php echo $row->KD_SALES?></td>
                  <td><?php echo $row->NAMA_SALES;?></td>
                  <td><?php echo $row->JABATAN_SALES;?></td>
                  <td><?php echo $row->ROW_STATUS == 0 ? 'Aktif':'Tidak Aktif';?></td>
                </tr>

                <?php 
              endforeach;
            else:
              ?>
              <tr>
                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                <td colspan="5"><b><?php echo ($people->message); ?></b></td>
              </tr>
              <?php
            endif;
          else:
            ?>
            <tr>
              <td>&nbsp;<i class="fa fa-info-circle"></i></td>
              <td colspan="5"><b>Ada error, harap hubungi bagian IT</b></td>
            </tr>
            <?php
          endif;
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

 <div class="col-lg-12 padding-left-right-10">
  <div class="panel margin-bottom-10">
    <div class="panel-heading">
      Budget
      <span class="tools pull-right">
        <a class="fa fa-chevron-up" href="javascript:;"></a>
      </span>
    </div>
    <div class="panel-body panel-body-border" style="display: show;">

      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th style="width:40px;">No.</th>
            <th>Kode Budget</th>
            <th>Nama Budget</th>
            <th>Jumlah Budget</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = $this->input->get('page');
          if($budget):
            if(is_array($budget->message) || is_object($budget->message)):
              foreach($budget->message as $key=>$row): 
                $no ++;
                ?>

                <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' ';?>" >
                  <td><?php echo $no;?></td>
                  <td><?php echo $row->KD_BUDGET;?></td>
                  <td><?php echo $row->NAMA_BUDGET;?></td>
                  <td><?php echo number_format($row->JUMLAH_BUDGET,0);?></td>
                  <td><?php echo $row->ROW_STATUS == 0 ? 'Aktif':'Tidak Aktif';?></td>
                </tr>

                <?php 
              endforeach;
            else:
              ?>
              <tr>
                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                <td colspan="5"><b><?php echo ($budget->message); ?></b></td>
              </tr>
              <?php
            endif;
          else:
            ?>
            <tr>
              <td>&nbsp;<i class="fa fa-info-circle"></i></td>
              <td colspan="5"><b>Ada error, harap hubungi bagian IT</b></td>
            </tr>
            <?php
          endif;
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

 <div class="col-lg-12 padding-left-right-10">
  <div class="panel margin-bottom-10">
    <div class="panel-heading">
      Unit to Display
      <span class="tools pull-right">
        <a class="fa fa-chevron-up" href="javascript:;"></a>
      </span>
    </div>
    <div class="panel-body panel-body-border" style="display: show;">

      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th style="width:40px;">No.</th>
            <th>Kode Item</th>
            <th>Nama Item</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = $this->input->get('page');
          if($unit):
            if(is_array($unit->message) || is_object($unit->message)):
              foreach($unit->message as $key=>$row): 
                $no ++;
                ?>

                <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' ';?>" >
                  <td><?php echo $no;?></td>
                  <td><?php echo $row->KD_ITEM;?></td>
                  <td><?php echo $row->NAMA_ITEM;?></td>
                  <td><?php echo $row->ROW_STATUS == 0 ? 'Aktif':'Tidak Aktif';?></td>
                </tr>

                <?php 
              endforeach;
            else:
              ?>
              <tr>
                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                <td colspan="5"><b><?php echo ($unit->message); ?></b></td>
              </tr>
              <?php
            endif;
          else:
            ?>
            <tr>
              <td>&nbsp;<i class="fa fa-info-circle"></i></td>
              <td colspan="5"><b>Ada error, harap hubungi bagian IT</b></td>
            </tr>
            <?php
          endif;
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="col-lg-12 padding-left-right-10">

  <div class="panel panel-default">
    <?php
    if($list->message[0]->APPROVAL_MD == 0){
                      ?>

    <footer class="panel-footer">
      <div class="row">
        <div class="col-sm-8">
        </div>
        <div class="col-sm-2">
          <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('sales_event/approval_event_simpan/' . $list->message[0]->ID); ?>">
            <input id="id" type="hidden" name="id" value="<?php echo $list->message[0]->ID; ?>">
            <input id="id" type="hidden" name="status_event" value="1">
            <button id="submit-btn" type="submit" class="btn btn-default <?php echo  $status_e ?> submit-btn">Approve</button>
          </form>
        </div>
        <div class="col-sm-2 text-right text-center-xs">                
         <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('sales_event/approval_event_simpan/' . $list->message[0]->ID); ?>">
          <input id="id" type="hidden" name="id" value="<?php echo $list->message[0]->ID; ?>">
          <input id="id" type="hidden" name="status_event" value="2">
          <button id="submit-btn" type="submit" class="btn btn-danger <?php echo  $status_e ?> submit-btn">Reject</button>
        </form>
      </div>
    </div>
  </footer>
  <?php
    }elseif($list->message[0]->APPROVAL_MD == 2){
  ?>
<footer class="panel-footer">
      <div class="row">
        <div class="col-sm-8">
        </div>
        <div class="col-sm-4 text-right text-center-xs" >
          <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('sales_event/approval_event_simpan/' . $list->message[0]->ID); ?>">
            <input id="id" type="hidden" name="id" value="<?php echo $list->message[0]->ID; ?>">
            <input id="id" type="hidden" name="status_event" value="1">
            <button id="submit-btn" type="submit" class="btn btn-default <?php echo  $status_e ?> submit-btn">Approve</button>
          </form>
        </div>
    </div>
  </footer>
<?php
                  }else{
                    ?>
<footer class="panel-footer">
      <div class="row">
        <div class="col-sm-8">
        </div>
        <div class="col-sm-4 text-right text-center-xs">                
         <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('sales_event/approval_event_simpan/' . $list->message[0]->ID); ?>">
          <input id="id" type="hidden" name="id" value="<?php echo $list->message[0]->ID; ?>">
          <input id="id" type="hidden" name="status_event" value="2">
          <button id="submit-btn" type="submit" class="btn btn-danger <?php echo  $status_e ?> submit-btn">Reject</button>
        </form>
      </div>
    </div>
  </footer>
<?php
                  }
                  ?>

</div>
</div>

</section>