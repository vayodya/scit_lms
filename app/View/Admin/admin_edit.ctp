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
        <legend><?php echo __('Edit user'); ?></legend>
      <?php $user = $user_role;
      foreach ($users as $u) {
          
     ?>


  
        <?php   echo $this->Form->input('Employee ID',array('type' => 'text','name'=> 'EmpId','required'=>'required','placeholder'=>$u['User']['EmpId'],'title'=>' Employee ID is required.'));?>
        <?php   echo $this->Form->input('Employee Name',array('type' => 'text','name'=> 'EmpName','required'=>'required','placeholder'=>$u['User']['EmpName'],'title'=>' Employee name is required.'));?>
        <?php   echo $this->Form->input('Username',array('type' => 'text','name'=> 'username','required'=>'required','placeholder'=>$u['User']['username'],'title'=>'A username is required.'));?>
        <?php   echo $this->Form->input('Join Date',array('type' => 'text','name'=> 'join_date','required'=>'required','placeholder'=>$u['User']['join_date'],'title'=>'A Join Date is required.'));?>
        <?php   //echo $this->Form->input('Password',array('type' => 'text','name'=> 'password','required'=>'required','placeholder'=>$u['User']['password'],'title'=>'A password is required.'));?>
        <?php   echo $this->Form->input('Email',array('type' => 'text','name'=> 'email','required'=>'required','placeholder'=>$u['User']['email'],'title'=>'An email is required.'));?>
        <?php   //echo $this->Form->input('Profile Picture',array('type' => 'text','name'=> 'pro_picture','required'=>'required','placeholder'=>$u['User']['pro_picture'],'title'=>'Profile picture  is required.'));?>
        <?php    
            $arrCategory=array('admin'=>"Admin",'normal'=>"Normal",'pm' =>"Manager",'tl'=>"Lead",'CEO'=>"CEO");
            echo $this->Form->input('Role',array('options'=>$arrCategory, 'default' => $user,'name'=>'role','required'=>'required','placeholder'=>$u['User']['role'],'title'=>'User role is required.'));?> 
       <?php }?>
        <?php //echo $this->Form->input('RadioGroup', array('div' => true,'label' => true,'type' => 'radio','legend' => false,'options' => array(1 => 'Personal ', 2 => 'Company')));?>
    </fieldset>
<?php echo $this->Form->end(__('Update')); ?>
