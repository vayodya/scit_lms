<?php echo $this->element("cmenu"); ?>
<div id="login">
    <div id="semilog">
    <?php 
    echo '&nbsp;'.$loguser.' '.'&nbsp;';?>
    </div>
    <?php //echo $this->Html->link('Sign Out ', array('controller' => 'Users','action' => 'logout'));
    $de_img = $this->Html->image('../images/Logout-Icon.jpg',array('alt' => 'Sign Out', 'title'=>'Sign Out'));
    echo $this->Html->link($de_img, array('controller' => 'Users','action' => 'logout'), array('escape' => false));
    $delete_img = $this->Html->image('../images/User_Edit.jpg',array('alt' => 'Delete', 'title'=>'Edit Profile'));
    echo $this->Html->link($delete_img, array('plugin' => NULL,'action' => 'edit','controller' => 'Users','action' => 'profileedit'), array('escape' => false)).'&nbsp;';
    $notify_img = $this->Html->image('../images/imagesnoti.png',array('alt' => 'Notify', 'title'=>'Notification Setting'));
    echo $this->Html->link($notify_img, array('plugin' => NULL,'controller' => 'Users','action' => 'email_notify'), array('escape' => false));
    ?>
</div>
</body>

<?php
/*
 * View/FullCalendar/index.ctp
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */
?>
<script type="text/javascript">
plgFcRoot = '<?php echo $this->Html->url('/'); ?>' + "full_calendar";
</script>
<?php
echo $this->Html->script(array('/full_calendar/js/jquery-1.5.min', '/full_calendar/js/jquery-ui-1.8.9.custom.min', '/full_calendar/js/fullcalendar.min', '/full_calendar/js/jquery.qtip-1.0.0-rc3.min', '/full_calendar/js/ready'), array('inline' => 'false'));
echo $this->Html->css('/full_calendar/css/fullcalendar', null, array('inline' => false));
?>


<div class="Calendar index" style="margin-left:202px">
	<div id="calendar"></div>
</div>



	<div class="actions" style="margin-top:-381px">
		<ul>
			<li><?php echo $this->Html->link(__('Add Event Type', true), array('plugin' => 'full_calendar', 'controller' => 'EventTypes', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('New Event', true), array('plugin' => 'full_calendar', 'controller' => 'events', 'action' => 'add')); ?></li>
			<li><?php echo $this->Html->link(__('Manage Events', true), array('plugin' => 'full_calendar', 'controller' => 'events')); ?></li>
			<li><?php echo $this->Html->link(__('Manage Events Types', true), array('plugin' => 'full_calendar', 'controller' => 'event_types')); ?></li>
		</ul>
	</div>
