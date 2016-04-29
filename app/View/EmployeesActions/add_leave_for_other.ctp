<meta charset="utf-8" />
<?php
echo $this->Html->css(array('jquery-ui-1.10.3'));

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



<div id="effect" class="ui-widget-content ui-corner-all">
    <h3 class="ui-widget-header ui-corner-all">Leave Balance</h3>
<!--    <p>-->
    <table id="blanceTable" >
        <tr>
            <th>Type</th>
            <th>Remaining</th>
            <th>Pending</th>
            <th>Taken</th>
        </tr>
        <tr>
            <td><b>Annual</b></td>
            <td id="leave_annual_remaining">0</td>
            <td id="leave_annual_pending">0</td>
            <td id="leave_annual_taken">0</td>
        </tr>
        <tr>
            <td><b>Casual</b></td>
            <td id="leave_casual_remaining">0</td>
            <td id="leave_casual_pending">0</td>
            <td id="leave_casual_taken">0</td>
        </tr>
        <tr>
            <td><b>Sick</b></td>
            <td id="leave_sick_remaining">0</td>
            <td id="leave_sick_pending">0</td>
            <td id="leave_sick_taken">0</td>
        </tr>
        <tr style="background-color:#006600 ;">
            <td><b>Lieu</b></td>
            <td id="leave_live_remaining">0</td>
            <td id="leave_live_pending">0</td>
            <td id="leave_live_taken">0</td>
        </tr>
        <tr style="background-color:#990000 ;">
            <td><b>No Pay</b></td>
            <td id="leave_nopay_remaining">0</td>
            <td id="leave_nopay_pending">0</td>
            <td id="leave_nopay_taken">0</td>
        </tr>
    </table>
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

<?php
echo $this->Form->create('EmployeesActions', array('action' => 'addLeaveForOther', 'id' => 'addLeaveForOtherForm', 'name' => 'EmployeesActions', 'style' => 'font-size: 12px;'));
?> 
<div id = "latitle">
    <center>
        <p id="pl">
            <font color ="white" size="3px">
                <?php echo __('Leave Application'); ?>
            </font>
        </p>
    </center>
</div>

<div id='w_border'>
    <div id='w_f_body'>
        <div style="margin-bottom: 0px;">
            <label>Employee</label>
            <select name="EmpId" size="1" style="width: 289px; height: 30px;" id="EmpId" >
                <option value = "0">Select Employee</option>
                <?php foreach ($employees as $key => $value): ?>
                    <option value = "<?php echo $value['User']['EmpId']; ?>">
                        <?php echo $value['User']['EmpName']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="margin-bottom: 0px;">
            <label>Leave Type </label>
            <select name="Leave_Type" id ="ltype" size="1" style="width: 78px; height: 30px;"></br>
                <option value="annual">Annual</option>
                <option value="sick">Sick</option>
                <option selected value="casual">Casual</option>
                <option value="live">Lieu</option>
                <option value="nopay">No Pay</option>
            </select>
        </div>
        <div>
            <label>Leave Time</label>
            <select name="Leave_Time" size="1" style="width: 78px; height: 30px;" id="ltime" >
                <option value = "fullday">Full Day</option>
            </select>
        </div>
        
        <div id="aling_fr">
            <div id='sd_lbl'>Start Date</div>
            <?php echo $this->Form->input('Start Date', 
                    array('type' => "text", 'id' => "datepicker", 'name' => 'From_Date', 
                        'label' => '', 'dateFormat' => 'yy-mm-dd', 'required' => 'required',
                        'placeholder' => 'YYYY-MM-DD', 'title' => 'From Date is required.')); ?>
        </div>
        <div id='ed_lbl'>End Date</div>
            <?php echo $this->Form->input('End Date', 
                    array('type' => "text", 'id' => "datepicker2", 
                        'name' => 'To_Date', 'label' => '', 
                        'dateFormat' => 'yy-mm-dd',
                        'placeholder' => 'YYYY-MM-DD', 'title' => 'To Date is required.'));
            ?>
        <div id="aling_wd">
            <ul id ="balance11" style="margin-left: 0px;">
                <li>
                    <?php echo $this->Form->input('', array('input type' => "hidden", 
                        'id' => "remaining1", 'class' => 'Re_fld', 
                        'readonly' => 'readonly')); ?>
                </li>
                <li>
                    <div id='re_lbl'>Remaining</div>
                    <div id="aling_re" style="margin-bottom: -7px;">
                        <?php echo $this->Form->input('Remaining', array('input type' => "text", 
                            'label' => '', 'id' => "remaining", 'class' => 'Re_fld', 
                            'readonly' => 'readonly', 'style' => 'height: 17px;')); ?>
                    </div>
                </li>
                <li>
                    <div id='pe_lbl'>Pending</div>
                    <div id="aling_pe" style="margin-bottom: -4px;">
                        <?php echo $this->Form->input('Pending', 
                                array('input type' => "text", 'label' => '', 'id' => "pending", 
                                    'class' => 'Pe_fld', 'readonly' => 'readonly', 'style' => 'height: 17px;')); ?>
                    </div>
                </li>
                <li>
                    <div id='du_lbl'>Duration</div>
                    <div id="aling_du">
                        <?php echo $this->Form->input('Duration', 
                                array('input type' => "text", 'label' => '', 'id' => "valid1", 
                                    'class' => 'workinD_txtArea', 'readonly' => 'readonly', 
                                    'style' => 'margin-left: 70px; height: 17px;')); ?>
                    </div>
                </li>
            </ul>
        </div>

        <div id='pur_ara'>
            <?php 
            echo $this->Form->input('Purpose', array('type' => 'textarea', 'name' => 'Leave_comment', 'id' => 'Leave_comment')); ?></div><?php
            echo $this->Form->input('Leave Status', array('type' => 'hidden', 'name' => 'Leave_states', 'default' => 'pending'));
            echo $this->Form->input('accept_id', array('type' => 'hidden', 'name' => 'accept_id', 'default' => 10));
            ?>
    </div>
