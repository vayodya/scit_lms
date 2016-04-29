<?php echo $this->element("menu"); ?>
<?php
    echo $this->Html->css(array('jquery-ui-1.10.3', 'chosen', 'lms'));
    echo $this->Html->script(array('jquery-ui-1.10.3', 'chosen.jquery.min', 'lms'));
?>
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
    <font color  = "#205081" size="3"> <b><center>  Leave Requests</center> </b> </font>

    <center> 
    <?php
        echo $this->Form->create(array('style' => 'margin: auto;'));

        $options = array('rejected' => 'Rejected', 'accepted' => 'Accepted', 'pending' => 'Pending');
        echo $this->Form->input('Status', array(
            'id'        => 'leaveType',     'type'  => 'select',
            'options'   => $options,        'name'  => 'leavetype',
            'multiple'  => 'multiple',      'class' => 'chosen-select',
            'style'     => 'width: 300px;', 'label' => 'Leave Type',
            'data-placeholder' => 'Select Leave status'
        ));
        echo $this->Form->submit('Find');
        echo $this->Form->end();
    ?>
    </center>     
    <table class="tableceo1">
        <tr>

            <th style="width: 180px;">Employee Name</th>
            <?php //<th><?php echo $this->Paginator->sort('EmpName', 'Employee Name');?><?php //</th> ?>
    <!--        <th>Leave Type</th>
            <th>From Date</th>
            <th>To Date</th>-->

            <th>
                <?php echo $this->Paginator->sort('Leave_Type', 'Leave Type'); ?>
            </th>
            <th>
                <?php echo $this->Paginator->sort('From_Date', 'From Date'); ?>
            </th>
            <th>
                <?php echo $this->Paginator->sort('To_Date', 'To Date'); ?>
            </th>

            <th>Leave Time</th>
            <th>No: of Days</th>
            <th>Leave Comment</th>
            <th style="width: 160px;">Action</>
        </tr>

        <!-- Here is where we loop through our $posts array, printing out post info -->
    <?php if($line == 'true'){ $inc = 0;?>

        <?php foreach ($leave_request as $request): 
            $accept_id = $request['leave_record']['accept_id']?>

        <tr id="leave-request_<?php echo $request['leave_record']['id']; ?>" data-leave-id="<?php echo $request['leave_record']['id']; ?>">


           <td>  <?php echo $this->Html->link($request['leave_record']['EmpName'], array('action' => 'view_leave_record', $request['leave_record']['Eid'])); ?></td>
            <td>
                <?php 
               // echo $request['leave_record']['Leave_Type']; 
                if ($request['leave_record']['Leave_Type'] == 'annual') {
                    echo 'Annual';    
                } else if ($request['leave_record']['Leave_Type'] == 'sick') {
                    echo 'Sick';
                } else if ($request['leave_record']['Leave_Type'] == 'casual') {
                    echo 'Casual';
                } else if ($request['leave_record']['Leave_Type'] == 'nopay') {
                    echo 'No Pay';
                } else if ($request['leave_record']['Leave_Type'] == 'live') {
                    echo 'Lieu';
                }
                ?>
            </td>
            <td><?php echo $request['leave_record']['From_Date']; ?></td>
            <td><?php echo $request['leave_record']['To_Date']; ?></td>
            <td><?php echo $request['leave_record']['Leave_Time']; ?></td>
            <td id = "10px"><?php echo $request['leave_record']['real_days']; ?></td>
            <td><?php echo $request['leave_record']['Leave_comment']; 
                        ?></td>
            <td id ="xx21" class ="actions">
                <?php 
                if ($request['leave_record']['Leave_states'] == 'accepted'){
                    $dv = "as".$inc;
                    $pass = $inc.".".$accept_id;?>

                <div id ="act1" onmouseover = "actionDetailsIn(<?php echo $pass;?>)" onmouseout ="actionDetailsOut(<?php echo $inc; ?>)"><?php 			echo $request['leave_record']['Leave_states'];?>
                <div id ="<?php echo $dv; ?>"></div></div>



                 <?php   //echo $request['leave_record']['accept_id'];
                }  else {?>
                 <div id ="rej1" style="margin-top: 2px;"> <?php   echo $this->Html->link('Accept', array('action' => 'accept',$request['leave_record']['id']));
                }?></div>

                <?php 
                 if ($request['leave_record']['Leave_states'] == 'rejected'){
                     $dv = "as".$inc;
                    $pass = $inc.".".$accept_id;?>

                 <div id ="act2" onmouseover = "actionDetailsIn1(<?php echo $pass;?>)" onmouseout ="actionDetailsOut1(<?php echo $inc; ?>)">
                    <?php   echo $request['leave_record']['Leave_states'];?>
                 <div id ="<?php echo $dv; ?>" class ="as2"></div</div>


                <?php    //echo $request['leave_record']['accept_id'];
                }  else {?>
                 <div id ="rej2" style="margin-top: 1px;"><?php     echo $this->Html->link('Reject', 
                            array('action' => 'reject', $request['leave_record']['id']), 
                            array('onclick' => 'return false;', 'id' => 'confirmLeveReject_'.$request['leave_record']['id'])); 
               }?></div>
            </td>
        </tr>
        <?php $inc++;
            endforeach; ?>
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

<div id="leave-reject-form" title="Leave Rejection" class="reject-form">
<!--  <p class="validateTips">All form fields are required.</p>-->

    <form style="font-size: 85%" action="/admins/reject" method="post">
        <input type="hidden" name="leave-request-id" value=""/>
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="" class="text ui-widget-content ui-corner-all input-disabled" disabled="">
        <label for="from-date" class="from-date-label">Leave Type</label>
        <input type="text" name="leave-type" value="" class="text ui-widget-content ui-corner-all from-date input-disabled" disabled="">
        <label for="to-date" class="to-date-label">Leave Time</label>
        <input type="text" name="leave-time" value="" class="text ui-widget-content ui-corner-all to-date input-disabled" disabled="">
        <label for="from-date" class="from-date-label">From Date</label>
        <input type="text" name="from-date" value="" class="text ui-widget-content ui-corner-all from-date input-disabled" disabled="">
        <label for="to-date" class="to-date-label">To Date</label>
        <input type="text" name="to-date" value="" class="text ui-widget-content ui-corner-all to-date input-disabled" disabled="">
        <label for="name" class="comment-label">Comment</label>
        <textarea class="comment input-disabled" disabled="" name="comment" style="resize: none;"></textarea>
        <hr style="margin: 14px 8px 14px 8px;"/>
        <label for="name" class="reject-comment-label">Reason to reject</label>
        <textarea rows="3" class="reject-comment" name="reject-comment" style="resize: none;"></textarea>
      
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
  </form>
</div>

<div id="leave-reject-confirm-dialog" title="Confirm Leave Rejection">
    <p style="font-size: 90%; margin: 10px 0 10px 0;"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to reject the leave?</p>
</div>

<script>

    function actionDetailsIn(id){
	var str1= id;
	var array = str1.toString().split('.');
			//alert(array[0]);
        if(array[1] == 2){
            var statement = "Accepted By"; 
            statement += "</br>";
            statement += "Manager";
        }

        if(array[1] == 3){
            var statement = "Accepted By"; 
            statement += "</br>";
            statement += "Lead";
        }

        if(array[1] == 6){
            var statement = "Accepted By"; 
            statement += "</br>";
            statement += "CEO";
        }

        var str ="as";
        var di = str.concat(array[0]);
        document.getElementById(di).className="asx";
        document.getElementById(di).innerHTML=statement;
    }
        
    function actionDetailsOut(id){
        var str ="as";
        var di = str.concat(id);
        document.getElementById(di).className="as1";
    }
        
    function actionDetailsIn1(id){
	var str1= id;
	var array = str1.toString().split('.');
			//alert(array[0]);
        if(array[1] == 2){
            var statement = "Rejected By"; 
            statement += "</br>";
            statement += "Manager";
        }

        if(array[1] == 3){
            var statement = "Rejected By"; 
            statement += "</br>";
            statement += "Lead";
        }

        if(array[1] == 6){
            var statement = "Rejected By"; 
            statement += "</br>";
            statement += "CEO";
        }

        var str ="as";
        var di = str.concat(array[0]);
        document.getElementById(di).className="asx2";
        document.getElementById(di).innerHTML=statement;
    }

    function actionDetailsOut1(id){
        var str ="as";
        var di = str.concat(id);
        document.getElementById(di).className="as1";
    }
    
    var leaveStatusArrSelected = <?php echo json_encode($leaveStatusArr); ?>;

    $(function() {
        $.each(leaveStatusArrSelected, function( index, value ) {
            $('#leaveType option[value="' + value + '"]').prop('selected', true);
        });

        //handling leave request list
        $('#leaveType').chosen({});
    });     
    
</script>
