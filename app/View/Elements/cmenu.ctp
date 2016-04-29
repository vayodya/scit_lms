<?php /* <html>
<head><link href="css/menu.css" rel="stylesheet" type="text/css" /></head>

<div id="wrapper">
<body>
<center><div class="contain">

 <center>           <ul id="nav">
                
                <li><?php echo $this->Html->link('     Home', array('plugin' => NULL, 'controller' => 'users','action' => 'home'));?></a></li></li>
                <li><a class="hsubs" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Leave&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                    <ul class="subs">
                        <li><?php echo $this->Html->link('Apply Leave', array('plugin' => NULL,'controller' => 'LeaveRecords','action' => 'add'));?></a></li>
                        <li><?php echo $this->Html->link('Leave Report', array('plugin' => NULL,'controller' => 'LeaveRecords','action' => 'leavereport'));?></li>
                        <li><?php echo $this->Html->link('Calendar', array('plugin' => 'full_calendar','controller' => 'FullCalendar', 'action' => 'index'));?></li>
                        
                    </ul>
                </li>
                <li><a class="hsubs" >Work from home</a>
                 <ul class="subs">
                        <li><?php echo $this->Html->link('Apply WFH', array('plugin' => NULL,'controller' => 'WorkFromHomes','action' => 'add'));?></a></li>
                        <li><?php echo $this->Html->link('WFH Report', array('plugin' => NULL,'controller' => 'WorkFromHomes','action' => 'wfhreport'));?></li>
                        
                        
                    </ul>
                   
                   
                </li>
               
                <?php if($is_admin){?>
                     <li><a class="hsubs" href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Admin&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                            <ul class="subs">
                        <li><?php echo $this->Html->link('New Accounts', array('plugin' => NULL,'controller' => 'admins','action' => 'create_new_user'));?></li>
                        <li><?php echo $this->Html->link('View Users', array('plugin' => NULL,'controller' => 'admins','action' => 'view_users', ));?></li>
                        <li><?php echo $this->Html->link('Leave Report', array('plugin' => NULL,'controller' => 'LeaveRecords','action' => 'view_leave_report'));?></li>
                        <li><?php echo $this->Html->link('WFH Report', array('controller' => 'WorkFromHomes','action' => 'view_wfh_report'));?></li>
                        <li><?php echo $this->Html->link(__('Calendar setup', true), array('plugin' => 'full_calendar', 'controller' => 'FullCalendar', 'action' => 'view')); ?></li>
                        <li><?php echo $this->Html->link(__('Add Projects', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'add_project')); ?></li>
                        <li><?php echo $this->Html->link(__('Add Employees', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'add_employee')); ?></li>
                        <li><?php echo $this->Html->link(__('Add Leaves', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'add_holidays')); ?></li>
                            </ul>
                     </li>
                     
                <?php }elseif($is_pm) {?>
                     
                        <li><a class="hsubs" href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                            <ul class="subs">
                        <li><?php echo $this->Html->link(__('Leave Requests', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'leave_request')); ?></li>
                        <li><?php echo $this->Html->link(__('WFH Requests', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'wfh_request')); ?></li>
                            </ul>
                     </li>

                <?php }elseif($is_tl) {?>
                     
                        <li><a class="hsubs" href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tech Lead&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                            <ul class="subs">
                        <li><?php echo $this->Html->link(__('Leave Requests', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'leave_request')); ?></li>
                        <li><?php echo $this->Html->link(__('WFH Requests', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'wfh_request')); ?></li>
                            </ul>
                     </li>

               <?php }elseif($is_CEO) {?>
                     
                        <li><a class="hsubs" href="#">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CEO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                            <ul class="subs">
                        <li><?php echo $this->Html->link(__('Leave Requests', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'leave_request')); ?></li>
                        <li><?php echo $this->Html->link(__('WFH Requests', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'wfh_request')); ?></li>

<li><?php echo $this->Html->link('New Accounts', array('controller' => 'admins','action' => 'create_new_user'));?></li>
                        <li><?php echo $this->Html->link('View Users', array('controller' => 'admins','action' => 'view_users', ));?></li>
                        <li><?php echo $this->Html->link('Leave Report', array('controller' => 'LeaveRecords','action' => 'view_leave_report'));?></li>
                        <li><?php echo $this->Html->link('WFH Report', array('controller' => 'WorkFromHomes','action' => 'view_wfh_report'));?></li>

                            </ul>
                     </li>
                <?php }?>
                        
                <div id="lavalamp"></div>
            </ul>

        </div></center></center> */ ?>


