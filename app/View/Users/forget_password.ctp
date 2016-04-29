<?php 
echo $this->Form->create('User'); 
echo $this->Form->input('username');
?>
<div style="margin-bottom: -2em;">
    <div style="display: inline-block;">
        <?php echo $this->Form->submit(__('Submit')); ?>
    </div>
    <div id="cancel_aling" style="margin-top: 17px; display: inline-block;">
        <?php echo $this->Html->link('Cancel', array('action' => 'login'), array('class' => 'reser_but', 'style' => 'margin-left: 0px;'));?>
    </div>
</div>
<?php echo $this->Form->end(); ?>
 