</div>

<div id='w_border'>
    <div id='aling_re_btn'>
        <?php echo $this->Form->button('Reset', array('type' => 'reset', 'value' => 'reset', 'class' => 'reser_but', 'id' => 'resetFormBtn')); ?>
    </div>
    <div id='apl_but1'>
        <?php echo $this->Form->end('Apply', array('class' => 'send_but', 'id' => 'submitFormBtn', 'style' => 'height: 36px;')); ?>
    </div>
    <div class="post">
    </div>
</div>

<script>
    $(function() {
        var eventHolidays = <?php echo json_encode($eventHolidays); ?>;
        
        $(document).ready(function() {
        });
  
        $( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd",
            showOn: "button",
            minDate: null,
            buttonImage: "../images/iconCalendar.gif",
            buttonImageOnly: true,
            beforeShowDay: function(date) {
                return checkForHoliday(date);
            },
            onSelect: function(dateText) {
                calculateLeaveDuration();
                populateEndDate();
            }
        }).on("change", function() {
            calculateLeaveDuration();
            populateEndDate();
        });     
        
        $( "#datepicker2" ).datepicker({ dateFormat: "yy-mm-dd", 
            showOn: "button",
            minDate: null,
            buttonImage: "../images/iconCalendar.gif",
            buttonImageOnly: true,
            beforeShowDay: function(date) {
                var startDateStr = $('#datepicker').val();
                if (startDateStr) {
                    var startDate = new Date(startDateStr + ' 00:00:00');
                    if (date < startDate) {
                        return [false];
                    }
                }
                return checkForHoliday(date);
            },
            onSelect: function(dateText) {
                calculateLeaveDuration();
            }
        }).on("change", function() {
            calculateLeaveDuration();
        }); 
        
        var checkForHoliday = function(date) {
            if (date.getDay() === 0 || date.getDay() === 6) {
                return [false];
            }
            
            var isEventHoliday = true;
            $.each(eventHolidays, function(key, value) {
                var eventDate = new Date(value + ' 00:00:00');
                if (eventDate.getTime() === date.getTime()) {
                    isEventHoliday = false;
                    return;
                }
            });
            return [isEventHoliday];
        };
        
        $('#EmpId').change(function() {
            if ($('#EmpId :selected').val() !== '0') {
                $.ajax({
                    url : base_url + 'EmployeesActions/getLeaveAmountByEmpId',
                    type : 'POST',
                    dataType : 'json',
                    data:{
                        empId : $('#EmpId :selected').val()
                    },
                    success : function(response){
                        $('#leave_annual_remaining').text(response.data.leaves.annual.remainging);
                        $('#leave_annual_pending').text(response.data.leaves.annual.pending);
                        $('#leave_annual_taken').text(response.data.leaves.annual.accepted);

                        $('#leave_casual_remaining').text(response.data.leaves.casual.remainging);
                        $('#leave_casual_pending').text(response.data.leaves.casual.pending);
                        $('#leave_casual_taken').text(response.data.leaves.casual.accepted);

                        $('#leave_sick_remaining').text(response.data.leaves.sick.remainging);
                        $('#leave_sick_pending').text(response.data.leaves.sick.pending);
                        $('#leave_sick_taken').text(response.data.leaves.sick.accepted);

                        $('#leave_live_remaining').text(response.data.leaves.live.remainging);
                        $('#leave_live_pending').text(response.data.leaves.live.pending);
                        $('#leave_live_taken').text(response.data.leaves.live.accepted);

                        $('#leave_nopay_remaining').text(response.data.leaves.nopay.remainging);
                        $('#leave_nopay_pending').text(response.data.leaves.nopay.pending);
                        $('#leave_nopay_taken').text(response.data.leaves.nopay.accepted);
                        
                        $('#ltype').trigger("change");  // change();
                    },
                    error : function(jqXHR, textStatus, errorThrown ) {
                        alert("Connection error.");
                    }
                });
            } else {
                resetData();
            }
        });
        
        $('#ltype').change(function() {
            var leaveType = $('#ltype option:selected').val();
            
            $('#remaining').val($('#leave_' + leaveType + '_remaining').text());
            $('#pending').val($('#leave_' + leaveType + '_pending').text());
            
            $('#ltime').empty();
            
            $('#ltime').append('<option value="fullday">Full Day</option>');
            if (leaveType !== 'annual') {
                $('#ltime').append(
                        '<option value="1sthalf">1st Half</option>'
                        + '<option value="2ndhalf">2nd Half</option>'
                        );
            }
            
            $('#ltime').trigger("change");
        });
        
        $('#ltime').change(function() {
            $('#datepicker2').datepicker('option', { disabled: false } );
            
            if ($('#ltime option:selected').val() !== 'fullday') {
                $('#datepicker2').datepicker('option', { disabled: true } );
            }
            populateEndDate();
        });
        
        var calculateLeaveDuration = function() {
            if ($('#datepicker').val() && $('#datepicker2').val()) {
                var startDate = new Date($('#datepicker').val() + ' 00:00:00');
                var endDate = new Date($('#datepicker2').val() + ' 00:00:00');
                
                if (startDate <= endDate) {
                    var count = 0;
                    for (var cDate = startDate; cDate <= endDate; cDate) {
                        if (checkForHoliday(cDate)[0]) {
                            count++;
                        }
                        cDate = new Date(cDate.getTime() + (1000*60*60*24));
                    }
                    
                    if ($('#ltime option:selected').val() !== 'fullday') {
                        count = 0.5;
                    }
                    $('#valid1').val(count);
                } else {
                    // Do nothing.
                }
            }
        };
        
        populateEndDate = function() {
            if ($('#ltime option:selected').val() !== 'fullday') {
//                $('#datepicker2').val($('#datepicker').val());
                $('#datepicker2').datepicker('setDate', new Date($('#datepicker').val()));
            }
            calculateLeaveDuration();
        };
        
        resetData = function() {
            $('#leave_annual_remaining').text(0);
            $('#leave_annual_pending').text(0);
            $('#leave_annual_taken').text(0);

            $('#leave_casual_remaining').text(0);
            $('#leave_casual_pending').text(0);
            $('#leave_casual_taken').text(0);

            $('#leave_sick_remaining').text(0);
            $('#leave_sick_pending').text(0);
            $('#leave_sick_taken').text(0);

            $('#leave_live_remaining').text(0);
            $('#leave_live_pending').text(0);
            $('#leave_live_taken').text(0);

            $('#leave_nopay_remaining').text(0);
            $('#leave_nopay_pending').text(0);
            $('#leave_nopay_taken').text(0);          
            
            $('#ltype option').prop('selected', false);
            $('#ltype option:first').prop('selected', true);
            $('#ltype').trigger("change");
            
            $('#datepicker').val('');
            $('#datepicker2').val('');
            $('#remaining').val('');
            $('#pending').val('');
            $('#valid1').val('');
            $('#Leave_comment').val('');
        };
        
        $('input[type="submit"]').click(function(e) {
            e.preventDefault();
            
            var errorMsg = '';
            if ($('#EmpId option:selected').val() === '0') {
                errorMsg += '\nPlease select the employee';
            }
            if (!$('#datepicker').val()) {
                errorMsg += '\nPlease select the start date';
            }
            if ($('#ltime option:selected').val() === 'fullday' && !$('#datepicker2').val()) {
                errorMsg += '\nPlease select the end date.';
            }
            if (!$('#Leave_comment').val()) {
                errorMsg += '\nPurpose can not be empty.';
            }
            
            if (errorMsg) {
                alert(errorMsg);
            } else {
                $('#addLeaveForOtherForm').submit();
            }
        });
    });
</script>