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
</body>


<?php
echo $this->Form->create('admins',array('action' => 'add_project'));?>

<fieldset>
    <legend><?php echo __('Add new projects'); ?></legend>
 <div id='w_f_body'>
 <?php 
 echo $this->Form->input('Project Name',array('input type'=>"text",'name' => 'pro_name','required'=>'required','placeholder'=>'Project Name','title'=>'Project Name is required.'));
 echo $this->Form->input('Project Desc:',array('input type'=>"text",'name' => 'pro_description','required'=>'required','placeholder'=>'Project Description','title'=>'Project Description is required.'));
 //echo $this->Form->input('PM Emp Id',array('input type'=>"text",'name' => 'PM_EmpId','required'=>'required','placeholder'=>'PM Emp Id','title'=>'PM Employee id is required.'));
//echo $this->Form->input('PM Email',array('input type'=>"text",'name' => 'PM_email','required'=>'required','placeholder'=>'PM Email','title'=>'PM Email is required.'));     
 echo $this->Form->end('Save');    

?></div>
</fieldset>

<table id = "projects">
    <tr>
        <th>Project ID</th>
        <th>Project Name</th>
        <th>Project Description</th>
    </tr>
    <tr>
        <?php foreach($pro_detail as $a):?>
        <td><?php echo $a['Project']['Pid']; ?> </td>
        <td><?php echo $a['Project']['pro_name']; ?> </td>
        <td><?php echo $a['Project']['pro_description']; ?> </td>
    </tr>
<?php endforeach; ?>
</table>