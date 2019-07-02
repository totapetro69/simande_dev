<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$KD_DEALER = ($this->input->get('kd_dealer'))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>
<section class="wrapper">
  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>
    <div class="bar-nav pull-right ">

      <a id="modal-button" class="btn btn-default <?php echo $status_c?>" onclick='addForm("<?php echo base_url('user/add_user'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
          <i class="fa fa-file-o fa-fw"></i> Add User
      </a>
      <?php if($this->session->userdata('type_users') == 'MD'): ?>
      <a class="btn btn-default" href="<?php echo base_url("user/user_group_list");?>"><i class="fa fa-list-ol"></i> List Group</a>
      <a class="btn btn-default" href="<?php echo base_url("modul/modul_list");?>"><i class="fa fa-cogs"></i> List Modul</a>
      <?php endif;?>
    </div>
    <!-- </li> -->
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          User List
          <span class="tools pull-right">
              <a class="fa fa-chevron-up" href="javascript:;"></a>
           </span>
      </div>

      <div class="panel-body panel-body-border" style="display: block;">

        <form id="filterForm" action="<?php echo base_url('user/user_list') ?>" class="bucket-form" method="get">

          <div id="ajax-url" url="<?php echo base_url('user/user_typeahead');?>"></div>

          <div class="row">



            <div class="col-xs-12 col-sm-3">
                    
              <div class="form-group">
                  <label>Dealer</label>
                  <select name="kd_dealer" id="kd_dealer" class="form-control">
                    <option value="">- Pilih Dealer -</option>
                    <?php if(isRoot()){ ?>
                        <option value="MD" <?php echo ($KD_DEALER=='MD')?'selected':'';?>>MAIN DEALER TM</option>
                    <?php }
                      foreach ($dealer->message as $key => $group) :
                        $default=($KD_DEALER==$group->KD_DEALER)?" selected":" ";
                    ?>
                      <option value="<?php echo $group->KD_DEALER;?>" <?php echo $default;?> ><?php echo $group->NAMA_DEALER;?></option>
                    <?php endforeach; ?>
                  </select>
              </div>

            </div>


            <div class="col-xs-12 col-sm-5">
              <div class="form-group">
                  <label>NIK atau Username</label>
                  <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukan Nomor Induk Karyawan atau username" autocomplete="off">
              </div>
            </div>

            <div class="col-xs-12 col-sm-3 col-sm-offset-1">
              <div class="form-group">
                <label>Status</label>
                <select id="kd_status" name="kd_status" class="form-control">
                  <option value="0" <?php echo ($this->input->get('kd_status') == 0 ? "selected" : "");?>>Aktif</option>
                  <option value="-1" <?php echo ($this->input->get('kd_status') == -1 ? "selected" : "");?>>Tidak Aktif</option>
                  <option value="-2" <?php echo ($this->input->get('kd_status') == -2 ? "selected" : "");?>>Semua</option>
                </select>
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

      <div class="table-responsive h350">
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th style="width:40px;">No.</th>
              <th style="width:45px;">Aksi</th>
              <th>NIK</th>
              <th>Name</th>
              <th>User Group</th>
              <!-- <th>User Level</th> -->
              <th>Divisi</th>
              <!-- <th>Dealer</th> -->
              <th>Status</th>
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
              <td class='table-nowarp'><?php echo $no;?></td>
              <td class="table-nowarp">
                <a id="modal-button" onclick='addForm("<?php echo base_url('user/add_user/?n='.$row->USER_ID.'&type_users='.$row->TYPE_USERS); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                  <i data-toggle="tooltip" data-placement="left" title="Edit" class="fa fa-edit text-success text-active"></i>
                </a>
                <a id="modal-button" onclick='addForm("<?php echo base_url('user/edit_password/'.$row->USER_ID); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_e?>">
                  <i data-toggle="tooltip" data-placement="left" title="Reset" class="fa fa-unlock text-primary"></i>
                </a>
                <?php if($row->TYPE_USERS == 'MD'):?>
                <a id="modal-button" onclick='addForm("<?php echo base_url('user/list_dealer/'.$row->USER_ID); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_e?>">
                  <i data-toggle="tooltip" data-placement="left" title="setup auth area / dealer" class="fa fa-list text-primary"></i>
                </a>
                <?php endif;?>
                <?php if($row->APV_DOC == '1'):?>
                <a id="modal-button" onclick='addForm("<?php echo base_url('user/apv_docs/'.$row->USER_ID.'?n='.$row->USER_ID); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_e?>">
                  <i data-toggle="tooltip" data-placement="left" title="setup aproval document" class="fa fa-pencil-square text-primary"></i>
                </a>
                <?php endif;?>
                <a id="delete-btn<?php echo $no;?>" class="delete-btn <?php echo $status_e?>" url="<?php echo base_url('user/delete_user/'.$row->USER_ID); ?>">
                  <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                </a>
              </td>
              <td class="table-nowarp"><?php echo strtoupper($row->USER_ID);?></td>
              <td class="table-nowarp"><?php echo $row->USER_NAME;?></td>
              <td class="table-nowarp"><?php echo $row->NAMA_GROUP;?></td>
              <!-- <td class="table-nowarp"><?php echo $row->NAMA_LEVEL;?></td> -->
              <td class="table-nowarp"><?php echo $row->NAMA_DIV;?></td>
              <!-- <td class="table-nowarp"><?php echo $row->NAMA_DEALER;?></td> -->
              <td class="table-nowarp"><?php echo $row->KD_STATUS == 0 ? 'Aktif':'Tidak';?></td>
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