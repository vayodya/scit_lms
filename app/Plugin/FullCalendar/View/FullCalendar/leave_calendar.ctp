<?php 

echo $this->element("cmenu"); 

echo $this->Html->css(
        array(
                '/full_calendar/css/lms_calendar',
                '/full_calendar/css/fullcalendar'
            ), 
        null, array('inline' => false)); 
?>

<div id="login">
    <div id="semilog">
    <?php echo '&nbsp;'.$loguser.' '.'&nbsp;';?>
    </div>
    
    <?php //echo $this->Html->link('Sign Out ', array('controller' => 'Users','action' => 'logout'));
    $de_img = $this->Html->image('../images/Logout-Icon.jpg',array('alt' => 'Sign Out', 'title'=>'Sign Out'));
    echo $this->Html->link($de_img, array('plugin' => NULL, 'controller' => 'Users','action' => 'logout'), array('escape' => false));
    $delete_img = $this->Html->image('../images/User_Edit.jpg',array('alt' => 'Delete', 'title'=>'Edit Profile'));
    echo $this->Html->link($delete_img, array('plugin' => NULL,'action' => 'edit','controller' => 'Users','action' => 'profileedit'), array('escape' => false)).'&nbsp;';
    $notify_img = $this->Html->image('../images/imagesnoti.png',array('alt' => 'Notify', 'title'=>'Notification Setting'));
    echo $this->Html->link($notify_img, array('plugin' => NULL,'controller' => 'Users','action' => 'email_notify'), array('escape' => false));
    ?>
</div>
<h2 class="leave_calendar">Leave Calendar</h2>
<div id="calendar"></div>

<script type="text/javascript">
    plgFcRoot = '<?php echo $this->Html->url('/'); ?>' + "full_calendar";
</script>
test
<?php
echo $this->Html->script(array('/full_calendar/js/jquery-1.5.min',
                    '/full_calendar/js/jquery-ui-1.8.9.custom.min', 
                    '/full_calendar/js/fullcalendar.min', 
                    '/full_calendar/js/jquery.qtip-1.0.0-rc3.min', 
                    '/full_calendar/js/ready2',
    ), array('inline' => 't'));

?>
