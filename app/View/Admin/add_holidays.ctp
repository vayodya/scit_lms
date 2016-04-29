<?php echo $this->element("menu"); ?>
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
<div style="margin-left: 100px;">
<?php
echo $this->Form->create('admins',array( 'enctype' => 'multipart/form-data'));?>
 
<fieldset>
    <legend><?php echo __('Upload a holiday csv file'); ?></legend>
    <div id='w_f_body'>
 <?php
echo $this->Form->input('Holidays', array('type' => 'file')); ?>
   
 <?php echo $this->Form->end('Save');    

?></div>
</fieldset> 
</div>


<div></br></div>
<div style="margin-left: 359px;"><b><font color ="#205081">Add or Change Employee Leaves <font></b></div>
<div style="margin-left: 140px;"><table id ="add_holi">
    <tr>
        <?php // <th>Employee Id</th> ?>
        <th><?php echo $this->Paginator->sort('EmpId', 'Employee ID');?></th>
        <?php // <th>Employee name</th>?>
        <th><?php echo $this->Paginator->sort('EmpName', 'Employee Name');?></th>
        
        <?php //<th>Role</th> ?>
        <th><?php echo $this->Paginator->sort('role', 'Role');?></th>
        <th>Action</th>
       
    </tr>

    <!-- Here is where we loop through our $posts array, printing out post info -->

    <?php foreach ($users as $user): ?>
    <tr>        
        <td><?php echo ($user['User']['EmpId']); ?></td>
        
        <td><?php echo $user['User']['EmpName']; ?></td>       
        
        <td><?php 
        if($user['User']['role']=='admin'){echo 'Admin';}
        elseif ($user['User']['role']=='pm'){echo 'Manager';}
        elseif ($user['User']['role']=='tl'){echo 'Lead';}
        elseif ($user['User']['role']=='normal'){echo 'Normal';}
        else{echo 'CEO';}
        ?></td>
        <td><?php echo $this->Html->link('Assign Leaves', array('action' => 'admin_user_leave_edit', $user['User']['id']),array('title'=>'')); ?></td>
        
    </tr>
    <?php endforeach; ?>
   
</table></div>


<div class="paging" align="center">
<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>