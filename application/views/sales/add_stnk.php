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
      

    </div>
    <!-- </li> -->
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          <i class="fa fa-list-ul fa-fw"></i> List STNK
          <span class="tools pull-right">
              <a class="fa fa-chevron-up" href="javascript:;"></a>
          </span>
      </div>

      <div class="panel-body panel-body-border" style="display: none;">

        <form id="filterForm" action="<?php echo base_url('stnk/stnk_list') ?>" class="bucket-form" method="get">


          <!-- <div id="ajax-url" url="<?php echo base_url('stnk/stnk_typeahead');?>"></div> -->

          <div class="row">


            <div class="col-xs-12 col-sm-3">
                    
              <div class="form-group">
                  <label>Dealer</label>
                  <select name="kd_dealer" id="kd_dealer" class="form-control" required="true">
                    
                      <?php
                      if (isset($dealer)) {
                          if ($dealer->totaldata > 0) {
                              foreach ($dealer->message as $key => $value) {
                                  $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                                  $select = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $select;
                                  echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                              }
                          }
                      }
                      ?>
                  </select>
              </div>

            </div>

            <div class="col-xs-12 col-sm-9">
              <div class="form-group">
                  <label>Nomor Sales Order</label>
                  <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Nomor Sales Order" autocomplete="off">
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
        <table class="table table-striped table-bordered">
          <thead>
            <tr class="text-center">
              <th rowspan="2" style="width:45px; vertical-align: middle;">No</th>
              <th rowspan="2" style="width:50px; vertical-align: middle;">Ajukan</th>
              <th colspan="13" style="text-align: center;">Nomor Surat Jalan</th>
            </tr>
            <tr>
              <th>No. Rangka</th>
              <th>Kd. Mesin</th>
              <th>No. Mesin</th>
              <th>Nama Pemilik</th>
              <th>Alamat</th>
              <th>Kelurahan</th>
              <th>Kecamatan</th>
              <th>Kota</th>
              <th>Kope POS</th>
              <th>Propinsi</th>
              <th>Jenis Pembayaran</th>
              <th>Kd. Dealer</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>

          <?php
            $no = $this->input->get('page');
            if($list):
              if(is_array($list->message) || is_object($list->message)):
              foreach($list->message as $key=>$group_row): 
              $no ++;
          ?>

            <tr class="info bold">
              <td><?php echo $no;?></td>
              <td>
                <a href="<?php echo base_url('stnk/createfile_udstk?n='.urlencode(base64_encode($group_row->NO_SURATJALAN)));?>" >
                    <i data-toggle="tooltip" data-placement="left" title="Download file .UDSTK" class="fa fa-download"></i>
                </a>
              </td>
              <td colspan="13"><?php echo $group_row->NO_SURATJALAN;?></td>
            </tr>

          <?php   
            if($list_group && is_array($list_group->message) || is_object($list_group->message)):
              foreach($list_group->message as $row): 
              if($group_row->ID == $row->ID_SURATJALAN):
          ?>

                <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' ';?>" >
                  <td></td>
                  <td>
                    <!-- <a id="delete-btn<?php echo $no;?>" class="delete-btn <?php echo $status_e?>" url="<?php echo base_url('sales_order/delete_so/'.$row->ID); ?>">
                      <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                    </a> -->
                  </td>
                  <td><?php echo $row->NO_RANGKA;?></td>
                  <!-- <td><?php echo $row->NO_MESIN;?></td> -->
                  <td><?php echo substr($row->NO_MESIN,0,5);?></td>
                  <td><?php echo substr($row->NO_MESIN,-7);?></td>
                  <td><?php echo $row->NAMA_CUSTOMER;?></td>
                  <td><?php echo $row->ALAMAT_SURAT;?></td>
                  <td><?php echo $row->NAMA_DESA;?></td>
                  <td><?php echo $row->NAMA_KECAMATAN;?></td>
                  <td><?php echo $row->NAMA_KABUPATEN;?></td>
                  <td><?php echo $row->KODE_POS;?></td>
                  <td><?php echo $row->NAMA_PROPINSI;?></td>
                  <td><?php echo $row->JENIS_PENJUALAN;?></td>
                  <td><?php echo $row->KD_DEALER;?></td>
                  <td><?php echo $row->STATUS_SJ;?></td>
                </tr>

            <?php 
              endif;
              endforeach;
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
