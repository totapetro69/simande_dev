<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<section class="wrapper">
  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb(); ?>

    <div class="bar-nav pull-right ">
      <a class="btn btn-default <?php echo $status_p ?>" id="modal-button" onclick='addForm("");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan" ></i> Cetak
      </a>
    </div>

  </div>

  <div class="col-lg-12 padding-left-right-10">
    <div class="panel margin-bottom-10">
      <div class="panel-heading">
        Neraca Percobaan
        <span class="tools pull-right">
          <a class="fa fa-chevron-down" href="javascript:;"></a>
        </span>
      </div>

      <div class="panel-body panel-body-border" style="display: block;">

        <form id="penerimaanForm" action="" class="bucket-form" method="get">

          <div class="row">

            <div class="col-xs-12 col-sm-4 col-md-4">
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
              <tr class="text-center">
                <th colspan="2" style="text-align: center;">Saldo Awal</th>
                <th colspan="2" style="text-align: center;">Mutasi</th>
                <th colspan="2" style="text-align: center;">Saldo Akhir</th>
              </tr>
              <tr>
                <th style="text-align: center;">Debet</th>
                <th style="text-align: center;">Kredit</th>
                <th style="text-align: center;">Debet</th>
                <th style="text-align: center;">Kredit</th>
                <th style="text-align: center;">Debet</th>
                <th style="text-align: center;">Kredit</th>
              </tr>
            </thead>

            <tbody>
              

              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                <td></b></td>
              </tr>
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
</script>