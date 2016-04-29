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
    echo $this->Html->link($delete_img, array('action' => 'edit','controller' => 'Users','action' => 'profileedit'), array('escape' => false)).'&nbsp;';
    $notify_img = $this->Html->image('../images/imagesnoti.png',array('alt' => 'Notify', 'title'=>'Notification Setting'));
    echo $this->Html->link($notify_img, array('action' => 'edit','controller' => 'Users','action' => 'email_notify'), array('escape' => false));
    ?>
</div>


<div class="eventTypes form" style="margin-left:230px">
<?php echo $this->Form->create('EventType');?>
	<fieldset>
 		<legend><?php echo 'Add Event Type'; ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('color', 
					array('options' => array(
						'Blue' => 'Blue',
						'Red' => 'Red',
						'Pink' => 'Pink',
						'Purple' => 'Purple',
						'Orange' => 'Orange',
						'Green' => 'Green',
						'Gray' => 'Gray',
						'Black' => 'Black',
						'Brown' => 'Brown'
					)));

	?>
	
<?php echo $this->Form->end(__('Submit', true));?>
                </fieldset>
</div>
<div class="actions" style="margin-top:-17%;position:absolute;width:14%">
	<ul>
		<li><?php echo $this->Html->link(__('Manage Event Types', true), array('plugin' => 'full_calendar', 'action' => 'index'));?></li>
		<li><li><?php echo $this->Html->link(__('View Calendar', true), array('plugin' => 'full_calendar', 'controller' => 'full_calendar')); ?></li>
	</ul>
</div>
