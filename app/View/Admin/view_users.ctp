<?php echo $this->element("menu"); ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
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

<center><b><font color ="#205081"> Employees <font></b></center>

<form action="" id="fixed1"> 

<?php echo $this->Form->input('',array('type'=>'text','id' =>'txt1','placeholder'=> '             Search for people','onkeyup'=>'showHint(this.value)'));?>
</form>
<div id="txtHint"></div> 
<table id ="user">
    <tr>
        <?php // <th>Employee Id</th> ?>
        <th><?php echo $this->Paginator->sort('EmpId', 'ID');?></th>
        <?php // <th>Employee name</th>?>
        <th><?php echo $this->Paginator->sort('EmpName', 'Employee name');?></th>
        <?php //<th>Username</th> ?>
        <th><?php echo $this->Paginator->sort('username', 'Username');?></th>
        <?php //<th>Email</th> ?>
        <th><?php echo $this->Paginator->sort('email', 'Email');?></th>
        <?php //<th>Role</th> ?>
        <th><?php echo $this->Paginator->sort('join_date', 'Join date');?></th>
        <th><?php echo $this->Paginator->sort('role', 'Role');?></th>
        <th>Action</>
    </tr>

    <!-- Here is where we loop through our $posts array, printing out post info -->

    <?php foreach ($users as $user): ?>
    <tr>
        <?php // <td><?php echo $user['User']['EmpId']; </td>?>
        <td><?php echo $this->Html->link($user['User']['EmpId'], array('action' => 'admin_edit', $user['User']['id'],$user['User']['role']),array('title'=>'For edit this user')); ?></td>
        
        <td><?php echo $user['User']['EmpName']; ?></td>
        
        <td><?php echo $user['User']['username']; ?></td>
        
        <td><?php echo $user['User']['email']; ?></td>
        <td><?php echo $user['User']['join_date']; ?></td>
        <td><?php 
        if($user['User']['role']=='admin'){echo 'Admin';}
        elseif ($user['User']['role']=='pm'){echo 'Manager';}
        elseif ($user['User']['role']=='tl'){echo 'Lead';}
        elseif ($user['User']['role']=='normal'){echo 'Normal';}
        else{echo 'CEO';}
        //echo $user['User']['role']; ?></td>
        
        
        <td><center>
            
            
           <?php  $delete_img = $this->Html->image('../images/delete2.png', array('alt' => 'Delete', 'title'=>'Delete User'));
            echo $this->Form->postLink($delete_img, array('action' => 'delete', $user['User']['id']), array('escape' => false), sprintf(__('Are you sure you want to delete ?', true), $user['User']['id']));?>
    </center></td>
    </tr>
    <?php endforeach; ?>
   
</table>     
<div class="dwn">
<?php /*
if(count($users)==0){}else{
//echo $this->Html->link('Download',array('controller' => 'Contacts','action' => 'download'), array('target'=>'_blank'));
$download_img = $this->Html->image('../images/downloadcsv.png',array('alt' => 'Export Report', 'title'=>'Export Report'));
echo $this->Html->link($download_img, array('controller' => 'Contacts','action' => 'download_view_user'), array('escape' => false));
} */
?>
</div>
<div class="paging" align="center">
    <?php 
if(count($users)==0){}else{
//echo $this->Html->link('Download',array('controller' => 'Contacts','action' => 'download'), array('target'=>'_blank'));
$download_img = $this->Html->image('../images/downloadcsv.png',array('alt' => 'Export Report', 'title'=>'Export Report'));?>
<font color ="#205081"> <?php echo 'For Download click this '.$this->Html->link($download_img, array('controller' => 'Contacts','action' => 'download_view_user'), array('escape' => false));?> </font> <?php
} 
?>
    
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>

<script>
    function showHint(str){
        
//alert(document.location.hostname);
      
         $.ajax({
      url : 'http://lms.softcodeit.net/___softcodeit/admins/getAjaxUsers',
      type: 'POST',
	 data:{name : str },
      success : function(response){
          //var names = response.split('@@@');
          document.getElementById('txtHint').innerHTML = response ;
          
      }
  });

    }

</script>

