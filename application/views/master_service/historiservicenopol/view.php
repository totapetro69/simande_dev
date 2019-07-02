<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
  
  $status_c = (isBolehAkses('c') ? '' : 'remove-button' ); 
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
  $defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
  $dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('first day of this month'));
  $sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y");
  ?>
  <section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
		<div class="bar-nav pull-right">
			
		</div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading"><i class='fa fa-list-ul'></i> List PKB
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="filterForm" action="<?php echo base_url('master_service/histori_service_nopol') ?>" class="bucket-form">

                    <!-- <div id="ajax-url" url="<?php echo base_url('pkb/pkb_typeahead'); ?>"></div> -->

                    <div class="row">

                        <div class="col-xs-12 col-sm-8">

                            <div class="form-group">
                                <label>Cari</label>
                                <input type="text" id="no_polisi" name="no_polisi" class="form-control" value="<?php echo $this->input->get('no_polisi'); ?>" placeholder="Masukan No. Mesin atau No. Polisi" autocomplete="off">
                                <!-- <input type="text" id="keyword" name="keyword" class="form-control" value="<?php echo $this->input->get('keyword'); ?>" placeholder="Masukan No. Mesin atau No. Polisi" autocomplete="off"> -->
                            </div>

                        </div>

                        <div class="col-xs-12 col-sm-4">
 
                            <div class="form-group">
                                <label>Dealer</label>
                                <select name="kd_dealer" id="kd_dealer" class="form-control" disabled="disabled">
                                  <option value="">- Pilih Dealer -</option>
                                  <?php foreach ($dealer->message as $key => $group) : 
                                    if($KD_DEALER!=''):
                                      $default=($KD_DEALER==$group->KD_DEALER)?" selected":" ";
                                    elseif($this->input->get('kd_dealer') != ''):
                                      $default=($this->input->get('kd_dealer')==$group->KD_DEALER)?" selected":" ";
                                    else:
                                      $default=($this->session->userdata("kd_dealer")==$group->KD_DEALER)?" selected":'';
                                    endif;
                                  ?>
                                    <option value="<?php echo $group->KD_DEALER;?>" <?php echo $default;?> ><?php echo $group->NAMA_DEALER;?></option>
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
            	<table class="table table-bordered table-striped">
            		<thead>
            			<tr>
                            <th rowspan="2">No.</th>
                            <th>Nama Pemilik</th>
                            <th>No. Mesin</th>
                            <th>No. Rangka</th>
            				<th>No.Polisi</th>
                            <th>Tanggal</th>
                            <th>Dealer</th>
                            <th>No. PKB</th>
                            <th>Nama Mekanik</th>
                            <th rowspan="2">Keluhan Konsumen</th>
            			</tr>
            			<tr clas="thead-alias-tr">
            				<th>Kode</th>
            				<th colspan="5">Deskripsi</th>
            				<th>Jumlah</th>
            				<th>Jenis</th>
            			</tr>
            		</thead>
            		<tbody>
                        <?php
                        if ($list) {
                            $no = $this->input->get('page');
                            if (is_array($list->message)) {
                                foreach ($list->message as $key => $value) {

                                    $no++;
                                    ?>

                                    <tr class="info">
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $value->NAMA_PEMILIK; ?></td>
                                        <td><?php echo $value->NO_MESIN; ?></td>
                                        <td><?php echo $value->NO_RANGKA; ?></td>
                                        <td style="text-transform: uppercase;"><?php echo $value->NO_POLISI; ?></td>
                                        <td><?php echo tglfromSql($value->TANGGAL_PKB); ?></td>
                                        <td><?php echo $value->KD_DEALER; ?></td>
                                        <td><?php echo $value->NO_PKB; ?></td>
                                        <td><?php echo $value->NAMA; ?></td>
                                        <td><?php echo $value->KEBUTUHAN_KONSUMEN; ?></td>

                                        
                                    </tr>


                                <?php
                                if ($detail && is_array($detail->message)) {
                                    foreach ($detail->message as $key => $detail_row) {
                                    if($detail_row->NO_PKB == $value->NO_PKB){
                                        ?>

                                        <tr>
                                            <td></td>
                                            <td><?php echo $detail_row->KD_PEKERJAAN; ?></td>
                                            <td colspan="5"><?php echo $detail_row->PART_DESKRIPSI; ?></td>
                                            <td><?php echo $detail_row->QTY; ?></td>
                                            <td><?php echo $detail_row->KATEGORI; ?></td>
                                            
                                        </tr>
                                <?php
                                    }
                                    }
                                }
                                }
                            } else {
                                belumAdaData(16);
                            }
                        } else {
                            belumAdaData(16);
                        }
                        ?>
            		</tbody>
                </table>
            </div>
            <div class="panel-footer">

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

            </div>
        </div>
    </div>
    <?php echo loading_proses();?>
  </section>
<script type="text/javascript">
    var path = window.location.pathname.split('/');
    var http = window.location.origin + '/' + path[1];

    $(document).ready(function(){

        var nopol=[]

        $.getJSON(http+"/master_service/histori_service_group",function(result){
          if(result.length > 0){
            $.each(result,function(e,d){
              nopol.push({
                'No Polisi' : d.NO_POLISI,
                'No Mesin'  : d.NO_MESIN
              })
            })
          }
          $('#no_polisi').inputpicker({
            data : nopol,
            fields :['No Polisi','No Mesin'],
            fieldText : 'No Polisi',
            fieldValue : 'No Polisi',
            headShow:true,
            filterOpen : true,
          }).on("change",function(){

            $('#filterForm').submit();
              /*var kd_item = $(this).val();

              $('#kd_item').val(kd_item);*/

          })
        });
    })

</script>