<?php
echo $this->Html->css(array('jquery-ui-1.10.3'));
echo $this->Html->script(array('jquery-ui-1.10.3'));
?>

<script>
var jArray= <?php echo json_encode($holidays ); ?>;
 
var holidays = new Array();
for (var i = 0; i < jArray.length; i++) {
	holidays[i] = jArray[i].Event.start;
    //Do something
}

function enableAllTheseDays(date) {
	var sdate = $.datepicker.formatDate( 'yy-mm-dd', date);
	if (date.getDay() == 0 || date.getDay() ==6) {
    	return [false];
	}
	if($.inArray(sdate, holidays) != -1) {
    	return [false];
	}
        
	return [true];
}

$(function() {
    document.getElementById("remaining").value="";
	document.getElementById("pending").value="";
    document.getElementById("valid1").value="";

    var opt = $('#ltype').val();
    
    $('#datepicker').datepicker({dateFormat: 'yy-mm-dd', 
        showOn: "button",
        minDate:new Date(new Date().getFullYear(), 1 - 1, 1),
        beforeShowDay: enableAllTheseDays,
        buttonImage: "../images/iconCalendar.gif",
        buttonImageOnly: true
	});

    $( "#datepicker2" ).datepicker({ dateFormat: "yy-mm-dd", 
        showOn: "button",
        minDate:null,
        beforeShowDay: function(date) {
            var sdate = $.datepicker.formatDate( 'yy-mm-dd', date);
            if (sdate <  $('#datepicker').val()) {
                return [false];
            }
            return enableAllTheseDays(date);
        },
        buttonImage: "../images/iconCalendar.gif",
        buttonImageOnly: true
	});
});
</script>

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

<script>
$(function() {
	// run the currently selected effect
	function runEffect() {
		// get effect type from
		var selectedEffect = $( "#effectTypes" ).val();
		// most effect types need no options passed by default
		var options = {};
		// some effects have required parameters
		if ( selectedEffect === "scale" ) {
			options = { percent: 0 };
		} else if ( selectedEffect === "size" ) {
			options = { to: { width: 100, height: 60 } };
		}
		// run the effect
		$( "#effect" ).toggle( selectedEffect, options, 500 );
	};
	
	// set effect from select menu value
	$( "#button" ).click(function() {
		runEffect();
		return false;
		x = 1;
	});
});
</script>

<script>
$(document).ready(function(){
	$("#blanceTable").show();
	
  	$('#effect').click(function(){
    	if($('#blanceTable').is(':visible'))
	    	$('#blanceTable').show();
		else
    		$('#blanceTable').show();
  	});

	getBalance();
});

</script>

<?php echo $this->element("menu"); ?>
<div id="login">
    <div id="semilog">
    	<?php echo '&nbsp;'.$loguser.' '.'&nbsp;';?>
    </div>
    <?php
    $de_img = $this->Html->image('../images/Logout-Icon.jpg',array('alt' => 'Sign Out', 'title'=>'Sign Out'));
    echo $this->Html->link($de_img, array('controller' => 'Users','action' => 'logout'), array('escape' => false));
    $delete_img = $this->Html->image('../images/User_Edit.jpg',array('alt' => 'Delete', 'title'=>'Edit Profile'));
    echo $this->Html->link($delete_img, array('action' => 'edit','controller' => 'Users','action' => 'profileedit'), array('escape' => false)).'&nbsp;';
    $notify_img = $this->Html->image('../images/imagesnoti.png',array('alt' => 'Notify', 'title'=>'Notification Setting'));
    echo $this->Html->link($notify_img, array('action' => 'edit','controller' => 'Users','action' => 'email_notify'), array('escape' => false));
    ?>
</div>



<div id="effect" class="ui-widget-content ui-corner-all">
	<h3 class="ui-widget-header ui-corner-all">Leave Balance</h3>
	<p>
		<?php 
		echo '<table id="blanceTable" ><tr><th>Type</th><th>Remaining</th><th>Pending</th><th>Taken</th></tr>';
		echo '<tr><td><b>Annual</b><td>'.($no_ann_lv-$a2-$aa2).'</td><td>'.$aa2.'</td><td>'.$a2.'</td></tr>'; 
		echo '<tr><td><b>Casual</b><td>'.($no_cas_lv-$a3-$aa3).'</td><td>'.$aa3.'</td><td>'.$a3.'</td></tr>';
		echo '<tr><td><b>Sick</b><td><!--'.($no_sick_lv-$a1-$aa1).'-->Not Define</td><td>'.$aa1.'</td><td>'.$a1.'</td></tr>';
		echo '<tr style="background-color:#006600 ;"><td><b>Lieu</b><td>'.($no_liv_lv-$a5-$aa5).'</td><td>'.$aa5.'</td><td>'.$a5.'</td></tr>';
		echo '<tr style="background-color:#990000 ;"><td><b>No Pay</b><td>Not Define</td><td>'.$aa4.'</td><td>'.$a4.'</td></tr>';
		echo '</table>';
		?>
	</p>
