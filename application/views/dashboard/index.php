<?php

foreach ($message as $key => $value) {
	$nama_dealer = $value->NAMA_DEALER;
	$alamat = $value->ALAMAT_LENGKAP;
	$tlp = $value->TLP;
}

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
		<div class="carousel-inner">
		    <div class="item active">
		    	<!-- Static Header -->
                <div class="header-text text-center hidden-xs">
                   <div class="main_title ">
                   	<h1><strong><?php echo $nama_dealer;?></strong></h1>
		                <!-- <h2>Paris <span>Top</span> Tours</h2> -->
		                <p><?php echo $alamat;?></p>
		                <p><?php echo $tlp;?></p>

            		</div>  
            		<!-- <div class="">
                         <a class="btn btn-theme btn-sm btn-min-block" href="#">Login</a><a class="btn btn-theme btn-sm btn-min-block" href="#">Register</a>
                    </div> -->
                </div>
                <!-- /header-text -->
		    	<img src="<?php echo base_url().'assets/images/banner1.jpg';?>" alt="Second slide">
		    </div>
		    <div class="item">
		    	<!-- Static Header -->
                <div class="header-text text-center hidden-xs">
                   <div class="main_title ">
                   	<h1><strong><?php echo $nama_dealer;?></strong></h1>
		                <!-- <h2>Paris <span>Top</span> Tours</h2> -->
		                <p><?php echo $alamat;?></p>
		                <p><?php echo $tlp;?></p>
		                
            		</div>  
            		<!-- div class="">
                         <a class="btn btn-theme btn-sm btn-min-block" href="#">Login</a><a class="btn btn-theme btn-sm btn-min-block" href="#">Register</a>
                    </div> -->
                </div>
                <!-- /header-text -->
		    	<img src="<?php echo base_url().'assets/images/banner2.jpg';?>" alt="Second slide">
		    </div>
		</div>
		<!-- Controls -->
		<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
	    	<span class="glyphicon glyphicon-chevron-left"></span>
		</a>
		<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
	    	<span class="glyphicon glyphicon-chevron-right"></span>
		</a>
	</div><!-- /carousel -->

</section>