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

        <div id="bc1" class="myBreadcrumb">
      <a href="javascript:void(0);"><i class="fa fa-home fa-2x"></i></a>
      <!-- <div>...</div> -->
      <a href="javascript:void(0);"><div>Motor</div></a>
      <a href="javascript:void(0);" class="active"><div>Bundling</div></a>
	  <a href="javascript:void(0);" class="active"><div>Detail</div></a>
    </div>
		<div class="bar-nav pull-right ">
			
			<a id="modal-button" class="btn btn-default <?php echo $status_c?>" onclick='addForm("<?php echo base_url('motor/add_item_bundling/'. $list_header->message[0]->KD_BUNDLING); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
				<i class="fa fa-file-o fa-fw"></i> Tambah Item
			</a>
			<a id="modal-button" class="btn btn-default <?php echo $status_c?>" onclick='addForm("<?php echo base_url('motor/add_aksesoris_bundling/'. $list_header->message[0]->KD_BUNDLING); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
				<i class="fa fa-file-o fa-fw"></i> Tambah Aksesoris
			</a>
			<a id="modal-button" class="btn btn-default <?php echo $status_c?>" onclick='addForm("<?php echo base_url('motor/add_apparel_bundling/'. $list_header->message[0]->KD_BUNDLING); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
				<i class="fa fa-file-o fa-fw"></i> Tambah Apparel
			</a>
        </div>
    </div>
	<div class="col-lg-12 padding-left-right-10">
    	<div class="panel margin-bottom-5">
    		<div class="panel-heading">
                <i class="fa fa-list fa-fw"></i> Bundling Header
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border">
				<table class="table table-striped b-t b-light" border="0">
					<tr>
						<td>Kode Bundling</td>
						<td>: <?php echo $list_header->message[0]->KD_BUNDLING; ?></td>
					</tr>
					<tr>
						<td>Nama Bundling</td>
						<td>: <?php echo $list_header->message[0]->NAMA_BUNDLING; ?></td>
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
							<th colspan="5">Motor</th>
						</tr>
					
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th style="width:45px;">Aksi</th>
                            <th>Tipe Motor</th>
							<th>Kode Warna</th>
                            <th>Jumlah</th>
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

                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp">
											
                                            <a id="modal-button" onclick='addForm("<?php echo base_url('motor/edit_bundling_item/'.$row->KD_BUNDLING.'/'.$row->TYPE_BUNDLING.'/'.$row->KD_WARNA.'/'.$row->STATUS_BUNDLING); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Ubah" class="fa fa-edit text-success text-active"></i>
                                            </a>

                                            <a id="delete-btn<?php echo $no; ?>" class="delete-btn" url="<?php echo base_url('motor/delete_bundling_detail/'.$row->ID.'/'.$row->KD_BUNDLING); ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                                            </a>
                                        </td>
                                        <td><?php echo $row->KD_TYPEMOTOR; ?> - <?php echo $row->NAMA_TYPEMOTOR; ?> - <?php echo $row->NAMA_PASAR; ?> - <?php echo $row->KET_WARNA; ?></td>
                                        <td><?php echo $row->KD_WARNA; ?></td>
										<td><?php echo $row->JUMLAH; ?></td>
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
				<br>
				<table class="table table-striped b-t b-light">
                    <thead>
						<tr>
							<th colspan="4">Aksesoris</th>
						</tr>
					
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th style="width:45px;">Aksi</th>
                            <th>Aksesoris</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if ($list_aksesoris):
                            if (is_array($list_aksesoris->message) || is_object($list_aksesoris->message)):
                                foreach ($list_aksesoris->message as $key => $row):
                                    $no ++;
                                    ?>

                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp">
											
                                            <a id="modal-button" onclick='addForm("<?php echo base_url('motor/edit_bundling_aksesoris/'.$row->KD_BUNDLING.'/'.$row->TYPE_BUNDLING); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Ubah" class="fa fa-edit text-success text-active"></i>
                                            </a>

                                            <a id="delete-btn<?php echo $no; ?>" class="delete-btn" url="<?php echo base_url('motor/delete_bundling_detail/'.$row->ID.'/'.$row->KD_BUNDLING); ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                                            </a>
                                        </td>
                                        <td><?php echo $row->NAMA_AKSESORIS; ?></td>
                                        <td><?php echo $row->JUMLAH; ?></td>
                                    </tr>

                                    <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                </tr>
                            <?php
                            endif;
                        else:
                            echo belumAdaData(40);
                        endif;
                        ?>
                    </tbody>

                </table>
				<br>
				<table class="table table-striped b-t b-light">
                    <thead>
						<tr>
							<th colspan="4">Apparel</th>
						</tr>
					
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th style="width:45px;">Aksi</th>
                            <th>Apparel</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php
                        $no = $this->input->get('page');
                        if ($list_apparel):
                            if (is_array($list_apparel->message) || is_object($list_apparel->message)):
                                foreach ($list_apparel->message as $key => $row):
                                    $no ++;
                                    ?>

                                    <tr id="<?php echo $this->session->flashdata('tr-active') == $row->ID ? 'tr-active' : ' '; ?>" >
                                        <td><?php echo $no; ?></td>
                                        <td class="table-nowarp">
											
                                            <a id="modal-button" onclick='addForm("<?php echo base_url('motor/edit_bundling_apparel/'.$row->KD_BUNDLING.'/'.$row->TYPE_BUNDLING); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" class="<?php echo $status_v ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Ubah" class="fa fa-edit text-success text-active"></i>
                                            </a>

                                            <a id="delete-btn<?php echo $no; ?>" class="delete-btn" url="<?php echo base_url('motor/delete_bundling_detail/'.$row->ID.'/'.$row->KD_BUNDLING); ?>">
                                                <i data-toggle="tooltip" data-placement="left" title="Hapus" class="fa fa-trash text-danger text"></i>
                                            </a>
                                        </td>
                                        <td><?php echo $row->NAMA_APPAREL; ?></td>
                                        <td><?php echo $row->JUMLAH; ?></td>
                                    </tr>

                                    <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
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

                </div>

            </footer>

        </div>

    </div>

</section>