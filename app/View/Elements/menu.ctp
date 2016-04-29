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
<!--                <div style="margin-left:85px;font-family: Calibri (Body);"><a class="brand" href="http://www.softcodeit.com/"><div style="background-color:white;"><img id="company-logo" src="/images/logo5.png"></div></a></div>-->
                <div class="nav-collapse collapse" style="margin-left: 43px;">
                    <ul class="nav">
                        <li id="hed"><b>Leave Management System</b></li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dashboard<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link('Home', array('controller' => 'users', 'action' => 'home')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link('Time Tracker', 'http://timetrack.softcodeit.net/', array('target'=>'_blank')); ?>
                                </li>
                            </ul>
                        </li>

                        <?php
//					if ((isset($is_CEO) && $is_CEO == false) && $is_admin == true) { 
                        if (!isset($is_CEO) || (isset($is_CEO) && $is_CEO == false)) {
                            ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Leave<b class="caret"></b></a>


                                <ul class="dropdown-menu">
                                    <li class="divider"></li>
                                    <li>
                                        <?php echo $this->Html->link('Apply Leave', array('controller' => 'LeaveRecords', 'action' => 'add')); ?>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <?php echo $this->Html->link('Leave Report', array('controller' => 'LeaveRecords', 'action' => 'leavereport')); ?>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <?php echo $this->Html->link('Calendar', array('plugin' => 'full_calendar', 'controller' => 'FullCalendar', 'action' => 'index')); ?>
                                    </li>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>

                        <?php
                        if (!(isset($is_CEO) && $is_CEO == true)) {
                            ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Work From Home<b class="caret"></b></a>


                                <ul class="dropdown-menu">
                                    <li class="divider"></li>
                                    <li>
                                        <?php echo $this->Html->link('Apply WFH', array('controller' => 'WorkFromHomes', 'action' => 'add')); ?>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <?php echo $this->Html->link('WFH Report', array('controller' => 'WorkFromHomes', 'action' => 'wfhreport')); ?>
                                    </li>

                                </ul>
                            </li>
                            <?php
                        }
                        ?>								

                        <?php if ($is_admin) { ?>          
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li class="divider"></li>
                                    <li>
                                        <?php echo $this->Html->link('New Accounts', array('controller' => 'admins', 'action' => 'create_new_user')); ?>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <?php echo $this->Html->link('View Users', array('controller' => 'admins', 'action' => 'view_users',)); ?>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <?php echo $this->Html->link('Leave Report', array('controller' => 'LeaveRecords', 'action' => 'view_leave_report')); ?>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <?php echo $this->Html->link('WFH Report', array('controller' => 'WorkFromHomes', 'action' => 'view_wfh_report')); ?>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <?php echo $this->Html->link(__('Calendar setup', true), array('plugin' => 'full_calendar', 'controller' => 'FullCalendar', 'action' => 'view')); ?>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <?php echo $this->Html->link(__('Add Projects', true), array('controller' => 'admins', 'action' => 'add_project')); ?>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <?php echo $this->Html->link(__('Add Employees', true), array('controller' => 'admins', 'action' => 'add_employee')); ?>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <?php echo $this->Html->link(__('Add Leaves', true), array('plugin' => NULL,'controller' => 'admins', 'action' => 'add_holidays')); ?>
                                    </li>
                                </ul>
                            </li>
                    <?php } elseif ($is_pm) { ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Manager<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link(__('Leave Requests', true), array('controller' => 'admins', 'action' => 'leave_request')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link(__('WFH Requests', true), array('controller' => 'admins', 'action' => 'wfh_request')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link('Leave Report', array('controller' => 'LeaveRecords', 'action' => 'view_leave_report')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link('WFH Report', array('controller' => 'WorkFromHomes', 'action' => 'view_wfh_report')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link('Add Leave for Other', array('controller' => 'EmployeesActions', 'action' => 'addLeaveForOther')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link('Leave Summary', array('controller' => 'Reports', 'action' => 'LeaveReportTable')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link(__('Add Projects', true), array('controller' => 'admins', 'action' => 'add_project')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link(__('Add Employees', true), array('controller' => 'admins', 'action' => 'add_employee')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link('New Accounts', array('controller' => 'admins', 'action' => 'create_new_user')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                <?php echo $this->Html->link('Employee Details', array('controller' => 'EmployeesActions', 'action' => 'viewEmployeeDetails')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                <?php echo $this->Html->link('Project Details', array('controller' => 'Admins', 'action' => 'viewProjectDetails')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                <?php echo $this->Html->link(__('Calendar setup', true), array('plugin' => 'full_calendar', 'controller' => 'FullCalendar', 'action' => 'view')); ?>
                                </li>
                            </ul>
                        </li>

                    <?php } elseif ($is_tl) { ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Lead<b class="caret"></b></a>


                            <ul class="dropdown-menu">
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link(__('Leave Requests', true), array('controller' => 'admins', 'action' => 'leave_request')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link(__('WFH Requests', true), array('controller' => 'admins', 'action' => 'wfh_request')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link('Leave Report', array('controller' => 'LeaveRecords', 'action' => 'view_leave_report')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link('WFH Report', array('controller' => 'WorkFromHomes', 'action' => 'view_wfh_report')); ?>
                                </li>

                            </ul>
                        </li>

                    <?php } elseif ($is_CEO) { ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">CEO<b class="caret"></b></a>


                            <ul class="dropdown-menu">
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link(__('Leave Requests', true), array('controller' => 'admins', 'action' => 'leave_request')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link(__('WFH Requests', true), array('controller' => 'admins', 'action' => 'wfh_request')); ?>
                                </li>

                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link('New Accounts', array('controller' => 'admins', 'action' => 'create_new_user')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link('View Users', array('controller' => 'admins', 'action' => 'view_users',)); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link('Leave Report', array('controller' => 'LeaveRecords', 'action' => 'view_leave_report')); ?></li>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link('WFH Report', array('controller' => 'WorkFromHomes', 'action' => 'view_wfh_report')); ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?php echo $this->Html->link('Add Leave for Other', array('controller' => 'EmployeesActions', 'action' => 'addLeaveForOther')); ?>
                                </li>


                        </ul>
                        </li>
                    <?php } ?>


                        <li>
                            <?php
                                $edit_img = $this->html->image('/images/calendar_icon.png', array('alt' => 'Calendars', 'title'=>'Leave Calendar'));
                                echo $this->Html->link($edit_img, array('plugin' =>'full_calendar', 'controller' => 'FullCalendar', 'action' => 'leaveCalendar'), array('escape' => false)); 
                            ?>                            
                        </li>
                    <li></li>


                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>
</center>