</div>

</body>

<script type = "text/javascript">

    function setOptions(opt) {
        var select2 = document.leave_record.Leave_Time;
        select2.options.length = 0;

        if (opt == "annual"){
            select2.options[select2.options.length] = new Option('Full Day',name='fullday');
        }

        if ((opt == "sick") || (opt == "casual") || (opt == "nopay") || (opt == "live")){
            select2.options[select2.options.length] = new Option('Full Day',name='fullday');
            select2.options[select2.options.length] = new Option('1st Half',name='1sthalf');
            select2.options[select2.options.length] = new Option('2nd Half',name='2ndhalf');
        }
    }

    function setType(){

        var select2 = document.getElementById("ltime");
        select2.options.length = 0;    
        var opt = $('#ltype').val();

        if(opt == "sick"){
            $('#datepicker').datepicker('option', {minDate:null});
            $('#datepicker2').datepicker('option', {minDate:null});
        }else{
            $('#datepicker').datepicker('option', {minDate:null});
            $('#datepicker2').datepicker('option', {minDate:null});
        }
        
        var fromDate = $('#datepicker').val();
        var toDate = $('#datepicker2').val();

        if (opt == "annual"){
            select2.options[select2.options.length] = new Option('Full Day',name='fullday');
        } else if ((opt == "sick") || (opt == "casual") || (opt == "nopay") || (opt == "live")) {
            
            if(fromDate === toDate){
                select2.options[select2.options.length] = new Option('Full Day',name='fullday');
                select2.options[select2.options.length] = new Option('1st Half',name='1sthalf');
                select2.options[select2.options.length] = new Option('2nd Half',name='2ndhalf');
            }else{
                select2.options[select2.options.length] = new Option('Full Day',name='fullday');
            }
        }

        getValidDays();
        getBalance();
    }

</script>
<?php 
if(count($error)>0){

 ?> 
 
 <div class="alert alert-block" >
  <button type="button" class="close" data-dismiss="alert">
   &times;
  </button>
  
 <font color="red"> <?php foreach($error as $err){
echo $err;
echo "</br>";
}?></font>
 </div>
 <?php } ?>
<?php 
if(isset($_SESSION['rand'])){?>

<div class="alert alert-block" >
  <button type="button" class="close" data-dismiss="alert">
   &times;
  </button>
  
  <?php echo  $_SESSION['rand'];
unset($_SESSION['rand']);?>
 </div>
 <?php } ?>


<?php echo $this->Form->create('leave_records',array('action' => 'add','name' =>'leave_record' )); ?> 
<div id = "latitle">
<center><p id="pl"><font color ="white" size="3px"><?php echo __('Leave Application'); ?></font></p></center></div>

<div id='w_border'><div id='w_f_body'>
	<label>Leave Type </label>
	<select name="Leave_Type" id ="ltype" size="1" style="width: 78px" onchange="setType()"></br>
		<option value="annual">Annual</option>		
<!-- 		<option value="sick">Sick</option> -->
		<option value="casual" selected="selected">Casual</option>
		<option value="live">Lieu</option>
		<option value="nopay">No Pay</option>
	</select>

	<?php echo "</br>"; ?>
	<div id="aling_re"></div>
	<div id="aling_pe"></div>
	<div id="aling_fr">
		<div id='sd_lbl'>Start Date</div>
		<?php  echo $this->Form->input('Start Date',
				array('input type'=>"text",'id'=>"datepicker",'name' => 'From_Date','label' =>'','dateFormat' =>'yy-mm-dd',
						'required' => true,'placeholder'=>'YYYY-MM-DD','title'=>'From Date is required.','onchange' =>'setType()')); ?>
	</div>
	<div id='ed_lbl'>End Date</div>
	<?php echo $this->Form->input('End Date',array('input type'=>"text",'id'=>"datepicker2",'name' => 'To_Date','label' =>'','dateFormat' =>'yy-mm-dd','placeholder'=>'YYYY-MM-DD','required' => true,'title'=>'To Date is required.','onchange' => 'setType()')); ?>
