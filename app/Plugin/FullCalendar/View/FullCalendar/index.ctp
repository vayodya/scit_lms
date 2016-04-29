<?php echo $this->element("cmenu"); ?>
<div id="login">
    <div id="semilog">
    <?php 
    echo '&nbsp;'.$loguser.' '.'&nbsp;';?>
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
echo $this->Html->script(array('/full_calendar/js/jquery-1.5.min',
                    '/full_calendar/js/jquery-ui-1.8.9.custom.min', 
                    '/full_calendar/js/fullcalendar.min', 
                    '/full_calendar/js/jquery.qtip-1.0.0-rc3.min', 
//                    '/full_calendar/js/ready',
                    '/full_calendar/js/ready2',
//                    '/full_calendar/js/one'
    ), array('inline' => 't'));

echo $this->Html->css('/full_calendar/css/fullcalendar', null, array('inline' => false));
?>

<script>

	$(document).ready(function() {
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			defaultDate: '2014-09-12',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: //$typeJson
//                                [
//                                {
//                                    "title": "hello",
//                                    "start": "2014-09-02",
//                                    "className": "green"
//                                },
//                                {   "title": "Nimantha",
//                                    "start": "2014-09-10",
//                                    "end": "2014-09-13"
//                                }    
//                                ]
                                
//                                [
//				{
//					title: 'All Day Event',
//					start: '2014-09-01'
//				},
//				{
//					title: 'Long Event',
//					start: '2014-09-07',
//					end: '2014-09-10'
//				},
//				{
//					id: 999,
//					title: 'Repeating Event',
//					start: '2014-09-09T16:00:00'
//				},
//				{
//					id: 999,
//					title: 'Repeating Event',
//					start: '2014-09-16T16:00:00'
//				},
//				{
//					title: 'Conference',
//					start: '2014-09-11',
//					end: '2014-09-13'
//				},
//				{
//					title: 'Meeting',
//					start: '2014-09-12T10:30:00',
//					end: '2014-09-12T12:30:00'
//				},
//				{
//					title: 'Lunch',
//					start: '2014-09-12T12:00:00'
//				},
//				{
//					title: 'Meeting',
//					start: '2014-09-12T14:30:00'
//				},
//				{
//					title: 'Happy Hour',
//					start: '2014-09-12T17:30:00'
//				},
//				{
//					title: 'Dinner',
//					start: '2014-09-12T20:00:00'
//				},
//				{
//					title: 'Birthday Party',
//					start: '2014-09-13T07:00:00'
//				},
//				{
//					title: 'Click for Google',
//					url: 'http://google.com/',
//					start: '2014-09-28'
//				}
//			]
		});
		
	});

</script>
<style>

	body {
		margin: 40px 10px;
		padding: 0;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		font-size: 14px;
	}

	#calendar {
		max-width: 900px;
		margin: 0 auto;
	}
        h2{
            font-family: "Arial Black" !important;
            color: black !important;
            font-weight: bold !important;
            text-align: center;
        }
        #login {
            margin-top: -30px;
            
        }

</style>

<div id="calendar"></div>



	