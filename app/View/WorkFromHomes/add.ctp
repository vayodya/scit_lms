<?php 
echo $this->Html->css(array('jquery-ui-1.10.3'));
?>

  <!-- app/View/Users/add.ctp -->

<?php echo $this->element("menu");

?>
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

 <?php   if(count($error)>0){

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

<?php 
echo $this->Form->create('work_from_homes',array('action' => 'add'));?>
<div id = "latitle">
<center><p id="pl"><font color ="white" size="3px"><?php echo __('Apply Work from home'); ?></font></p></center></div>

 
 <div id ="divError"></div>
 <div id='w_border'>
     <div id='w_f_body'>
 <?php
 echo $this->Form->input('From Date',array('input type'=>"text",'id'=>"datepicker",'name' => 'From_Date','dateFormat' =>'yy-mm-dd','required'=>'required','placeholder'=>'YYYY-MM-DD','title'=>'From Date is required.','onchange' => 'setType()'));
 
 echo $this->Form->input('To Date',array('input type'=>"text",'id'=>"datepicker2",'name' => 'To_Date','dateFormat' =>'yy-mm-dd','required'=>'required','placeholder'=>'YYYY-MM-DD','title'=>'From Date is required.','onchange' => 'setType()'));

        //echo $this->Form->input('To Date', array('name' => 'To_Date','dateFormat' =>'YMD','required'=>'required','placeholder'=>'YYYY-MM-DD','title'=>'To Date is required.','empty' => true, 'minYear' => date('Y')-60,'maxYear' => date('Y')+1));
 
 ?>
 
<div id ="validDays" style="margin-left: 120px;"></div>
<label> WFH Time</label><select name="wfh_Time" size="1"  id="ltime">
<option value="fullday">Full Day</option>
<option value="1sthalf">1st Half</option>
<option value="2ndhalf">2nd Half</option>

</select>


 <?php 
        echo $this->Form->input('Note', array('type' => 'textarea','required' => TRUE,'name'=>'wfh_comment'));
        echo $this->Form->input('WFH Status', array('type' => 'hidden','name'=>'wfh_states','default' => 'pending')); 
        echo $this->Form->input('accept_id', array('type' => 'hidden','name'=>'accept_id','default' => 10));?></div></div><div id='w_border'><div id='aling_re_btn'><?php
 echo $this->Form->button('Reset', array('type'=>'reset','value'=>'reset','class'=>'reser_but'));   ?></div><?php    
 echo $this->Form->end('Apply',array('class'=>'send_but')); 
   

?>
        </div>
<div id="free_sp_fr"></div>

<?php
    echo $this->Html->script(array('jquery-ui-1.10.3'));
?>

<script>
    var jArray= <?php echo json_encode($holidays ); ?>;
    //alert(jArray[0].Event.start);

    var holidays = new Array();
    for (var i = 0; i < jArray.length; i++) {
        holidays[i] = jArray[i].Event.start;
        //Do something
    }

    $(function() {
        function enableAllTheseDays(date) {
            var sdate = $.datepicker.formatDate( 'yy-mm-dd', date)
            //console.log(sdate)
             if (date.getDay() == 0 || date.getDay() ==6) {
                return [false];
            }
            if($.inArray(sdate, holidays) != -1) {
                return [false];
            }
            return [true];
        }

        $('#datepicker').datepicker({dateFormat: 'yy-mm-dd', 
            showOn: "button",
            beforeShowDay: enableAllTheseDays,
            buttonImage: "../images/iconCalendar.gif",
            buttonImageOnly: true,
            onSelect: function(selectedDateText) {
            	$("#datepicker2").datepicker("option", "disabled", false);
            	$("#datepicker2").datepicker('option', 'minDate', new Date(selectedDateText));
            }
		});

        $( "#datepicker2" ).datepicker({ dateFormat: "yy-mm-dd", 
            showOn: "button",
            beforeShowDay: enableAllTheseDays,
            buttonImage: "../images/iconCalendar.gif",
            buttonImageOnly: true,
            disabled: true     
        });
    });

    function setType(){
        var select2 = document.getElementById("ltime");
        select2.options.length = 0;    

        var fromDate = $('#datepicker').val();
        var toDate = $('#datepicker2').val();

        if(fromDate === toDate){
            select2.options[select2.options.length] = new Option('Full Day',name='fullday');
            select2.options[select2.options.length] = new Option('1st Half',name='1sthalf');
            select2.options[select2.options.length] = new Option('2nd Half',name='2ndhalf');
        }else{
            select2.options[select2.options.length] = new Option('Full Day',name='fullday');
        }
        if(fromDate !="" && toDate !="" && fromDate <= toDate){
            validateDates();
        }else
        // console.log(sdate)
        document.getElementById('validDays').innerHTML = "Invalid";   
    }
</script>


<script>
        
//function validatingDates(){
//    var fromDate = $('#datepicker').val();
//    var toDate = $('#datepicker2').val();
//    
//    if(fromDate != "" && toDate != ""){
//        $.ajax({
//            url     : '../WorkFromHomesController/dateCalculate',
//            type    : 'POST',
//            dataType: 'json',
//            data    : (fDate : fromDate, tDate : toDate),
//            success : function(response){
//                days = "Valid Days : ".response;
//                document.getElementById('valideDays').style.display = "inline-block";
//                document.getElementById('validDays').innerHTML = days;
//            }
//        });
//    }else{
//       //do nothing 
//    }
//    
//}        

function validateDates(){
	var fromDate = $('#datepicker').val();
	var toDate = $('#datepicker2').val();
	

       if(fromDate != "" && toDate !=""){
         $.ajax({
      url : '../Users/email_profileedit',
      type: 'POST',
	 data:{fd : fromDate, td : toDate},
      success : function(response){
          days = "Valid Days :";
          days +=response;
        document.getElementById('validDays').style.display = "inline-block";
        document.getElementById('validDays').innerHTML = days;
      },
      error: function (xhr, ajaxOptions, thrownError) {
      }
  });
}
}

</script>