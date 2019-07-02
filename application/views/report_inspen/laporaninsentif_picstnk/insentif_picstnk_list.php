<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$kd_dealer=$this->session->userdata("kd_dealer");
?>
<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>
    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                Dealer
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="penerimaanForm" action="<?php echo base_url('stnk/approve_insentif_picstnk') ?>" class="bucket-form" method="get">

                    <div class="row">

                        <div class="col-xs-12 col-sm-3 col-md-3">

                            <div class="form-group">
                                <label>Dealer</label>
                                <select name="kd_dealer" id="kd_dealer" class="form-control" required="true">
                                    <option value="xxx">- Pilih Dealer -</option>
                                   <?php
                                        if(isset($dealer)):
                                            if($dealer->totaldata >0):
                                                foreach ($dealer->message as $key => $group):
													$dlr = $this->input->get("kd_dealer");
													$default = ($dlr == $group->KD_DEALER) ? " selected" : '';
													if(isset($dealer_ds)){
														foreach($dealer_ds->message as $key => $dlr_ds){
															if($dlr_ds->KD_DEALER == $group->KD_DEALER){
																?><option value="<?php echo $group->KD_DEALER; ?>" <?php echo $default; ?> ><?php echo $group->KD_DEALER." - ".$group->NAMA_DEALER; ?></option><?php
															}
														}
													} else {
													?>
													<option value="<?php echo $group->KD_DEALER; ?>" <?php echo $default; ?> ><?php echo $group->KD_DEALER." - ".$group->NAMA_DEALER; ?></option>
													 <?php 
													}
												endforeach;
                                            endif;
                                        endif;
                                    ?>
                                </select>
                            </div>

                        </div>
						
						
                        <div class="col-xs-12 col-sm-2 col-md-2">
 
                            <div class="form-group">
 
                                <label class="control-label" for="date">Tanggal Awal Pengajuan</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
 
                            </div>
 
                        </div>
 
                        <div class="col-xs-12 col-sm-2 col-md-2">
 
                            <div class="form-group">
 
                                <label class="control-label" for="date">Tanggal Akhir Pengajuan</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
 
                            </div>
 
                        </div>
                        
                        <div class="col-xs-12 col-sm-1 col-md-1">

                            <div class="form-group">

                                <br />
                                <button id="submit-btn" onclick="" class="btn btn-primary"><i class="fa fa-search"></i> Preview</button>

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
                            <th>Dealer</th>
                            <th>No. Proses</th>
                            <th>Periode Pengajuan</th>
							<th>Total Insentif</th>
                            <th>Waktu Pengajuan</th>
                            <th>Status Approval</th>
							<th>Aksi</th>
                        </tr>
						
                       
                    </thead>
                        <?php
                        $no = $this->input->get('page');
						//var_dump($list);exit;
                        if ($list):
                            if (is_array($list->message)):
								$totalunit = 0;
								$birojasa = "";
                                foreach ($list->message as $key => $row):
                                    $no ++;
									?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $row->KD_DEALER; ?></td>
                                        <td><?php echo $row->NO_PROSES; ?></td>
                                        <td><?php echo $row->PERIODE; ?></td>
										<td><?php echo $row->GRAND_TOTAL; ?></td>
                                        <td><?php echo $row->CREATED_TIME; ?></td>
										<td><?php 
										if ($row->APPROVAL_STATUS == 'Y')
											echo '<b style="color:green">Disetujui</b>';
										else if ($row->APPROVAL_STATUS == 'N')
											echo '<b style="color:red">Ditolak</b>';
										else
											echo "Belum Direspon";
										
										?></td>
										<td>
											<a class="btn btn-default" id="modal-button" onclick='addForm("<?php echo base_url('stnk/modal_approve_insentif_picstnk/'.$row->NO_PROSES); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"  title="Rincian Proses Insentif">
												<small><i class='fa fa-eye' data-toggle="tooltip" data-placement="left" ></i></small>
											</a>    
										</td>
										
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
                    <tbody>
                        <?php
                        //echo $html;
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

    $(document).ready(function (e) {
        
    });
    

</script>
