<html>
<head>
<!--    <link href="css/menu.css" rel="stylesheet" type="text/css" />-->
</head>




<?php echo $this->Session->flash('auth'); ?>

<?php echo $this->Form->create('User'); ?>
    <div id="bac_image"></div>
        <div id="login_logo_image"></div>
        <div style="margin-top: -100px;">
        <?php echo $this->Form->input('username');?>

       <?php echo $this->Form->input('password'); ?>
<div id="fget_ali"><b><?php echo 'Forgot your password?</br>';?></b><font size="1.5"><?php echo $this->Html->link('Click here', array('controller' => 'users','action' => 'forget_password'), array('class'=>'notext'));?></font> <font size="1"> <?php echo 'to receive password </br>via your registered email address.'?></font></div>
   
<div style="margin-top: -51px;" id="lo_but"><?php echo $this->Form->end(__('Login')); ?></div>
        </div>
        
        <div id="copy_right"> <?php echo'Copyright © 2014 Softcodeit Solutions. All rights Reserved.';?></div>
<div id="free_sp_login"></div>


</html>