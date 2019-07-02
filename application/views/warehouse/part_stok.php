<?php
if (!isBolehAkses()) { redirect(base_url() . 'auth/error_auth');}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$KD_DEALER = $this->input->get("kd_dealer");
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading ">
                <i class='fa fa-list-ul'></i> Part Stok
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" >
                <form id="filterForm" action="<?php echo base_url('part/part_stok') ?>" class="bucket-form" method="get">
                    <div id="ajax-url" url="<?php echo base_url('part/part_stok_typeahead'); ?>"></div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-3">
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
                        <div class="col-xs-12 col-sm-5">
                            <div class="form-group">
                                <label>Cari Nomor Part</label>
                                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="Masukkan Nomor Part atau Nama Part" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-1 col-sm-1">
                            <div class="form-group">
                                <br>
                                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Preview</button>
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
                            <th>Kode Dealer</th>
                            <th>Part Number</th>
                            <th>Nama Part</th>
                            <th>Stok</th>
                            <!-- <th>Harga Beli</th> -->
                            <th>Harga Jual</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if ($list):
                            if (is_array($list->message) || is_object($list->message)):
                                foreach ($list->message as $key => $row):
                                    $no ++;
                                    ?>
                                    <tr id="<?php echo $row->PART_NUMBER;?>" data-toggle='popover' title='Detail Stock'>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo NamaDealer($row->KD_DEALER) ; ?></td>
                                        <td><?php echo $row->PART_NUMBER; ?></td>
                                        <td><?php echo $row->PART_DESKRIPSI; ?></td>
                                        <td class='text-right'><?php echo number_format($row->JUMLAH_SAK,0); ?></td>
                                        <!-- <td><?php echo number_format($row->HARGA_BELI,0); ?></td> -->
                                        <td><?php echo number_format($row->HARGA_JUAL,0); ?></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="40"><b><?php echo ($list->message); ?></b></td>
                                </tr>
                            <?php
                            endif;
                        else:
                            echo belumAdaData(40);
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
    <?php 
        if(isset($list_d)){
            if($list_d->totaldata >0){
                foreach ($list_d->message as $key => $value) {
                    ?>
                        <div id="<?php echo $value->PART_NUMBER;?>" class='hidden'>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Lokasi</th>
                                        <th>Gudang</th>
                                        <th>Rak Bin</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="<?php echo ($value->KD_LOKASI=='Z')?"totaldata":"";?>">
                                        <td><?php echo ($value->KD_LOKASI!='Z')?$value->KD_LOKASI:"";?></td>
                                        <td><?php echo ($value->KD_GUDANG!='X' || $value->KD_GUDANG!='Z')?$value->KD_GUDANG:"";?></td>
                                        <td><?php echo $value->KD_RAKBIN;?></td>
                                        <td class='text-center'><?php echo $value->JUMLAH;?></td>
                                        <td><?php echo $value->KETERANGAN;?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?
                }
            }
        }
    ?>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        $('tr.hover').on('hover',function(){
            var id=$(this).attr('id');
            $(this).popover({
                placement: 'top', 
                trigger: 'hover', 
                html: true,
            })
        })
        
    })
</script>