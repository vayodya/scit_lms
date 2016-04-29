<?php echo $this->Form->create('User'); ?>
    
        
        <?php 
        echo $this->Form->input('password' ,array(
   
   'label' => 'New password'
   
));?>
   
<?php echo $this->Form->submit(__('Change')); ?>
<div id="cancels" ><?php echo $this->Html->link('Cancel', array('action' => 'login'), array('class' => 'notext'));?></div>
<?php echo $this->Form->end(); ?>
 
 
