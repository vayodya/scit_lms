<div style="padding-bottom: 150px;">
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
</br>
</br>
<div id ="leave_record">
<h3><center> <?php echo 'Employee Leave Record - '.date("Y") ?></center></h2>
</br>


<h4><?php echo 'Name :'.$name?></h4>
</br>

<h4><?php echo 'Leave Balance for Current Year :'; ?>
    </br>
    
    <h4 style="padding-left: 35px;">Annual Leaves : <?php echo $a2;?></h4>
    <h4 style="padding-left: 35px;">Casual Leaves : <?php echo $a3;?></h4>
    <h4 style="padding-left: 35px;">Liue Leaves : <?php echo $a4;?></h4>
</div>
</br>
</br></br></br></br></br></br></br></br></br></br></br>
<h3 style="margin-left: 184px;"><font color  = #2C6877> <b> Leave Utilization </b> </font></h3>
        
<table id="leave_record">
    <tr>
        <th>Leave Id</th>
        
        <th>Leave Type</th>
        <th>From Date</th>
        <th>To Date</th>
        <th>Leave Time</th>
        <th>No: of Days</th>
        <th>Leave Comment</th>
        
    </tr>

    <!-- Here is where we loop through our $posts array, printing out post info -->

    <?php foreach ($used_leave as $used_leaves): ?>
    <tr>
        <td><?php echo $used_leaves['leave_record']['id']; ?></td>
       
       
        <td><?php echo $used_leaves['leave_record']['Leave_Type']; ?></td>
        <td><?php echo $used_leaves['leave_record']['From_Date']; ?></td>
        <td><?php echo $used_leaves['leave_record']['To_Date']; ?></td>
        <td><?php echo $used_leaves['leave_record']['Leave_Time']; ?></td>
        <td><?php echo $used_leaves['leave_record']['real_days']; ?></td>
        
        <td><?php echo $used_leaves['leave_record']['Leave_comment']; ?></td>
        
    </tr>
    <?php endforeach; ?>
   
</table>     
</div>

<script>
    $(document).ready(function() {
        // Set company logo. 
        setCompanyLogo();

        // Set body div height.
        var leaveRecordTableTBodyHeight = $('#leave_record tbody').height();
        leaveRecordTableTBodyHeight = leaveRecordTableTBodyHeight + 330;

        $('#content').height(leaveRecordTableTBodyHeight);
    });


    function setCompanyLogo() {
        var logoPath = $('#company-logo').attr('src');
        logoPath = '../' + logoPath;
        $('#company-logo').prop('src', logoPath);
    }
</script>