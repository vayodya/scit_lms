<meta charset="utf-8" />
<title>jQuery UI Datepicker - Icon trigger</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>
 <script src="/resources/demos/external/jquery.mousewheel.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css" />

<script>
$(function() {
$( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd",
showOn: "button",
buttonImage: "../images/iconCalendar.gif",
buttonImageOnly: true
});
$( "#datepicker2" ).datepicker({ dateFormat: "yy-mm-dd", 
showOn: "button",
buttonImage: "../images/iconCalendar.gif",
buttonImageOnly: true
});
});
</script>
    
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


	<h2><?php __('Work_from_homes');?></h2>
	
	<div class="filter">
	<?php
            
		echo $this->Form->create('Work_from_homes', array(
			'url' => array('action' => 'view_wfh_repo')
			));
		echo $this->Form->input('From Date',array('readonly'=>'readonly','input type'=>"text",'id'=>"datepicker",'name' => 'From_Date','dateFormat' =>'yy-mm-dd','required'=>'required','placeholder'=>'YYYY-MM-DD','title'=>'From Date is required.'));
 
                echo $this->Form->input('To Date',array('readonly'=>'readonly','input type'=>"text",'id'=>"datepicker2",'name' => 'To_Date','dateFormat' =>'yy-mm-dd','required'=>'required','placeholder'=>'YYYY-MM-DD','title'=>'From Date is required.'));

                $arrCategory=array('all'=>"All",'pending'=>"Pending",'accepted' =>"Accepted",'rejected'=>"Rejected");
            echo $this->Form->input('Status',array('options'=>$arrCategory, 'default' => 'all','name'=>'status','required'=>'required'));
		echo $this->Form->submit(__('Search', true), array('id' =>'search','div' => false));
		echo $this->Form->end();
                
	?>
	</div>

<?php if($inc == 1){?>
<table id ="user">
    <tr>
        <th>Employee Name</th> 
        <th>From Date</th>
        
        <th>To Date</th>
        
        <th>Wfh Time</th>
        
        <th>WFH Comment</th>
        
        <th>Wfh Status</th>
        
    </tr>

<?php  
    
     foreach ($search_result as $result){?> 
        
    
    <tr>
        
       
       <td>  <?php echo $result['Work_from_homes']['EmpName'];  ?></td>
        
        <td><?php echo $result['Work_from_homes']['From_Date']; ?></td>
        <td><?php echo $result['Work_from_homes']['To_Date']; ?></td>
        <td><?php echo $result['Work_from_homes']['wfh_Time']; ?></td>
        <td ><?php echo $result['Work_from_homes']['wfh_comment']; ?></td>
        <td><?php echo $result['Work_from_homes']['wfh_states']; }}

else{?>

<div id="search_err"> <?php echo "There are no search results";?> </div>
<?php }?></td>

</tr>
</table>


<div class="paging">
<?php 
        if(count($search_result)==0){}else{
//echo $this->Html->link('Download',array('controller' => 'Contacts','action' => 'download'), array('target'=>'_blank'));
$download_img = $this->Html->image('../images/downloadcsv.png',array('alt' => 'Export Report', 'title'=>'Export Report'));?>
<font color ="green"> <?php echo 'For Download click this '.$this->Html->link($download_img, array('controller' => 'Contacts','action' =>'download_adminview_wfh_filter',$f_date,$t_date,$status), array('escape' => false));?> </font> <?php
        }
?>
</div>
