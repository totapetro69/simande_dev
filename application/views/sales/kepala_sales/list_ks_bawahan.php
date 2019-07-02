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
 
    <a id="modal-button" class="btn btn-default <?php echo $status_c?>" onclick='addForm("<?php echo base_url('company/add_ks_bawahan/'. $cek->message[0]->ID); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
      <i class="fa fa-file-o fa-fw"></i> Baru
    </a>
    <a class="btn btn-default <?php echo $status_c?>" href="<?php echo base_url('company/ks');?>" role="button">
      <i class="fa fa-list-ul fa-fw"></i>List Kepala Sales
    </a>
 
  </div>
 
</div>


   <div class="col-lg-12 padding-left-right-10">

      <div class="panel margin-bottom-10">

         <div class="panel-heading">
                Kepala Sales
            <span class="tools pull-right">
               <a class="fa fa-chevron-up" href="javascript:;"></a>
            </span>
         </div>

      <div class="panel-body panel-body-border" style="display: show;">

         <table class="table table-striped b-t b-light">
                    <tr>
                        <td>NIK Kepala Sales</td>
                        <td>: <?php echo $cek->message[0]->NIK; ?></td>
                    </tr>
                    <tr>
                        <td>Nama Kepala Sales</td>
                        <td>: <?php echo $cek->message[0]->NAMA; ?></td>
                    </tr>
                    <tr>
                        <td>Dealer</td>
                        <td>: <?php echo $cek->message[0]->NAMA_DEALER; ?></td>
                    </tr>
                    <tr>
                        <td>Lokasi Dealer</td>
                        <td>: <?php echo $cek->message[0]->NAMA_LOKASI; ?></td>
                    </tr>
                </table>

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
                     <th>Aksi</th>
                     <th>Dealer</th>
                     <th>NIK</th>
                     <th>Nama Sales</th>
                     <th>Tanggal Pengangkatan</th>
                     <<th>Tanggal Berhenti</th>
                     <th>Status Jabatan</th>
                     <th>Status</th>
                     <!-- <th>Bawahan</th> -->
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
                    <a id="modal-button" onclick='addForm("<?php echo base_url('company/edit_ks_bawahan/').$row->ID.'/'.$row->ROW_STATUS; ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v?>">
                      <i data-toggle="tooltip" data-placement="left" title="edit" class="fa fa-edit text-success text-active"></i>
                   </a>
                    <?php 
                    if($row->ROW_STATUS == 0){ 
                      ?>
                      <a id="delete-btn<?php echo $no;?>" class="delete-btn" url="<?php echo base_url('company/delete_ks_bawahan/'.$row->ID.'/'.$row->KS_ID); ?>">
                        <i data-toggle="tooltip" data-placement="left" title="hapus" class="fa fa-trash text-danger text"></i>
                      </a>
                      <?php
                    }
                    ?>
                  </td>
                     <td class="table-nowarp"><?php echo $row->KD_DEALER; ?></td>
                     <td class="table-nowarp"><?php echo $row->NIK; ?></td>
                     <td class="table-nowarp"><?php echo $row->NAMA; ?></td>
                     <td class="table-nowarp"><?php echo tglFromSql($row->TGL_AWAL); ?></td>
                     <td class="table-nowarp"><?php if(date('Y', strtotime(tglFromSql($row->TGL_AKHIR))) < '2017'){ echo '';
                     }else{ echo tglFromSql($row->TGL_AKHIR);} ?></td>
                     <td class="table-nowarp"><?php echo ($row->STATUS=="1" ) ? 'Non Aktif':'Aktif';?></td>
                     <td class="table-nowarp"><?php echo ($row->ROW_STATUS=="-1" ) ? 'Non Aktif':'Aktif';?></td>
                     
                     <!-- <td class="table-nowarp"><?php echo $row->NIK_BAWAHAN; ?></td> -->
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
                            echo belumAdaData(8);
                        endif;
                        ?>
               </tbody>

            </table>

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

   </div>

</section>
<script type="text/javascript">
    $(document).ready(function(){
        $('#nmd').html($('#kd_dealer option:selected').text())
    })
</script>