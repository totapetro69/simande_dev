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

      <a id="modal-button" class="btn btn-primary <?php echo $status_c?>" onclick='addForm("<?php echo base_url('master_service/add_kpb'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
          <i class="fa fa-download"></i> Update Data
      </a>

    </div>
   
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          KPB By Tipe Motor
          <span class="tools pull-right">
              <a class="fa fa-chevron-up" href="javascript:;"></a>
           </span>
      </div>

      <div class="panel-body panel-body-border" style="display: none;">

        <form id="filterForm" action="<?php echo base_url('master_service/kpb') ?>" class="bucket-form" method="get">

          <div id="ajax-url" url="<?php echo base_url('master_service/kpb_typeahead');?>"></div>

          <div class="row">

            <div class="col-xs-12 col-sm-12">
              <div class="form-group">
                <label>KPB By Tipe Motor</label>
                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan nomor mesin atau tipe motor" autocomplete="off">
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
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th style="width:40px;">No.</th>
              <th>No Mesin</th>
              <th>Tipe Motor</th>
              <th>Premium</th>
              <th>BKM 1</th>
              <th>BKM 2</th>
              <th>BKM 3</th>
              <th>BKM 4</th>
              <th>BSE 1</th>
              <th>BSE 2</th>
              <th>BSE 3</th>
              <th>BSE 4</th>
              <th>BCL 1</th>
              <th>BCL 2</th>
              <th>BCL 3</th>
              <th>BCL 4</th>
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
              <td><?php echo $row->NO_MESIN;?></td>
              <td><?php echo $row->TIPE_MOTOR;?></td>
              <td><?php echo $row->PREMIUM;?></td>
              <td><?php echo $row->BKM1;?></td>
              <td><?php echo $row->BKM2;?></td>
              <td><?php echo $row->BKM3;?></td>
              <td><?php echo $row->BKM4;?></td>
              <td><?php echo $row->BSE1;?></td>
              <td><?php echo $row->BSE2;?></td>
              <td><?php echo $row->BSE3;?></td>
              <td><?php echo $row->BSE4;?></td>
              <td><?php echo $row->BCL1;?></td>
              <td><?php echo $row->BCL2;?></td>
              <td><?php echo $row->BCL3;?></td>
              <td><?php echo $row->BCL4;?></td>
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
        ?>
            <tr>
                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                <td colspan="8"><b>ada error, harap hubungi bagian IT</b></td>
            </tr>
        <?php
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