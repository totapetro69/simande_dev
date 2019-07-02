<?php
if (!isBolehAkses()) { redirect(base_url() . 'auth/error_auth');}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

?>

<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
     <?php echo breadcrumb();?>

        <div class="bar-nav pull-right ">  
         <a id="modal-button" class="btn btn-info <?php echo  $status_c ?>" onclick='addForm("<?php echo base_url('umsl/addhargamotor'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-download"></i> Update Harga
            </a>
            
        </div>
    </div>
    
    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                <i class="fa fa-list-ul"></i> Harga Motor <span id="wilayah"></span>
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: none;">

                <form id="filterForm" action="<?php echo base_url('umsl/hargamotor') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('umsl/hargamotor'); ?>"></div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Wilayah Dealer</label>
                                <select class="form-control" id="kd_wilayah" name="kd_wilayah">
                                    <option value='0'>-- Pilih Wilayah Dealer --</option>
                                    <?php 
                                        if($wilayah){
                                            if(is_array($wilayah->message)){
                                                foreach ($wilayah->message as $key => $value) {
                                                    $aktif=($propinsi==$value->KD_PROPINSI)?"selected":"";
                                                    $aktif=($this->input->get('kd_wilayah')==$value->KD_WILAYAH)?"selected":"";
                                                   echo "<option value='".$value->KD_WILAYAH."' ".$aktif.">".$value->NAMA_WILAYAH."</option>";
                                                }
                                            }
                                        }
                                    ?>
                                </select>

                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Field Cari</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="cari berdasarkan Type Motor" >
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
                            <th>No</th>
                            <th>KodeItem</th>
                            <th>Nama Item</th>
                            <th>Wilayah Dealer</th>
                            <!-- <th>Harga MD</th> -->
                            <!-- <th>Harga Dealer</th> -->
                            <th>BBN</th>
                            <th>Harga OTR</th>
                            <th>Kategory</th>
                            <th>Tgl Update</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php

                        $i=$this->input->get("page");
                            if($list){
                                if(is_array($list->message)){
                                    foreach ($list->message as $key => $value) {
                                        $i++;
                                        echo 
                                        "<tr>
                                            <td>$i</td>
                                            <td>".$value->KD_ITEM."</td>
                                            <td>".$value->NAMA_ITEM."</td>
                                            <td>".$value->KD_WILAYAH."</td>
                                            
                                            <td>".number_format($value->BBN,2)."</td>
                                            <td>".number_format($value->HARGA_OTR,2)."</td>
                                            <td>".$value->KD_CATEGORY."</td>
                                            <td>".tglfromSql($value->TGL_UPDATE)."</td>
                                        </tr>
                                        ";
                                    }
                                }
                            }else{
                                echo BelumAdaData(8);
                            }
                       ?>
                    </tbody>
                </table>
            </div>
            <footer class="panel-footer">
                <div class="row">

                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php if($list){echo ($list->totaldata == '') ? "" : "<i>Total Data " . $list->totaldata . " items</i>" ;}?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo $pagination; ?>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <?php echo loading_proses();?>
    <!-- <td>".number_format($value->HARGA,2)."</td>
    <td>".number_format($value->HARGA_DEALER,2)."</td> -->
</section>