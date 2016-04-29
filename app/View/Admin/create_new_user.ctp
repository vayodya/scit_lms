<?php 
echo $this->element("menu"); 

echo $this->Html->css(array('jquery-ui-1.10.3'));
?>

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

<?php echo $this->Form->create('users', array( 'enctype' => 'multipart/form-data')); ?>
    <fieldset>
        <legend><?php echo __('Create new user account'); ?></legend>
     
       <div id='w_f_body'>
     <?php //   echo $this->Form->input('Employee ID',array('type' => 'text','name'=> 'EmpId','required'=>'required','placeholder'=>'Employee ID','title'=>' Employee ID is required.','id' =>'empid'));?>
<!--        <font color ="red" id="err_EmpId">    <?php if (!empty($errors['EmpId'][0])) { 
        echo $errors['EmpId'][0];}?>
         
        
        </font>
        
        <font color ="red">    <?php if (!empty($errors['EmpId'][1])) { 
        echo $errors['EmpId'][1];}?>
         
        
        </font>-->
         <?php   echo $this->Form->input('Employee Name',array('type' => 'text','name'=> 'EmpName','required'=>'required','placeholder'=>'Employee Name','title'=>' Employee name is required.','id' =>'empname'));?>
        
        
         <?php   echo $this->Form->input('Username',array('type' => 'text','name'=> 'username','required'=>'required','placeholder'=>'Username','title'=>'A username is required.','id' => 'username'));?>
        <font color ="red" id="err_username"><?php if (!empty($errors['username'][0])) { 
         echo $errors['username'][0];
        }
        ?></font>
         <?php   echo $this->Form->input('Password',array('type' => 'password','name'=> 'password','required'=>'required','placeholder'=>'Password','title'=>'A password is required.','id' =>'password'));?>
        <font color ="red"><?php if (!empty($errors['password'][0])) { 
         echo $errors['password'][0];
        }
        ?></font>
 

        
         <?php   echo $this->Form->input('Email',array('type' => 'text','name'=> 'email','required'=>'required','placeholder'=>'Email','title'=>'An email is required.','id'=>'email'));?>
       <font color ="red"> <?php if (!empty($errors['email'][0])) { 
         echo $errors['email'][0];
        }
        ?></font>
        <div id="lab_1">Join Date</div>
        <?php echo $this->Form->input('',array('input type'=>"text",'id'=>"datepicker",'name' => 'join_date','dateFormat' =>'yy-mm-dd','required'=>'required','placeholder'=>'YYYY-MM-DD','title'=>'From Date is required.'));?>
        <div id="lab_2">Profile Picture</div>
        <div id="ali_pp"><?php echo $this->Form->input('pro_picture', array('type' => 'file','label' =>'')); ?></div>
            <?php    
      $arrCategory=array('admin'=>"Admin",'normal'=>"Normal",'pm' =>"Manager",'tl'=>"Lead",'CEO'=>"CEO");
	echo $this->Form->input('Role',array('options'=>$arrCategory, 'name'=>'role','required'=>'required','placeholder'=>'Role','title'=>'User role is required.','id'=>'role'));?> 
        <font color ="red"><?php if (!empty($errors['role'][0])) { 
         echo $errors['role'][0];
        }
        ?></font>
        <div id="but_al">
<?php //echo $this->Form->end(__('Create')); ?>
<?php echo $this->Form->end(array('label' => 'Create', 'id' => 'userAccountCreate')); ?>
</div></div>
 </fieldset>

<?php echo $this->Html->script(array('jquery-ui-1.10.3', 'lms')); ?>
<script>
    $(function() {
        $('#datepicker').datepicker({dateFormat: 'yy-mm-dd', 
            showOn: "button",
            buttonImage: "../images/iconCalendar.gif",
            buttonImageOnly: true
        });
    });
    
 </script>

