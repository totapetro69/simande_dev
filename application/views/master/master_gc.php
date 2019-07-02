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

      <a id="modal-button" class="btn btn-primary <?php echo $status_c?>" onclick='addForm("<?php echo base_url('setup/add_gc'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
          <i class="fa fa-file-o fa-fw"></i> Update Data
      </a>

    </div>
   
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          Master Group Customer
          <span class="tools pull-right">
              <a class="fa fa-chevron-up" href="javascript:;"></a>
           </span>
      </div>

      <div class="panel-body panel-body-border" style="display: none;">

        <form id="filterForm" action="<?php echo base_url('setup/gc') ?>" class="bucket-form" method="get">

          <div id="ajax-url" url="<?php echo base_url('setup/gc_typeahead');?>"></div>

          <div class="row">

            <div class="col-xs-12 col-sm-12">
              <div class="form-group">
                <label>Master Group Customer</label>
                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan Kode atau Nama Group Customer" autocomplete="off">
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
              <th>Kode GC</th>
              <th>Nama Program</th>
              <th>Tanggal Mulai</th>
              <th>Tanggal Selesai</th>
              <th>Kode Motor</th>
              <th>S_AHM</th>
              <th>S_MD</th>
              <th>S_SD</th>
              <th>LKPP Kalsel</th>
              <th>LKPP Kalteng</th>
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
              <td><?php echo $row->KD_GC;?></td>
              <td><?php echo $row->NAMA_PROGRAM;?></td>
              <td><?php echo $row->START_DATE;?></td>
              <td><?php echo $row->END_DATE;?></td>
              <td><?php echo $row->KD_TYPEMOTOR;?></td>
              <td><?php echo $row->S_AHM;?></td>
              <td><?php echo $row->S_MD;?></td>
              <td><?php echo $row->S_SD;?></td>
              <td><?php echo $row->LKPP_KALSES;?></td>
              <td><?php echo $row->LKPP_KALTENG;?></td>
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