  <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
  
  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
  ?>
  <section class="wrapper">


<!-- <div style="margin-left:20px;margin-top:50px;"> -->
<!-- </div>  -->



  <div class="breadcrumb margin-bottom-10">
    
    <?php echo breadcrumb();?>


    <div class="bar-nav pull-right ">

<!--       <div class="btn-group">

        <a type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-file"></i> download <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="#">PDF</a></li>
          <li><a href="#">Excel</a></li>
        </ul>
      </div> -->

      <a id="modal-button" class="btn btn-default <?php echo $status_c?>" onclick='addForm("<?php echo base_url('user/add_user_otorisasi'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
          <i class="fa fa-file-o fa-fw"></i> Baru
      </a>

    </div>
    <!-- </li> -->
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          User List
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
           </span>
      </div>

      <div class="panel-body panel-body-border">

        <form id="filterForm" action="<?php echo base_url('user/user_otorisasi') ?>" class="bucket-form" method="get">

          <div class="row">

            <div class="col-xs-6 col-sm-6 col-md-6">
                  
              <div class="form-group">
                <label>Grup User</label>
                <select id="kd_group" name="kd_group" class="form-control">
                  <option value="">- Pilih grup -</option>
                  <?php if($groups  && (is_array($groups->message) || is_object($groups->message))): foreach ($groups->message as $key => $group) : ?>
                    <option value="<?php echo $group->KD_GROUP;?>" <?php echo ($group->KD_GROUP == $this->input->get('kd_group') ? "selected" : "");?>  ><?php echo $group->NAMA_GROUP;?></option>
                  <?php endforeach; endif;?>
                </select>
              </div>

            </div>


            <div class="col-xs-6 col-sm-6 col-md-6">

              <div class="form-group">
                <label>Menu</label>
                <select id="kd_modul" name="kd_modul" class="form-control">
                  <option value="">- Pilih Menu -</option>
                  <?php if($moduls  && (is_array($moduls->message) || is_object($moduls->message))): foreach ($moduls->message as $key => $modul) : ?>
                    <option value="<?php echo $modul->KD_MODUL;?>" <?php echo ($modul->KD_MODUL == $this->input->get('kd_modul') ? "selected" : "");?>  ><?php echo $modul->NAMA_MODUL;?></option>
                  <?php endforeach; endif;?>
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

      <div class="table-responsive">
        <table class="table table-striped b-t b-light">
          <thead>
            <tr>
              <th style="width:40px;">No.</th>
              <th style="width:45px;">Aksi</th>
              <th>Nama Menu</th>
              <th>Group</th>
              <th>Create</th>
              <th>Edit</th>
              <th>View</th>
              <th>Print</th>
            </tr>
          </thead>
          <tbody>

          <form id="updateForm" method="post" action="<?php echo base_url('user/update_user_otorisasi'); ?>">
            
        <?php
          $no = $this->input->get('page');
          if($list):
            if(is_array($list->message) || is_object($list->message)):
            foreach($list->message as $key=>$row): 
            if($row->PARENT_MODUL == null):
            $no ++;
        ?>

            <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' ';?>" >
              <td><?php echo $no;?></td>
              <td class="table-nowarp">


              <?php if($row->PARENT_MODUL == null): ?>
                <a id="delete-btn<?php echo $no;?>" class="<?php echo $status_e?> delete-btn" url="<?php echo base_url('user/delete_user_otorisasi/'.$row->KD_MODUL.'/'.$row->KD_GROUP); ?>">
                  <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                </a>
              <?php endif; ?>

              <?php if($row->PARENT_STATUS == 1): ?>
                <a id="<?php echo $row->ID?>" class="<?php echo $status_e?> update-btn">
                  <i data-toggle="tooltip" data-placement="left" title="Update" class="fa fa-save text"></i>
                </a>
              <?php endif; ?>


              </td>
              <td><?php echo $row->NAMA_MODUL;?><input type="hidden" name="kd_modul<?php echo $row->ID?>" value="<?php echo $row->KD_MODUL;?>"></td>
              <td><?php echo $row->NAMA_GROUP;?><input type="hidden" name="kd_group<?php echo $row->ID?>" value="<?php echo $row->KD_GROUP;?>"></td>
              <?php if($row->PARENT_STATUS == 1): ?>
              <td><input id="c" name="c<?php echo $row->ID?>" type="checkbox" <?php echo ($row->C == 1 ? "checked" : "");?> class="<?php echo $status_e?>"></td>
              <td><input id="e" name="e<?php echo $row->ID?>" type="checkbox" <?php echo ($row->E == 1 ? "checked" : "");?> class="<?php echo $status_e?>"></td>
              <td><input id="v" name="v<?php echo $row->ID?>" type="checkbox" <?php echo ($row->V == 1 ? "checked" : "");?> class="<?php echo $status_e?>"></td>
              <td><input id="p" name="p<?php echo $row->ID?>" type="checkbox" <?php echo ($row->P == 1 ? "checked" : "");?> class="<?php echo $status_e?>"></td>
              <?php
              else:
              echo "<td colspan='4'></td>";
              endif; ?>
            </tr>



            <?php
                foreach($childs->message as $key=>$child): 
                if($child->PARENT_MODUL == $row->KD_MODUL && $child->KD_GROUP == $row->KD_GROUP):
            ?>

                <tr id="<?php echo $this->session->flashdata('tr-active') == $child->ID ? 'tr-active' : ' ';?>" >
                  <td></td>
                  <td class="table-nowarp">

                    <a id="<?php echo $child->ID?>"  class="<?php echo $status_e?> update-btn">
                      <i data-toggle="tooltip" data-placement="left" title="Update" class="fa fa-save text-primary text"></i>
                    </a>

                  </td>
                  <td style="padding-left: 20px !important;"><?php echo $child->NAMA_MODUL;?><input type="hidden" name="kd_modul<?php echo $child->ID?>" value="<?php echo $child->KD_MODUL;?>"></td>
                  <td><?php echo $child->NAMA_GROUP;?><input type="hidden" name="kd_group<?php echo $child->ID?>" value="<?php echo $child->KD_GROUP;?>"></td>
                  <td><input id="c" name="c<?php echo $child->ID?>" type="checkbox" <?php echo ($child->C == 1 ? "checked" : "");?> class="<?php echo $status_e?>"></td>
                  <td><input id="e" name="e<?php echo $child->ID?>" type="checkbox" <?php echo ($child->E == 1 ? "checked" : "");?> class="<?php echo $status_e?>"></td>
                  <td><input id="v" name="v<?php echo $child->ID?>" type="checkbox" <?php echo ($child->V == 1 ? "checked" : "");?> class="<?php echo $status_e?>"></td>
                  <td><input id="p" name="p<?php echo $child->ID?>" type="checkbox" <?php echo ($child->P == 1 ? "checked" : "");?> class="<?php echo $status_e?>"></td>
                </tr>





                <?php
                    foreach($childs->message as $grand_key=>$grand_child): 
                    if($grand_child->PARENT_MODUL == $child->KD_MODUL && $child->KD_GROUP == $grand_child->KD_GROUP):
                ?>

                    <tr id="<?php echo $this->session->flashdata('tr-active') == $grand_child->ID ? 'tr-active' : ' ';?>" >
                      <td></td>
                      <td class="table-nowarp">

                        <a id="<?php echo $grand_child->ID?>"  class="<?php echo $status_e?> update-btn">
                          <i data-toggle="tooltip" data-placement="left" title="Update" class="fa fa-save text-primary text"></i>
                        </a>

                      </td>
                      <td style="padding-left: 30px !important;"><?php echo $grand_child->NAMA_MODUL;?><input type="hidden" name="kd_modul<?php echo $grand_child->ID?>" value="<?php echo $grand_child->KD_MODUL;?>"></td>
                      <td><?php echo $grand_child->NAMA_GROUP;?><input type="hidden" name="kd_group<?php echo $grand_child->ID?>" value="<?php echo $grand_child->KD_GROUP;?>"></td>
                      <td><input id="c" name="c<?php echo $grand_child->ID?>" type="checkbox" <?php echo ($grand_child->C == 1 ? "checked" : "");?> class="<?php echo $status_e?>"></td>
                      <td><input id="e" name="e<?php echo $grand_child->ID?>" type="checkbox" <?php echo ($grand_child->E == 1 ? "checked" : "");?> class="<?php echo $status_e?>"></td>
                      <td><input id="v" name="v<?php echo $grand_child->ID?>" type="checkbox" <?php echo ($grand_child->V == 1 ? "checked" : "");?> class="<?php echo $status_e?>"></td>
                      <td><input id="p" name="p<?php echo $grand_child->ID?>" type="checkbox" <?php echo ($grand_child->P == 1 ? "checked" : "");?> class="<?php echo $status_e?>"></td>
                    </tr>

                  <?php 
                    endif;
                    endforeach;
                  ?>



              <?php 
                endif;
                endforeach;
              ?>


          <?php 
            endif;
            endforeach;
            else:
          ?>
            <tr>
                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                <td colspan="7"><b><?php echo ($list->message); ?></b></td>
            </tr>
        <?php
            endif;
          else:
        
            belumAdaData(8);

          endif;
        ?>


          </form>

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