<div id="aling_wd"><ul id ="balance11"><li><?php echo $this->Form->input('',array('input type'=>"hidden",'id'=>"remaining1",'class'=>'Re_fld','readonly' => 'readonly'));?></li></ul>
<li><div id='re_lbl'>Remaining</div><div id="aling_re"><?php echo $this->Form->input('Remaining',array('input type'=>"text",'label' =>'','id'=>"remaining",'class'=>'Re_fld','readonly' => 'readonly'));?></div></li></ul>
<li><div id='pe_lbl'>Pending</div><div id="aling_pe"><?php echo $this->Form->input('Pending',array('input type'=>"text",'label' =>'','id'=>"pending",'class'=>'Pe_fld','readonly' => 'readonly'));?></div></li>
<li><div id='du_lbl'>Duration</div><div id="aling_du"><?php echo $this->Form->input('Duration',array('input type'=>"text",'label' =>'','id'=>"valid1",'class'=>'workinD_txtArea','readonly' => 'readonly', 'style' => 'margin-left: 70px;'));?></div></li></ul></div><?php
 ?> 
 

<div id="aling_lt"><label>Leave Time</label><select name="Leave_Time" size="1" style="width: 78px" id="ltime" >
<option value = "fullday">Full Day</option>
</select></div>
<div id='pur_ara'>
<?php
 echo $this->Form->input('Purpose', array('type' => 'textarea','required' => true, 'name'=>'Leave_comment'));?></div><?php
        echo $this->Form->input('Leave Status', array('type' => 'hidden','name'=>'Leave_states','default' => 'pending'));
        echo $this->Form->input('accept_id', array('type' => 'hidden','name'=>'accept_id','default' => 10));?></div></div><div id='w_border'><div id='aling_re_btn'><?php
echo $this->Form->button('Reset', array('type'=>'reset','value'=>'reset','class'=>'reser_but'));   ?></div>
 <div id='apl_but1'><?php echo $this->Form->end('Apply',array('class'=>'send_but')); ?></div>
<div class="post">
    	
        	
		</div>
 				
</div>

<script type="text/javascript">

$(document).ready(function(){
	$('form').submit( function() {
    	return validateForm(this, rules, eval({"messageId":"messages"}));
    });
});
    
var rules = eval([]);

    function getValidDays(){
        var fromDate = $('#datepicker').val();
        var toDate = $('#datepicker2').val();

        var e = document.getElementById("ltype");
        var type = e.options[e.selectedIndex].value ;
    
        if (fromDate && toDate) {
            fromDateObj = new Date(fromDate + ' 00:00:00');
            toDateObj = new Date(toDate + ' 00:00:00');
            
            if (toDateObj >= fromDateObj) {
                var duration = 0;
                
                for (var selectedDate = fromDateObj; selectedDate <= toDateObj; selectedDate) {
                    if (enableAllTheseDays(selectedDate)[0]) {
                        duration++;
                    }
                    var selecctedDateInMili = selectedDate.getTime() + 1000*60*60*24;
                    selectedDate = new Date(selecctedDateInMili);
                }
                
                $('#valid1').val(duration);
            }
        }
        
        setOptions($('#ltype option:selected').val());
    }

	function getBalance() {
        var e = document.getElementById("ltype");
        var type = e.options[e.selectedIndex].value ;
        
       if(type != "" ){
    		$.ajax({
		        url : '../LeaveRecords/leave_balance',
		        type: 'POST',
		        dataType: 'json',
		        data:{ltype:type},
		        success : function(response) {
		            var accepted = 0;
		            var pending = 0;
		            var total = 0;
                
		            if (response.applied_leaves) {
		                $.each(response.applied_leaves, function(i, item) {
		                   if (item.leave_record.Leave_states === 'accepted') {
		                       accepted = item[0].sumOfLeaves;
		                   } else if (item.leave_record.Leave_states === 'pending') {
		                       pending = item[0].sumOfLeaves;
		                   }                    
		                });
		            }
            
		            switch(type) {
		                case 'annual': 
		                    total = response.user_leave_info.User.nof_ann_lv;
		                    break;
		                case 'casual': 
		                    total = response.user_leave_info.User.nof_cas_lv;
		                    break;
		                case 'live': 
		                    total = response.user_leave_info.User.nof_liv_lv;
		                    break;
		                case 'sick': 
		                    total = response.user_leave_info.User.nof_sick_lv;
		                    break;
		                default :
		                    total = 0;
		            }
		            
		            total = (total) ? total : 0;
            
		            var remainings = total - accepted - pending;
		            remainings = (remainings > 0) ? remainings : 0;
            
		            $('#pending').val(pending);
		            $('#remaining').val(remainings);
		        }
    		});
		}
	}
</script> 

<style type="text/css">


.post { margin: 0 auto; padding-bottom: 50px; float: left; width: auto; }

