<?php

?>

<style type="text/css">
.main_title H1{

	color: #e92030;
}
.carousel-inner .item{
	padding-top: 25px;
}
</style>
<section class="wrapper">

	<img src="<?php echo base_url().'assets/images/trioban.jpg';?>" alt="Second slide" class="img-responsive" style="width: 100%;">

	<!-- Carousel -->
	
	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="false">
		<!-- Indicators -->
		<ol class="carousel-indicators">
			<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
			<li data-target="#carousel-example-generic" data-slide-to="1"></li>
		</ol>
		<!-- Wrapper for slides -->
<!-- 		<div class="carousel-inner">
			<div class="item active"> -->
				<!-- Static Header -->
				<div class="header-text text-center hidden-xs">
					<div class="main_title ">
						<h3>Monitoring Dealer</h3>
						<!-- <h2>Paris <span>Top</span> Tours</h2> -->
						<p>PT. Trio Motor</p>
						<br>

					</div>  
					<div class="col-lg-12 padding-left-right-10">
						<div class="panel panel-default">
							<div class="table-responsive">
								<table class="table table-striped b-t b-light">
									<thead>
										<tr>
											<th style="width:40px;">No.</th>
											<!-- <th>Kode Main Dealer</th> -->
											<th>Kode Dealer</th>
											<th>Nama Dealer</th>
											<th>Jumlah SPK</th>
											<th>Input SPK Terakhir</th>
											<th>Jumlah PKB</th>
											<th>Input PKB Terakhir</th>
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

													<tr id="<?php echo $this->session->flashdata('tr-active') == $row->KD_DEALER ? 'tr-active' : ' ';?>" >
														<td align="left"><?php echo $no;?></td>
														<!-- <td><?php echo $row->KD_MAINDEALER;?></td> -->
														<td align="left"><?php echo $row->KD_DEALER;?></td>
														<td align="left"><?php echo $row->NAMA_DEALER;?></td>
														<td align="left"><?php echo $row->JUMLAH_SPK;?></td>
														<td align="left"><?php echo $row->LAST_INPUT_SPK;?></td>
														<td align="left"><?php echo $row->JUMLAH_PKB;?></td>
														<td align="left"><?php echo $row->LAST_INPUT_PKB;?></td>
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
											?>
											<tr>
												<td>&nbsp;<i class="fa fa-info-circle"></i></td>
												<td colspan="8"><b>ada error, harap hubungi bagian IT</b></td>
											</tr>
											<?php
										endif;
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
<!-- 			</div>
			/header-text
		</div> -->

	</div><!-- /carousel -->

</section>