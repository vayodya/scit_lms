<?php echo $this->Html->css(array('jquery-ui-1.10.3', 'chosen', 'lms', 'jquery-ui-1.10.3')); ?>
    
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

<h2><?php __('Work_from_homes');?></h2>

<div class="filter">
    <?php
    echo $this->Form->create('Work_from_homes', array(
            'url' => array('action' => 'view_wfh_report')
            ));

    $employeeProjects = array (
        $allProjects
    );
    echo $this->Form->input('Project', 
        array(
            'id'        => 'projectList',       'type'              =>'select', 
            'default'   => $selectedProjects,   'name'              =>'projectName',
            'options'   => $employeeProjects,   'data-placeholder'  => 'Select Project',
            'multiple'  => 'multiple',          'class'             => 'chosen-select',
            'style'     => 'width: 373px;'
        ));

    $fullEmployeeList = array (
                        'all' => 'All Employees',
                         $allemployees
                    );

    $employeeDefault = 'all';
    if ($this->request->is('post')) {
        $employeeDefault = $this->request->data['employeeId'];
    }

    echo $this->Form->input('Employee', array('type'=>'select', 'default' => $employeeDefault, 'name'=>'employeeId','options'=>$fullEmployeeList));  

    $fromDateVal = ($this->request->data) ? $this->request->data['From_Date'] : '';
    ?>
    <div style="margin-bottom: -18px;">
        <div style="display: inline-block; width: 267px;">
            <?php
            echo $this->Form->input('From Date',array('readonly'=>'readonly',
                'input type'=>"text",'id'=>"datepicker",
                'name' => 'From_Date','dateFormat' =>'yy-mm-dd',
                'required'=>'required','placeholder'=>'YYYY-MM-DD',
                'title'=>'From Date is required.',
                'value' => $fromDateVal));
            ?>
        </div>
        <div class="calendar-input-reset-container">
            <img id="wfhFromDateReset" src="/images/icon-large-remove.png" class="calendar-input-reset"/>
        </div>
    </div>
    <div style="margin-bottom: -18px;">
        <div style="display: inline-block; width: 267px;">    
            <?php
            $toDateVal = ($this->request->data) ? $this->request->data['To_Date'] : '';
            echo $this->Form->input('To Date',array(
                'readonly'      =>'readonly',
                'input type'    =>"text",       'id'            => "datepicker2",
                'name'          => 'To_Date',   'dateFormat'    => 'yy-mm-dd',
                'required'      =>'required',   'placeholder'   => 'YYYY-MM-DD',
                'title'         =>'From Date is required.',
                'value'         => $toDateVal));
            ?>
        </div>
        <div class="calendar-input-reset-container">
            <img id="wfhToDateReset" src="/images/icon-large-remove.png" class="calendar-input-reset"/>
        </div>
    </div>
    <?php
    $categoryDefault = 'all';
    if ($this->request->is('post')) {
        $categoryDefault = $this->request->data['status'];
    }
    $arrCategory=array('all'=>"All",'pending'=>"Pending",'accepted' =>"Accepted",'rejected'=>"Rejected");
    echo $this->Form->input('Status',array('options'=>$arrCategory, 'default' => $categoryDefault,'name'=>'status','required'=>'required'));
            echo $this->Form->submit(__('Search', true), array('id' =>'search','div' => false));
            echo $this->Form->end();
    ?>
</div>	

<table id= "vlr">
    <tr>
        <th>Employee Name</th>
        <th style="width: 90px;">From Date</th>
        <th style="width: 90px;">To Date</th>
        <th>WFH Time</th>
        <th>Comment</th>
        <th>Status</th>
    </tr>
    <?php foreach ($recordsList as $key => $value) : ?>
    <tr>
        <td><?php echo $value['Work_from_homes']['EmpName']; ?></td>
        <td><?php echo $value['Work_from_homes']['From_Date']; ?></td>
        <td><?php echo $value['Work_from_homes']['To_Date']; ?></td>
        <td><?php echo $value['Work_from_homes']['wfh_Time']; ?></td>
        <td><?php echo $value['Work_from_homes']['wfh_comment']; ?></td>
        <td><?php echo $value['Work_from_homes']['wfh_states']; ?></td>        
    </tr>        
    <?php endforeach; ?>        
</table>

<?php echo $this->Html->script(array('jquery-ui-1.10.3', 'chosen.jquery.min', 'lms')); ?>
