 <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$ROOT = ($this->session->userdata('nama_group')=='Root'?'':'disabled');

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        <div class="bar-nav pull-right ">  
         <!-- <a class="btn btn-default" href="<?php echo base_url('umsl/addpenerimaan'); ?>" role="button" data-toggle="modal" data-backdrop="static">
                <i class="fa fa-file-o"></i> Input Penerimaan
            </a> -->
            
        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                <i class="fa fa-list-ul fa-fw"></i> List Penerimaan Motor
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('umsl/terimamotor') ?>" class="bucket-form" method="get">

                <div id="ajax-url" url="<?php echo base_url('umsl/tm_typeahead'); ?>"></div>

                <div class="row">

                  <div class="col-xs-12 col-sm-2">
                          
                    <div class="form-group">
                        <label>Dealer</label>
                        <select name="kd_dealer" id="kd_dealer" class="form-control" <?php echo $ROOT;?> required="true">
                          <option value="">- Pilih Dealer -</option>
                          <?php foreach ($dealer->message as $key => $group) : 
                            if($KD_DEALER!=''):
                              $default=($KD_DEALER==$group->KD_DEALER)?" selected":" ";
                            else:
                              $default=($this->session->userdata("kd_dealer")==$group->KD_DEALER)?" selected":'';
                            endif;
                          ?>
                            <option value="<?php echo $group->KD_DEALER;?>" <?php echo $default;?> ><?php echo $group->NAMA_DEALER;?></option>
                          <?php endforeach; ?>
                        </select>
                    </div>

                  </div>
                  
                  <div class="col-xs-12 col-sm-4">


                    <div class="form-group">
                        <label>Field Cari</label>
                        <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="cari berdasarkan kode nomor penerimaan surat jalan atau nomor Mesin" autocomplete="off">
                    </div>

                  </div>


                  <div class="col-xs-12 col-sm-3">

                    <div class="form-group">

                      <label class="control-label" for="date">Periode Awal</label>
                      <div class="input-group input-append date">
                          <input class="form-control" id="tgl_awal" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tgl_awal')?$this->input->get('tgl_awal'):date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                          <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                      </div>

                    </div>

                  </div>

                  <div class="col-xs-12 col-sm-3">

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
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr class="text-center">
                          <th rowspan="2" style="width:45px; vertical-align: middle;">No</th>
                          <th rowspan="2" style="width:50px; vertical-align: middle;">Aksi</th>
                          <th colspan="7" style="text-align: center;">Nomor Terima SJ</th>
                        </tr>
                        <tr>
                          <th>Tgl Terima SJ</th>
                          <th>KD Item</th>
                          <th>Nama Item</th>
                          <th>No. Rangka</th>
                          <th>No. Mesin</th>
                          <th style="vertical-align: middle; text-align: center;">Jml Unit</th>
                          <th class='text-center' style="vertical-align: middle;">KSU</th>
                    </thead>
                    <tbody>

                      <?php
                        $no = $this->input->get('page');
                        if(isset($list)):
                          if($list->totaldata >0):
                          foreach($list->message as $key=>$group_row): 
                          $no ++;

                      ?>
                      <tr class="info bold">

                        <td class="text-bold"><?php echo  $no; ?></td>
                        <td>
                          <!-- <a href="<?php echo base_url('umsl/addpenerimaan?n='.urlencode(base64_encode($group_row->NO_TERIMASJM))); ?>" class="<?php echo $status_v?>">
                            <i data-toggle="tooltip" data-placement="left" title="Edit" class="fa fa-edit text-success text"></i>
                          </a> -->
<!-- 
                          <a id="modal-button" onclick='addForm("<?php echo base_url('umsl/edit_penerimaan/'.$row->ID); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                            <i data-toggle="tooltip" data-placement="left" title="Edit" class="fa fa-edit text-success text-active"></i>
                          </a> -->
                        </td>
                        <td colspan="7"><?php echo $group_row->NO_TERIMASJM;?></td>

                      </tr>


                      <?php
                          foreach ($list_group->message as $row):
                          if($group_row->NO_TERIMASJM == $row->NO_TERIMASJM):
                      ?>

                          <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' ';?>" >
                            <td><!-- <?php echo $no;?> --></td>
                            <td><!-- 
                              <a id="modal-button" onclick='addForm("<?php echo base_url('umsl/edit_penerimaan/'.$row->ID); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                                <i data-toggle="tooltip" data-placement="left" title="Edit" class="fa fa-edit text-success text-active"></i>
                              </a> -->
                              <?php
                                $status_rm = ($row->STATUS_RM == 1? 'disabled-action' : $status_e);  
                              ?>

                              <a id="delete-btn<?php echo $no;?>" class="delete-btn <?php echo $status_rm;?>" url="<?php echo base_url('umsl/delete_penerimaan/'.$row->ID); ?>">
                                <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                              </a>
                            </td>
                            <td class='table-nowarp'><?php echo tglfromSql($row->TGL_TRANS);?></td>
                            <td class='table-nowarp'><?php echo $row->KD_ITEM;?></td>
                            <td class='table-nowarp'><?php echo $row->NAMA_PASAR;?></td>
                            <td class='table-nowarp'><?php echo $row->NO_RANGKA;?></td>
                            <td class='table-nowarp'><?php echo $row->NO_MESIN;?></td>
                            <td class='table-nowarp'><?php echo $row->JUMLAH;?></td>
                            <td><?php echo $row->KSU;?></td>
                          </tr>

                      <?php 
                          endif;
                          endforeach;

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
                            <?php if ($list) echo ($list->totaldata == '') ? "" : "<i>Total Data " . $list->totaldata . " items</i>" ?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo $pagination; ?>
                    </div>
                </div>
            </footer>
        </div>

    </div>
</section>