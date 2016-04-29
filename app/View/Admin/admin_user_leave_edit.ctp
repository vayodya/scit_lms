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
</body>


<?php echo $this->Form->create('users'); ?>
    <fieldset>
        <legend><?php echo __('Edit user leave'); ?></legend>
      
<?php   //echo $this->Form->input('Employee ID',array('type' => 'text','name'=> 'EmpId','required'=>'required','title'=>' Employee ID is required.'));?>
<?php   echo $this->Form->input('Annual Leave',array('type' => 'text','name'=> 'no_ann_lv','placeholder'=>$no_ann_lv,'title'=>' Annual Leaves.'));?>
<?php   echo $this->Form->input('casual Leave',array('type' => 'text','name'=> 'no_cas_lv','placeholder'=>$no_cas_lv,'title'=>' Casual Leaves.'));?>  
<?php   echo $this->Form->input('Lieu Leave',array('type' => 'text','name'=> 'no_liv_lv','placeholder'=>$no_liv_lv,'title'=>' Lieu Leaves.'));?>
        
       <?php ?>
        
    </fieldset>
<?php echo $this->Form->end(__('Update')); ?>
