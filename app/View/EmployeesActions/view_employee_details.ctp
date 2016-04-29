<meta charset="utf-8" />
<?php
echo $this->Html->css(array('jquery-ui-1.10.3'));

echo $this->Html->script(array('jquery-ui-1.10.3'));
?>
<style>
    .toggler {
        margin-left: 10px;
        width: auto;
        height: auto;
    }
    #button {
        padding: .5em 3em;
        text-decoration: none;
        margin-top:25px;
        margin-left:36px;
        width: 200px;
    }
    #effect {
        position: fixed;
        width: 263px; 
        height: auto;
        padding: 0.4em;
        margin-left:33px;
        margin-top:75px;
        background-color: #BEBEBE;
    }
    #effect h3 {
        margin: 0;
        padding: 0.4em;
        text-align: center;
    }
</style>
<!--	<link rel="stylesheet" href="css/style.css">-->
        <?php
            echo $this->Html->css(array('lms.css'));
            echo $this->Html->script(array('jquery-ui-1.10.3', 'chosen.jquery.min', 'lms'));
        ?>
<?php echo $this->element("menu"); ?>
<div id="login">
    <div id="semilog">
        <?php echo '&nbsp;' . $loguser . ' ' . '&nbsp;'; ?>
    </div>
    <?php
    //echo $this->Html->link('Sign Out ', array('controller' => 'Users','action' => 'logout'));
    $de_img = $this->Html->image('../images/Logout-Icon.jpg', array('alt' => 'Sign Out', 'title' => 'Sign Out'));
    echo $this->Html->link($de_img, array('controller' => 'Users', 'action' => 'logout'), array('escape' => false));
    $delete_img = $this->Html->image('../images/User_Edit.jpg', array('alt' => 'Delete', 'title' => 'Edit Profile'));
    echo $this->Html->link($delete_img, array('action' => 'edit', 'controller' => 'Users', 'action' => 'profileedit'), array('escape' => false)) . '&nbsp;';
    $notify_img = $this->Html->image('../images/imagesnoti.png', array('alt' => 'Notify', 'title' => 'Notification Setting'));
    echo $this->Html->link($notify_img, array('action' => 'edit', 'controller' => 'Users', 'action' => 'email_notify'), array('escape' => false));
    ?>
</div>

<?php if (isset($_SESSION['rand'])) : ?>
<div class="alert alert-block" >
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php
        echo $_SESSION['rand'];
        unset($_SESSION['rand']);
    ?>
</div>
<?php endif; ?>

<?php if (isset($errorMessage)) : ?>
<div class="alert alert-block" >
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <font color="red">
        <?php echo $errorMessage; ?>
    </font>
</div>
<?php endif; ?>
<table>
    <tr>
        <th>Employee ID</th>
        <th>Employee Name</th>
        <th>Employee Role</th>
        <th>Action</th>
    </tr>
    <tr>
        <?php foreach($user_detail as $user):?>
        <td><?php echo $user['User']['EmpId']; ?> </td>
        <td><?php echo $user['User']['EmpName']; ?> </td>
        <td>
        <div class = 'dropdown-role'>
        <?php

             $userRoleList = array('CEO'=>'CEO','pm'=>'Project Manager','admin'=>'Admin','normal'=>'Normal','tl'=>'Lead');
             echo $this->Form->input('', array(
               'type' => 'select',
               'id' => 'user-role_'.$user['User']['EmpId'],
               'class' => 'testclass',
               'options' => $userRoleList,
               'default' => $user['User']['role'],
               'disabled' => 'disabled',
               'data-empid' => $user['User']['EmpId']
                ));
               ?>

        </div>
        <div style="" class=employee-role>
        <a href="#" class="user-edit" data-empid="<?= $user['User']['EmpId']; ?>" id="user-edit_<?= $user['User']['EmpId']; ?>">
        <span class="ui-icon ui-icon-pencil"></span>
              </a>
              </div>


        <div class="hidden">
        <a href="#" onclick="return false;" data-empid="<?= $user['User']['EmpId']; ?>" uid="<?= $user['User']['EmpId']; ?>" userValue="<?= $user['User']['role']; ?>" id="user-save_<?= $user['User']['EmpId']; ?>" data-emprole="<?= $user['User']['role']; ?>" class="user-save" >
        <span class="ui-icon ui-icon-check"></span>
        </a>
        <a href="#" onclick="return false;" data-empid="<?= $user['User']['EmpId']; ?>" data-emprole="<?= $user['User']['role']; ?>" id="user-cancel_<?= $user['User']['EmpId']; ?>" class="user-cancel" >
        <span class="ui-icon ui-icon-close"></span>
        </a>
        </div>
         </td>
         <td>
          <div class="deleteEmployee">
                 <a href="#"  class="user-delete" data-deleteid="<?= $user['User']['EmpId']; ?>" id="user-delete_<?= $user['User']['EmpId']; ?>">
                 <span><img src="/img/delete_icon.png" class="deleteImg"/></span>
                 </a>
                 </div>
                 </td>
    </tr>
<?php endforeach; ?>
</table>



