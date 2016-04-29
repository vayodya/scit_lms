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
<center><b><font color ="#205081"> Leave Report <font></b></center>
<table id="leave_report_t">
    <tr>
        <th>Leave Type</th>
<!--        <th><?php //echo $this->Paginator->sort('Leave_Time','Leave Time');?></th>
        <th><?php //echo $this->Paginator->sort('From_Date');?></th>
        <th><?php //echo $this->Paginator->sort('To_Date');?></th>-->
        <th>Leave Time</th>
        <th>From Date</th>
        <th>To Date</th>
        <th>No: of Days</th>
        <th>Comments</th>
        <th>Status</th>
        <th>Action</th>
        
    </tr>

    <!-- Here is where we loop through our $posts array, printing out post info -->
<div class="filter">
    <?php foreach ($leave_records as $post): ?>

    <tr>
        <td>
            <?php 
            // echo $post['leave_record']['Leave_Type']; 
            if ($post['leave_record']['Leave_Type'] == 'annual') {
                echo 'Annual';    
            } else if ($post['leave_record']['Leave_Type'] == 'sick') {
                echo 'Sick';
            } else if ($post['leave_record']['Leave_Type'] == 'casual') {
                echo 'Casual';
            } else if ($post['leave_record']['Leave_Type'] == 'nopay') {
                echo 'No Pay';
            } else if ($post['leave_record']['Leave_Type'] == 'live') {
                echo 'Lieu';
            }            
            ?>
        </td>
        <td><?php echo $post['leave_record']['Leave_Time']?></td>
        <td><?php echo $post['leave_record']['From_Date']; ?></td>
        <td><?php echo $post['leave_record']['To_Date']; ?></td>
        <td><center><?php echo $post['leave_record']['real_days']; ?></center></td>
        <td><?php echo $post['leave_record']['Leave_comment']; ?></td>
        <td><?php echo $post['leave_record']['Leave_states']; ?></td>
        
        <td><center>
            
            <?php 
            $delete_img = $this->Html->image('../images/images_del.jpg', array('alt' => 'Delete', 'title'=>'Cancel Leave'));  
            $e_p=false;$e_a=false;$e_r=false;
            
            if(date("Y-m-d")> $post['leave_record']['From_Date']){ //allowing to edit request for today and future days.
                $edit =  false;
            }
            else if($post['leave_record']['Leave_states'] == 'pending'){                
                $edit = true;$e_p=true;
            }elseif($post['leave_record']['Leave_states'] == 'accepted'){
                $edit = true;$e_a=true;
            }else{
                $edit = true;$e_r=true;
            }
             
            if($edit){
                if($e_p){echo $this->Form->postLink($delete_img,array('action' => 'delete',$post['leave_record']['id']),array('escape' => false,'confirm' => 'Are you sure? You want to cancel your PENDING leave?'));
                
                }   
                if($e_a){echo $this->Form->postLink($delete_img,array('action' => 'accept_leave_cancel', $post['leave_record']['id']),array('escape' => false,'confirm' => 'Are you sure? You want to cancel your ACCEPTED leave?'));}
                if($e_r){echo 'Already Canceled';} 
            }else{
                echo "Can't Cancel";/*echo $this->Html->link($delete_img, array($post['leave_record']['id']),array('escape' => false,'confirm' => "Can't cancel, because date expired or already commented.."));*/                
            }?>
        </center></td>
        
    </tr>
    <?php endforeach; ?>
    <?php unset($post);?>
</table>

<?php /*
if(count($leave_records)==0){}else{ 
//echo $this->Html->link('Download',array('controller' => 'Contacts','action' => 'download'), array('target'=>'_blank'));
$download_img = $this->Html->image('../images/downloadcsv.png',array('alt' => 'Export Report', 'title'=>'Export Report'));
echo 'For Download Click this '.$this->Html->link($download_img, array('controller' => 'Contacts','action' => 'download'), array('escape' => false));
}*/
?>

</div>
<div class="paging">
    
    <?php
if(count($leave_records)==0){}else{ 
//echo $this->Html->link('Download',array('controller' => 'Contacts','action' => 'download'), array('target'=>'_blank'));
$download_img = $this->Html->image('../images/downloadcsv.png',array('alt' => 'Export Report', 'title'=>'Export Report'));?>
<font color ="#205081"> <?php echo 'For Download click this '.$this->Html->link($download_img, array('controller' => 'Contacts','action' => 'download'), array('escape' => false));?> </font> <?php
}
?>
		<?php //echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 	<?php //echo $this->Paginator->numbers();?>
 
		<?php //echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>

    <script>
    function abcd()
    {
      alert('ok');exit;
    }
    </script>
    <script type="text/javascript">
        function OnClickButton () {
            alert ("You clicked on the button!");
        }
    </script>
</head>

