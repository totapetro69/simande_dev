<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$pilih = $this->input->get('pilih');
?>
<section class="wrapper">
  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb(); ?>
    <div class="bar-nav pull-right ">

    <a class="btn btn-default" id="baru"><i class="fa fa-file-o fa-fw"></i> Baru </a>
    <a class="btn btn-default <?php echo $status_p ?>" id="modal-button" onclick='addForm("<?php echo base_url('report/sales_print?pilih=' . $this->input->get("pilih") . '&tgl_awal=' . $this->input->get("tgl_awal") . '&tgl_akhir=' . $this->input->get("tgl_akhir") . '&keyword=' . $this->input->get("keyword")); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
      <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Penerimaan" ></i> Cetak
    </a>
  </div>
  </div>

  <div class="col-lg-12 padding-left-right-10">
    <div class="panel margin-bottom-10">
      <div class="panel-heading"> Pelunasan AR
        <span class="tools pull-right">
          <a class="fa fa-chevron-down" href="javascript:;"></a>
        </span>
      </div>

      <div class="panel-body panel-body-border" style="display: block;">
        <form id="filterFormz" action="<?php echo base_url('report/sales') ?>" class="bucket-form" method="get">
          <div id="ajax-url" url="<?php echo base_url('report/sales_typeahead'); ?>"></div>

          <div class="row">

            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label>Nama Dealer</label>
                <select name="kd_dealer" class="form-control" <?php echo ($this->session->userdata("kd_group") == "Root") ? "" : "disabled"; ?>>
                  <option value="0">--Pilih Dealer--</option>
                  <?php
                  if ($dealer) {
                    if (is_array($dealer->message)) {
                      foreach ($dealer->message as $key => $value) {
                        $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                        echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                      }
                    }
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label>No Kwitansi</label>
                <select name="" class="form-control">
                  <option value="0">--Pilih--</option>
                  
                </select>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
    </div>

    <div id="detail-panel" class="col-lg-12 padding-left-right-10">

          <div id="detail-panel" class="col-lg-4 ">
            <div class="panel margin-bottom-10">
              <div class="panel-body panel-body-border-top" style="display: show;">

                <div class=" col-xs-4 col-sm-10">
                  <div class="form-group">
                    <label>Kode Perusahaan</label>
                    <select name="" class="form-control">
                      <option value="0">--Pilih--</option></select>
                    <input class="form-control" id="" name="" placeholder="" type="text"/>
                    <input class="form-control" id="" name="" placeholder="" type="text"/>
                    <br>
                    Data Transfer Bank
                    <label>No Trans</label>
                    <select name="" class="form-control">
                      <option value="0">--Pilih--</option></select>
                    <label>Kode Bank</label>
                    <input class="form-control" id="" name="" placeholder="" type="text" />
                  </div>
                </div>
                <hr>
                <div class=" col-xs-4 col-sm-10">
                  <div class="form-group">
                    <label>Tgl transfer</label>
                    <input class="form-control" id="" name="" placeholder="" type="text" />
                    <label>Jumlah KU</label>
                    <input class="form-control" id="" name="" placeholder="" type="text" />
                    <label>Sisa Saldo</label>
                    <input class="form-control" id="" name="" placeholder="" type="text" />
                  </div>
                </div>

                <div class=" col-xs-4 col-sm-10">
                  <div class="form-group">
                    <button>OK</button>
                  </div>
                </div>
 
              </div>
            </div>
          </div>

          <div id="detail-panel" class="col-lg-4 padding-left-right-5">
            <div class="panel margin-bottom-10">
              <div class="panel-body panel-body-border-top" style="display: show;">

                <div class=" col-xs-4 col-sm-10">
                  <div class="form-group">
                    <label>No Rek</label>
                    <input class="form-control" id="" name="" placeholder="" type="text"/>
                  </div>
                </div>

                <div class=" col-xs-4 col-sm-10">
                  <div class="form-group">
                    <label>No Rek/BG</label>
                    <input class="form-control" id="" name="" placeholder="" type="text" />
                  </div>
                </div>
 
                <div class=" col-xs-4 col-sm-10">
                  <div class="form-group">
                    <label>Nama Bank</label>
                    <input class="form-control" id="" name="" placeholder="" type="text" />
                  </div>
                </div>

                <div class=" col-xs-4 col-sm-10">
                  <div class="form-group">
                    <label class="control-label" for="date">Tanggal TOP</label>
                    <div class="input-group input-append date" id="date">
                      <input class="form-control" id="tgl_top" name="" placeholder="DD/MM/YYYY" value="" type="text"/>
                      <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                  </div>
                </div>

            </div>
          </div>
        </div>

        <div id="detail-panel" class="col-lg-4 padding-left-right-5">
            <div class="panel margin-bottom-10">
              <div class="panel-body panel-body-border-top" style="display: show;">

                <div class=" col-xs-4 col-sm-10">
                  <div class="form-group">
                    <label>No Rek</label>
                    <input class="form-control" id="" name="" placeholder="" type="text"/>
                  </div>
                </div>

                <div class=" col-xs-4 col-sm-10">
                  <div class="form-group">
                    <label>No Rek/BG</label>
                    <input class="form-control" id="" name="" placeholder="" type="text" />
                  </div>
                </div>

                <div class=" col-xs-4 col-sm-10">
                  <div class="form-group">
                    <label>Nama Bank</label>
                    <input class="form-control" id="" name="" placeholder="" type="text" />
                  </div>
                </div>

                <div class=" col-xs-4 col-sm-10">
                  <div class="form-group">
                    <label class="control-label" for="date">Tanggal TOP</label>
                    <div class="input-group input-append date" id="date">
                      <input class="form-control" id="tgl_top" name="" placeholder="DD/MM/YYYY" value="" type="text"/>
                      <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                  </div>
                </div>

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
              <th>No Faktur</th>
              <th>Tgl Faktur</th>
              <th>Kode Prs</th>
              <th>Nama Customer</th>
              <th>No Mesin</th>
              <th>Tagihan</th>
              <th>Lunas</th>
              <th>Jenis</th>
              <th>Titipan</th>
              <th>Jml Bayar</th>
              <th>S.AHM</th>
              <th>Rinci Kupon</th>
              <th>Kupon+lain</th>
              <th>Tamb.UM</th>
              <th>Ket Tamb UM</th>
              <th>Cek Kpn</th>
              <th>No Kwit Tagihan</th>
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
                      <a href=""class="<?php echo $status_v?>">
                        <i data-toggle="tooltip" data-placement="left" title="Edit" class="fa fa-edit text-success text-active"></i>
                      </a>
                      <?php 
                      if($row->ROW_STATUS == 0){ 
                       ?>
                       <a id="delete-btn<?php echo $no;?>" class="delete-btn <?php echo $status_e?>" url="">
                        <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                      </a>
                      <?php
                    }
                    ?>
                  </td>
                  <td class="table-nowarp"></td>
                  <td class="table-nowarp"></td>
                  <td class='text-right'></td>
                  <td class="table-nowarp"></td>
                  <td class="table-nowarp"></td>
                  <td class='text-right'></td>
                  <td class="table-nowarp"></td>
                  <td class="table-nowarp"></td>
                  <td class='text-right'></td>
                  <td class="table-nowarp"></td>
                  <td class="table-nowarp"></td>
                  <td class='text-right'></td>
                  <td class="table-nowarp"></td>
                  <td class="table-nowarp"></td>
                  <td class='text-right'></td>
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
          <!-- <small class="text-muted inline m-t-sm m-b-sm"> 
            <?php echo ($list)? ($list->totaldata==''?"":"<i>Total Data ". $list->totaldata ." items</i>") : '' ?>
          </small> -->
        </div>
        <div class="col-sm-7 text-right text-center-xs">                
         <?php echo $pagination;?>
       </div>
     </div>
   </footer>

 </div>
</div>