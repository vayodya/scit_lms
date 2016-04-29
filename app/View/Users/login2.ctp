<?php echo $this->Form->create('User'); ?>
    
        
        <?php 
        echo $this->Form->input('pwd' ,array(
   
   'label' => 'Securiy code'
   
));?>
   
<?php echo $this->Form->submit(__('Submit')); ?>
<div id="cancels" ><?php echo $this->Html->link('Cancel', array('action' => 'login'), array('class' => 'notext'));?></div>
<?php echo $this->Form->end(); ?>
 
 
