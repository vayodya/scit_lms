<meta charset="utf-8" />
<?php
echo $this->Html->css(array('jquery-ui-1.10.3', 'chosen'));

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



 <form  style="margin: auto; width: 56%;" id="UserViewProjectDetailsForm" method="GET" accept-charset="utf-8">
 <div style="display:none;">
    <input type="hidden" name="_method" value="GET">
    </div>

    <ul id='ser_ul'>
    <fieldset style ="padding: 24px 44px;">
    <font color  = "#205081" size="3"> <b><center>  Assign employees to projects</center> </b> </font>
    <li style="    margin-top: 40px; margin-left: -108px;">
                        <?php
                        $employeeProjects = array ($allProjects);
                        echo $this->Form->input('',
                            array(
                                'id'        => 'projectList',       'type'              => 'select',
                                'default'   => $selectedProjects,   'name'              => 'projectName',
                                'options'   => $employeeProjects,   'data-placeholder'  => 'Select Project',
                                'multiple'  => 'multiple',          'class'             => 'chosen-select',
                                'style'     => 'width: 416px; padding: 5px; height: 15px;'
                            ));
                        ?>
                    </li>

                      <li style="">
                      <?php
                      $fullEmployeeList = array (
                        'all' => 'All Employees',$allemployees
                         );

                      $employeeDefault = 'all';
                         if ($this->request->is('post')) {
                         $employeeDefault = $this->request->data['employeeId'];
                          }
                        echo $this->Form->input('Employee', array(
                             'id'            => 'employeeNames',         'type'              => 'select',
                             'default'       => $employeeList,           'name'           => 'employeeId',
                             'options'       => $fullEmployeeList,       'data-placeholder'  => 'Select Employee/Employees',
                             'multiple'      => 'multiple',              'class'             => 'chosen-select',
                             'style'         => 'width: 416px; padding: 5px; height: 15px;'
                        ));

                    ?>
                      </li>
                    <li>
                    <?php echo $this->Form->submit(__('Search', true), array('id' =>'search','div' => false, 'class' => 'subButton'));?>
                     </li>
                      <li style ="margin-top: -27px;">
                      <?php echo $this->Form->button(__('Assign'), array('id' =>'assign','div' => false, 'class' => 'addButton', 'type' => 'button'));?>
                      </li>

                     </fieldset>

                    </ul>

    </form>


<table style="margin-left: 155px;">
    <tr>
        <th>Employee Name</th>
        <th>Project Name</th>
        <th>Action</th>
    </tr>
    <tr>
        <?php foreach($project_detail as $project):?>
        <td><?php echo $project['User']['EmpName']; ?></td>
        <td><?php echo $project['pro']['pro_name']; ?></td>
        <td class="viewProjecttd">
        <div class="viewProjectDive">
            <button class="viewButton" data-tdid="<?= $project['emp']['id']; ?>" >Delete</button>
        </div>
         </td>

    </tr>
<?php endforeach; ?>
</table>
</div>


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

<script>
    $(function(){
      $('#employeeNames').chosen({});
      $('#projectNames').chosen({});
//      $('#employeeNames2').chosen({});
//      $('#employeeNames2').multipleSelect();
    })
</script>