<meta charset="utf-8">
<title>Schedule </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- styles -->
<link href="bootstrap/assets/css/bootstrap.css" rel="stylesheet">
<link href="bootstrap/assets/css/bootstrap-responsive.css" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="bootstrap/assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="bootstrap/assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="bootstrap/assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="bootstrap/assets/ico/apple-touch-icon-57-precomposed.png">
<link rel="icon" type="image/ico" href="my.ico">
<!-- Placed at the end of the document so the pages load faster -->
<script src="/js/jquery.js"></script>
<script src="/js/bootstrap-transition.js"></script>
<script src="/js/bootstrap-alert.js"></script>
<script src="/js/bootstrap-modal.js"></script>
<!--<script src="/js/bootstrap-dropdown.js"></script>-->
<script src="/js/bootstrap-scrollspy.js"></script>
<script src="/js/bootstrap-tab.js"></script>
<script src="/js/bootstrap-tooltip.js"></script>
<script src="/js/bootstrap-popover.js"></script>
<script src="/js/bootstrap-button.js"></script>
<script src="/js/bootstrap-collapse.js"></script>
<script src="/js/bootstrap-carousel.js"></script>
<script src="/js/bootstrap-typeahead.js"></script>

<style>
	body {
		padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
	}
</style>
<center>