.btn-sign {
	width:186px;
	margin-top:-44px;
	margin-left:220px;
	
	padding:8px;
	border-radius:5px;
	background: -moz-linear-gradient(center top, #00c6ff, #018eb6);
    background: -webkit-gradient(linear, left top, left bottom, from(#00c6ff), to(#018eb6));
	background:  -o-linear-gradient(top, #00c6ff, #018eb6);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='#00c6ff', EndColorStr='#018eb6');
	text-align:center;
	font-size:12px;
	color:#fff;
	text-transform:uppercase;
}

.btn-sign a { color:#fff; text-shadow:0 1px 2px #161616; }

#mask {
	display: none;
	background: #000; 
	position: fixed; left: 0; top: 0; 
	z-index: 10;
	width: 100%; height: 100%;
	opacity: 0.8;
	z-index: 999;
}

.login-popup{
	display:none;
	background: yellow;
	padding: 10px; 	
	border: 2px solid #ddd;
	float: left;
	font-size: 1.2em;
	position: absolute;
	left: 50%;
        height:500px;


top:334px;
overflow:scroll;

	z-index: 99999;
	box-shadow: 0px 0px 20px #999;
	-moz-box-shadow: 0px 0px 20px #999; /* Firefox */
    -webkit-box-shadow: 0px 0px 20px #999; /* Safari, Chrome */
	border-radius:3px 3px 3px 3px;
    -moz-border-radius: 3px; /* Firefox */
    -webkit-border-radius: 3px; /* Safari, Chrome */

}

img.btn_close {
	float: right; 
	margin: -28px -28px 0 0;
}



form.signin .textbox label { 
	display:block; 
	padding-bottom:7px; 
}

form.signin .textbox span { 
	display:block;
}

form.signin p, form.signin span { 
	color:#999; 
	font-size:11px; 
	line-height:18px;
} 

form.signin .textbox input { 
	background:#666666; 
	border-bottom:1px solid #333;
	border-left:1px solid #000;
	border-right:1px solid #333;
	border-top:1px solid #000;
	color:#fff; 
	border-radius: 3px 3px 3px 3px;
	-moz-border-radius: 3px;
    -webkit-border-radius: 3px;
	font:13px Arial, Helvetica, sans-serif;
	padding:6px 6px 4px;
	width:200px;
}

form.signin input:-moz-placeholder { color:#bbb; text-shadow:0 0 2px #000; }
form.signin input::-webkit-input-placeholder { color:#bbb; text-shadow:0 0 2px #000;  }

.button { 
	background: -moz-linear-gradient(center top, #f3f3f3, #dddddd);
	background: -webkit-gradient(linear, left top, left bottom, from(#f3f3f3), to(#dddddd));
	background:  -o-linear-gradient(top, #f3f3f3, #dddddd);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='#f3f3f3', EndColorStr='#dddddd');
	border-color:#000; 
	border-width:1px;
	border-radius:4px 4px 4px 4px;
	-moz-border-radius: 4px;
    -webkit-border-radius: 4px;
	color:#333;
	cursor:pointer;
	display:inline-block;
	padding:6px 6px 4px;
	margin-top:10px;
	font:12px; 
	width:214px;
}

#saveme{
	background-color:green;
}

#closeme{
	background-color:red;
	margin-left:284px;
}

.button:hover { background:#ddd; }

</style>
<script>
$(document).ready(function() {
	$('a.login-window').click(function() {
		
		// Getting the variable's value from a link 
		var loginBox = $(this).attr('href');

		//Fade in the Popup and add close button
		$(loginBox).fadeIn(300);
		
		//Set the center alignment padding + border
		var popMargTop = ($(loginBox).height() + 24) / 2; 
		var popMargLeft = ($(loginBox).width() + 24) / 2; 
		
		$(loginBox).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		// Add the mask to body
		$('body').append('<div id="mask"></div>');
		$('#mask').fadeIn(300);
		
		return false;
	});
	
	$( "#closeme" ).click(function() {
		$( ".login-popup" ).hide();
		$( "#mask" ).hide();

		return false;
	});

        $( "#saveme" ).click(function() {
            var selected = new Array();

            selected.push(<?php echo $owner; ?>);

            $("input:checkbox[type=checkbox]:checked").each(function() {
                    selected.push($(this).val());
            });

            $.ajax({
                url : '../LeaveRecords/settings',
                type: 'POST',
                data:{arr:selected},
                success : function(response){
                }
            });
            
            $( ".login-popup" ).hide();
            $( "#mask" ).hide();
            return false;
        });
});
</script>


        
        <div id="login-box" class="login-popup">
        <button id = "closeme"> close </button>
<button id = "saveme">save</button>
        <ul>
         <?php foreach ($users as $emp){ ?>
            <li><input id="UserUser4" type="checkbox" value=" <?php echo $emp['User']['EmpId']; ?>" /><p><?php echo $emp['User']['EmpName']; ?> </p> </li>
          <?php } ?>
          </ul>
		</div>

<div id="free_sp_fr"></div>