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
</body>


<div class="eventTypes index" style="margin-left:218px">
	<h2><?php echo 'Event Types';?></h2>
	<table id ="table1" cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('name');?></th>
            <th><?php echo $this->Paginator->sort('color');?></th>
            
			<th class="actions">Action</th>
	</tr>
	<?php
	$i = 0;
	foreach ($eventTypes as $eventType):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $eventType['EventType']['name']; ?>&nbsp;</td>
        <td><?php echo $eventType['EventType']['color']; ?>&nbsp;</td>
		<td class="actions">
			
			<?php echo $this->Html->link(__('Edit', true), array('plugin' => 'full_calendar', 'action' => 'edit', $eventType['EventType']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>

	<div class="paging" style="width:700px">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions" style="margin-top:-18%;position:absolute; width:14%">
	<ul>
		<li><?php echo $this->Html->link(__('New Event Type', true), array('plugin' => 'full_calendar', 'action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Manage Events', true), array('plugin' => 'full_calendar', 'controller' => 'events', 'action' => 'index')); ?></li>
        <li><li><?php echo $this->Html->link(__('View Calendar', true), array('plugin' => 'full_calendar', 'controller' => 'full_calendar')); ?></li>
	</ul>
</div>
