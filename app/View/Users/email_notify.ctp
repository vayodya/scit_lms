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
    ?>
</div> 
<?php echo $this->Form->create('User',array('action' => 'email_notify'));?>
<fieldset>
<legend><?php echo __('Email Notification Settings').'</br>'.'</br>';?></legend>
<?php
if($noti=='enabled'){
    $arrCategory=array('enabled'=>"Enable",'disabled'=>"Disable");
    $noti1='Enabled';
}else{
    $arrCategory=array('disabled'=>"Disable",'enabled'=>"Enable");
    $noti1='Disabled';
}
 echo $this->Form->input('Notification',array('options'=>$arrCategory, 'name'=>'notify',/*'required'=>'required',*/'placeholder'=>'Notification States',/*'title'=>'Notification States is required.','empty'=>'-- Select --',*/'selected'=>'Your Value'));    
 echo $this->Form->end('Save');
 ?>
<b><font color='red'><center>
<?php 
 echo 'Current Notify States is : '.$noti1;
?>
</center></font></b>
</fieldset>
</html>