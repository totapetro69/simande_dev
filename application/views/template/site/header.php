<header class="header fixed-top clearfix">
<!--logo start-->
<div class="brand">
    <a href="<?php echo base_url('/');?>" class="logo">
        <span class="tm tm-logo"></span> SiMANDE
    </a>
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars"></div>
    </div>
</div>
<!--logo end-->
<div class="nav notify-row" id="top_menu">
    <!--  notification start -->
    <ul class="nav top-menu">
        <!-- settings start -->
        <li><h2 class="font-effect-shadow-multiple">
            <?php echo $this->session->userdata("nama_dealer");?>
            
        </h2> 
            
        </li>
    </ul>
    <!--  notification end -->
</div>
<input type="hidden" name="usergroupe" id="usergroupe" value="<?php echo $this->session->userdata("nama_group");?>">

<div class="top-nav clearfix">
    <!--search & user info start-->
    <?php 
        $defaultDealer = $this->session->userdata("kd_dealer");
        $kdlokasi = $this->session->userdata("kd_lokasi");
    ?>
    <ul class="nav pull-right top-menu">
        <!-- <li class="dropdown">
            <a title='Lokasi Dealer ' class="dropdown-toggle" href="#" style="border-radius: 100px; -webkit-border-radius: 100px; text-transform: lowercase;">
                <i class="fa fa-compass fa-fw"></i> 
                <span><?php echo LokasiDealer($defaultDealer,$kdlokasi);?></span>
            </a>
        </li> -->


 <!--        <li class="nav-user"><a title='Lokasi Dealer ' class="dropdown-toggle" href="#"><i class="fa fa-compass fa-fw"></i> 
            <?php echo LokasiDealer($defaultDealer,$kdlokasi);?></a></li>
 -->
        <!-- settings start -->
        <li class="dropdown hidden">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <i class="fa fa-tasks"></i>
                <span class="badge bg-success">8</span>
            </a>
            <ul class="dropdown-menu extended tasks-bar">
                <li>
                    <p class="">You have 8 pending tasks</p>
                </li>
                <li>
                    <a href="#">
                        <div class="task-info clearfix">
                            <div class="desc pull-left">
                                <h5>Target Sell</h5>
                                <p>25% , Deadline  12 June’13</p>
                            </div>
                                    <span class="notification-pie-chart pull-right" data-percent="45">
                            <span class="percent"></span>
                            </span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="task-info clearfix">
                            <div class="desc pull-left">
                                <h5>Product Delivery</h5>
                                <p>45% , Deadline  12 June’13</p>
                            </div>
                                    <span class="notification-pie-chart pull-right" data-percent="78">
                            <span class="percent"></span>
                            </span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="task-info clearfix">
                            <div class="desc pull-left">
                                <h5>Payment collection</h5>
                                <p>87% , Deadline  12 June’13</p>
                            </div>
                                    <span class="notification-pie-chart pull-right" data-percent="60">
                            <span class="percent"></span>
                            </span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="task-info clearfix">
                            <div class="desc pull-left">
                                <h5>Target Sell</h5>
                                <p>33% , Deadline  12 June’13</p>
                            </div>
                                    <span class="notification-pie-chart pull-right" data-percent="90">
                            <span class="percent"></span>
                            </span>
                        </div>
                    </a>
                </li>

                <li class="external">
                    <a href="#">See All Tasks</a>
                </li>
            </ul>
        </li>
        <!-- settings end -->
        <!-- inbox dropdown start-->
        <li id="header_inbox_bar" class="dropdown hidden">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <i class="fa fa-envelope-o"></i>
                <span class="badge bg-important">4</span>
            </a>
            <ul class="dropdown-menu extended inbox">
                <li>
                    <p class="red">You have 4 Mails</p>
                </li>
                <li>
                    <a href="#">
                        <span class="photo"><img alt="avatar" src="<?php echo base_url('assets/images/3.png'); ?>"></span>
                                <span class="subject">
                                <span class="from">Jonathan Smith</span>
                                <span class="time">Just now</span>
                                </span>
                                <span class="message">
                                    Hello, this is an example msg.
                                </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="photo"><img alt="avatar" src="<?php echo base_url('assets/images/1.png'); ?>"></span>
                                <span class="subject">
                                <span class="from">Jane Doe</span>
                                <span class="time">2 min ago</span>
                                </span>
                                <span class="message">
                                    Nice admin template
                                </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="photo"><img alt="avatar" src="<?php echo base_url('assets/images/3.png'); ?>"></span>
                                <span class="subject">
                                <span class="from">Tasi sam</span>
                                <span class="time">2 days ago</span>
                                </span>
                                <span class="message">
                                    This is an example msg.
                                </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="photo"><img alt="avatar" src="<?php echo base_url('assets/images/2.png'); ?>"></span>
                                <span class="subject">
                                <span class="from">Mr. Perfect</span>
                                <span class="time">2 hour ago</span>
                                </span>
                                <span class="message">
                                    Hi there, its a test
                                </span>
                    </a>
                </li>
                <li>
                    <a href="#">See all messages</a>
                </li>
            </ul>
        </li>
        <!-- inbox dropdown end -->
        <!-- notification dropdown start-->
        <li id="header_notification_bar" class="dropdown hidden">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                <i class="fa fa-bell-o"></i>
                <span class="badge bg-warning">3</span>
            </a>
            <ul class="dropdown-menu extended notification">
                <li>
                    <p>Notifications</p>
                </li>
                <li>
                    <div class="alert alert-info clearfix">
                        <span class="alert-icon"><i class="fa fa-bolt"></i></span>
                        <div class="noti-info">
                            <a href="#"> Server #1 overloaded.</a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="alert alert-danger clearfix">
                        <span class="alert-icon"><i class="fa fa-bolt"></i></span>
                        <div class="noti-info">
                            <a href="#"> Server #2 overloaded.</a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="alert alert-success clearfix">
                        <span class="alert-icon"><i class="fa fa-bolt"></i></span>
                        <div class="noti-info">
                            <a href="#"> Server #3 overloaded.</a>
                        </div>
                    </div>
                </li>

            </ul>
        </li>
        <!-- notification dropdown end -->
        <li class="hidden">
            <input type="text" class="form-control search" placeholder=" Search">
        </li>
        <!-- user login dropdown start-->
        <li class="dropdown nav-user">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#"  id="lst_login">
                <img alt="" src="<?php echo base_url('assets/images/4.png'); ?>">
                <span class="username"><?php echo $this->session->userdata("user_name");?></span>
                <span class="badge bg-warning" style="border-radius: 100px; -webkit-border-radius: 100px; text-transform: lowercase;"><i class="fa fa-map-marker fa-fw"></i><?php echo LokasiDealer($defaultDealer,$kdlokasi);?></span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
                
                <li>
                    <a id='profile-modal' class="modal-button" url="<?php echo base_url('auth/profile'); ?>" role="button" data-toggle="modal" data-target="#myModalDf" data-backdrop="static">
                        <i class=" fa fa-suitcase"></i>Profile
                    </a>
                </li>

                <li>
                    <a id='password-modal' class="modal-button" url="<?php echo base_url('auth/ganti_password'); ?>" role="button" data-toggle="modal" data-target="#myModalDf" data-backdrop="static">
                        <i class="fa fa-unlock-alt"></i> Ubah Password
                    </a>
                </li>

                <li><hr ></li>
                <li><a href="<?php echo base_url();?>auth/logout"><i class="fa fa-key"></i> Log Out</a></li>
            </ul>
        </li>
        <!-- user login dropdown end -->
       
    </ul>
    <!--search & user info end-->
</div>
</header>