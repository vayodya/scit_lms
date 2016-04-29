<?php
    echo $this->Html->css(array('jquery-ui-1.10.3', 'chosen', 'jquery-ui-1.10.3'));
    echo $this->Html->script(array('jquery-ui-1.10.3', 'chosen.jquery.min'));
?>

<script>
    $(function(){
      $('#employeeNames').chosen({}); 
      $('#projectNames').chosen({});
//      $('#employeeNames2').chosen({});
//      $('#employeeNames2').multipleSelect();
    })
</script>

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
echo $this->Form->create('admins',array('action' => 'add_employee'));?>

 <fieldset>
        <legend><?php echo __('Assign employees to projects'); ?></legend>
        <div id='w_f_body'>
<?php
    
    echo $this->Form->input('Project Names', array(
        'id'            => 'projectNames',          'type'              => 'select',
        'default'       => $pa,                     'name'              => 'projectName',
        'options'       => $pa,                     'data-placeholder'  => 'Select Project/Projects',
        'multiple'      => 'multiple',              'class'             => 'chosen-select',
        'required'      => TRUE,
    ));

    echo $this->Form->input('Names', array(
        'id'            => 'employeeNames',         'type'              => 'select',
        'default'       => $employeeList,           'name'              => 'employeeName',
        'options'       => $employeeList,           'data-placeholder'  => 'Select Employee/Employees',
        'multiple'      => 'multiple',              'class'             => 'chosen-select',
        'required'      => TRUE,
    ));
    echo $this->Form->submit('Assign');
?>
</div>
</fieldset>


