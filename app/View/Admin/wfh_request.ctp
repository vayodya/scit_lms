<style>
    .wfhAction {
        display: inline-block;
    }
    
    .wfhActionRejected {
        width: 70px;
        background-color: red;
    }
    
    .wfhActionAccepted {
        width: 70px;
        background-color: #64A9E1;
    }
</style>

<?php echo $this->element("menu"); ?>

<?php echo $this->Html->css(array('jquery-ui-1.10.3', 'chosen', 'lms')); ?>

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
    </body>
    </br></br>
    <font color  = "#205081" size="3"><b> <center> Work From Home Requests </center></b> </font>
    <table class="tableceo1">
        <tr>

            <th>Employee Name</th>

            <th>From Date</th>
            <th>To Date</th>
            <th>WFH Time</th>
            <th>No: of Days</th>
            <th>WFH Comment</th>
            <th>Action</>
        </tr>

        <!-- Here is where we loop through our $posts array, printing out post info -->
    <?php if($line == 'true'){?>
        <?php foreach ($wfh_request as $request): ?>
        <tr id="wfh-request_<?php echo $request['Work_from_homes']['id']; ?>">
            <td><?php echo $request['Work_from_homes']['EmpName']; ?></td>

            <td><?php echo $request['Work_from_homes']['From_Date']; ?></td>
            <td><?php echo $request['Work_from_homes']['To_Date']; ?></td>
            <td><?php echo LeaveUtil::getLeaveTimeName($request['Work_from_homes']['wfh_Time']); ?></td>
            <td><?php echo $request['Work_from_homes']['real_days']; ?></td>
            <td><?php echo $request['Work_from_homes']['wfh_comment']; ?></td>
            <td class="actions">
                <?php
                $confirmWfhRejectId = "confirmWfhReject_".$request['Work_from_homes']['id'];

                $acceptLink = $this->Html->link('Accept', array('action' => 'wfh_accept', $request['Work_from_homes']['id']),
                        array('class' => 'wfhAction'));
                $rejectLink = $this->Html->link('Reject', array('action' => 'wfh_reject', $request['Work_from_homes']['id']),
                        array('class' => 'wfhAction', 'id' => $confirmWfhRejectId, 'onclick' => 'return false;', 'data-wfhid' => $request['Work_from_homes']['id']));

                if ($request['Work_from_homes']['wfh_states'] === 'accepted') {
                    $acceptLink = $this->Html->div('wfhAction wfhActionAccepted', 'Accepted');
                } elseif ($request['Work_from_homes']['wfh_states'] === 'rejected') {
                    $rejectLink = $this->Html->div('wfhAction wfhActionRejected', 'Rejected');                
                } else {
                    // Do nothing.
                }

                echo $acceptLink;
                echo $rejectLink;
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
       <?php }?>
    </table>     
    <div class="paging">    
            <?php
               echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));
               echo $this->Paginator->numbers();
               echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));
            ?>
    </div> 
</div>

<div id="wfh-reject-form" title="Work From Home Rejection" class="reject-form">
<!--  <p class="validateTips">All form fields are required.</p>-->

    <form style="font-size: 85%" action="/admins/wfh_reject" method="post">
        <input type="hidden" name="wfh-request-id" value=""/>
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="" class="text ui-widget-content ui-corner-all input-disabled" disabled="">
        <label for="to-date" class="to-date-label" style="margin-left: 10px;">WFH Time</label>
        <input type="text" name="wfh-time" value="" class="text ui-widget-content ui-corner-all to-date input-disabled" disabled="">
        <br>
        <label for="from-date" class="from-date-label">From Date</label>
        <input type="text" name="from-date" value="" class="text ui-widget-content ui-corner-all from-date input-disabled" disabled="">
        <label for="to-date" class="to-date-label">To Date</label>
        <input type="text" name="to-date" value="" class="text ui-widget-content ui-corner-all to-date input-disabled" disabled="">
        <label for="comment" class="comment-label">Comment</label>
        <textarea class="comment input-disabled" disabled="" name="comment" style="resize: none;"></textarea>
        <hr style="margin: 14px 8px 14px 8px;"/>
        <label class="reject-comment-label">Reason to reject</label>
        <textarea rows="3" class="reject-comment" name="reject-comment" style="resize: none;"></textarea>
      
        <!-- Allow form submission with keyboard without duplicating the dialog button -->
        <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
  </form>
</div>

<div id="wfh-reject-confirm-dialog" title="Confirm Work From Home Rejection">
    <p style="font-size: 90%; margin: 10px 0 10px 0;"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to reject the work from home?</p>
</div>

<?php echo $this->Html->script(array('jquery-ui-1.10.3', 'chosen.jquery.min', 'lms')); ?>