<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
<!--			<div style="margin-left:85px;"><a class="brand" href="http://www.softcodeit.com/"><div style="background-color:white;"><img src="../images/logo5.png"></div></a></div>-->
			<div class="nav-collapse collapse" style="margin-left: 43px;">
				<ul class="nav">
                                    <li id="hed"><b>Leave Management System</b></li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dashboard<b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li class="divider"></li>
                                            <li>
                                                <?php echo $this->Html->link('Home', array('plugin' => NULL, 'controller' => 'users','action' => 'home'));?>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <?php echo $this->Html->link('Time Tracker', 'http://timetrack.softcodeit.net/', array('target'=>'_blank')); ?>
                                            </li>
                                        </ul>
                                    </li>                                   
				    <li class="dropdown">
					   <a href="#" class="dropdown-toggle" data-toggle="dropdown">Leave<b class="caret"></b></a>
                                                
                                               
						<ul class="dropdown-menu">
							<li class="divider"></li>
							<li>
								<?php echo $this->Html->link('Apply Leave', array('plugin' => NULL,'controller' => 'LeaveRecords','action' => 'add'));?>
							</li>
							<li class="divider"></li>
							<li>
								<?php echo $this->Html->link('Leave Report', array('plugin' => NULL,'controller' => 'LeaveRecords','action' => 'leavereport'));?>
							</li>
                                                        <li class="divider"></li>
							<li>
								<?php echo $this->Html->link('Calendar', array('plugin' => 'full_calendar','controller' => 'FullCalendar', 'action' => 'index'));?>
							</li>
						</ul>
					</li>
                                        
                                        <li class="dropdown">
					   <a href="#" class="dropdown-toggle" data-toggle="dropdown">Work From Home<b class="caret"></b></a>
                                                
                                               
						<ul class="dropdown-menu">
							<li class="divider"></li>
							<li>
								<?php echo $this->Html->link('Apply WFH', array('plugin' => NULL,'controller' => 'WorkFromHomes','action' => 'add'));?>
							</li>
							<li class="divider"></li>
							<li>
								<?php echo $this->Html->link('WFH Report', array('plugin' => NULL,'controller' => 'WorkFromHomes','action' => 'wfhreport'));?>
							</li>
                                                        
						</ul>
					</li>
                              <?php if($is_admin){?>          
                                        <li class="dropdown">
					   <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin<b class="caret"></b></a>
                                                
                                               
						<ul class="dropdown-menu">
							<li class="divider"></li>
							<li>
								<?php echo $this->Html->link('New Accounts', array('plugin' => NULL,'controller' => 'admins','action' => 'create_new_user'));?>
							</li>
							<li class="divider"></li>
							<li>
								<?php echo $this->Html->link('View Users', array('plugin' => NULL,'controller' => 'admins','action' => 'view_users', ));?>
							</li>
                                                        <li class="divider"></li>
							<li>
								<?php echo $this->Html->link('Leave Report', array('plugin' => NULL,'controller' => 'LeaveRecords','action' => 'view_leave_report'));?></li>
							</li>
                                                        <li class="divider"></li>
							<li>
								<?php echo $this->Html->link('WFH Report', array('plugin' => NULL,'controller' => 'WorkFromHomes','action' => 'view_wfh_report'));?></li>
							</li>
                                                        <li class="divider"></li>
							<li>
								<?php echo $this->Html->link(__('Calendar setup', true), array('plugin' => 'full_calendar', 'controller' => 'FullCalendar', 'action' => 'view')); ?>
							</li>
                                                        <li class="divider"></li>
							<li>
								<?php echo $this->Html->link(__('Add Projects', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'add_project')); ?>
							</li>
                                                        <li class="divider"></li>
							<li>
								<?php echo $this->Html->link(__('Add Employees', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'add_employee')); ?>
							</li>
                                                        <li class="divider"></li>
							<li>
								<?php echo $this->Html->link(__('Add Leaves', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'add_holidays')); ?>
							</li>
                                                        
                                                        
						</ul>
					</li>
                                        <?php }elseif($is_pm) {?>
                                            <li class="dropdown">
					   <a href="#" class="dropdown-toggle" data-toggle="dropdown">Manager<b class="caret"></b></a>
                                                
                                               
						<ul class="dropdown-menu">
							<li class="divider"></li>
							<li>
								<?php echo $this->Html->link(__('Leave Requests', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'leave_request')); ?>
							</li>
							<li class="divider"></li>
							<li>
								<?php echo $this->Html->link(__('WFH Requests', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'wfh_request')); ?>
							</li>
                                                        <li class="divider"></li>
                                                        <li>
                                                                <?php echo $this->Html->link('Leave Report', array('plugin' => NULL,'controller' => 'LeaveRecords', 'action' => 'view_leave_report')); ?>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li>
                                                                <?php echo $this->Html->link('WFH Report', array('plugin' => NULL,'controller' => 'WorkFromHomes', 'action' => 'view_wfh_report')); ?>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li>
                                                                <?php echo $this->Html->link('Add Leave for Other', array('plugin' => NULL,'controller' => 'EmployeesActions', 'action' => 'addLeaveForOther')); ?>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li>
                                                                <?php echo $this->Html->link('Leave Summary', array('plugin' => NULL,'controller' => 'Reports', 'action' => 'LeaveReportTable')); ?>
                                                        </li>


                                                        
						</ul>
					</li>
                                        
                                        <?php }elseif($is_tl) {?>
                                            <li class="dropdown">
					   <a href="#" class="dropdown-toggle" data-toggle="dropdown">Lead<b class="caret"></b></a>
                                                
                                               
						<ul class="dropdown-menu">
							<li class="divider"></li>
							<li>
								<?php echo $this->Html->link(__('Leave Requests', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'leave_request')); ?>
							</li>
							<li class="divider"></li>
							<li>
								<?php echo $this->Html->link(__('WFH Requests', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'wfh_request')); ?>
							</li>
                                                        <li class="divider"></li>
                                                        <li>
                                                                <?php echo $this->Html->link('Leave Report', array('plugin' => NULL,'controller' => 'LeaveRecords', 'action' => 'view_leave_report')); ?>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li>
                                                                <?php echo $this->Html->link('WFH Report', array('plugin' => NULL,'controller' => 'WorkFromHomes', 'action' => 'view_wfh_report')); ?>
                                                        </li>
                                                        
						</ul>
					</li>
                                        
                                        <?php }elseif($is_CEO) {?>
                                            <li class="dropdown">
					   <a href="#" class="dropdown-toggle" data-toggle="dropdown">CEO<b class="caret"></b></a>
                                                
                                               
						<ul class="dropdown-menu">
							<li class="divider"></li>
							<li>
								<?php echo $this->Html->link(__('Leave Requests', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'leave_request')); ?>
							</li>
							<li class="divider"></li>
							<li>
								<?php echo $this->Html->link(__('WFH Requests', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'wfh_request')); ?>
							</li>
                                                        
                                                        <li class="divider"></li>
							<li>
								<?php echo $this->Html->link('New Accounts', array('plugin' => NULL,'controller' => 'admins','action' => 'create_new_user'));?>
							</li>
							<li class="divider"></li>
							<li>
								<?php echo $this->Html->link('View Users', array('plugin' => NULL,'controller' => 'admins','action' => 'view_users', ));?>
							</li>
                                                        <li class="divider"></li>
							<li>
								<?php echo $this->Html->link('Leave Report', array('plugin' => NULL,'controller' => 'LeaveRecords','action' => 'view_leave_report'));?></li>
							</li>
                                                        <li class="divider"></li>
							<li>
								<?php echo $this->Html->link('WFH Report', array('plugin' => NULL,'controller' => 'WorkFromHomes','action' => 'view_wfh_report'));?></li>
							
                                                        
						</ul>
					</li>
                                        <?php }?>
					
					
					<li></li>
					<li></li>
					
					
				</ul>
			</div><!--/.nav-collapse -->
                        <?php
                            $edit_img = $this->html->image('/images/calendar_icon.png', array('alt' => 'Calendars', 'title'=>'Leave Calendar'));
                            echo $this->Html->link($edit_img, array('plugin' =>'full_calendar', 'controller' => 'FullCalendar', 'action' => 'leaveCalendar'), array('escape' => false)); 
                        ?>
		</div>
	</div>
</div>
</center>