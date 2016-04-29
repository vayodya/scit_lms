<?php 
echo $this->element("menu"); 

echo $this->Html->css(array('jquery-ui-1.10.3'));

?>
<?php echo $this->Html->script(array('jquery-ui-1.10.3', 'chosen.jquery.min', 'lms')); ?>

<div>
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

    <center><b><font color ="#205081"> WFH Report <font></b></center>
    <table id="wfhTable">
        <tr>
            <th>WFH Time</th>
            <th>From Date</th>
            <th>To_Date</th>
            <th>No: of Days</th>
            <th>Comments</th>
            <th>Status</th>
            <th>Action</th>
        </tr>   
        <?php foreach ($Work_from_home as $post): ?>
        <?php 
        $wfhStatus = $post['Work_from_homes']['wfh_states'];
        switch ($wfhStatus) {
            case 'pending':
                $wfhStatus = "Pending";
                break;
            case 'rejected':
                $wfhStatus = "Declined";
                break;
            case 'accepted':
                $wfhStatus = "Accepted";
                break;
            default:
                break;
        }
        ?>
        <tr>
            <td><?php echo LeaveUtil::getLeaveTimeName($post['Work_from_homes']['wfh_Time']); ?></td>
            <td><?php echo $post['Work_from_homes']['From_Date']; ?></td>
            <td><?php echo $post['Work_from_homes']['To_Date']; ?></td>
            <td><center><?php echo $post['Work_from_homes']['real_days']; ?></center></td>
            <td><?php echo $post['Work_from_homes']['wfh_comment']; ?></td>
            <td><?php echo $wfhStatus; ?></td>
            <td><center>            
                <?php if (date("Y-m-d") < $post['Work_from_homes']['From_Date'] ): ?>
                    <a id="cancelWfhRequest_<?php echo $post['Work_from_homes']['id']; ?>"  href="/WorkFromHomes/delete/<?php echo $post['Work_from_homes']['id']; ?>" onclick="return false;" >
                        <img title="Cancel work from home request" src="/images/images_del.jpg">
                    </a>            
                <?php else: ?>
                    <p>No Cancellation</p>
                <?php endif; ?>
                </center>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php unset($post); ?>
    </table>
    <div class="paging">
       <?php if(count($Work_from_home)==0){}else{ 
            //echo $this->Html->link('Download',array('controller' => 'Contacts','action' => 'download'), array('target'=>'_blank'));
            $download_img = $this->Html->image('../images/downloadcsv.png',array('alt' => 'Export Report', 'title'=>'Export Report'));?>
            <font color ="#205081"> <?php echo 'For Download click this '.$this->Html->link($download_img, array('controller' => 'Contacts','action' => 'download_wfh'), array('escape' => false));?> </font> <?php
            }
        ?>    
    </div>
</div>

<div id="wfh-cancel-confirm-dialog" title="Confirm WFH Cancellation">
    <p style="font-size: 90%; margin: 10px 0 10px 0;">
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
        Are you sure you want to cancel the work from home request?
    </p>
</div>


