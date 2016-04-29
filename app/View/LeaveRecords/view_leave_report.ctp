<?php 
echo $this->Html->css(array('jquery-ui-1.10.3', 'chosen', 'lms', 'jquery-ui-1.10.3')); 

echo $this->element("menu");
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

    <h2><?php __('leave_record');?></h2>
    <div class="filter">
        <?php echo $this->Form->create('leave_record', array(
                'url' => array('action' => 'view_leave_report'), 'style' => 'height: auto;')); ?>
            <ul id='ser_ul'>
                <li>
                    <?php 
                    $employeeProjects = array ($allProjects);
                    echo $this->Form->input('Project', 
                        array(
                            'id'        => 'projectList',       'type'              =>'select', 
                            'default'   => $selectedProjects,   'name'              =>'projectName',
                            'options'   => $employeeProjects,   'data-placeholder'  => 'Select Project',
                            'multiple'  => 'multiple',          'class'             => 'chosen-select',
                            'style'     => 'width: 373px;'
                        )); 
                    ?>
                </li>   
                <li>
                    <?php
                        $fullEmployeeList = array (
                                'all' => 'All Employees',
                                 $allemployees
                            );

                        $employeeDefault = 'all';
                        if ($this->request->is('post')) {
                            $employeeDefault = $this->request->data['employeeId'];
                        }                            
                        echo $this->Form->input('Employee', array('type'=>'select', 'default' => $employeeDefault, 'name'=>'employeeId','options'=>$fullEmployeeList)); //'id'=>'employeeList',
                    ?>
                </li>
                <li>
                    <div style="margin-bottom: -18px;">
                        <div style="display: inline-block; width: 267px;"> 
                            <?php
                                $fromDateVal = ($this->request->data) ? $this->request->data['From_Date'] : '';
                                echo $this->Form->input('From Date',array('readonly'=>'readonly',
                                    'input type'=>"text",'id'=>"datepicker",'name' => 'From_Date',
                                    'dateFormat' =>'yy-mm-dd','required'=>'required',
                                    'placeholder'=>'YYYY-MM-DD','title'=>'From Date is required.',
                                    'value' => $fromDateVal)); 
                            ?>
                        </div>
                        <div class="calendar-input-reset-container">
                            <img id="wfhFromDateReset" src="/images/icon-large-remove.png" class="calendar-input-reset"/>
                        </div>
                    </div>
                </li>
                <li>
                    <div style="margin-bottom: -18px;">
                        <div style="display: inline-block; width: 267px;"> 
                            <?php 
                                $toDateVal = ($this->request->data) ? $this->request->data['To_Date'] : '';
                                echo $this->Form->input('To Date',array('readonly'=>'readonly','input type'=>"text",
                                    'id'=>"datepicker2",'name' => 'To_Date','dateFormat' =>'yy-mm-dd',
                                    'required'=>'required','placeholder'=>'YYYY-MM-DD','title'=>'From Date is required.', 
                                    'value' => $toDateVal)); 
                            ?>
                        </div>
                        <div class="calendar-input-reset-container">
                            <img id="wfhToDateReset" src="/images/icon-large-remove.png" class="calendar-input-reset"/>
                        </div>
                    </div>
                </li>
                <li>
                    <?php  
                    $arrCategory=array('all'=>"All",'pending'=>"Pending",'accepted' =>"Accepted",'rejected'=>"Rejected");
                    echo $this->Form->input('Status',array('options'=>$arrCategory, 'default' => 'all','name'=>'status','required'=>'required')); 
                    ?>
                </li>
                <li>
                    <?php echo $this->Form->submit(__('Search', true), array('id' =>'search','div' => false));?>
                </li>
            </ul>
        <?php echo $this->Form->end(); ?>
    </div>	

    <table id ="vll" >
        <tr>
            <th><?php // echo $this->Paginator->sort('EmpName','Employee Name');?> Employee Name</th>
            <th><?php // echo 'Leave Type';?> Leave Type</th>
            <th><?php // echo $this->Paginator->sort('From_Date');?> From Date</th>
            <th><?php // echo $this->Paginator->sort('To_Date');?> To_Date</th>
            <th><?php // echo $this->Paginator->sort('Leave_Time','Leave Time');?> Leave Time</th>
            <th><?php // echo 'Leave Comment';?> Comment</th>
            <th><?php // echo 'Leave Status';?> Status</th>
        </tr>
        <?php foreach ($recordsList as $key => $value) : ?>
        <tr>
            <td><?php echo $value['leave_record']['EmpName']; ?></td>
            <td><?php echo $value['leave_record']['Leave_Type']; ?></td>
            <td><?php echo $value['leave_record']['From_Date']; ?></td>
            <td><?php echo $value['leave_record']['To_Date']; ?></td>
            <td><?php echo $value['leave_record']['Leave_Time']; ?></td>
            <td><?php echo $value['leave_record']['Leave_comment']; ?></td>
            <td><?php echo $value['leave_record']['Leave_states']; ?></td>        
        </tr>        
        <?php endforeach; ?>             
    </table>
</div>

<?php echo $this->Html->script(array('jquery-ui-1.10.3', 'chosen.jquery.min', 'lms')); ?>

<script>
    $(function() {
        $('#projectList').chosen({});
        
        $('#projectList').on('change', function(evt, params) {
            var projectSelectedArr = $('#projectList').val();
            $.ajax({
                url : base_url + 'users/getUsersByProjects',
                type: 'GET',
                dataType: 'json',
                data: {
                    projectSelected: projectSelectedArr
                },
                success : function(response) {
                    $('#leave_recordEmployee').empty();
                    
                    if (response.length){
                        var optionStr = '<option value="all" selected="selected">All Employees</option>';
                    }else{
                        var optionStr = '<option value=0 selected="selected">No Employees</option>';
                    }
//                    var optionStr = '<option value="all" selected="selected">All Employees</option>';
                    $('#leave_recordEmployee').append(optionStr);
                    
                    $.each(response, function(index, value) {
                        optionStr = '<option value="'
                            + value.User.EmpId + '">'         //added by chamith 
                            + value.User.Surname_RestName + '</option>';
                        $('#leave_recordEmployee').append(optionStr);
                    });
                },
                error : function(jqXHR, textStatus, errorThrown ) {
                    alert("Could not get the employees list");
                }
            });            
        });
    });
</script>

  